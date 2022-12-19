<?php
class OrliqueInvoice extends ObjectModel {
	public $id;
	public $id_order;
	public $content;
	public $active;
    
    protected $fieldsValidate = array(
        'id_order' => 'isUnsignedInt'
    );
    
    protected $fieldsRequiredLang = array(
        'content'
    );
    
    protected $fieldsValidateLang = array(
        'content' => 'isMessage'
    );

    protected $table = 'orlique_invoice';
	protected $identifier = 'id_orlique_invoice';
    
	public function getFields() 
	{ 
		parent::validateFields();
        
        if (Validate::isLoadedObject($this))
            $fields[$this->identifier] = (int)$this->id;
        
		$fields['id_order']        = (int)($this->id_order);
		$fields['active']          = (int)($this->active);
        
		return $fields;	 
	}
    
	public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();

		$fields          = array();
		$languages       = Language::getLanguages(false);
		$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
        
		foreach ($languages as $language)
		{
			$fields[$language['id_lang']]['id_lang']         = (int)($language['id_lang']);
			$fields[$language['id_lang']][$this->identifier] = (int)($this->id);
			$fields[$language['id_lang']]['content']         = (isset($this->content[$language['id_lang']])) ? pSQL($this->content[$language['id_lang']], true) : '';
		}
        
		return $fields;
	}
    
    public static function getInvoiceByOrderId($orderId)
    {
        if ( ! Validate::isUnsignedId($orderId))
            return false;
        
        return Db::getInstance()->getValue('
            SELECT `id_orlique_invoice`
            FROM `' . _DB_PREFIX_ . 'orlique_invoice`
            WHERE `id_order` = ' . (int)$orderId
        );
    }
    
    public static function orderHasInvoice($orderId)
    {
        if ( ! Validate::isUnsignedId($orderId) || ! Validate::isLoadedObject($order = new Order((int)$orderId)))
            return false;
        
        return ($order->invoice && $order->invoice_number);
    }
}
?>