<?php 

class PrestaBook extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'prestabook';
		$this->tab = 'Tools';
		$this->version = 1;

		parent::__construct(); // The parent construct is required for translations

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('PrestaBook');
		$this->description = $this->l('Catalog of products in a flash book');
	}

	function install()
	{
		if (!Configuration::updateValue('PRESTABOOK_NBR', 10) OR !parent::install() OR !$this->registerHook('leftColumn'))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_SKIP_CAT', 1))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_COLOR', 'RSI Sistemas'))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_WIDTH', 'Catalog'))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_HEIGHT', 'About us full desc'))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_ALIGN', 'Description short'))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_NUMBER', 'Title about us'))
			return false;
			if (!Configuration::updateValue('PRESTABOOK_SORT', 0))
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitPrestaBook'))
		{
			$nbr = intval(Tools::getValue('nbr'));
			$skipcat = Tools::getValue('skipcat');
			$color = Tools::getValue('color');
			$number = Tools::getValue('number');
			$width = Tools::getValue('width');
			$height = Tools::getValue('height');
			$align = Tools::getValue('align');
			$sort = intval(Tools::getValue('sort'));
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of products');
			else
				Configuration::updateValue('PRESTABOOK_NBR', $nbr);
				Configuration::updateValue('PRESTABOOK_COLOR', $color);
				Configuration::updateValue('PRESTABOOK_ALIGN', $align);
				Configuration::updateValue('PRESTABOOK_WIDTH', $width);
				Configuration::updateValue('PRESTABOOK_HEIGHT', $height);
				Configuration::updateValue('PRESTABOOK_NUMBER', $number);
				Configuration::updateValue('PRESTABOOK_SORT', $sort);
				
		if (!empty($skipcat))
				Configuration::updateValue('PRESTABOOK_SKIP_CAT', implode(',',$skipcat));

	
			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
				
			else
				$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
		return $output.$this->displayForm();
	}
	function recurseCategory($categories, $current, $id_category = 1, $selectids_array)
	{
		global $currentIndex;		

		echo '<option value="'.$id_category.'"'.(in_array($id_category,$selectids_array) ? ' selected="selected"' : '').'>'.
		str_repeat('&nbsp;', $current['infos']['level_depth'] * 5) . preg_replace('/^[0-9]+\./', '', stripslashes($current['infos']['name'])) . '</option>';
		if (isset($categories[$id_category]))
			foreach ($categories[$id_category] AS $key => $row)
				$this->recurseCategory($categories, $categories[$id_category][$key], $key, $selectids_array);
	}

	public function displayForm()
	{
		global $cookie,$currentIndex;

		$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.png" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Number of product displayed').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="nbr" value="'.Tools::getValue('nbr', Configuration::get('PRESTABOOK_NBR')).'" />
					<p class="clear">'.$this->l('The number featured products displayed on homepage (default: 10)').'</p>
					
				
		</div>
		
		<label>'.$this->l('Company name').'</label>
				<div class="margin-form">
					<input type="text" size="20" name="color" value="'.Tools::getValue('color', Configuration::get('PRESTABOOK_COLOR')).'" />
					<p class="clear">'.$this->l('Name of the company').'</p>
					
				
		</div>
		<label>'.$this->l('Short description').'</label>
				<div class="margin-form">
					<input type="text" size="50" name="align" value="'.Tools::getValue('align', Configuration::get('PRESTABOOK_ALIGN')).'" />
					<p class="clear">'.$this->l('A short description').'</p>
					
				
		</div>	
		<label>'.$this->l('About us title').'</label>
				<div class="margin-form">
					<input type="text" size="15" name="number" value="'.Tools::getValue('number', Configuration::get('PRESTABOOK_NUMBER')).'" />
					<p class="clear">'.$this->l('A title for ABOUT US').'</p>
					
				
		</div>
		<label>'.$this->l('Catalog title').'</label>
				<div class="margin-form">
					<input type="text" size="20" name="width" value="'.Tools::getValue('width', Configuration::get('PRESTABOOK_WIDTH')).'" />
					<p class="clear">'.$this->l('Title of the catalog section').'</p>
					
				
		</div>
		
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript">
		function tinyMCEInit(element)
		{
			$().ready(function() {
				$(element).tinymce({
					// Location of TinyMCE script
					script_url : \''.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/tiny_mce.js\',
					// General options
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
					// Theme options
					theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,
					content_css : "'.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/global.css",
					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",
					elements : "nourlconvert",
					convert_urls : false,
					language : "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'"
				});
			});
		}
		tinyMCEInit(\'textarea.rte\');
		</script>
		<script type="text/javascript">id_language = Number('.$defaultLanguage.');</script>
		
			
				
				<label>'.$this->l('About us text').'</label>
				<div class="margin-form">
			<textarea class="rte" cols="70" rows="30" id="height" name="height">'.Tools::getValue('height', Configuration::get('PRESTABOOK_HEIGHT')).'</textarea>
				</div>';
				
		
		
		$skipcat = Configuration::get('PRESTABOOK_SKIP_CAT');
		
		if (!empty($skipcat))
		{
			$skipcat_array = explode(',',$skipcat);
		}
		else
		{
			$skipcat_array = array();
		}
		
		
		$output .= '
				  <label>'.$this->l('Shop category to include in  Homepage').'</label>
				  <div class="margin-form">
						<select name="skipcat[]" multiple="multiple">';
					
		$categories = Category::getCategories(intval($cookie->id_lang));
		ob_start();
		$this->recurseCategory($categories, $categories[0][1], 1, $skipcat_array);
		$output .= ob_get_contents();
		ob_end_clean();
		$output .= '
					    </select>
							
						<p class="clear">'.$this->l('Select the categories you want to include in the book. Only root categories (ctrl+clic)').'</p>									
				   </div>
				
				
				<center><input type="submit" name="submitPrestaBook" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>						
		</form>';
					
		return $output;
	}

public function getProductscath($idcat, $id_lang, $p, $n, $orderBy = NULL, $orderWay = NULL)
	{
		global $cookie;
	
		

	
		if (empty($idcat))
		{
			return false;
		}

		if ($p < 1) $p = 1;
		if (empty($orderBy))
			$orderBy = 'position';
		if (empty($orderWay))
			$orderWay = 'ASC';
		if ($orderBy == 'id_product' OR	$orderBy == 'price' OR	$orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'manufacturer')
		{
			$orderByPrefix = 'm';
			$orderBy = 'name';
		}
		elseif ($orderBy == 'position')
			$orderByPrefix = 'cp';

		$sql = '
		SELECT p.*, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new
		            
		FROM `'._DB_PREFIX_.'category_product` cp
		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = p.`id_tax`)
		LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		WHERE cp.`id_category` IN ('.$idcat.') AND p.`active` = 1
		GROUP BY cp.`id_product`
		ORDER BY '.(isset($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
		LIMIT '.((intval($p) - 1) * intval($n)).','.intval($n);
		
		$result = Db::getInstance()->ExecuteS($sql);
		
		if ($orderBy == 'price')
			Tools::orderbyPrice($result, $orderWay);
		if (!$result)
			return false;

		/* Modify SQL result */
		return Product::getProductsProperties($id_lang, $result);
	}
	function hookRightColumn($params)
	{
		global $smarty;
		
		
		return $this->display(__FILE__, 'prestabook.tpl');
	}
	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

}