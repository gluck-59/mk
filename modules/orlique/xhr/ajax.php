<?php
require_once (dirname(__FILE__) . '/../../../config/config.inc.php');
require_once (dirname(__FILE__) . '/../orlique.php');

function paramStrDecode($string)
{
    $prepared = array();
    
    $tmp1 = explode('&', $string);
    
    if (sizeof($tmp1))
    {
        foreach ($tmp1 as $value)
        {
            $value = explode('=', $value);
            
            if (substr($value[0], strlen($value[0]) - 2) === '[]')
                $prepared[substr($value[0], 0, -2)][] = $value[1];
            else
                $prepared[$value[0]] = $value[1];
        }
    }
    
    return $prepared;
}

function getStatesInCountry($countryId)
{
    $errors = array();
    
    if ( ! Validate::isUnsignedId($countryId))
            $errors[] = Tools::displayError('Invalid Country ID');
    
    if ( ! sizeof($errors))
    {
        if (method_exists('State', 'getStatesByIdCountry'))
            $states = State::getStatesByIdCountry((int)$countryId);
        else
            $states = Db::getInstance()->ExecuteS('
                SELECT *
                FROM `' . _DB_PREFIX_ . 'state`
                WHERE `id_country` = ' . (int)$countryId
            );
            
        if (sizeof($states))
            return OrliqueHelper::jsonEncode(array('states' => $states));
    }
    
    if (sizeof($errors))
        return OrliqueHelper::jsonEncode(array('errors' => $errors));
        
    return null;
}

if (Employee::checkPassword(Tools::getValue('iem'), Tools::getValue('iemp')))
{
    if (class_exists('Cookie'))
        $cookie = new Cookie('psOrderEdit');
    
    $id_lang         = Tools::getValue('id_lang');
    $cookie->id_lang = $id_lang;
    $cookie->id_employee = (int)Tools::getValue('iem');
    $cookie->passwd = Tools::getValue('iemp');
    Tools::setCookieLanguage();
    
    $editorInstance  = new Orlique();
    
    if (Tools::getIsset('q'))
    {
        $query = Tools::getValue('q', false);
        
        if (Tools::getValue('type') == 1)
            $editorInstance->ajaxProductList($id_lang, $query);
        else
            $editorInstance->ajaxCustomerList($query);
    }
    elseif (Tools::getIsset('customerCreate'))
    {
        $editorInstance->displayCustomerCreationForm($id_lang);
    }
    elseif (Tools::getIsset('customerCreateSave'))
    {
        die($editorInstance->saveCustomer($_POST));
    }
    elseif (Tools::getIsset('getStates'))
    {
        die(getStatesInCountry((int)Tools::getValue('getStates')));
    }
    elseif (Tools::getIsset('getfull'))
    {
        $productId = Tools::getValue('getfull', false);
        $customer = Tools::getValue('customer', null);
        
        $cookie->id_customer = $customer;
        
        $editorInstance->ajaxFullProductInfo($id_lang, $productId);
    }
    elseif (Tools::getIsset('getcustomer'))
    {
        $customerId = Tools::getValue('getcustomer', false);
        $editorInstance->ajaxFullCustomerInfo($id_lang, $customerId, Tools::getValue('iem', false));
    }
    elseif (Tools::getIsset('order_update'))
    {
        die($editorInstance->updateOrder($_POST));
    }
    elseif (Tools::getIsset('getMessages'))
    {
        die(OrliqueHelper::getOrderMessages(Tools::getValue('getMessages')));
    }
    elseif (Tools::getIsset('addMessage'))
    {
        die($editorInstance->addOrderMessage($_POST));
    }
    elseif (Tools::getIsset('markMessageRead'))
    {
        die($editorInstance->markMessageRead($_POST['id_employee'], $_POST['message_id']));
    }
    elseif (Tools::getIsset('addProduct'))
    {
        $id_product           = Tools::getValue('addProduct');
        $id_product_attribute = Tools::getValue('id_combination', null);
        $id_order             = Tools::getValue('oid', null);
        $index                = Tools::getValue('index', 0);
        $id_currency          = Tools::getValue('currency');
        $address_invoice      = Tools::getValue('address_invoice', null);
        $address_delivery     = Tools::getValue('address_delivery', null);
        $id_customer          = Tools::getValue('customer', null);
        
        $id_product_attribute = (int)$id_product_attribute > 0 ? (int)$id_product_attribute : null;
        
        if (class_exists('Cart'))
        {
            $cart = new Cart();
            $cart->id_currency         = (int)$id_currency;
            $cart->id_lang             = $id_lang;
            $cart->id_carrier          = null;
            $cart->id_customer         = $id_customer;
            $cart->id_address_invoice  = $address_invoice;
            $cart->id_address_delivery = $address_delivery;
        }
        
        if (isset($cookie))
            $cookie->id_customer = $id_customer;
        
        Orlique::addProductToOrder($id_order, $index, $id_product, $id_lang, $id_currency, $id_product_attribute);
    }
    elseif (Tools::getIsset('deleteOrderId'))
    {
        $orderDetail = intval(Tools::getValue('deleteOrderId'));
        
        if (OrliqueHelper::orderDetailIsDeletable($orderDetail))
        {
            $detailObj   = new OrderDetail($orderDetail);
            
            if (Validate::isLoadedObject($detailObj))
            {
                $productId       = $detailObj->product_id;
                $attribute       = isset($detailObj->product_attribute_id) && $detailObj->product_attribute_id != 0 ? $detailObj->product_attribute_id : null;
                $quantity        = $detailObj->product_quantity;
                
                Product::updateQuantity(array(
                        'id_product'           => $productId,
                        'id_product_attribute' => $attribute,
                        'quantity'             => intval($quantity) * -1,
                    )
                );
                
                $detailObj->delete();
            }
        }
    }
    elseif (Tools::getIsset('calculateShipping'))
    {
        die($editorInstance->getShippingPrice($_POST));
    }
    elseif (Tools::getIsset('getCurrency'))
    {
        die(OrliqueHelper::getCurrencyDetails(Tools::getValue('getCurrency')));
    }
    elseif (Tools::getIsset('orliqueInvoice'))
    {
        $data = paramStrDecode(Tools::getValue('invoiceData'));
        $orderID = Tools::getValue('orderId');
        
        die($editorInstance->saveOrliqueInvoice((int)$orderID, $data));
    }
} 
else 
{
    die('Please log in first');    
}
?>