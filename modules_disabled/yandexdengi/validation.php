<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/yandexdengi.php');

$yandexdengi = new YandexDengi();

if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$yandexdengi->active)
	Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

$currency = new Currency(intval(isset($_POST['currency_payement']) ? $_POST['currency_payement'] : $cookie->id_currency));
$total = floatval($cart->getOrderTotal(true, 3));
$mailVars = array(
	'{yandexdengi_owner}' => Configuration::get('YANDEX_MONEY_OWNER'),
	'{yandexdengi_details}' => nl2br(Configuration::get('YANDEX_MONEY_DETAILS')),
	'{yandexdengi_address}' => nl2br(Configuration::get('YANDEX_MONEY_ADDRESS'))
);

$yandexdengi->validateOrder($cart->id, _PS_OS_BANKWIRE_, $total, $yandexdengi->displayName, NULL, $mailVars, $currency->id);
$order = new Order($yandexdengi->currentOrder);
Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$yandexdengi->id.'&id_order='.$yandexdengi->currentOrder.'&key='.$order->secure_key);
?>