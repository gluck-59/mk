<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/liqpay.php');

$errors = '';
$result = false;
$liqpay = new liqpay();
 
$merchant_pass = Configuration::get('LIQPAY_MERCHANT_PASS');

$xml = base64_decode($_POST['operation_xml']);
$signature = base64_encode(sha1($merchant_pass.$xml.$merchant_pass, 1));

if($_POST['signature']==$signature) {
    
    $xml_arr = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $id_cart = array_pop(explode('_', $xml_arr->order_id));
    
    if($xml_arr->status=='success') {
        $liqpay->validateOrder($id_cart, _PS_OS_PAYMENT_, $xml_arr->amount, $liqpay->displayName, 'LiqPay Transaction ID: '.$xml_arr->transaction_id);
    } elseif($xml_arr->status=='wait_secure') {
        $liqpay->validateOrder($id_cart, _PS_OS_BANKWIRE_, $xml_arr->amount, $liqpay->displayName);
    } elseif($xml_arr->status=='failure') {
        $liqpay->validateOrder($id_cart, _PS_OS_ERROR_, 0, $liqpay->displayName, $errors.'<br />');
    }
    echo $liqpay->getL($xml_arr->status);
}

?>