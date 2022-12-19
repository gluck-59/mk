<?php

class vkapi extends Module
{

    function __construct()
    {
        $this->name = 'vkapi';
        $this->tab = 'Tools';
        $this->version = '0.1';

        parent::__construct();

        $this->displayName = $this->l('ВКонтакте Open API');
        $this->description = $this->l('Инициализация виджетов ВКонтакте');
    }

    function install()
    {
			if (!parent::install() 
				OR !$this->registerHook('header'))
			return (false);
		return (true);
    }
    
	function uninstall()
	{
		return (
			parent::uninstall() AND
			Configuration::deleteByName('vk_apiId')
		);
	}

	public function getContent()
	{
		$output =  '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submit'))
		{
			Configuration::updateValue('vk_apiId', Tools::getValue('vk_apiId'));
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
				<label>'.$this->l('Ваш API ID').'</label>
				<div class="margin-form">
					<input type="text" name="vk_apiId" value="'.Tools::getValue('vk_apiId', Configuration::get('vk_apiId')).'" />
				</div>
				<center><input type="submit" name="submit" value="'.$this->l('Обновить').'" class="button" /></center>
			</fieldset>
		</form>
';
	}

  public function hookHeader($params)
  {

  // асинхронный способ 
  return '<script src="https://vk.com/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
  <div id="vk_api_transport"></div>
<script type="text/javascript">
  window.vkAsyncInit = function() {
    VK.init({
      apiId: '.Configuration::get('vk_apiId').'
    });
  };

  setTimeout(function() {
    var el = document.createElement("script");
    el.type = "text/javascript";
    el.src = "//vk.com/js/api/openapi.js";
    el.async = true;
    document.getElementById("vk_api_transport").appendChild(el);
  }, 0);
</script>
';

  // синхронный способ
/*    return '<script src="https://vk.com/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
            
            <script type="text/javascript">VK.init({apiId: '.Configuration::get('vk_apiId').', onlyWidgets: true});
            
            setTimeout(function() {
    var el = document.createElement("script");
    el.type = "text/javascript";
    el.src = "//vk.com/js/api/openapi.js";
    el.async = true;
    document.getElementById("vk_api_transport").appendChild(el);
  }, 0);</script>';
  */
	
	}

}

?>