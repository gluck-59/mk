<?php

class Webmoney extends PaymentModule
{
	private	$_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'webmoney';
		$this->tab = 'Payment';
		$this->version = '0.4';
		
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		
		$config = Configuration::getMultiple(array('WEBMONEY_PURSE_R', 'WEBMONEY_PURSE_Z', 'WEBMONEY_PURSE_E', 'WEBMONEY_KEY'));
		if (isset($config['WEBMONEY_PURSE_R']))
			$this->purse_r = $config['WEBMONEY_PURSE_R'];
		if (isset($config['WEBMONEY_PURSE_Z']))
			$this->purse_z = $config['WEBMONEY_PURSE_Z'];
		if (isset($config['WEBMONEY_PURSE_E']))
			$this->purse_e = $config['WEBMONEY_PURSE_E'];
		if (isset($config['WEBMONEY_KEY']))
			$this->ikey = $config['WEBMONEY_KEY'];
			
        parent::__construct();

        $this->displayName = $this->l('WebMoney');
        $this->description = $this->l('Accepts payments by WebMoney');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
		if (!isset($this->purse_r) OR !isset($this->purse_z) OR !isset($this->purse_e))
			$this->warning = $this->l('Add any purse');
		if (!isset($this->ikey))
			$this->warning = $this->l('Add secret key');
		
	}

	public function install()
	{
		if (!parent::install() OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('WEBMONEY_PURSE_R')
				OR !Configuration::deleteByName('WEBMONEY_PURSE_Z')
				OR !Configuration::deleteByName('WEBMONEY_PURSE_E')
				OR !Configuration::deleteByName('WEBMONEY_KEY')
				OR !parent::uninstall())
			return false;
		return true;
	}


	private function _postValidation()
	{
		if (isset($_POST['submitWebmoney']))
		{
			if (empty($_POST['purse_r']) && empty($_POST['purse_z']) && empty($_POST['purse_e']))
				$this->_postErrors[] = $this->l('Add any purse');
			if (empty($_POST['ikey']))
				$this->_postErrors[] = $this->l('Add secret key');
		}
	}
	
	private function _postProcess()
	{
		if (isset($_POST['submitWebmoney']))
		{
			Configuration::updateValue('WEBMONEY_PURSE_R', $_POST['purse_r']);
			Configuration::updateValue('WEBMONEY_PURSE_Z', $_POST['purse_z']);
			Configuration::updateValue('WEBMONEY_PURSE_E', $_POST['purse_e']);
			Configuration::updateValue('WEBMONEY_KEY', $_POST['ikey']);
		}
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Settings updated').'</div>';
	}
	
	private function _displayWebMoney()
	{
		$this->_html .= '
		<img src="../modules/webmoney/logo_silver-small.png" style="float:left; margin-right:15px;" />
		<b>'.$this->l('This module allows you to accept payments by WebMoney.').'</b><br /><br />
		<br /><br />';
	}
	
	private function _displayForm()
	{
		$this->_html .=
		'<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<div><label>'.$this->l('WMR:').'</label>
			<div class="margin-form"><input type="text" size="33" maxlength="13" name="purse_r" value="'.htmlentities(Tools::getValue('purse_r', $this->purse_r), ENT_COMPAT, 'UTF-8').'" />
			<p>Формат: буква и 12 цифр</p></div>
			<div><label>'.$this->l('WMZ:').'</label>
			<div class="margin-form"><input type="text" size="33" maxlength="13" name="purse_z" value="'.htmlentities(Tools::getValue('purse_z', $this->purse_z), ENT_COMPAT, 'UTF-8').'" />
			<p>Формат: буква и 12 цифр</p></div>
			<div><label>'.$this->l('WME:').'</label>
			<div class="margin-form"><input type="text" size="33" maxlength="13" name="purse_e" value="'.htmlentities(Tools::getValue('purse_e', $this->purse_e), ENT_COMPAT, 'UTF-8').'" />
			<p>Формат: буква и 12 цифр</p></div>
			<div><label>'.$this->l('Key:').'</label>
			<div class="margin-form"><input type="text" size="33" maxlength="15" name="ikey" value="'.htmlentities(Tools::getValue('ikey', $this->ikey), ENT_COMPAT, 'UTF-8').'" />
			</div>
			<br /><center><input type="submit" name="submitWebmoney" value="'.$this->l('Save').'" class="button" /></center>
		</fieldset>
		</form><br /><br />
		<fieldset class="width3">
			<legend><img src="../img/admin/warning.gif" />'.$this->l('Information').'</legend>
			<b style="color: red;">'.$this->l('What connect WebMoney:').'</b><br />
			На сайте <b>https://merchant.webmoney.ru</b> выберите пункт меню &quot;Настройки&quot;. Пройдите авторизацию и выберите кошелек, на который вы будете принимать платежи   через сервис Web Merchant Interface. Вы получите страницу для настройки   параметров. <br />
			<br />Ниже приведен перечень заполняемых значений:</p>
			<ul>
				<li><b>Торговое имя:</b> Торговое имя (это название отображается на странице при оплате)</li>
				<li><b>Secret Key:</b> Придумайте Ваш секретный код платежа, должен знать только Ваш интернет-магазин и Webmoney</li>
				<li><b>Тестовый/Рабочий режим:</b> Тестовый - для тестов, Рабочий - для приема платежей</li>
				<li><b>Передавать параметры в предварительном запросе:</b> отмечен</li>
				<li><b>Позволять использовать URL, передаваемые в форме:</b> отмечен</li>
			</ul>
			<br />
			</fieldset>
		</form>';
	}
	
	
	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';

		if (!empty($_POST))
		{
			$this->_postValidation();
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= '<div class="alert error">'. $err .'</div>';
		}
		else
			$this->_html .= '<br />';

		$this->_displayWebMoney();
		$this->_displayForm();

		return $this->_html;
	}




	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		global $smarty;
		$id_currency = intval($params['cart']->id_currency);
		$currency = new Currency(intval($id_currency));
		
			if ($currency->iso_code == 'RUB' && Configuration::get('WEBMONEY_PURSE_R'))
				{$purse = Configuration::get('WEBMONEY_PURSE_R');}
			elseif ($currency->iso_code == 'USD' && Configuration::get('WEBMONEY_PURSE_Z'))
				{$purse = Configuration::get('WEBMONEY_PURSE_Z');}
			elseif ($currency->iso_code == 'EUR' && Configuration::get('WEBMONEY_PURSE_E'))
				{$purse = Configuration::get('WEBMONEY_PURSE_E');} 
			else
				{$purse = Configuration::get('WEBMONEY_PURSE_R'); $currency = $this->getCurrency();}


		$smarty->assign(array(
			'purse' => $purse,
			'total' => number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 3), $currency), 2, '.', ''),
			'id_cart' => intval($params['cart']->id),
			'returnUrl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/webmoney/validation.php',
			'cancelUrl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'order.php',
			'this_path' => $this->_path
		));

		return $this->display(__FILE__, 'webmoney.tpl');
    }		
}
?>
