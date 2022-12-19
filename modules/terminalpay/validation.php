<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/terminalpay.php');

$currency = new Currency(intval(isset($_POST['currency_payement']) ? $_POST['currency_payement'] : $cookie->id_currency));
$total = floatval(number_format($cart->getOrderTotal(true, 3), 2, '.', ''));
$mailVars = array(
	'{terminalpay_owner}' => Configuration::get('TERMINAL_PAY_OWNER'),
    '{terminalpay_icq}' => Configuration::get('TERMINAL_PAY_ICQ'),
    '{terminalpay_pochta}' => Configuration::get('TERMINAL_PAY_POCHTA'),
    '{terminalpay_skype}' => Configuration::get('TERMINAL_PAY_SKYPE'),
    '{terminalpay_paysys}' => Configuration::get('TERMINAL_PAY_PAYSYS'),
);

$terminalpay = new TerminalPay();
$terminalpay->validateOrder($cart->id, _PS_OS_TERMINALPAY_, $total, $terminalpay->displayName, NULL, $mailVars, $currency->id);
$order = new Order($terminalpay->currentOrder);
Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$terminalpay->id.'&id_order='.$terminalpay->currentOrder.'&key='.$order->secure_key);
?>