<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/westernunion.php');

$currency = new Currency(intval(isset($_POST['currency_payement']) ? $_POST['currency_payement'] : $cookie->id_currency));
$total = floatval(number_format($cart->getOrderTotal(true, 3), 2, '.', ''));
$mailVars = array(
	'{westernunion_owner}' => Configuration::get('WESTERNUNION_OWNER'),
	'{westernunion_details}' => nl2br(Configuration::get('WESTERNUNION_DETAILS')),
	'{westernunion_address}' => nl2br(Configuration::get('WESTERNUNION_ADDRESS'))
);

$westernunion = new WesternUnion();
$westernunion->validateOrder($cart->id, _PS_OS_BANKWIRE_, $total, $westernunion->displayName, NULL, $mailVars, $currency->id);
$order = new Order($westernunion->currentOrder);
Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$westernunion->id.'&id_order='.$westernunion->currentOrder.'&key='.$order->secure_key);
?>