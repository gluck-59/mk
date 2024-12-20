<?php
//echo('<pre>');
//@ini_set('max_execution_time', 0);
set_time_limit (180);


error_reporting(E_ALL^E_WARNING^E_NOTICE);
ini_set('display_errors','on');

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/../../config/settings.inc.php');

// начало вывода файла
if (isset($_POST['export'])) {
	$filename = "export.csv";
	header('X-Accel-Buffering: yes');
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="export.csv"');
	header('Pragma: no-cache');
	header('Expires: 0');
}

/*$db = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_);
if (!$db)
{
    die('No connection to database');
}
$db_select = mysqli_select_db($db, "motokofr");
if (!$db_select) {
	die("Database selection failed: " . mysqli_connect_error());
}*/


// основные переменные
$usd = Currency::getCurrency(Currency::getIdByIsoCode('USD'));
$eur = Currency::getCurrency(Currency::getIdByIsoCode('EUR'));
//$usd = ($usd['conversion_rate']);
//$eur = ($usd / $eur['conversion_rate']);
$request = $_POST['request'];
$store = $_POST['store'];
$site_id = $_POST['site_id'];
$minprice = $_POST['minprice'];
$maxprice = $_POST['maxprice'];
$supplier = $_POST['supplier'] ; //марка
$manufacturer = $_POST['manufacturer'] ; //производитель
$paypal = 1; // комиссия Paypal считается при чекауте
$nacenka_perc = $_POST['nacenka_perc']; //процент наценки
$min_prib = 20;
$max_prib = 80;
$weight = $_POST['weight']; //вес
$active = 0; if (!empty($_POST['active'])) $active=1; //включить/выключить товар
$tags = $_POST['tags']; //мета-теги
$desc_short = $_POST['desc_short']; //короткое описание
$quantity = $_POST['quantity']; //колво товара
$lots = [];



// возьмем из базы цену доставки в зависимости от веса товара (почта россии, prority)
/*$weight_price = Db::getInstance()->getValue('
SELECT `price` FROM `presta_delivery`
where `id_carrier` = 55
and `id_zone` = 1
and `id_range_weight` = (SELECT `id_range_weight` 
FROM `presta_range_weight`
where `id_carrier` = 55
and `delimiter1` <= '.$weight.'
and `delimiter2` >= '.$weight.')
');
$weight_price = (float)$weight_price;*/
prettyDump($request);
// если "ключевые слова" содержит номер лота
if (preg_match('/^\d{12}$/', trim($request)) ) {
	Ebay_shopping::getSingleItem(trim($request)); // @TODO заинклюдить файл
die('нужно вызвать getSingleItem');
} else {
//	include __DIR__.'/ebay_findItemsAdvanced.php'; // инклюдим соотв файл в каждом случае
	$lots = Ebay_shopping::findItemsAdvanced($request, 0, 1);


	// если store указан
//	if ($store != '')
//	{
//    	$lots = Ebay_shopping::findItemsIneBayStores($request, $store, $minprice, $maxprice, $site_id);
//    }

	// если нет	
//	else
//	{
//    	$lots = Ebay_shopping::findItemsAdvanced($request, 0, 1); // оригинал
//    	$lots = [];
//    }
}
//echo sizeof($lots). ' шт';
//prettyDump($lots);



/////// экспорт в CSV //////////
$categories = "";
for ($cat_id = 1;$cat_id<=100;$cat_id++) {
 if (!empty($_POST['category_'.$cat_id])) {
  	$categories.=$_POST['category_'.$cat_id]."|";
  }
}
// заголовки таблицы
echo("skip;Активен;Название;Категории;Цена вкл налоги;Описание;;Цена закупки;Короткое описание;Артикул №;Артикул поставщика;EAN13;Марка;Произв;Вес;Кол-во;Метки;Meta keywords;Meta_description;URL изображений\r\n");

// выводим массив в файл
echo '<br>'.sizeof($lots['item']).' шт.';
foreach ($lots['item'] as $lot) {
prettyDump($lot);
	// основное
	if (strval($lot['listingInfo']['listingType']) != "FixedPrice") continue; // пропускать если режим аукциона
	//    if (!$lot['shipping']) continue; // пропускать если нет доставки

	//  валюты, цены, округление
	$ebay_currency = $lot['currency'];
	if	($ebay_currency = "USD") $currency = 1;//$usd;
	else $currency = $usd / $eur['conversion_rate'];
	
	$shipping = $lot['shippingInfo']['shippingServiceCost']['value'];

	switch ($shipping = $lot['shippingInfo']['shippingServiceCost']['currencyId']) {
		case 'USD': $shipping = $lot['shippingInfo']['shippingServiceCost']['value']; break;
		case 'EUR':
			$eur = Currency::getIdByIsoCode('EUR');
			$shipping = $lot['shippingInfo']['shippingServiceCost']['value'] / $eur['conversion_rate'];
			break;

		default: $shipping = false;
	}

  	$ebay_price = ($lot['price'] + $shipping);
	$wholesale_price = round($ebay_price * $paypal * $currency);
	$nacenka = $wholesale_price / 100 * (float)$nacenka_perc;
	if ($nacenka < $min_prib) $nacenka = $min_prib;
	if ($nacenka > $max_prib) $nacenka = $max_prib;
	$price = round($wholesale_price + $nacenka - $weight_price);
    if ($shipping === false) $price = 'нет доставки';

	echo ";";						// --
	echo $_POST['active'].";";		// Активен
	echo $lot['title'].";";			// Название
	echo $categories.";";			// Категории
	echo $price.";";				// Цена вкл налоги
	echo $lot['description'].";";	// Описание
	echo $lot['compatibility'].";";	// Подходит для
	echo $wholesale_price.";";		// Цена закупки
	echo $desc_short.";";			// Короткое описание
	echo $lot['seller'].";";		// Артикул №
	echo $lot['lot'].";";			// Артикул поставщика
	echo $lot['ean13'].";";			// EAN13
	echo $supplier.";";				// Марка
	echo $manufacturer.";";			// Произв
	echo $weight.";";				// Вес
	echo $quantity.";";				// Кол-во
	echo $tags.";";					// Метки
	echo $tags.";";					// Meta keywords
	echo $lot['name'].";";			// Meta_description
	echo $lot['image'].";";			// URL изображений
	echo "\r\n";	
}

//echo('</pre>');
?>
