<?php

/*
 * Copyright 2010 VTEM.NET
 */
 

class vtemslideshow extends Module {
	private $_postErrors = array();
	
	function __construct() {
		$this->name = 'vtemslideshow';
		parent::__construct();

		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->displayName = $this->l('VTEM Slideshow');
		$this->description = $this->l('This module will make images on your site more vivid beautiful as the effects of FLASH software on the homepage or in a column.');
	}
	
	public function install()
	{
		if (!parent::install() OR !$this->registerHook('home'))
			return false;
		
		$images = $this->_parseImageDir();
		if (!$images)
			return false;
			
        $langs = '';
        for($i = 0; $i < count($images); $i++)
            $langs .= 'all;';
						
		if (!Configuration::updateValue($this->name.'_timeout'	, 5000) 	OR
			!Configuration::updateValue($this->name.'_speed'	, 1000) 	OR
			!Configuration::updateValue($this->name.'_height'	, 250) 	OR
			!Configuration::updateValue($this->name.'_width'	, 560)   OR
			!Configuration::updateValue($this->name.'_startingSlide'	, 0) 		OR
            !Configuration::updateValue($this->name.'_fx'	, 'fade') 	OR
			!Configuration::updateValue($this->name.'_position'	, 'Bottom-Right') 	OR
			!Configuration::updateValue($this->name.'_navstyle'	, 'Navigation-style1') 	OR
			!Configuration::updateValue($this->name.'_nav'		, 'true') 	OR
			!Configuration::updateValue($this->name.'_fit'		, 'true') 	OR
			!Configuration::updateValue($this->name.'_next_prev'	, 'true') 	OR
			!Configuration::updateValue($this->name.'_links'	, '')		OR
			!Configuration::updateValue($this->name.'_langs'	, $langs)	OR
			!Configuration::updateValue($this->name.'_images'	, implode(";", $images)))
			return false;
		
		return true;
	}
	
	private function _postValidation()
	{
		if (!Validate::isInt(Tools::getValue('timeout')))
			$this->_postErrors[] = $this->l('Timeout : invalid value, it must be an number.');
		if (!Validate::isInt(Tools::getValue('speed')))
			$this->_postErrors[] = $this->l('Transition speed : invalid value, it must be an number.');
		if (!Validate::isInt(Tools::getValue('height')))
			$this->_postErrors[] = $this->l('Module height : invalid value, it must be an number.');
        if (!Validate::isInt(Tools::getValue('width')))
			$this->_postErrors[] = $this->l('Module width : invalid value, it must be an number.');
		if (!Validate::isInt(Tools::getValue('startingSlide')))
			$this->_postErrors[] = $this->l('Starting slide : invalid value, it must be an number.');
	}
	
	private function _postProcess()
	{
		Configuration::updateValue($this->name.'_timeout'	,  Tools::getValue('timeout'));
		Configuration::updateValue($this->name.'_speed' ,  Tools::getValue('speed'));
		Configuration::updateValue($this->name.'_height'	,  Tools::getValue('height'));
		Configuration::updateValue($this->name.'_width'	    ,  Tools::getValue('width'));
		Configuration::updateValue($this->name.'_fx'	,  Tools::getValue('fx'));
		Configuration::updateValue($this->name.'_position'	,  Tools::getValue('position'));
		Configuration::updateValue($this->name.'_navstyle'	,  Tools::getValue('navstyle'));
		Configuration::updateValue($this->name.'_startingSlide'		,  Tools::getValue('startingSlide'));
		Configuration::updateValue($this->name.'_images'	,  Tools::getValue('image_data'));
		Configuration::updateValue($this->name.'_links'		,  Tools::getValue('link_data'));
		Configuration::updateValue($this->name.'_langs'		,  Tools::getValue('lang_data'));
		Configuration::updateValue($this->name.'_nav'		, (Tools::getValue('nav') 	? 'true' : 'false'));
		Configuration::updateValue($this->name.'_fit'		, (Tools::getValue('fit') ? 'true' : 'false'));
		Configuration::updateValue($this->name.'_next_prev'		, (Tools::getValue('next_prev') ? 'true' : 'false'));
		
		$this->_html .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
	}

	public function getContent() {
		$this->_html .= '<h2>'.$this->displayName.'</h2>';
		
		if (Tools::isSubmit('submit'))
		{			
			$this->_postValidation();
			
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
			{
				foreach ($this->_postErrors as $err)
				{
					$this->_html .= '<div class="alert error">'.$err.'</div>';
				}
			}
		}
		
		$this->_displayForm();
	}
	 
	private function _displayForm() {
		$dirImages = $this->_parseImageDir();
		$confImages = $this->_getImageArray();
		$nbDirImages = count($dirImages);
		$nbConfImages = count($confImages);
		$fxs = array('fade','fadeZoom', 'blindX', 'blindY','blindZ', 'cover', 'curtainX','curtainY', 'growX', 'growY','scrollUp', 'scrollDown', 'scrollLeft','scrollRight', 'scrollHorz', 'scrollVert','shuffle', 'slideX', 'slideY','toss', 'turnUp', 'turnDown','turnLeft', 'turnRight', 'uncover','wipe', 'zoom', 'all','none');
	    $positions = array('Top-Left', 'Top-Right', 'Bottom-Left', 'Bottom-Right');
	    $navstyles = array('Navigation-style1', 'Navigation-style2', 'Navigation-style3', 'Navigation-style4', 'Navigation-style5');
		
		echo $this->_html;
	
		echo '
			<script type="text/javascript" src="../js/jquery/jquery.tablednd_0_5.js"></script>
			<script type="text/javascript" src="../modules/'.$this->name.'/js/ajaxupload.js"></script>
			<script type="text/javascript" src="../modules/'.$this->name.'/js/vtemslideshow.js"></script>
			<link href="../modules/'.$this->name.'/styles.css" rel="stylesheet" type="text/css" />
			
			<table id="hidden-row" style="display:none">' . $this->_getTableRowHTML(0, 2, '') . '</table>
		
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
				<fieldset>
				<legend><img src="../img/admin/cog.gif" alt="" class="middle" />'.$this->l('Settings').'</legend>
					
					<label>'.$this->l('Module width').'</label>
					<div class="margin-form">
						<input type="text" name="width" size="6" value="' . 
							Tools::getValue('width', Configuration::get($this->name.'_width')).'"/>
						<p class="clear">'.$this->l('Set container width for the slideshow in pixel (ex : \'600\').').'</p>
					</div>
					
					<label>'.$this->l('Module height').'</label>
					<div class="margin-form">
						<input type="text" name="height" size="6" value="' . 
							Tools::getValue('height', Configuration::get($this->name.'_height')).'"/>
						<p class="clear">'.$this->l('Set container height for the slideshow in pixel (ex : \'250\').').'</p>
					</div>
									
					<label>'.$this->l('Timeout').'</label>
					<div class="margin-form">
						<input type="text" name="timeout" size="6" value="' . 
							Tools::getValue('timeout', Configuration::get($this->name.'_timeout')).'"/>
						<p class="clear">'.$this->l('Time in milliseconds between slide transitions (0 to disable auto advance).').'</p>
					</div>
					
					<label>'.$this->l('Transition speed').'</label>
					<div class="margin-form">
						<input type="text" name="speed" size="6" value="' . 
							Tools::getValue('speed', Configuration::get($this->name.'_speed')).'"/>
						<p class="clear">'.$this->l('Speed of the transition in milliseconds.').'</p>
					</div>
					
					<label>'.$this->l('Starting slide').'</label>
					<div class="margin-form">
						<input type="text" name="startingSlide" size="6" value="' . 
							Tools::getValue('startingSlide', Configuration::get($this->name.'_startingSlide')).'"/>
						<p class="clear">'.$this->l('zero-based index of the first slide to be displayed').'</p>
					</div>
					
					<label>'.$this->l('Effect').'</label>
					<div class="margin-form">
						<select id="fx" name = "fx">';
					    foreach($fxs as $fx){
						  echo '<option value="'.$fx.'"'.(Tools::getValue('fx', Configuration::get($this->name.'_fx')) == $fx ? ' selected="selected"' : '' ).'>'.$this->l($fx).'</option>';
							}
					   echo '</select>
						<p class="clear">'.$this->l('Select a effect for the slideshow').'</p>
					</div>
					<label>'.$this->l('Navigation Position').'</label>
					<div class="margin-form">
						<select id="position" name = "position">';
					    foreach($positions as $position){
						  echo '<option value="'.$position.'"'.(Tools::getValue('position', Configuration::get($this->name.'_position')) == $position ? ' selected="selected"' : '' ).'>'.$this->l($position).'</option>';
							}
					   echo '</select>
						<p class="clear">'.$this->l('Select a position for navigation on the slideshow').'</p>
					</div>
					<label>'.$this->l('Navigation style').'</label>
					<div class="margin-form">
						<select id="navstyle" name = "navstyle">';
					    foreach($navstyles as $navstyle){
						  echo '<option value="'.$navstyle.'"'.(Tools::getValue('navstyle', Configuration::get($this->name.'_navstyle')) == $navstyle ? ' selected="selected"' : '' ).'>'.$this->l($navstyle).'</option>';
							}
					   echo '</select>
						<p class="clear">'.$this->l('Select a navigation style for the slideshow').'</p>
					</div>
					
					<label>'.$this->l('Show Navigation').'</label>
					<div class="margin-form">
						<input type="checkbox" name="nav" value="' . 
							(Tools::getValue('nav', Configuration::get($this->name.'_nav')) ? "true" : "false").'"' .
							(Tools::getValue('nav', Configuration::get($this->name.'_nav')) == "true" ? ' checked="checked"' : '') . ' />
						<p class="clear">'.$this->l('Show navigation buttons for the slideshow.').'</p>
					</div>
					
					<label>'.$this->l('Show Prev/Next').'</label>
					<div class="margin-form">
						<input type="checkbox" name="next_prev" value="' . 
							(Tools::getValue('next_prev', Configuration::get($this->name.'_next_prev')) ? "true" : "false").'"' .
							(Tools::getValue('next_prev', Configuration::get($this->name.'_next_prev')) == "true" ? ' checked="checked"' : '') . ' />
						<p class="clear">'.$this->l('Show prev and next navigation buttons.').'</p>
					</div>
					
					<label>'.$this->l('Fit').'</label>
					<div class="margin-form">
						<input type="checkbox" name="fit" value="' . 
							(Tools::getValue('fit', Configuration::get($this->name.'_fit')) ? "true" : "false").'"' .
							(Tools::getValue('fit', Configuration::get($this->name.'_fit')) == "true" ? ' checked="checked"' : '') . ' />
						<p class="clear">'.$this->l('Force slides to fit container.').'</p>
					</div>
					
					
					<br />
					
					<input type="hidden" id="hidden_image_data" name="image_data" value="' . Configuration::get($this->name.'_images') . '"/>
					<input type="hidden" id="hidden_link_data" name="link_data" value="'   . Configuration::get($this->name.'_links') . '"/>
					<input type="hidden" id="hidden_lang_data" name="lang_data" value="'   . Configuration::get($this->name.'_langs') . '"/>
					
					<fieldset style="background:#fffff6;"><legend style="font-weight: bold; border:none; background: none;">' . $this->l('Images') . '</legend>
					<table cellpadding="0" cellspacing="0" class="table space'.($nbDirImages >= 2 ? ' tableDnD' : '' ).'" id="table_images" style="margin-left: 30px; width: 825px;">
					<tr class="nodrag nodrop">
						<th width="60" colspan="2">' . $this->l('Position') . '</th>
						
						<th style="padding-left: 10px;">'. $this->l('Image') .' </th>
						<th width="270">'. $this->l('Link') .' </th>
						<th width="80">'. $this->l('Language') .' </th>
						<th width="40">'. $this->l('Enabled') .' </th>
						<th width="40">'. $this->l('Delete') .' </th>
					</tr>';
					
				if ($nbDirImages) {
					$i = 1;
					
					foreach ($confImages AS $confImage) {
						if (in_array($confImage['name'], $dirImages)) {
							echo $this->_getTableRowHTML($i, $nbDirImages, $confImage['name'], $confImage['link'], true);
							$i++;
						}
					}
					
					if ($nbDirImages > $nbConfImages) {
						foreach ($dirImages AS $dirImage) {
							if (!$this->_isImageInArray($dirImage, $confImages)) {
								echo $this->_getTableRowHTML($i, $nbDirImages, $dirImage);
								$i++;
							}
						}
					}
				}
				else {
					echo '<tr><td colspan="4">'.$this->l('No image in module directory').'</td></tr>';
				}
					
			echo '	</table>
			
			        <br />
			        
			        <a href="#" id="uploadImage" style="margin-left:30px">
			             <img src="../img/admin/add.gif" alt="upload image" />' . $this->l('Add an image') . '
                    </a>
                    <img id="loading_gif" src="'. _MODULE_DIR_ . $this->name . '/images/ajax-loader.gif" alt="uploading..." style="position:relative; top:2px; display:none;"/>
			       </fieldset>
					<br /><br />
					<center>
					<input type="submit" name="submit" value="'.$this->l('Save').'" class="vtem_button" />
				    </center>
				</fieldset>
			</form>';
	}
	
	function hookHome($params) {
		global $smarty;
		$smarty->assign(array('images'  => $this->_getImageArray(true),
							  'timeout' => Configuration::get($this->name.'_timeout'),
							  'speed'	=> Configuration::get($this->name.'_speed'),
							  'height' 	=> Configuration::get($this->name.'_height'),
							  'width' 	=> Configuration::get($this->name.'_width'),
							  'fx' 	=> Configuration::get($this->name.'_fx'),
							  'position' 	=> Configuration::get($this->name.'_position'),
							  'navstyle' 	=> Configuration::get($this->name.'_navstyle'),
							  'startingSlide' 	=> Configuration::get($this->name.'_startingSlide'),
							  'nav' 	=> Configuration::get($this->name.'_nav'),
							  'fit' 	=> Configuration::get($this->name.'_fit'),
							  'next_prev' 	=> Configuration::get($this->name.'_next_prev')));

		return $this->display(__FILE__, 'vtemslideshow.tpl');
	
	}
	
	function hookLeftColumn($params) {
		global $smarty;
		
		$smarty->assign('column', true);
		return $this->hookHome($params);
	}	
	
	function hookRightColumn($params) {
		return $this->hookLeftColumn($params);
	}
	
	private function _getImageArray($lang_filter = false) {
	    global $cookie;
	    
		$images = explode(";", Configuration::get($this->name.'_images'));
		$links 	= explode(";", Configuration::get($this->name.'_links'));
		$langs 	= $lang_filter ? explode(";", Configuration::get($this->name.'_langs')) : false;
		
		$tab_images = array();
		
		for($i = 0, $length = sizeof($images); $i < $length; $i++) {
			if ($images[$i] != "") {
				if ($lang_filter && isset($langs[$i]) && $langs[$i] != 'all' && $langs[$i] != $cookie->id_lang)
				    continue;
				
				$tab_images[] = array('name' 	=> $images[$i], 
								      'link' 	=> isset($links[$i]) ? $links[$i] : '');
			}
		}
		
		return $tab_images;
	}
	
	private function _isImageInArray($name, $array) {
		if (!is_array($array))
			return false;
		
		foreach ($array as $image) {
			if (isset($image['name'])) {
				if ($image['name'] == $name)
					return true;
			}
		}
		
		return false;
	}
	
	private function _parseImageDir() {
	    $dir = _PS_MODULE_DIR_ . $this->name . '/slides/';
	    $imgs = array();
		$imgmarkup = '';
		
	    if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	            if (!is_dir($file) && preg_match("/^[^.].*?\.(jpe?g|gif|png)$/i", $file)) {
	                array_push($imgs, $file);
	            }
	        }
	        closedir($dh);
	    } else {
	        echo 'can\'t open slide directory';
	        return false;
	    }
		
		return $imgs;
	}
	
	private function _getTableRowHTML($i, $nbImages, $imagename, $imagelink = '', $checked = false) {
	   return '<tr id="tr_image_'. $i . '"' . ($i % 2 ? ' class="alt_row"' : '').' style="height: 42px;">
				<td class="positions" width="30" style="padding-left: 20px;">' . $i . '</td>
				<td'.($nbImages >= 2 ? ' class="dragHandle"' : '') . ' id="td_image_'. $i . '" width="30">
					<a' .($i == 1 ? ' style="display: none;"' : '' ).' href="#" class="move-up"><img src="../img/admin/up.gif" alt="'.$this->l('Up').'" title="'.$this->l('Up').'" /></a><br />
					<a '.($i == $nbImages ? ' style="display: none;"' : '' ).'href="#" class="move-down"><img src="../img/admin/down.gif" alt="'.$this->l('Down').'" title="'.$this->l('Down').'" /></a>
				</td>
				<td class="imagename" style="padding-left: 10px;">'. $imagename .'</td>
				<td class="imagelink">
					<input type="text" style="width: 250px" name="image_link_' . $i . '" value="' . $imagelink .'" />
				</td>
				<td class="imagelang">' . $this->_getLanguageSelectHTML($i) . '</td>
				<td class="checkbox_image_enabled" style="padding-left: 25px;" width="40">
					<input type="checkbox" name="image_enable_' . $i . '"' . ($checked ? ' checked="checked"' : '') . ' /> 
				</td>
				<td class="delete_image" style="padding-left: 25px;" width="40">
					<img src="../img/admin/delete.gif" alt="'.$this->l('Delete').'" title="'.$this->l('Delete').'" style="cursor:pointer;" /> 
				</td>
			</tr>';
	}
	
	private function _getLanguageSelectHTML($i) {
        $languages = Language::getLanguages();
        $langs 	   = explode(";", Configuration::get($this->name.'_langs'));
        
        $html =  '<select name="language_' . $i . '" style="width:55px">';
        $html .= '<option value="all">ALL</option>';
        
		foreach ($languages as $language)
		{		
			 $html .= '<option value="' . $language['id_lang'] . '"' . (isset($langs[$i-1]) && $langs[$i-1] == $language['id_lang'] ? 'selected="selected"' : '') . '>' . strtoupper($language['iso_code']) . '</option>';
		}
        
        return $html .= '</select>';
	}
}

?>