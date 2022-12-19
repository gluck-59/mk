<?php

class card2card extends PaymentModule
{	
	public function __construct()
	{
		$this->name = 'card2card';
		$this->tab = 'Payment';
		$this->version = '0.3';
		
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		
		parent::__construct();

		$this->displayName = $this->l('Cash on delivery (COD)');
		$this->description = $this->l('Accept cash on delivery payments');
	}

	public function install()
	{
		if (!parent::install() OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		global $smarty;

		// Check if cart has product download
		foreach ($params['cart']->getProducts() AS $product)
		{
			$pd = ProductDownload::getIdFromIdProduct(intval($product['id_product']));
			if ($pd AND Validate::isUnsignedInt($pd))
				return false;
		}

		$currency = new Currency(3);
		
//echo floatval(Cart::getTotalCart($params[cart]->id)) * floatval($currency->conversion_rate);		
		$smarty->assign(array(
			'this_path' => $this->_path,
			'total' => floatval(Cart::getTotalCart($params[cart]->id)) * floatval($currency->conversion_rate),
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
	}
	
	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		return $this->display(__FILE__, 'confirmation.tpl');
	}
}

