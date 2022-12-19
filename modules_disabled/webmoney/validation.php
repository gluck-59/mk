<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/webmoney.php');
	$webmoney = new Webmoney();


$ikey = Configuration::get('WEBMONEY_KEY');
$cart = new Cart(intval($_POST['LMI_PAYMENT_NO']));
	$currency = new Currency(intval($cart->id_currency));
	
		if ($currency->iso_code == 'RUB')
			{$purse = Configuration::get('WEBMONEY_PURSE_R');}
		elseif ($currency->iso_code == 'USD')
			{$purse = Configuration::get('WEBMONEY_PURSE_Z');}
		elseif ($currency->iso_code == 'EUR')
			{$purse = Configuration::get('WEBMONEY_PURSE_E');}
		
$order_amount = number_format(Tools::convertPrice($cart->getOrderTotal(true, 3), $currency), 2, '.', '');

/*
echo '<pre>';
print_r($order_amount);die;
*/



if ($_POST)
	{

		if($_POST['LMI_PREREQUEST'] == 1)
			{
					if ($purse==$_POST['LMI_PAYEE_PURSE'] && $_POST['LMI_MODE'] == 0 && $cart->id==$_POST['LMI_PAYMENT_NO'] && $order_amount==$_POST['LMI_PAYMENT_AMOUNT']) {echo 'YES'; exit;} else {echo 'WRONG';}
			}
		if (isset($_POST['LMI_HASH']))
			{
				$crc = strtoupper(md5($_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].$ikey.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM']));
					if ($crc == strtoupper($_POST['LMI_HASH'])) 
						{
							$webmoney->validateOrder($cart->id, _PS_OS_PAYMENT_, $_POST['LMI_PAYMENT_AMOUNT'], $webmoney->displayName, NULL, NULL);
								$order = new Order($webmoney->currentOrder);
								Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$webmoney->id.'&id_order='.$webmoney->currentOrder.'&key='.$order->secure_key);
						}	
			}
	}
else 
	{
		Tools::redirectLink(__PS_BASE_URI__.'order.php');
	}

// what? -> to order
Tools::redirectLink(__PS_BASE_URI__.'order.php');
?>