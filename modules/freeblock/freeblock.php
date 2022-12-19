<?php

/**
  * Free block class, freeblock.php
  * Free block Module
  *
  * @author Atwill Europe <contact@atwill-europe.com>
  * @copyright Atwill Europe <www.atwill-europe.com>
  * @version 1.2
  *
  * History:
  * 1.0 - Initial version - Free contribution to Prestashop project
  * 1.1 - Added german translation (by Henrik Pantle <pantle@gmx.de>)
  * 1.2 - Added possibility of deleting free block for a specific language by saving empty fields
  */
	
class FreeBlock extends Module
{
	/* @var boolean error */
	protected $error = false;
	
	
	/**
	* Module constructor
	*/	
	public function __construct()
	{
	 	$this->name = 'freeblock';
	 	$this->tab = 'Blocks';
	 	$this->version = '1.1';

	 	parent::__construct();

        	$this->displayName = $this->l('Free block');
        	$this->description = $this->l('Adds a block with free content');
		$this->confirmUninstall = $this->l('Are you sure that you want to delete your free blocks?');
	}
	
	
	/**
	* Module installer
	* Create tables
	*/	
	public function install()
	{
	 	if (parent::install() == false OR $this->registerHook('leftColumn') == false)
	 		return false;
		$query = 'CREATE TABLE '._DB_PREFIX_.'freeblock (`id_lang` int(2) NOT NULL, `title` varchar(255) NOT NULL, `content` text NOT NULL, PRIMARY KEY(`id_lang`)) ENGINE=MyISAM default CHARSET=utf8';
	 	if (!Db::getInstance()->Execute($query))
	 		return false;
	 	return true;
	}
	
	/**
	* Module uninstaller
	* Drops tables
	*/	
	public function uninstall()
	{
	 	if (parent::uninstall() == false)
	 		return false;
	 	if (!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'freeblock'))
	 		return false;
	 	return true;
	}
	
	/**
	* Left column hook
	*/	
	public function hookLeftColumn($params)
	{
	 	global $cookie, $smarty;
	 	$freeblock = $this->getFreeblock($cookie->id_lang);

		$smarty->assign(array(
			'title' => $freeblock['title'],
			'freeblock_content' => $freeblock['content']
		));		
	 	if (!$freeblock)
			return false;
		return $this->display(__FILE__, 'freeblock.tpl');
	}
	
	/**
	* Right column hook
	*/	
	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}

	/**
	* Retrieves free block content for specific language
	*
	* @param integer $lang Specific language
	* @return string Free block content	
	*/
	public function getFreeblock($lang)
	{
		$result = array();
	 	/* Get content */
	 	if (!$result = Db::getInstance()->getRow('SELECT `title`, `content` FROM '._DB_PREFIX_.'freeblock WHERE `id_lang`='.$lang))
	 		return false;
	 	return $result;
	}
	
	/**
	* Adds a new free block content in db or updates an existing one
	*/	
	public function addFreeblock()
	{
	 	/* Multilingual freeblock */
	 	$languages = Language::getLanguages();
	 	$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
	 	if (!$languages)
	 		return false;
	 	foreach ($languages AS $language)
	 	 	if ((!empty($_POST['content_'.$language['id_lang']])) || (!empty($_POST['title_'.$language['id_lang']])))
	 	 	{
	 	 		if (!Db::getInstance()->Execute('REPLACE INTO '._DB_PREFIX_.'freeblock VALUES ('.intval($language['id_lang']).', \''.($_POST['title_'.$language['id_lang']]).'\', \''.($_POST['content_'.$language['id_lang']]).'\')'))
	 	 			return false;
	 	 	}
	 	 	else
	 	 		if (!Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'freeblock WHERE `id_lang`='.intval($language['id_lang'])))
	 	 			return false;
	 	return true;
	}

	/**
	* Displays free block help
	*/
	private function _displayFreeblockHelp()
	{
		$this->_html .= '<b>'.$this->l('This module allows you to insert any text or HTML content on your site.').'</b><br /><br />
		'.$this->l('For each language, you can setup a title and a content for you free block, and manage its position through the standard position management page.').'
		'.$this->l('Just copy/paste your text or HTML code in the content area to have it displayed on your site.').'<br />
		'.$this->l('Once saved, a preview of your content will be displayed at the bottom of this page.').'<br /><br />
		'.$this->l('Hints:').'<br />
		'.$this->l('- A free block will be displayed in a specific language only if a content for this specific language has been entered here. Use the flags to switch from one language to another.').'<br />
		'.$this->l('- To delete a free block for a language, just empty the fields and save the block.').'<br /><br />';
	}

	
	/**
	* Back office control panel for free blocks
	*/	
	public function getContent()
   	{
	     	$this->_html = '<h2>'.$this->displayName.'</h2>';
	
	     	/* Add a free block */
	     	if (isset($_POST['submitFreeblockAdd']))
	     	{
		     	  	if ($this->addFreeblock())
		     	  		$this->_html .= $this->displayConfirmation($this->l('The free block has been added successfully'));
		     	  	else
		     	 		$this->_html .= $this->displayError($this->l('An error occured during free block creation'));
	     	}
	
		$this->_displayFreeblockHelp();
	     	$this->_displayForm();
	
	        return $this->_html;
    	}
	
	/**
	* Displays the free block and title management form
	*/	
	private function _displayForm()
	{
	 	global $cookie;
	 	/* Language */
	 	$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$divLangName = 'titlediv¤contentdiv¤previewdiv';
		
		/* Form */
	 	$this->_html .= '
		<script type="text/javascript">
			id_language = Number('.$defaultLanguage.');
		</script>	 	
	 	<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->l('Manage your free blocks').'</legend>
			<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
				<label>'.$this->l('Title:').'</label>
				<div class="margin-form">';
					foreach ($languages as $language)
					{
						/* Grab free block title */
						$freeblock = $this->getFreeblock($language['id_lang']);						
						$this->_html .= '
					<div id="titlediv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" name="title_'.$language['id_lang'].'" value="'.$freeblock['title'].'" />
					</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'titlediv', true);	
					$this->_html .= '
					<div class="clear"></div>
				</div>
				<label>'.$this->l('Content:').'</label>
				<div class="margin-form">';									
					foreach ($languages as $language)
					{
						/* Grab free block content */
						$freeblock = $this->getFreeblock($language['id_lang']);						
						$this->_html .= '
					<div id="contentdiv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<textarea name="content_'.$language['id_lang'].'" id="contentInput_'.$language['id_lang'].'" cols="100" rows="20">'.$freeblock['content'].'</textarea>
					</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'contentdiv', true);
					$this->_html .= '
					<div class="clear"></div>
				</div>			
				<div class="margin-form">';
					$this->_html .= '<input type="submit" class="button" name="submitFreeblockAdd" value="'.$this->l('Save this free block').'" id="submitFreeblockAdd" />';
					$this->_html .= '					
				</div>
				<label>'.$this->l('Preview:').'</label>
				<div class="margin-form">';
					foreach ($languages as $language)
					{
						/* Grab free block title */
						$freeblock = $this->getFreeblock($language['id_lang']);	
						$this->_html .= '
					<div id="previewdiv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">'.$freeblock['content'].'</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'previewdiv', true);
					$this->_html .= '
				</div>
			</form>
		</fieldset>';
	}
}
?>
