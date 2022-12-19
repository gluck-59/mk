<?php

error_reporting(E_ALL);
ini_set('display_errors','On');


$file = ('ym.xml');
$fp = fopen($file, "w"); 


$shopUrl = '//www.motokofr.com';
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php'); 
$cfgFile = dirname(__FILE__). '/config/settings.inc.php';
if (!file_exists($cfgFile))
{
    die('skript doljen byt v korne prestashop i config/settings.inc.php nastroen na soedinenie s DB.');
}
require_once($cfgFile);

$db = mysql_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_);
if (!$db)
{
    die('No connection to database');
}

mysql_select_db(_DB_NAME_, $db);
mysql_query("SET NAMES 'utf8'");
$expire_date = date('Y-m-d H:i');

$currency = new Currency(3);
$rate = $currency->conversion_rate;

$res = mysql_query("
    SELECT
        c.id_category,
        c.id_parent,
        c.level_depth,
        l.name
    FROM presta_category c
    JOIN presta_category_lang l ON l.id_category = c.id_category AND l.id_lang = 3 
    WHERE active = 1 
    AND level_depth > 0
    ORDER BY level_depth, id_category");

file_put_contents($file, '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.$expire_date.'">
<shop>
<name>Motokofr.com</name>
<company>Автомобили от Япония ООД - Руска Федерация (гр. София, Република България)</company>
<url>//www.motokofr.com/</url>
<platform>Prestashop</platform>
<version>1.2.5.0</version>
<email>support@motokofr.com</email>
<currencies>
<currency id="RUR" rate="1"/>
<currency id="UAH" rate="NBU" plus="1"/>
<currency id="KZT" rate="CB" plus="1"/>
</currencies>
<categories>', FILE_APPEND);
				
$categories = array();
$katname = array();
file_put_contents($file, '<category id="1">Авто, мото</category>' , FILE_APPEND);


// выдает категории из магазина 
while ($row = mysql_fetch_assoc($res))
{
    $categories[$row['id_category']] = $row['id_category'];
    $katname[$row['id_category']] = $row['name'];
    
file_put_contents($file,  '
    <category id="'.$categories[$row['id_category']].'" parentId="'.$row['id_parent'].'">'.preg_replace('/^[0-9]+\./','',$katname[$row['id_category']]) .'</category>', FILE_APPEND);
}
// конец категорий магазина




/*
// маркет требует названий ЕГО категорий
while ($row = mysql_fetch_assoc($res))
{
    $categories[$row['id_category']] = $row['id_category'];
    $katname[$row['id_category']] = $row['name'];
	$name_category = $katname[$row['id_category']];
	
//приводим названия категорий в соответствие Маркету

$mycat = $categories[$row['id_category']];
 
	switch ($mycat)
	{
	 case ($mycat == 12 or $mycat == 35 or $mycat == 36 or $mycat == 37 or $mycat == 38) : $name_category = "Багажники, боксы, крепления"; break;
	 case ($mycat == 14 or $mycat == 63 or $mycat == 64 or $mycat == 65) :  $name_category = "Запчасти"; break;
	 case ($mycat == 72 or $mycat == 73 or $mycat == 74 or $mycat == 75 or $mycat == 76) :  $name_category = "Кузов"; break;
	 case ($mycat == 15 or $mycat == 53 or $mycat == 54 or $mycat == 55 or $mycat == 56) : $name_category = "Внешний декор"; break;
	 case ($mycat == 24 or $mycat == 43 or $mycat == 44 or $mycat == 45 or $mycat == 46 or $mycat == 82 or $mycat == 83 or $mycat == 84 or $mycat == 85 or $mycat == 86 or $mycat == 87) : $name_category = "Защита"; break;
 	 case ($mycat == 88) : $name_category = "Система выпуска"; break;
	 default: $name_category = "Аксессуары";
	}
	
file_put_contents($file,  '
    <category id="'.$categories[$row['id_category']].'" parentId="'.$row['id_parent'].'">'.preg_replace('/^[0-9]+\./','',$name_category) .'</category>', FILE_APPEND);
}
// конец категорий для маркета    
*/   
   
   
    

//error_reporting(0);
//=Product::getProducts(3, 0, 0, 'id_product', 'desc', false, $only_active = true);

/* // старый запрос
		$p = Db::getInstance()->ExecuteS('
		SELECT p.*, pl.* , t.`rate` AS tax_rate, m.`name` AS manufacturer_name, s.`name` AS supplier_name,  c.`id_category`
		FROM `presta_product` p
		LEFT JOIN `presta_product_lang` pl ON (p.`id_product` = pl.`id_product`)
		LEFT JOIN `presta_tax` t ON (t.`id_tax` = p.`id_tax`)
		LEFT JOIN `presta_manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `presta_supplier` s ON (s.`id_supplier` = p.`id_supplier`)
		LEFT JOIN `presta_category_product` c ON (c.`id_product` = p.`id_product`)
		WHERE pl.`id_lang` = 3
		AND p.`active` = 1
		AND p.`quantity` > 0
		GROUP BY p.`id_product`
		');
*/
		$p = Db::getInstance()->ExecuteS('
		SELECT p.`id_product`, p.`id_category_default`, p.`quantity`, p.`price`,  pl.`description`, pl.`description_short`, pl.`meta_keywords` AS keywords, pl.`name`, m.`meta_title` AS manufacturer_name, s.`name` AS supplier_name,  c.`id_category`, p.`id_supplier`, p.`ean13` AS ean13
		FROM `presta_product` p
		LEFT JOIN `presta_product_lang` pl ON (p.`id_product` = pl.`id_product`)
		LEFT JOIN `presta_manufacturer_lang` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `presta_supplier` s ON (s.`id_supplier` = p.`id_supplier`)
		LEFT JOIN `presta_category_product` c ON (c.`id_product` = p.`id_product`)
		WHERE pl.`id_lang` = 3
		AND p.`active` = 1
		AND p.`quantity` > 0
		GROUP BY p.`id_product`
		');
		
	$products=Product::getProductsProperties(3, $p);

	file_put_contents($file,  '
	</categories>
	<local_delivery_cost>0</local_delivery_cost>
	<offers>', FILE_APPEND);

//$i = 0;	

	foreach ($products as $row) 
	{

	$kategorie=array();
	$category = new Category(intval($row['id_category']), intval(3));
	while ($category->id <> 0) 
	{
    	$kategorie[]=$category->hideCategoryPosition($category->id);
	    $category = new Category(intval($category->id_parent), intval(3));
	}


//только главная фотка
// $img=Product::getCover($row['id_product']);

$rn = array("<br />", "<br>","</p>", "</ul>", "</li>");
//str_replace($rn, ". ",$row['description']);

//if ($row['quantity'] > 0)

$description_short = htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['description_short'])));
// отключаем $description_short из-за пидорасов из отдела цензуры
if (!$description_short) {
$description_short = "";
}

$description = htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['description']))).'. ';
if (!$description) {
$description = htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['name']))).'. ';
}

$manucacturer = htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['manufacturer_name'])));
// добавим EAN13
if ( $row['ean13'] != '' AND preg_match('/[а-яА-Я]/', $row['ean13'] ) == 0) 
    $vendorcode = htmlspecialchars(strip_tags($row['ean13']));

$marka = ' '.$row['supplier_name'].'.';
if (intval($row['id_supplier']) == 12) { 
$marka = "."; 
}


// "теги" - это не теги, а "meta_keywords" из presta_product_lang
$keywords = htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['keywords'])));
if (!$keywords) {
$keywords = "";
}
else
$keywords = 'Теги товара: '.$keywords.'. ';

$repl = "";
if (strpos($row['link'],'motokofr.com')==false)
{
$repl = '<url>'.str_replace('http://','http://www.motokofr.com',$row['link']).'</url>';
}
else
{
$repl = '<url>'.$row['link'].'</url>';

}
file_put_contents($file, '<offer available="true" id="'.$row['id_product'].'">
'.$repl.'
<price>'.round($row['price']*$rate, -2).'</price>
<currencyId>RUR</currencyId>
<categoryId>'.$row['id_category'].'</categoryId>
', FILE_APPEND);

//все фотки, но не больше 10
    $obj = new Product($row['id_product'], false, 3);
    $images = $obj->getImages(3);
    $photos = 0;
	foreach ($images AS $img)
	{
	file_put_contents($file, '<picture>//www.motokofr.com/img/p/'.$row['id_product'].'-'.$img['id_image'].'.jpg</picture>', FILE_APPEND);
	$photos = $photos+1;
	if ($photos == 10 ) break;
	}



// для яндекс-маркета
//file_put_contents($file, '<name>'.htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['name'].', '.$description_short))).' для мотоцикла'.$marka.'</name>

// для номальных
file_put_contents($file, '<name>'.htmlspecialchars(strip_tags(str_replace($rn, "\r\n",$row['name']))).'</name>

<vendor>'.$manucacturer.'</vendor>
<vendorCode>'.trim($vendorcode).'</vendorCode>
<description>'.$description_short.'. '.$description.$keywords.'</description>
</offer>
', FILE_APPEND);
//<sales_notes>Предоплата.</sales_notes>

/*
$i = $i+1;
if ($i==100) break;
*/

unset ($vendorcode, $description_short, $description, $manucacturer, $vendorcode, $marka, $keywords);
}


file_put_contents($file, '</offers>
</shop>
</yml_catalog>', FILE_APPEND);

fclose($fp); //Закрытие файла
echo (count($p).' товаров сгенерировано, //www.motokofr.com/ym.xml создан');


?> 