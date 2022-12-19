<?php

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/product-sort.php');

$nbProducts = Product::getPricesDrop(intval($cookie->id_lang), NULL, NULL, true);
include(dirname(__FILE__).'/pagination.php');

$smarty->assign(array(
	'products' => Product::getPricesDrop(intval($cookie->id_lang), intval($p) - 1, 50, false, $orderBy, $orderWay),
	'nbProducts' => $nbProducts));

$smarty->display(_PS_THEME_DIR_.'prices-drop.tpl');

include(dirname(__FILE__).'/footer.php');

//echo '<!--pre>jo';
//print_r(Product::getPricesDrop(intval($cookie->id_lang), $orderBy, $orderWay));
//echo '</pre-->';

?>