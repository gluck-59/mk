<?php

class TerminalPay extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();
	public  $owner;    //Кошелек получателя
	public  $paysys;   //Платежная система
    public	$icq;
    public	$pochta;
    public	$skype;

	public function __construct()
	{
		$this->name = 'terminalpay';
		$this->tab = 'Payment';
		$this->version = 0.3;
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';

		$config = Configuration::getMultiple(array('TERMINAL_PAY_OWNER', 'TERMINAL_PAY_ICQ', 'TERMINAL_PAY_POCHTA', 'TERMINAL_PAY_SKYPE', 'TERMINAL_PAY_PAYSYS'));
		if (isset($config['TERMINAL_PAY_OWNER']))
			$this->owner = $config['TERMINAL_PAY_OWNER'];
		if (isset($config['TERMINAL_PAY_ICQ']))
			$this->icq = $config['TERMINAL_PAY_ICQ'];
		if (isset($config['TERMINAL_PAY_POCHTA']))
			$this->pochta = $config['TERMINAL_PAY_POCHTA'];
		if (isset($config['TERMINAL_PAY_SKYPE']))
			$this->skype = $config['TERMINAL_PAY_SKYPE'];
		if (isset($config['TERMINAL_PAY_PAYSYS']))
			$this->paysys = $config['TERMINAL_PAY_PAYSYS'];

		parent::__construct();

		$this->displayName = $this->l('Оплата через терминал');
		$this->description = $this->l('Получение оплаты за товар посредством аппаратов моментальной оплаты');
		$this->confirmUninstall = $this->l('Вы действительно хотите удалить ваши данные?');
		if (!isset($this->owner) OR !isset($this->icq))
			$this->warning = $this->l('Получатель и другие данные должны быть установлены для корректного использования данного модуля');
		if (!sizeof(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('Не установлена валюта для данного модуля');
	}

	public function install()
	{
		if (!parent::install() OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('TERMINAL_PAY_OWNER')
                OR !Configuration::deleteByName('TERMINAL_PAY_ICQ')
                OR !Configuration::deleteByName('TERMINAL_PAY_POCHTA')
                OR !Configuration::deleteByName('TERMINAL_PAY_SKYPE')
                OR !Configuration::deleteByName('TERMINAL_PAY_PAYSYS')
				OR !parent::uninstall())
			return false;
	}

	private function _postValidation()
	{
		if (isset($_POST['btnSubmit']))
		{
			if (empty($_POST['owner']))
				$this->_postErrors[] = $this->l('Необходимо указать электронный кошелек получателя.');
			if (empty($_POST['paysys']))
				$this->_postErrors[] = $this->l('Необходимо указать название системы платежа.');

		}
	}

	private function _postProcess()
	{
		if (isset($_POST['btnSubmit']))
		{
			Configuration::updateValue('TERMINAL_PAY_OWNER', $_POST['owner']);
            Configuration::updateValue('TERMINAL_PAY_ICQ', $_POST['icq']);
            Configuration::updateValue('TERMINAL_PAY_POCHTA', $_POST['pochta']);
            Configuration::updateValue('TERMINAL_PAY_SKYPE', $_POST['skype']);
            Configuration::updateValue('TERMINAL_PAY_PAYSYS', $_POST['paysys']);
		}
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Данные обновлены').'</div>';
	}

	private function _displayTerminalPay()
	{
		$this->_html .= '<img src="../modules/terminalpay/terminalpay.jpg" style="float:left; margin-right:15px;"><b>'.$this->l('Данный модуль позволяет принимать оплату через терминал - Аппарат моментальной оплаты.').'</b><br /><br />
		'.$this->l('Если покупатель выбирает этот способ оплаты, его заказ переходит в статус  \'Ожидает оплаты\'.').'<br />
		'.$this->l('После этого, вы должны самостоятельно проверить поступление денег на выбранный Вами счет, за указанный покупателем список товаров...').'<br /><br /><br />';
	}

	private function _displayForm()
	{
		$this->_html .=
		'<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Платежные данные').'</legend>
				<table border="0" width="500" cellpadding="0" cellspacing="0" id="form">
					<tr><td colspan="2">'.$this->l('Пожалуйста, укажите необходимые данные для осуществления платежа через терминал').'.<br /><br /></td></tr>
					<tr>
                    <td width="200" style="height: 35px;">'.$this->l('Кошелёк получателя:').'<font color="#FF000A" size="-2">*</font></td>
                    <td><input type="text" name="owner" value="'.htmlentities(Tools::getValue('owner', $this->owner), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" />
<font color="#5E5F00" size="-2">(Например: R123456789)</font>
</td>
                    </tr>

<tr>
                    <td width="200" style="height: 35px;">'.$this->l('Система платежа:').'<font color="#FF000A" size="-2">*</font></td>
                    <td><input type="text" name="paysys" value="'.htmlentities(Tools::getValue('paysys', $this->paysys), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" />
<font color="#5E5F00" size="-2">(Например: WebMoney)</font>
</td>
                    </tr>
<tr>
                    <td width="200" style="height: 35px;">'.$this->l('Контактная информация:').'</td>
                    <td>&nbsp;</td>
                    </tr>
                    <tr>
                    <td align="right" width="200" style="height: 35px;">'.$this->l('ICQ:').'</td>
                    <td><input type="text" name="icq" value="'.htmlentities(Tools::getValue('icq', $this->icq), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td>
                    </tr>
                    <tr>
                    <td align="right" width="200" style="height: 35px;">'.$this->l('Skype:').'</td>
                    <td><input type="text" name="skype" value="'.htmlentities(Tools::getValue('skype', $this->skype), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td>
                    </tr>
                                    <tr>
                    <td align="right" width="200" style="height: 35px;">'.$this->l('E-Mail:').'</td>
                    <td><input type="text" name="pochta" value="'.htmlentities(Tools::getValue('pochta', $this->pochta), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td>
                    </tr>


<tr><td colspan="2" align="center"><input class="button" name="btnSubmit" value="'.$this->l('Обновить данные').'" type="submit" /></td></tr>

				</table>
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

		$this->_displayTerminalPay();
		$this->_displayForm();

		return $this->_html;
	}

	public function execPayment($cart)
	{
		if (!$this->active)
			return ;

		global $cookie, $smarty;

		$smarty->assign(array(
			'nbProducts' => $cart->nbProducts(),
			'cust_currency' => $cookie->id_currency,
			'currencies' => $this->getCurrency(),
			'total' => number_format($cart->getOrderTotal(true, 3), 2, '.', ''),
			'isoCode' => Language::getIsoById(intval($cookie->id_lang)),
            'terminalpayICQ' => $this->icq,
            'terminalpayPOCHTA' => $this->pochta,
            'terminalpaySKYPE' => $this->skype,
            'terminalpayPAYSYS' => $this->paysys,
			'terminalpayOwner' => $this->owner,
			'this_path' => $this->_path,
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));

		return $this->display(__FILE__, 'payment_execution.tpl');
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		global $cookie, $smarty;

$total_to_pay = Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false, false);

//echo '<!--jopa';
//print_r($total_to_pay);
//print_r($params[cart]->id);
//echo '-->';

// получим список товаров в корзине и выведем в tpl
//$products = $cart->getProducts();

//print_r ($params['total_to_pay']);

$conversion_rate = new Currency($cookie->id_currency);

		$smarty->assign(array(
			'products' => $products,
			'total_terminal' =>  floatval(Cart::getTotalCart($params[cart]->id)) * floatval($conversion_rate->conversion_rate),
			'this_path' => $this->_path,
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
	}

	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		global $smarty, $cookie;
        $id_customer = intval($cookie->id_customer);
        $state = $params['objOrder']->getCurrentState();
        $address = new Address(intval($params['cart']->id_customer));
        $customer = new Customer(intval($params['cart']->id_customer));
		if ($state == _PS_OS_TERMINALPAY_ OR $state == _PS_OS_OUTOFSTOCK_)
			$smarty->assign(array(
				'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false, false),
                'firstName' => ($cookie->logged ? $cookie->customer_firstname : false),
                'lastName' => ($cookie->logged ? $cookie->customer_lastname : false),
                'address' => $address,
                'customer' => $customer,
				'terminalpayOwner' => $this->owner,
			    'terminalpayICQ' => $this->icq,
                'terminalpayPOCHTA' => $this->pochta,
                'terminalpaySKYPE' => $this->skype,
                'terminalpayPAYSYS' => $this->paysys,
				'status' => 'ok',
                'this_path' => $this->_path,
				'id_order' => $params['objOrder']->id
			));
		else
			$smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'payment_return.tpl');
	}

}
