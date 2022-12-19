<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/product-sort.php');


function getSharedWislists($pageNumber = 0, $nbProducts = 20, $count = false)
{
	if ($pageNumber < 0) $pageNumber = 0;
	if ($nbProducts < 1) $nbProducts = 20;

		if ($count)
		{
			$result = Db::getInstance()->getRow('
			SELECT count(c.`id_customer`) as nb,  c.`id_customer`, c.`firstname`, wp.`id_product`, pl.`name`, pl.`description_short`, i.`id_image`, a.`other`
			FROM `presta_wishlist` w
			join `presta_customer` c ON c.`id_customer` = w.`id_customer`
			join `presta_wishlist_product` wp ON w.`id_wishlist` = wp.`id_wishlist`
			join `presta_product_lang` pl ON pl.`id_product` = wp.`id_product`
			JOIN `presta_image` i ON (i.`id_product` = pl.`id_product` AND i.`cover` = 1)
			join `presta_address` a ON (a.`id_customer` = w.`id_customer` AND a.`deleted` = 0) 
			');
			return intval($result['nb']);
		}

	$result = Db::getInstance()->ExecuteS('
	SELECT /*DISTINCT*/ c.`id_customer`, c.`firstname`, wp.`id_product`, pl.`name`, pl.`description_short`, i.`id_image`, a.`other`
	FROM `presta_wishlist` w
	join `presta_customer` c ON c.`id_customer` = w.`id_customer`
	join `presta_wishlist_product` wp ON w.`id_wishlist` = wp.`id_wishlist`
	join `presta_product_lang` pl ON pl.`id_product` = wp.`id_product`
	JOIN `presta_image` i ON (i.`id_product` = pl.`id_product` AND i.`cover` = 1)
	join `presta_address` a ON (a.`id_customer` = w.`id_customer` AND a.`deleted` = 0) 
	ORDER BY c.`id_customer`
	
	LIMIT '.intval($pageNumber * $nbProducts).', '.intval($nbProducts).'
	');

	return Product::getProductsProperties(3, $result);
}


$nbProducts = intval(getSharedWislists(isset($p) ? intval($p) - 1 : NULL, isset($n) ? intval($n) : NULL, true));
include(dirname(__FILE__).'/pagination.php');

$products = getSharedWislists(intval($p) - 1, intval($n), false);

$smarty->assign(array(
	'products' => $products,
	'nbProducts' => intval($nbProducts),
	'p' => $p
	));

$smarty->display(_PS_THEME_DIR_.'wishlists.tpl');

/*
echo '<pre>';
print_r($nbProducts);
echo '</pre>';
*/

include(dirname(__FILE__).'/footer.php');

?>
