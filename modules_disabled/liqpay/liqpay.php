<?php

class liqpay extends PaymentModule
{
    private $_html = '';
    private $_postErrors = array();

    public function __construct()
    {
        $this->name = 'liqpay';        
        $this->tab = 'Payment';
        $this->version = 1.2;
        
        $this->currencies = true;
        $this->currencies_mode = 'radio';
        
        $config = Configuration::getMultiple(array('LIQPAY_MERCHANT_PASS', 'LIQPAY_MERCHANT_ID'));    
        if (isset($config['LIQPAY_MERCHANT_PASS']))
            $this->liqpay_merchant_pass = $config['LIQPAY_MERCHANT_PASS'];
        if (isset($config['LIQPAY_MERCHANT_ID']))
            $this->liqpay_merchant_id = $config['LIQPAY_MERCHANT_ID'];
            
        parent::__construct();
        
        /* The parent construct is required for translations */
        $this->page = basename(__FILE__, '.php');
        $this->displayName = 'LiqPay';
        $this->description = $this->l('Accept payments with LiqPay');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
        
        if (!isset($this->liqpay_merchant_pass) OR !isset($this->liqpay_merchant_id))
            $this->warning = $this->l('Your LiqPay account must be set correctly (specify a password and a unique id merchant');
    }        

    function install()
    {        
        if (!parent::install() OR !$this->registerHook('payment'))
            return false;
        return true;
    }
    
    function uninstall()
    {
        if (!Configuration::deleteByName('LIQPAY_MERCHANT_PASS') OR !Configuration::deleteByName('LIQPAY_MERCHANT_ID') OR !parent::uninstall())
            return false;
        return true;
    }
    
    private function _postValidation()
    {
        if (isset($_POST['btnSubmit']))
        {
            if (empty($_POST['merchant_id']))
                $this->_postErrors[] = $this->l('Merchant ID is required');
            elseif (empty($_POST['merchant_pass']))
                $this->_postErrors[] = $this->l('Merchant password is required.');
        }
    }

    private function _postProcess()
    {
        if (isset($_POST['btnSubmit']))
        {
            Configuration::updateValue('LIQPAY_MERCHANT_ID', $_POST['merchant_id']);
            Configuration::updateValue('LIQPAY_MERCHANT_PASS', $_POST['merchant_pass']);
        }
        $this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('OK').'" /> '.$this->l('Settings updated').'</div>';
    }
    
    private function _displayLiqpay()
    {
        $this->_html .= '<img src="../modules/liqpay/LiqPay.gif" style="float:left; margin-right:15px;"><b>'.$this->l('This module allows you to accept payments by LiqPay.').'</b><br /><br />
        '.$this->l('You need to register on the site').' <a href="https://liqpay.com" target="blank">liqpay.com</a> <br /><br /><br />';
    }
    
    private function _displayForm()
    {
        $this->_html .=
        '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
            <fieldset>
            <legend><img src="../img/admin/contact.gif" />'.$this->l('Contact details').'</legend>
                <table border="0" width="500" cellpadding="0" cellspacing="0" id="form">
                    <tr><td colspan="2">'.$this->l('Please specify the password and a unique id merchant registered in the LiqPay system').'.<br /><br /></td></tr>
                    <tr><td width="140" style="height: 35px;">'.$this->l('Merchant ID').'</td><td><input type="text" name="merchant_id" value="'.htmlentities(Tools::getValue('merchant_id', $this->liqpay_merchant_id), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
                    <tr><td width="140" style="height: 35px;">'.$this->l('Merchant password').'</td><td><input type="text" name="merchant_pass" value="'.htmlentities(Tools::getValue('merchant_pass', $this->liqpay_merchant_pass), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
                    <tr><td colspan="2" align="center"><br /><input class="button" name="btnSubmit" value="'.$this->l('Update settings').'" type="submit" /></td></tr>
                </table>
            </fieldset>
        </form>';
    }

    function getContent()
    {
        $this->_html = '<h2>'.$this->displayName.'</h2>';

        if (!empty($_POST))
        {
            $this->_postValidation();
            if (!sizeof($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors AS $err)
                    $this->_html .= '<div class="alert error">'. $err .'</div>';
        }
        else
            $this->_html .= '<br />';

        $this->_displayLiqpay();
        $this->_displayForm();

        return $this->_html;
    }

    function hookPayment($params)
    {
        global $smarty;
        
        $delivery = new Address(intval($params['cart']->id_address_delivery));
        $invoice = new Address(intval($params['cart']->id_address_invoice));
        $customer = new Customer(intval($params['cart']->id_customer));                
                
        $return_url    = 'http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/liqpay/validation.php';            
        $currency = $this->getCurrency();
        $amount         = number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 3), $currency), 2, '.', '');
        $order_id      = $_SERVER['SERVER_NAME'].'_'.$params['cart']->id;
        $description   = 'Payment of goods on the site '.$_SERVER['SERVER_NAME'];               

        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <request>
                    <version>1.2</version>
                    <result_url>'.$return_url.'</result_url>
                    <server_url>'.$return_url.'</server_url>
                    <merchant_id>'.Configuration::get('LIQPAY_MERCHANT_ID').'</merchant_id>
                    <order_id>'.$order_id.'</order_id>
                    <amount>'.$amount.'</amount>
                    <currency>'.$currency->iso_code.'</currency>
                    <description>'.$description.'</description>
                </request>';
        $merchant_pass = Configuration::get('LIQPAY_MERCHANT_PASS');
        $operation_xml = base64_encode($xml);
        $signature = base64_encode(sha1($merchant_pass.$xml.$merchant_pass, 1));        
                
        $smarty->assign(array( 
            'liqpayUrl'         => 'https://liqpay.com/?do=clickNbuy',
            'operation_xml'     => $operation_xml,
            'signature'         => $signature,        
            'this_path'         => $this->_path,
            'this_path_ssl'     => Configuration::get('PS_FO_PROTOCOL').$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/{$this->name}/"));

        return $this->display(__FILE__, 'liqpay.tpl');
    }
    
    public function getL($key)
    {
        $translations = array(
            'success'=> 'The LiqPay transaction is carried out successfully.',
            'wait_secure'=> 'The LiqPay transaction is processed.',
            'failure'=> 'The LiqPay transaction is refused.'
        );
        return $translations[$key];
    }
    
}

?>