<?php

class ajaxscroll extends Module
{

	public function __construct()
	{
		$this->name = 'ajaxscroll';
		$this->tab = 'Products';
		$this->version = '0.1';
		$this->author = 'PrestaLab.Ru';
 
		parent::__construct();

		$this->displayName = $this->l('Product list scroller');
		$this->description = $this->l('Pagination without page reloading');
	}

	public function install()
	{
		return parent::install() 
			&& $this->registerHook('header');
	}

	public function hookHeader($params)
	{
		if (Tools::getValue('id_category') <2)
			return;
		global $js_files, $css_files;
		$js_files[] = ($this->_path).'ajaxscroll.js';
		$css_files[($this->_path).'ajaxscroll.css'] = 'screen';
	}

	public function ajax()
	{
		global $cookie, $smarty;
		$id_category=Tools::getValue('id_category');

		if (!$id_category)
			return;
		
		$n = abs((int)(Tools::getValue('n', ((isset($cookie->nb_item_per_page) AND $cookie->nb_item_per_page >= 10) ? $cookie->nb_item_per_page : (int)(Configuration::get('PS_PRODUCTS_PER_PAGE'))))));
		$p = abs((int)(Tools::getValue('p', 1)))+1;
		
		$orderBy=(Tools::getValue('orderby')=='undefined'?false:Tools::getValue('orderby'));
		$orderWay=(Tools::getValue('orderway')=='undefined'?false:Tools::getValue('orderway'));

		$category=new Category((int)$id_category);
		$cat_products = $category->getProducts((int)$cookie->id_lang, (int)($p), (int)($n), $orderBy, $orderWay);


		if(!$cat_products)
			return;

		
		$smarty->assign(array(
				'products'=> $cat_products,
				'ajax' => 1,
				'comparator_max_item' => (int)(Configuration::get('PS_COMPARATOR_MAX_ITEM'))
		));
		
//		return $this->display(__FILE__, 'ajaxscroll.tpl');
		return $this->display(__FILE__, '../../themes/Earth/product-list.tpl');

	}
}