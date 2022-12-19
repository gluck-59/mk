<?php

require_once(realpath(dirname(__FILE__).'/../../').'/config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

$ind = intval(Tools::getValue('ind'));
if ( $ind > Configuration::get('RECENT_ORDER_NUMBER') )
    die;
    
$select = '
    a.id_order AS id_pdf,
	CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
	osl.`name` AS `osname`,
	os.`color`,
	IF((SELECT COUNT(so.id_order) FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = a.id_customer AND so.valid = 1) > 1, 0, 1) as new,
	(SELECT COUNT(od.`id_order`) FROM `'._DB_PREFIX_.'order_detail` od WHERE od.`id_order` = a.`id_order` GROUP BY `id_order`) AS product_number';
	
$join = '
    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)
    LEFT JOIN `'._DB_PREFIX_.'order_history` oh ON (oh.`id_order` = a.`id_order`)
    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = oh.`id_order_state`)
    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.intval($cookie->id_lang).')';

$where = '
    AND oh.`id_order_history` = (SELECT MAX(`id_order_history`) FROM `'._DB_PREFIX_.'order_history` moh WHERE moh.`id_order` = a.`id_order` AND a.`valid` = 1 GROUP BY moh.`id_order`)';

$sql = '
    SELECT SQL_CALC_FOUND_ROWS
	a.*, '.$select.'
	FROM `'._DB_PREFIX_.'orders` a
	'.$join.'
	WHERE 1 '.$where.'
	ORDER BY a.`'.pSQL('date_add').'` 
	'.pSQL('DESC limit '.(!$ind ? '0' : $ind+5 ).', '.(!$ind ? '5' : '1').' ');
	
//		'.pSQL('DESC limit '.$ind.','.Configuration::get('RECENT_ORDER_NUMBER').' ');

$orders = Db::getInstance()->ExecuteS($sql);

foreach($orders as $item)
{
	$order = new Order($item['id_order']);
	$addressDelivery = new Address($order->id_address_delivery, intval($cookie->id_lang));
	$products = $order->getProducts();
	$customer = $order = new Address($order->id_address_delivery, intval($cookie->id_lang));
	$products = array_slice($products, 0, 1);
	$product = new Product($products[0]['product_id']);
	$images = $product->getImages(intval($cookie->id_lang));
	$img = $products[0]['product_id']."-".$images[0]['id_image']."-medium.jpg";
	
	$result[] = array(
	                  'index' => $ind++,
	                  'product_name' => $products[0]['product_name'],
					  'product_link' => $product->getLink(),
					  'address' => $addressDelivery->city,
					  'biker' => $customer->firstname,
					  'img' => _THEME_PROD_DIR_.$img
					);
}

foreach ($result as $item)
{
     $smarty->assign(array(
    'item' => $item,
    'a' => _PS_MODULE_DIR_
    ));
     $smarty->display(_PS_MODULE_DIR_.'/recentorders_ajax/recentorders_ajax.tpl');
}


//print_r($item);

?>