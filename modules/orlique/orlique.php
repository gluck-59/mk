<?php
/*
 * Orlique
 *
 * An Order Manager for Prestashop
 *
 * @package		Silbersaiten
 * @author		George June <j.june@silbersaiten.de>
 * @copyright	Copyright (c) 2010, Silbersaiten GbR
 * @license		End User License Agreement (EULA)
 * @link		http://silbersaiten.de
 * @since		Version 1.1.1
 */
@ini_set('display_errors', 'off');

class Orlique extends Module
{
    private $_html = '';
    private $_postErrors = array();
	private static $_tblCache = array();
    
    public function __construct()
    {
		global $smarty;
		
        $this->name    = 'orlique';
        $this->tab     = 'Tools';
        $this->version = '1.2.2';
        
        parent::__construct();
        
        $this->displayName = $this->l('Orlique');
        $this->description = $this->l('Silbersaiten Order Manager');
		
		include_once(_PS_ROOT_DIR_ . '/modules/' . $this->name . '/classes/OrliqueHelper.php');
		include_once(_PS_ROOT_DIR_ . '/modules/' . $this->name . '/classes/OrliqueInvoice.php');
		
		self::smartyRegisterFunction($smarty, 'displayPrice', array('Tools', 'displayPriceSmarty'));
    }

	/*
	 * Registers smarty function for price display, in 1.4.x it's already
	 * registered in config/smarty.config.inc.php , however, in earlier versions
	 * this function is registered in init.php file
	 *
	 * @access public
	 * @scope static
	 *
	 * @param object $smarty
	 * @param string $function
	 * @param array  $params
	 * 
	 * @return void
	 */
	protected static function smartyRegisterFunction($smarty, $function, $params)
	{
		$existingFunctions = property_exists($smarty, 'registered_plugins') ? $smarty->registered_plugins : $smarty->_plugins;
		$existingFunctions = (is_array($existingFunctions) && array_key_exists('function', $existingFunctions)) ? $existingFunctions['function'] : false;

		if ($existingFunctions && ! array_key_exists($function, $existingFunctions))
		{
			$smarty2 = Configuration::get('PS_FORCE_SMARTY_2');
			
			if ($smarty2 || $smarty2 === false)
				$smarty->register_function($function, $params);
			else
				$smarty->registerPlugin('function', $function, $params);
		}
	}
	
	
	/*
	 * Installs the module
	 *
	 * @access public
	 * @return boolean
	 */
	public function install()
	{
		if ( ! parent::install() || ! $this->registerHook('PDFInvoice'))
			return false;
		
		$queries = array(
			'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'orlique_invoice` (
				`id_orlique_invoice` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_order` int(10) unsigned NOT NULL,
				`active` tinyint(1) unsigned NOT NULL DEFAULT "0",
				PRIMARY KEY (`id_orlique_invoice`)
			  ) ENGINE=' . (defined('ENGINE_TYPE') ? ENGINE_TYPE : 'MyISAM'),
			  
			'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'orlique_invoice_lang` (
				`id_orlique_invoice` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`content` TEXT,
				PRIMARY KEY (`id_orlique_invoice`,`id_lang`)
			  ) ENGINE=' . (defined('ENGINE_TYPE') ? ENGINE_TYPE : 'MyISAM')
		);
		
		// If any of the queries failed, we need to first delete the tables
		// that could have been created to avoid an error of type "A table with
		// such name already exists in a database", which is why we first
		// call the "uninstall" method, and only after that return false
		foreach ($queries as $query)
			if ( ! Db::getInstance()->Execute($query))
			{
				parent::uninstall();
				
				return false;
			}
		
		return $this->installModuleTab('AdminOrderEdit', 'Order Manager');
	}
	
	
	/*
	 * Uninstalls the module
	 *
	 * @access public
	 * @return boolean
	 */
	public function uninstall()
	{
		$sql = '
		SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `module` = "' . pSQL($this->name) . '"';
		
		$result = Db::getInstance()->ExecuteS($sql);
		
		if ($result && sizeof($result))
		{
			foreach ($result as $tabData)
			{
				$tab = new Tab($tabData['id_tab']);
				
				if (Validate::isLoadedObject($tab))
					$tab->delete();
			}
		}
		
		if (self::tableExists(_DB_PREFIX_ . 'orlique_invoice'))
			Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'orlique_invoice`');
		
		if (self::tableExists(_DB_PREFIX_ . 'orlique_invoice_lang'))
			Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'orlique_invoice_lang`');
		
		return parent::uninstall();
	}
	
	
	/*
	 * Checks if table exists in the database
	 *
	 * @access private
	 * @scope static
	 * @param string $table    - Table name to check
	 *
	 * @return boolean
	 */
    private static function tableExists($table, $useCache = true)
    {
        if ( ! sizeof(self::$_tblCache) || ! $useCache)
        {
            $tmp = Db::getInstance()->ExecuteS('SHOW TABLES');
        
            foreach ($tmp as $entry)
            {
                reset($entry);
                
                $tableTmp = strtolower($entry[key($entry)]);
                
                if ( ! array_search($tableTmp, self::$_tblCache))
                    self::$_tblCache[] = $tableTmp;
            }
        }
        
        return array_search(strtolower($table), self::$_tblCache) ? true : false;
    }
    
	
	/*
	 * Copies Orlique logo to img/t, so that it would display in the backoffice
	 * like other tabs do.
	 *
	 * @access private
	 * @param string $class    - Class name, like "AdminOrders"
	 *
	 * @return boolean
	 */
    private function copyLogo($class)
    {
        return @copy(dirname(__FILE__) . '/logo.gif', _PS_IMG_DIR_ . 't/' . $class . '.gif');
    }
    
	
	/*
	 * Creates a "subtab" in "Orders" tab.
	 *
	 * @access private
	 * @param string $class    - Class name, like "AdminOrders"
	 * @param string $name     - Tab title
	 *
	 * @return boolean
	 */
    private function installModuleTab($class, $name)
    {
		$sql = '
		SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `class_name` = "AdminOrders"';
		
		$tabParent = (int)(Db::getInstance()->getValue($sql));
		
        if ( ! is_array($name))
            $name = self::getMultilangField($name);
            
        if (self::fileExistsInModulesDir('logo.gif') && is_writeable(_PS_IMG_DIR_ . 't/'))
            $this->copyLogo($class);
                
        $tab = new Tab();
        $tab->name       = $name;
        $tab->class_name = $class;
        $tab->module     = $this->name;
        $tab->id_parent  = $tabParent;
		
		$tab->save();
        
        if ( ! $tab->id)
        {
            return false;
        }
        
        return self::checkAdminAccess($tab->id);
    }
	
	
    private static function checkAdminAccess($tab_id)
    {
        if ( ! Validate::isUnsignedId($tab_id))
        {
            return false;
        }
        
        $tabInstalled = (bool)Db::getInstance()->getValue('
            SELECT
                1
            FROM
                `' . _DB_PREFIX_ . 'access`
            WHERE
                `id_tab` = ' . (int)$tab_id
        );
        
        if ($tabInstalled)
        {
            return true;
        }
        
        return Db::getInstance()->autoExecute(
            _DB_PREFIX_ . 'tab',
            array(
                'id_tab'     => (int)$tab_id,
                'id_profile' => 1,
                'view'       => 1,
                'add'        => 1,
                'edit'       => 1,
                'delete'     => 1
            ),
            'INSERT'
        );
    }
    
	
	/*
	 * Tests if a file exists in /modules/orlique
	 *
	 * @access private
	 * @scope static
	 * @param string $file    - A file to look for
	 *
	 * @return array
	 */
    private static function fileExistsInModulesDir($file)
    {
        return file_exists(dirname(__FILE__) . '/' . $file);
    }
	
	
	/*
	 * Basically a dummy method, kept for compatibility and will be removed
	 * @return boolean
	 */
    public static function checkEmployee($id_employee, $passwd)
    {
        return Employee::checkPassword($id_employee, $passwd);
    }


	/*
	 * Turns a string into an array with language IDs as keys. This array can
	 * be used to create multilingual fields for prestashop
	 *
	 * @access private
	 * @scope static
	 * @param mixed $field    - A field to turn into multilingual
	 *
	 * @return array
	 */
    private static function getMultilangField($field)
    {
        $languages = Language::getLanguages();
        $res = array();
        
        foreach ($languages as $lang)
            $res[$lang['id_lang']] = $field;
            
        return $res;
    }
    
	
	/*
	 * Returns a list of products for an autofill box
	 *
	 * @access public
	 * @param integer $id_lang - Language ID
	 * @param string $query    - A search query to search the database for
	 *
	 * @return void
	 */
	public function ajaxProductList($id_lang, $query)
    {
        if ( ! $query || $query == '' || strlen($query) < 1)
	        die();
			
		$sql = '
        SELECT  p.`id_product`,
                p.`reference` ,
               pl.`name`
        FROM `' . _DB_PREFIX_ . 'product` p
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
            ON  (
                    pl.id_product = p.id_product
                )
        WHERE (p.id_product LIKE "%' . pSQL($query) . '%" OR pl.name LIKE "%' . pSQL($query) . '%" OR  p.reference LIKE "%' . pSQL($query) . '%")
        AND pl.id_lang = ' . (int)($id_lang);

        $products = Db::getInstance()->ExecuteS($sql);
		
        if ($products && sizeof($products))
	        foreach ($products as $product)
		        echo $product['name'] . ( ! empty($product['reference']) ? ' (' . $product['reference'] . ')' : '') . '|' . (int)($product['id_product']) . "\n";
    }
	
	
	/*
	 * Returns a list of customers for an autofill box
	 *
	 * @access public
	 * @param string $query  - A search query to search the database for
	 *
	 * @return void
	 */
	public function ajaxCustomerList($query)
    {
        if ( ! $query || $query == '' || strlen($query) < 1)
	        die();
			
		$sql = '
		SELECT `id_customer`                                  ,
			`firstname`                                       ,
			`lastname`                                        ,
			`email`
		FROM `' . _DB_PREFIX_ . 'customer`
		WHERE (
				`firstname` LIKE "%' . pSQL($query) . '%"
			OR  `lastname`  LIKE "%' . pSQL($query) . '%"
			OR  CONCAT(`firstname`, " ", `lastname`) LIKE "%' . pSQL($query) . '%"
			OR  `email`     LIKE "%' . pSQL($query) . '%"
			)';

        $customers = Db::getInstance()->ExecuteS($sql);
		
        if ($customers && sizeof($customers))
	        foreach ($customers as $customer)
		        printf("%s %s (%s) | %d\n", $customer['firstname'], $customer['lastname'], $customer['email'], (int)$customer['id_customer']);
    }
	
	
	/*
	 * Calculates a shipping price for an order
	 *
	 * @access public
	 * @param array $data    - Data array to calculate shipping (order info)
	 *
	 * @return string        - JSON object to use later in javascript part of
	 *                         the module
	 */
	public function getShippingPrice($data)
	{
		$id_carrier          = (int)($data['carrier']);
		$id_order            = (isset($data['orderId'])  && (int)$data['orderId'] > 0) ? (int)$data['orderId'] : null;
		$id_lang             = (int)Configuration::get('PS_LANG_DEFAULT');
		$currency            = new Currency((isset($data['currency']) && Validate::isUnsignedInt($data['currency'])) ? (int)$data['currency'] : Configuration::get('PS_CURRENCY_DEFAULT'));
		$orderExists         = isset($id_order);
		$id_address_delivery = $id_address_invoice = $id_customer = false;
		$freePrice           = (float)Configuration::get('PS_SHIPPING_FREE_PRICE');
		$freeWeight          = (float)Configuration::get('PS_SHIPPING_FREE_WEIGHT');
		$shippingCost        = 0;
		$freeShipping        = false;
		$errors              = array();
		
		if ( ! $orderExists)
		{
			if ( ! isset($data['delivery']) || ! Validate::isUnsignedInt($data['delivery'])
			|| ( ! isset($data['invoice'])  || ! Validate::isUnsignedInt($data['invoice']))
			|| ( ! isset($data['customer']) || ! Validate::isUnsignedInt($data['customer'])))
			{
				$errors[] = $this->l('You need to assign this order to a customer and select addresses first');
			}
			else
			{
				$id_address_delivery = (int)$data['delivery'];
				$id_address_invoice  = (int)$data['invoice'];
				$id_customer         = (int)$data['customer'];
			}
		}
		else
		{
			if (Validate::isLoadedObject($order = new Order($id_order)))
			{
				$id_address_delivery = $order->id_address_delivery;
				$id_address_invoice  = $order->id_address_invoice;
				$id_customer         = $order->id_customer;
			}
		}
		
		if ( ! $id_address_invoice || ! $id_address_delivery || ! $id_customer)
			$errors[] = $this->l('An error occured: can\'t get delivery address/customer ID');
		
		if ( ! sizeof($errors))
		{
			if (Validate::isUnsignedId($id_carrier) && Validate::isLoadedObject($carrier = new Carrier((int)$id_carrier)))
			{
				$id_zone          = Address::getZoneById((int)($id_address_delivery));
				$shippingMethod   = method_exists($carrier, 'getShippingMethod') ? $carrier->getShippingMethod() : Configuration::Get('PS_SHIPPING_METHOD');
				$dicounts         = (float)($data['discounts']);
				$wrapping         = (float)($data['wrapping']);
				$totalProducts    = 0;
				$totalProducts_wt = 0;
				$totalWeight      = 0;
				$orderTotal       = 0;
				$newerPsVersion   = true;
				
				if (method_exists($carrier, 'getShippingMethod'))
					$shippingMethod = $carrier->getShippingMethod();
				else
				{
					$newerPsVersion = false;
					$shippingMethod = (int)Configuration::Get('PS_SHIPPING_METHOD');
				}
				
				
				if (isset($data['product']) && sizeof($data['product']))
				{
					$productInfo = array();
					
					foreach ($data['product'] as $productIndex => $product)
					{
						foreach ($product as $id_product => $productData)
						{
							$quantity    = Validate::isInt($productData['quantity']) ? (int)$productData['quantity'] : 1;
							$price       = Validate::isPrice($productData['price']) ? OrliqueHelper::roundPrice($productData['price'], 6) : 0;
							$price_wt    = Validate::isPrice($productData['price_wt']) ? OrliqueHelper::roundPrice($productData['price_wt'], 6) : 0;
							$weight      = (float)($productData['weight']);
							
							$totalProducts    += $price * $quantity;
							$totalProducts_wt += $price_wt * $quantity;
							$totalWeight      += $weight * $quantity;
						}
					}
					
					$orderTotal = $totalProducts_wt + $wrapping - $dicounts;

					if ( ! Carrier::checkCarrierZone($id_carrier, $id_zone))
					{
						$errors[] = $this->l('This carrier can not deliver to this address');
					}
					else
					{
						if ((int)($shippingMethod) == 1)
						{
							if ($totalWeight >= $freeWeight && $freeWeight > 0)
								$freeShipping = true;
							else
								$shippingCost = $carrier->getDeliveryPriceByWeight($totalWeight, $id_zone);
						}
						elseif (($newerPsVersion && (int)$shippingMethod == 2) || ( ! $newerPsVersion && (int)$shippingMethod == 0))
						{
							if ($orderTotal >= $freePrice && $freePrice > 0)
								$freeShipping = true;
							else
								$shippingCost = $carrier->getDeliveryPriceByPrice($orderTotal, $id_zone);
						}
						else
							$freeShipping = true;
						
						$handlingPrice = Configuration::get('PS_SHIPPING_HANDLING');
						
						if ( ! $freeShipping && isset($handlingPrice) && $carrier->shipping_handling)
							$shippingCost += (float)($handlingPrice);
							
						$taxAddress = Configuration::get('PS_TAX_ADDRESS_TYPE');
						$taxAddress = $taxAddress ? ${$taxAddress} : $id_address_delivery;
						
						if (property_exists($carrier, 'shipping_external') && $carrier->shipping_external)
						{
							$moduleName = $carrier->external_module_name;
							$module = Module::getInstanceByName($moduleName);
					
							if (Validate::isLoadedObject($module))
							{
								if ( ! array_key_exists('product', $data) || ! sizeof($data['product']))
									$errors[] = $this->l('You must add some products to this order first');
								else
								{
									$cart = new Cart();
									
									$cart->id_address_delivery = (int)$id_address_delivery;
									$cart->id_address_invoice  = (int)$id_address_invoice;
									$cart->id_customer         = (int)$id_customer;
									$cart->id_lang             = (int)$id_lang;
									$cart->id_currency         = (int)$currency->id;
									
									$cartProducts = array();
									
									foreach ($data['product'] as $product)
									{
										foreach ($product as $id_product => $productData)
										{
											if (sizeof($productData) == 1)
												continue;
											
											$quantity = Validate::isInt($productData['quantity']) ? (int)$productData['quantity'] : 1;
											
											$cartProducts[] = array(
												'product_id'	       => (int)$id_product,
												'product_attribute_id' => (int)$productData['product_attribute'],
												'product_quantity'     => (int)$quantity
											);
										}
									}
									
									if ($cart->add())
										OrliqueHelper::updateQtyInCart($cart, $cartProducts);
									else
										$errors[] = $this->l('Unable to create a temporary cart.');
									
									if (array_key_exists('id_carrier', $module))
										$module->id_carrier = $carrier->id;		
									if ($carrier->need_range)
									{
										$shippingCost = $module->getOrderShippingCost($cart, $shippingCost);
										
										$cart->delete();
									}
									else
									{
										$shippingCost = $module->getOrderShippingCostExternal($cart);
										
										$cart->delete();
									}
						
									if ($shippingCost === false)
										$errors[] = $this->l('Unable to load the external carrier');
								}
							}
							else
								$errors[] = $this->l('Could not find selected carrier');
						}
							
						$carrierTax = OrliqueHelper::getShippingTax($carrier, $taxAddress);
					}
				}
			}
			else
			{
				$errors[] = $this->l('Could not find selected carrier');
			}
		}
		
		if (sizeof ($errors))
		{
			$errors = array_unique($errors);
			
			return OrliqueHelper::jsonEncode(array('errors' => $errors));
		}
		else
		{
			$shippingCost = Tools::convertPrice((float)$shippingCost, $currency);
			
			if (isset($carrierTax))
				$shippingCost *= 1 + ($carrierTax / 100);
				
			return OrliqueHelper::jsonEncode(array(
				'message'		 => $this->l('Shipping price calculated successfully') . ($freeShipping ? ' ' . $this->l('This order gets a free shipping, based on it\'s price and weight.') : ''),
				'shipping_price' => $freeShipping ? 0 : $shippingCost,
				'shipping_tax'   => isset($carrierTax) ? $carrierTax : 0
			));
		}
	}
	
	
	/*
	 * Updates (or creates a new) order
	 *
	 * @access public
	 * @param array $data    - Data array to create an order from
	 *
	 * @return string        - JSON object to use later in javascript part of
	 *                         the module
	 */
	public function updateOrder($data)
	{
		global $currentIndex, $cookie;

		$errors            = array();
		$productErrors     = array();
		$payment           = Module::getInstanceById((int)$data['payment']);
		$id_order          = (isset($data['orderId'])  && (int)$data['orderId'] > 0) ? (int)$data['orderId'] : null;
		$currency          = new Currency((isset($data['currency']) && Validate::isUnsignedInt($data['currency'])) ? (int)$data['currency'] : Configuration::get('PS_CURRENCY_DEFAULT'));
		$status            = (isset($data['status']) && Validate::isUnsignedInt($data['status'])) ? (int)$data['status'] : null;
		$orderExists       = isset($id_order);
		$id_lang           = (isset($data['order_lang']) && Validate::isUnsignedInt($data['order_lang'])) ? (int)$data['order_lang'] : (int)Configuration::get('PS_LANG_DEFAULT');
		$allowOutOfStockPs = (bool)(int)(Configuration::get('PS_ORDER_OUT_OF_STOCK'));
		$manageStock       = (bool)(int)(Configuration::get('PS_STOCK_MANAGEMENT'));
		$sendMail          = isset($data['notifyClient']);
		
		if ( ! isset($status) || ! Validate::isLoadedObject($statusObj = new OrderState((int)$status, $id_lang)))
			$errors[] = $this->l('Invalid order status');
			
		if ( ! Validate::isLoadedObject($payment))
			$errors[] = $this->l('Invalid payment module');
			
		$data['date_add'] = Validate::isDate($data['date_add']) ? $data['date_add'] : date('Y-m-d h:i:s');
		
		$orderInfo = array(
			'total_discounts'   => (float)($data['discounts']),
			'date_add'   	    => $data['date_add'],
			'total_products'    => 0,
			'total_products_wt' => 0,
			'total_paid'        => 0,
			'total_paid_real'   => 0,
			'total_shipping'    => (float)($data['total_shipping']),
			'total_wrapping'    => (float)($data['wrapping']),
			'id_carrier'		=> (int)($data['carrier']),
			'id_currency'       => $currency->id,
			'conversion_rate'   => (float)($currency->conversion_rate),
			'gift'              => (float)($data['wrapping']) > 0
		);
		
		if ( ! $orderExists)
		{
			if ( ! isset($data['delivery']) || ! Validate::isUnsignedInt($data['delivery'])
			|| ( ! isset($data['invoice'])  || ! Validate::isUnsignedInt($data['invoice']))
			|| ( ! isset($data['customer']) || ! Validate::isUnsignedInt($data['customer'])))
			{
				$errors[] = $this->l('Please select a customer and assign shipping and invoice addresses');
			}
			else
			{
				$orderInfo['id_address_delivery'] = (int)$data['delivery'];
				$orderInfo['id_address_invoice']  = (int)$data['invoice'];
				$orderInfo['id_customer']         = (int)$data['customer'];
				$orderInfo['id_currency']         = $currency->id;
				$orderInfo['id_lang']             = $id_lang;
			}
			
			$orderInfo['invoice_date']  = '0000-00-00 00:00:00';
			$orderInfo['delivery_date'] = '0000-00-00 00:00:00';
		}

		$orderInfo['module']  = $payment->name;
		
		if (isset($data['product']) && sizeof($data['product']))
		{
			$productInfo = array();
			foreach ($data['product'] as $productIndex => $product)
			{
				foreach ($product as $id_product => $productData)
				{
					if (sizeof($productData) == 1)
						continue;
					
					$skip        = false;
					$price       = Validate::isPrice($productData['price']) ? OrliqueHelper::roundPrice($productData['price'], 6) : 0;
					$price_wt    = Validate::isPrice($productData['price_wt']) ? OrliqueHelper::roundPrice($productData['price_wt'], 6) : 0;
					$tax_rate    = Validate::isFloat($productData['tax_rate']) ? OrliqueHelper::roundPrice($productData['tax_rate'], 6) : 0;
					$quantity    = Validate::isInt($productData['quantity']) ? (int)$productData['quantity'] : 1;
					$attribute   = (int)$productData['product_attribute'];
					$orderDetail = isset($productData['order_detail']) ? (int)$productData['order_detail'] : null;

					$availableQuantity = Product::getQuantity($id_product, $attribute != 0 ? $attribute : null);
					$availableQuantity = $availableQuantity < 0 ? 0 : $availableQuantity;
					$orderQuantity     = 0;
					$allowOutOfStock = $allowOutOfStockPs;
					$currentProductInfo = array();
					
					if (Validate::isLoadedObject($productObj = new Product((int)$id_product, false)))
					{
						$allowOutOfStock = (bool)$productObj->isAvailableWhenOutOfStock($productObj->out_of_stock);
						
						$currentProductInfo['product_supplier_reference'] = OrliqueHelper::getProductSupplierReference($productObj, $attribute);
						$currentProductInfo['product_ean13'] = OrliqueHelper::getProductEAN($productObj, $attribute);
					}
					
					if ($orderDetail)
						$orderQuantity = OrliqueHelper::getAmountAlreadyInOrder($orderDetail, $id_product, $attribute != 0 ? $attribute : null);
					
					$wantedQuantity = $quantity - $orderQuantity;
					
					if ($wantedQuantity > $availableQuantity && ! $allowOutOfStock)
					{
						$productErrors[] = $this->l('Not enough quantity in stock for product') . ' "' . $productData['name'] . '"';
						$quantity = $availableQuantity > 0 ? $availableQuantity : 0;
						$skip = true;
					}

					if ( ! $skip)
					{
						$currentProductInfo+= array(
							'index'                => (int)$productData['index'],
							'product_id'           => (int)$id_product,
							'product_attribute_id' => (int)$productData['product_attribute'],
							'product_price'        => $price,
							'id_order_detail'      => $orderDetail,
							'product_name'         => $productData['name'],
/*							'product_reference'    => $productData['reference'], */
							'wanted_quantity'      => $wantedQuantity,
							'product_weight'       => (float)($productData['weight']),
							'product_quantity'     => $quantity,
							'reduction_amount'     => (float)$productData['reduction_amount'],
							'reduction_percent'    => (float)$productData['reduction_percent'],
							'tax_rate'             => $tax_rate,
							'download_deadline'    => '0000-00-00 00:00:00'
						);
						
						if ($currentProductInfo['reduction_percent'] > 0 || $currentProductInfo['reduction_amount'] > 0)
						{
							$priceReducWt = $price * (1 + ($tax_rate / 100));
							$priceReduc   = $price;

							if ($currentProductInfo['reduction_percent'] > 0)
							{
								$priceReduc = $priceReduc * (100 / (100 -  $currentProductInfo['reduction_percent']));
								$priceReducWt = $priceReducWt * (100 / (100 -  $currentProductInfo['reduction_percent']));
							}
							elseif ($currentProductInfo['reduction_amount'] > 0)
							{
								$priceReduc+= ($currentProductInfo['reduction_amount'] / (1 + ($tax_rate / 100)));
								$priceReducWt+= $currentProductInfo['reduction_amount'];
							}
							
							$priceReduc = OrliqueHelper::roundPrice((float)$priceReduc, 6);
							$priceReducWt = OrliqueHelper::roundPrice((float)$priceReducWt, 6);
							
							$currentProductInfo+= array(
								'product_price' => $priceReduc,
								'product_price_wt' => $priceReducWt
							);
						}
						
						$productInfo[] = $currentProductInfo;
							
						$orderInfo['total_products']    += $price    * $quantity;
						$orderInfo['total_paid']        += $price_wt * $quantity;
						$orderInfo['total_products_wt'] += $price_wt * $quantity;
					}
				}
			}
				
			$orderInfo['total_paid']      = $orderInfo['total_paid'] + $orderInfo['total_shipping'] + $orderInfo['total_wrapping'] - $orderInfo['total_discounts'];
			$orderInfo['total_paid_real'] = $orderInfo['total_paid'];

			if ( ! sizeof($errors))
			{
				$order = new Order($id_order);
				
				if (isset($id_order))
				{
					$orderProducts = OrliqueHelper::getCurrentOrderDetails($order);
					$orliqueProducts = array();
					
					foreach ($productInfo as $orliqueOrdered)
						if (isset($orliqueOrdered['id_order_detail']))
							array_push($orliqueProducts, $orliqueOrdered['id_order_detail']);
							
					if (is_array($orderProducts) && sizeof($orderProducts))
					{
						$obsoleteProducts = (array_diff($orderProducts, $orliqueProducts));
						
						if (sizeof($obsoleteProducts))
							foreach ($obsoleteProducts as $id_order_detail)
								OrliqueHelper::deleteProductFromOrder((int)$id_order_detail);
					}
				}
				else
					$order->recyclable = Configuration::get('PS_RECYCLABLE_PACK');
				
				foreach ($orderInfo as $field => $value)
					if (property_exists($order, $field))
						$order->{$field} = $value;
						
				$shippingAddressTax = Configuration::get('PS_TAX_ADDRESS_TYPE');
				if (property_exists($order, 'carrier_tax_rate') && Validate::isLoadedObject($carrier = new Carrier($orderInfo['id_carrier'])))
					$order->carrier_tax_rate = OrliqueHelper::getShippingTax($carrier, (int)$shippingAddressTax ? $order->${$shippingAddressTax} : $order->id_address_delivery);
						

				// If it's a new order, we need to create a cart for it
				if ( ! $orderExists)
				{
					$cart = new Cart();
					
					foreach ($orderInfo as $field => $value)
						if (property_exists($cart, $field))
							$cart->{$field} = $value;

					if ($cart->add())
						OrliqueHelper::updateQtyInCart($cart, $productInfo);
					else
						$errors[] = $this->l('Unable to create a cart for this new order');
				}
				else
				{
					$cart = new Cart($order->id_cart);

					if (Validate::isLoadedObject($cart))
						OrliqueHelper::updateQtyInCart($cart, $productInfo, true);
				}
				
				if (isset($cart))
					$order->id_cart = $cart->id;
					
				$orderErrors = $order->validateControler();
				
				$order->payment = $payment->displayName;
				
				if (sizeof($orderErrors) || ! $order->save())
				{
					$errors = array_merge($errors, $orderErrors);
					$errors[] = $this->l('Unable to save order');
				}
				else
				{
					$id_order = $order->id;
					$newOrderDetails = array();

					foreach ($productInfo as $orderDetail)
					{
						$id              = isset($orderDetail['id_order_detail']) ? (int)$orderDetail['id_order_detail'] : null;
						$index           = (int)($orderDetail['index']);
						$id_product      = $orderDetail['product_id'];
						$attribute       = $orderDetail['product_attribute_id'] != 0 ? $orderDetail['product_attribute_id'] : null;
						$wantedQuantity  = $orderDetail['wanted_quantity'];
						
						unset(
							$orderDetail['id_order_detail'],
							$orderDetail['index'],
							$orderDetail['wanted_quantity']
						);
						
						$orderDetail['id_order'] = $id_order;
						
						$detailObj = new OrderDetail($id);

						foreach ($orderDetail as $field => $value)
							$detailObj->{$field} = $value;
						
						$orderDetailErrors = $detailObj->validateControler();
						
						if ( ! sizeof($orderDetailErrors) && $detailObj->save())
						{
							if ( ! isset($id))
								$newOrderDetails[$index] = array(
									'order_detail' => $detailObj->id,
									'product'      => $detailObj->product_id,
                                    'attribute'    => $attribute,
                                    'wantedQuantity'=> $wantedQuantity
								);
							
							Product::updateQuantity(array(
									'id_product'           => $id_product,
									'id_product_attribute' => $attribute,
									'quantity'             => (int)($wantedQuantity),
									'cart_quantity'        => (int)($wantedQuantity),
									'out_of_stock'         => OrliqueHelper::getProductOutOfStock($id_product),
								)
							);
						}
						else
							$errors = array_merge($errors, $orderDetailErrors);
					}
					
					if (sizeof($errors) || sizeof($productErrors))
					{
						if ( ! $orderExists)
						{
							$history = $order->getHistory(Configuration::get('PS_LANG_DEFAULT'));
							
							if ($history && sizeof($history))
							{
								foreach ($history as $orderHistory)
								{
									$historyObj = new OrderHistory($orderHistory['id_order_history']);
									
									if (Validate::isLoadedObject($historyObj))
										$historyObj->delete();
								}
							}
							
							if (isset($cart) && Validate::isLoadedObject($cart))
								$cart->delete();
								
							$order->delete();

                            // restore product quantity in stock
                            foreach ($newOrderDetails as $newOrderDetail) {
                                Product::updateQuantity(array(
                                        'id_product'           => $newOrderDetail['product'],
                                        'id_product_attribute' => $newOrderDetail['attribute'],
                                        'quantity'             => (int)(-$newOrderDetail['wantedQuantity']),
                                        'cart_quantity'        => (int)(-$newOrderDetail['wantedQuantity']),
                                        'out_of_stock'         => OrliqueHelper::getProductOutOfStock($newOrderDetail['product']),
                                    )
                                );
                            }
						}
						
						return OrliqueHelper::jsonEncode(array('errors' => array_merge($errors, $productErrors)));
					}
					else
					{
						if ($sendMail)
						{
							$this->notifyCustomer($order, $cart, $payment, $currency, $productInfo, $orderExists);
						}
						
						// A very basic coupons system, please note that
						// to use it you also should uncomment
						// "Discount coupon(s) code(s)" part in AdminOrderEdit.php
						// Use at you own risk, this is NOT recommended yet
						//
						//$this->checkDiscountCouponsOnOrder($order, $data);
						if ( ! $orderExists || $status != $order->getCurrentState())
						{
							$orderHistory = new OrderHistory();
							$orderHistory->id_order = $order->id;
							$orderHistory->id_employee = (int)$data['iem'];
							$orderHistory->changeIdOrderState((int)$status, $order->id);
							
							$orderHistory->add();
						}
						
						$invoiceLink = false;
						$deliveryLink = false;
						
						if (Validate::isLoadedObject($statusObj))
						{
							if ($statusObj->invoice && $order->invoice_number)
								$invoiceLink = 'pdf.php?id_order=' . (int)$order->id . '&pdf';
							
							if ($statusObj->delivery && $order->delivery_number)
								$deliveryLink = 'pdf.php?id_delivery=' . (int)($order->delivery_number);
						}
						
						$editLink = $currentIndex . 'index.php?tab=AdminOrderEdit&id_order=' . $order->id . '&updateorder&token=' . OrliqueHelper::getTabToken('AdminOrderEdit', $cookie->id_employee);
						$viewLink = $currentIndex . 'index.php?tab=AdminOrderEdit&id_order=' . $order->id . '&vieworder&token=' . OrliqueHelper::getTabToken('AdminOrderEdit', $cookie->id_employee);
						
						return OrliqueHelper::jsonEncode(
							array(
								'message'      => $orderExists ? ($this->l('Order has been successfully updated')) : ($this->l('New order has been created successfully') . '. <a href="' . $editLink . '">' . $this->l('Do you want to switch to full editing mode now?') . '</a>'),
								'order'        => $id_order,
								'details'      => $newOrderDetails,
								'viewLink'     => $viewLink,
								'invoiceLink'  => $invoiceLink,
								'deliveryLink' => $deliveryLink
							)
						);
					}
				}
			}
		}
		else
		{
			if ($orderExists)
				OrliqueHelper::purgeOrder($id_order);
				
			$errors[] = $this->l('Please select at least one product');
		}
		
		return OrliqueHelper::jsonEncode(array('errors' => $errors));
	}
	
	
	/*
	 * Adds a new product to an order, at this stage the price is just taken
	 * from the database, we change it during an order update.
	 *
	 * @access public
	 * @scope static
	 * @param integer $id_order             - An order ID that we're adding product to
	 * @param integer $index                - Product index, incremented with each
	 *                                        added product
	 * @param integer $id_product           - Product ID to add
	 * @param integer $id_lang              - Language ID
	 * @param integer $id_currency          - Currency ID
	 * @param integer $id_product_attribute - (optional) Attribute combination ID
	 */
	public static function addProductToOrder($id_order, $index, $id_product, $id_lang, $id_currency, $id_product_attribute = null)
	{
		global $smarty;
		
		require_once(dirname(__FILE__) . '/../../images.inc.php');
		
		if ( ! OrliqueHelper::checkCurrency($id_currency))
			$id_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
		
		$currency   = new Currency((int)$id_currency);
		$weightUnit = Configuration::get('PS_WEIGHT_UNIT'); 
		$product    = new Product((int)$id_product, true, (int)$id_lang);
		
		$price       = Tools::convertPrice((float)(Product::getPriceStatic($product->id, false, $id_product_attribute)), $currency);
		$taxedPrice  = Tools::convertPrice((float)(Product::getPriceStatic($product->id, true, $id_product_attribute)), $currency);
		$productName = $product->name;
		$productReference = $product->reference;
		
		if (isset($id_product_attribute) && Validate::isUnsignedId($id_product_attribute))
		{
			$productName.= ' - ';
			$combinations = $product->getAttributeCombinaisons((int)$id_lang);

			foreach ($combinations as $tmpComb)
				if ($tmpComb['id_product_attribute'] == $id_product_attribute)
				{
					$productReference = $tmpComb['reference'];
					$productName.= ' ' . $tmpComb['group_name'] . ': ' . $tmpComb['attribute_name'] . ',';
				}
			
			$productName = rtrim($productName, ', ');
		}

		$fields = array(
			'id_order'             => (int)($id_order),
			'product_id'           => (int)($product->id),
			'product_attribute_id' => $id_product_attribute, 
			'product_name'         => $productName,
			'product_quantity'     => 1,
			'product_price'        => $price,
			'tax_rate'             => (float)($product->tax_rate)
		);

		$image = OrliqueHelper::getImage($product->id, $id_product_attribute);

		if (isset($image['id_image']))
		{
			$target = _PS_TMP_IMG_DIR_ . 'product_mini_' . (int)($product->id) . (isset($id_product_attribute) ? '_' . (int)($id_product_attribute) : '') . '.jpg';

			if (file_exists($target))
			{
				$size = getimagesize($target);
				
				$imageHtml = OrliqueHelper::getImageHtmlStr($image['id_image'], $product->id, $id_product_attribute);
			}
		}
		
		$productData = array();
		
		array_push($productData, array(
			'rowId'             => false,
			'currency'          => $currency->id,
			'index'             => $index,
			'productId'         => $product->id,
			'combination'       => $id_product_attribute,
			'productNamePublic' => str_replace(array("\r\n", "\n", "\r"), '<br />', $productName),
			'productNameReal'   => str_replace('<br />', "\n", $productName),
			'reference'         => $productReference,
			'quantity'          => 1,
			'weight'            => (float)$product->weight,
			'weightUnit'        => $weightUnit,
			'price'             => $price,
			'taxRate'           => $product->tax_rate,
			'priceTaxed'        => $taxedPrice,
			'priceTotal'        => $taxedPrice,
			'reduction_amount'  => 0,
			'reduction_percent' => 0,
			'imgStr'            => ((isset($image['id_image']) && isset($size)) ? ' height="' . ($size[1] + 7) . '"' : ''),
			'image'             => ((isset($imageHtml) && $imageHtml !== false) ? $imageHtml : '--')
		));
			
		$smarty->assign('productData', $productData);
		
		die(parent::display(__FILE__, 'templates/product_line.tpl'));
	}


	private function checkDiscountCouponsOnOrder(Order $order, $data)
	{
		$listDiscounts	= $order->getDiscounts();
		
		$discounts = Tools::getValue('discount_coupon', false);
		
		if ($discounts && is_array($discounts))
			$discounts = array_values(array_filter($discounts));
		
		if (sizeof($discounts))
		{
			$newDiscountList = array();
			
			foreach($discounts as $discount)
			{
				$id_discount = Discount::getIdByName($discount);
				
				if ($id_discount !== false)
				{
					$newDiscountList[$id_discount]['id']   = $id_discount;
					$newDiscountList[$id_discount]['name'] = $discount;
				}
			}
			// Now newlist contains only valid discount coupons, let's compare with existing coupons
			$remaining_discount = (float)$order->total_discounts;

			if (is_array($listDiscounts) && sizeof($listDiscounts) > 0)
			{
				foreach ($newDiscountList as $curdis_id => $curdis)
				{
					$newDiscountList[$curdis_id]['exist'] = false;
					
					foreach ($listDiscounts as $curcoupon)
					{
						if ($curcoupon['id_discount'] == $curdis_id)
						{
							$newDiscountList[$curdis_id]['value'] = $curcoupon['value'];
							$newDiscountList[$curdis_id]['exist'] = $curcoupon['id_order_discount'];
							$remaining_discount-= $curcoupon['value'];
						}
					}
				}
				// Calculating the correct value of individual discount according to remaining total
				foreach($newDiscountList as $curdis_id => $curdis)
					$newDiscountList[$curdis_id]['value']+= $remaining_discount / sizeof($newDiscountList);

				foreach($newDiscountList as $curdis_id => $curdis)
				{
					if ($curdis['exist'])
						Db::getInstance()->Execute('
							UPDATE `' . _DB_PREFIX_ . 'order_discount`
							SET value="' . $curdis['value'] . '"
							WHERE `id_order_discount` = ' . (int)($curdis['exist'])
						);
					else
						$order->addDiscount($curdis_id, $curdis['name'], $curdis['value']);
				}
				
				OrliqueHelper::purgeObsoleteDiscounts($listDiscounts, $newDiscountList);
			}
			else
			{
				// No discount coupon on current order ? simplest way, just create everything
				foreach ($newDiscountList as $curdis_id => $curdis)
				{
					$curdis['value'] = $order->total_discounts / sizeof($newDiscountList);
					
					$order->addDiscount($curdis_id, $curdis['name'], $curdis['value']);
				}
			}
			
		}
		else
		{
			// Clean all discount coupons
			OrliqueHelper::purgeOrderDiscounts($listDiscounts);
		}
	}
	
	public function saveOrliqueInvoice($id_order, $invoiceData)
	{
		$errors = array();
		
		if ( ! Validate::isUnsignedId($id_order) || ! Validate::isLoadedObject($order = new Order($id_order)))
			return OrliqueHelper::jsonEncode(array('errors' => array($this->l('Unable to load the order'))));
			
		if ( ! sizeof($errors))
		{
			$languages          = Language::getLanguages();
			$defaultLanguage    = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
			$id_orlique_invoice = OrliqueInvoice::getInvoiceByOrderId($order->id);
			$id_orlique_invoice = $id_orlique_invoice !== false ? (int)$id_orlique_invoice : null;
			
			$orliqueInvoice = new OrliqueInvoice($id_orlique_invoice);
			
			$content = array();
			
			foreach ($languages as $language)
				if (array_key_exists('content_' . $language['id_lang'], $invoiceData))
					$content[$language['id_lang']] = urldecode($invoiceData['content_' . $language['id_lang']]);
					
			if ( ! array_key_exists($defaultLanguage->id, $content) || Tools::isEmpty($content[$defaultLanguage->id]))
				$errors[] = $this->l('Invoice content can not be empty for a default language');
			
			$orliqueInvoice->id_order = (int)$order->id;
			$orliqueInvoice->active   = (int)$invoiceData['active'];
			$orliqueInvoice->content  = $content;
			
			$errors = array_merge($errors, $orliqueInvoice->validateControler());
			
			if ( ! sizeof($errors) && $orliqueInvoice->save())
				return OrliqueHelper::jsonEncode(array('success' => $this->l('A message has been successfully added to an invoice')));
			else
				return OrliqueHelper::jsonEncode(array('errors' => $errors));
		}
	}
	
	private function notifyCustomer(Order $order, Cart $cart, $payment, $currency, $products, $orderExists = false)
	{
		if ( ! Validate::isLoadedObject($customer = new Customer($order->id_customer)))
			return false;
		
		$productsList = '';

		$displayTaxed = true;
		
		foreach ($products as $key => $product)
		{
//			$price = Tools::convertPrice($product['product_price'], $currency); // оригинал, глючит если валюта магазина = доллар
//			$price_wt = Tools::convertPrice(($product['product_price'] * (1 + ((float)$product['tax_rate'] / 100))), $currency); // оригинал, глючит если валюта магазина = доллар
			$price = $product['product_price'];			
			$price_wt = ($product['product_price'] * (1 + ((float)$product['tax_rate'] / 100)));
			
			
			// Add some informations for virtual products
			$deadline = '0000-00-00 00:00:00';
			$download_hash = NULL;
			$productDownload = new ProductDownload();
			if ($id_product_download = $productDownload->getIdFromIdProduct((int)($product['product_id'])))
			{
				$productDownload = new ProductDownload((int)($id_product_download));
				$deadline = $productDownload->getDeadLine();
				$download_hash = $productDownload->getHash();
			}

			$priceWithTax = OrliqueHelper::roundPrice($price * (1 + ((float)$product['tax_rate'] /100)), 2);
			
			$productsList .=
				'<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
					<td style="padding: 0.6em 0.4em;">' . $product['product_reference'] . '</td>
					<td style="padding: 0.6em 0.4em;"><strong>'.$product['product_name'].'</strong></td>
					<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice($displayTaxed ? $price_wt : $price, $currency, false, false).'</td>
					<td style="padding: 0.6em 0.4em; text-align: center;">'.((int)($product['product_quantity'])).'</td>
					<td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(((int)($product['product_quantity'])) * ($displayTaxed ? $price_wt : $price), $currency, false, false).'</td>
				</tr>';
		}
		
		$invoice        = new Address((int)($order->id_address_invoice));
		$delivery       = new Address((int)($order->id_address_delivery));
		$carrier        = new Carrier((int)($order->id_carrier));
		$delivery_state = $delivery->id_state ? new State((int)($delivery->id_state)) : false;
		$invoice_state  = $invoice->id_state ? new State((int)($invoice->id_state)) : false;
		
		$data = array(
			'{firstname}'            => $customer->firstname,
			'{lastname}'             => $customer->lastname,
			'{email}'                => $customer->email,
			'{delivery_company}'     => $delivery->company,
			'{delivery_firstname}'   => $delivery->firstname,
			'{delivery_lastname}'    => $delivery->lastname,
			'{delivery_address1}'    => $delivery->address1,
			'{delivery_address2}'    => $delivery->address2,
			'{delivery_city}'        => $delivery->city,
			'{delivery_postal_code}' => $delivery->postcode,
			'{delivery_country}'     => $delivery->country,
			'{delivery_state}'       => $delivery->id_state ? $delivery_state->name : '',
			'{delivery_phone}'       => $delivery->phone,
			'{delivery_other}'       => $delivery->other,
			'{invoice_company}'      => $invoice->company,
			'{invoice_firstname}'    => $invoice->firstname,
			'{invoice_lastname}'     => $invoice->lastname,
			'{invoice_address2}'     => $invoice->address2,
			'{invoice_address1}'     => $invoice->address1,
			'{invoice_city}'         => $invoice->city,
			'{invoice_postal_code}'  => $invoice->postcode,
			'{invoice_country}'      => $invoice->country,
			'{invoice_state}'        => $invoice->id_state ? $invoice_state->name : '',
			'{invoice_phone}'        => $invoice->phone,
			'{invoice_other}'        => $invoice->other,
			'{order_name}'           => sprintf("№ %4d", (int)($order->id)),
			'{date}'                 => Tools::displayDate($order->date_add, (int)($order->id_lang), $full = false),
			'{carrier}'              => (strval($carrier->name) != '0' ? $carrier->name : Configuration::get('PS_SHOP_NAME')),
			'{payment}'              => $order->payment,
			'{products}'             => $productsList,
			'{total_paid}'           => Tools::displayPrice($order->total_paid, $currency, false, false),
			'{total_products}'       => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping+ $order->total_discounts, $currency, false, false),
			'{total_discounts}'      => Tools::displayPrice($order->total_discounts, $currency, false, false),
			'{total_shipping}'       => Tools::displayPrice($order->total_shipping, $currency, false, false),
			'{total_wrapping}'       => Tools::displayPrice($order->total_wrapping, $currency, false, false)
		);

		$tpl   = $orderExists ? 'order_change' : 'order_conf';
		$topic = $orderExists ? $this->l('The contents of your order have been changed') : $this->l('Order confirmation');
		$iso   = Language::getIsoById($order->id_lang);
//		$path  = dirname(__FILE__) . '/mails/' . $iso . '/';
$path  = dirname(__FILE__) . '/../../mails/' . $iso . '/';		
		

		
		if (file_exists($path . $tpl . '.html') && file_exists($path . $tpl . '.txt'))
			return @Mail::Send((int)($order->id_lang), $tpl, $topic, $data, $customer->email, $customer->firstname.' '.$customer->lastname, null, null, null, null, dirname(__FILE__) . '/../../mails/');
	
		return false;
	}
    
    public function ajaxFullProductInfo($id_lang, $id_product)
    {
		global $smarty;
		
        if ( ! OrliqueHelper::checkId(array($id_lang, $id_product)))
            die();
        
        $product  = new Product((int)$id_product, true, (int)$id_lang);
		$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
		
		if ( ! Validate::isLoadedObject($product))
			unset($product);
	  
        $combinations = $product->getAttributeCombinaisons($id_lang);
		
		$groups = array();
		
		if (is_array($combinations))
		{
			foreach ($combinations as $k => $combination)
			{
				$combArray[$combination['id_product_attribute']]['price']              = $combination['price'];
				$combArray[$combination['id_product_attribute']]['weight']             = $combination['weight'];
				$combArray[$combination['id_product_attribute']]['reference']          = $combination['reference'];
				$combArray[$combination['id_product_attribute']]['quantity']           = $combination['quantity'];
				$combArray[$combination['id_product_attribute']]['default_on']         = $combination['default_on'];
				$combArray[$combination['id_product_attribute']]['attributes'][]       = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
				
				if ($combination['is_color_group'])
					$groups[$combination['id_attribute_group']] = $combination['group_name'];
			}
		}

		if (isset($combArray))
		{
			foreach ($combArray as $id_product_attribute => &$product_attribute)
			{
				$list = '';
				
				foreach ($product_attribute['attributes'] as $attribute)
					$list .= addslashes(htmlspecialchars($attribute[0])).' - '.addslashes(htmlspecialchars($attribute[1])).', ';
				
				$list = rtrim($list, ', ');
				
				$product_attribute['list'] = $list;
				$product_attribute['price'] = Tools::displayPrice(Tools::convertPrice($product_attribute['price'], $currency), $currency);
			}
			
			$smarty->assign('combinations', $combArray);
	    }
		
		if (isset($product))
			$smarty->assign('product', $product);
			
		$smarty->assign(array(
			'weightUnit' => Configuration::get('PS_WEIGHT_UNIT'),
			'currencyId' => $currency->id
		));
		
		die(parent::display(__FILE__, 'templates/product_add.tpl'));
    }
	
	public function displayCustomerCreationForm($id_lang)
	{
		global $smarty;
		
		$groups = Group::getGroups((int)$id_lang);
		$countries = Country::getCountries((int)$id_lang, true);
		
		$smarty->assign(array(
			'groups'          => Group::getGroups((int)$id_lang),
			'countries'       => Country::getCountries((int)$id_lang, true),
			'displayDni'      => property_exists('Customer', 'dni'),
			'selectedCountry' => (int)Configuration::get('PS_COUNTRY_DEFAULT')
		));

		die(parent::display(__FILE__, 'templates/customer.tpl'));
	}
	
	public function addOrderMessage($params)
	{
		$errors       = array();
		$id_order     = $params['id_order'];
		$id_customer  = OrliqueHelper::getCustomerIdByOrderId($id_order);
		$messageText  = $params['message'];
		
		if ( ! (Validate::isUnsignedId($id_order)) || ! (Validate::isUnsignedId($id_customer)))
			$errors[] = Tools::displayError('An error occurred before sending message');
		elseif ( ! isset($messageText) || Tools::isEmpty($messageText))
			$errors[] = Tools::displayError('Message cannot be blank');
		else
		{
			$rules = call_user_func(array('Message', 'getValidationRules'), 'Message');
			
			foreach ($rules['required'] as $field)
				if ( ! isset($params[$field]) || Tools::isEmpty($params[$field]))
					$errors[] = Tools::displayError('field') . ' <b>' . $field . '</b> ' . Tools::displayError('is required.');
			
			foreach ($rules['size'] as $field => $maxLength)
				if (isset($params[$field]) && Tools::strlen($params[$field]) > $maxLength)
					$errors[] = Tools::displayError('field') . ' <b>' . $field . '</b> ' . Tools::displayError('is too long.') . ' (' . $maxLength . ' ' . Tools::displayError('chars max') . ')';
			
			foreach ($rules['validate'] as $field => $function)
				if (Tools::getValue($field))
					if ( ! Validate::$function(htmlentities($params[$field], ENT_COMPAT, 'UTF-8')))
						$errors[] = Tools::displayError('field') . ' <b>' . $field . '</b> ' . Tools::displayError('is invalid.');
			
			if ( ! sizeof($errors))
			{
				$message = new Message();
				
				foreach ($params as $field => $value)
					if (property_exists($message, $field) && $field != 'id_customer' && $field != 'id_lang')
					{
						if ($field == 'message')
							$value = htmlentities($value, ENT_COMPAT, 'UTF-8');
							
						$message->{$field} = $value;
					}

				if ( ! $message->add())
					$errors[] = Tools::displayError('An error occurred while sending message.');
					
				elseif (Validate::isLoadedObject($customer = new Customer($id_customer)) && $message->private == 0)
				{
					$order = new Order((int)($message->id_order));
					if (Validate::isLoadedObject($order))
					{
						$varsTpl = array('{lastname}' => $customer->lastname, '{firstname}' => $customer->firstname, '{id_order}' => $message->id_order, '{message}' => (Configuration::get('PS_MAIL_TYPE') == 2 ? $message->message : nl2br2($message->message)));
						if ( ! @Mail::Send((int)($order->id_lang), 'order_merchant_comment', Mail::l('New message regarding your order'), $varsTpl, $customer->email, $customer->firstname.' '.$customer->lastname))
							$errors[] = Tools::displayError('An error occurred while sending e-mail to customer.');					}
				}
			}
		}
		
		if (sizeof($errors))
			return OrliqueHelper::jsonEncode(array('errors' => $errors));
		elseif (isset($message))
			return OrliqueHelper::jsonEncode(array('success' => $this->l('Message has been successfully added')));
	}
	
	public function markMessageRead($id_employee, $id_message)
	{
		$errors = array();
		
		if ( ! Validate::isUnsignedId($id_employee) || ! Validate::isUnsignedId($id_message))
			$errors[] = $this->l('Incorrect employee ID or message ID');
		else
		{
			if ( ! Message::markAsReaded((int)$id_message, (int)$id_employee))
				$errors[] = $this->l('Unable to mark the message as read');
			else
				return OrliqueHelper::jsonEncode(array('success' => $this->l('Message has been successfully marked as read')));
		}
		
		return OrliqueHelper::jsonEncode(array('errors' => $errors));
	}
	
	public function saveCustomer($data)
	{
		$errors = array();
		$customer = new Customer();
		$addreses = array();
		
		$customerInfo  = $data['customer'];
		$addressesInfo = $data['addresses'];
		$groups        = $data['groups'];
		
		$customer->passwd = Tools::passwdGen(8);
		$customer->is_guest = false;
		
		foreach ($customerInfo as $property => $value)
		{
			if (property_exists($customer, $property))
			{
				$customer->{$property} = trim($value);
			}
		}
		
		foreach ($addressesInfo as $address)
		{
			$addressObj = new Address();
			
			foreach ($address as $property => $value)
				if (property_exists($addressObj, $property))
					$addressObj->{$property} = $value;
			
			$addressObj->firstname = $customer->firstname;
			$addressObj->lastname = $customer->lastname;
			$addressObj->id_customer = 1;
			$addressObj->alias = $this->l('My address');

			$addreses[] = $addressObj;
		}
				
		if (Validate::isEmail($customer->email) && Customer::customerExists($customer->email))
			$errors[] = $this->l('Someone has already registered with this e-mail address');
			
		if ( ! is_array($groups) || sizeof($groups) == 0)
			$errors[] = $this->l('Customer must be in at least one group');
			
		if (property_exists($customer, 'id_default_group') && ! in_array($customer->id_default_group, $groups))
			$errors[] = $this->l('Default customer group must be selected in group box');
			
		foreach ($addreses as $address)
			$errors = array_unique(array_merge($errors, $address->validateControler()));

		$errors = array_unique(array_merge($errors, $customer->validateControler()));
		
		if (sizeof($errors) || ! $customer->add())
		{
			$errors[] = $this->l('Unable to add a new customer');
			return OrliqueHelper::jsonEncode(array('errors' => $errors));
		}
		
		if (is_array($groups) && sizeof($groups) > 0)
			$customer->addGroups($groups);

		foreach ($addreses as $address)
		{
			$address->id_customer = $customer->id;
			
			if ( ! $address->add())
				$errors[] = $this->l('Unable to add an address');
		}
		
		if (sizeof($errors))
			return OrliqueHelper::jsonEncode(array('errors' => $errors));
		
		return OrliqueHelper::jsonEncode(
			array(
				'success'    => true,
				'customerID' => $customer->id,
				'message'    => $this->l('New customer has been successfully created')
			)
		);
	}
	
    public function ajaxFullCustomerInfo($id_lang, $id_customer, $id_employee)
    {
        if ( ! OrliqueHelper::checkId(array($id_lang, $id_customer)))
            die();
			
		$customer = new Customer($id_customer);
		
		if ( ! Validate::isLoadedObject($customer))
			return '<h4>' . $this->l('Unknown customer selected') . '</h4>';
			
		$addresses = $customer->getAddresses($id_lang);
		
		if ( ! sizeof($addresses))
			return '<h4>' . $this->l('This customer doesn\'t have any address assigned, please do that first') . '</h4>';
		
		$output = '';
		$iso = Language::getIsoById($id_lang);

		foreach ($addresses as $address)
			$output.= $this->displayAddress($address, $iso, $id_employee);
		
		die($output);
    }
	
	private function displayAddress($address, $iso, $id_employee)
	{
		global $currentIndex, $smarty;
		
		$link = new Link();
		
		$smarty->assign(array(
			'addressEditUrl'   => $currentIndex . 'index.php?tab=AdminAddresses&id_address=' . $address['id_address'] . '&updateaddress&token=' . Tools::getAdminToken('AdminAddresses' . (int)(Tab::getIdFromClassName('AdminAddresses')) . (int)($id_employee)),
			'addressGoogleUrl' => 'http://maps.google.com/maps?f=q&hl=' . $iso . '&geocode=&q=' . $address['address1'] . ' ' . $address['postcode'] . ' ' . $address['city'] . ( ! Tools::isEmpty($address['state']) ? ' ' . $address['state'] : ''),
			'address'          => $address
		));
		
		return parent::display(__FILE__, 'templates/address.tpl');
	}
	
	public function hookPDFInvoice($params)
	{
		global $cookie;
		
		$pdf     = $params['pdf'];
		$id_order = $params['id_order'];
		
		$order = new Order((int)$id_order);
		
		$invoiceId = OrliqueInvoice::getInvoiceByOrderId($id_order);

		if ($invoiceId && Validate::isLoadedObject($order) && Validate::isLoadedObject($invoice = new OrliqueInvoice($invoiceId, (int)$order->id_lang)))
		{
			if ( ! Tools::isEmpty($invoice->content))
			{
				$pdf->ln(5);
				$pdf->MultiCell(0, 5, $invoice->content);
			}
		}
	}
}
?>