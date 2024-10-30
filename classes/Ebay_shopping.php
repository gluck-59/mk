<?php /** @noinspection PhpUndefinedFunctionInspection */
    error_reporting(E_ERROR);
    set_time_limit (60);

    if (!isset($_POST['lot']))
        echo '<body id="php">';

    class Ebay_shopping
    {

        var $lotnum;
        var $name;
        var $currency;
        var $price;
        var $shipping;
        var $images = array();
        var $siteid;
        var $error = '';
        var $lot   = null;

        public static $banlist_OLD = array(
            'speedoutfitters',
            'motorcityperformanceplus',
            'ridersaddiction',
            'hondaeasttoledo'
        );

        public static $banlist = [];

        public function __construct($lot)
        {
//            $this->lotnum = $lot;
            if (!empty($responseXML)) $this->name = $responseXML->Item->Title;
        }


        public static function getEbayUrl() {
            return (in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1', '0.0.0.0', 'localhost']) ? 'ebaysdk' : 'ebaysdk.motokofr.com');
        }

        /**
         * Возвращает приемлемый процент позитивных отзывов
         * @return int
         */
        public static function getsellerEbayPositive()
        {
            return 90;
        }

        /**
         * Устанавливает номер лота из параметра
         * @param mixed $lot  Lot
         */
        function setLot($lot)
        {
            $this->lot = $lot;
        }

        /**
         * Get single item
         * @param $request
         * @param int $skip_no_spipping
         * @param int $ajax
         * @return array|bool
         */
        public static function getSingleItem($request, $skip_no_spipping = 1, $ajax = 0)
        {
//            echo '<br>==================запрос getSingleItem, $request = ';
//prettyDump($request);
            $auth = self::getAuthorization();
            $ebaySettings = parse_ini_file(_PS_ROOT_DIR_."/config/ebay_config.php", true);
            if (empty($auth['access_token'])) return false;

            $endpoint = 'https://open.api.ebay.com/shopping?';
            $session  = curl_multi_init();                       // create a curl session
            //print_r($request); die;
            //exit;
            //
            $results = array();

            $headersShip = array(
                'X-EBAY-API-CALL-NAME: GetShippingCosts',
                'X-EBAY-API-SITE-ID: 0',
                'X-EBAY-API-APP-ID: '.EBAY_CLIENT_ID,
                'X-EBAY-API-VERSION: 889',
                "X-EBAY-API-REQUEST-ENCODING: XML",
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token'],
                'Content-Type: text/xml;charset=utf-8',
            );

            //
            $headers = array(
                'X-EBAY-API-CALL-NAME: GetSingleItem',
                'X-EBAY-API-SITE-ID: 0',                                 // Site 0 is for US
                'X-EBAY-SOA-SECURITY-APPNAME:'.$ebaySettings['production']['appId'],
                'X-EBAY-API-VERSION: 889',
                "X-EBAY-API-REQUEST-ENCODING: XML",    // for a POST request, the response by default is in the same format as the request
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token'],
                'Content-Type: text/xml;charset=utf-8',
            );

            $chs = array();
            //printf(" (старт мультикурла getSingleItem: %.2f сек)<br>", (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]));
            foreach ( $request as $url )
            {
//var_dump($url);
                // подготовим мультикурл для GetSingleItem
                $chs[] = ( $ch = curl_init() );

                $xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
                $xmlRequest .= '<GetSingleItemRequest xmlns="urn:ebay:apis:eBLBaseComponents"><ItemID>'.$url.'</ItemID>';
//                $xmlRequest .= '<IncludeSelector>ShippingCosts,Compatibility,Details,ItemSpecifics</IncludeSelector>';
                $xmlRequest .= '<IncludeSelector>ShippingCosts,Details,ItemSpecifics</IncludeSelector>';
                $xmlRequest .= '</GetSingleItemRequest>​';
                curl_setopt($ch, CURLOPT_URL, $endpoint );
                curl_setopt($ch, CURLOPT_POST, true);              // POST request type
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest); // set the body of the POST
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // return values as a string - not to std out
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);    //set headers using the above array of headers

//echo '<br>GetSingleItemRequest<br>';
//curl_setopt($ch, CURLOPT_VERBOSE, true); // лог апача

                curl_multi_add_handle( $session, $ch );

                // подготовим мультикурл для getShipping ---- кажется это лишнее - порождает запросы-дубликаты
                // при доставке в США запрашивать шиппинг отдельно неактуально
                /*$chs[] = ( $ch = curl_init() );
                $xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
                $xmlRequest .= "<GetShippingCostsRequest xmlns='urn:ebay:apis:eBLBaseComponents'>";
                $xmlRequest .= "<ItemID>$url</ItemID>";
                $xmlRequest .= "<DestinationCountryCode>RU</DestinationCountryCode>";
                //$xmlRequest .= "<DestinationPostalCode>19720</DestinationPostalCode>";
                $xmlRequest .= "<QuantitySold>1</QuantitySold>";
                $xmlRequest .= "<IncludeDetails>false</IncludeDetails>"; // false|true необязательно
                $xmlRequest .= "</GetShippingCostsRequest>​";
                curl_setopt( $ch, CURLOPT_URL, $endpoint );
                curl_setopt($ch, CURLOPT_POST, true);              // POST request type
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest); // set the body of the POST
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // return values as a string - not to std out
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headersShip);    //set headers using the above array of headers

//echo '<br>GetShippingCostsRequest<br>'.$url;
//curl_setopt($ch, CURLOPT_VERBOSE, true);  // лог апача

                curl_multi_add_handle( $session, $ch );
                */
            }


            do {
                // запустим мультикурл
                $status = curl_multi_exec( $session, $running );
                // получаю информацию о текущих соединениях
                $info = curl_multi_info_read( $session );
                if ( $info!==false )
                {
//	   			prettyDump($info);  // Resource id
                }
            }
            while ( $status === CURLM_CALL_MULTI_PERFORM || $running);


///////////////////// главный цикл  /////////////////////
            foreach ( $chs as $ch )
            {
                $responseXML = curl_multi_getcontent($ch);
                $responseXML = simplexml_load_string($responseXML);


// дебаг
//$curlURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
//prettyDump($curlURL);

//prettyDump('getSingleItem '.__LINE__);
//prettyDump($responseXML);

                // если лот не BIN
                // почему-то вседа выполняется continue независимо от 	ListingType
                // continue пока отключено
                if ($responseXML->Item->ListingType != 'FixedPriceItem')
                {
                    echo			$error = $responseXML->Item->ItemID.' '.$responseXML->Item->ListingType;
                    ob_get_flush();
                    //			continue;
                }

                // если лот протух
                if ($responseXML->Item->ListingStatus == 'Completed')
                {
                    if ($ajax == 0)
                    {
                        echo '<script>toastr.warning(\'<a target="_blank" href="https://ebay.com/itm/'.$responseXML->Item->ItemID.'">Лот '.$responseXML->Item->ItemID.' протух, ищем другой по partnumber</a>\',\'Ответ1 getSingleItem:\');</script>';
                        ob_get_flush();
                    }
                    return;

                    if (isset($responseXML->Item->ProductID))
                    {
//					 echo $error = ('EPID = '. $responseXML->Item->ProductID);
                        echo '<div id="console">'.$error = ('EPID = '. $responseXML->Item->ProductID).'</div>';
                        ob_get_flush();
                        self::findItemsByEPID($responseXML->Item->ProductID);
                    }
                    else
                    {
                        echo $error = ('EPID не найден, доделать. ');
                        ob_get_flush();
                    }
                }

                // если продавец (userID) в бан-листе - пропускаем
                if (in_array($responseXML->Item->Seller->UserID, self::$banlist))
                {
                    if ($ajax == 0)
                    {
                        echo "<script>toastr.warning('getSingleItem: cеллер забанен, пропускаем', '".$responseXML->Item->Seller->UserID."');</script>";
                        ob_get_flush();
                    }
                    else {
                        return json_decode(array('error'=>'Селлер забанен'));
                    }
                    continue;
                }


                // найдем epid
                unset($epid);
                $epid = strval($responseXML->Item->ProductID[0]);

                // обработаем производителя: Part Brand или Brand
                unset($brand);
                $partnumbers = self::simplexmlelement2array($responseXML->Item->ItemSpecifics->NameValueList);
                if ($partnumbers['Part Brand'] && $partnumbers['Brand'])
                {
                    $brand = $partnumbers['Part Brand'].' ';
                }
                elseif ($partnumbers['Part Brand'])
                {
                    $brand = $partnumbers['Part Brand'].' ';
                }
                else
                {
                    $brand = $partnumbers['Brand'].' ';
                }


                // обработаем парт-номер: Manufacturer Part Number или MPN или OEM
                unset($partnumber);
                if ($partnumbers['Manufacturer Part Number'] && $partnumbers['MPN'])
                {
                    $partnumber = $partnumbers['Manufacturer Part Number'];
                }
                elseif ($partnumbers['Manufacturer Part Number'])
                {
                    $partnumber = $partnumbers['Manufacturer Part Number'];
                }
                elseif ($partnumbers['OEM'])
                {
                    $partnumber = $partnumbers['OEM'];
                }
                else
                {
                    $partnumber = $partnumbers['MPN'];
                }

                if (!$partnumber)
                {
                    //			echo '<div id="console">PartNumber не найден.</div>';
                    //			ob_get_flush();
                }
                else
                    // сформируем поле "производитель" + "партномер"
                {
                    $partnumber = $brand.$partnumber;
//                    echo '<div id="console">partnumber = '.$partnumber.'</div>';
//                    ob_get_flush();
                }

                // обработаем совместимость с марками-моделями (если указано) -- РАБОТАЕТ НЕ У ВСЕХ ЛОТОВ
                        unset($compatibility);
                        $compatibilities = $responseXML->Item->ItemCompatibilityList;
                        if ($compatibilities->Compatibility)
                        {
                            $compatibility = '<p>Подходит для:</p><ul>';
                            foreach ($compatibilities->Compatibility as $models)
                            {
                                $model = self::simplexmlelement2array($models);
                                $compatibility .= '<li>'.$model['Make'].' '.$model['Model'].' '.$model['Submodel'].' '.$model['Year'].'</li>';
                            }
                            $compatibility .= '</ul>';
                        }

                // обработаем фотки
                unset($image);
                if (empty($responseXML->CorrelationID))  {
                    $i=0;
                    foreach ($responseXML->Item->PictureURL as $images)
                    {
                        $imgs = $images->$i;
                        $images1[] = self::imageNameTrim($imgs);
                        $i++;
                    }
                    $cover = $responseXML->Item->GalleryURL;
                    $image = implode("|", $images1);
                }

                // обработаем стоимость доставки
                if (isset($responseXML->CorrelationID))
                {
                    echo '<hr>зачем нужен CorrelationID?<hr>';
                    $results[strval($responseXML->CorrelationID)]['shipping'] = strval($responseXML->ShippingCostSummary->ListedShippingServiceCost);
                    $results[strval($responseXML->CorrelationID)]['ebay_price'] = strval($responseXML->ShippingCostSummary->ListedShippingServiceCost)+$results[strval($responseXML->CorrelationID)]['price'];
                } else {
//prettyDump('getSingleItem $responseXML');
//prettyDump($responseXML);
//echo '<hr>';
                    $out = array (
                        'lot' => strval($responseXML->Item->ItemID),
                        'status' => strval($responseXML->Item->ListingStatus),
                        'condition' => strval($responseXML->Item->ConditionDisplayName),
                        'conditionId' => $responseXML->Item->ConditionID,
                        'type' => strval($responseXML->Item->ListingType),
                        'name' => strval($responseXML->Item->Title),
                        'epid' => $epid,
                        'ean13' => $partnumber,
                        'currency' => strval($responseXML->Item->ConvertedCurrentPrice['currencyID']),
                        'price' => strval($responseXML->Item->ConvertedCurrentPrice),
                        'shipping' => strval($responseXML->Item->ShippingCostSummary->ListedShippingServiceCost),
                        'seller' => strval($responseXML->Item->Storefront->StoreName),
                        'sellerCountry' => strval($responseXML->Item->Country),
                        'feedback' => strval($responseXML->Item->Seller->FeedbackScore),
                        'positive' => strval($responseXML->Item->Seller->PositiveFeedbackPercent),
                        'image' => $image,
                        'cover' => $cover,
//			    'description' => self::cleanText(strip_tags(strval($responseXML->Item->Description))),
                        'ebay_price' => (float)$responseXML->Item->ShippingCostSummary->ListedShippingServiceCost*1 + (float)$responseXML->Item->ConvertedCurrentPrice*1,
                        'compatibility' => empty($compatibility) ? '' : $compatibility
                    );
                    $results[$out['lot']] = $out;
                }
//prettyDump('$results '.__LINE__);
//prettyDump($results);
                if ($responseXML->Errors->LongMessage)
                {
                    if ($ajax == 0)
                    {
                        echo '<script>toastr.error(\'<a target="_blank" href="http://ebay.com/itm/'.$responseXML->CorrelationID.'">'.$responseXML->CorrelationID.' — '.$responseXML->Errors->LongMessage[0].'</a>\', \'Ответ2 getSingleItem: \');</script>'; // оригинал
                        ob_get_flush();
                    }
                }

                curl_multi_remove_handle( $session, $ch );
                curl_close( $ch );

            } //end foreach

            // отбросим не имеющие доставки (актуально только при доств=авк в рашку)
//            foreach ($results as $result)
//            {
//                if ($skip_no_spipping == 1)
//                    if ($result['shipping'] == '')
//                    {
//                        unset($results[$result['lot']]);
//                        continue;
//                    } else {
//                        $results[$result['lot']]['ebay_price'] = str_replace(',','.',strval($results[$result['lot']][price]+$results[$result['lot']]['shipping']));
//                    }
//
//                // передадим остальные лоты в отдельном массиве $variations
//                $variations[$result['lot']] = ($results[$result['lot']]['ebay_price']);
//            }

            curl_multi_close($session);
//prettyDump('$results '.__LINE__);
//prettyDump($results);
            return $results;
        } // /getSingleItem

        /**
         * Ищет стоимость доставки по номеру лота, выдает ошибку если нет доставки
         * @param $request
         * @param int $skip_no_spipping
         * @param string $endpoint
         * @return array|bool|null
         */
        public static function getShipping($request, $skip_no_spipping = 1, $endpoint = 'https://open.api.ebay.com/shopping?')
        {
            $auth = self::getAuthorization();
            if (empty($auth['access_token'])) return false;
            $xmlRequest = <<<xml
<?xml version="1.0" encoding="utf-8"?>
<GetShippingCostsRequest xmlns='urn:ebay:apis:eBLBaseComponents'>
    <ItemID>{$request}</ItemID>
    <DestinationCountryCode>ES</DestinationCountryCode>
    <DestinationPostalCode>03540</DestinationPostalCode>
    <QuantitySold>1</QuantitySold>
    <MessageID>{$request}</MessageID>
    <IncludeDetails>false</IncludeDetails>
</GetShippingCostsRequest>
xml;
            $responseXML = self::getXMLRequest($endpoint, $xmlRequest, array(
                'X-EBAY-API-CALL-NAME: GetShippingCosts',
                'X-EBAY-API-SITE-ID: 0',
                'X-EBAY-API-APP-ID: ' . EBAY_CLIENT_ID,
                'X-EBAY-API-VERSION: ' . EBAY_API_VER,
                'X-EBAY-API-REQUEST-ENCODING: XML',
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token'],
                'Content-Type: text/xml;charset=utf-8',
            ));
            if (!$responseXML->ShippingCostSummary->ListedShippingServiceCost && $skip_no_spipping == 0) {
                echo '<div id="console">нет доставки, ищем другой лот</div>';
                ob_get_flush();
                return null;
                // здесь запускаем другой лот из найденного
            } elseif ($responseXML->Errors->LongMessage) {
                echo 'хуйня с доставкой ';
                ob_get_flush();
                return array('ListedShippingServiceCost' => $responseXML->Errors->LongMessage);
            } else {
                return array(
                    'lot' => $request,
                    'ShippingServiceName' => $responseXML->ShippingCostSummary->ShippingServiceName,
                    'ListedShippingServiceCost' => $responseXML->ShippingCostSummary->ListedShippingServiceCost,
                    'currencyID' => $responseXML->ShippingCostSummary->ListedShippingServiceCost['currencyID']
                );
            }
        }

        /**
         * Ищет товары по ключевым словам ВО ВСЕХ store
         * в качестве ключевых слов понимает partnumber
         * использовать в EbayUpdater
         *
         * @param $request // ean13, meta description
         * @param int $findpair
         * @param int $csv
         * @return array|bool|float
         */
        public static function findItemsAdvanced($request, $findpair = 0, $csv = 0) {
            $auth = self::getAuthorization();
            if (empty($auth['access_token'])) return false;
            $ebaySettings = parse_ini_file(_PS_ROOT_DIR_."/config/ebay_config.php", true);
            $endpoint = 'https://svcs.ebay.com/services/search/FindingService/v1?';

$xmlRequest = "<?xml version='1.0' encoding='UTF-8'?>
<findItemsAdvancedRequest xmlns='http://www.ebay.com/marketplace/search/v1/services'>
    <categoryId>10063</categoryId>
  <descriptionSearch>false</descriptionSearch>
  
  <itemFilter><name>Condition</name><value>New</value></itemFilter>    
  <itemFilter><name>FeedbackScoreMin</name><value>1000</value></itemFilter>
  <itemFilter><name>ListingType</name><value>FixedPrice</value></itemFilter>
  <itemFilter><name>HideDuplicateItems</name><value>true</value></itemFilter>
  
  <outputSelector>SellerInfo</outputSelector>
  
  <paginationInput>
    <entriesPerPage>100</entriesPerPage>
  </paginationInput>
  <keywords>$request</keywords>
  <sortOrder>PricePlusShippingLowest</sortOrder>
</findItemsAdvancedRequest>";

            $session  = curl_init($endpoint);
            curl_setopt($session, CURLOPT_POST, true);
            curl_setopt($session, CURLOPT_POSTFIELDS, $xmlRequest);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                'X-EBAY-SOA-SERVICE-NAME:FindingService',
                'X-EBAY-SOA-OPERATION-NAME:findItemsAdvanced',
                'X-EBAY-SOA-SERVICE-VERSION:1.12.0',
                'X-EBAY-SOA-GLOBAL-ID:EBAY-US',
                'X-EBAY-SOA-SECURITY-APPNAME:'.$ebaySettings['production']['appId'],
                "X-EBAY-API-REQUEST-ENCODING: XML",
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token'],
                'Content-Type: text/xml;charset=utf-8',
            );
            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

            $responseXML = curl_exec($session);
            curl_close($session);
            $responseXML = simplexml_load_string($responseXML);

//        prettyDump('findItemsAdvanced');
        prettyDump($responseXML);

            if (!isset($responseXML->searchResult->item)) {
                echo "<script>toastr.error('Не найдено ни одного лота для $request', 'Ответ findItemsAdvanced:');</script>";
                ob_get_flush();
                return;
            }

            foreach ($responseXML->searchResult->item as $item) {
                if (strval($item->sellingStatus->sellingState) !== 'Active')		// пропустим проданные лоты
                {
                    $error = 'Лот продан';
                    continue;
                }

                // пропустим селлеров с positive feedback меньше X
                // не учитываем kakahealthcare, Meow-Auctshop, Mutazu
                if ((float)$item->sellerInfo->positiveFeedbackPercent < self::getsellerEbayPositive() && $item->sellerInfo->sellerUserName != 'mutazu')
                {
                    continue;
                }


                // если продавец (название магазина) в бан-листе - пропускаем
                if (in_array($item->sellerInfo->sellerUserName, self::$banlist)) {
                    echo "<script>toastr.warning('".$item->sellerInfo->sellerUserName." забанен, пропускаем', 'Ответ findItemsAdvanced:');</script>";
                    continue;
                }


                $items[] = $item;
                $itemslots[] = strval($item->itemId);
            }
            // и тут нужно снова вызвать getSingleItem для получения полной инфы по лоту
            echo "<script>toastr.info('$item->itemId', 'Повторный getSingleItem:');</script>";

            // лоты в $itemslots уже отсортированы по цене ебейским параметром PricePlusShippingLowest
            // возьмем ВТОРОЙ лот чтобы отсечь возможные глючные лоты с неверно указанным ценником
            // если в массиве содержится только один номер лота — его и возьмем
            if (sizeof($itemslots) > 1) $itemslot[] = $itemslots[1];
            else $itemslot[] = $itemslots[0];

            $results = (self::getSingleItem($itemslot, 1));

            // если ищется одиночный товар
            if ($findpair == 0)
            {
                //$results = self::priceSort($results);
                // если запрос не для вывода в файл
                if ($csv == 0) {
                    return $results[$itemslot[0]];
                } else {
                    $results[$results['lot']] = $results;
                    return $results;
                }
            }
            // если ищется парный товар
            else {
                echo "<script>toastr.info('$item->title','Ответ findItemsAdvanced для пары:');</script>";
                ob_get_flush();
                $results = array_values($results);
                return $results;
            }
        } // /findItemsAdvanced

        /**
         * Ищет составные товары из нескольких лотов
         * принимает строку с разделителями
         * разделитель — знак плюса +
         * понимает номера лотов
         *
         * @param $request
         * @return string
         */
        public static function findPair($request)
        {
            $tmp = array();
            $tmp1 = array();
            echo 'Ищем ' . $request . '<br><br>';
            $request = explode('+', $request);
            foreach ($request as $keyword) {
                $results[trim($keyword)] = self::findItemsAdvanced(trim($keyword), 1);
            }
            foreach ($results as $result => $values) {
                foreach ($values as $value) $tmp[$value['seller']][] = $value;
            }
            foreach ($tmp as $seller => $products) {
                if (count($products) >= count($request)) {
                    $tmp1[$seller] = $products;
                    $data = '';
                    $price = array();
                    $total = 0;
                    foreach ($tmp1[$seller] as $product) {
                        // сравним текущую цену с забитыми ранее
                        if (!in_array($product['price'], $price)) {
                            $tmp1[$seller]['total_price'] += round($product['ebay_price']);
                            $data .= '<br><div style=""><img style="width: 150px; height: 80px; margin-right: 20px; object-fit: contain; border: #d0d1d5 solid 1px; border-radius: 5px;" src="'.$product['cover'].'">';
                            $data .= '<a href="https://www.ebay.com/itm/'.$product['lot'].'" target="_blank">$'.$product['ebay_price'].' — '.$product['ean13'].'</div></a>';
                            $tmp2[] = $product;
                            // набиваем массив с ценами для послед проверки
                            array_push($price, $product['price']);
                            $total++;
                        }
                    }
                    $tmp2['total_price'] = $tmp1[$seller]['total_price'];
                    // если колво товаров меньше запрошенных - ничего не выводим
                    if ($total >= count($request)) {
                        $return = <<<html
<div class="sellers" data-price="{$tmp1[$seller]['total_price']}" data-supplier="{$seller}" id="{$tmp1[$seller]['total_price']}"> 
<hr>
<h1>${$tmp1[$seller]['total_price']} — {$seller}</h1>
<p></p>
{$data}<br>
</div><br>
html;
                    }
                }
                unset($tmp2);
            }
            return $return;
        }

        /**
         * ищет товары по EPID, показывает %PositiveFeedback
         * имеет фильтр по колву feedback продавца
         * показывает большие фотки
         * не показывает стоимость доставки
         *
         * <outputSelector>SellerInfo|StoreInfo|AspectHistogram|ConditionHistogram|GalleryInfo|PictureURLSuperSize|PictureURLLarge|UnitPriceInfo</outputSelector>
         * <sortOrder>BestMatch|CurrentPriceHighest|DistanceNearest|EndTimeSoonest|PricePlusShippingLowest|PricePlusShippingHighest|StartTimeNewest|BidCountMost|BidCountFewest|CountryAscending(Only showing first 10 of 11)</sortOrder>
         *
         * @param $request
         * @param string $endpoint
         * @return bool|SimpleXMLElement
         */
        public static function findItemsByEPID($request, $endpoint = 'https://svcs.ebay.com/services/search/FindingService/v1?')
        {
            $auth = self::getAuthorization();
            $ebaySettings = parse_ini_file(_PS_ROOT_DIR_."/config/ebay_config.php", true);
            if (empty($auth['access_token'])) return false;
            $xmlRequest = <<<xml
<?xml version="1.0" encoding="utf-8"?>
<findItemsByProductRequest xmlns="https://www.ebay.com/marketplace/search/v1/services">
    <itemFilter><name>FeedbackScoreMin</name><value>10000</value></itemFilter>
    <productId type="ReferenceID">{$request}</productId>
    <outputSelector>SellerInfo</outputSelector>	    
    <outputSelector>PictureURLLarge</outputSelector>	    
    <sortOrder>PricePlusShippingLowest</sortOrder>
    <paginationInput><entriesPerPage>10</entriesPerPage></paginationInput>
</findItemsByProductRequest>
xml;
            $responseXML = self::getXMLRequest($endpoint, $xmlRequest, array(
                'X-EBAY-SOA-SERVICE-NAME:FindingService',
                'X-EBAY-SOA-OPERATION-NAME:findItemsByProduct',
                'X-EBAY-SOA-SERVICE-VERSION:1.12.0',
                'X-EBAY-SOA-SECURITY-APPNAME:'.$ebaySettings['production']['appId'],
                'X-EBAY-SOA-REQUEST-DATA-FORMAT:XML',
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token']
            ));
            if (!isset($responseXML->errorMessage)) {
                foreach ($responseXML->searchResult->item as $lots) {
                    print_r($lots);
                    die;
                    // return $responseXML;
                }
                return false;
            } else {
                return $responseXML->errorMessage->error->message;
            }
        }

        /**
         * Ищет ключевые слова в заданной store
         * аналог RSS
         * понимает доставку в рашку
         * itemFilter - http://developer.ebay.com/Devzone/finding/CallRef/findItemsIneBayStores.html искать по itemFilter.name
         * @param $request
         * @param $store
         * @param int $minprice
         * @param int $maxprice
         * @return array|bool|SimpleXMLElement|string
         */
        public static function findItemsIneBayStores($request, $store, $minprice = 0, $maxprice = 99999)
        {
            die('die findItemsIneBayStores');
            $auth = self::getAuthorization();
            $ebaySettings = parse_ini_file(_PS_ROOT_DIR_."/config/ebay_config.php", true);
            if (empty($auth['access_token'])) return false;
            $xmlTemplate = <<<xml
<?xml version="1.0" encoding="utf-8"?>
<findItemsIneBayStoresRequest xmlns="https://www.ebay.com/marketplace/search/v1/services">
    <keywords>{$request}</keywords>
    <storeName>{$store}</storeName>
    <itemFilter><name>MinPrice</name><value>{$minprice}</value></itemFilter>
    <itemFilter><name>MaxPrice</name><value>{$maxprice}</value></itemFilter>  
    <itemFilter><name>AvailableTo</name><value>RU</value></itemFilter>
    <itemFilter><name>Condition</name><value>1000</value></itemFilter>
    <paginationInput>
        <entriesPerPage>100</entriesPerPage>  
        <pageNumber>[+page+]</pageNumber>
    </paginationInput>
</findItemsIneBayStoresRequest>
xml;
            $headers = array(
                'X-EBAY-SOA-SERVICE-NAME:FindingService',
                'X-EBAY-SOA-OPERATION-NAME:findItemsIneBayStores',
                'X-EBAY-SOA-SERVICE-VERSION:1.13.0',
                'X-EBAY-SOA-GLOBAL-ID:EBAY-US',
                'X-EBAY-SOA-SECURITY-APPNAME:'.$ebaySettings['production']['appId'],
                'X-EBAY-SOA-REQUEST-DATA-FORMAT:XML',
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token']
            );
            $session = curl_init('https://svcs.ebay.com/services/search/FindingService/v1?');
            curl_setopt($session, CURLOPT_POST, true);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
            $curr_page  = 0;
            $totalpages = 0;
            do
            {
                $xmlRequest = self::placeholders($xmlTemplate, array('page' => $curr_page + 1));
                curl_setopt($session, CURLOPT_POSTFIELDS, $xmlRequest);
                $responseXML = curl_exec($session);
                $responseXML = simplexml_load_string($responseXML);
                if ($responseXML->errorMessage) return $responseXML->errorMessage->error->message;
                if ($responseXML->searchResult['count'] == 0) return '<div id="console">Ничего не найдено. Возможно нет доставки?</div>';
                if ($totalpages==0)
                {
                    echo $error = 'Найдено '. strval($responseXML->paginationOutput->totalEntries).' лотов ('.strval($responseXML->paginationOutput->totalPages).' страниц).<br>';
                    // если лотов больше 100, запросим след страницу
                    // результат в виде номеров лотов запишем в $items
                    $totalpages = strval($responseXML->paginationOutput->totalPages);
                }
                foreach ($responseXML->searchResult->item as $item) $items[] = strval($item->itemId);
                $curr_page++;
            }
            while ($curr_page < $totalpages);
            curl_close($session);
            return self::getSingleItem($items,0);
        }

        /**
         * Поиск пользователя
         * @param mixed  $userID  User ID
         * @param string $url     URL
         * @return SimpleXMLElement|false
         */
        public static function GetUserProfile($userID, $url = 'https://open.api.ebay.com/shopping?')
        {
            $auth = self::getAuthorization();
            if (empty($auth['access_token'])) return false;
            $xmlRequest = <<<xml
<?xml version="1.0" encoding="utf-8"?>
<GetUserProfileRequest xmlns="urn:ebay:apis:eBLBaseComponents">
    <UserID>{$userID}</UserID>
</GetUserProfileRequest>
xml;
            $responseXML = self::getXMLRequest($url, $xmlRequest, array(
                'X-EBAY-API-APP-ID:' . EBAY_CLIENT_ID,
                'X-EBAY-API-VERSION:' . EBAY_API_VER,
                'X-EBAY-API-IAF-TOKEN:' . $auth['access_token'],
                'X-EBAY-API-CALL-NAME:GetUserProfile',
                'X-EBAY-API-REQUEST-ENCODING:XML',
                'Content-type:text/xml;charset=utf-8'
            ));
            if (!isset($responseXML->Errors)) {
                return $responseXML->User;
            } else {
                return $responseXML->Errors->LongMessage;
            }
        }

        /**
         * Get authorization data
         * @param array $scope  Scope
         * @return mixed
         */
        public static function getAuthorization(array $scope = null, &$req = null, &$auth = null)
        {
            $ebaySettings = parse_ini_file(_PS_ROOT_DIR_."/config/ebay_config.php", true);
//prettyDump($ebaySettings);
//die();
            if (empty($scope)) $scope = array('https://api.ebay.com/oauth/api_scope');
            $request = array(
                'grant_type' => 'client_credentials',
                'scope'      => implode(' ', $scope)
            );
            $req = http_build_query($request);
            $auth = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . base64_encode($ebaySettings['production']['appId'] . ':' . $ebaySettings['production']['certId'])
            );
            $ch = curl_init('https://api.ebay.com/identity/v1/oauth2/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $auth);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }

        /**
         * Get XML request
         * @param string $url      URL
         * @param string $request  XML request body
         * @param array  $headers  Headers
         * @return SimpleXMLElement
         */
        public static function getXMLRequest($url, $request, $headers = array())
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $responseXML = curl_exec($ch);
            curl_close($ch);
            return simplexml_load_string($responseXML);
        }

        /**
         * Обрезает хвосты у имен файлов картинок
         * @param string $image  Image file
         * @return string
         */
        public static function imageNameTrim($image)
        {
            $p = strpos($image, '.JPG');
            return ($p !== false) ? substr($image, 0, $p - 4) : $image;
        }

        /**
         * Делает массив из объекта simpleXMLelement Key=Value
         * @param mixed $xml  XML object
         * @return array
         */
        public static function simplexmlelement2array($xml)
        {
            $arr = array();
            foreach ($xml as $NameValue) {
                $arr[strval($NameValue->Name)] = strval($NameValue->Value);
            }
            return $arr;
        }

        /**
         * SimpleXMLElement to array
         * @param mixed $obj
         * @return array
         */
        public static function simpleXmlToArray($obj)
        {
            $ret = array();
            $obj = (array) $obj;
            foreach ($obj as $name => $node) {
                if (is_array($node)) {
                    foreach ($node as $child) {
                        $ret[$name][] = self::simpleXmlToArray($child);
                    }
                } elseif (is_object($node)) {
                    $ret[$name] = self::simpleXmlToArray($node);
                } else {
                    $ret[$name] = (string) $node;
                }
            }
            return $ret;
        }

        /**
         * Очистка текста от лишнего
         * @param string $desc  Входной текст
         * @return mixed
         */
        public static function cleanText($desc)
        {
            $search = array('visit our ebay Store', 'visit our store', 'ebay', 'positive feedback', '   ', ';');
            $desc = str_ireplace($search, '', $desc);
            return $desc;
        }

        /**
         * Берет стоимость доставки из базы на основе веса товара для соотв уменьшения цены
         * @param mixed $weight
         * @return float
         */
        public static function weightPrice($weight)
        {
            $sql = <<<sql
select `price` from `presta_delivery`
where (`id_carrier` = 55)
  and (`id_zone` = 1)
  and (`id_range_weight` = (
      select `id_range_weight` from `presta_range_weight`
      where (`id_carrier` = 55) and (`delimiter1` <= {$weight}) and (`delimiter2` >= {$weight})
  )
sql;
            $weight_price = Db::getInstance()->getValue($sql);
            $result = (float) $weight_price;
            return $result;
        }

        /**
         * Ищет наиболее дешевый лот из массива. Используется в findItemsAdvanced
         * @param array $request  Входной массив
         * @return float
         */
        public static function priceSort($request)
        {
            $ret = null;
            foreach ($request as $lot)
            {
                if (empty($lot['ebay_price'])) continue;
                $price = (float) $lot['ebay_price'];
                if ($ret === null) $ret = $price;
                if ($price < $ret) $ret = $price;
            }
            return $ret;
        }

        /**
         * Simple templater
         * @param string $tpl   Template
         * @param array  $data  Data
         * @return string
         */
        public static function placeholders($tpl, $data = array())
        {
            $ph = array();
            foreach ($data as $k => $v) $ph['[+' . $k . '+]'] = $v;
            return strtr($tpl, $ph);
        }
    }

// список категорий ebay
// http://faq.frooition.com/ebay_categories.php

    if (!isset($_POST['lot']))
    {
        echo <<<html
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="//motokofr.com/js/toastr/toastr.css" rel="stylesheet"/>

html;
    }