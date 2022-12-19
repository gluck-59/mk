<?php

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/product-sort.php');

//header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

$nbProducts = intval(Product::getNewProducts(intval($cookie->id_lang), isset($p) ? intval($p) - 1 : NULL, isset($n) ? intval($n) : NULL, true));
include(dirname(__FILE__).'/pagination.php');

$products = Product::getNewProducts(intval($cookie->id_lang), intval($p) - 1, intval($n), false, 'date_upd', 'desc');

$smarty->assign(array(
	'products' => $products,
	'nbProducts' => intval($nbProducts)));

$smarty->display(_PS_THEME_DIR_.'new-products.tpl');

include(dirname(__FILE__).'/footer.php');

?>
