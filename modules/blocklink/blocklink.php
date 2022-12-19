<?php
class BlockLink extends Module
{
	/* @var boolean error */
	protected $error = false;
	
	public function __construct()
	{
	 	$this->name = 'blocklink';
	 	$this->tab = 'Blocks';
	 	$this->version = '1.4';

	 	parent::__construct();

        $this->displayName = $this->l('Link block');
        $this->description = $this->l('Adds a block with additional links');
		$this->confirmUninstall = $this->l('Are you sure you want to delete all your links ?');
	}
	
	public function hookLeftColumn($params)
	{
	 	global $cookie, $smarty;
		$shipping_numbers = array();
		$tracks = array();


		$tracks = Db::getInstance()->ExecuteS('
		SELECT DISTINCT `shipping_number`, o.`id_order`, IF(SUBSTR(a.`city`, 1, 16)=a.`city`, a.`city`, CONCAT(SUBSTR(a.`city`, 1, 14), "…")) as city, a.`city` as city_full
		FROM `presta_orders` o
        JOIN `presta_address` a ON o.`id_customer` = a.`id_customer`
		WHERE TO_DAYS(NOW()) - TO_DAYS(o.date_add) > 7
        AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 60 
		AND `shipping_number`<>"" 
        GROUP BY o.`id_order` 
        ORDER BY o.`id_order` ASC
        LIMIT 30 
		');

		$sn = array();
		foreach ($tracks as $shipping_numbers) 
			{
			$city = $shipping_numbers['city'];
			$city_full = $shipping_numbers['city_full'];
			$shipping_numbers = explode(" ",$shipping_numbers['shipping_number']);
				foreach ($shipping_numbers as $shipping_number) {
					$snc = array();
					array_push($snc, $city, $city_full, $shipping_number) ;
					array_push($sn, $snc);	
				}

//echo('<!-- jopa');
//print_r($snc);
//echo('-->');


}

		$smarty->assign(array(
			'tracks' => $sn,
			//'city' => $shipping_numbers[city]
		));
		return $this->display(__FILE__, 'blocklink.tpl');
	}
	
	
	
	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}
	
}
?>
