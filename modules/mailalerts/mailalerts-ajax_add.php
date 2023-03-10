<?php
	require_once(dirname(__FILE__).'/../../config/config.inc.php');
	require_once(dirname(__FILE__).'/../../init.php');

	if (!$id_product = intval(Tools::getValue('id_product')))
		die ('0');

	$id_product_attribute = intval(Tools::getValue('id_product_attribute'));

	if (!$cookie->isLogged())
	{
		$id_customer = 0;
		$customer_email = strval(Tools::getValue('customer_email'));
		
		// Check if already in dbb
		$query = '
			SELECT * FROM `'._DB_PREFIX_.'mailalert_customer_oos` 
			WHERE `id_customer` = '.intval($id_customer).'
			AND `customer_email` = \''.pSQL($customer_email).'\'
			AND `id_product` = '.intval($id_product).'
			AND `id_product_attribute` = '.intval($id_product_attribute);
		if (Db::getInstance()->ExecuteS($query))
			die('1');
		$customer_name = $customer_email;
	}
	else
	{
		$id_customer = intval($cookie->id_customer);
		$customer = new Customer($id_customer);
		$customer_email = $customer->email;
		$customer_name = $customer->firstname.' '.$customer->lastname;
	}


	//http://motokofr.com/modules/mailalerts/mailalerts-ajax_add.php?id_product=2426&id_product_attribute=0&customer_email=japancars@perm.ru
	
	
$name = Db::getInstance()->ExecuteS('
SELECT name
FROM `'._DB_PREFIX_.'product_lang` 
WHERE `id_product` = '.$id_product);

	
			Mail::Send(3, 'oos', 'Запрос товара от '.$customer_name, 
			array(
			'{name}' => $customer_name, 
			'{url}' => 'product.php?id_product='.$id_product,
			'{product}' => $name[0]['name'],
			'{email}' => $customer_email),
			'support@motokofr.com', 'gluck', $customer_email, $customer_name, NULL, NULL);
	


	$query = '
		INSERT INTO `'._DB_PREFIX_.'mailalert_customer_oos` (`id_customer`, `customer_email`, `id_product` , `id_product_attribute`)
		VALUES ('.intval($id_customer).', \''.pSQL($customer_email).'\', '.intval($id_product).', '.intval($id_product_attribute).')';

	if (Db::getInstance()->Execute($query))
		die ('1');
	die ('0');
?>
