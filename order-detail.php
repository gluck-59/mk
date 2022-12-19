<?php
//echo(''.__FILE__.' - стр. '.__LINE__);            

/* Disable some cache related bugs on the cart/order */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// этот скрипт вызывается из history

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
require_once(dirname(__FILE__).'/init.php');
include_once(dirname(__FILE__).'/classes/Product.php');

include_once(dirname(__FILE__).'/adminka/gettrack.php');

//echo '<pre>';

if (!$cookie->isLogged())
	Tools::redirect('authentication.php?back=history.php');

$errors = array();

if (Tools::isSubmit('submitMessage'))
{
	$idOrder = intval(Tools::getValue('id_order'));
	$msgText = htmlentities(Tools::getValue('msgText'), ENT_COMPAT, 'UTF-8');

	if (!$idOrder OR !Validate::isUnsignedId($idOrder))
		$errors[] = Tools::displayError('order is no longer valid');
	elseif (empty($msgText))
		$errors[] = Tools::displayError('message cannot be blank');
	elseif (!Validate::isMessage($msgText))
		$errors[] = Tools::displayError('message is not valid (HTML is not allowed)');
	if(!sizeof($errors))
	{
	 	$order = new Order(intval($idOrder));
	 	if (Validate::isLoadedObject($order) AND $order->id_customer == $cookie->id_customer)
	 	{
			$message = new Message();
			$message->id_customer = intval($cookie->id_customer);
			$message->message = $msgText;
			$message->id_order = intval($idOrder);
			$message->private = false;
			$message->add();
			if (!Configuration::get('PS_MAIL_EMAIL_MESSAGE'))
				$to = strval(Configuration::get('PS_SHOP_EMAIL'));
			else
			{
				$to = new Contact(intval(Configuration::get('PS_MAIL_EMAIL_MESSAGE')));
				$to = strval($to->email);
			}
			$toName = strval(Configuration::get('PS_SHOP_NAME'));
			$customer = new Customer(intval($cookie->id_customer));
			if (Validate::isLoadedObject($customer))
				Mail::Send(intval($cookie->id_lang), 'order_customer_comment', 'Message from a customer', 
				array(
				'{lastname}' => $customer->lastname, 
				'{firstname}' => $customer->firstname, 
				'{id_order}' => intval($message->id_order), 
				'{message}' => $message->message),
				$to, $toName, $customer->email, $customer->firstname.' '.$customer->lastname);
			if (Tools::getValue('ajax') != 'true')
				Tools::redirect('order-detail.php?id_order='.intval($idOrder));
		}
		else
		{
			$errors[] = Tools::displayError('order not found');
		}
	}
}

if (!$id_order = intval(Tools::getValue('id_order')) OR !Validate::isUnsignedId($id_order))
	$errors[] = Tools::displayError('order ID is required');
else
{
	$order = new Order($id_order);
	if (Validate::isLoadedObject($order) AND $order->id_customer == $cookie->id_customer)
	{
		$id_order_state = intval($order->getCurrentState());
		$carrier = new Carrier(intval($order->id_carrier), intval($order->id_lang));
		$addressInvoice = new Address(intval($order->id_address_invoice));
		$addressDelivery = new Address(intval($order->id_address_delivery));
		if ($order->total_discounts > 0)
		    $smarty->assign('total_old', floatval($order->total_paid - $order->total_discounts));
		$products = $order->getProducts();
		
foreach ($products as &$orderdetail)
{
	$imgs = Product::getCover($orderdetail[product_id]);
    $orderdetail[image]=$imgs[id_image];
}

		$customizedDatas = Product::getAllCustomizedDatas(intval($order->id_cart));
		Product::addCustomizationPrice($products, $customizedDatas);

		$smarty->assign(array(
			'shop_name' => strval(Configuration::get('PS_SHOP_NAME')),
			'order' => $order,
			'return_allowed' => intval($order->isReturnable()),
			'currency' => new Currency($order->id_currency),
			'order_state' => intval($id_order_state),
			'invoiceAllowed' => intval(Configuration::get('PS_INVOICE')),
			'invoice' => (OrderState::invoiceAvailable(intval($id_order_state)) OR $order->invoice_number),
			'order_history' => $order->getHistory(intval($cookie->id_lang), false, true),
			'products' => $products,
			'discounts' => $order->getDiscounts(),
			'carrier' => $carrier,
			'address_invoice' => $addressInvoice,
			'invoiceState' => (Validate::isLoadedObject($addressInvoice) AND $addressInvoice->id_state) ? new State(intval($addressInvoice->id_state)) : false,
			'address_delivery' => $addressDelivery,
			'deliveryState' => (Validate::isLoadedObject($addressDelivery) AND $addressDelivery->id_state) ? new State(intval($addressDelivery->id_state)) : false,
			'messages' => Message::getMessagesByOrderId(intval($order->id)),
			'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
			'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
			'customizedDatas' => $customizedDatas));
	
		if ($carrier->url AND $order->shipping_number) 
        {
			$order->shipping_number = str_replace(" " ,";",$order->shipping_number); 
			$smarty->assign('followup', $carrier->url);

	
			$shipping_numbers = explode(";",$order->shipping_number);
			
/*			$from_country =array();
			foreach ($shipping_numbers as $shipping_number) 
				{
				
				// ищем название страны по ее ISO-коду
				$from = Db::getInstance()->getValue('
				SELECT l.`name`
				FROM `presta_country_lang` l
				join `presta_country` c on c.`id_country` = l.`id_country` and `id_lang` = 3
				where c.`iso_code` like "'.substr($shipping_number,-2).'"');
	
				$from_country[$shipping_number] = $from;
				}*/

			// запрашиваем трекинг посылки 
            foreach ($shipping_numbers as $shipping_number)
            {
                if ($shipping_number != NULL)
                {
                    // вычислим перевозчика 
                    $couriersResponse = send('detect', $shipping_number);
                    
                    if ($couriersResponse->length != 1)
                    {
                        // если посылка из Германии DHL, установим deutsche-post
                        if (preg_match('/^\d{12}$/', trim($shipping_number)))
                            $courierSlug = 'deutsche-post';
                        else // если посылка ХЗ чья, установим russian-post
                            $courierSlug = 'russian-post';
                    }
                    else 
                    {
                        $courierSlug = $couriersResponse->data[0]->courier->slug;
                    }

                    // отследим что получилось
                    $response = send($courierSlug, $shipping_number);
                
                    $checkpoints = $response->data->checkpoints;

//echo $response->error;
//print_r($response);die;
                    $track_last[$shipping_number] = new stdClass(); // иначе php 5 весь обругается
                    $track_last[$shipping_number]->shipping_number = $shipping_number;                
                    
                    if ($response->error)
                    {
                        $push = new Push('self');
                        $push->title = 'Гдепосылка-Кабинет ошибка:';
                        $push->message = $shipping_number.' Ошибка из curl: '.$response->error;
                        $push->url = 'https://pushall.ru/';
                        $push->send();
                        
                    }
                    else
                    {
                        $track[$shipping_number]['checkpoints'] = array_reverse($checkpoints);
                        
                        $extra = (array)$response->data->extra[0]->data;
                        $weight = $extra['weight.actual'];
                        $recipient = $extra['recipient.title'];
                        $service = $extra['service.name'];
                        
                        $track[$shipping_number]['weight'] = $weight;                        
                        $track[$shipping_number]['recipient'] = $recipient;
                        $track[$shipping_number]['service'] = $service;
                        $track[$shipping_number]['shipping_number'] = $shipping_number;
                        
                        foreach ($checkpoints as $checkpoint)
                        {
                            $track_last[$shipping_number]->checkpoints = $checkpoint;
                            break;
                        }
                    }
                }
            }


//print_r($track_last);
echo '</pre>';
			 
			$smarty->assign('track', $track);
			$smarty->assign('track_last', $track_last);
			$smarty->assign('shipping_numbers', $shipping_numbers);
		}
	}
	else
		$errors[] = Tools::displayError('cannot find this order');
}

$smarty->assign('errors', $errors);
if (Tools::getValue('ajax') == 'true')
	$smarty->display(_PS_THEME_DIR_.'order-detail.tpl');
else
{
	include(dirname(__FILE__).'/header.php');
	$smarty->display(_PS_THEME_DIR_.'order-detail.tpl');
	include(dirname(__FILE__).'/footer.php');
}

