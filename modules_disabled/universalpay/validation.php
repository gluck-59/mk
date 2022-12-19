<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/universalpay.php');

if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0)
	Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

$universalpay = new universalpay();
$universalpay->validation($cart);