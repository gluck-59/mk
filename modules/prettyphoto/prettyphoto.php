<?php

/*Copyright 2010 maofree  email: msecco@gmx.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 3, as 
published by the Free Software Foundation.

This file can't be removed. This module can't be sold.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

*/

if(version_compare(_PS_VERSION_, '1.3.1.1', '>')) {
	if (!defined('_CAN_LOAD_FILES_'))
		exit;
}

class prettyPhoto extends Module
{
	function __construct()
	{
		$this->name = 'prettyphoto';
		$this->tab = 'Products';
		$this->version = 1.0;
		
		parent::__construct();

		$this->displayName = $this->l('prettyPhoto');
		$this->description = $this->l('Thickbox replacement in the products page');
	}

	public function install()
	{
		if (
		   !parent::install() ||
			!$this->registerHook('productFooter')
		)
			return false;
			
			$filename = _PS_THEME_DIR_.'/product.tpl';
			$file = @fopen($filename, 'r');
			$content = @fread($file, filesize($filename));
         if (strpos($content, 'rel="other-views"', 50) !== false)
    		   $content = str_replace('rel="other-views"', 'rel="prettyPhoto[mao]"', $content);
			@fclose($file);
			$file = @fopen($filename, 'w');
			@fwrite($file, $content);
			@fclose($file);		
						
		return true;
	}
	
   public function uninstall()
   {
      if (!parent::uninstall())
         return false;
           
			$filename = _PS_THEME_DIR_.'/product.tpl';
			$file = @fopen($filename, 'r');
			$content = @fread($file, filesize($filename));
         if (strpos($content, 'rel="prettyPhoto[mao]"', 50) !== false)
		      $content = str_replace('rel="prettyPhoto[mao]"', 'rel="other-views"', $content);
			@fclose($file);
			$file = @fopen($filename, 'w');
			@fwrite($file, $content);
			@fclose($file);
		                
      return true;
   }
   
	public function hookProductFooter($params)
	{
		return $this->display(__FILE__, 'prettyphoto.tpl');
	}   
   
}

?>