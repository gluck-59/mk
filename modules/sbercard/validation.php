<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/sbercard.php');

$confirm = Tools::getValue('confirm');

/* Validate order */
if ($confirm)
{
	$customer = new Customer(intval($cart->id_customer));
	$sbercard = new sbercard();
	$total = $cart->getOrderTotal(true, 3);
	$sbercard->validateOrder(intval($cart->id), _PS_OS_sbercard_, $total, $sbercard->displayName);
	$order = new Order(intval($sbercard->currentOrder));
	Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.intval($cart->id).'&id_module='.intval($sbercard->id).'&id_order='.intval($sbercard->currentOrder));
}
else
{
// получим список товаров в корзине и выведем в tpl
$address = new Address($cart->id_address_delivery);
$products = $cart->getProducts();

$currency = new Currency($cart->id_currency);
$total = number_format($cart->getOrderTotal(true, 3), 2, '.', '');

	/* or ask for confirmation */ 
	$smarty->assign(array(
		'products' => $products,
		'total' => $total,
		'id_address' => $cart->id_address_delivery,
		'address' => $address,		
		'total_terminal' => ($total * $currency->conversion_rate),
		'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/sbercard/'
	));

	$smarty->assign('this_path', __PS_BASE_URI__.'modules/sbercard/');
	echo Module::display(__FILE__, 'validation.tpl');
}

include(dirname(__FILE__).'/../../footer.php');

