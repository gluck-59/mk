<?php
set_time_limit (180);
error_reporting(E_ALL^E_WARNING^E_NOTICE^E_DEPRECATED);
ini_set('display_errors','on');

class EbayParser extends AdminTab
{
    /**
     пробелы в запросе заменить на +
    _fcid=186 = испания
    _stpos=03000  аликанте
     */
    CONST EBAY_US_URL = 'https://www.ebay.com/sch/i.html?_stpos=03000&_fcid=186&_nkw=';

//    public function __construct()
//    {
//        parent::__construct();
//    }

    function parse($request, $findpair = 0, $csv = 0) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
//            CURLOPT_URL => self::EBAY_US_URL.str_ireplace(' ', '+', $request),
            CURLOPT_URL => 'https://motokofr.com',
//            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_USERAGENT => self::getRandomUseragent(),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        prettyDump($response);


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

