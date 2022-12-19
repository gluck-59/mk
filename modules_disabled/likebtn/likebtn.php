<?php

class likebtn extends Module
{
    private $_settings = array();
    function __construct()
    {
        $this->name = 'likebtn';
        $this->tab = 'Products';
        $this->version = '0.1';

        parent::__construct();

        $this->displayName = $this->l('Кнопки Мне Нравится');
        $this->description = $this->l('Кнопки Мне Нравится с различных сервисов');
        $this->_settings=unserialize(Configuration::get('likebtn'));
    }

    function install()
    {
			if (!parent::install()
				OR !$this->registerHook('productfooter'))
			return (false);
		return (true);
    }
    
	function uninstall()
	{
		return (
			parent::uninstall() AND
			Configuration::deleteByName('likebtn')
		);
	}

	public function getContent()
	{
		$output =  '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submit'))
		{
      $this->_settings['vk']= Tools::getValue('vk');
      $this->_settings['od']= Tools::getValue('od');
      $this->_settings['mail']= Tools::getValue('mail');
			Configuration::updateValue('likebtn', serialize($this->_settings));
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
				<legend><img src="../img/admin/cog.gif" alt="" class="middle" />'.$this->l('Настройки').'</legend>
				Настройки отключены, все кнопки берутся с <a href="http://api.yandex.ru/share/" target="_blank">http://api.yandex.ru/share/</a><br>
				<label>'.$this->l('Вконтакте').'</label>
				<div class="margin-form">
					<input type="checkbox" name="vk" value="1" '.(Tools::getValue('vk', $this->_settings['vk']) ? 'checked="checked" ' : '').'/>
          <p class="clear">Кнопка Мне нравится</p>
				</div>
				<label>'.$this->l('Одноклассники').'</label>
				<div class="margin-form">
					<input type="checkbox" name="od" value="1" '.(Tools::getValue('od', $this->_settings['od']) ? 'checked="checked" ' : '').'/>
          <p class="clear">Кнопка Класс</p>
				</div>
				<label>'.$this->l('Mail.Ru').'</label>
				<div class="margin-form">
					<input type="checkbox" name="mail" value="1" '.(Tools::getValue('mail', $this->_settings['mail']) ? 'checked="checked" ' : '').'/>
          <p class="clear">Кнопка Нравится</p>
				</div>
				<center><input type="submit" name="submit" value="'.$this->l('Обновить').'" class="button" /></center>
			</fieldset>
		</form>
';
	}
  public function hookproductfooter($params)
  {
		global $smarty;
		$smarty->assign(array(
			'likebtn' => $this->_settings
		));
		return ($this->display(__FILE__, '/likebtn.tpl'));
	}
  public function hookextraLeft($params)
  {
		return $this->hookproductfooter($params);
	}
	
  public function hookextraRight($params)
  {
		return $this->hookproductfooter($params);
	}

}

?>