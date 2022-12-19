<?php
require_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');
require_once (dirname(__FILE__).'/UniPaySystem.php');

class AdminUniPaySystem extends AdminTab
{
	public function __construct()
	{
		global $cookie;
	 	$this->table = 'universalpay_system';
	 	$this->className = 'UniPaySystem';
	 	$this->lang = true;
	 	$this->edit = true;
	 	$this->delete = true;
		
 		$this->fieldImageSettings = array('name' => 'logo', 'dir' => 'pay');

		$this->fieldsDisplay = array(
		'id_universalpay_system' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 30),
		'logo' => array('title' => $this->l('Logo'), 'align' => 'center', 'image' => 'pay', 'orderby' => false, 'search' => false),
		'name' => array('title' => $this->l('Name'), 'width' => 150),
		'description_short' => array('title' => $this->l('Short description'), 'width' => 450, 'maxlength' => 90, 'orderby' => false),
		'active' => array('title' => $this->l('Displayed'), 'active' => 'status', 'align' => 'center', 'type' => 'bool', 'orderby' => false));

		$this->_select = 'position ';

		parent::__construct();
	}

	public function displayForm($token = NULL)
	{
		global $currentIndex, $cookie;
		parent::displayForm();

		if (!($obj = $this->loadObject(true)))
			return;
		$active = $this->getFieldValue($obj, 'active');

		echo '
		<form action="'.$currentIndex.'&submitAdd'.$this->table.'=1&token='.($token!=NULL ? $token : $this->token).'" method="post" enctype="multipart/form-data">
		'.($obj->id ? '<input type="hidden" name="id_'.$this->table.'" value="'.$obj->id.'" />' : '').'
			<fieldset><legend><img src="../img/admin/tab-payment.gif" />'.$this->l('Pay Systems').'</legend>
				<label>'.$this->l('Name:').' </label>
				<div class="margin-form translatable">';
		foreach ($this->_languages AS $language)
			echo '
					<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" style="width: 260px" name="name_'.$language['id_lang'].'" id="name_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'name', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" /><sup> *</sup>
						<span class="hint" name="help_box">'.$this->l('Invalid characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
					</div>';
		echo '	<p class="clear"></p>
				</div>
				<label>'.$this->l('Short Description:').' </label>
				<div class="margin-form translatable">';
		foreach ($this->_languages AS $language)
			echo '
					<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" style="width: 260px" name="description_short_'.$language['id_lang'].'" id="description_short_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'description_short', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" /><sup> *</sup>
						<span class="hint" name="help_box">'.$this->l('Invalid characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
					</div>';
		echo '	<p class="clear"></p>
				</div>
				<label>'.$this->l('Displayed:').' </label>
				<div class="margin-form">
					<input type="radio" name="active" id="active_on" value="1" '.($active ? 'checked="checked" ' : '').'/>
					<label class="t" for="active_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="active" id="active_off" value="0" '.(!$active ? 'checked="checked" ' : '').'/>
					<label class="t" for="active_off"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('Description:').' </label>
				<div class="margin-form translatable">';
		foreach ($this->_languages AS $language)
			echo '
					<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<textarea class="rte" cols="100" rows="10" name="description_'.$language['id_lang'].'" rows="10" cols="100">'.htmlentities($this->getFieldValue($obj, 'description', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'</textarea>
					</div>';
		echo '	<p class="clear"></p>
				</div>
				<label>'.$this->l('Image:').' </label>
				<div class="margin-form">';
		echo 		$this->displayImage($obj->id, _PS_IMG_DIR_.'pay/'.$obj->id.'.jpg', 350, NULL, Tools::getAdminToken('AdminUniPaySystem'.(int)(Tab::getIdFromClassName('AdminUniPaySystem')).(int)($cookie->id_employee)), true);
		echo '	<br /><input type="file" name="logo" />
					<p>'.$this->l('Upload payment logo from your computer').'</p>
				</div>
				<div class="clear"><br /></div>
				<div class="margin-form">
					<input type="submit" class="button" name="submitAdd'.$this->table.'" value="'.$this->l('Save').'"/>
				</div>
				<div class="small"><sup>*</sup> '.$this->l('Required field').'</div>
			</fieldset>
		</form>
		<p class="clear"></p>';

		$iso = Language::getIsoById((int)($cookie->id_lang));
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		$ad = dirname($_SERVER["PHP_SELF"]);
		echo '
			<script type="text/javascript">
			var iso = \''.$isoTinyMCE.'\' ;
			var pathCSS = \''._THEME_CSS_DIR_.'\' ;
			var ad = \''.$ad.'\' ;
			</script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
			<script type="text/javascript">
					toggleVirtualProduct(getE(\'is_virtual_good\'));
					unitPriceWithTax(\'unit\');
			</script>';
	}

}
