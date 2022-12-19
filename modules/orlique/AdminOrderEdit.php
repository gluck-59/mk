<?php
include_once(PS_ADMIN_DIR . '/tabs/AdminOrders.php');
include_once(dirname(__FILE__) . '/classes/OrliqueHelper.php');

class AdminOrderEdit extends AdminOrders
{
	public function __construct()
	{
		global $cookie, $smarty, $_LANGADM, $_MODULES, $currentIndex;
		
	    $this->module = 'orlique';
	 	$this->edit   = true;
	 	$this->delete = true;

		$langFile = _PS_MODULE_DIR_ . $this->module . '/' . Language::getIsoById((int)($cookie->id_lang)) . '.php';
		
		if (file_exists($langFile))
		{
			require_once $langFile;
			
			if (method_exists('Module', 'findTranslation'))
				$_MODULES = array_merge((is_array($_MODULES) ? $_MODULES : array()), $_MODULE);
			
			foreach ($_MODULE as $key => $value)
				if (strtolower(substr(strip_tags($key), 0, 5)) == 'admin')
					$_LANGADM[str_replace('_', '', strip_tags($key))] = $value;
		}
		
		$filters = Tools::getValue('exFilter', false);
		
		parent::__construct();
		
		if ($filters && sizeof($filters))
		{
			$emptyElements = array_keys($filters, '');
				foreach ($emptyElements as $key)
					unset($filters[$key]);
			
			if (sizeof($filters))
			{
				$this->_where.= '
				AND EXISTS
					(
						SELECT
							`id_order`
						FROM `' . _DB_PREFIX_ . 'order_detail`
						WHERE
							`id_order` = a.`id_order`';
				foreach ($filters as $filterName => $filterValue)
				{
					$this->_where.= ' AND (`' . trim(pSQL($filterName)) . '` LIKE "' . trim(pSQL($filterValue))  . '%" OR `' . trim(pSQL($filterName)) . '` = "' . trim(pSQL($filterValue))  . '")';
				}
				
				$this->_where.= ')';
			}
		}
		
		$this->l('Add new');
		$this->l('Display');
		$this->l('Page');
		$this->l('result(s)');
		$this->l('Reset');
		$this->l('Filter');
		$this->l('ID');
		$this->l('New');
		$this->l('Customer');
		$this->l('Total');
		$this->l('Payment');
		$this->l('Status');
		$this->l('Date');
		$this->l('PDF');
		$this->l('Actions');
		$this->l('View');
		$this->l('Edit');
		$this->l('Delete');
		$this->l('Delete selection');
	}
	
	protected function l($string, $class = 'AdminTab', $addslashes = false, $htmlentities = true)
	{
		return parent::l($string, strtolower(get_class($this)), $addslashes, $htmlentities);
	}
	
	private static function getOrderRelatedTables()
	{
		return Db::getInstance()->ExecuteS('
			SELECT DISTINCT
				TABLE_NAME as `table`,
				COLUMN_NAME as `column`
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE
				COLUMN_NAME IN ("id_order", "order_id")
				AND TABLE_SCHEMA="' . _DB_NAME_ . '"
		');
	}
	
	private static function getTableField($table, $field, $whereKey, $whereVal)
	{
		return Db::getInstance()->getValue('
			SELECT `' . pSQL($field) . '`
			FROM `' . pSQL($table) . '`
			WHERE `' . pSQL($whereKey) . '` = ' . pSQL($whereVal)
		);
	}
	
	private function updateOrderHistory($ordersList, $newOrderStatus)
	{
	    if ( ! is_array($ordersList) || ! Validate::isUnsignedId($newOrderStatus))
	        $this->_errors[] = Tools::displayError('Wrong parameters passed.');
	    else
	    {
	        global $cookie;
	        
	        // The faster way would be just to update one table, but that way 
	        // no letters will be sent to customers, so we're going to have to 
	        // loop through objects.
	        foreach ($ordersList as $orderId)
	        {
	            if (Validate::isLoadedObject($order = new Order((int)$orderId)))
	            {
					$history = new OrderHistory();
					$history->id_order = $orderId;
					$history->changeIdOrderState((int)$newOrderStatus, (int)$orderId);
					$history->id_employee = (int)$cookie->id_employee;
					$carrier = new Carrier((int)$order->id_carrier, (int)$order->id_lang);
					$templateVars = array('{followup}' => ($history->id_order_state == _PS_OS_SHIPPING_ AND $order->shipping_number) ? str_replace('@', $order->shipping_number, $carrier->url) : '');
					if ( ! $history->addWithemail(true, $templateVars))
                        $this->_errors[] = Tools::displayError('an error occurred while changing status or was unable to send e-mail to the customer');
	            }
	        }
	    }
	}
	
	public function viewAccess($disable = false)
	{
		global $cookie;

		if ($disable)
			return true;
		
		// Apparently, it's not safe to use $cookie->profile, as it is getting
		// cached, which is why it doesn't always returns correct value
		$profile = Db::getInstance()->getValue('
			SELECT `id_profile`
			FROM `' . _DB_PREFIX_ . 'employee`
			WHERE `id_employee` = ' . (int)$cookie->id_employee
		);

		$this->tabAccess = Profile::getProfileAccess($profile, $this->id);

		if ($this->tabAccess['view'] === '1')
			return true;
		
		return false;
	}
	
	public function postProcess()
	{
		global $currentIndex;
		
		$token = Tools::getValue('token') ? Tools::getValue('token') : $this->token;
		
	    if (Tools::getIsset('submitStateChange' . $this->table))
	    {
	        if ($this->tabAccess['edit'] === '1')
	        {
	            if (isset($_POST[$this->table . 'Box']))
	                $this->updateOrderHistory($_POST[$this->table . 'Box'], Tools::getValue('id_order_state', false));
	        }
            else
                $this->_errors[] = Tools::displayError('You do not have permission to edit here.');
	    }
		elseif (Tools::getIsset('deleteorder') || Tools::isSubmit('submitDel' . $this->table))
		{
	        if ($this->tabAccess['delete'] === '1')
	        {
				$ordersToDelete = Tools::isSubmit('submitDel' . $this->table) ? (array_key_exists($this->table . 'Box', $_POST) ? $_POST[$this->table . 'Box'] : false) : array((int)Tools::getValue('id_order'));
				$relatedTables  = self::getOrderRelatedTables();
				
				if ( ! $relatedTables || ! sizeof($relatedTables))
					$this->_errors[] = Tools::displayError('Unable to fetch information related to orders in database, this is a critical error, the orders will not be deleted');

				if ( ! sizeof($this->_errors))
				{
					$auxTables = array(
						_DB_PREFIX_ . 'message' => array(
							array(
								'table'       => _DB_PREFIX_ . 'message_readed',
								'keyToJoinBy' => 'id_message'
							)
						),
						_DB_PREFIX_ . 'orders' => array(
							array(
								'table'       => _DB_PREFIX_ . 'customization',
								'keyToJoinBy' => 'id_cart',
								'cascadeDelete' => array(
									array(
										'table'       => _DB_PREFIX_ . 'customized_data',
										'keyToJoinBy' => 'id_customization'
									)
								)
							),
							array(
								'table'       => _DB_PREFIX_ . 'cart',
								'keyToJoinBy' => 'id_cart',
								'cascadeDelete' => array(
									array(
										'table'       => _DB_PREFIX_ . 'cart_discount',
										'keyToJoinBy' => 'id_cart'
									),
									array(
										'table'       => _DB_PREFIX_ . 'cart_product',
										'keyToJoinBy' => 'id_cart'
									),
								)
							),
						)
					);
					
					$tmpCache = array();
					$deleteRequests = array();
					
					foreach ($ordersToDelete as $id_order)
					{
						$deleteRequests[$id_order] = array();
						
						if (Validate::isUnsignedId($id_order))
						{
							$id_order = (int)$id_order;
							
							foreach ($relatedTables as $tableData)
							{
								$orderKey = $tableData['column'];
								
								if (array_key_exists($tableData['table'], $auxTables))
								{
									foreach ($auxTables[$tableData['table']] as $auxTable)
									{
										$cacheKey = md5($tableData['table'] . '_' . $auxTable['keyToJoinBy'] . '_' . $orderKey . '_' . $id_order);
										
										if ( ! array_key_exists($cacheKey, $tmpCache))
											$tmpCache[$cacheKey] = self::getTableField($tableData['table'], $auxTable['keyToJoinBy'], $orderKey, $id_order);
											
										$targetId = $tmpCache[$cacheKey];
										
										if ($targetId)
											$deleteRequests[$id_order][] = 'DELETE FROM `' . pSQL($auxTable['table']) . '` WHERE `' . pSQL($auxTable['keyToJoinBy']) . '` = ' . pSQL($targetId);

										if (array_key_exists('cascadeDelete', $auxTable) && is_array($auxTable['cascadeDelete']))
										{
											foreach ($auxTable['cascadeDelete'] as $cascadeDeleteTable)
											{
												$cacheKey = md5($auxTable['table'] . '_' . $cascadeDeleteTable['keyToJoinBy'] . '_' . $auxTable['keyToJoinBy'] . '_' . $targetId);
										
												if ( ! array_key_exists($cacheKey, $tmpCache))
													$tmpCache[$cacheKey] = self::getTableField($auxTable['table'], $cascadeDeleteTable['keyToJoinBy'], $auxTable['keyToJoinBy'], $targetId);
													
												$auxId = $tmpCache[$cacheKey];
												
												if ($auxId)
													$deleteRequests[$id_order][] = 'DELETE FROM `' . pSQL($cascadeDeleteTable['table']) . '` WHERE `' . pSQL($cascadeDeleteTable['keyToJoinBy']) . '` = ' . pSQL($auxId);
											}
										}
									}
								}
								
								$deleteRequests[$id_order][] = 'DELETE FROM `' . pSQL($tableData['table']) . '` WHERE `' . pSQL($tableData['column']) . '` = ' . ($id_order);
							}
						}
					}
					
					if (sizeof($deleteRequests))
						foreach ($deleteRequests as $id_order => $queriesToRun)
							if (sizeof($queriesToRun))
							{
								foreach ($queriesToRun as $query)
									if ( ! Db::getInstance()->Execute($query))
										$this->_errors[] = $this->l('Unable to execute query') . ' <b>' . $query . '</b> ' . $this->l('for order #') . $id_order; 
							}
							else
								$this->_errors[] = $this->l('No queries to run for order #') . $id_order;
								
					if ( ! sizeof($this->_errors))
						Tools::redirectAdmin($currentIndex . '&conf=' . (sizeof($ordersToDelete) == 1 ? '1' : '2') . '&token=' . $token);
				}
	        }
		}
	    
	    parent::postProcess();
	}
	
	public function display()
	{
		global $cookie;
		
		if (isset($_GET['view' . $this->table]))
			$this->viewDetails();
		elseif (isset($_GET['update' . $this->table]) || isset($_GET['add' . $this->table]))
		{
			if (isset($_GET['update' . $this->table]))
			{
				if ($this->tabAccess['edit'] === '1')
					$this->displayForm();
				else
					echo '<div class="error"><img src="../img/admin/warning.gif" alt="" title=""> ' . $this->l('You do not have permission to edit here') . '</div>';
			}
			elseif (isset($_GET['add' . $this->table]))
			{
				if ($this->tabAccess['add'] === '1')
					$this->displayForm();
				else
					echo '<div class="error"><img src="../img/admin/warning.gif" alt="" title=""> ' . $this->l('You do not have permission to add here') . '</div>';
			}
		}
		else
		{
			$this->getList((int)$cookie->id_lang, !Tools::getValue($this->table.'Orderby') ? 'date_add' : NULL, !Tools::getValue($this->table.'Orderway') ? 'DESC' : NULL);
			$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
			$this->displayList();
			echo '<h2 class="space" style="text-align:right; margin-right:44px;">'.$this->l('Total:').' '.Tools::displayPrice($this->getTotal(), $currency).'</h2>';
		}
	}
	
	public function displayForm($isMainTab = true)
	{
	    global $cookie;
		
		if (Tools::getIsset($this->identifier))
			$order = $this->loadObject();
			
		$defaultCurrency = Configuration::get('PS_CURRENCY_DEFAULT');
			
		$order           = (isset($order) && Validate::isLoadedObject($order)) ? $order : null;
		$discounts       = isset($order) ? (float)$order->total_discounts : 0;
		$wrapping        = isset($order) ? (float)$order->total_wrapping : 0;
		$shipping        = isset($order) ? (float)$order->total_shipping : 0;
		$status          = isset($order) ? $order->getCurrentState() : null;
		$orderCurrency   = isset($order) ? $order->id_currency : $defaultCurrency;
	    $language        = new Language((int)$cookie->id_lang);
		$langaugeDefault = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
	    $link            = new Link();
		$currency        = new Currency($orderCurrency);
	    
	    echo '
	    <link rel="stylesheet" href="' . _MODULE_DIR_ . $this->module . '/css/style.css" />
		<script type="text/javascript">
            var ajaxPath        = "' . _MODULE_DIR_ . $this->module . '/xhr/ajax.php";
            var id_lang         = ' . (int)$language->id . ';
			var iso             = "' . (strtolower($language->iso_code)) . '";
			var id_lang_default = ' . $langaugeDefault->id . ';
			var iso_default     = "' . (strtolower($langaugeDefault->iso_code)) . '";
            var iem             = ' . (int)$cookie->id_employee . ';
            var iemp            = "' . $cookie->passwd . '";
			var currencySign    = "' . $currency->sign . '";
			var currencyRate    = '  . $currency->conversion_rate . ';
			var currencyFormat  = "' . $currency->format . '";
			var currencyBlank   = '  . $currency->blank . ';
			
			var translations = {
				"Order #": "' . $this->l('Order #') . '",
				"State": "' . $this->l('State') . '",
				"Delete this row": "' . $this->l('Delete this row') . '",
				"View/hide messages": "' . $this->l('View/hide messages') . '",
				"Add message": "' . $this->l('Add message') . '",
				"Yes": "' . $this->l('Yes') . '",
				"No": "' . $this->l('No') . '",
				"Private message": "' . $this->l('Private message') . '",
				"Private": "' . $this->l('Private') . '",
				"Message": "' . $this->l('Message') . '",
				"Mark as read": "' . $this->l('Mark as read') . '",
				"Compose a new message": "' . $this->l('Compose a new message') . '",
				"Please create an order first": "' . $this->l('Please create an order first') . '",
				"Order Messages": "' . $this->l('Order Messages') . '",
				"This product does not have combinations": "' . $this->l('This product does not have combinations') . '",
				"Download invoice": "' . $this->l('Download invoice') . '",
				"Download delivery slip": "' . $this->l('Download delivery slip') . '"
			};
        </script>
		<script type="text/javascript" src="../modules/' . $this->module . '/js/jquery1.5.js"></script>
		<link type="text/css" href="../modules/' . $this->module . '/js/ui/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="../modules/' . $this->module . '/js/ui/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="../modules/' . $this->module . '/js/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="../modules/' . $this->module . '/js/editor.js"></script>
		
	<!-- Ссылка на локализацию datepicker (он расположен в ../modules/orlique/js/ui/jquery-ui-1.8.16.custom.min.js) -->
		<script type="text/javascript" src="../modules/' . $this->module . '/js/ui/jquery.ui.datepicker-ru.js"></script>

		<link rel="stylesheet" type="text/css" href="' . __PS_BASE_URI__ . 'css/jquery.autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="' . _MODULE_DIR_ . $this->module . '/css/style.css" />
		<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/jquery/jquery.autocomplete.js"></script>
		<script type="text/javascript">
			var pos_select = ' . (($tab = Tools::getValue('tabs')) ? $tab : '0') . ';
			function loadTab(id) {}
		</script>
		<script src="../js/tabpane.js" type="text/javascript"></script>
		<link type="text/css" rel="stylesheet" href="../css/tabpane.css" />
		
		<input type="hidden" name="tabs" id="tabs" value="0" />
		<img src="../modules/' . $this->module . '/images/logo.jpg" id="orliqueLogo" />';
		
		if (isset($order) && $order->id)
			echo '<h2>' . $this->l('Order #') . (sprintf('%06d', $order->id)) . '</h2>';
		
		echo '
	    <div id="orderEditorContainer">
			<div id="orderAlwaysVisibleWrapper">
				<div style="float: left; width: 50%;">';
		
		$this->displayPaymentModules($order, $language->id);
		$this->displayStatusInfo($status, $language->id);
		$this->displayOrderDate($order);
		$this->displayOrderLanguages($order);
		$this->displayCurrencyInfo($orderCurrency);
		$this->displayWrappingInfo($wrapping, $currency);
		
		echo '
				</div>
				<div style="float: left; width: 50%;">';
		
		$this->displayOrderTotals($order, $currency);
		
		echo '
				</div>
			</div>
			<div class="tab-pane" id="tabPane1" style="margin-top: 1em;">';

		echo '
				<div class="tab-page" id="step1">
					<h4 class="tab"><img src="../img/admin/tab-orders.gif" />  ' . $this->l('Order') . '</h4>';

	    $this->displayProductSelection();
		$this->displayOrderedProducts($order, $currency);
		
		echo '
				</div>';
		
		echo '
				<div class="tab-page" id="step2">
					<h4 class="tab"><img src="../img/admin/tab-shipping.gif" />  ' . $this->l('Shipping / Address') . '</h4>';
			
		if ( ! isset($order))
			$this->displayCustomerSelection();
			
		$this->displayAddress($order, $link, $language, true);
		$this->displayAddress($order, $link, $language);
		
		$this->displayShippingInfo($order, $language->id, $currency, $shipping);
		
		echo '
				</div>';
		
		echo '
				<div class="tab-page" id="step3">
					<h4 class="tab"><img src="../img/admin/tab-payment.gif" />  ' . $this->l('Discounts') . '</h4>';
		
		$this->displayDiscountInfo($order, $currency);
		
		echo '
				</div>';
		
		if (isset($order))
		{
			echo '
					<div class="tab-page invoiceSettings" id="step4">
						<h4 class="tab"><img src="../img/admin/tab-invoice.gif" />  ' . $this->l('Invoice Settings') . '</h4>';
				
			$this->displayInvoiceSettings($order);
			
			echo '
					</div>';
			
			echo '
					<div class="tab-page orderMessagesTab" id="step5">
						<h4 class="tab"><img src="../img/admin/tab-contact.gif" />  ' . $this->l('Messages') . '</h4>';
			
			echo '
					</div>';
		}

	    echo '
			</div>
			<input style="margin-top: 1em;" type="button" class="button orderSaveBtn" value="' . $this->l('Save order') . '" />
			<input style="margin-top: 1em;" type="button" class="button orderSaveBtn saveAndPreview" value="' . $this->l('Save order and preview') . '" />
	    </div>';
	}
	
	private static function createFieldset($contents, $id = false, $class = false, $legend = false, $icon = false)
	{
		$output = '
		<fieldset' . ($id ? ' id="' . $id . '"' : '') . ($class ? ' class="' . $class . '"' : '') . '>';
		
		if ($icon || $legend)
		{
			$output.= '
			<legend>
				' . ($icon ? '<img src="' . $icon . '" /> ' : '') . ($legend ? $legend : '') .  '
			</legend>';
		}
			
		$output.= ($contents ? $contents : '') . '</fieldset>';
		
		return $output;
	}
	
	private static function createDropDown($values, $valueId, $valueName, $selectedValue = null, $id = false, $name = false, $class = false)
	{
		$output = '
		<select' . ($id ? ' id="' . $id . '"' : '') . ($class ? ' class="' . $class . '"' : '') . ($name ? ' name="' . $name . '"' : '') . '>';

		// При формировании нового заказа выбираем способ оплаты по умолчанию "Оплата наличными при получении"
		$default_paymentModule = 'cashondelivery';
		$id_default_paymentModule = self::getModuleIdByName($default_paymentModule);
		if ($selectedValue == '' AND $id == 'paymentModule') $selectedValue = $id_default_paymentModule;
		
		// При формировании нового заказа выбираем статус по умолчанию "Заказ принят"
		$default_orderStatus = '3';
		if ($selectedValue == '' AND $id == 'orderStatus') $selectedValue = $default_orderStatus;

		foreach ($values as $value)
			$output.= '
				<option value="' . $value[$valueId] . '"' . ($value[$valueId] == $selectedValue ? ' selected="selected"' : '') . '>' . $value[$valueName] . '</option>';
		
		$output.= '
		</select>';
		
		return $output;
	}
	
	private static function getModuleIdByName($name)
	{
		$sql = 'SELECT `id_module` FROM `' . _DB_PREFIX_ . 'module` WHERE `name` = "' . pSQL($name) . '"';
		
		$result = Db::getInstance()->getRow($sql);
		
		return isset($result['id_module']) ? (int)$result['id_module'] : null;
	}
	
    private function displayCustomerSelection()
    {
        global $cookie;
		
		$content = '
		<div id="ajax_create_customer">
			<a href="" id="customerCreate"><img src="../modules/' . $this->module . '/images/add.png" /> ' . $this->l('Create new customer') . '</a>
		</div>
		<div id="ajax_choose_customer" style="padding:6px; padding-top:2px; width:600px;">
			<p class="clear">' . $this->l('Please enter customer\'s name or email') . '</p>
			<input type="text" size="60" value="" id="customer_autocomplete_input" />
		</div>
		<script type="text/javascript">
			urlToCall = null;
			/* function autocomplete */
			$(function() {
				$(\'#customer_autocomplete_input\')
					.autocomplete(\'../modules/' . $this->module . '/xhr/ajax.php\', {
						minChars: 1,
						autoFill: true,
						max:20,
						matchContains: true,
						mustMatch:true,
						scroll:false,
						cacheLength:0,
						formatItem: function(item) {
							return item[1]+\' - \'+item[0];
						}
					}).result(requestCustomerInfo);
				$(\'#customer_autocomplete_input\').setOptions({
					extraParams: {type: 0, id_lang: id_lang, iem: iem, iemp: iemp}
				});
			});
		</script>
		<div id="customer">
		</div>';

		echo self::createFieldset($content, 'customerSelector', 'full', $this->l('Select customer'), '../modules/' . $this->module . '/images/customers.png');
    }
	
    private function displayProductSelection()
    {
        global $cookie;

        $content = '
		<div id="ajax_choose_product" style="padding:6px; padding-top:2px; width:600px;">
			<p class="clear">' . $this->l('Type in a name or a reference of the product you would like to add to this order') . '</p>
			<input type="text" size="60" value="" id="product_autocomplete_input" />
		</div>
		<script type="text/javascript">
			urlToCall = null;
			/* function autocomplete */
			$(function() {
				$(\'#product_autocomplete_input\')
					.autocomplete(\'../modules/' . $this->module . '/xhr/ajax.php\', {
						minChars: 1,
						autoFill: true,
						max:20,
						matchContains: true,
						mustMatch:true,
						scroll:false,
						cacheLength:0,
						formatItem: function(item) {
							return item[1]+\' - \'+item[0];
						}
					}).result(requestProductInfo);
				$(\'#product_autocomplete_input\').setOptions({
					extraParams: {type: 1, id_lang : id_lang, iem: iem, iemp: iemp}
				});
			});
		</script>
		<div id="productToAdd">
		</div>';
		
		echo self::createFieldset($content, 'productSelector', 'full', $this->l('Add products to order'), '../modules/' . $this->module . '/images/products.png');
    }
	
	
	private function displayStatusInfo($currentState, $language)
	{
		$orderStates = OrderState::getOrderStates($language);
		$dropDown    = self::createDropDown($orderStates, 'id_order_state', 'name', $currentState, 'orderStatus');
		
		echo self::createFieldset($dropDown, 'orderStates', 'halfBox', $this->l('Status'), '../modules/' . $this->module . '/images/status.png');
	}
	
	
	private function displayPaymentModules($order, $language)
	{
		$paymentModules = array();
		$modules = Module::getModulesOnDisk();
		$selectedId = Validate::isLoadedObject($order) ? self::getModuleIdByName($order->module) : null;
		
		foreach ($modules AS $module)
			if (($module->tab == 'Payment' || $module->tab == 'payments_gateways') && isset($module->id))
				$paymentModules[] = (array)$module;
		
		$dropDown = self::createDropDown($paymentModules, 'id', 'displayName', $selectedId, 'paymentModule');		
		
		echo self::createFieldset($dropDown, 'orderStates', 'halfBox', $this->l('Payment'), '../modules/' . $this->module . '/images/currency.png');
	}
	
	
	private function displayCurrencyInfo($currentCurrency)
	{
		$currencies = Currency::getCurrencies(false);
		$dropDown   = self::createDropDown($currencies, 'id_currency', 'name', $currentCurrency, 'orderCurrency');
		
		echo self::createFieldset($dropDown, 'orderCurrencies', 'halfBox', $this->l('Order Currency'), '../modules/' . $this->module . '/images/currency.png');
	}
	
	
	private function displayAddress($order, $link, $language, $delivery = false)
	{
	    global $cookie;
		
		$fieldsetId    = 'address' . ($delivery ? 'delivery' : 'invoice');
		$fieldsetLabel = $delivery ? $this->l('Shipping address') : $this->l('Invoice address');
		$fieldsetIcon  = '../modules/' . $this->module . '/images/' . ($delivery ? 'shipping' : 'invoice') . '.png';
		
		$content = '
		<div class="addressContainer">';

		if (isset($order))
			$content.= $this->displayAddressInfo($order, $link, $language, $delivery);
		
		$content.= '
		</div>';
		
		echo self::createFieldset($content, $fieldsetId, 'halfBox', $fieldsetLabel, $fieldsetIcon);
	}
	
	
	private function displayOrderLanguages($order)
	{
		global $cookie;
		
		$selected = Validate::isLoadedObject($order) ? $order->id_lang : (int)$cookie->id_lang;
		
		$languages = Language::getLanguages();
		
		$dropDown   = self::createDropDown($languages, 'id_lang', 'name', $selected, 'orderLanguage');
		
		echo self::createFieldset($dropDown, 'orderLanguage', 'halfBox', $this->l('Order Language'), '../modules/' . $this->module . '/images/currency.png');
	}
	
	
	private function displayOrderDate($order)
	{
		if(isset($order))
			$dateTime = $order->date_add;
		else
			$dateTime = date('Y-m-d H:i:s');
			
		list($date, $time) = explode(' ', $dateTime);
		
		list($hour, $minute, $second) = explode(':', $time);
		
		$dateInput = '
		<script type="text/javascript">
			$(function() {
				$("#order_date_add").datetimepicker({
					prevText:"",
					nextText:"",
					dateFormat: "yy-mm-dd",
					timeFormat: "hh:mm:ss",
					defaultDate: "' . $date . '",
					showSecond: true,
					hourGrid: 4,
					minuteGrid: 10,
					secondGrid: 10,
					timeText: "' . $this->l('Time') . '",
					hourText: "' . $this->l('Hours') . '",
					minuteText: "' . $this->l('Minutes') . '",
					secondText: "' . $this->l('Second') . '",
					hour: ' . (int)$hour . ',
					minute: ' . (int)$minute . ',
					second: ' . (int)$second . '
				});
			});
		</script>
		<input id="order_date_add" type="text" value="' . $dateTime . '" name="order_date_add">';
		
		echo self::createFieldset($dateInput, 'orderDate', 'halfBox', $this->l('Date'), '../modules/' . $this->module . '/images/calendar.png');
	}

	
	private function displayAddressInfo($order, $link, $language, $delivery = false)
	{
	    global $cookie; 

		$address = new Address((int)($delivery ? $order->id_address_delivery : $order->id_address_invoice), $language->id);
		if (Validate::isLoadedObject($address) AND $address->id_state)
			$state = new State((int)$address->id_state);
		
		$output = '
		<input type="hidden" class="id_address"  value="' . $address->id . '" />
        <input type="hidden" class="id_customer" value="' . $order->id_customer . '" />
		<div style="float: right">
			<a href="?tab=AdminAddresses&id_address='.$address->id.'&addaddress&realedit=1&id_order='.$order->id. ($order->id_address_invoice == $order->id_address_delivery ? '&address_type=' . ($delivery ? '1' : '2') : '') . '&token='.Tools::getAdminToken('AdminAddresses'.(int)Tab::getIdFromClassName('AdminAddresses').(int)$cookie->id_employee).'&back='.urlencode($_SERVER['REQUEST_URI']).'"><img src="../img/admin/edit.gif" /></a>
			<a href="http://maps.google.com/maps?f=q&hl=' . $language->iso_code . '&geocode=&q=' . $address->address1.' '.$address->postcode.' '.$address->city.($address->id_state ? ' '.$state->name: '').'"><img src="../img/admin/google.gif" alt="" class="middle" /></a>
		</div>
		' . (!empty($address->company) ? $address->company.'<br />' : '') .$address->firstname . ' ' . $address->lastname . '<br />
		' . $address->address1.'<br />'. (!empty($address->address2) ? $address->address2.'<br />' : '') . '
		' . $address->postcode.' '.$address->city.'<br />
		' . $address->country.($address->id_state ? ' - '.$state->name : '') . '<br />
		' . ( ! empty($address->phone) ? $address->phone.'<br />' : '') . '
		' . ( ! empty($address->phone_mobile) ? $address->phone_mobile.'<br />' : '') . '
		' . ( ! empty($address->other) ? '<hr />' . $address->other . '<br />' : '');
		
		return $output;
	}
	
	
	private function displayShippingInfo($order, $id_lang, $currency, $shippingPrice)
	{
		$orderCarrier = isset($order) ? $order->id_carrier : null;
		
		/*
		 * PS 1.4.x takes one additional parameter to display external carriers,
		 * so we need to check which parameters we can pass to the "getCarriers"
		 * method.
		 */
		$reflector = new ReflectionClass('Carrier');
		$parameters = $reflector->getMethod('getCarriers')->getParameters();
		$carrierParams = array();
		
		$knownProperties = array(
			'id_lang'         => (int)$id_lang,
			'modules_filters' => 4
		);
		
		foreach ($parameters as $parameter)
		{
			$parameterName = $parameter->getName();
			
			if (array_key_exists($parameterName, $knownProperties))
				$carrierParams[$parameterName] = $knownProperties[$parameterName];
			elseif ($parameter->isOptional())
				$carrierParams[$parameterName] = $parameter->getDefaultValue();
			else
				die(Tools::displayError('Unexpected parameter in getCarrier method: ' . $parameterName));
		}
		
		$carriers = call_user_func_array(array('Carrier', 'getCarriers'), $carrierParams);
		$dropDown = self::createDropDown($carriers, 'id_carrier', 'name', $orderCarrier, 'carrierNew');
		
		$output = '
		<label>' . $this->l('Carrier:') . '</label>
		<div class="formMargin">'
		. $dropDown . '
		<input type="button" class="button" id="autoCalculate" value="' . $this->l('Calculate automatically') . '" />
		</div>
		
		<label>' . $this->l('Shipping Price:') . '</label>
		<div class="formMargin" id="shippingPriceContainer">
			<p class="editable">
				<span class="customValue"></span>
				<span class="publicView">
					' . Tools::displayPrice($shippingPrice, $currency, false, false) . '
				</span>
				<span class="realValue">
					<input type="text" size="10" class="priceFormat" id="total_shipping" name="total_shipping" value="' . $shippingPrice . '" />
				</span>
			</p>
		</div>';
		
		echo self::createFieldset($output, 'orderCarriers', 'halfBox', $this->l('Shipping Information'), '../modules/' . $this->module . '/images/shipping.png');
	}
	
	private function displayDiscountInfo($order, $currency)
	{
		$discounts = 0;
		
		if (Validate::isLoadedObject($order))
		{
			$discounts      = (float)$order->total_discounts;
			$listDiscounts	= $order->getDiscounts();
		}

		// Begin Modif YB 20/07/2011 - Editing discount coupon from Orlique
		$output = '
		<label>' . $this->l('Current discount:') . '</label>
		<div class="margin-form">
			<p class="editable" id="orderDiscountsValue">
				<span class="customValue"></span>
				<span class="publicView">
					' . Tools::displayPrice($discounts, $currency, false, false) . '
				</span>
				<span class="realValue">
					<label class="t" for="total_discount">' . $this->l('Value') . ':</label>
					<input onchange="discountEdit();" type="text" size="6" class="priceFormat" id="total_discount" name="total_discount" value="' . $discounts . '" />
				</span>
			</p>
			<p class="editable" id="orderDiscountsPercent">
				<span class="customValue"></span>
				<span class="publicView">
					0%
				</span>
				<span class="realValue">
					<label class="t" for="discount_percent">' . $this->l('Percent') . ':</label>
					<input onchange="percentEdit();" type="text" size="6" class="percentageFormat" id="discount_percent" name="discount_percent" value="0" />%
				</span>
			</p>
			<p class="clear">' . $this->l('Set a discount for this order') . '</p>
		</div>';
		
		/*$output.= '
		<label>' . $this->l('Discount coupon(s) code(s):') . '</label>
		<div class="margin-form">
			<div class="discountsRow">';
		
		$i = 0;
		if (isset($listDiscounts) && is_array($listDiscounts) && count($listDiscounts) > 0)
		{
			foreach($listDiscounts as $curcoupon)
			{
				$curcoupon['name'];
				
				$output.= '
				<div class="duplicatedRow discount">
					<input type="text" class="discountInput" name="discount_coupon[' . $i . ']" value="' . $curcoupon['name'] . '" />
					<a class="removeRow">' . $this->l('Delete this row') . '</a>
				</div>';
				
				$i++;
			}
		}
		
		$output.= '
			<div class="duplicatedRow discount">
				<input type="text" class="discountInput" name="discount_coupon[' . $i . ']" value="" />
			</div>';
		
		$output.= '
				<a class="duplicator appendRight">' . $this->l('Add another discount') . '</a>
			</div>
			<p class="clear">' . $this->l('Manage discount coupons for this order') . '</p>
		</div>';*/

		echo self::createFieldset($output, 'orderDiscounts', 'full');
	}
	
	private function displayInvoiceSettings($order)
	{
		if ( ! Validate::isLoadedObject($order))
			$output = '<p class="warning warn">' . $this->l('Please save the order first, then open it in editing mode') . '</p>';
		else
		{
			$languages = Language::getLanguages();
			$defaultLanguage = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
			
			$orliqueInvoiceID = OrliqueInvoice::getInvoiceByOrderId($order->id);
			
			$orliqueInvoice = new OrliqueInvoice((int)$orliqueInvoiceID);

			$output = '
			<form method="post" id="orliqueInvoice">
				<fieldset class="full" id="orliqueInvoiceWrapper">
					<label>' . $this->l('Status') . '</label>
					<div class="margin-form">
						<input style="float:left;" type="radio" name="active" id="active_on" value="1" ' . ($this->getFieldValue($orliqueInvoice, 'active') ? 'checked="checked" ' : '') . '/>
						<label for="active_on" class="t">
							<img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" style="float:left; padding:0px 5px 0px 5px;" />
							' . $this->l('Enabled') . '
						</label>
						<br class="clear" />
						
						<input style="float:left;" type="radio" name="active" id="active_off" value="0" ' . (!$this->getFieldValue($orliqueInvoice, 'active') ? 'checked="checked" ' : '') . '/>
						<label for="active_off" class="t">
							<img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" style="float:left; padding:0px 5px 0px 5px" />
							' . $this->l('Disabled'). '
						</label>
						<p class="clear">' . $this->l('This option enables or disables the additional content only, not the actual invoice.') . '</p>
					</div>
					
					<label>' . $this->l('Invoice Contents:') . '</label>
					<div class="margin-form">';
				
			foreach ($languages as $language)
			{
				$output.= '
						<div id="ccontent_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage->id ? 'block' : 'none') . '; float: left;">
							<textarea cols="80" rows="20" id="content_' . $language['id_lang'] . '" name="content_' . $language['id_lang'] . '">' . htmlentities($this->getFieldValue($orliqueInvoice, 'content', (int)$language['id_lang']), ENT_COMPAT, 'UTF-8').'</textarea>
						</div>';
			}
			
			$output.= $this->displayFlags($languages, $defaultLanguage->id, 'ccontent', 'ccontent', true);
			
			$output.= '
						<p class="clear">' . $this->l('Enter the text you\'d like to display in this invoice. Please note that all HTML formatting will be removed, use plain text only.') . '</p>
					</div>
					<div class="clear space">&nbsp;</div>
					
					<div class="margin-form">
						<input type="submit" name="saveOrliqueInvoice" class="button" value="' . $this->l('Save') . '" />
					</div>
				</fieldset>
			</form>';
		}
		
		echo $output;
	}

	
	private function displayWrappingInfo($wrapping, $currency)
	{
		$output = '
			<p class="editable" style="margin: 2px 0;">
				<span class="customValue"></span>
				<span class="publicView">
					' . Tools::displayPrice($wrapping, $currency, false, false) . '
				</span>
				<span class="realValue">
					<input type="text" size="10" id="total_wrapping" class="priceFormat" name="total_wrapping" value="' . $wrapping . '" />
				</span>
			</p>';
		
		echo self::createFieldset($output, 'orderWrapping', 'halfBox', $this->l('Wrapping'), '../modules/' . $this->module . '/images/wrapping.png');
	}

	
	private function displayOrderTotals($order, $currency)
	{
		$discounts    = isset($order) ? $order->total_discounts : 0;
		$wrapping     = isset($order) ? $order->total_wrapping : 0;
		$shipping     = isset($order) ? $order->total_shipping : 0;
		$totalPaid    = isset($order) ? $order->total_paid : 0;
		$totalPaidR   = isset($order) ? $order->total_paid_real : 0;

		$output = '
			<div class="formMargin">
				<table class="table" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width="150px;">
							' . $this->l('Products') . '
						</td>
						<td align="right" id="orderTotalProducts">
							<span class="customValue orderDetails">' . Tools::displayPrice($totalProducts, $currency, false, false) . '</span>
							<input type="hidden" id="orderTotalProductsF" value="' . $totalProducts . '" />
						</td>
					</tr>
					<tr>
						<td>
							' . $this->l('Discounts') . '
						</td>
						<td align="right" id="orderTotalDiscounts">
							<span class="customValue orderDetails">' . Tools::displayPrice($discounts, $currency, false, false) . '</span>
						</td>
					</tr>
					<tr>
						<td>
							' . $this->l('Wrapping') . '
						</td>
						<td align="right" id="orderWrappingPrice">
							<span class="customValue orderDetails">' . Tools::displayPrice($wrapping, $currency, false, false) . '</span>
						</td>
					</tr>
					<tr>
						<td>
							' . $this->l('Shipping') . '
						</td>
						<td align="right" id="orderShippingPrice">
							<span class="customValue orderDetails">' . Tools::displayPrice($shipping, $currency, false, false) . '</span>
						</td>
					</tr>
					<tr style="font-size: 20px">
						<td>' . $this->l('Total') . '</td>
						<td align="right" id="orderTotalPrice">
							<span class="customValue orderDetails">' . Tools::displayPrice($totalPaid, $currency, false, false) . ($totalPaid != $totalPaidR ? '<br /><font color="red">(' . $this->l('Paid:') . ' ' . Tools::displayPrice($totalPaidR, $currency, false, false).')</font>' : '') . '</span>
							<input type="hidden" id="orderTotalPriceF" value="' . $totalPaid . '" />
						</td>
					</tr>
				</table>
			</div>';
			
		if (Validate::isLoadedObject($order))
		{
			$orderState = OrderHistory::getLastOrderState($order->id);
			
			if (Validate::isLoadedObject($orderState))
			{
				if ($orderState->invoice && $order->invoice_number)
				{
					$output.= '
					<div class="formMargin" id="invoicePDF">
						<a href="pdf.php?id_order=' . (int)($order->id) . '&pdf">
							<img src="../img/admin/tab-invoice.gif" alt="invoice" />
							' . $this->l('Download invoice') . '
						</a>
					</div>';
				}
				
				if ($orderState->delivery && $order->delivery_number)
				{
					$output.= '
					<div class="formMargin" id="deliveryPDF">
						<a href="pdf.php?id_delivery=' . (int)($order->delivery_number) . '">
							<img src="../img/admin/delivery.gif" alt="delivery" />
							' . $this->l('Download delivery slip') . '
						</a>
					</div>';
				}
			}
		}
		
		echo self::createFieldset($output, 'orderTotalsWrapper', 'halfBox', $this->l('Order details'), '../modules/' . $this->module . '/images/view.png');
	}
	
	
	private function displayOrderedProducts($order, $currency)
	{
		global $smarty, $currentIndex, $cookie;
		
		//print_r($order);
		//exit;
		$discounts  = isset($order) ? $order->getDiscounts() : 0;
		$products   = isset($order) ? OrliqueHelper::getOrderProducts($order) : array();
		//print_r($products);
		//exit;

		$orderId    = isset($order) ? (int)$order->id : false;
		$weightUnit = Configuration::get('PS_WEIGHT_UNIT');
		
		$result = '
        <script type="text/javascript">
            var ajaxPath       = "' . _MODULE_DIR_ . $this->module . '/xhr/ajax.php";
            var id_lang        = '  . (int)$cookie->id_lang . ';
            var iem            = '  . (int)$cookie->id_employee . ';
            var iemp           = "' . $cookie->passwd . '";
            var labelNoComb    = "' . $this->l('This product does not have combinations') . '";
			var weightUnit     = "' . $weightUnit . '";
			var weightUnit     = "' . $weightUnit . '";
        </script>
		<form method="post" id="orderForm" action="' . _MODULE_DIR_ . $this->module . '/xhr/ajax.php">';
		if ($products && sizeof($products))
			foreach ($products as $index => $product)
				$result.= '
				<input type="hidden" class="orderDetailId" name="product[' . (int)$index . '][' . (int)$product['product_id'] . '][order_detail]" value="' . (int)$product['id_order_detail'] . '" />';
		
		$result.= '
			<input type="hidden" id="orderId" name="orderId" value="' . $orderId . '" />
			<fieldset class="full" id="orderedProductsWrapper">
				<legend>
					<img src="../modules/' . $this->module . '/images/add_to_order.png" alt="' . $this->l('Products') . '" />' . $this->l('Products') . '
				</legend>
				<div style="overflow: hidden;">
					<table class="table" cellspacing="0" cellpadding="0" id="orderContents">
						<thead>
							<tr>
								<th>' . $this->l('Image') . '</th>
								<th width="220">' . $this->l('Product') . '</th>
								<th>' . $this->l('Reference') . '</th>
								<th>' . $this->l('Quantity') . '</th>
								<th>' . $this->l('Weight') . '</th>
								<th>' . $this->l('Price (tax excl.)') . '</th>
								<th>' . $this->l('Tax rate') . '</th>
								<th>' . $this->l('Price (tax incl.)') . '</th>
								<th>' . $this->l('Total (tax incl.)') . '</th>
								<th></th>
							</tr>
						</thead>
						<tbody>';
						$tokenCatalog = Tools::getAdminToken('AdminCatalog'.(int)Tab::getIdFromClassName('AdminCatalog').(int)$cookie->id_employee);
					
						$productData = array();
		
						foreach ($products as $k => $product)
						{
							$image = OrliqueHelper::getImage((int)$product['product_id'], (isset($product['product_attribute_id']) ? (int)$product['product_attribute_id'] : null));
								
							if (isset($image['id_image']))
							{
								$target = _PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$product['product_id'] . (isset($product['product_attribute_id']) ? '_' . (int)$product['product_attribute_id'] : '') . '.jpg';
					
								if (file_exists($target))
								{
									$size = getimagesize($target);
									
									$imageHtml = OrliqueHelper::getImageHtmlStr((int)$image['id_image'], (int)$product['product_id'], (isset($product['product_attribute_id']) ? (int)$product['product_attribute_id'] : null));

									$products[$k]['image_size'] = getimagesize($target);
									$products[$k]['image_html'] = $imageHtml;
								}
							}
							
							$key = ($taxSettings == PS_TAX_INC) ? 'product_price' : 'product_price_wt';
							
							if ($product['reduction_percent'] > 0)
							{
								$product[$key] = OrliqueHelper::roundPrice($product[$key] - $product[$key] * ($product['reduction_percent'] * 0.01), 2);
							}
							elseif ($product['reduction_amount'] > 0)
							{
								$reduction = $product['reduction_amount'];
								
								if ($key == 'product_price')
								{
									$reduction/= (1 + $product['tax_rate'] / 100);
								}
								
								$product[$key]-= $reduction;
							}
							
							array_push($productData, array(
								'rowId'             => 'od_' . (int)$product['id_order_detail'],
								'currency'          => $currency->id,
								'index'             => $k,
								'productId'         => (int)$product['product_id'],
								'combination'       => $product['product_attribute_id'],
								'productNamePublic' => str_replace(array("\r\n", "\n", "\r"), '<br />', $product['product_name']),
								'productNameReal'   => str_replace('<br />', "\n", $product['product_name']),
								'reference'         => $product['product_reference'],
								'quantity'          => (int)$product['product_quantity'],
								'weight'            => (float)$product['product_weight'],
								'weightUnit'        => $weightUnit,
								'price'             => $product['product_price'],
								'reduction_amount'  => $product['reduction_amount'],
								'reduction_percent' => $product['reduction_percent'],
								'taxRate'           => $product['tax_rate'],
								'priceTaxed'        => $product['product_price_wt'],
								'priceTotal'        => ($product['product_price_wt'] * (int)$product['product_quantity']),
								'imgStr'            => ((isset($image['id_image']) && isset($products[$k]['image_size'])) ? ' height="' . ($products[$k]['image_size'][1] + 7) . '"' : ''),
								'image'             => (isset($image['id_image']) && isset($products[$k]['image_html']) && $products[$k]['image_html'] ? $products[$k]['image_html'] : '--')
							));
						}
						
			$smarty->assign('productData', $productData);

			$result.= $smarty->fetch(_PS_ROOT_DIR_ . '/modules/orlique/templates/product_line.tpl');
			
			
			$result.= '
					</tbody>
					</table>
				</div>
				<input type="hidden" name="submitOrderChange" value="1" />
				<div style="margin-top: 1em;">
					<label style="width: 250px;">' . $this->l('Notify client by mail') . '</label>
					<div class="margin-form">
						<input checked style="margin-top: 5px;" type="checkbox" name="notifyClient" />
						<p class="desc clear" style="margin-left: 50px; width: 420px;">' . $this->l('Check this box to send an email to client, notifying him/her about the new order or the changes you\'ve made to it') . '</p>
					</div>
				</div>
			</fieldset>
		</form>';
		
		echo $result;

	}

	
	public function displayList()
	{
		global $currentIndex;
		
		echo '
		<link rel="stylesheet" href="' . _MODULE_DIR_ . $this->module . '/css/style.css" />
		<img src="../modules/' . $this->module . '/images/logo.jpg" id="orliqueLogo" />';

		if ($this->edit AND (!isset($this->noAdd) OR !$this->noAdd))
			echo '<br /><a href="'.$currentIndex.'&add'.$this->table.'&token='.$this->token.'"><img src="../img/admin/add.gif" border="0" /> '.$this->l('Add new Order').'</a><br /><br />';
		/* Append when we get a syntax error in SQL query */
		if ($this->_list === false)
		{
			$this->displayWarning($this->l('Bad SQL query'));
			return false;
		}

		/* Display list header (filtering, pagination and column names) */
		$this->displayListHeader();
		if (!sizeof($this->_list))
			echo '<tr><td class="center" colspan="'.(sizeof($this->fieldsDisplay) + 2).'">'.$this->l('No items found').'</td></tr>';

		/* Show the content of the table */
		$this->displayListContent();

		/* Close list table and submit button */
		$this->displayListFooter();
	}
	
	
	public function displayListFooter($token = NULL)
	{
	    global $cookie;
	    
	    $orderStates = OrderState::getOrderStates((int)$cookie->id_lang);
		echo '
					</table>
						<p>';
		echo self::createDropDown($orderStates, 'id_order_state', 'name', false, false, 'id_order_state');
		echo '
							<input type="submit" class="button" name="submitStateChange'.$this->table.'" value="'.$this->l('Change Status').'" />
							<input type="submit" class="button" name="submitDel'.$this->table.'" value="'.$this->l('Delete selection').'" onclick="return confirm(\''.$this->l('Delete selected items?', __CLASS__, TRUE, FALSE).'\');" />';
		echo '
						</p>
					</td>
				</tr>
			</table>
			<input type="hidden" name="token" value="'.($token ? $token : $this->token).'" />
		</form>';
		if (isset($this->_includeTab) AND sizeof($this->_includeTab))
			echo '<br /><br />';
	}
	
	public function displayListHeader($token = null)
	{
		global $currentIndex;
		
		parent::displayListHeader($this->token);
		
		$filters = Tools::getValue('exFilter');
		
		echo '
		<tr class="advancedFilter">
			<td colspan="10">
				<table class="table" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<th colspan="2">
							' . $this->l('Advanced Order Search') . '
						</th>
					</tr>
					<tr class="filterRow">
						<td class="filterDesc">' . $this->l('Product reference:') . '</td>
						<td class="filterInput">
							<input type="text" name="exFilter[product_reference]" value="' . (isset($filters['product_reference']) ? $filters['product_reference'] : '') . '" />
						</td>
					</tr>
					<tr class="filterRow">
						<td class="filterDesc">' . $this->l('Supplier reference:') . '</td>
						<td class="filterInput">
							<input type="text" name="exFilter[product_supplier_reference]" value="' . (isset($filters['product_supplier_reference']) ? $filters['product_supplier_reference'] : '') . '" />
							<i>' . $this->l('This will only work for orders placed as usual, through FO') . '</i>
						</td>
					</tr>
					<tr class="filterRow">
						<td class="filterDesc">' . $this->l('Product name:') . '</td>
						<td class="filterInput">
							<input type="text" name="exFilter[product_name]" value="' . (isset($filters['product_name']) ? $filters['product_name'] : '') . '" />
						</td>
					</tr>
				</table>
			</td>
		</tr>';
	}
	
	private function getTotal()
	{
		global $cookie;
		
		$total = 0;
		foreach($this->_list AS $item)
			if ($item['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT'))
				$total += (float)$item['total_paid'];
			else
			{
				$currency = new Currency((int)$item['id_currency']);
				$total += OrliqueHelper::roundPrice((float)$item['total_paid'] / (float)$currency->conversion_rate, 2);
			}
		return $total;
	}
}
?>