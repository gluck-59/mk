<?php
require_once (_PS_MODULE_DIR_.'universalpay/UniPaySystem.php');

class universalpay extends PaymentModule
{
	public function __construct()
	{
		$this->name = 'universalpay';
		$this->tab = 'payments_gateways';
		$this->version = '0.1';
		$this->author = 'PrestaLab';

		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
 
		parent::__construct();

		$this->displayName = $this->l('Universal Payment Module');
		$this->description = $this->l('Payment methods creating.');
	}

	public function install()
	{
		$sql1="CREATE TABLE `"._DB_PREFIX_."universalpay_system` (
				`id_universalpay_system` INT(10) NOT NULL AUTO_INCREMENT,
				`active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				`position` INT(10) UNSIGNED NOT NULL DEFAULT '0',
				`date_add` DATETIME NOT NULL,
				`date_upd` DATETIME NOT NULL,
				PRIMARY KEY (`id_universalpay_system`)
			) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
		$sql2="CREATE TABLE `"._DB_PREFIX_."universalpay_system_lang` (
				`id_universalpay_system` INT(10) UNSIGNED NOT NULL,
				`id_lang` INT(10) UNSIGNED NOT NULL,
				`name` VARCHAR(128) NOT NULL,
				`description_short` VARCHAR(255) NOT NULL,
				`description` TEXT NULL,
				UNIQUE INDEX `universalpay_system_lang_index` (`id_universalpay_system`, `id_lang`)
			) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
		return parent::install()
		       && $this->registerHook('payment')
		       && Db::getInstance()->Execute($sql1)
		       && Db::getInstance()->Execute($sql2)
		       && mkdir(_PS_IMG_DIR_.'pay')
		       && self::installModuleTab('AdminUniPaySystem', array('ru' => 'Платежные системы', 'default' => 'Pay Systems'), 4);
	}

	public function uninstall()
	{
		$sql1='DROP TABLE `'._DB_PREFIX_.'universalpay_system`';
		$sql2='DROP TABLE `'._DB_PREFIX_.'universalpay_system_lang`';
		return self::uninstallModuleTab('AdminUniPaySystem')
		       && Db::getInstance()->Execute($sql1)
		       && Db::getInstance()->Execute($sql2)
		       && self::rrmdir(_PS_IMG_DIR_.'pay')
		       && parent::uninstall();
	}

	function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
		return true;
	}

	private static function createMultiLangField($field)
	{
		$languages = Language::getLanguages(false);
		$res = array();
		foreach ($languages AS $lang)
			$res[$lang['id_lang']] = $field;
		return $res;
	}

	private function installModuleTab($tabClass, $tabName, $idTabParent)
	{
		@copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$tabClass.'.gif');
		$tab = new Tab();
		$languages = Language::getLanguages(true);
		foreach ($languages as $language)
		{
			if (!isset($tabName[$language['iso_code']]))
				$tab->name[$language['id_lang']] = $tabName['default'];
			else
				$tab->name[(int)$language['id_lang']] = $tabName[$language['iso_code']];
		}
		$tab->class_name = $tabClass;
		$tab->module = $this->name;
		$tab->id_parent = $idTabParent;
		if(!$tab->save())
			return false;
		return true;
	}

	private function uninstallModuleTab($tabClass)
	{
		$idTab = Tab::getIdFromClassName($tabClass);
		if($idTab != 0)
		{
			$tab = new Tab($idTab);
			$tab->delete();
			return true;
		}
		return false;
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;
		if (!$this->_checkCurrency($params['cart']))
			return ;

		global $smarty, $cookie;

		$smarty->assign(array(
			'this_path' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/',
			'universalpay' => UniPaySystem::getPaySystems($cookie->id_lang)
		));
		return $this->display(__FILE__, 'payment.tpl');
	}

	public function execPayment($cart)
	{
		if (!$this->active)
			return ;
		if (!$this->_checkCurrency($cart))
			Tools::redirectLink(__PS_BASE_URI__.'order.php');

		global $cookie, $smarty;

		$paysistem=new UniPaySystem((int)Tools::getValue('id_universalpay_system'), $cookie->id_lang);
		if(!Validate::isLoadedObject($paysistem))
			return ;

		$smarty->assign(array(
			'nbProducts' => $cart->nbProducts(),
			'paysistem' => $paysistem,
			'total' => $cart->getOrderTotal(true, Cart::BOTH),
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
		));

		return $this->display(__FILE__, 'payment_execution.tpl');
	}

	public function validation($cart)
	{
		if (!$this->active)
			return ;

		$customer = new Customer((int)$cart->id_customer);

		if (!Validate::isLoadedObject($customer))
			Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

		global $cookie;

		$paysistem=new UniPaySystem((int)Tools::getValue('id_universalpay_system'), $cookie->id_lang);
		if(!Validate::isLoadedObject($paysistem))
			return ;

		$total = (float)($cart->getOrderTotal(true, Cart::BOTH));
		$mailVars = array(
			'{paysistem_name}' => $paysistem->name
		);

		$this->validateOrder($cart->id, _PS_OS_PREPARATION_, $total, $paysistem->name, NULL, $mailVars, (int)$cart->id_currency, false, $customer->secure_key);
		Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$this->id.'&id_order='.$this->currentOrder.'&key='.$customer->secure_key);
	}

	private function _checkCurrency($cart)
	{
		$currency_order = new Currency((int)($cart->id_currency));
		$currencies_module = $this->getCurrency((int)$cart->id_currency);
		$currency_default = Configuration::get('PS_CURRENCY_DEFAULT');

		if (is_array($currencies_module))
			foreach ($currencies_module AS $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;
		return false;
	}

	public function getContent()
	{
		global $cookie;
		return '
		<fieldset style="width: 300px;float:right;margin-left:15px;">
			<legend><img src="../img/admin/manufacturers.gif" /> ' . $this->l('Information') . '</legend>
			<div id="dev_div">
				<span><b>' . $this->l('Version') . ':</b> ' . $this->version . '</span><br>
				<span><b>' . $this->l('License') . ':</b> <a class="link" href="http://www.opensource.org/licenses/osl-3.0.php" target="_blank">OSL 3.0</a></span><br>
				<span><b>' . $this->l('Developer') . ':</b> <a class="link" href="mailto:admin@prestalab.ru" target="_blank">ORS</a><br>
				<span><b>' . $this->l('Description') . ':</b> <a class="link" href="http://prestalab.ru/moduli-oplaty/46-universalnyj-modul-oplaty.html" target="_blank">PrestaLab.ru</a><br>
				<p style="text-align:center"><a href="http://prestalab.ru/"><img src="http://prestalab.ru/upload/banner.png" alt="' . $this->l('PrestaShop modules') . '" /></a></p>
			</div>
		</fieldset>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend>'.$this->l('Configuration').'</legend>
				'.$this->l('Add payment methods on').' <a href="?tab=AdminUniPaySystem&token='.Tools::getAdminToken('AdminUniPaySystem'.(int)(Tab::getIdFromClassName('AdminUniPaySystem')).(int)($cookie->id_employee)).'" class="link">'.$this->l('Payments>Pay Systems tab').'</a>
				</fieldset>
		</form>
		';
	}
}