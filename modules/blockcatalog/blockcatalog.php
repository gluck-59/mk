<?php

class blockcatalog extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'blockcatalog';
		$this->tab = 'Tools';
		$this->version = 0.9;

		parent::__construct();
		
		$this->displayName = $this->l('PDF-каталог');
		$this->description = $this->l('Показывает PDF-каталоги со slideshare.net');
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
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Настройки каталогов').'</legend>
			
					<label for="name_catalog1">'.$this->l('Название').'</label>
					<input type="text" name="name_catalog1" value="'.Configuration::get('PRODUCTS_VIEWED_NBR').'" /><br>
					<label for="url_catalog1">'.$this->l('Ссылка').'</label>
					<input type="text" name="url_catalog1" value="'.Configuration::get('PRODUCTS_VIEWED_NBR').'" /><br><br>					

					<label for="name_catalog2">'.$this->l('Название').'</label>
					<input type="text" name="name_catalog2" value="'.Configuration::get('PRODUCTS_VIEWED_NBR').'" /><br>
					<label for="url_catalog2">'.$this->l('Ссылка').'</label>
					<input type="text" name="url_catalog2" value="'.Configuration::get('PRODUCTS_VIEWED_NBR').'" /><br>					
					
					<p class="clear">'.$this->l('Введите названия каталогов и ссылки на них').'</p>
				<center><input type="submit" name="submitblockcatalog" value="'.$this->l('Save').'" class="button" /></center>			
			</fieldset>
		</form>';
		return $output;
	}

	function hookRightColumn($params)
	{
		global $link, $smarty;
$catalog = array('name' => 'Show Chrome', 'url' => 'http://www.slideshare.net/slideshow/embed_code/31834546');
//print_r($catalog);				
			$smarty->assign(array(
				'catalog' => $catalog));
			return $this->display(__FILE__, 'blockcatalog.tpl');
		
	}

	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

}
