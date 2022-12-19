<?php

class HomeFeatured extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'homefeatured';
		$this->tab = 'Tools';
		$this->version = '0.9';

		parent::__construct();
		
		$this->displayName = $this->l('Featured Products on the homepage');
		$this->description = $this->l('Displays Featured Products in the middle of your homepage');
	}

	function install()
	{
		if (!Configuration::updateValue('HOME_FEATURED_NBR', 8)
			OR !Configuration::updateValue('FEATURED_CATEGORY', 1)
			OR !parent::install()
			OR !$this->registerHook('home'))
		{
			return false;
		}
		
		return true;
	}
	
	public function uninstall()
	{
		Configuration::deleteByName('HOME_FEATURED_NBR');
		Configuration::deleteByName('FEATURED_CATEGORY');
			
		return parent::uninstall();
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitHomeFeatured'))
		{
			//HOME_FEATURED_NBR
			$nbr = intval(Tools::getValue('nbr'));
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of product');
			else
				Configuration::updateValue('HOME_FEATURED_NBR', $nbr);
				
			//FEATURED_CATEGORY
			$category_id = intval(Tools::getValue('category_id'));
			if (!$category_id OR $category_id <= 0 OR !Validate::isInt($category_id))
				$errors[] = $this->l('Invalid category id');
			else
				Configuration::updateValue('FEATURED_CATEGORY', $category_id);
				
			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
				$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<p>'.$this->l('In order to add products to your homepage, just add them to the "home" category.').'</p><br />
				<label>'.$this->l('Number of product displayed').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="nbr" value="'.Tools::getValue('nbr', Configuration::get('HOME_FEATURED_NBR')).'" />
					<p class="clear">'.$this->l('The number of products displayed on homepage (default: 10)').'</p>
					
				</div>
				<label>'.$this->l('Category id to display').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="category_id" value="'.Tools::getValue('category_id', Configuration::get('FEATURED_CATEGORY')).'" />
					<p class="clear">'.$this->l('Category to display on homepage (default: 1(home))').'</p>
					
				</div>
				<center><input type="submit" name="submitHomeFeatured" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}
	
	function hookHome($params)
	{
		global $smarty;
		$category_id = intval(Configuration::get('FEATURED_CATEGORY')); 
		$category = new Category($category_id);
		$nb = intval(Configuration::get('HOME_FEATURED_NBR'));
		$products = ProductSale::getBestSales(intval($params['cookie']->id_lang), intval($p) - 1, 20); //$products = shuffle($products); // бестселлеры 

//		$products = Product::getNewProductsRandom(intval($params['cookie']->id_lang), 0, Configuration::get('NEW_PRODUCTS_NBR')); // рандомно из новых 
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));

//echo '<!-- pre>jopa';
//print_r($products);
	

		return $this->display(__FILE__, 'homefeatured.tpl');
	}

}

// 		$products = $category->getProducts(intval($params['cookie']->id_lang), 1, ($nb ? $nb : 10),NULL,NULL,false,true,true,($nb ? $nb : 10)); // оригинал с 1 категорией
// 		$products = Product::getNewProducts(intval($params['cookie']->id_lang), 1, ($nb ? $nb : 10),NULL,NULL,false,true,true,($nb ? $nb : 10)); // недоделанный мной getNewProducts
