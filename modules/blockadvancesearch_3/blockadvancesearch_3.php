<?php
/**
  * BlockAdvanceSearch Front Office Feature
  *
  * @category tools
  * @authors Jean-Sebastien Couvert & Stephan Obadia / Presta-Module.com <support@presta-module.com>
  * @copyright Presta-Module 2010
  * @version 3.4
  *
  * ************************************
  * *       BlockAdvanceSearch V3      *
  * *   http://www.presta-module.com   *
  * *               V 3.4 :            *
  * ************************************
  * +
  * +Languages: EN, FR
  * +PS version:1.3, 1.2, 1.1
  *
  **/
class BlockAdvancesearch_3 extends Module {
	private $advc_cache_lifetime = 3600; //en seconde
	private $default_tags_dif = 5;
	private $tags_max_size = 18;
	private $tags_dif = false;
	public $multi_separator = '|';
	public $dupliq_id = 3;
	/** @var Currency Current currency */
	static private	$current = NULL;
	private $indexToVerif = array(
		'category_lang' => 						array('id_lang','name'),
		'category_product' => 					array('id_category'),
		'manufacturer' => 						array('id_manufacturer','name'),
		'supplier' => 							array('id_supplier','name'),
		'product' => 							array('id_manufacturer'),
		'feature_product' => 					array('id_feature_value'),
		'feature_value_lang' => 				array('id_lang','value'),
		'product_attribute_combination' => 		array('id_attribute'),
		'attribute_lang' => 					array('id_lang','name'),
		//'filter' => 					array('id_product','name'),
		
	);
	private $advc_conf = array(
		'ADVC_ORDER' 				=> 	array(),
		'ADVC_FEATURE' 				=> 	array(),
		'ADVC_ATTRIBUTE' 			=> 	array(),
		'ADVC_FILTER_CAT' 			=> 	0,
		'ADVC_ONLY_CAT' 			=> 	array(),
		'ADVC_CACHE' 				=> 	0,
		'ADVC_SEARCH_TYPE' 			=> 	array(),
		'ADVC_MAXVISIBLE' 			=> 	array(),
		'ADVC_SEARCHTAB' 			=> 	array(),
		'ADVC_MULTICRIT' 			=> 	array(),
		'ADVC_SHOW_SELECTION' 		=> 	1,
		'ADVC_ONLY_ON_STOCK' 		=> 	0,
		'ADVC_TAGS_DIF'				=> 	5,
		'ADVC_FULL_AJAX'			=>	1,
		'ADVC_RANGE'				=> 	array(),
		'ADVC_STEP_MODE'			=>	0,
		'ADVC_SHOWCRIT_METHODE'		=>	1,
		'ADVC_WIDTH_HOOK_TOP'		=>	array(),
		'ADVC_KEEP_EMPTY_BLOCK'		=> 0,
		'ADVC_KEEP_EMPTY_RESULT'	=> 0,
		'ADVC_COMB_IMG'				=> 0,
		'ADVC_DISPLAY_NB_PRODUCT'	=> 1,
		'ADVC_SUBMIT_MODE'			=> 1
	);
	private $advcCritStyle;
	function __construct() {
		$this->name = 'blockadvancesearch_'.$this->dupliq_id;
		$this->tab = 'Products';
		$this->version = 3.4;

		parent::__construct();

		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Bloc recherche avancée');
		$this->description = $this->l('Ajoutez une recherche avancée à votre boutique.');
		if (! count(unserialize(Configuration::get('ADVC_ORDER_'.$this->dupliq_id))) || ! Configuration::get('ADVC_ORDER_'.$this->dupliq_id))
			$this->warning = $this->l('La recherche avancée doit être configurée pour fonctionner');

		//Define search style
		$this->advcCritStyle = array(
				1 => array(
					'title'				=> $this->l('Filtre lien'),
					'multicritere'		=> 1,
					'hidable'			=> 1,
					'rangeable'			=> 1
				),
				2 => array(
					'title'				=> $this->l('Liste déroulante'),
					'multicritere'		=> 0,
					'hidable'			=> 1,
					'rangeable'			=> 1
				),
				3 => array(
					'title'				=> $this->l('Champs de recherche'),
					'multicritere'		=> 0,
					'hidable'			=> 0,
					'rangeable'			=> 0
				),
				4 => array(
					'title'				=> $this->l('Nuage de tags'),
					'multicritere'		=> 1,
					'hidable'			=> 1,
					'rangeable'			=> 0
				),
				5 => array(
					'title'				=> $this->l('Checkbox'),
					'multicritere'		=> 1,
					'hidable'			=> 1,
					'rangeable'			=> 1
				),
			);
	}

	function install() {
		foreach($this->advc_conf as $key=>$val) {
			if(is_array($val)) {
				$val = serialize($val);
			}
			Configuration::updateValue($key.'_'.$this->dupliq_id, $val);
		}
		if (! parent::install() OR !$this->registerHook('leftColumn') OR !$this->registerHook('addProduct') OR !$this->registerHook('updateProduct') OR !$this->registerHook('deleteProduct') OR !$this->registerHook('updateProductAttribute'))
			return false;
		return true;
	}
	function uninstall() {
		if (parent::uninstall() == false)
			return false;
		foreach($this->advc_conf as $key=>$val) {
			Configuration::deleteByName($key.'_'.$this->dupliq_id);
		}
		return ($this->clearAdvcCache());
	}
	public function clearAdvcCache() {
		global $smarty;
		return $smarty->clear_cache(null, 'ADVCSearch_'.$this->dupliq_id);
	}
	public function getContent() {
		global $smarty;
		$output = '<h2>' . $this->displayName . '</h2>';
		//Mise à jour du type de recherche sur champs
		if (isset($_GET ['search-id'], $_GET['search-type'])) {
			$searchTypes = @unserialize(Configuration::get('ADVC_SEARCH_TYPE_'.$this->dupliq_id));
			if (! is_array($searchTypes))
				$searchTypes = array ();
			$searchTypes [$_GET ['search-id']] = $_GET ['search-type'];
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_SEARCH_TYPE_'.$this->dupliq_id, serialize($searchTypes));
			die();
		}
		//Mise à jour des recherches par tranche
		elseif (isset($_GET ['range-id'])) {
			$range = @unserialize(Configuration::get('ADVC_RANGE_'.$this->dupliq_id));
			if (! is_array($range))
				$range = array ();

			@$range [$_GET ['range-id']] = $_GET ['range'];
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_RANGE_'.$this->dupliq_id, serialize($range));
			die();
		}
		//Mise à jour des largeur dans le hook top
		elseif (isset($_GET ['widthhooktop-id'])) {
			$widthHookTop = @unserialize(Configuration::get('ADVC_WIDTH_HOOK_TOP_'.$this->dupliq_id));
			if (! is_array($widthHookTop))
				$widthHookTop = array ();

			@$widthHookTop [$_GET ['widthhooktop-id']] = intval($_GET ['width']);
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_WIDTH_HOOK_TOP_'.$this->dupliq_id, serialize($widthHookTop));
			die();
		}
		//Mise à jour du label pour les recherches par tranche
		elseif (isset($_GET ['labelrange-id'])) {
			$labelrange = @unserialize(Configuration::get('ADVC_LABEL_RANGE_'.$this->dupliq_id));
			if (! is_array($labelrange))
				$labelrange = array ();

			@$labelrange [$_GET ['labelrange-id']] = $_GET ['labelrange'];
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_LABEL_RANGE_'.$this->dupliq_id, serialize($labelrange));
			die();
		}
		//Mise à jour de la séléction maximum a afficher
		elseif (isset($_GET ['maxvisible-id'])) {
			$maxVisible = @unserialize(Configuration::get('ADVC_MAXVISIBLE_'.$this->dupliq_id));
			if (! is_array($maxVisible))
				$maxVisible = array ();

			@$maxVisible [$_GET ['maxvisible-id']] = intval($_GET ['maxvisible']);
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_MAXVISIBLE_'.$this->dupliq_id, serialize($maxVisible));
			die();
		}
		//Mise à jour de la séléction multicritere
		elseif (isset($_GET ['multicritere-id'])) {
			$SelectMulti = @unserialize(Configuration::get('ADVC_MULTICRIT_'.$this->dupliq_id));
			if (! is_array($SelectMulti))
				$SelectMulti = array ();

			if ($_GET ['setmulticritere'] == 'true')
				@$SelectMulti [$_GET ['multicritere-id']] = 1;
			elseif ($_GET ['setmulticritere'] == 'false' && isset($SelectMulti [$_GET ['multicritere-id']])) {
				unset($SelectMulti [$_GET ['multicritere-id']]);
			} else
				die();
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_MULTICRIT_'.$this->dupliq_id, serialize($SelectMulti));
			die();
		}
		//Mise à jour de la position des choix de recherche
		elseif (isset($_GET ['position'])) {
			$order = $_GET ['position'] ? explode(',', $_GET ['position']) : array ();
			$aFlnavOrder = array ();
			$aFlnavFeature = array ();
			$aFlnavAttribute = array ();
			foreach ( $order as $v ) {
				if (preg_match('#^caract_([0-9]+)#', $v, $pat)) {
					$aFlnavFeature [] = $pat [1];
					$aFlnavOrder [$v] = $pat [1];
				} elseif (preg_match('#^attr_([0-9]+)#', $v, $pat)) {
					$aFlnavAttribute [] = $pat [1];
					$aFlnavOrder [$v] = $pat [1];
				} else {
					$aFlnavOrder [$v] = 1;
				}
			}
			$this->clearAdvcCache();
			Configuration::updateValue('ADVC_ORDER_'.$this->dupliq_id, serialize($aFlnavOrder));
			Configuration::updateValue('ADVC_FEATURE_'.$this->dupliq_id, serialize($aFlnavFeature));
			Configuration::updateValue('ADVC_ATTRIBUTE_'.$this->dupliq_id, serialize($aFlnavAttribute));
			die('save');
		}
		//Mise à jour des index des tables mysql
		if (isset($_GET ['create_index'])) {
			$this->createIndex();
		}
		if (isset($_POST ['btnSubmit'])) {
			//filter_category
			Configuration::updateValue('ADVC_CACHE_'.$this->dupliq_id, (isset($_POST ['advc_cache']) ? true : false));
			Configuration::updateValue('ADVC_FULL_AJAX_'.$this->dupliq_id, (isset($_POST ['ADVC_FULL_AJAX']) ? intval($_POST ['ADVC_FULL_AJAX']) : false));
			Configuration::updateValue('ADVC_STEP_MODE_'.$this->dupliq_id, (isset($_POST ['ADVC_STEP_MODE']) ? true : false));
			Configuration::updateValue('ADVC_ONLY_ON_STOCK_'.$this->dupliq_id, (isset($_POST ['ADVC_ONLY_ON_STOCK']) ? true : false));
			Configuration::updateValue('ADVC_ONLY_CAT_'.$this->dupliq_id, (isset($_POST ['categoryBox']) ? serialize($_POST ['categoryBox']) : 0));
			Configuration::updateValue('ADVC_FILTER_CAT_'.$this->dupliq_id, (isset($_POST ['filter_category']) ? 1 : 0));
			Configuration::updateValue('ADVC_TAGS_DIF_'.$this->dupliq_id, $_POST ['tags_dif']);
			Configuration::updateValue('ADVC_SHOW_SELECTION_'.$this->dupliq_id, (isset($_POST ['ADVC_SHOW_SELECTION']) ? $_POST ['ADVC_SHOW_SELECTION'] : 0));
			Configuration::updateValue('ADVC_DISPLAY_NB_PRODUCT_'.$this->dupliq_id, (isset($_POST ['ADVC_DISPLAY_NB_PRODUCT']) ? $_POST ['ADVC_DISPLAY_NB_PRODUCT'] : 0));
			Configuration::updateValue('ADVC_SHOWCRIT_METHODE_'.$this->dupliq_id, (isset($_POST ['ADVC_SHOWCRIT_METHODE']) ? $_POST ['ADVC_SHOWCRIT_METHODE'] : 1));
			Configuration::updateValue('ADVC_KEEP_EMPTY_RESULT_'.$this->dupliq_id, (isset($_POST ['ADVC_KEEP_EMPTY_RESULT']) ? 1 : 0));
			Configuration::updateValue('ADVC_KEEP_EMPTY_BLOCK_'.$this->dupliq_id, (isset($_POST ['ADVC_KEEP_EMPTY_BLOCK']) ? 1 : 0));
			Configuration::updateValue('ADVC_COMB_IMG_'.$this->dupliq_id, $_POST ['ADVC_COMB_IMG']);
			Configuration::updateValue('ADVC_SUBMIT_MODE_'.$this->dupliq_id, (isset($_POST ['ADVC_SUBMIT_MODE']) ? intval($_POST ['ADVC_SUBMIT_MODE']) : false));

			//Mooving hook
			$advcHook = $this->getCurrentHook();
			if($advcHook && $advcHook != Tools::getValue('ADVC_HOOK'))
				$this->unregisterHook($advcHook);
			if(Tools::getValue('ADVC_HOOK') != $advcHook)
				$this->registerHook($this->getHookNameById(Tools::getValue('ADVC_HOOK')));
			$this->clearAdvcCache();
			$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="' . $this->l('OK') . '" /> ' . $this->l('Paramètres mis à jour') . '</div>';
		}
		$output .= $this->displayForm();
		$output .='<br /><fieldset><legend>PrestaModule</legend>'.$this->l('Ce module a été développé par PrestaModule.').'<br/>'.$this->l('Merci de reporter les éventuels bugs à :').' <a href="mailto:support@presta-module.com">support@presta-module.com</a></fieldset>';
		return $output;
	}
	public function verifIndex() {
		foreach($this->indexToVerif as $table_name=>$columns) {
			foreach($columns as $column_name) {
				if (! mysql_num_rows(mysql_query('SHOW INDEX FROM `'._DB_PREFIX_.$table_name.'` WHERE `column_name` = "'.$column_name.'"')))
					return false;
			}
		}
		return true;
	}
	public function createIndex() {
		foreach($this->indexToVerif as $table_name=>$columns) {
			foreach($columns as $column_name) {
				if (! mysql_num_rows(mysql_query('SHOW INDEX FROM `'._DB_PREFIX_.$table_name.'` WHERE `column_name` = "'.$column_name.'"')))
					if (! Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$table_name.'` ADD INDEX ( `'.$column_name.'`  )'))
						return false;
			}
		}
		return true;
	}
	public function getCurrentHook()
	{
		$result = Db::getInstance()->GetRow('
			SELECT hm.`id_hook`
			FROM `'._DB_PREFIX_.'hook_module` hm
			LEFT JOIN `'._DB_PREFIX_.'hook` h ON (hm.id_hook = h.id_hook)
			WHERE h.`name` IN("top","rightColumn","leftColumn") AND hm.`id_module` = '.intval($this->id));
		return ($result ? $result['id_hook'] : false);
	}
	static public function getHookNameById($id_hook)
	{
		$result = Db::getInstance()->GetRow('
		SELECT `name`
		FROM `'._DB_PREFIX_.'hook`
		WHERE `id_hook` = \''.intval($id_hook).'\'');

		return ($result ? $result['name'] : false);
	}
//эта функция выводит в админку
	public function displayForm() {
		global $cookie, $smarty;
		$order 				= @unserialize(Configuration::get('ADVC_ORDER_'.$this->dupliq_id));
		$advcCache 			= Configuration::get('ADVC_CACHE_'.$this->dupliq_id);
		$featuressave 		= @unserialize(Configuration::get('ADVC_FEATURE_'.$this->dupliq_id));
		$attributessave 	= @unserialize(Configuration::get('ADVC_ATTRIBUTE_'.$this->dupliq_id));
		$SelectMulti 		= @unserialize(Configuration::get('ADVC_MULTICRIT_'.$this->dupliq_id));
		$searchTypes 		= @unserialize(Configuration::get('ADVC_SEARCH_TYPE_'.$this->dupliq_id));
		$maxVisible 		= @unserialize(Configuration::get('ADVC_MAXVISIBLE_'.$this->dupliq_id));
		$range				= @unserialize(Configuration::get('ADVC_RANGE_'.$this->dupliq_id));
		$labelRange			= @unserialize(Configuration::get('ADVC_LABEL_RANGE_'.$this->dupliq_id));
		$widthHookTop		= @unserialize(Configuration::get('ADVC_WIDTH_HOOK_TOP_'.$this->dupliq_id));
		$isRegisteredInHookTop = $this->isRegisteredInHook('top');
		$price_range = Configuration::get('ADVC_PRICE_RANGE_'.$this->dupliq_id);
		$tags_dif = Configuration::get('ADVC_TAGS_DIF_'.$this->dupliq_id);
		$advcHook = $this->getCurrentHook();
		$tags_dif = ($tags_dif?$tags_dif:$this->default_tags_dif);
		$category = false;
		//Récupération des groupes de caractéristiques
		$features = Db::getInstance()->ExecuteS('
		SELECT  CONCAT("caract_",f.`id_feature`) AS `typeid`, f.`id_feature`, fl.`name`
		FROM `' . _DB_PREFIX_ . 'feature` f
		LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` fl ON (f.`id_feature` = fl.`id_feature` AND fl.`id_lang` = ' . intval($cookie->id_lang) . ')
		WHERE  f.`id_feature` NOT IN ("' . @implode('", "', $featuressave) . '")');

		//Récupération des groupes d'attribut
		$attributes = Db::getInstance()->ExecuteS('
		SELECT CONCAT("attr_",ag.`id_attribute_group`) AS `typeid`, agl.`name`, ag.`id_attribute_group`
		FROM `' . _DB_PREFIX_ . 'attribute_group` ag
		LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND `id_lang` = ' . intval($cookie->id_lang) . ')
		WHERE  ag.`id_attribute_group` NOT IN ("' . @implode('", "', $attributessave) . '")');

		$a = array ();
		$priceRange = false;
		$manufacturer = false;
		foreach ( (array)$order as $k => $v ) {
			//Par caracteristique
			if (preg_match('#^caract_[0-9]+#', $k)) {
				$feat = Db::getInstance()->getRow('
		SELECT CONCAT("caract_",f.`id_feature`) AS `typeid`, f.`id_feature`, fl.`name`
		FROM `' . _DB_PREFIX_ . 'feature` f
		LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` fl ON ( f.`id_feature` = fl.`id_feature` AND fl.`id_lang` = ' . intval($cookie->id_lang) . ')
		WHERE f.`id_feature` = ' . intval($v));

				$a [] = $feat;
			} //Par attribut
elseif (preg_match('#^(attr_[0-9])#', $k)) {
				$attr = Db::getInstance()->getRow('
		SELECT CONCAT("attr_",ag.`id_attribute_group`) AS `typeid`, agl.`name`, ag.`id_attribute_group`
		FROM `' . _DB_PREFIX_ . 'attribute_group` ag
		LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND `id_lang` = ' . intval($cookie->id_lang) . ')
		WHERE ag.`id_attribute_group` = ' . intval($v));
				$a [] = $attr;
			} //Par tranche de prix
elseif (preg_match('#^priceRange#', $k)) {
				$priceRange = true;
				$a [] = array ('typeid' => $k, 'name' => $this->l('Par Tranche de prix') );
			} //Par manufacturer
elseif (preg_match('#^manufacturer#', $k)) {
				$manufacturer = true;
				$a [] = array ('typeid' => $k, 'name' => $this->l('Par Fabriquant') );
			} //Par catégories
elseif (preg_match('#^category#', $k)) {
				$category = true;
				$a [] = array ('typeid' => $k, 'name' => $this->l('Par Catégories') );
			} //по маркам
elseif (preg_match('#^supplier#', $k)) {
				$supplier = true;
				$a [] = array ('typeid' => $k, 'name' => $this->l('По маркам') );
			}
		}
		//Si pas de tranche de prix on init
		if (! $priceRange) {
			$a [] = array ('typeid' => 'priceRange', 'name' => $this->l('Par Tranche de prix') );
		}
		//Si pas de manufactuer on init
		if (! $manufacturer) {
			$a [] = array ('typeid' => 'manufacturer', 'name' => $this->l('Par Fabriquant') );
		}
		//Si pas de category on init
		if (! $category) {
			$a [] = array ('typeid' => 'category', 'name' => $this->l('Par Catégories') );
		}
		//добавляем "по маркам" (supplier)
		if (! $supplier) {
			$a [] = array ('typeid' => 'supplier', 'name' => $this->l('По маркам') );
		}

		$cacheDirIsWritable = is_writable($smarty->cache_dir);
		$finalNav = array_merge($a, $features, $attributes);

		/*$this->check_final_version();*/

		$output = '';
		if (! $this->verifIndex())
			$output .= '<div class="warning warn">' . $this->l('Tous les index mysql ne sont pas créés. Cela peut engendrer des lenteurs lors des recherches.') . '<br /><input class="button" name="btnSubmit" value="' . $this->l('Mettre à jour les index') . '" type="button" onclick="location.href=\'' . $_SERVER ['REQUEST_URI'] . '&create_index=1\'" /></div>';
		$output .= '
		<style type="text/css" media="all">
		.info_screen {
		background: #F4E6C9 url("../img/admin/news-bg.gif") repeat-x left top ;
		border-bottom:1px solid #CCCCCC;
		clear:left;
		color:#cc0000;
		margin:0;
		padding:0;
		display:none;width:100%;font-size:1.4em!important;font-weight:bold!important;position:fixed;z-index:200000;bottom:0;height:40px;line-height:40px;text-align:center;}
		* html .info_screen {position:absolute;top:expression(body.scrollTop + document.body.clientHeight - 40 + "px");}
		</style>
		<p><a href="'.$this->_path.'AdvancedSearch_3_FR_documentation.pdf" target="_blank"><img src="../img/admin/pdf.gif" /> &nbsp; ' . $this->l('Vous pouvez consulter la documentation du module en cliquant ici (en français)') . '.</a></p><br />
		<form action="' . $_SERVER ['REQUEST_URI'] . '" method="post">
		<fieldset>
			<legend><img src="../img/admin/cog.gif" />' . $this->l('Paramètre d\'affichage') . '</legend>
		<table border="0" width="100%" cellpadding="0" cellspacing="0" id="form">
					<tr><td width="500" valign="top">' . $this->l('Afficher le bloc uniquement dans les catégories suivantes') . '<br />' . $this->l('Ne rien cocher aura pour effet d\'afficher la recherche sur tout le site') . '</td><td><div id="categoryList">
							<table cellspacing="0" cellpadding="0" class="table" style="width: 29.5em;">
									<tr>
										<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'categoryBox[]\', this.checked)" /><sup> *</sup></th>
										<th>' . $this->l('ID') . '</th>
										<th>' . $this->l('Nom') . '</th>
									</tr>';
		$done = array ();
		$index = array ();
		$categories = Configuration::get('ADVC_ONLY_CAT_'.$this->dupliq_id);
		$indexedCategories = ($categories) ? unserialize($categories) : array ();
		if ($indexedCategories)
			foreach ( @$indexedCategories as $v )
				$index [] = $v;
		$categories = Category::getCategories(intval($cookie->id_lang), false);
		//Récupération des groupes d'attribut
		$allAttributes = Db::getInstance()->ExecuteS('
		SELECT CONCAT("attr_",ag.`id_attribute_group`) AS `typeid`, agl.`name`, ag.`id_attribute_group`
		FROM `' . _DB_PREFIX_ . 'attribute_group` ag
		LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND `id_lang` = ' . intval($cookie->id_lang) . ')
		');
		$output .= $this->recurseCategoryForInclude($index, $categories, $categories [0] [1], 1, @$obj->id_category_default, $output);
		$output .= '</table>
							<p style="padding:0px; margin:0px 0px 10px 0px;">' . $this->l('* Choisir toutes les catégories') . '</p>
							</div></td></tr>
							<tr><td style="height: 70px;">' . $this->l('Accrocher au hook') . '</td><td><input type="radio" name="ADVC_HOOK" value="'.Hook::get('leftColumn').'" ' . ($advcHook == Hook::get('leftColumn') ? 'checked="checked"' : '') . ' /> ' . $this->l('colonne de gauche') . '<br /><input type="radio" name="ADVC_HOOK" value="'.Hook::get('rightColumn').'" ' . ($advcHook == Hook::get('rightColumn') ? 'checked="checked"' : '') . ' /> ' . $this->l('colonne de droite') . '<br /><input type="radio" name="ADVC_HOOK" value="'.Hook::get('top').'" ' . ($advcHook == Hook::get('top') ? 'checked="checked"' : '') . ' /> ' . $this->l('top') . '</td></tr>
							<tr><td style="height: 70px;">' . $this->l('Mode') . '</td><td><input type="radio" name="ADVC_FULL_AJAX" value="0" ' . (Configuration::get('ADVC_FULL_AJAX_'.$this->dupliq_id) == 0 ? 'checked="checked"' : '') . ' /> ' . $this->l('sans AJAX') . '<br /><input type="radio" name="ADVC_FULL_AJAX" value="1" ' . (Configuration::get('ADVC_FULL_AJAX_'.$this->dupliq_id) == 1 ? 'checked="checked"' : '') . ' />  ' . $this->l('AJAX pour la recherche') . '<br /><input type="radio" name="ADVC_FULL_AJAX" value="2" ' . (Configuration::get('ADVC_FULL_AJAX_'.$this->dupliq_id) == 2 ? 'checked="checked"' : '') . ' />  ' . $this->l('AJAX pour le chargement et la recherche') . '<br><b>При включении AJAX отрубятся каменты Вконтакте</b>	</td></tr>
							<tr><td style="height: 35px;">' . $this->l('Activer l\'affichage de la séléction courante') . '</td><td><input type="checkbox" name="ADVC_SHOW_SELECTION" value="1" ' . (Configuration::get('ADVC_SHOW_SELECTION_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Activer la recherche par étape') . '</td><td><input type="checkbox" name="ADVC_STEP_MODE" value="1" ' . (Configuration::get('ADVC_STEP_MODE_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Filtrer les possibilités des recherches en fonction de la catégorie courante') . '</td><td><input type="checkbox" name="filter_category" value="1" ' . (Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Recherche uniquement sur produits en stock') . '</td><td><input type="checkbox" name="ADVC_ONLY_ON_STOCK" value="1" ' . (Configuration::get('ADVC_ONLY_ON_STOCK_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Afficher les blocs n\'ayant aucun critère sélectionnable') . '</td><td><input type="checkbox" name="ADVC_KEEP_EMPTY_BLOCK" value="1" ' . (Configuration::get('ADVC_KEEP_EMPTY_BLOCK_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Afficher les critères n\'ayant aucun produit (non sélectionnable)') . '</td><td><input type="checkbox" name="ADVC_KEEP_EMPTY_RESULT" value="1" ' . (Configuration::get('ADVC_KEEP_EMPTY_RESULT_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Afficher le nombre de produit dans le bloc de recherche') . '</td><td><input type="checkbox" name="ADVC_DISPLAY_NB_PRODUCT" value="1" ' . (Configuration::get('ADVC_DISPLAY_NB_PRODUCT_'.$this->dupliq_id) ? 'checked="checked"' : '') . ' /></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Méthode pour afficher les critères masqués') . '</td><td><input type="radio" name="ADVC_SHOWCRIT_METHODE" value="1" ' . (Configuration::get('ADVC_SHOWCRIT_METHODE_'.$this->dupliq_id) == 1 ? 'checked="checked"' : '') . ' /> ' . $this->l('au passage de la souris') . '<br /><input type="radio" name="ADVC_SHOWCRIT_METHODE" value="2" ' . (Configuration::get('ADVC_SHOWCRIT_METHODE_'.$this->dupliq_id) == 2? 'checked="checked"' : '') . ' />  ' . $this->l('au clique sur un lien') . '</td></tr>
							<tr><td style="height: 70px;">' . $this->l('Méthode pour poster la recherche') . '</td><td><input type="radio" name="ADVC_SUBMIT_MODE" value="1" ' . (Configuration::get('ADVC_SUBMIT_MODE_'.$this->dupliq_id) == 1 ? 'checked="checked"' : '') . ' /> ' . $this->l('dès le choix d\'un critère') . '<br /><input type="radio" name="ADVC_SUBMIT_MODE" value="2" ' . (Configuration::get('ADVC_SUBMIT_MODE_'.$this->dupliq_id) == 2? 'checked="checked"' : '') . ' />  ' . $this->l('avec un bouton envoyer') . '</td></tr>
							<tr><td style="height: 35px;">' . $this->l('Afficher les images lié a l\'attribut') . '</td><td><select name="ADVC_COMB_IMG">';
							$output .= '<option value="0" ' . (Configuration::get('ADVC_COMB_IMG_'.$this->dupliq_id) == 0 ? 'selected="selected"' : '') . '>'.$this->l('Image par defaut').'</option>';
							if($allAttributes && count($allAttributes)) {
								foreach ( $allAttributes as $k => $v ) {
									$output .= '<option value="attr_'.$v['id_attribute_group'].'" ' . (Configuration::get('ADVC_COMB_IMG_'.$this->dupliq_id) == 'attr_'.$v['id_attribute_group'] ? 'selected="selected"' : '') . '>'.$v['name'].'</option>';
								}
							}
				$output .= '</select></td></tr>
							<tr><td style="height: 35px;">' . $this->l('Indice de différence de taille pour le style nuage de tags') . '</td><td><select name="tags_dif">';
					for($i = 1;$i<=9;$i++) {
						$output .= '<option value="'.$i.'" '.($tags_dif == $i?'selected=selected':'').'>'.$i.'</option>';
					}
				$output .= '</select></td></tr>
					<tr><td style="height: 35px;">' . $this->l('Activer le cache pour les recherches') . '</td><td><input type="checkbox" name="advc_cache" value="1" ' . ($advcCache ? 'checked="checked"' : '') . ' ' . ($cacheDirIsWritable ? '' : 'disabled="disabled"') . ' />';
		if (! $cacheDirIsWritable)
			$output .= '<div class="warning warn">' . $this->l('Pour activer le cache veuillez attribuer les droits en écriture au dossier :') . ' ' . $smarty->cache_dir . '</div>';
		$output .= '</td></tr>
					<tr><td colspan="2" align="center"><br /><input class="button" name="btnSubmit" value="' . $this->l('Mettre à jour les paramètres d\'affichage') . '" type="submit" /></td></tr>
				</table>
				</fieldset>
		</form>
		<br />
		<fieldset>
			<legend><img src="../img/admin/tab-categories.gif" />' . $this->l('Ordre et mise en place') . '</legend>
			<p>' . $this->l('Pour modifier l\'ordre d\'affichage, glissez la ligne à l\'endroit souhaité.') . '<br />
' . $this->l('Pour désactiver un élément, décoché le.') . '</p>
		<table class="table table_sort" border="0" cellspacing="0" id="tableadvancesearch">
				<tbody><tr class="nodrag nodrop">
					<th style="width: 400px;">' . $this->l('Recherche sur') . '</th>
					<th style="width: 80px;">' . $this->l('Actif / Inactif Block') . '</th>
					<th style="width: 60px;">' . $this->l('Multicritères') . '</th>
					<th style="width: 60px;">' . $this->l('Style') . '</th>
					<th style="width: 60px;">' . $this->l('Masquer au delà de') . '</th>
					<th style="width: 120px;">' . $this->l('Recherche par tranche') . '</th>
					<th style="width: 100px;">' . $this->l('Label pour les tranches') . '</th>
					<th style="width: 100px;">' . $this->l('Largeur dans le hook top (en px)') . '</th>
				</tr>';
//выводится в админку
/*
echo '<pre>';
print_r($finalNav);
echo '</pre>';
*/
				
		foreach ( $finalNav as $k => $v ) {
			if (isset($v ['id_feature'])) {
				$isActifChecked = @in_array($v ['id_feature'], $featuressave);
			}
			if (isset($v ['id_attribute_group'])) {
				$isActifChecked = @in_array($v ['id_attribute_group'], $attributessave);
			} elseif ($v ['typeid'] == 'priceRange') {
				$isActifChecked = @$priceRange;
			} elseif ($v ['typeid'] == 'manufacturer') {
				$isActifChecked = @$manufacturer;
			} elseif ($v ['typeid'] == 'category') {
				$isActifChecked = @$category;
			} 
// марки (supplier) содержатся в $products
			elseif ($v ['typeid'] == 'supplier') {
				$isActifChecked = @$supplier;
			}
			

			
			
			
			
			$isRangeable 		= preg_match('#caract_|attr_|priceRange#',$v ['typeid'])?1:0;
			$isMultiCritere 	= (isset($SelectMulti [$v ['typeid']]));
			$curMaxVisible 		= (int)@$maxVisible [$v ['typeid']];
			$curRange			= @$range [$v ['typeid']];
			$curLabelRange		= @$labelRange [$v ['typeid']];
			$curWidthHookTop	= @$widthHookTop [$v ['typeid']];
			$output .= '<tr id="' . $v ['typeid'] . '" ' . (@$isActifChecked ? '' : 'class="notfromsearch"') . '><td>' . $v ['name'] . '</td>
			<td align="center" id="check_block"><input type="checkbox" name="' . $v ['typeid'] . '" id="ADVC_actif_' . $v ['typeid'] . '" value="1" onclick="disablefromsearch(this)" ' . (@$isActifChecked ? 'checked="checked"' : '') . '></td>
			<td align="center" id="check_multi"><input type="checkbox" id="ADVC_multiple_' . $k . '" name="' . $v ['typeid'] . '" value="1" onclick="setMulticriteres(this);" ' . (@$isMultiCritere ? 'checked="checked"' : '') . '  ' . (@! $isActifChecked || @$searchTypes [$v ['typeid']] == 2 || @$searchTypes [$v ['typeid']] == 3 || $v ['typeid'] == 'priceRange' ? 'disabled="disabled"' : '') . ' /></td>
			<td align="center"  id="check_style">
			<select name="' . $v ['typeid'] . '" id="ADVC_searchtype_' . $k . '" onchange="setSearchType(this,'.$isRangeable.');" ' . (@! $isActifChecked ? 'disabled="disabled"' : '') . '>';
			foreach($this->advcCritStyle as $k2 => $v2) {
				$output .= '<option value="'.intval($k2).'" ' . (@$searchTypes [$v ['typeid']] == $k2 ? 'selected="selected"' : '') . '>' . $v2['title'] . '</option>';
			}

			$output .= '</select></td>
			<td align="center" id="check_max_visible"><input type="text" name="' . $v ['typeid'] . '" value="' . $curMaxVisible . '" id="ADVC_maxvisible_' . $k . '" onchange="setMaxVisible(this);" style="width:50px;" ' . (@! $isActifChecked|| @$searchTypes [$v ['typeid']] == 3 ? 'disabled="disabled"' : '') . ' /></td>
			<td align="center" id="check_range"><input type="text" name="' . $v ['typeid'] . '" value="' . $curRange . '" id="ADVC_range_' . $k . '" onchange="setRange(this);" style="width:110px;" ' . (@! $isActifChecked|| @$searchTypes [$v ['typeid']] == 3 || !$isRangeable ? 'disabled="disabled"' : '') . ' /></td>
			<td align="center" id="check_label_range"><input type="text" name="' . $v ['typeid'] . '" value="' . $curLabelRange . '" id="ADVC_label_range_' . $k . '" onchange="setLabelRange(this);" style="width:90px;" ' . (@! $isActifChecked|| @$searchTypes [$v ['typeid']] == 3 || !$isRangeable ? 'disabled="disabled"' : '') . ' /></td>
			<td align="center" id="width_hook_top"><input type="text" name="' . $v ['typeid'] . '" value="' . $curWidthHookTop . '" id="ADVC_width_hook_top_' . $k . '" onchange="setWidthHookTop(this);" style="width:90px;" ' . (@! $isRegisteredInHookTop ? 'disabled="disabled"' : '') . ' /></td>
			</tr>';
		}
		$output .= '</tbody></table></fieldset>';
		//Fonction JS pour le drag and drop
		$output .= '
					<script type="text/javascript" src="' . $this->_path . '/js/jquery.tablednd_0_5.js"></script>
					<script type="text/javascript" src="' . $this->_path . '/js/advancesearchadmin.js"></script>

					<script type="text/javascript">
		var critStyles = new Array();';
		foreach($this->advcCritStyle as $k2 => $v2) {
			$output .= 'critStyles['.intval($k2).'] = {multicritere: '.$v2['multicritere'].',hidable: '.$v2['hidable'].',rangeable: '.$v2['rangeable'].'};';
		}
		$output .='
	</script>';
		return $output;
	}
	function getAllCategory() {
		global $cookie;
		$result = Db::getInstance()->ExecuteS('
		SELECT c.`id_category`, cl.`name`, cl.`link_rewrite`
		FROM `' . _DB_PREFIX_ . 'category` c
		LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON c.`id_category` = cl.`id_category`
		WHERE `id_lang` = ' . intval($cookie->id_lang) . ' AND c.`id_category` != 1
		AND `active` = 1
		ORDER BY `name` ASC');

		/* Modify SQL result */
		$resultsArray = array ();
		foreach ( $result as $row ) {
			$row ['name'] = Category::hideCategoryPosition($row ['name']);
			$resultsArray [] = $row;
		}
		return $resultsArray;
	}
	
	
	function recurseCategoryForInclude($indexedCategories, $categories, $current, $id_category = 1, $id_category_default = NULL, &$output = '') {
		global $done;
		static $irow;
		$id_obj = intval(Tools::getValue($this->identifier));

		if (! isset($done [$current ['infos'] ['id_parent']]))
			$done [$current ['infos'] ['id_parent']] = 0;
		$done [$current ['infos'] ['id_parent']] += 1;

		$todo = sizeof($categories [$current ['infos'] ['id_parent']]);
		$doneC = $done [$current ['infos'] ['id_parent']];

		$level = $current ['infos'] ['level_depth'] + 1;
		$img = $level == 1 ? 'lv1.gif' : 'lv' . $level . '_' . ($todo == $doneC ? 'f' : 'b') . '.gif';

		$output .= '
		<tr class="' . ($irow ++ % 2 ? 'alt_row' : '') . '">
			<td>
				<input type="checkbox" name="categoryBox[]" class="categoryBox' . ($id_category_default != NULL ? ' id_category_default' : '') . '" id="categoryBox_' . $id_category . '" value="' . $id_category . '"' . ((in_array($id_category, $indexedCategories) or (intval(Tools::getValue('id_category')) == $id_category and ! intval($id_obj))) ? ' checked="checked"' : '') . ' />
			</td>
			<td>
				' . $id_category . '
			</td>
			<td>
				<img src="../img/admin/' . $img . '" alt="" /> &nbsp;<label for="categoryBox_' . $id_category . '" class="t">' . stripslashes(Category::hideCategoryPosition($current ['infos'] ['name'])) . '</label>
			</td>
		</tr>';
		

		if (isset($categories [$id_category]))
			foreach ( $categories [$id_category] as $key => $row )
				if ($key != 'infos')
					$this->recurseCategoryForInclude($indexedCategories, $categories, $categories [$id_category] [$key], $key, NULL, $output);

	}
	public function getUrlWithMultipleSelect($key, $val, $multiple) {
		$n = 0;
		$valExist = false;
		$ignor_var = array ('iso_lang', 'submitAdvancedSearch', 'id_category', 'id_product', 'id_manufacturer', 'id_supplier','p','advc_ajax_mode','advc_get_block');
		$url = $this->_path . 'advancesearch.php';
		foreach ( $_GET as $k => $value ) {
			if (in_array($k, $ignor_var)) {
				if ($k == 'id_category' && Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id)) {
					$k = 'category';
				}elseif ($k == 'id_manufacturer' && Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id)) {
					$k = 'manufacturer';
				}elseif ($k == 'id_supplier' && Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id)) {
					$k = 'supplier';
				} else
					continue;
			}
			$isSelect = $this->isSelected($value, $val);
			$curSelect = ((! $n ++) ? '?' : '&amp;');
			if ($k == $key && $isSelect) {

				$curSelectClear = $this->clearSelecte($value, $key, $val);
				if ($curSelectClear == '') {
					$n --;
					$curSelect = substr($curSelect, 0, - 1);
				}
				$curSelect .= $curSelectClear;
			} else {
				$curSelect .= ($value ? urlencode($k) . '=' . urlencode((! $isSelect && ! $multiple && $k == $key ? $val : $value)) : '');
			}
			if (! is_array($value) and $k == $key and Tools::isSubmit($k) && ! $isSelect) {
				if ($multiple)
					$curSelect .= $this->multi_separator . urlencode($val);
			}
			if ($k == $key) $valExist = true;
			$url .= $curSelect;
		}

		return $url . (! $valExist ? ($n ? '&amp;' : '?') . urlencode($key) . '=' . urlencode($val) : '');
	}
	/*public function check_final_version() {
		if(@file_get_contents('http://www.presta-module.com/modules_versions/advancesearch.txt') != $this->version)
			echo '<div class="warning warn"><h3>' . $this->l('Votre version du module n\'est pas à jour! Vous pouvez la demander sur')  . ' <a href="http://www.presta-module.com" target="_blank">http://www.presta-module.com</a></h3></div>';
		return true;
	}*/
	public function clearSelecte($curSelect, $key, $val) {
		$b = explode($this->multi_separator, $curSelect);
		$c = array ();
		foreach ( $b as $v ) {
			if ($val != $v)
				$c [] = $v;
		}
		return (count($c) ? $key . '=' . implode($this->multi_separator, $c) : '');
	}
	public function getSizeTagCloud($total,$val) {
		//if(!$this->)
		//Baisser cette variable aura pour effet de mieux percevoir les différences de tailles
		if(!$this->tags_dif) {
			$tags_dif = Configuration::get('ADVC_TAGS_DIF_'.$this->dupliq_id);
			$this->tags_dif = ($tags_dif?$tags_dif:$this->default_tags_dif);
		}
		//die('dfg'.$total);
		$initSize = 10;
		$percent = ($val*100)/$total;

		$indice = ($percent/(10-$this->tags_dif));
		$size = $initSize+$indice;
		if($size > $this->tags_max_size)
			$size = $this->tags_max_size;
		return $size;
	}
	public function isSelected($curSelect, $val) {
		$aSelect = explode($this->multi_separator, $curSelect);
		foreach ( $aSelect as $v ) {
			if ($v == $val) {
				return true;
			}
			continue;
		}
		return false;
	}
	public function fetchWithCache($file, $template, $cacheid = NULL, $cache_lifetime = 0) {
		global $smarty;
		$previousTemplate = $smarty->currentTemplate;
		$smarty->currentTemplate = substr(basename($template), 0, - 4);
		$smarty->assign('module_dir', __PS_BASE_URI__ . 'modules/' . basename($file, '.php') . '/');
		$smarty->cache_lifetime = $cache_lifetime;
		if (file_exists(_PS_THEME_DIR_ . 'modules/' . basename($file, '.php') . '/' . $template)) {
			$smarty->assign('module_template_dir', _THEME_DIR_ . 'modules/' . basename($file, '.php') . '/');
			$result = $smarty->fetch(_PS_THEME_DIR_ . 'modules/' . basename($file, '.php') . '/' . $template, $cacheid);
		} elseif (file_exists(dirname($file) . '/' . $template)) {
			$smarty->assign('module_template_dir', __PS_BASE_URI__ . 'modules/' . basename($file, '.php') . '/');
			$result = $smarty->fetch(dirname($file) . '/' . $template, $cacheid);
		} else
			$result = '';
		$smarty->currentTemplate = $previousTemplate;
		return $result;
	}
	function getSearchResult() {
		global $smarty, $cookie;
		$advcCache = Configuration::get('ADVC_CACHE_'.$this->dupliq_id);
		if ($advcCache) {
			$smarty->force_compile	= false;
			$smarty->compile_check	= false;
			$smarty->caching = 2;
			$sCacheId = (isset($_SERVER ['QUERY_STRING'])) ? $_SERVER ['PHP_SELF'] . '?' . $_SERVER ['QUERY_STRING'] : $_SERVER ['PHP_SELF'];
			$sCacheId = str_replace('&', '|', $sCacheId);
			$advcCacheId = sprintf('ADVCSearch_'.$this->dupliq_id.'|searchresult|%d|%d|%s', $cookie->id_lang,$cookie->id_currency, $sCacheId);
		}
		if (! $advcCache || (!$smarty->is_cached(dirname(__FILE__).'/advancesearch.tpl', $advcCacheId))) {
			$ids_feature = array ();
			$ids_attribute = array ();
			$gtPrice = false;
			$ltPrice = false;
			$on_stock = @Configuration::get('ADVC_ONLY_ON_STOCK_'.$this->dupliq_id);
			foreach ( $_GET as $k => $v ) {
				if (! $v)
					continue;
				//Convert array from get if submitMode eq 2
				if(is_array($_GET[$k])) $_GET[$k] = $v = implode('|',$_GET[$k]);
				$info = explode('|', $k);
				$info2 = explode('_', $info [0]);
				$id = $v;
				$type = $info2 [0];
				switch ($type) {
					//recherche caracteristiques
					case 'caract' :
						$ids_feature [$k] = $id;
						break;
					case 'attr' :
						$ids_attribute [$k] = $id;
						break;
					case 'priceRange' :
						$price = explode(',', $id);
						if (trim($price [0]))
							$gtPrice = $price [0];
						if (trim($price [1]))
							$ltPrice = $price [1];
						break;
				}
			}

			//Ajustement filtre en fonction de la page courante
			if(Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id)) {
				$id_category = (isset($_GET ['category']) && $_GET ['category'] ? $_GET ['category'] : (isset($_GET ['id_category']) ? intval(Tools::getValue('id_category')) : false));
				$id_manufacturer = (isset($_GET ['manufacturer']) && $_GET ['manufacturer'] ? $_GET ['manufacturer'] : (isset($_GET ['id_manufacturer']) ? intval(Tools::getValue('id_manufacturer')) : false));
//добавим везде "supplier"
				$id_supplier = (isset($_GET ['supplier']) && $_GET ['supplier'] ? $_GET ['supplier'] : (isset($_GET ['id_supplier']) ? intval(Tools::getValue('id_supplier')) : false));
//ali
$filter_name = (isset($_GET ['filter']) && $_GET ['filter'] ? $_GET ['filter'] : false);				
			}else {
				$id_category = (isset($_GET ['category']) && $_GET ['category'] ? $_GET ['category'] : false);
				$id_manufacturer = (isset($_GET ['manufacturer']) && $_GET ['manufacturer'] ? $_GET ['manufacturer'] : false);
				$id_supplier = (isset($_GET ['supplier']) && $_GET ['supplier'] ? $_GET ['supplier'] : false);
//ali
$filter_name = (isset($_GET ['filter']) && $_GET ['filter'] ? $_GET ['filter'] : false);
				
			}
			/*$is_new = ($_SERVER['SCRIPT_NAME'] == __PS_BASE_URI__.'new-products.php'?true:false);
			$is_promo = ($_SERVER['SCRIPT_NAME'] == __PS_BASE_URI__.'prices-drop.php'?true:false);*/
			$is_new = false;
			$is_promo = false;

$filter_name = Search::sanitize(strip_tags($filter_name), $id_lang);
$filter_alias = '';
foreach (explode(' ',$filter_name) as $cfilter)
		  	   {
				$alias = new Alias(NULL, $cfilter);
				if ($alias->search) $cfilter = $alias->search;
				$filter_alias .= $cfilter.' ';
		  	   //$aSelection []= array($cfilter,$cfilter,'По названию','filter','1');
		  	   }

			$nbProducts = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, (count($ids_feature) ? $ids_feature : false), (count($ids_attribute) ? $ids_attribute : false), $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock,false,trim($filter_alias));
			include (dirname(__FILE__) . '/../../pagination.php');
			include (dirname(__FILE__) . '/../../product-sort.php');
			
			$products = $this->search(intval($cookie->id_lang), intval($p), intval($n), $orderBy, $orderWay, false, true, (count($ids_feature) ? $ids_feature : false), (count($ids_attribute) ? $ids_attribute : false), $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock,Configuration::get('ADVC_COMB_IMG_'.$this->dupliq_id),trim($filter_alias));

// выводится во фронт-офис!
//echo '<!---super---';
//print_r($products);
//echo '--->'; 


$rand_product = array(
"глушитель", "платформы", "дуги", "задние дуги", "багажник", "спинка", "зеркала", "крыло", "боковые крышки", "люстра", "накладка", "сиденье", "стекло", "рычаги", "прикуриватель", "решетка радиатора", "бугель", "пульт", "воздушный фильтр", "тахометр", "выносы", "ручки", "поворотник", "хомуты", "колонки", "часы"
);

/*
$rand_product = array(
"глушитель VTX 1300", "платформы Road Star", "дуги Aero 750", "задние дуги Drag Star Classic", "багажник Intruder 1500", "спинка Intruder 800", "зеркала", "крыло VTX 1800", "крышки Spirit 1100", "люстра Boulevard M50", "накладка Stratoliner", "сиденье Road King", "стекло Volusia", "рычаги", "прикуриватель", "решетка радиатора", "бугель Vulcan 900 Custom", "пульт", "фильтр", "тахометр", "вынос Shadow ACE", "ручки", "поворотник", "хомуты", "колонки", "часы"
);
*/

/*echo '<pre>';
print_r($_GET);
echo '</pre>';*/

	// назначим \ для подсказок в зависимости:
	// ...от категории
if ($_GET ['category'])
{
	$cat = explode("|",$_GET ['category']);
	$cat = array_pop($cat);

	$cat = new Category($cat);
	$tips = explode(",", $cat->meta_keywords[$this->dupliq_id]);
	
	// передадим почти рандомный placeholder
	switch ($cat)
	{
		case ($cat->id_category == 5) : $placeholder = ""; break;
		case ($cat->id_category == 8) : $placeholder = ""; break;	
//		default: $placeholder = "универсальн кожа пластик";
	}
}

//echo '<pre>';
//print_r($cat);
//echo '</pre>';

	// ...от марки
if ($_GET ['supplier'])
{
	$supp = explode("|",$_GET ['supplier']);
	$supp = array_pop($supp);

	$supp = new Supplier($supp);
	$tips = explode(",", $supp->meta_keywords[$this->dupliq_id]);

//echo '<!--JOPA'.$tips.'>';
	
	// передадим почти рандомный placeholder
	switch ($supp)
	{
		case ($supp->id == 5) : $placeholder = trim($rand_product[array_rand($rand_product)]).' '.trim($tips[array_rand($tips)]); break;
		case ($supp->id == 8) : $placeholder = trim($rand_product[array_rand($rand_product)]).' '.trim($tips[array_rand($tips)]); break;
		case ($supp->id == 9) : $placeholder = trim($rand_product[array_rand($rand_product)]).' '.trim($tips[array_rand($tips)]); break;
		case ($supp->id == 10) : $placeholder = trim($rand_product[array_rand($rand_product)]).' '.trim($tips[array_rand($tips)]); break;
		case ($supp->id == 11) : $placeholder = trim($rand_product[array_rand($rand_product)]).' '.trim($tips[array_rand($tips)]); break;
		case ($supp->id == 12) : $placeholder = trim($rand_product[array_rand($rand_product)]); break;
		case ($supp->id == 15) : $placeholder = "защита картера"; break;
		case ($supp->id == 16) : $placeholder = trim($rand_product[array_rand($rand_product)]).' '.trim($tips[array_rand($tips)]); break;
		
//		default: $placeholder = "спинка багажник shadow ace";
	}
}

			$smarty->assign(array (
			'oAdvaceSearch' => $this,
			'products' => $products,
			'filter'=>$filter_name,
			'nbProducts' => $nbProducts,
			'static_token' => Tools::getToken(false),'duliq_id' => $this->dupliq_id,
			'this_path' => $this->_path,
			'tips' => $tips,
			'full_ajax' => @Configuration::get('ADVC_FULL_AJAX_'.$this->dupliq_id),
			'placeholder' => $placeholder
			));
		}
		if ($advcCache) {
			$smarty->caching = 2;
			$return = $this->fetchWithCache(__FILE__, 'advancesearch.tpl', $advcCacheId, $this->advc_cache_lifetime);
			$smarty->caching = 0;
			return $return;
		} else
			return $this->display(__FILE__, 'advancesearch.tpl');

	}
	public function getFeatureValuesWithLang($id_lang, $id_feature)
	{
		return Db::getInstance()->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'feature_value` v
		LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` vl ON (v.`id_feature_value` = vl.`id_feature_value` AND vl.`id_lang` = '.intval($id_lang).')
		WHERE v.`id_feature` = '.intval($id_feature).'
		GROUP BY vl.`value`
		ORDER BY vl.`value` ASC');
	}
	public function getCurrent()
	{
		global $cookie;

		if (!self::$current)
		{
			if (isset($cookie->id_currency) AND $cookie->id_currency)
				self::$current = new Currency(intval($cookie->id_currency));
			else
				self::$current = new Currency(intval(Configuration::get('PS_CURRENCY_DEFAULT_'.$this->dupliq_id)));
		}
		return self::$current;
	}
	function getDataBlock($keep_empty_block = true,$keep_empty_result =  false) {
		global $smarty, $cookie;
		$advancedsearch_value = array ();
		$advancedsearch_label = array ();
		$advancedsearch_total = array ();
		$order 				= @unserialize(Configuration::get('ADVC_ORDER_'.$this->dupliq_id));
		$full_ajax 			= @Configuration::get('ADVC_FULL_AJAX_'.$this->dupliq_id);
		$SelectMulti 		= @unserialize(Configuration::get('ADVC_MULTICRIT_'.$this->dupliq_id));
		$maxVisible 		= @unserialize(Configuration::get('ADVC_MAXVISIBLE_'.$this->dupliq_id));
		$searchTypes 		= @unserialize(Configuration::get('ADVC_SEARCH_TYPE_'.$this->dupliq_id));
		$on_stock 			= @Configuration::get('ADVC_ONLY_ON_STOCK_'.$this->dupliq_id);
		$filterCat 			= @Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id);
		$stepMode 			= @Configuration::get('ADVC_STEP_MODE_'.$this->dupliq_id);
		$showSelection 		= @Configuration::get('ADVC_SHOW_SELECTION_'.$this->dupliq_id);
		$range				= @unserialize(Configuration::get('ADVC_RANGE_'.$this->dupliq_id));
		$labelRange			= @unserialize(Configuration::get('ADVC_LABEL_RANGE_'.$this->dupliq_id));
		$displayNbProduct 		= Configuration::get('ADVC_DISPLAY_NB_PRODUCT_'.$this->dupliq_id);
		$submitMode = @Configuration::get('ADVC_SUBMIT_MODE_'.$this->dupliq_id);
		if($stepMode){ $nextStepValue = true;$keep_empty_block = true; }

$order['filter']='1';
		
				
		//$_GET = array_walk($_GET,'delamp');
		//$dd = array_map('htmlspecialchars', $dd);
		//$dd = array_map('strip_tags', $dd);
		//echo "<pre>jopa3=";
		//var_dump($_GET);
		//print_r($_GET);
		//exit;
//echo "<pre>";print_r($order);
		$gtPrice = $ltPrice = false;
		$aSelection = array();
		if (! count($order) or ! $order)
			return false;
			//On ignore des caracteristiques quand filtre choisi
		$ids_feature = array ();
		$ids_feature_value = array ();

		$ids_attrubute = array ();
		$ids_attrubute_value = array ();
		//Total produits dispo
		$total = 0;

foreach ( $_GET as $k => $v ) 
		{
			if (strpos($k, 'amp;')!==false)
			{
				$_GET['filter']=$v;
				unset($_GET[$k]);
			}

			
		}


		foreach ( $_GET as $k => $v ) {
			if (! $v) {
				continue;
			}
			//Convert array from get if submitMode eq 2
			if($submitMode == 2 && is_array($_GET[$k])) $_GET[$k] = $v =  implode('|',$_GET[$k]);
			//recherche caracteristiques
			if (preg_match('#^caract_([0-9]+)#', $k, $pat)) {
				$ids_feature [$k] = $pat [1];
				$ids_feature_value [$k] = $v;

			} //recherche attributs
			elseif (preg_match('#^attr_([0-9]+)#', $k, $pat)) {
				$ids_attrubute [$k] = $pat [1];
				$ids_attrubute_value [$k] = $v;
			} //Par tranche de prix
			elseif (preg_match('#^priceRange#', $k)) {
				$price = explode(',', $v);
				if (trim($price [0]))
					$gtPrice = $price [0];
				if (trim($price [1]))
					$ltPrice = $price [1];
			}
		}

		//Ajustement filtre en fonction de la page courante
		if(Configuration::get('ADVC_FILTER_CAT_'.$this->dupliq_id)) {
			$id_category = (isset($_GET ['category']) && $_GET ['category'] ? $_GET ['category'] : (isset($_GET ['id_category']) ? intval(Tools::getValue('id_category')) : false));
			$id_manufacturer = (isset($_GET ['manufacturer']) && $_GET ['manufacturer'] ? $_GET ['manufacturer'] : (isset($_GET ['id_manufacturer']) ? intval(Tools::getValue('id_manufacturer')) : false));
			$id_supplier = (isset($_GET ['supplier']) && $_GET ['supplier'] ? $_GET ['supplier'] : (isset($_GET ['id_supplier']) ? intval(Tools::getValue('id_supplier')) : false));

		}else {
			$id_category = (isset($_GET ['category']) && $_GET ['category'] ? $_GET ['category'] : false);
			$id_manufacturer = (isset($_GET ['manufacturer']) && $_GET ['manufacturer'] ? $_GET ['manufacturer'] : false);
			$id_supplier = (isset($_GET ['supplier']) && $_GET ['supplier'] ? $_GET ['supplier'] : false);
		}
		/*$is_new = ($_SERVER['SCRIPT_NAME'] == __PS_BASE_URI__.'new-products.php'?true:false);
		$is_promo = ($_SERVER['SCRIPT_NAME'] == __PS_BASE_URI__.'prices-drop.php'?true:false);*/
		$is_new = false;
		$is_promo = false;

		$i = 0;
		foreach ( @$order as $k => $v ) {
			$totalGroup = 0;
			//echo "<pre>";print_r($k);
			//if (preg_match('#^filter#', $k))
			//echo "jopa!!!!";
			//Par Caractéristiques
			if (preg_match('#^caract_[0-9]#', $k)) {
				$feat = Db::getInstance()->getRow('
					SELECT SQL_CACHE CONCAT(f.`id_feature`,"") AS `typeid`, f.`id_feature`, fl.`name`
					FROM `' . _DB_PREFIX_ . 'feature` f
					LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` fl ON ( f.`id_feature` = fl.`id_feature` AND fl.`id_lang` = ' . intval($cookie->id_lang) . ')
					WHERE f.`id_feature` = ' . intval($v));
					$id_feature = $feat ['id_feature'];
				if(!$stepMode || ($stepMode && $nextStepValue)) {
					//Déclaration pour affichage même vide (disabled)
					if ((isset($searchTypes[$k]) && $searchTypes [$k] == 3) || $keep_empty_block) {
						$advancedsearch_value ['caract_' . $id_feature] = array ();
					}
					if (isset($searchTypes[$k]) && $searchTypes [$k] != 3) {
						if(!$range[$k]) {
							//Récuperation des valeurs de recherche possible
							$feature_values = $this->getFeatureValuesWithLang($cookie->id_lang, $id_feature);
							foreach ( $feature_values as $v ) {
								$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $v ['id_feature_value']) : false);
								//FeatureValue Ajout uniquement si produits disponible
								$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, array_merge($ids_feature_value, array ('caract_' . $id_feature => ($v['custom']?$v['value']:$v['id_feature_value']) )), $ids_attrubute_value, $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock);

								if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
									continue;
									//ON ajoute
								$advancedsearch_value ['caract_' . $id_feature] [] = array (($v['custom']?$v['value']:$v['id_feature_value']), $v ['value'], $nbProduct, 'caract_' . $id_feature );
								$total += $nbProduct;
								$totalGroup += $nbProduct;
							}
						}
						else {
							$aRange = explode(',',$range[$k]);
							foreach ( $aRange as $krange => $vrange ) {
								$searchOn = $vrange.(isset($aRange[$krange+1])?','.$aRange[$krange+1]:'');
								$searchOnArray = array();
								$searchOnArray ['caract_' . $id_feature] = $searchOn;
								$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $searchOn) : false);
								//FeatureValue Ajout uniquement si produits disponible
								$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $searchOnArray, $ids_attrubute_value, $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock);

								if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
									continue;
									//ON ajoute
								$advancedsearch_value ['caract_' . $id_feature] [] = array ($searchOn, ((count($aRange) == $krange + 1) ? $this->l('Plus de') . ' ' . $vrange.(isset($labelRange[$k]) && $labelRange[$k]?' '.$this->l($labelRange[$k]):'') : $this->l('Entre') . ' ' . $vrange .(isset($labelRange[$k]) && $labelRange[$k]?' '.$this->l($labelRange[$k]):''). ' ' . $this->l('et') . ' ' . $aRange [$krange + 1].(isset($labelRange[$k]) && $labelRange[$k]?' '.$this->l($labelRange[$k]):'')), $nbProduct, 'caract_' . $id_feature );
								$total += $nbProduct;
								$totalGroup += $nbProduct;
							}
						}
					} elseif (isset($_GET [$k])) {
						$nextStepValue = false;
						$advancedsearch_value ['caract_' . $id_feature] [] = array (0, $_GET [$k], 0 );
					}
				}
				if (($stepMode && !$nextStepValue) || ((isset($searchTypes[$k]) && @$searchTypes [$k] == 3) || @count($advancedsearch_value ['caract_' . $id_feature]) || ($keep_empty_block))) {
					$advancedsearch_label ['caract_' . $id_feature] = $feat;
				}
			}
			//Par Attributs
			if (preg_match('#^attr_[0-9]#', $k)) {
				$attr = Db::getInstance()->getRow('
				SELECT SQL_CACHE CONCAT("attr_",ag.`id_attribute_group`) AS `typeid`, agl.`name`, ag.`id_attribute_group`, ag.`is_color_group`
				FROM `' . _DB_PREFIX_ . 'attribute_group` ag
				LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND `id_lang` = ' . intval($cookie->id_lang) . ')
				WHERE ag.`id_attribute_group` = ' . intval($v));
				$id_attribute_group = $attr ['id_attribute_group'];
				if(!$stepMode || ($stepMode && $nextStepValue)) {
					//Déclaration pour affichage même vide (disabled)
					if ((isset($searchTypes[$k]) && @$searchTypes [$k] == 3) || $keep_empty_block) {
						$advancedsearch_value ['attr_' . $id_attribute_group] = array ();
					}
					if (@$searchTypes [$k] != 3) {
						if(!$range[$k]) {
							$attribut_values = AttributeGroup::getAttributes($cookie->id_lang, $id_attribute_group);
							//Récuperation des valeurs de recherche possible
							foreach ( $attribut_values as $v ) {
								$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $v ['id_attribute']) : false);
								//FeatureValue Ajout uniquement si produits disponible
								$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, array_merge($ids_attrubute_value, array ('attr_' . $id_attribute_group => $v ['id_attribute'] )), $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock);
								if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
									continue;
									//ON ajoute
								$advancedsearch_value ['attr_' . $id_attribute_group] [] = array ($v ['id_attribute'], $v ['name'], $nbProduct, 'attr_' . $id_attribute_group, 'is_color' => $attr ['is_color_group'], 'color' => $v ['color'] );
								$total += $nbProduct;
								$totalGroup += $nbProduct;
							}
						}
						else {
							$aRange = explode(',',$range[$k]);
							foreach ( $aRange as $krange => $vrange ) {
								$searchOn = $vrange.(isset($aRange[$krange+1])?','.$aRange[$krange+1]:'');
								$searchOnArray = array();
								$searchOnArray ['attr_' . $id_attribute_group] = $searchOn;
								$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $searchOn) : false);
								//FeatureValue Ajout uniquement si produits disponible
								$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, $searchOnArray, $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock);

								if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
									continue;
									//ON ajoute
								$advancedsearch_value ['attr_' . $id_attribute_group] [] = array ($searchOn, ((count($aRange) == $krange + 1) ? $this->l('Plus de') . ' ' . $vrange.(isset($labelRange[$k]) && $labelRange[$k]?' '.$this->l($labelRange[$k]):'') : $this->l('Entre') . ' ' . $vrange.(isset($labelRange[$k]) && $labelRange[$k]?' '.$this->l($labelRange[$k]):'') . ' ' . $this->l('et') . ' ' . $aRange [$krange + 1].(isset($labelRange[$k]) && $labelRange[$k]?' '.$this->l($labelRange[$k]):'')), $nbProduct, 'attr_' . $id_attribute_group, 'is_color' => $attr ['is_color_group'], 'color' => $v ['color'] );
								$total += $nbProduct;
								$totalGroup += $nbProduct;
							}
						}
					} elseif (@isset($_GET [$k])) {
						$advancedsearch_value ['attr_' . $id_attribute_group] [] = array (0, $_GET [$k], 0 );
					}
				}
				if (($stepMode && !$nextStepValue) || (@$searchTypes [$k] == 3 || count(@$advancedsearch_value ['attr_' . $id_attribute_group]) || $keep_empty_block)) {
					$advancedsearch_label ['attr_' . $id_attribute_group] = $attr;
				}
			} //Par tranche de prix
			elseif (preg_match('#^priceRange#', $k)) {
				//Get current currency
				$currency = $this->getCurrent();
				/* if you modified this function, don't forget to modify the Javascript function formatCurrency (in tools.js) */
				if (is_int($currency))
					$currency = new Currency(intval($currency));
				$c_char = (is_array($currency) ? $currency['sign'] : $currency->sign);
				$priceRange = explode(',',$range[$k]);
				if(!$stepMode || ($stepMode && $nextStepValue)) {
					//Déclaration pour affichage même vide (disabled)
					if ($keep_empty_block) {
						$advancedsearch_value ['priceRange'] = array ();
					}
					foreach ( $priceRange as $k2 => $v2 ) {
						$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $v2 . ',' . @$priceRange [$k2 + 1]) : false);
						$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, $ids_attrubute_value, $v2, (count($priceRange) == $k2 + 1) ? false : $priceRange [$k2 + 1], $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock);
						if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
							continue;
						$advancedsearch_value ['priceRange'] [] = array ($v2 . ',' . @$priceRange [$k2 + 1], ((count($priceRange) == $k2 + 1) ? $this->l('Plus de') . ' ' . $v2.' '.$c_char : $this->l('Entre') . ' ' . $v2 . ' ' . $this->l('et') . ' ' . $priceRange [$k2 + 1].' '.$c_char), $nbProduct );
						$total += $nbProduct;
						$totalGroup += $nbProduct;
					}
				}
				if (($stepMode && !$nextStepValue) || (@count($advancedsearch_value ['priceRange']) || $keep_empty_block))
					$advancedsearch_label ['priceRange'] = array ('name' => $this->l('Par Tranche de prix') );
			} //Par manufacturer
			elseif (preg_match('#^manufacturer#', $k)) {
				$aManufacturer = Manufacturer::getManufacturers();
				if(!$stepMode || ($stepMode && $nextStepValue)) {
					//Déclaration pour affichage même vide (disabled)
					if ((isset($searchTypes[$k]) && $searchTypes [$k] == 3) || $keep_empty_block) {
						$advancedsearch_value ['manufacturer'] = array ();
					}
					if (isset($searchTypes[$k]) && $searchTypes [$k] != 3) {
						foreach ( $aManufacturer as $k2 => $v2 ) {
							$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $v2 ['id_manufacturer']) : false);
							$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, $ids_attrubute_value, $gtPrice, $ltPrice, $id_category, $v2['id_manufacturer'], $v2['id_supplier'], $is_new, $is_promo,$on_stock);
							if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
								continue;
								//ON ajoute
							$advancedsearch_value ['manufacturer'] [] = array ($v2 ['id_manufacturer'], $v2 ['name'], $nbProduct );
							$total += $nbProduct;
							$totalGroup += $nbProduct;
						}
					} elseif (isset($_GET [$k])) {
						$advancedsearch_value ['manufacturer'] [] = array (0, $_GET [$k], 0 );
					}
				}
				if (($stepMode && !$nextStepValue) || ($searchTypes [$k] == 3 || @count($advancedsearch_value ['manufacturer']) || $keep_empty_block))
					$advancedsearch_label ['manufacturer'] = array ('name' => $this->l('Par fabriquant') );
			
			
		
			
			} 
	
		elseif (preg_match('#^supplier#', $k)) {
				$aSupplier = Supplier::getSuppliers();
				if(!$stepMode || ($stepMode && $nextStepValue)) {
					//Déclaration pour affichage même vide (disabled)
					if ((isset($searchTypes[$k]) && $searchTypes [$k] == 3) || $keep_empty_block) {
						$advancedsearch_value ['supplier'] = array ();
					}
					if (isset($searchTypes[$k]) && $searchTypes [$k] != 3) {
						foreach ( $aSupplier as $k2 => $v2 ) {
							$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $v2 ['id_supplier']) : false);
							$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, $ids_attrubute_value, $gtPrice, $ltPrice, $id_category, $v2 ['id_manufacturer'], $v2 ['id_supplier'], $is_new, $is_promo,$on_stock);
							if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
								continue;
								//ON ajoute
							$advancedsearch_value ['supplier'] [] = array ($v2 ['id_supplier'], $v2 ['name'], $nbProduct );
							$total += $nbProduct;
							$totalGroup += $nbProduct;
						}
					} elseif (isset($_GET [$k])) {
						$advancedsearch_value ['supplier'] [] = array (0, $_GET [$k], 0 );
					}
				}
				if (($stepMode && !$nextStepValue) || ($searchTypes [$k] == 3 || @count($advancedsearch_value ['supplier']) || $keep_empty_block))
					$advancedsearch_label ['supplier'] = array ('name' => $this->l('По маркам') );
/*			
echo '<!--';
print_r ($advancedsearch_value);
echo '-->';			
*/			
			
			} 	

			//Par category
			elseif (preg_match('#^category#', $k)) {
				$aCategory = $this->getAllCategory();
				if(!$stepMode || ($stepMode && $nextStepValue)) {
					//Déclaration pour affichage même vide (disabled)
					if ($keep_empty_block) {
						$advancedsearch_value ['category'] = array ();
					}
					
					
					
					
					
/* а собсно это шо здесь такое закомменчено c PARENT_CATEGORY?
					if ($id_category) {
						$aParentCategory = array ();
						$categ = new Category ( $id_category, intval ( $cookie->id_lang ) );
						$aParentCategory [] = array ('id_category' => $categ->id_category, 'name' => $categ->name );
						$aCategory = array_merge ( $aParentCategory, $aCategory );
					}*/
					
					
					
					
					
					
					
					foreach ( $aCategory as $k2 => $v2 ) {
						$is_select = (isset($_GET [$k]) ? $this->isSelected($_GET [$k], $v2 ['id_category']) : false);
						$nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, $ids_attrubute_value, $gtPrice, $ltPrice, $v2 ['id_category'], $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock);
						if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
							continue;
							//ON ajoute
						$advancedsearch_value['category'] [] = array ($v2 ['id_category'], $v2 ['name'], $nbProduct );
						$total += $nbProduct;
						$totalGroup += $nbProduct;
					}
				}
				if (($stepMode && !$nextStepValue) || (@count($advancedsearch_value ['category']) || $keep_empty_block))
					$advancedsearch_label ['category'] = array ('name' => $this->l('Par Catégories') );
			}
elseif (preg_match('#^filter#', $k)) {
	  if (isset($_GET[$k]))
	  	   {
	  	   
	  	   $filter_alias = Search::sanitize(strip_tags($_GET[$k]), $id_lang);
		  	   foreach (explode(' ',$filter_alias) as $cfilter)
		  	   {
				
				$alias = new Alias(NULL, $cfilter);
				if ($alias->search) $cfilter = $alias->search;

		  	   $aSelection []= array($cfilter,$cfilter,'По названию','filter','1');
		  	   }
			   
			   //print_r($cfilter);
			   $nbProduct = $this->search(intval($cookie->id_lang), NULL, NULL, 'date_add', NULL, true, true, $ids_feature_value, $ids_attrubute_value, $gtPrice, $ltPrice, $id_category, $id_manufacturer, $id_supplier, $is_new, $is_promo,$on_stock,false,$cfilter);
	if (!$keep_empty_result && $nbProduct == 0 && ! $is_select)
								continue;
			   $total += $nbProduct;
			   $totalGroup += $nbProduct;
				//print_r($_GET[$k]);
				//echo "aliec!!!";
			}
			//$advancedsearch_value['filter'] [] = array($k);
			//$advancedsearch_label ['filter'] = array ('name' => $k );
		
			}
			//print_r($advancedsearch_value);
//			echo "<pre>";
//			print_r(@$advancedsearch_value[$k]);
			//Séléction active

//			if(count(@$advancedsearch_value[$k])) // оригинал
			if(is_countable(@$advancedsearch_value[$k])) // оригинал
			{
				foreach ($advancedsearch_value[$k] as $curValue) {
					if($this->isSelected(@$_GET[$k], $curValue[0])) {
						$aSelection[] = array($curValue[0],$curValue[1],@$advancedsearch_label[$k]['name'],$k,@$SelectMulti[$k]);
					}
				}
			}

			if (isset($searchTypes[$k]) && $searchTypes[$k] == 4 && $totalGroup) {
				$advancedsearch_total[$k] = $totalGroup;
			}
			if ($stepMode) {
				/*POUR RECHERCHE PAR ÉTAPE*/
				if((!isset($_GET[$k]) || empty($_GET[$k]))&& $i > 0) {
					$nextStepValue = false;
				}
				elseif ((!isset($_GET[$k]) || empty($_GET[$k]) )&& $i === 0) {
					$nextStepValue = false;
				}
				$i++;
			}
			
			//echo "total=";
			//print_r($advancedsearch_total);echo "|";
		}
		
		
		//print_r($aSelection);

		
		$smarty->assign(
			array(
				'selection' 		=> $aSelection,
				'showCritMethode'	=> Configuration::get('ADVC_SHOWCRIT_METHODE_'.$this->dupliq_id),
				'total'				=> $total,
				'SelectMulti'		=> $SelectMulti,
				'searchTypes'		=> $searchTypes,
				'filterCat'			=> $filterCat,
				'maxVisible'		=> $maxVisible,
				'full_ajax'			=> $full_ajax,
				'stepMode'			=> $stepMode,
				'oAdvaceSearch'		=> $this,
				'showSelection'		=> $showSelection,
				'this_path'			=> $this->_path,
				'col_img_dir'		=> _PS_COL_IMG_DIR_,
				'displayNbProduct'	=> $displayNbProduct,
				'submitMode'		=> $submitMode,
				'advancedsearch_value'=> $advancedsearch_value,
				'advancedsearch_total'=> $advancedsearch_total,
				'advancedsearch_label'=> $advancedsearch_label,
				'duliq_id'			  => $this->dupliq_id
			)
		);
		return true;
	}
	public function isRegisteredInHook($hook)
	{
		if (!$this->id)
			return false;

		$row = Db::getInstance()->getRow('
		SELECT COUNT(hm.`id_hook`) as `is_register`
		FROM `'._DB_PREFIX_.'hook_module` hm
		LEFT JOIN `'._DB_PREFIX_.'hook` h ON (h.`id_hook` = hm.`id_hook`)
		WHERE h.`name` = \''.pSQL($hook).'\'
		AND hm.`id_module` = '.intval($this->id)
		);
		return $row['is_register'];
	}
	function getSearchBlock() {
		global $smarty,$cookie;
		if (Tools::getValue('advc_get_block') && Tools::getValue('dupliq_id')!=$this->dupliq_id) return;
		$advcCache = Configuration::get('ADVC_CACHE_'.$this->dupliq_id);
		$type = ($this->isRegisteredInHook('top')?'-horizontal':'');
		$full_ajax = @Configuration::get('ADVC_FULL_AJAX_'.$this->dupliq_id);
		$showInCat = (Configuration::get('ADVC_ONLY_CAT_'.$this->dupliq_id) ? unserialize(Configuration::get('ADVC_ONLY_CAT_'.$this->dupliq_id)) : false);
		$id_category = ($_SERVER ['SCRIPT_NAME'] == __PS_BASE_URI__ . 'index.php' ? 1 : (isset($_GET ['id_category']) ? $_GET ['id_category'] : false));
		if ($showInCat && ! in_array($id_category, $showInCat) && $_SERVER ['SCRIPT_NAME'] != $this->_path . 'advancesearch.php') {
			$smarty->caching = 0;
			return;
		}
		if($full_ajax != 2 || Tools::getValue('advc_get_block')) {
			if ($advcCache) {
				$smarty->force_compile	= false;
				$smarty->compile_check	= false;
				$smarty->caching = 2;
				$sCacheId = (isset($_SERVER ['QUERY_STRING'])) ? $_SERVER ['PHP_SELF'] . '?' . $_SERVER ['QUERY_STRING'] : $_SERVER ['PHP_SELF'];
				$sCacheId = str_replace(array ('&', '?' ), '|', $sCacheId);
				$advcCacheId = sprintf('ADVCSearch_'.$this->dupliq_id.'|searchblock'.$type.'|%d|%d|%s', $cookie->id_lang,$cookie->id_currency, $sCacheId);
			}

			if (! $advcCache || (! $smarty->is_cached(dirname(__FILE__) . '/blockadvancesearch'.( Tools::getValue('advc_get_block')?'menu':'').$type.'.tpl', $advcCacheId))) {
				if($type == '-horizontal') $widthHookTop = @unserialize(Configuration::get('ADVC_WIDTH_HOOK_TOP_'.$this->dupliq_id));
				if (! $smarty->get_template_vars('duliq_id') || ($smarty->get_template_vars('duliq_id') && $smarty->get_template_vars('duliq_id') != $this->dupliq_id)) {
					$this->getDataBlock(Configuration::get('ADVC_KEEP_EMPTY_BLOCK_'.$this->dupliq_id),Configuration::get('ADVC_KEEP_EMPTY_RESULT_'.$this->dupliq_id));
				}

				$smarty->assign('this_path', $this->_path);
				$smarty->assign('formtpl', dirname(__FILE__) . '/blockadvancesearchmenu'.$type.'.tpl');
				if(isset($widthHookTop))
					$smarty->assign('widthHookTop', $widthHookTop);
			}
			$smarty->assign('duliq_id' , $this->dupliq_id);
			//Mise à jour en ajax
			if (Tools::getValue('advc_get_block')) {
				if ($advcCache) {
					$smarty->caching = 2;
					$return = $this->fetchWithCache(__FILE__, 'blockadvancesearchmenu'.$type.'.tpl', $advcCacheId, $this->advc_cache_lifetime);
					$smarty->caching = 0;
					echo $return;
					die;
				}
				else {
					die($this->display(__FILE__, 'blockadvancesearchmenu'.$type.'.tpl'));
				}
			}
		}
		elseif($full_ajax) {
			$smarty->assign('submitMode', @Configuration::get('ADVC_SUBMIT_MODE_'.$this->dupliq_id));
			$smarty->assign('full_ajax', $full_ajax);
			$smarty->assign('formtpl', dirname(__FILE__) . '/blockadvancesearchmenu'.$type.'.tpl');
			$smarty->assign('this_path', $this->_path);
			$smarty->assign('duliq_id' , $this->dupliq_id);
		}
		//Affichage classique
		if ($advcCache) {
			$return = $this->fetchWithCache(__FILE__, 'blockadvancesearch'.$type.'.tpl', $advcCacheId, $this->advc_cache_lifetime);
			$smarty->caching = 0;
			return $return;
		} else
			return $this->display(__FILE__, 'blockadvancesearch'.$type.'.tpl');
	}

	function search($id_lang, $p, $n, $orderBy = NULL, $orderWay = NULL, $getTotal = false, $active = true, $ids_feature = false, $ids_attributes = false,$gtPrice = false,$ltPrice = false,$id_category = false,$id_manufacturer = false, $id_supplier = false, $is_new = false,$is_promo = false,$on_stock=false,$comb_img = false,$filter_name = NULL )
	{
		global $cookie, $searchTypes, $SelectMulti, $range;
//		echo "in search!".rand()."<br>";
		if(!$searchTypes && !is_array($searchTypes))
			$searchTypes = @unserialize(Configuration::get('ADVC_SEARCH_TYPE_'.$this->dupliq_id));
		if(!$SelectMulti && !is_array($SelectMulti))
			$SelectMulti = @unserialize(Configuration::get('ADVC_MULTICRIT_'.$this->dupliq_id));
		if(!$range && !is_array($range))
			$range = @unserialize(Configuration::get('ADVC_RANGE_'.$this->dupliq_id));
		if ($p < 1) $p = 1;
		if (empty($orderBy))
			$orderBy = 'position';
		if (empty($orderWay))
			$orderWay = 'ASC';
		if ($orderBy == 'id_product' OR	$orderBy == 'price' OR	$orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'manufacturer_name')
		{
			$orderByPrefix = 'm';
			$orderBy = 'name';
		}
		elseif ($orderBy == 'position')
			$orderByPrefix = 'cp';
/*		elseif ($orderBy == 'supplier')
		{
			$orderByPrefix = 'm';
			$orderBy = 'name';
		}
*/		
		if (!Validate::isBool($active) OR !Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());

		$where = array();
		$join = array();
		if($active) $where[] = 'p.`active` = 1';

		$join[] = 'LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (p.`id_product` = cp.`id_product`)';
		if($id_category) {
			$k = 'category';
			if(isset($SelectMulti[$k]) && (@$searchTypes[$k] != 3)) {
				$critMultiple = '';
				foreach (explode('|',$id_category) as $v2) {
					$critMultiple .= 'cp.`id_category` = '.pSQL($v2).' OR ';
				}
				$where[] = '('.substr($critMultiple,0,-4).')';

			}
			else {
				if((isset($searchTypes[$k]) && $searchTypes[$k] == 3) || !is_numeric($id_category)) {
					$join[] = 'LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cp.`id_category` = cl.`id_category`)';
					$where[] = 'cl.`id_lang` = '.$id_lang;
					$where[] = 'cl.`name` LIKE "%'.pSQL($id_category).'%"';
				}
				else {
					$where[] = 'cp.`id_category` = '.pSQL($id_category);
				}

			}
		}

		if($id_manufacturer) {
			$k = 'manufacturer';
			$join[] = 'LEFT JOIN `'._DB_PREFIX_.'manufacturer` manu ON (p.`id_manufacturer` = manu.`id_manufacturer`)';
			if(isset($SelectMulti[$k]) && (@$searchTypes[$k] != 3)) {
				$critMultiple = '';
				foreach (explode('|',$id_manufacturer) as $v2) {
					$critMultiple .= 'p.`id_manufacturer` = '.pSQL($v2).' OR ';
				}
				$where[] = '('.substr($critMultiple,0,-4).')';

			}
			else {
				if((isset($searchTypes[$k]) && $searchTypes[$k] == 3) || !is_numeric($id_manufacturer))
					$where[] = 'manu.`name` LIKE "%'.pSQL($id_manufacturer).'%"';

				else {
					$where[] = 'p.`id_manufacturer` = '.$id_manufacturer;
				}
			}

		}
//добавляем марки (supplier)

		if($id_supplier) {
			$k = 'supplier';
			$join[] = 'LEFT JOIN `'._DB_PREFIX_.'supplier` sup ON (p.`id_supplier` = sup.`id_supplier`)';
			if(isset($SelectMulti[$k]) && (@$searchTypes[$k] != 3)) {
				$critMultiple = '';
				foreach (explode('|',$id_supplier) as $v2) 
				{
					$critMultiple .= 'p.`id_supplier` = '.pSQL($v2).' OR ';
				}

// другие и универсальные постоянно лезут вперед всех
//				$critMultiple .= 'p.`id_supplier` = 0 OR p.`id_supplier` = 12  OR ';
				$where[] = '('.substr($critMultiple,0,-4).')';

			}
			else {
				if((isset($searchTypes[$k]) && $searchTypes[$k] == 3) || !is_numeric($id_supplier))
					$where[] = 'sup.`name` LIKE "%'.pSQL($id_supplier).'%"';

				else {
					$where[] = 'p.`id_supplier` = '.$id_supplier;
				}
			}

		}
	

//print_r($join);

		if($is_new) {
			$where[] = 'DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0';
		}
		if($is_promo) {
			$where[] = '(`reduction_price` > 0 OR `reduction_percent` > 0)';
		}

		if($ids_feature) {
			foreach($ids_feature as $k=>$v) {
				$id_feature = explode('_',$k);
				$id_feature = $id_feature[1];
				$use_feat_value = false;
				$join[] = 'LEFT JOIN `'._DB_PREFIX_.'feature_product` fvp'.$k.' ON (p.`id_product` = fvp'.$k.'.`id_product`) ';
				$where[] = 'fvp'.$k.'.`id_feature` = '.$id_feature;
				if(isset($SelectMulti[$k]) && (@$searchTypes[$k] != 3)) {
						$critMultiple = '';
						foreach (explode('|',$v) as $v2) {
							if((isset($range[$k]) && $range[$k] && @$searchTypes[$k] != 3)) {
								$ranges = explode(',',$v2);
								$range1 = $ranges[0];
								$range2 = (isset($ranges[1])?$ranges[1]:false);
								$where[] = 'fvp'.$k.'_val.`id_lang` = '.$id_lang;
								$rangeCrit = '(';
								if($range1 !== false)
									$rangeCrit .= 'fvp'.$k.'_val.`value` >= '.intval(pSQL($range1)).' AND ';
								if($range2 !== false)
									$rangeCrit .= 'fvp'.$k.'_val.`value` <= '.intval(pSQL($range2)).' AND ';
								$rangeCrit = substr($rangeCrit,0,-5);
								$rangeCrit .= ') OR ';
								$critMultiple .= $rangeCrit;
								$use_feat_value = true;
							}
							elseif((isset($searchTypes[$k]) && $searchTypes[$k] == 3) || !is_numeric($v2)) {
								$use_feat_value = true;
								$critMultiple .= 'fvp'.$k.'_val.`value` LIKE "%'.pSQL($v2).'%" OR ';
							}
							else {
								$critMultiple .= 'fvp'.$k.'.`id_feature_value` = '.pSQL($v2).' OR ';
							}
						}

						$where[] = '('.substr($critMultiple,0,-4).')';
						if($use_feat_value) {
							$where[] = 'fvp'.$k.'_val.`id_lang` = '.$id_lang;
							$join[] = 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvp'.$k.'_val ON (fvp'.$k.'.`id_feature_value` = fvp'.$k.'_val.`id_feature_value`) ';
						}

					}
					else {
						if((isset($range[$k]) && $range[$k] && $searchTypes[$k] != 3)) {
							$ranges = explode(',',$v);
							$range1 = $ranges[0];
							$range2 = (isset($ranges[1])?$ranges[1]:false);
							$where[] = 'fvp'.$k.'_val.`id_lang` = '.$id_lang;
							if($range1)
								$where[] = 'fvp'.$k.'_val.`value` >= "'.pSQL($range1).'"';
							if($range2)
								$where[] = 'fvp'.$k.'_val.`value` <= "'.pSQL($range2).'"';
							$join[] = 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvp'.$k.'_val ON (fvp'.$k.'.`id_feature_value` = fvp'.$k.'_val.`id_feature_value`) ';
						}
						elseif((isset($searchTypes[$k]) && $searchTypes[$k] == 3) || !is_numeric($v)) {
							$where[] = 'fvp'.$k.'_val.`id_lang` = '.$id_lang;
							$where[] = 'fvp'.$k.'_val.`value` LIKE "%'.pSQL($v).'%"';
							$join[] = 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvp'.$k.'_val ON (fvp'.$k.'.`id_feature_value` = fvp'.$k.'_val.`id_feature_value`) ';
						}
						else {
							$where[] = 'fvp'.$k.'.`id_feature_value` = '.pSQL($v);
						}
					}
			}
		}
		
		if($ids_attributes) {
			$join[] = 'LEFT JOIN `'._DB_PREFIX_.'product_attribute` avp ON (p.`id_product` = avp.`id_product`) ';
			foreach($ids_attributes as $k=>$v) {
				$use_attr_lang = false;

			  	$join[] = 'LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` avcp'.$k.' ON (avp.`id_product_attribute` = avcp'.$k.'.`id_product_attribute`) ';
			  	if((isset($range[$k]) && $range[$k] && $searchTypes[$k] != 3)) {
				  	$id_attribute_group = explode('_',$k);
					$id_attribute_group = $id_attribute_group[1];
					$join[] = 'LEFT JOIN `'._DB_PREFIX_.'attribute` avcpa'.$k.' ON (avcp'.$k.'.`id_attribute` = avcpa'.$k.'.`id_attribute`) ';
				  	$where[] = 'avcpa'.$k.'.`id_attribute_group` = '.$id_attribute_group;
			  	}
			  		if(isset($SelectMulti[$k]) && (@$searchTypes[$k] != 3)) {
						$critMultiple = '';
						foreach (explode('|',$v) as $v2) {
							if((isset($range[$k]) && $range[$k] && $searchTypes[$k] != 3)) {
								$ranges = explode(',',$v2);
								$range1 = $ranges[0];
								$range2 = (isset($ranges[1])?$ranges[1]:false);
								$rangeCrit = '(';
								if($range1 !== false)
									$rangeCrit .= 'avcp'.$k.'_lang.`name` >= '.intval(pSQL($range1)).' AND ';
								if($range2 !== false)
									$rangeCrit .= 'avcp'.$k.'_lang.`name` <= '.intval(pSQL($range2)).' AND ';
								$rangeCrit = substr($rangeCrit,0,-5);
								$rangeCrit .= ') OR ';
								$critMultiple .= $rangeCrit;
								$use_attr_lang = true;
							}
							else
								$critMultiple .= 'avcp'.$k.'.`id_attribute` = '.pSQL($v2).' OR ';
						}
						$where[] = '('.substr($critMultiple,0,-4).')';
						if($use_attr_lang) {
							$where[] = 'avcp'.$k.'_lang.`id_lang` = '.$id_lang;
							$join[] = 'LEFT JOIN `'._DB_PREFIX_.'attribute_lang` avcp'.$k.'_lang ON (avcp'.$k.'.`id_attribute` = avcp'.$k.'_lang.`id_attribute`) ';
						}

					}
					else {
						if((isset($range[$k]) && $range[$k] && $searchTypes[$k] != 3)) {
							$ranges = explode(',',$v);
							$range1 = $ranges[0];
							$range2 = (isset($ranges[1])?$ranges[1]:false);
							$where[] = 'avcp'.$k.'_lang.`id_lang` = '.$id_lang;
							$join[] = 'LEFT JOIN `'._DB_PREFIX_.'attribute_lang` avcp'.$k.'_lang ON (avcp'.$k.'.`id_attribute` = avcp'.$k.'_lang.`id_attribute`) ';
							$rangeCrit = '(';
							if($range1 !== false)
								$rangeCrit .= 'avcp'.$k.'_lang.`name` >= '.intval(pSQL($range1)).' AND ';
							if($range2 !== false)
								$rangeCrit .= 'avcp'.$k.'_lang.`name` <= '.intval(pSQL($range2)).' AND ';
							$rangeCrit = substr($rangeCrit,0,-5);
							$rangeCrit .= ')';
							$where[] = $rangeCrit;
						}
						elseif((isset($searchTypes[$k]) && $searchTypes[$k] == 3) || !is_numeric($v)) {
							$where[] = 'avcp'.$k.'_lang.`id_lang` = '.$id_lang;
							$where[] = 'avcp'.$k.'_lang.`name` LIKE "%'.pSQL($v).'%"';
							$join[] = 'LEFT JOIN `'._DB_PREFIX_.'attribute_lang` avcp'.$k.'_lang ON (avcp'.$k.'.`id_attribute` = avcp'.$k.'_lang.`id_attribute`) ';
						}
						else {
							$where[] = 'avcp'.$k.'.`id_attribute` = '.pSQL($v);
						}
					}
					if($on_stock) {
						$where[] = 'avp.`quantity` > 0';
					}
			}
			if(isset($ids_attributes[$comb_img])) {
				$joinImg = 'LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON (pai.`id_product_attribute` = avp.`id_product_attribute`)
							LEFT JOIN `'._DB_PREFIX_.'image` i ON (IF(pai.`id_image`,
									i.`id_image` =
									(SELECT i2.`id_image`
									FROM `'._DB_PREFIX_.'image` i2
									INNER JOIN `'._DB_PREFIX_.'product_attribute_image` pai2 ON (pai2.`id_image` = i2.`id_image`)
									WHERE i2.`id_product` = p.`id_product` AND pai2.`id_product_attribute` = avp.`id_product_attribute`
									ORDER BY i2.`position`
									LIMIT 1),
									i.`id_product` = p.`id_product` AND i.`cover` = 1)
							)';
			}
		}
		

		if($on_stock) {
			$where[] = 'p.`quantity` > 0';
		}

		$sql_price="
		CASE WHEN t.rate>0 THEN
			CASE WHEN p.reduction_from!=p.reduction_to THEN
				CASE WHEN p.reduction_to>='".date("Y-m-d")."' and p.reduction_from<='".date("Y-m-d")."' THEN
					CASE WHEN p.reduction_price>0 THEN
						ROUND(((p.price*(1+(t.rate/100)))-p.reduction_price)*cu.conversion_rate,2)
					WHEN p.reduction_percent>0 THEN
						ROUND(((p.price*(1+(t.rate/100)))-((p.price*(1+(t.rate/100)))*(p.reduction_percent/100)))*cu.conversion_rate,2)
					ELSE
						ROUND((p.price*(1+t.rate/100))*cu.conversion_rate,2)
					END
				ELSE
					ROUND((p.price*(1+t.rate/100))*cu.conversion_rate,2)
				END
			ELSE
				CASE WHEN p.reduction_price>0 THEN
					ROUND(((p.price*(1+t.rate/100))-p.reduction_price)*cu.conversion_rate,2)
				WHEN p.reduction_percent>0 THEN
					ROUND(((p.price*(1+t.rate/100))-((p.price*(1+(t.rate/100)))*(p.reduction_percent/100)))*cu.conversion_rate,2)
				ELSE
					ROUND((p.price*(1+t.rate/100))*cu.conversion_rate,2)
				END
			END
		ELSE
			CASE WHEN p.reduction_from!=p.reduction_to THEN
				CASE WHEN p.reduction_to>='".date("Y-m-d")."' and p.reduction_from<='".date("Y-m-d")."' THEN
					CASE WHEN p.reduction_price>0 THEN
						ROUND((p.price-p.reduction_price)*cu.conversion_rate,2)
					WHEN p.reduction_percent>0 THEN
						ROUND((p.price-(p.price*(p.reduction_percent/100)))*cu.conversion_rate,2)
					ELSE
						ROUND((p.price)*cu.conversion_rate,2)
					END
				ELSE
					ROUND((p.price)*cu.conversion_rate,2)
				END
			ELSE
				CASE WHEN p.reduction_price>0 THEN
					ROUND((p.price-p.reduction_price)*cu.conversion_rate,2)
				WHEN p.reduction_percent>0 THEN
					ROUND((p.price-(p.price*(p.reduction_percent/100)))*cu.conversion_rate,2)
				ELSE
					ROUND((p.price)*cu.conversion_rate,2)
				END
			END

		END
		";

		if(($gtPrice>0)||($ltPrice>0)) {
			$join[] = 'JOIN `'._DB_PREFIX_.'currency` cu';
			$where[] = 'cu.id_currency='.intval($cookie->id_currency);
		}
		if($gtPrice>0) {
			$where[] = $sql_price.' >= '.intval($gtPrice);
		}
		if($ltPrice>0) {
			$where[] = $sql_price.' <= '.intval($ltPrice);
		}

/*
echo '
				SELECT SQL_CACHE COUNT(DISTINCT p.`id_product`) as nbProduct
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON p.`id_tax` = t.`id_tax` '.
				(count($join)?implode("\n",$join):'')
				.(count($where)?' WHERE '.implode(' AND ',$where):'');
		*/
		/* Return only the number of products */
		
	
if($filter_name) 
{
	// отсроимся от хакеров 
//	$filter_name = Search::sanitize($filter_name, $id_lang);

	foreach (explode(' ',$filter_name) as $cfilter) 
	{
		// отстроимся от неграмотных
/*
		$alias = new Alias(NULL, $cfilter);
		if ($alias->search) $cfilter = $alias->search;
*/
		$where[] = 'pl.`name` LIKE \'%'.$cfilter.'%\' ';
	}
}
		
//echo "GET_TOTAL".$getTotal;
		if ($getTotal)
		{
		$sql_total ='SELECT SQL_CACHE COUNT(DISTINCT p.`id_product`) as nbProduct
		FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON ps.`id_product` = p.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON p.`id_tax` = t.`id_tax` '.
		(count($join)?implode("\n",$join):'').'
		'.(isset($joinImg) && $joinImg ? $joinImg:'LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)').'
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		'.(count($where)?' WHERE '.implode(' AND ',$where):'');
		
		//echo "SQL-TOTAL".$sql_total;
		$result = Db::getInstance()->getRow($sql_total);		
				//echo "TOTALS=".isset($result['nbProduct']) ? $result['nbProduct'] : 0;
				return isset($result['nbProduct']) ? $result['nbProduct'] : 0;
		}		
		
		//ali
	
		$sql = '
		SELECT SQL_CACHE p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, t.`rate`, i.`id_image`, il.`legend`, ps.`sale_nbr` AS sales, DATEDIFF(p.`date_upd`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, IF(ps.`sale_nbr` > '.(Validate::isUnsignedInt(Configuration::get("PS_NB_HOT_PRODUCT")) ? Configuration::get("PS_NB_HOT_PRODUCT") : 30).',"1","0") as hot, m.`id_manufacturer`, m.`name` as manufacturer_name
		FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON ps.`id_product` = p.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON p.`id_manufacturer` = m.`id_manufacturer`
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON p.`id_tax` = t.`id_tax` '.
		(count($join)?implode("\n",$join):'').'
		'.(isset($joinImg) && $joinImg ? $joinImg:'LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)').'
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		'.(count($where)?' WHERE '.implode(' AND ',$where):'');
		
// тормозит из-за ORDER BY
 		$sql .= ' GROUP BY p.`id_product` ORDER BY p.`id_supplier`, '.(isset($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).' LIMIT '.((intval($p) - 1) * intval($n)).','.intval($n);

// альтернатива - отдать сортировку PHP
//		$sql .= ' GROUP BY p.`id_product` LIMIT '.((intval($p) - 1) * intval($n)).','.intval($n);

//if ($id_supplier) echo '---есть $id_supplier---<br>';
//echo $sql;		

		
		//echo '<!--super---'.$sql.'-->';
		$result = Db::getInstance()->ExecuteS($sql);

		if ($orderBy == 'price')
			Tools::orderbyPrice($result, $orderWay);
		if (!$result)
			return false;
		return Product::getProductsProperties($id_lang, $result);
	}
	function hookLeftColumn($params) {
		return $this->getSearchBlock();
	}
	function hookRightColumn($params) {
		return $this->getSearchBlock();
	}
	function hookTop($params) {
		return $this->getSearchBlock();
	}
	function hookUpdateProduct($params) {
		return $this->clearAdvcCache();
	}
	function hookAddProduct($params) {
		return $this->hookUpdateProduct();
	}
	function hookDeleteProduct($params) {
		return $this->hookUpdateProduct();
	}
	function hookUpdateProductAttribute($params) {
		return $this->hookUpdateProduct();
	}
}



?>
