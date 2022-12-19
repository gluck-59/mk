<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/card2card.php');

$confirm = Tools::getValue('confirm');

/* Validate order */
if ($confirm)
{
	$customer = new Customer(intval($cart->id_customer));
	$card2card = new card2card();
	$total = $cart->getOrderTotal(true, 3);
	$card2card->validateOrder(intval($cart->id), _PS_OS_card2card_, $total, $card2card->displayName);
	$order = new Order(intval($card2card->currentOrder));
	Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.intval($cart->id).'&id_module='.intval($card2card->id).'&id_order='.intval($card2card->currentOrder));
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
		'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/card2card/'
	));

	$smarty->assign('this_path', __PS_BASE_URI__.'modules/card2card/');
	echo Module::display(__FILE__, 'validation.tpl');
}

include(dirname(__FILE__).'/../../footer.php');

