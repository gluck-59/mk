<?php

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/product-sort.php');

function findTags($tag, $id_lang, $orderBy = false, $orderWay = false, $pgNumber = 0, $nb = 20, $count = false)
{	
	if ($count)
		{
			$count = Db::getInstance()->ExecuteS('
			SELECT count(pt.id_product) as nb, t.name tagname, l.name, l.name, l.description_short, p.price, i.id_image, p.quantity, pt.id_tag
			FROM presta_product_tag pt
			LEFT JOIN presta_product_lang l
			ON (pt.id_product = l.id_product)
			LEFT JOIN presta_product p
			ON (p.id_product = l.id_product)
			LEFT JOIN presta_image i
			ON (i.id_product = l.id_product and cover = "1")
			LEFT JOIN presta_tag t 
			ON (pt.id_tag = t.id_tag AND t.id_lang = '.intval($id_lang).')
			WHERE p.`active` = 1
			AND t.name = "'.pSQL($tag).'"
			');
			return intval($count[0]['nb']);
		}
		
	else
		{	
			if ($pgNumber < 0) $pgNumber = 0;
			if ($nb < 1) $nb = 20;
			
			if ($orderBy == false)
				$orderBy = 'price';
							
			if ($orderWay == false)
			   $orderWay = 'asc';
								
			if ($orderBy == 'price')
				$orderByPrefix = 'p';
				
			elseif ($orderBy == 'name')
				    $orderByPrefix = 'l';
				    
			$result = Db::getInstance()->ExecuteS('
			SELECT SQL_CALC_FOUND_ROWS pt.id_product, t.name tagname, l.name, l.name, l.description_short, p.price, i.id_image, p.quantity, pt.id_tag
			FROM presta_product_tag pt
			LEFT JOIN presta_product_lang l
			ON (pt.id_product = l.id_product)
			LEFT JOIN presta_product p
			ON (p.id_product = l.id_product)
			LEFT JOIN presta_image i
			ON (i.id_product = l.id_product and cover = "1")
			LEFT JOIN presta_tag t 
			ON (pt.id_tag = t.id_tag AND t.id_lang = '.intval($id_lang).')
			WHERE p.`active` = 1
			AND t.name = "' . pSQL($tag) . '" 
			ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
			LIMIT '.intval($pgNumber * $nb).', '.intval($nb)
			);
			return Product::getProductsProperties($id_lang, $result);
	 	}
	 	
}

$tag = $_GET['tag'];
$orderby = $_GET['orderby'];
$orderway = $_GET['orderway'];

$nbProducts = findTags($tag, $cookie->id_lang, false, false, false, false, true);
include(dirname(__FILE__).'/pagination.php');
$result = findTags($tag, $cookie->id_lang, $orderby, $orderway, isset($p) ? intval($p - 1) : NULL, isset($n) ? $n : NULL);

$smarty->assign(array(
	'products' => $result,
	'tag' => $tag,
	'nbProducts' => intval($nbProducts)
));

$smarty->display(_PS_THEME_DIR_.'tags.tpl');

include(dirname(__FILE__).'/footer.php');

?>