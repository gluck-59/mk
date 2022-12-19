<?php

class recentorders_ajax extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'recentorders_ajax';
		$this->tab = 'Products';
		$this->version = 1;

		parent::__construct();
		
		$this->displayName = $this->l('Recent Orders');
		$this->description = $this->l('Показывает крайние заказы, грузит через ajax');
	}

	function install()
	{
		if (!parent::install()
			OR !$this->registerHook('leftColumn'))
			return false;
		return true;
	}

	public function getContent()
	{
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Настройки (только чтение)').'</legend>
			
					<label for="setting1">'.$this->l('RECENT_ORDER_NUMBER').'</label>
					<input type="text" name="setting1" value="'.Configuration::get('RECENT_ORDER_NUMBER').'" /><br>
					
					<label for="setting2">'.$this->l('RECENT_ORDER_NUMBER_DISPLAY').'</label>
					<input type="text" name="setting2" value="'.Configuration::get('RECENT_ORDER_NUMBER_DISPLAY').'" /><br><br>					

				<center><input type="submit" name="submitrecentorders" value="'.$this->l('Save').'" class="button" /></center>			
			</fieldset>
		</form>';
		return $output;
	}

	function hookRightColumn($params)
	{
    
		global $smarty;
			$smarty->assign(array(
				'qty' => Configuration::get('RECENT_ORDER_NUMBER_DISPLAY'),
                'max'=> Configuration::get('RECENT_ORDER_NUMBER')
                ));
			return $this->display(__FILE__, 'recentorders.tpl');
		
	}

	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

}
