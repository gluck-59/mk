<?php

/**
  * Tools tab for admin panel, AdminTools.php
  * @category admin
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.3
  *
  */

include_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');

class AdminBackup extends AdminTab
{
	public function postProcess()
	{
	}
	
	public function display()
	{
		echo '<fieldset><legend><img src="../img/admin/tab-tools.gif" />'.$this->l('Sypex Dumper').'</legend>';
		echo '		<form style="float:right; width:200px; margin:15px; text-align:center;">
			<fieldset>
				<a href="http://prestalab.ru/"><img src="http://prestalab.ru/upload/banner.png" alt="Модули и шаблоны для PrestaShop" width="174px" height="100px" /></a>
			</fieldset>
		</form>';
		echo '<br />';
		echo '<iframe src="sxd/" width="586" height="462" frameborder="0" style="margin:0;"></iframe>';
		echo '</fieldset>';
	}
}

?>