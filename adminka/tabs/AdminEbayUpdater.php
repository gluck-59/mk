<?php
error_reporting(E_ERROR);
ini_set('display_errors','On');

//header('X-Accel-Buffering: no');
//ob_get_flush();

$prib = 1.20;					// % прибыль
$min_prib = 15;					// в долларах
$max_prib = 200;					// в долларах
$paypal_rate = 1;				// комиссия Paypal считается при чекауте

$euCountries = ['AT','BE','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES','SE','EU'];
$ukCountries = ['UK','IE'];

$max_results = $_POST['max_results'];
if (!$max_results) $max_results = 1;

$debug = $_POST['debug'];
$no_check_old  = $_POST['no_check_old'];
$autorecord = $_POST['autorecord'];
$fast = $_POST['fast'];

if (!$no_check_old) $no_check_old = '';
if (!$debug) $debug = '';
if (!$autorecord) $autorecord = 'off';
if (!$_POST) $autorecord = 'off'; // запускает авторекорд с первого раза
if (!$_POST) $no_check_old = 'off'; // запускает не проверять старые с первого раза
if (!$_POST) $fast = 'off'; // запускает не проверять старые с первого раза
if (!$fast) $fast = 5000; else $fast = 0;

//echo "<script>toastr.clear();</script>";

?>
    <script>
            jQuery.fn.sortElements=function(){var t=[].sort;return function(e,n){n=n||function(){return this};var r=this.map(function(){var t=n.call(this),e=t.parentNode,r=e.insertBefore(document.createTextNode(''),t.nextSibling);return function(){if(e===this)throw new Error;e.insertBefore(this,r),e.removeChild(r)}});return t.call(this,e).each(function(t){r[t].call(n.call(this))})}}();
    </script>
<?     

$sellerEbayPositive = Ebay_shopping::getsellerEbayPositive(); 		// процент положительных отзывов о продавце на Ebay. Если ниже - пропускаем


// удобства
$price_diff = 15; 				// если разница больше, то выводим красную или зеленую точку
$price_alert_perc = 30; 		// если разница В ПРОЦЕНТАХ больше, то алерт
$maintenance_interval = 30; 	// через сколько ДНЕЙ считать сверку товаров устаревшей
$isnew = array('New', 'Neu', 'Nuovo', 'Neuf', 'Neu mit Etikett', 'Nuevo', 'Brand New', 'New with tags'); // состояние товара "новый" на разных языках


include_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');
include_once(PS_ADMIN_DIR.'/tabs/AdminProfiles.php');
global $cookie;

$token = Tools::getAdminToken('AdminCatalog'.intval(Tab::getIdFromClassName('AdminCatalog')).intval($cookie->id_employee));
//$token = $_GET['token'];

// при нажатии кнопки пишем в базу и грузим следующие товары
if (!empty($_POST['id'])) 
{

$err = $_POST['err'];
if ($debug == "on") 	echo '<div class="conf confirm">';

	$i = 0;
	for ($i=0;$i<count($_POST['id']);$i++)
	{
		if (!$_POST['wholesale_price'][$i]) $_POST['wholesale_price'][$i] = 0;
		// пишем в sync_price 
		Db::getInstance()->Execute("  
		INSERT INTO `sync_price` (`id_product`, `ean13`, `reference`, `supplier_reference`, `date_upd`, `wholesale_price`, `id_currency`, `skip`) 
		VALUES (".$_POST['id'][$i].", '".$_POST['ean13'][$i]."','".str_replace("'","", $_POST['reference'][$i])."','".$_POST['supplier_reference'][$i]."',NOW(), ".$_POST['wholesale_price'][$i].", ".$_POST['currency'][$i].", ".($_POST['skip'][$i] == "on" ? "1" : "0" ).")
		ON DUPLICATE KEY UPDATE
		`date_upd`=NOW(), `wholesale_price`= ".$_POST['wholesale_price'][$i].",  `skip` = ".($_POST['skip'][$i] == "on" ? "1" : "0" )."
		");

		// контроль записи в presta_product
        if ($debug == "on") 		echo '<br>'.$_POST['id'][$i].': ';
        if ($debug == "on") 		echo 'закуп '.$_POST['wholesale_price'][$i].', цена '.$_POST['price'][$i];
        if ($debug == "on") 		echo '<br>новый лот '.$_POST['supplier_reference'][$i];
        if ($debug == "on") 		echo '<br>meta_desc '.$_POST['name'][$i];
        if ($debug == "on") 		echo '<br>новый селлер '.$_POST['reference'][$i];
        if ($debug == "on")		if ($_POST['price_discount'][$i] > 0) echo ', скид '.$_POST['price_discount'][$i].',';
        if ($debug == "on")		if ($_POST['price_discount_ttl'][$i] > 0) echo ' '.$_POST['price_discount_ttl'][$i].' дн';
        if ($debug == "on")         echo '<br>skip = '.$_POST['skip'][$i];

		// если цена не ноль, то запись в presta_product
		if (!$_POST['price'][$i] =='' && $_POST['price'][$i] > 0)
		{
            if ($debug == "on") 			echo '<br> пишем в базу';	

			// пишем в presta_product
			
/* оригинал строка записи
    set `price` = ".floatval($_POST['price'][$i]).", ".($err > 0 ? "" : "`reference` = '".str_replace("'","", $_POST['reference'][$i])."', `supplier_reference` = '".$_POST['supplier_reference'][$i]."', `location` = 'ebay.com/itm',")." `wholesale_price` = ".$_POST['wholesale_price'][$i].", `active` = 1, `quantity` = 4, `date_upd` = NOW() 
*/
			
			Db::getInstance()->Execute("  		
			update presta_product
            set `price` = ".floatval($_POST['price'][$i]).", `reference` = '".str_replace("'","", $_POST['reference'][$i])."', ".($err > 0 ? "" : "`supplier_reference` = '".$_POST['supplier_reference'][$i]."', `location` = 'ebay.com/itm',")." `wholesale_price` = ".$_POST['wholesale_price'][$i].", `active` = 1, `quantity` = 4, `date_upd` = NOW() 
			
						
			where `id_product` = ".$_POST['id'][$i]."
			");
			
			Db::getInstance()->Execute("  		
			update presta_product_lang
			set `meta_description` = '".mysql_real_escape_string(str_replace('#', '', $_POST['name'][$i]))."'
			where `id_product` = ".$_POST['id'][$i].";
			");

			// если скидка не ноль, то пишем ее в presta_product
/*			if ($_POST['price_discount'][$i] > 0 && $_POST['price_discount_ttl'][$i] > 0)
			{
if ($debug == "on") echo '+++';
				Db::getInstance()->Execute("  		
				update presta_product
				set `reduction_price` = ".$_POST['price_discount'][$i].", `reduction_from` = CURDATE(), reduction_to = CURDATE() + INTERVAL ".$_POST['price_discount_ttl'][$i]." DAY - INTERVAL 1 DAY
				where `id_product` = ".$_POST['id'][$i]."
				");
			}*/
		}
		
		// если обе цены = ноль, то запись в presta_product только "колво = 0" и "Update = сегодня"
		if ($_POST['price'][$i] == 0 && $_POST['wholesale_price'][$i] == 0)
		{
		if ($debug == "on") echo ' - пишем: колво=0; active=0; обновление=сегодня';

			Db::getInstance()->Execute("  		
			update presta_product
			set `quantity` = 0, `date_upd` = NOW(), active = 0
			where `id_product` = ".$_POST['id'][$i]."
			");
		}
		
		
		
	}
if ($debug == "on") echo '</div>';	
}

//if ($no_check_old == "on") $max_results = 1;

$products = Db::getInstance()->ExecuteS('
SELECT SQL_CALC_FOUND_ROWS distinct p.`id_product`, p.`ean13`, p.`reference`, p.`supplier_reference`, pl.`name`, pl.`meta_description`, `quantity`, p.`weight`, p.`id_manufacturer`, p.`id_category_default`, p.`wholesale_price`, p.`price` as presta_price, p.`date_upd` as p_date_upd, sp.`date_upd` as sp_date_upd
FROM `presta_product` p 
left join `presta_product_lang` pl ON (pl.`id_product`= p.`id_product`  AND pl.`id_lang` = 3)
left outer join `sync_price` sp on p.`id_product`=sp.`id_product`
left join `presta_category_product` pcp on p.`id_product` = pcp.`id_product` 
left join `presta_category` pc on pcp.`id_category` = pc.`id_category` 
where pc.`active` = 1
/*and p.`active` = 1*/
and (sp.`skip` = 0 OR sp.`skip` IS NULL) 
and p.`supplier_reference` rlike "^[0-9]{12,}$" 
and sp.`date_upd` BETWEEN "2010-01-01" AND (CURDATE() - INTERVAL '.$maintenance_interval.' DAY) 
ORDER BY sp.`date_upd` 
LIMIT 0,'.abs($max_results));

$remain = Db::getInstance()->getRow('SELECT FOUND_ROWS () as remain');

$all = Db::getInstance()->ExecuteS('
SELECT distinct p.`id_product`, p.`ean13`, p.`reference`, p.`supplier_reference`, pl.`name`, p.`id_manufacturer`, p.`id_category_default`, p.`wholesale_price`, p.`date_upd` as p_date_upd, sp.`date_upd` as sp_date_upd
FROM `presta_product` p 
left join `presta_product_lang` pl ON (pl.`id_product`= p.`id_product`  AND pl.`id_lang` = 3)
left join `sync_price` sp on p.`id_product`=sp.`id_product` 
where p.`supplier_reference` rlike "^[0-9]{12,}$" 
');

$all = count($all);
$done = (intval($all) - intval($remain));

$remain_perc = intval($done - $remain['remain'])/intval($done)*100;

echo '<div style="width:100%; position:relative;text-align:right;background-color: beige; "><div style="position: relative; z-index: 1; padding: 5px">готово '.($done - $remain['remain']).', осталось '.$remain['remain'].'</div>
<div style="width:'.round($remain_perc).'%; background-color:#a7f1ac; position:absolute; top:-1px; left:0; text-align:center; z-index: 0;padding: 5px">'.round($remain_perc).'%</div></div><br>';

echo '<form id="main_form" action="'.$PHP_SELF.'" method="POST">';
$irow = 0;


//////////////////////// главный foreach  ////////////////////////////////
foreach ($products as $product)
	{
		unset($new_product);
		$cover = Image::getCover($product['id_product']);
		$skip_no_spipping = 0; // $skip_no_spipping актуален только при достааке в рашку, поэтому пока ебей не вернется в ращку $skip_no_spipping будет = 0 всегда
        $skip = 0; // пишет в базу "неактивный товар
		$currency=0;
		$id_currency = 0;
		$err = "";
		$error ="";
//		$price = 0;
		$shipping = 0;
		$new_price = 0;
		$ebay_price =0;
		$conversion_rate = 1; // чтобы исключить divide by zero
		$price_discount = 0;
		$price_discount_ttl = 0;
		$pos_discount = 0;
		$pos_discount_ttl = 0;
		$presta_price = $product['presta_price'];

		if ($_POST)
            echo '<script>toastr.success(\'<a href="index.php?tab=AdminCatalog&id_product='.$_POST['old_product'].'&updateproduct&token='.$token.'">Товар '.$_POST['old_product'].' обновлен</a>\');</script>';

//		ob_get_flush();
			
    	// сначала определим парный ли товар
    	$pair = strripos($product['ean13'], '+');
    	if ($pair != FALSE)
    	{
			echo "<script>toastr.info('".$product['ean13']."','Запрос findPair: ');</script>";
            $pairFound = Ebay_shopping::findPair($product['ean13']);
    		echo $pairFound;
    		$err = 1;
//            ob_get_flush();

            echo"<script>jQuery.fn.sortElements=function(){var t=[].sort;return function(e,n){n=n||function(){return this};var r=this.map(function(){var t=n.call(this),e=t.parentNode,r=e.insertBefore(document.createTextNode(''),t.nextSibling);return function(){if(e===this)throw new Error;e.insertBefore(this,r),e.removeChild(r)}});return t.call(this,e).each(function(t){r[t].call(n.call(this))})}}();
            
                setTimeout( function() {   
                    $('.sellers').sortElements(function(a, b)
                    {
                        return parseInt($(a).attr('id')) > parseInt($(b).attr('id')) ? -1 : 1;
                    });
                
                    $('.sellers:last h1').css('color', '#090');            
                   
                    // заполним поля с ценой
                    document.getElementsByName('price_test[]')[0].value = $('.sellers:last').attr('id'); 
                    document.getElementsByName('wholesale_price[]')[0].value = parseFloat($('.sellers:last').attr('id'));
                    document.getElementsByName('shipping[]')[0].value = 0; // добавил                    
                    
                    // заполним поле с поставщиком
                    if (document.getElementsByName('reference[]')[0] !== undefined && $('.sellers:last')[0] !== undefined) {
                        document.getElementsByName('reference[]')[0].value = $('.sellers:last')[0].dataset.supplier;    
                    }
                    
                    
                    // вызовем пересчет
                    do_math($('[name=old_product]').val(), $conversion_rate);
                    
                }, 500);
                
                // автозаполнятель по клику
                $('.sellers').click(function() { 
                    // заполним оба пока
                    document.getElementsByName('price_test[]')[0].value = this.id;                     
                    var el = document.getElementsByName('wholesale_price[]')[0];
                    el.value = this.id;
                    
                    var el1 = document.getElementsByName('price[]')[0];
                    el1.value = this.id;
                    
                    var el2 = document.getElementsByName('reference[]')[0];
                    el2.value = this.dataset.supplier;
            
                    // моргнем
                    el.style.background = 'gold';
                    setTimeout( function() {   
                        el.style.background = 'white';
                    }, 400);
                    
                    el1.style.background = 'gold';
                    setTimeout( function() {   
                        el1.style.background = 'white';
                    }, 400);                    
                    
                    el2.style.background = 'gold';
                    setTimeout( function() {   
                        el2.style.background = 'white';
                    }, 400);                    
                    
            
                    $('.sellers h1').css('color', '#666');
                    $('[data-price='+this.id+'] h1').css('color', 'green');
                    
                    // вызовем пересчет
                    do_math($('[name=old_product]').val(), $conversion_rate);                    
                } );
            </script>";  

            if ($pairFound != null) echo 'Клик на нужном диве подставит его цену';
            else echo 'Ни одной пары не найдено';
    	}

        // товар не парный
    	else
    	{
    		if ( /* $product['supplier_reference'] !== '' &&  $no_check_old == '' &&*/ preg_match('/^\d{12}$/', trim($product['supplier_reference'])))
    		{

    			echo "<script>toastr.info('".$product['supplier_reference']."','Запрос getSingleItem: ');</script>";
                $new_product = array_values(Ebay_shopping::getSingleItem([trim($product['supplier_reference'])], $skip_no_spipping ))[0];
    		}

    		if (!$new_product['lot'] && strripos($product['ean13'], 'http') === FALSE)
    		{
    			if (!$product['ean13'])
    			{
				    // Нет EAN13
                    echo "<script>toastr.info('".$product['ean13']."','Запрос findItemsAdvanced по meta_description: ');</script>";
    				echo "<script>toastr.warning('Нет EAN13, запрашиваем meta_description');</script>";				
    				$new_product = Ebay_shopping::findItemsAdvanced($product['meta_description']);
    			} else {
                    echo "<script>toastr.info('".$product['ean13']."','Запрос findItemsAdvanced по ean13: ');</script>";
                    $new_product = Ebay_shopping::findItemsAdvanced($product['ean13']);
                }
    		}	
    	}

//echo '<br>==================== AdminEbayUpdater presta $product';
//prettyDump($product);
//
//echo '<br>==================== AdminEbayUpdater Ebay $new_product';
//prettyDump($new_product);


    if ($new_product && !$new_product['shipping']) {
    	$err = 2;
    	$error .= "Нет доставки ";
    }
    if (!$new_product[array_keys($new_product)[0] /****/ ]['lot'] && $product['quantity'] > 0) {
    	$err = 1;
    	$error = "Ручное заполнение";
    }

    // ищем картинку для нового лота
    $cover_new = Ebay_shopping::simpleXmlToArray($new_product['cover']);
    $cover_new = $cover_new[0];


    ///////////////  МАТЕМАТИКА //////////////////
// тут считается входная и выходная цены
    if ($skip == 0) {
        if ($conversion_rate != 0) {
            $ebay_price = ($paypal_rate * ((float)$new_product['price'] + (float)$new_product['shipping'])) / $conversion_rate;
        }

        $new_price = $ebay_price;

        // если нет доставки, то оставим старые цены из престы
        if ($err == 2) {
            $ebay_price = $presta_price;
            $ebay_price = $product['wholesale_price'];
        }

        // если есть лот — считаем всё
        if (!is_null($new_product)) {
            // выкуп посредником
            if ($ebay_price <= 1000) {
                $additionalPrice = $ebay_price / 100 * 1.1;
            } else {
                $additionalPrice = $ebay_price / 100 * 1.07;
            }
            if ($additionalPrice < 13) $additionalPrice = 13;


            // упаковка
            // габариты заранее хуй поймешь, будем отталкиваться от веса
            $wrappingPrice = 3.5;
            if ($product['weight'] > 1) {
                $wrappingPrice = 5;
            } elseif ($product['weight'] > 3) {
                $wrappingPrice = 7;
            } elseif ($product['weight'] > 6) {
                $wrappingPrice = 10;
            }


            // подготовим запрос к бандерольке
            $hub = 'DE1'; // delaware
            if (in_array($new_product['sellerCountry'], $euCountries)) {
                $hub = 'EU1'; // berlin
            } elseif (in_array($new_product['sellerCountry'], $ukCountries)) {
                $hub = 'UK1'; // england
            }


            // доставка
            $delivery = new Delivery();
            $shippingPrice = $delivery->getQwintryCost($product['weight'], $hub);
prettyDump('готовый к математике $new_product с бандеролькой' );
prettyDump($new_product);


            // ВХОДНАЯ: соберем и добавим все доп расходы
            $ebay_price = $ebay_price + $shippingPrice + $wrappingPrice + $additionalPrice;

            // ВЫХОДНАЯ: добавим наценку
            $new_price = $ebay_price * $prib;
            if ($new_price - $ebay_price < $min_prib) {
                $new_price = $ebay_price + $min_prib;
            } elseif ($new_price - $ebay_price > $max_prib) {
                $new_price = $ebay_price + $max_prib;
            }

///////////////  /МАТЕМАТИКА //////////////////

            // если нет нового лота, то цена = 0
            if ($err == 1) {
                $new_price = 0;
                $ebay_price = 0;
            }
            $price_alert = (abs((floatval($presta_price) - floatval($new_price))) / (floatval($presta_price) / 100));
        }
    }
    

    echo '<div id="out"><div id="in">
    <div style="position: absolute;top: 10px;right: 9px;">'.$product['p_date_upd'].'</div>
    <h1>'.$product['name'].'</h1>
    <p style="margin-top:-12px">'.$new_product['name'].'</p>
    
    
    '.( $product['quantity'] < 2 ? '<div class="product_quantity">'.$product['quantity'].'</div>' : '' ).'
    <a href="index.php?tab=AdminCatalog&id_product='.$product['id_product'].'&updateproduct&token='.$token.'">
    <img style="height:140px; width: 240px; object-fit: contain;" src="../../img/p/'.$cover['id_product'].'-'.$cover['id_image'].'.jpg"></a>
    
    <a href="http://ebay.com/itm/'.$new_product['lot'].'" target="_blank">
    <img id="cover_new" style="height:140px; width: 240px; object-fit: contain;'.(!$cover_new ? 'width:129px"' : '"src="'.$cover_new.'"').'></a>
    ';

    echo '
    <div class="old_product">
    '.( $product['supplier_reference'] != $new_product['lot'] ? '<font color="#AAA">' : '').'
    '.$product['supplier_reference'].'<br>
    '.( $product['supplier_reference'] != $new_product['lot'] ? '</font>' : '').'
    '.$product['reference'].'<br>
    '.mb_substr($product['ean13'], 0, 30).'</a>

    <input type="1hidden" readonly name="price_test[]" onchange="blink()" id="price_test_'.$product['id_product'].'" style="width: 58px; border:0; text-align:right" value="'.$ebay_price.'"><br><br>'.'    
    Ст.whol <input readonly style="width: 32px; height: 12px; border: 0; text-align:right" id="wholesale_price_old_'.$product['id_product'].'" value="'.$product['wholesale_price'].'"><br>
    Ст.цена <input readonly style="width: 32px; height: 12px; border: 0; text-align:right" id="presta_price_'.$product['id_product'].'" value="'.$presta_price.'"><br>
    Разница '.($price_alert >= $price_alert_perc ? '<input class="error"' : '<input').' id="different" readonly value="'.round($price_alert).'" style="width: 23px;text-align:right;
    border: 0;">%</span><br>
    </div>

    <div class="new_product">
    <!--a href="http://ebay.com/itm/'.$new_product['lot'].'" target="_blank"-->';
    if (!$new_product['lot'])
    {
        if ($pair == null AND $product['quantity'] == 0) {
            $skip = 1;
        }

    	echo '<input type="checkbox" id="'.$product['id_product'].'" name="skip[]" '.($skip == 1 ? 'checked' : '').'><label style="float:none; font-weight:normal" for="'.$product['id_product'].'"> Товар в архив</label> <span class="error">'.$error.'</span> <br>';
    	if ($skip==0)
    	{
    		echo '<input type="hidden" name="skip[]" value="off">
            <input readonly name="reference[]" value="'.$new_product['seller'].'"> <br>';
    	}
    }
    else
	{	
		echo $new_product['lot'].' '.(in_array($new_product['condition'], $isnew) ? '' : '<span class="error">'.$new_product['condition'].'</span>').'<br>';
	}
    
    if ($new_product['lot'])
    {
    	echo $new_product['seller'].' '. ($new_product['positive'] ? '('.$new_product['feedback'].' - '.($new_product['positive'] < $sellerEbayPositive ? '<span class="error">' : ''). $new_product['positive'].'%</span>)' : '').'<br>'.mb_substr($new_product['ean13'], 0, 30).'</a><br>';
    }
    elseif ($pair > 0 AND $pairFound != null) echo 'найдена пара<br>';
    
    echo '<input autofocus readonly onchange="do_math('.$product['id_product'].','.$conversion_rate.')" name="shipping[]" autocomplete="off" id="shipping_'.$product['id_product'].'" style=" width: 55px; " value="'.ceil($shipping).'"> '.($err == 2 ? '<span class="error">'.$error.'</span>' : 'не исп.').'<br>';
    echo '<input style="'.( (round($product['wholesale_price'],0) > round($ebay_price,0)) ? 'background:#dfd;' : '').( (round($product['wholesale_price'],0) < round($ebay_price,0)) ? 'background:#fdd;' : '').' width: 32px; height: 12px;" name="wholesale_price[]" autocomplete="off" id="wholesale_price_'.$product['id_product'].'" value="'.round($ebay_price,0).'"> Нов.whol';
        if ($ebay_price > 1)
    		{
    			$price_temp = ($product['wholesale_price'] - round($ebay_price,0));
    			while ( $price_temp >= $price_diff)
    			{
    				$price_temp = ($price_temp - $price_diff);
    				echo '<span style="color: #1c1;">&#9660;</span>'; // зеленый
    			}
    
    			$price_temp = (round($ebay_price,0) - $product['wholesale_price']);
    			while ( $price_temp >= $price_diff)
    			{
    				$price_temp = ($price_temp - $price_diff);
    				echo '<span style="color: #c22;">&#9650;</span>'; // красный
    			}			
    		};
    
    echo '<br><input style="'.( (round($new_price,0) < $presta_price) ? 'background:#dfd;' : '').( (round($new_price,0) > $presta_price) ? 'background:#fdd;' : '').'  width: 32px; height: 12px;" name="price[]" autocomplete="off" id="price_'.$product['id_product'].'" '. ($new_price ? 'value="'.round($new_price,0).'"' : 'value="0"').' > Нов.цена';
    
    		if ($ebay_price > 1)
    		{
    			$price_temp = ($presta_price - round($new_price,0));
    			while ( $price_temp >= $price_diff)
    			{
    				$price_temp = ($price_temp - $price_diff);
    				echo '<span style="color: #1c1;">&#9660;</span>'; // зеленый
    			}
    
    			$price_temp = (round($new_price,0) - $presta_price);
    			while ( $price_temp >= $price_diff)
    			{
    				$price_temp = ($price_temp - $price_diff);
    				echo '<span style="color: #c22;">&#9650;</span>'; // красный
    			}
    
    		};
    
    $rur = Currency::getCurrency(3)['conversion_rate'];
    echo  '<br>'.round(($new_price - $ebay_price)*$rur).'р прибыль';
    echo '</div>
    
    <div class="right_block">';
    //($error ? '<p class="error">'.$error.' $err = '.$err.'</p>' : '')
    
    if ($err > 0)
    {
    	echo '<br><p style="text-align:center">
    		<a  class="ebutton red" href="'.$currentIndex.'?tab=AdminCatalog&opa=1&id_product='.$product['id_product'].'&deleteproduct&token='.$token.'" onclick="return confirm(\'Удалить '.$product['name'].' ('.$product['id_product'].') ?\');">Удалить</a></p>';
    	if ($no_check_old == "on")
    		echo '<p style="text-align:center">
    		<a  class="ebutton green" href="/adminka/index.php?tab=AdminEbayUpdater&token='.$token.'" >Перезагрузить</a></p>';
    }
    
    
    if ($new_product['compatibility'])
    {
    	$compatibility = $new_product['compatibility'];
    //	$compatibility = str_replace('<', '&lt;', $new_product['compatibility']);
    	echo '<p style="width: 135px;text-align: center;">
    	<a class="ebutton blue" href="javascript:void(0)" onclick="showHide(\'compatibility '.$new_product['lot'].'\')">Совместимость</a></p>';
    }
    
    
    if ($price_discount > 0) 
    	echo ' '.$currency.' '.($ebay_price - $price_discount).' еще '.$price_discount_ttl.' дн';
    
    echo '</div>
    <div class="compatibility" id="compatibility '.$new_product['lot'].'" style="display:none">'.$compatibility.'</div>
    </div></div>';
    
    echo '
    <input type="hidden" name="currency[]" value="'.$id_currency.'">
    <input hidden name="id[]" value="'.$product['id_product'].'">
    <input hidden name="err" value="'.$err.'">
    <input hidden name="supplier_reference[]" value="'.$new_product['lot'].'">
    <input hidden name="name[]" value="'.$new_product['name'].'">
    <input hidden name="ean13[]" value="'.$product['ean13'].'">
    <input hidden name="sum[]" id="sum_'.$product['id_product'].'" style="width:50px;  border:0" value="'.floatval($ebay_price).'">
    <input hidden name="old_product" value="'.$product['id_product'].'">
    ';	
//    ob_get_flush();
    		
//die; // остановить цикл	
} //////////////////////// главный foreach  ////////////////////////////////

	
echo('</tbody></table>
');


echo '<p align="right">
<input type="checkbox" id="no_check_old" name="no_check_old" '.($no_check_old == "on" ? 'checked' : '').'>
<label style="float:none; font-weight:normal" for="no_check_old">&nbsp;Не проверять старые лоты</label>
&nbsp;&nbsp;

<input name="autorecord" type="checkbox" id="autorecord" '.($autorecord == "on" ? 'checked' : '').'>&nbsp;&nbsp;
<label style="float:none; font-weight:normal" for="autorecord">автомат</label>
&nbsp;&nbsp;

<input name="fast" type="checkbox" id="fast" '.($fast == "on" ? 'checked' : '').'>&nbsp;&nbsp;
<label style="float:none; font-weight:normal" for="fast">быстро</label>
&nbsp;&nbsp;


<input id="timer" value="Записать и проверить следующие" type="submit" onclick="/*do_math111('.$product['id_product'].','.$conversion_rate.')*/" style="height:25px; padding: 4px 7px 4px 7px; border: solid 1px gray; border-radius: 4px; background-color:';
if ($err == 0) 
echo '#CCFFCD;'; 
else 
echo '#FBB;';
echo '" name="save">
<input '.($autorecord =="on" ? readonly : "").' name="max_results" type="number" style="width: 30px; height:20px" value="'.$max_results.'">';

echo '
<input type="checkbox" id="debug" name="debug"';
if ($debug == "on") echo 'checked="checked"';
echo '><label style="float:none; font-weight:normal" for="debug">&nbsp;отладка&nbsp;&nbsp;</label>

</p></form>';

if ($err == null) $err = 0;

?>

<script language='JavaScript'>
// <![CDATA[
function do_math(id,rate) {
	var prib = <?php echo number_format($prib,2,'.',''); ?>;
	var min_prib = <?php echo number_format($min_prib,2,'.',''); ?>;
	var max_prib = <?php echo number_format($max_prib,2,'.',''); ?>;
	var paypal_rate = <?php echo number_format($paypal_rate,2,'.',''); ?>;
    var price_test = (document.getElementById('price_test_'+id).value != 'undefined' ?  document.getElementById('price_test_'+id).value : 0);
    var shipping1 = document.getElementById('shipping_'+id).value;
    var res = new Function('return ' + shipping1);
    var shipping = res();

    var sum = parseFloat(price_test)+parseFloat(shipping);
	var wholesale_price = Math.round((paypal_rate * sum) / rate);
    var wholesale_price_old = document.getElementById('wholesale_price_old_'+id).value;
	var price = Math.round(wholesale_price*prib);  
    var presta_price = document.getElementById('presta_price_'+id).value;	

    if (price_test > 0) {
        if ((wholesale_price*prib)-wholesale_price < min_prib )
        {
            var price = Math.round(wholesale_price+min_prib);
        }
        if ((wholesale_price*prib)-wholesale_price > max_prib )
        {
            var price = Math.round(wholesale_price+max_prib);
        }
    } else {
        var price = 0;
    }

    price = Math.ceil(price/1)*1; // 10 = округл до десятков, 100 = до сотен
    document.getElementById('sum_'+id).value = isNaN(sum) ? '' : sum;
    if (!isNaN(price)) document.getElementById('price_'+id).value = price;
    if (!isNaN(Math.ceil(wholesale_price/1)*1)) document.getElementById('wholesale_price_'+id).value = Math.ceil(wholesale_price/1)*1;



console.log('вызов do_math()');
// console.warn('price_test',price_test);
// console.warn('shipping',shipping);
console.log('prib',prib);
console.log('min_prib',min_prib);
console.log('max_prib',max_prib);
console.log('paypal_rate',paypal_rate);
console.log('price_test',price_test);
console.log('shipping1',shipping1);
console.log('wholesale_price',wholesale_price);
console.log('presta_price',presta_price);
console.log('price',price);
console.log('price-итог',price);

    if (wholesale_price > wholesale_price_old)
    {
        document.getElementById('wholesale_price_'+id).style.background = ("#fdd");
    }
    else if (wholesale_price < wholesale_price_old)
    {
        document.getElementById('wholesale_price_'+id).style.background = ("#dfd");
    }
    else document.getElementById('wholesale_price_'+id).style.background = ("#fff");
    
        
    
    if (document.getElementById('price_'+id).value > presta_price)
    {
        document.getElementById('price_'+id).style.background = ("#fdd");
    }
        
    else if (document.getElementById('price_'+id).value < presta_price)
    {
        document.getElementById('price_'+id).style.background = ("#dfd");    
    }
    else document.getElementById('price_'+id).style.background = ("#fff");

}

    //]]>
</script>



<script type="text/javascript">
	function showHide(element_id) 
	{
	    //Если элемент с id-шником element_id существует
	    if (document.getElementById(element_id)) { 
	        //Записываем ссылку на элемент в переменную obj
	        var obj = document.getElementById(element_id); 
	        //Если css-свойство display не block, то: 
	        if (obj.style.display != "block") { 
	            obj.style.display = "block"; //Показываем элемент
	        }
	        else obj.style.display = "none"; //Скрываем элемент
	    }
	    //Если элемент с id-шником element_id не найден, то выводим сообщение
	    else alert("Элемент с id: " + element_id + " не найден!"); 
	} 
	function hide(element_id)
	{
		document.getElementById(element_id)
	    obj.style.display = "none";
	}
</script>




<script type="text/javascript">
	var err = <?php echo $err; ?>;
	console.log('err = '+err);
	if (err > 0)
    {
		var audio = new Audio(); // Создаём новый элемент Audio
		audio.src = '../../error.wav'; // Указываем путь к звуку "клика"
		audio.autoplay = true; // Автоматически запускаем
        console.log('Тормозим (err > 0)');
    }
		
	else
	{		
	var autorecord = '<?php echo $autorecord; ?>';
	var old_quantity = '<?php echo $product['quantity']; ?>';
	var cover_new = new Image();
    
    cover_new.onload = function start_autorecord() 
		{
            console.log('картинка прогрузилась');			
            console.log('"Автомат" = '+autorecord);	

			if (autorecord == 'on' )
			{
				var 
				price_alert = <?php echo number_format($price_alert,0,'.',''); ?>,
				price_alert_perc = <?php echo number_format($price_alert_perc,0,'.',''); ?>,
				id_product = <?php echo $product['id_product']; ?>,
				positive = <?php if (isset($new_product['positive'])) echo $new_product['positive']; else echo '0'; ?>,
				fast = <?php echo $fast; ?>,
                sellerEbayPositive = <?php echo $sellerEbayPositive; ?>,

				different = document.getElementById('different').value;

//console.log('price_alert = '+price_alert+' '+'price_alert_perc = '+price_alert_perc+' '+'different = '+different);


				if (price_alert >= price_alert_perc && positive >= sellerEbayPositive)
				{
					var audio = new Audio(); // Создаём новый элемент Audio
					audio.src = '../../error.wav'; // Указываем путь к звуку "клика"
					audio.autoplay = true; // Автоматически запускаем
		        	toastr.info('Большая разница в цене при хорошем feedback');
		        }

		        else 
		        {
    		        function mytimer()
    				{
    					if (i < 1)
    					{
    						
    					}
    					else
    					{
    		            timer.value = 'Записать и проверить следующие ('+i+')';
    		            i--;
    					setTimeout(mytimer, 1000);
    					}
    				}
				
					var audio = new Audio(); // Создаём новый элемент Audio
					audio.src = '../../done.wav'; // Указываем путь к звуку "клика"
					audio.autoplay = true; // Автоматически запускаем

					var i = fast/1000;
					mytimer();
					setTimeout("document.getElementById('main_form').submit()", fast);
					//toastr.info('Отправляем форму');
				}
			
			}
		}
		if (old_quantity == 0)
		{
    		cover_new.src = "<?=$cover['id_image']?>"+'.jpg';
    		console.log('Этого товара и было НОЛЬ, пропускаем');
        }
		else
	    cover_new.src = "<?=$cover_new; ?>";
	}
	
</script>
