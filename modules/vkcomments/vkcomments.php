<?php

class VKComments extends Module
{

    function __construct()
    {
        $this->name = 'vkcomments';
        $this->tab = 'Products';
        $this->version = '0.1';

        parent::__construct();

        $this->displayName = $this->l('Комментарии ВКонтакте');
        $this->description = $this->l('Позволяет пользователям комментировать продукты при помощи виджета ВКонтакте');
    }

    function install()
    {
			if (parent::install() == false 
				OR $this->registerHook('productTab') == false
				OR $this->registerHook('productTabContent') == false)
			return (false);
		return (true);
    }


	public function getContent()
	{
		$output =  '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitVKComments') AND ($vklimit = Tools::getValue('VKCOMMENTS_LIMIT',10)))
		{
			Configuration::updateValue('VKCOMMENTS_LIMIT', $vklimit);
			$output .= '
			<div class="conf confirm">
				<img src="../img/admin/ok.gif" alt="" title="" />
				'.$this->l('Настройки обновлены').'
			</div>';
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		return '
		<form style="float:right; width:200px; margin:15px; text-align:center;">
			<fieldset>
				<a href="http://prestalab.ru/"><img src="http://prestalab.ru/upload/banner.png" alt="Модули и шаблоны для PrestaShop" width="174px" height="100px" /></a>
			</fieldset>
		</form>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset class="width3">
				<label>'.$this->l('Количество комментариев на странице').'</label>
				<div class="margin-form">
					<input type="text" name="VKCOMMENTS_LIMIT" value="'.Tools::getValue('VKCOMMENTS_LIMIT', Configuration::get('VKCOMMENTS_LIMIT')).'" />
					<p class="clear">целое число 5-100</p>
				</div>
				<center><input type="submit" name="submitVKComments" value="'.$this->l('Обновить').'" class="button" /></center>
			</fieldset>
		</form>
';
	}


	
	public function hookProductTab($params)
	{

		return ($this->display(__FILE__, '/tab.tpl'));
	}


    public function hookProductTabContent($params)
    {
		global $smarty;
		$smarty->assign(array(
			'vklimit' => Configuration::get('VKCOMMENTS_LIMIT'),
			'vkid' => Tools::getValue('id_product',0)
		));
		return ($this->display(__FILE__, '/vkcomments.tpl'));
	}

}

?>