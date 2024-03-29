<?php

/**
  * Scenes tab for admin panel, AdminScenes.php
  * @category admin
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

include_once(dirname(__FILE__).'/../../classes/AdminTab.php');

class AdminScenes extends AdminTab
{
	protected $maxImageSize = 1000000;

	public function __construct()
	{
	 	$this->table = 'scene';
	 	$this->className = 'Scene';
	 	$this->lang = true;
	 	$this->edit = true;
	 	$this->delete = true;
		
		$this->fieldImageSettings = array(
			array('name' => 'image', 'dir' => 'scenes'),
			array('name' => 'thumb', 'dir' => 'scenes/thumbs')
		);
				
		$this->fieldsDisplay = array(
		'id_scene' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
		'name' => array('title' => $this->l('Image Maps'), 'width' => 150),
		'active' => array('title' => $this->l('Activated'), 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false)
		);
	
		parent::__construct();
	}
	
	
	public function delete()
	{
		removeZoneProducts();
	}
	
	function afterImageUpload()
	{
		/* Generate image with differents size */
		$obj = $this->loadObject(true);
		if ($obj->id AND (isset($_FILES['image']) OR isset($_FILES['thumb'])))
		{
			$imagesTypes = ImageType::getImagesTypes('scenes');
			foreach ($imagesTypes AS $k => $imageType)
			{
				if ($imageType['name'] == 'large_scene' AND isset($_FILES['image']))
					imageResize($_FILES['image']['tmp_name'], _PS_SCENE_IMG_DIR_.$obj->id.'-'.stripslashes($imageType['name']).'.jpg', intval($imageType['width']), intval($imageType['height']));
				elseif ($imageType['name'] == 'thumb_scene')
					{
					if (isset($_FILES['thumb'])  AND !$_FILES['thumb']['error'])
						$tmpName = $_FILES['thumb']['tmp_name'];
					else
						$tmpName = $_FILES['image']['tmp_name'];
					imageResize($tmpName, _PS_SCENE_THUMB_IMG_DIR_.$obj->id.'-'.stripslashes($imageType['name']).'.jpg', intval($imageType['width']), intval($imageType['height']));
					}
			}
		}
		return true;
	}
	
	
	
	/**
	 * Build a categories tree
	 *
	 * @param array $indexedCategories Array with categories where product is indexed (in order to check checkbox)
	 * @param array $categories Categories to list
	 * @param array $current Current category
	 * @param integer $id_category Current category id
	 */
	function recurseCategoryForInclude($indexedCategories, $categories, $current, $id_category = 1, $id_category_default = NULL)
	{
		global $done;
		static $irow;
		$id_obj = intval(Tools::getValue($this->id));

		if (!isset($done[$current['infos']['id_parent']]))
			$done[$current['infos']['id_parent']] = 0;
		$done[$current['infos']['id_parent']] += 1;

		$todo = sizeof($categories[$current['infos']['id_parent']]);
		$doneC = $done[$current['infos']['id_parent']];

		$level = $current['infos']['level_depth'] + 1;
		$img = $level == 1 ? 'lv1.gif' : 'lv'.$level.'_'.($todo == $doneC ? 'f' : 'b').'.gif';

		echo '
		<tr class="'.($irow++ % 2 ? 'alt_row' : '').'">
			<td>
				<input type="checkbox" name="categoryBox[]" class="categoryBox'.($id_category_default != NULL ? ' id_category_default' : '').'" id="categoryBox_'.$id_category.'" value="'.$id_category.'"'.((in_array($id_category, $indexedCategories) OR (intval(Tools::getValue('id_category')) == $id_category AND !intval($id_obj))) ? ' checked="checked"' : '').' />
			</td>
			<td>
				'.$id_category.'
			</td>
			<td>
				<img src="../img/admin/'.$img.'" alt="" /> &nbsp;<label for="categoryBox_'.$id_category.'" class="t">'.stripslashes(Category::hideCategoryPosition($current['infos']['name'])).'</label>
			</td>
		</tr>';

		if (isset($categories[$id_category]))
			foreach ($categories[$id_category] AS $key => $row)
				if ($key != 'infos')
					$this->recurseCategoryForInclude($indexedCategories, $categories, $categories[$id_category][$key], $key);
	}
	
	
	public function displayForm()
	{
		global $currentIndex, $cookie;
		
		$obj = $this->loadObject(true);
		
		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$langtags = 'name';
		$active = $this->getFieldValue($obj, 'active');
		
		echo '
		<script type="text/javascript">
			id_language = Number('.$defaultLanguage.');';
			
			
			echo 'startingData = new Array();'."\n";
			foreach ($obj->getProducts() as $key => $product)
			{
				$productObj = new Product($product['id_product'], $full = true, $cookie->id_lang);
				echo 'startingData['.$key.'] = new Array(\''.$productObj->name.'\', '.$product['id_product'].', '.$product['x_axis'].', '.$product['y_axis'].', '.$product['zone_width'].', '.$product['zone_height'].');';
			}
			
		echo
		'</script>
		<form id="scenesForm" action="'.$currentIndex.'&submitAdd'.$this->table.'=1&token='.$this->token.'" method="post" enctype="multipart/form-data">
		'.($obj->id ? '<input type="hidden" name="id_'.$this->table.'" value="'.$obj->id.'" />' : '').'
			<fieldset><legend><img src="../img/admin/photo.gif" />'.$this->l('Image Maps').'</legend>';
			
		
				echo '
				<label>Атас!</label>
				<div class="margin-form">Разрешить jquery.hotkeys-0.7.8-packed.js в header.tpl</div>
					<label>'.$this->l('How to map products in the image:').' </label>
					<div class="margin-form">
						'.$this->l('When a customer hovers over the image with the mouse, a pop-up appears displaying a brief description of the product. The customer can then click to open the product\'s full product page. To achieve this, please define the \'mapping zone\' that, when hovered over, will display the pop-up. Left-click with your mouse to draw the four-sided mapping zone, then release. Then, begin typing the name of the associated product. A list of products appears. Click the appropriate product, then click OK. Repeat these steps for each mapping zone you wish to create. When you have finished mapping zones, click Save Image Map.').'
					</div>
					';
		
		
		echo '<label>'.$this->l('Image map name:').' </label>
				<div class="margin-form">';
		foreach ($languages as $language)
			echo '
					<div id="name_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" style="width: 260px" name="name_'.$language['id_lang'].'" id="name_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'name', intval($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" /><sup> *</sup>
					</div>';
		$this->displayFlags($languages, $defaultLanguage, $langtags, 'name');
		echo '		<div class="clear"></div>
				</div>';
			
			
			 	echo '<label>'.$this->l('Status:').' </label>
				<div class="margin-form">
					<input type="radio" name="active" id="active_on" value="1" '.((!$obj->id OR Tools::getValue('active', $obj->active)) ? 'checked="checked" ' : '').'/>
					<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Activated').'" title="'.$this->l('Activated').'" /></label>
					<input type="radio" name="active" id="active_off" value="0" '.((!Tools::getValue('active', $obj->active) AND $obj->id) ? 'checked="checked" ' : '').'/>
					<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Deactivated').'" title="'.$this->l('Deactivated').'" /></label>
					<p>'.$this->l('Activate or deactivate the image map').'</p>
				</div>';
				
				
					$sceneImageTypes = ImageType::getImagesTypes('scenes');
					$largeSceneImageType = NULL;
					$thumbSceneImageType = NULL;
					foreach ($sceneImageTypes as $sceneImageType)
					{
						if ($sceneImageType['name'] == 'large_scene')
							$largeSceneImageType = $sceneImageType;
						if ($sceneImageType['name'] == 'thumb_scene')
							$thumbSceneImageType = $sceneImageType;
					}
					
	
 	echo '<label>'.$this->l('Image to be mapped:').' </label>
				<div class="margin-form">
					<input type="hidden" id="stay_here" name="stay_here" value="" />
					<input type="file" name="image" id="image_input" /> <input type="button" value="'.$this->l('Upload image').'" onclick="{$(\'#stay_here\').val(\'true\');$(\'#scenesForm\').submit();}" class="button" /><br/>
					<p>'.$this->l('Format:').' JPG, GIF, PNG. '.$this->l('File size:').' '.($this->maxImageSize / 1000).''.$this->l('KB max.').' '.$this->l('If larger than the image size setting, the image will be reduced to ').' '.$largeSceneImageType['width'].'x'.$largeSceneImageType['height'].'px '.$this->l('(width x height). If smaller than the image-size setting, a white background will be added in order to achieve the correct image size.').'.<br />'.$this->l('Note: To change image dimensions, please change the \'large_scene\' image type settings to the desired size (in Back Office > Preferences > Images).').'</p>';
					
	if ($obj->id && file_exists(_PS_SCENE_IMG_DIR_.$obj->id.'-large_scene.jpg'))
	{

		echo '<img id="large_scene_image" style="clear:both;border:1px solid black;" alt="" src="'._THEME_SCENE_DIR_.$obj->id.'-large_scene.jpg" /><br />';
		
		echo '
					<div id="ajax_choose_product" style="display:none; padding:6px; padding-top:2px; width:600px;">
						'.$this->l('Begin typing the first letters of the product name, then select the product from the drop-down list:').'<br /><input type="text" value="" id="product_autocomplete_input" /> <input type="button" class="button" value="'.$this->l('OK').'" onclick="$(this).prev().search();" /><input type="button" class="button" value="'.$this->l('Delete').'" onclick="undoEdit();" />
					</div>
			';
		
		echo '
					<link rel="stylesheet" type="text/css" href="'.__PS_BASE_URI__.'css/jquery.autocomplete.css" />
					<link rel="stylesheet" type="text/css" href="'.__PS_BASE_URI__.'js/jquery/imgareaselect/imgareaselect-default.css" />
					<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/jquery.autocomplete.js"></script>
					<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/jquery.hotkeys-0.7.8-packed.js"></script>
					<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/imgareaselect/jquery.imgareaselect.pack.js"></script>
					<script type="text/javascript" src="'.__PS_BASE_URI__.'js/admin-scene-cropping.js"></script>
		';
				
		echo '</div>';


 	echo '<label>'.$this->l('Alternative thumbnail:').' </label>
				<div class="margin-form">
					<input type="file" name="thumb" id="thumb_input" />&nbsp;&nbsp;'.$this->l('(optional)').'
					<p>'.$this->l('If you want to use a thumbnail other than one generated from simply reducing the mapped image, please upload it here.').'<br />'.$this->l('Format:').' JPG, GIF, PNG. '.$this->l('Filesize:').' '.($this->maxImageSize / 1000).''.$this->l('Kb max.').' '.$this->l('Automatically resized to').' '.$thumbSceneImageType['width'].'x'.$thumbSceneImageType['height'].'px '.$this->l('(width x height)').'.<br />'.$this->l('Note: To change image dimensions, please change the \'thumb_scene\' image type settings to the desired size (in Back Office > Preferences > Images).').'</p>
					';
	if ($obj->id && file_exists(_PS_SCENE_IMG_DIR_.'thumbs/'.$obj->id.'-thumb_scene.jpg'))
		echo '<img id="large_scene_image" style="clear:both;border:1px solid black;" alt="" src="'._THEME_SCENE_DIR_.'thumbs/'.$obj->id.'-thumb_scene.jpg" /><br />';
	echo '</div>
			 ';
					
		echo '<label>'.$this->l('Category:').' </label>
				<div class="margin-form">
					<div style="overflow: auto; min-height: 300px; padding-top: 0.6em;" id="categoryList">
						<table cellspacing="0" cellpadding="0" class="table" style="width: 29.5em;">
								<tr>
									<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'categoryBox[]\', this.checked)" /></th>
									<th>'.$this->l('ID').'</th>
									<th>'.$this->l('Image map name:').'</th>
								</tr>';
					$categories = Category::getCategories(intval($cookie->id_lang), false);
					$done = array();
					$index = array();
					$indexedCategories =  isset($_POST['categoryBox']) ? $_POST['categoryBox'] : ($obj->id ? Scene::getIndexedCategories($obj->id) : array());
					foreach ($indexedCategories AS $k => $row)
						$index[] = $row['id_category'];
					$this->recurseCategoryForInclude($index, $categories, $categories[0][1], 1, null);
			echo '</table>
						<p style="padding:0px; margin:0px 0px 10px 0px;">'.$this->l('Mark all checkbox(es) of the categories for which the image map is to appear.').'<sup> *</sup></p>
					</div>
				</div>';
				
				
				
				

		echo '
					<div id="save_scene" class="margin-form" '.(($obj->id && file_exists(_PS_SCENE_IMG_DIR_.$obj->id.'-large_scene.jpg')) ? '' : 'style="display:none;"') .'>
						<input type="submit" value="'.$this->l('Save Image Map(s)').'" class="button" />
					</div>';
	} else {
	echo '
					<br/><span class="bold">'.$this->l('Please add a picture to continue mapping the image...').'</span><br/><br/>';
	}
	echo '<div class="small"><sup>*</sup> '.$this->l('Required field').'</div>
			</fieldset>
		</form>';
	}
}

?>
