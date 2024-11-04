<?php
set_time_limit (180);
error_reporting(E_ALL^E_WARNING^E_NOTICE^E_DEPRECATED);
ini_set('display_errors','on');


use DiDom\Document;


class EbayParser extends AdminTab
{
    private $banlist = array();
    private $sellerMinPositive = 97;
    private $sellerMinFeedback = 1000;
    private $misha_prib = 100;
    private $min_prib = 100;
    private $max_prib = 1000;


    /**
     пробелы в запросе заменить на +
    _fcid=186 — испания
    _stpos=03000 — аликанте
    _sacat=6000 и _osacat=6000 —  motors
     LH_ItemCondition=3 - новый
     &rt=nc&LH_BIN - BIN
     &_sop=15 - сортировка Price + Shipping:lowers first
     &_udlo= мин цена
     &_udhi= макс цена
     */
    const EBAY_MOTOR_LIST_URL = 'https://www.ebay.com/sch/i.html?_stpos=03000&_fcid=186&LH_ItemCondition=3&rt=nc&LH_BIN=1&_stpos=03000&_fcid=186&_osacat=6000&_sacat=6000&_sop=15&_nkw=';
    const EBAY_ITEM_URL = 'https://www.ebay.com/itm/';


//    public function __construct()
//    {
//        parent::__construct();
//    }

    function parse($request, $findpair = 0) {
        $curl = self::request($request, 1);
        if (!empty($curl['errors'])) {
            return $curl;
        }

        $document = new Document($curl['response']);
        // https://github.com/Imangazaliev/DiDOM/blob/master/README-RU.md
        $arr = $document->find('.s-item__wrapper'); //s-item__link

        $lots = [];
        foreach ($arr as $item) {
            // парсер выдает пару пустых итемов, пропустим их
            $link = $item->first('.s-item__link');
            $itm = parse_url($link->getAttribute('href'), PHP_URL_PATH);
            preg_match('/\d{12}/', $itm, $itemNo);
            if (!$itemNo) continue;

            // если селлер не соответсвует нашим критериям — пропускаем
            $sellerBlock = $item->first('.s-item__seller-info-text');
            $sellerArray = explode(' ', $sellerBlock->text());
            if (intval($sellerArray[2]) < $this->sellerMinPositive) continue;
            if (preg_replace('/\D/', '', $sellerArray[1]) < $this->sellerMinFeedback) continue;
            if (!empty($this->banlist) && in_array($sellerArray[0], $this->banlist)) continue;

            // на выходе будет массив номеров лотов, который мы обработаем позже
            $lot['itemNo'] = $itemNo[0];
            $lots[] = $lot;
        }

        // в выдаче Ebay много мусора и сортировать по цене нельзя
        //        usort($lots, function($a,$b){
        //            return ($a['ebayPrice']-$b['ebayPrice']);
        //        });

//prettyDump($lots, 1);
        // берем второй элемент из массива $lots[1] и обрабатываем его
        // второй элемент — чтобы случайно не попал левый лот с другим товаром, который будет самым дешевым
        if (sizeof($lots) > 1) {
            $lotDetailInfo = self::getitemDetails($lots[1]);
        } elseif (sizeof($lots) == 1) {
            $lotDetailInfo = self::getitemDetails($lots[0]);
        } else die('массив $lots пуст ');

//prettyDump($lotDetailInfo);

        if ($_POST['export']) {
            self::export($lotDetailInfo, $_POST['export']);
        } else return ['response' => $lotDetailInfo, 'debug' => '$curl'];
    }




    /**
     * ходит на ебей курлом
     * принимает запрос: а) массив б) инт
     * возвращает массив данных
     *
     * @param string $request
     * @return array
     */
    private function request($request, $type) {
        switch ($type) {
            case 1: $url = self::EBAY_MOTOR_LIST_URL.str_ireplace(' ', '+', $request['request']); break;  // запрос списка лотов
            case 2: $url = self::EBAY_ITEM_URL.$request['request']; break;                                              // запрос одного лота
        }
        if ($type == 1 && $_POST['minprice']) $url = $url.'&_udlo='.$_POST['minprice'];
        if ($type == 1 && $_POST['maxprice']) $url = $url.'&_udhi='.$_POST['maxprice'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://motokofr.com', // дебаг
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_HEADER =>true, // заголовки ответа
            CURLOPT_NOBODY => false, // сама страница, для отладки
            CURLOPT_FAILONERROR => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_USERAGENT => self::getRandomUseragent(),
            CURLOPT_HTTPHEADER, 'Accept-Language: en-US;q=0.6,en;q=0.4',
            CURLOPT_VERBOSE => true
        ));
        $debug['curl_effective_url'] = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        $response = curl_exec($curl);
        $errors = curl_error($curl);
        curl_close($curl);

        return ['errors' => $errors, 'debug' => $debug, 'response' => $response];
    }





    /**
     *
     * возвращает список совместимых марок-моделей
     *
     * @param int $itemNo
     * @return array
     */
    private function getitemDetails($lot) {
        $itemDetails = self::request(['request' => $lot['itemNo']], 2);
        //$itemDetails = self::request(['request' => 204619231974], 2);

        $numberFormat = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
        $document = new Document($itemDetails['response']);

        /** сборка массива с данными о лоте */
        // селлер
        $lot['sellerName'] = $document->first('.x-sellercard-atf__info__about-seller span.ux-textspans--BOLD')->text();;
        $storeUrl = parse_url($document->first('div.x-sellercard-atf a.ux-action')->attr('href'));
        parse_str($storeUrl['query'], $store);
        $lot['storeName'] = $store['store_name'];
        $lot['positive'] = floatval($document->first('a[href*=#STORE_INFORMATION]')->text());
        $lot['feedback'] = preg_replace('/[()]/', '', $document->first('.x-sellercard-atf__about-seller .ux-textspans--SECONDARY')->text());

        // товар
        $lot['title'] = preg_replace('/[|"\'`]/', '', $document->first('.x-item-title__mainTitle span')->text());
        $images = explode('|',  self::getEbayImages($document));
//        $lot['image'] = '';
//        $lot['cover'] = $images[0];
        if (sizeof($images) > 1) {
            $lot['image'] = implode('|', $images);
        }
        $lot['categories'] = self::getCategories();

        // цены
        $price = $document->first('.x-price-primary .ux-textspans')->text();
        $price = preg_replace('/[A-Z ]/', '', $price);
        $price = $numberFormat->parseCurrency($price, $currency);
        $shipping = preg_replace('/[A-Z ]/', '', $document->first('.ux-labels-values--shipping .ux-textspans--BOLD')->text());
        $shipping = $numberFormat->parseCurrency($shipping, $currency);
        // обработаем пока только USD
        if ($currency == 'USD' && $shipping) {
            $lot['price'] = $price;
            $lot['shipping'] = $shipping;
            $lot['ebayPrice'] = $lot['price'] + $lot['shipping']; // просто сумма price + shipping

            // похоже epid, производителя и партномер никак не взять кроме как через xPath
            if ($manufacturerWrapper = $document->first('//*[@id="viTabs_0_is"]/div/div[2]/div/div[1]/div[2]/dl/dd/div/div/span', \DiDom\Query::TYPE_XPATH)) {
                $lot['manufacturer'] = $manufacturerWrapper->text();
            }
            if ($partNumberWrapper = $document->first('//*[@id="viTabs_0_is"]/div/div[2]/div/div[2]/div[2]/dl/dd/div/div/span', \DiDom\Query::TYPE_XPATH)) {
                $lot['ean13'] = $lot['manufacturer'].' '.$partNumberWrapper->text();
            }
            if ($epidWrapper = $document->first('//*[@id="s0-1-26-7-17-1-93[1]-2-3-tabpanel-0"]/div/div/div/div[4]/div/div[2]/div[2]/div[2]/div[1]/div/div[2]/div/div/span', \DiDom\Query::TYPE_XPATH)) {
                $lot['epid'] = $epidWrapper->text();
            }
            $lot['compatibility'] = '';
            $table = $document->first('.motors-compatibility-table table');
            if ($table) {
                $tr = $table->find('th');
                $td = $table->find('td');
                $lot['compatibility'] = 'Подходит для:<br>';
                for ($i = 0; $i < sizeof($td); $i++) {
                    if ($i == 0 || ($i % sizeof($tr) == 0)) $lot['compatibility'] .= '<br>' . $td[$i]->text() . ' ';
                    else $lot['compatibility'] .= $td[$i]->text() . ' ';
                }
            }
        } else {
            $lot['price'] = ($currency == 'USD' ? $price : '---- цена в ' . (!empty($currency) ? $currency : 'неизвестной валюте'));
            // если валюта кривая покажем ее и закончим формирование лота
        }

//prettyDump($_POST);
        return $lot;
    }



    /**
     * обрабатывает картинки в переданном объекте $document
     * отбрасывает первую картинку (она уже есть в cover), возвращает пути для остальных
     *
     * @param $document
     * @return string
     */
    private function getEbayImages($document) {
        $imagesPath = [];
        $imgElem = $document->find('.ux-image-grid-container.filmstrip img');
        if (sizeof($imgElem) > 0 ) {
            for ($i = 0; $i <= sizeof($imgElem); $i++) {
                if (!is_null($imgElem[$i])) {
                    $imgPath = pathinfo($imgElem[$i]->getAttribute('src'));
                    if ($imgPath['basename']) {
                        $imagesPath[] = $imgPath['dirname'].'/s-l1600.'.$imgPath['extension'];
                    }
                }
            }
        }
        return implode('|', $imagesPath);
    }



    /**
     * возвращает рандомный UserAgent
     *
     * @return string
     */
    private static function getRandomUseragent() {
        $useragents = [
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/119.0",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1",
            "Mozilla/5.0 (iPad; CPU OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1",
            "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36"
        ];
        return rand(0, sizeof($useragents)-1);
    }


    private static function getCategories() {
        $categories = "";
        for ($cat_id = 1;$cat_id<=100;$cat_id++) {
            if (!empty($_POST['category_'.$cat_id])) {
                $categories.=$_POST['category_'.$cat_id]."|";
            }
        }
        return $categories;
    }


    private function export($lot, $format) {
//file_put_contents('jopa', $lot['compatibility']);
//die();
//prettyDump($_POST);
        $categories = "";
        for ($cat_id = 1;$cat_id<=100;$cat_id++) {
            if (!empty($_POST['category_'.$cat_id])) {
                $categories.=$_POST['category_'.$cat_id]."|";
            }
        }

        $nacenka = ($lot['ebayPrice'] / 100 * (float) $_POST['nacenka_percent']) + $this->misha_prib;
//            if ($nacenka < $this->min_prib) $nacenka = $this->min_prib;
//            if ($nacenka > $this->max_prib) $nacenka = $this->max_prib;
        $price = round($lot['ebayPrice'] + $nacenka /*- $weight_price*/);

        // заголовки таблицы
        $tableHeaders = ['skip', 'Активен','Название','Категории','Цена вкл налоги','Описание','Цена закупки','Короткое описание','Артикул №','Артикул поставщика','EAN13','Марка','Произв','Вес','Кол-во','Метки','Meta keywords','Meta_description','URL изображений'];

        // содержимое таблицы
        $list = array (
            $tableHeaders,
            [
                '',
                $_POST['active'],
                $lot['title'],
                $categories,
                $price,
                $lot['compatibility'], //'описание = compatibility',
                $lot['ebayPrice'],
                $_POST['desc_short'],
                $lot['storeName'],
                $lot['itemNo'],
                $lot['ean13'],
                $_POST['manufacturer'],
                $_POST['supplier'],
                $_POST['weight'],
                $_POST['quantity'],
                $_POST['tags'], $_POST['tags'], // так надо
                $lot['title'],
                $lot['image']]
        );

        // xlsx
        if ($format == 'xlsx') {
            // https://github.com/shuchkin/simplexlsxgen
            $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($list);
            $xlsx->downloadAs('export.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx
        }

        // csv
        if ($format == 'csv') {
            $file = '../temp/export.csv';
            $fp = fopen($file, 'w');
            foreach ($list as $fields) {
                fputcsv($fp, $fields, ';');
            }
            fclose($fp);

            if (file_exists($file)) {
                ob_end_clean();
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));

                readfile($file);
                unlink($file);
            } else {
                echo '<br>нет файла на диске<br>';
                var_dump($file);
            }
        }
    }
} // class





