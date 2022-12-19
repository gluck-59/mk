<?php

/**
/*    Генерируем только те размеры картинок
/*    которые нам нужны
/*    остальные не трогаем
**/
    

//header('X-Accel-Buffering: no');
ob_get_flush();

error_reporting(E_ALL);
ini_set('display_errors','On');
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php'); 
include_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');
$cfgFile = dirname(__FILE__). '/config/settings.inc.php';
require_once($cfgFile);


header('X-Accel-Buffering: no');
ob_get_flush();
echo('<pre>');

$db = mysql_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_);
if (!$db)
{
    die('No connection to database');
}
mysql_select_db(_DB_NAME_, $db);
mysql_query("SET NAMES 'utf8'");


@ini_set('max_execution_time', 3600);
include(dirname(__FILE__).'/images.inc.php'); 
    $productsTypes = ImageType::getImagesTypes('products');
	$productsImages = getAllImages();
    function getAllImages()
	{
		return Db::getInstance()->ExecuteS('
		SELECT `id_image`, `id_product`
		FROM `'._DB_PREFIX_.'image`
		ORDER BY `id_image` ASC');
	}
		
		/* Regenerate products images */
		$errors = false;
		foreach ($productsImages AS $k => $image)
		{
			if (file_exists(_PS_PROD_IMG_DIR_.$image['id_product'].'-'.$image['id_image'].'.jpg'))
				foreach ($productsTypes AS $k => $imageType)
				{
// какой тип перегенерить    				
                    if ($imageType['name'] == 'home')
                    {
                        echo $newFile = _PS_PROD_IMG_DIR_.$image['id_product'].'-'.$image['id_image'].'-'.stripslashes($imageType['name']).'.jpg';
                        if (!imageResize(_PS_PROD_IMG_DIR_.$image['id_product'].'-'.$image['id_image'].'.jpg', $newFile, intval($imageType['width']), intval($imageType['height'])))
						$errors = true;
						ob_get_flush();
						echo '<br>';

                    }
				}
		}


echo '<br><hr><br>';

//var_dump($a);


echo('</pre>');
printf("общее время %.2f сек", (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]));

?>

