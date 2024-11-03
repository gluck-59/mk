<?php
set_time_limit (180);
error_reporting(E_ALL^E_WARNING^E_NOTICE^E_DEPRECATED);
ini_set('display_errors','on');


use DiDom\Document;


class EbayParser extends AdminTab
{
    private $banlist = array();


    /**
     пробелы в запросе заменить на +
    _fcid=186 = испания
    _stpos=03000  аликанте
     LH_ItemCondition=3 - новый
     &rt=nc&LH_BIN - BIN
     */
    CONST EBAY_US_URL = 'https://www.ebay.com/sch/i.html?_stpos=03000&_fcid=186&LH_ItemCondition=3&rt=nc&LH_BIN=1&_stpos=03000&_fcid=186&_nkw=';

//    public function __construct()
//    {
//        parent::__construct();
//    }

    function parse($request, $findpair = 0, $csv = 0) {
//prettyDump(empty($this->banlist));
//die();
        $curl = curl_init();
        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://motokofr.com',
            CURLOPT_URL => self::EBAY_US_URL.str_ireplace(' ', '+', $request['request']),
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_HEADER =>true, // заголовки ответа

            CURLOPT_NOBODY => false, // сама страница, для отладки
            CURLOPT_FAILONERROR => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_USERAGENT => self::getRandomUseragent(),
            CURLOPT_HTTPHEADER, 'Accept-Language: en-US;q=0.6,en;q=0.4',
            CURLOPT_VERBOSE => true

        ));

        $debug[] = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL, '');
        $response = curl_exec($curl);
        $errors = curl_error($curl);
        curl_close($curl);

        // https://github.com/Imangazaliev/DiDOM/blob/master/README-RU.md
        $document = new Document($response);
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
            if (intval($sellerArray[2]) < 97) continue;
            if (preg_replace('/\D/', '', $sellerArray[1]) < 1000) continue;
            if (!empty($this->banlist) && in_array($sellerArray[0], $this->banlist)) continue;

            // картинки
            $imgElem = $item->first('.image-treatment img');
            $imgPath = pathinfo($imgElem->getAttribute('src'));




            // сбока массива с данными о лоте
            $lot['itemNo'] = $itemNo[0];
            $lot['price'] = preg_replace('/[a-zA-Z$ ]/ ', '', $item->find('.s-item__price')[0]->text());
            $lot['shipping'] = preg_replace('/[a-zA-Z+\$]/', '', $item->find('.s-item__shipping')[0]->text());
            $lot['ebayPrice'] = $lot['price'] + $lot['shipping']; // просто сумма price + shipping
            $lot['imgPath'] = $imgPath['dirname'].'/s-l1600.'.$imgPath['extension'];
            $lot['sellerName'] = $sellerArray[0];
//            prettyDump($lot);

            $lots[] = $lot;
        }

prettyDump($lots);
prettyDump($arr);


//        return ['error' => $errors, 'response' => $out, 'debug' => $debug];
    }


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
} // class



/*
// начало вывода файла
if (isset($_POST['export'])) {
    $filename = "export.csv";
    header('X-Accel-Buffering: yes');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Pragma: no-cache');
    header('Expires: 0');

    // заголовки таблицы
    echo("skip;Активен;Название;Категории;Цена вкл налоги;Описание;;Цена закупки;Короткое описание;Артикул №;Артикул поставщика;EAN13;Марка;Произв;Вес;Кол-во;Метки;Meta keywords;Meta_description;URL изображений\r\n");
} else {
    echo 'нету $_POST[export]';
}
    */