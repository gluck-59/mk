<?php

class PaypalPayment extends PaypalAPI
{
	protected $_logs = array();

	public function getAuthorisation()
	{
    	$paypal_comission = 1.039;
		global $cookie, $cart;

		// Getting cart informations
		$currency = new Currency(3);
//		$currency = new Currency(intval($cookie->id_currency)); //оригинал
		if (!Validate::isLoadedObject($currency))
			$this->_logs[] = $this->l('Not a valid currency');
		if (sizeof($this->_logs))
			return false;
   		$currencyCodeType = strval($currency->iso_code);			

        $customer = new Customer($cookie->id_customer);			
            $email = $customer->email;
            //$buyerregistrationdate = $customer->date_add;
        
        $address = new Address($cart->id_address_delivery);			
            $shiptoname = $address->firstname.' '.$address->lastname;
            $shiptostreet = $address->address1;
            $shiptocity = $address->city;
            $shiptozip = $address->postcode;
            $shiptocountry = $address->iso_country;
            $shiptophonenum = $address->phone_mobile;
            



/*
//echo '<pre>';
$products = $cart->getProducts();
$key = 0;
foreach ($products as $product)
{
//	$products[$key]['name'] = str_replace('"', '\'', $product['name']);
//	if (isset($product['attributes']))
//		$products[$key]['attributes'] = str_replace('"', '\'', $product['attributes']);
        
        //$products[$key]['name'] = $product['name'];

    //$products[$key]['paypalAmount'] = number_format(Tools::convertPrice($product['price_wt'], $currency), 2, '.', '');
    $request = '&L_PAYMENTREQUEST_0_NAME'.$key.'='.trim($product['name']).'&L_PAYMENTREQUEST_0_ITEMAMT'.$key.'='.number_format(Tools::convertPrice($product['price_wt'], $currency), 2, '.', '').'&L_PAYMENTREQUEST_0_QTY'.$key.'='.$product['quantity'].'&L_PAYMENTREQUEST_0_AMT'.$key.'='.round((number_format(Tools::convertPrice($product['price_wt'], $currency), 2, '.', '') * $product['quantity'] * $paypal_comission)).'&L_PAYMENTREQUEST_0_CURRENCYCODE'.$key.'='.urlencode($currencyCodeType).'&L_PAYMENTREQUEST_0_DESC'.$key.'= '.strip_tags($product['description_short']);
    $key = $key++;
//echo '<br>';	 
}



$shipping = $cart->getOrderShippingCost();
$shipping = number_format(Tools::convertPrice($shipping, $currency), 2, '.', '');

*/



		// Making request
		$vars = '?fromPayPal=1';
		$returnURL = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/paypalapi/payment/submit.php'.$vars;
		$cancelURL = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'order.php';
		$paymentAmount = number_format(floatval(Tools::convertPrice($cart->getOrderTotal(),$currency)), 2, '.', '');//оригинал 
        $paymentAmount = round($paymentAmount * $paypal_comission);
//$paymentAmount = round($paymentAmount * 1.039);  // комиссия Paypal
		$paymentType = 'Sale';
		$request .= '&AMT='.urlencode($paymentAmount).'&PAYMENTACTION='.urlencode($paymentType).'&ReturnUrl='.urlencode($returnURL).'&CANCELURL='.urlencode($cancelURL).'&CURRENCYCODE='.urlencode($currencyCodeType).'&NOSHIPPING=2'.'&ALLOWNOTE=1';
		
		// передаем адрес доставки в пайпал
        $request .=	'&ADDROVERRIDE=1'.'&EMAIL='.$email.'&shiptoname='.$shiptoname.'&SHIPTOSTREET='.$shiptostreet.'&SHIPTOCITY='.$shiptocity.'&SHIPTOZIP='.$shiptozip.'&SHIPTOCOUNTRY='.$shiptocountry.'&SHIPTOPHONENUM='.$shiptophonenum;
        
        // передаем доставку
        $request .= '&PAYMENTREQUEST_0_SHIPPINGAMT='.$shipping;

        
        
		if ($this->_header)
			$request .= '&HDRIMG='.urlencode($this->_header);


//echo $paymentAmount.$currencyCodeType;exit;			
//echo '<pre>';
//print_r($products);exit;			

		// Calling PayPal API
		include(_PS_MODULE_DIR_.'paypalapi/api/PaypalLib.php');
		$ppAPI = new PaypalLib();
		$result = $ppAPI->makeCall($this->getAPIURL(), $this->getAPIScript(), 'SetExpressCheckout', $request);
		$this->_logs = array_merge($this->_logs, $ppAPI->getLogs());
//print_r($result);die;		
		return $result;
	}

	public function home($params)
	{
		return $this->display(__FILE__.'../', 'payment.tpl');
	}

	public function getLogs()
	{
		return $this->_logs;
	}
}