<?php
class UniPaySystem extends ObjectModel
{
	public  $id;
	public  $active = 1;
	public  $position;
	public  $date_add;
	public  $date_upd;

	public  $name;
	public  $description_short;
	public  $description;

	public  $image_dir;

	protected $tables = array ('universalpay_system', 'universalpay_system_lang');

	protected 	$fieldsRequired = array('active');
 	protected 	$fieldsSize = array('active' => 1);
 	protected 	$fieldsValidate = array('active' => 'isBool');
	protected 	$fieldsRequiredLang = array('name', 'description_short');
 	protected 	$fieldsSizeLang = array('name' => 128, 'description_short' => 255);
 	protected 	$fieldsValidateLang = array('name' => 'isCatalogName', 'description_short' => 'isCatalogName', 'description' => 'isCleanHtml');

	protected 	$table = 'universalpay_system';
	protected 	$identifier = 'id_universalpay_system';

	public		$id_image = 'default';

	public function __construct($id = NULL, $id_lang = NULL){
		$this->image_dir=_PS_IMG_DIR_.'pay/';
		parent::__construct($id, $id_lang);
	}
	
	public function getFields()
	{
		parent::validateFields();
		$fields['id_universalpay_system'] = (int)($this->id);
		$fields['active'] = (int)($this->active);
		$fields['position'] = (int)($this->position);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
		return $fields;
	}

	public function getTranslationsFieldsChild()
	{
		self::validateFieldsLang();

		$fieldsArray = array('name');
		$fields = array();
		$languages = Language::getLanguages(false);
		$defaultLanguage = Configuration::get('PS_LANG_DEFAULT');
		foreach ($languages as $language)
		{
			$fields[$language['id_lang']]['id_lang'] = $language['id_lang'];
			$fields[$language['id_lang']][$this->identifier] = (int)($this->id);
			$fields[$language['id_lang']]['description_short'] = (isset($this->description_short[$language['id_lang']])) ? pSQL($this->description_short[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['description'] = (isset($this->description[$language['id_lang']])) ? pSQL($this->description[$language['id_lang']], true) : '';
			foreach ($fieldsArray as $field)
			{
				if (!Validate::isTableOrIdentifier($field))
					die(Tools::displayError());

				/* Check fields validity */
				if (isset($this->{$field}[$language['id_lang']]) AND !empty($this->{$field}[$language['id_lang']]))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']]);
				elseif (in_array($field, $this->fieldsRequiredLang))
				{
					if ($this->{$field} != '')
						$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage]);
				}
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
	}

	public static function getPaySystems($id_lang, $active = true)
	{
	 	if (!Validate::isBool($active))
	 		die(Tools::displayError());

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'universalpay_system` us
		LEFT JOIN `'._DB_PREFIX_.'universalpay_system_lang` usl ON us.`id_universalpay_system` = usl.`id_universalpay_system`
		WHERE `id_lang` = '.(int)($id_lang).
		($active ? ' AND `active` = 1' : '').'
		ORDER BY us.`position` ASC'
		);
		return $result;
	}
}


