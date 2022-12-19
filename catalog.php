<?php
include(dirname(__FILE__).'/config/config.inc.php');

include_once(dirname(__FILE__).'/init.php');

/*$js_files = array(
	__PS_BASE_URI__.'js/jquery/jquery.idTabs.modified.js',
	__PS_BASE_URI__.'js/jquery/jquery.scrollto.js',
	__PS_BASE_URI__.'js/jquery/jquery.serialScroll.js',
	_THEME_JS_DIR_.'tools.js',
	_THEME_JS_DIR_.'product.js'
);
*/

global $errors;
$errors = array();

// предзагрузим 
	$preload = array("//motokofr.com/catalog/kuryakyn/index.html", "//motokofr.com/catalog/bbp/index.html", "//motokofr.com/catalog/highway_hawk/index.html");
include_once(dirname(__FILE__).'/header.php');


//print_r ($_POST);
//$name = array('Show Chrome', 'Kuryakyn');




/* $smarty->assign(array(
	'url' => $url,
	'name' => $name
	));
*/	


	

$smarty->display(_PS_THEME_DIR_.'catalog.tpl');
include(dirname(__FILE__).'/footer.php');

?>
