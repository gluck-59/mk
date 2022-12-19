<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/tinkoff.php');
//$js_files = array('http://maps.google.com/maps?file=api&amp;v=2.x&amp></script>');

$confirm = Tools::getValue('confirm');

/* Validate order */
if ($confirm)
{
	$customer = new Customer(intval($cart->id_customer));
	$tinkoff = new tinkoff();
	$total = $cart->getOrderTotal(true, 3);
	$tinkoff->validateOrder(intval($cart->id), _PS_OS_tinkoff_, $total, $tinkoff->displayName);
	$order = new Order(intval($tinkoff->currentOrder));
	Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.intval($cart->id).'&id_module='.intval($tinkoff->id).'&id_order='.intval($tinkoff->currentOrder));
}
else
{
$address = new Address($cart->id_address_delivery);

// получим список товаров в корзине и выведем в tpl
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
		'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/tinkoff/'
	));

	$smarty->assign('this_path', __PS_BASE_URI__.'modules/tinkoff/');
	echo Module::display(__FILE__, 'validation.tpl');
}

include(dirname(__FILE__).'/../../footer.php');

