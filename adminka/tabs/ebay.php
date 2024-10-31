<?php
//echo('<pre>');
//@ini_set('max_execution_time', 0);
set_time_limit (180);


error_reporting(E_ALL^E_WARNING^E_NOTICE);
ini_set('display_errors','on');

require __DIR__.'/../../ebay-sdk/vendor/autoload.php';
$config = require __DIR__.'/../../ebay-sdk/configuration.php';

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/../../config/settings.inc.php');


use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;


$service = new Services\FindingService([
	'credentials' => $config['production']['credentials'],
	'globalId'    => Constants\GlobalIds::MOTORS
]);



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
$usd = ($usd['conversion_rate']);
$eur = ($usd / $eur['conversion_rate']);
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


prettyDump($_POST);







echo '<hr>';
die();
// возьмем из базы цену доставки в зависимости от веса товара (почта россии, prority)
$weight_price = Db::getInstance()->getValue('
SELECT `price` FROM `presta_delivery`
where `id_carrier` = 55
and `id_zone` = 1
and `id_range_weight` = (SELECT `id_range_weight` 
FROM `presta_range_weight`
where `id_carrier` = 55
and `delimiter1` <= '.$weight.'
and `delimiter2` >= '.$weight.')
');
$weight_price = (float)$weight_price;

// если "ключевые слова" содержит номер лота
if (preg_match('/^\d{12}$/', trim($request)))
$lots = Ebay_shopping::getSingleItem(trim($request));

// если "ключевые слова" содержит слова
else 
{
	// если store указан
	if ($store != '')
	{
    	$lots = Ebay_shopping::findItemsIneBayStores($request, $store, $minprice, $maxprice, $site_id);
    }

	// если нет	
	else
	{
//    	$lots = Ebay_shopping::findItemsAdvanced($request, 0, 1); // оригинал
    	$lots = [];
    }

prettyDump($lots);

}

$categories = "";
for ($cat_id = 1;$cat_id<=100;$cat_id++)
{
 if (!empty($_POST['category_'.$cat_id])) 
  {
  $categories.=$_POST['category_'.$cat_id]."|"; 
  }
}

// заголовки таблицы
echo("skip;Активен;Название;Категории;Цена вкл налоги;Описание;;Цена закупки;Короткое описание;Артикул №;Артикул поставщика;EAN13;Марка;Произв;Вес;Кол-во;Метки;Meta keywords;Meta_description;URL изображений\r\n");

// выводим массив в файл
foreach ($lots as $lot)
{

	// основное
	if (strval($lot['type']) != "FixedPriceItem") continue; // пропускать если режим аукциона 
	//    if (!$lot['shipping']) continue; // пропускать если нет доставки

	//  валюты, цены, округление
	$ebay_currency = $lot['currency'];
	if	($ebay_currency = "USD") $currency = 1;//$usd;
	else $currency = $eur;
	
	$shipping = $lot['shipping'];
  	$ebay_price = ($lot['price'] + $shipping);
	$wholesale_price = round($ebay_price * $paypal * $currency);
	$nacenka = $wholesale_price / 100 * (float)$nacenka_perc;
	if ($nacenka < $min_prib) $nacenka = $min_prib;
	if ($nacenka > $max_prib) $nacenka = $max_prib;
	$price = round($wholesale_price + $nacenka - $weight_price);
	
    if (!$shipping) 
    {
    	$price = 'нет доставки';
   	}

	echo ";";						// --
	echo $_POST['active'].";";		// Активен
	echo $lot['name'].";";			// Название
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

function prettyDump($data = null, $die = false) {
	if (in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1', '0.0.0.0', 'localhost'])) {
		$stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		echo "<pre style='text-align: left;font-size: 14px;font-family: Courier, monospace; background-color: #f4f4f4; width: fit-content; opacity: .9; z-index: 999;position: relative;width: 100vw;'>";
//        print_r($stack);
		if ($stack[0]['function'] == 'prettyDump') {
			echo __FUNCTION__.'() из '.$stack[0]['file'].' line '.$stack[0]['line'].'<br>';
		} else {
//			print_r($stack);
			echo __FUNCTION__.'() из '.($stack[1]['args'][0] ? $stack[1]['args'][0] : $stack[2]['file']).' строка '.$stack[0]['line'].':<br>';
		}
		if (is_bool($data) || is_null($data) || empty($data)) var_dump($data);
		else print_r($data);
		echo "</pre>";
		if ($die) die;
	}
}

?>
