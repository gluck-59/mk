<?php

class vkgroups extends Module {

    function __construct() {
        $this->name = 'vkgroups';
        $this->tab = 'Blocks';
        $this->version = 0.1;

        parent::__construct();

        $this->displayName = $this->l('Виджет сообществ ВКонтакте');
        $this->description = $this->l('Виждет сообществ ВКонтакте на страницах магазина');
    }

    function install() {
        return (parent::install() AND $this->registerHook('leftColumn'));
    }

    function hookLeftColumn($params) {
        global $smarty;


            $smarty->assign(array(
                'vk_groups_mode' => (int) !Configuration::get('VK_GROUPS_MODE'),
                'vk_groups_id' => (int) Configuration::get('VK_GROUPS_ID')
            ));

        $display = $this->display(__FILE__, 'vkgroups.tpl');
        return $display;
    }

    function hookRightColumn($params) {
        return $this->hookLeftColumn($params);
    }

    function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitvkgroups')) {
            Configuration::updateValue('VK_GROUPS_MODE', (int) (Tools::getValue('vk_groups_mode')));
            Configuration::updateValue('VK_GROUPS_ID', (int) (Tools::getValue('vk_groups_id')));
            $output .= $this->displayConfirmation($this->l('Настройки обновлены'));
        }
        return $output . $this->displayForm();
    }

    public function displayForm() {
        $output = '
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
			<fieldset><legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('Настройки') . '</legend>
				<label>' . $this->l('Номер группы') . '</label>
				<div class="margin-form">
					<input type="text" name="vk_groups_id" id="vk_groups_id" value="'.Tools::getValue('vk_groups_id', Configuration::get('VK_GROUPS_ID')).'" />
				</div>
				<label>' . $this->l('Показывать участников') . '</label>
				<div class="margin-form">
					<input type="radio" name="vk_groups_mode" id="vk_groups_mode_on" value="1" ' . (Tools::getValue('vk_groups_mode', Configuration::get('VK_GROUPS_MODE')) ? 'checked="checked" ' : '') . '/>
					<label class="t" for="vk_groups_mode_on"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Да') . '" title="' . $this->l('Да') . '" /></label>
					<input type="radio" name="vk_groups_mode" id="vk_groups_mode_off" value="0" ' . (!Tools::getValue('vk_groups_mode', Configuration::get('VK_GROUPS_MODE')) ? 'checked="checked" ' : '') . '/>
					<label class="t" for="vk_groups_mode_off"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Нет') . '" title="' . $this->l('Нет') . '" /></label>
				</div>
				<center><input type="submit" name="submitvkgroups" value="' . $this->l('Сохранить') . '" class="button" /></center>
			</fieldset>
		</form>';
        return $output;
    }

}