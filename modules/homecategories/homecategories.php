<?php

class Homecategories extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'homecategories';
		$this->tab = 'Products';
		$this->version = 1.2;
		$this->author = 'John Stocks';
		$this->need_instance = 0;

		parent::__construct(); // The parent construct is required for translations

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Homepage Categories');
		$this->description = $this->l('Популярные категории на главной');
	}

	function install()
	{
			return (parent::install() AND $this->registerHook('home') AND $this->registerHook('header'));
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitHomecategories'))
		{
			$nbr = intval(Tools::getValue('nbr'));
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of categories');
			else
				Configuration::updateValue('HOME_categories_NBR', $nbr);
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
				<label>'.$this->l('Number of categories displayed').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="nbr" value="'.Tools::getValue('nbr', Configuration::get('HOME_categories_NBR')).'" />
					<p class="clear">'.$this->l('The number of catgeories displayed on homepage (default: 10)').'</p>
					
				</div>
				<center><input type="submit" name="submitHomecategories" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}

	function hookHome($params)
	{
//print '<pre>';

		global $smarty;//, $cookie;//, $link;
		$id_lang = (int)$params['cookie']->id_lang;

        // из топ-50 товаров вычисляются наиболее популярные категории 
        
        $products = ProductSale::getBestSales($id_lang, 0, 1000, 'sale_nbr', 'DESC', 90);
        $categories = Category::getCategories($id_lang, true, true)[1];
        $result = array();

        if ($products) {
            foreach ($products as $product)
            {
                $cat = new Category($product['id_category_default']);
                $result[] = $cat->id_category;
            }
        }
        $values = array_count_values($result);
        arsort($values, SORT_NUMERIC);
       
        foreach ($values as $key => $value)
        {
            $cat = array(
            'id_category' => $key, 
            'name' => $categories[$key]['infos']['name'],
            'description' => $categories[$key]['infos']['description'],
            'sold' => $value
            );
            $category[] = $cat;
            if ( count($category) == intval(Configuration::get('HOME_categories_NBR')) ) break;
        }

        $smarty->assign(array(
            'categories' => $category
            ));


echo('<pre>');
//print_r($category);
echo('</pre>');

        return $this->display(__FILE__, 'homecategories.tpl');
    }
}
