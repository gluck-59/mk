<?php
class OrliqueHelper
{
    /*
     * Prestashop 1.2.5 didn't have a "ps_round" method, which is why we use a
     * separate method to round prices. It checks if ps_round method exists,
     * if it does, then it simply applies it, if not, we fall back to the way
     * PS 1.2.5 rounded the prices.
     *
     * @access public
     * @scope static
     *
     * @param float $price
     * @param integer $precision
     *
     * @return float;
     */
    public static function roundPrice($price, $precision)
	{
        return method_exists('Tools', 'ps_round') ? Tools::ps_round($price, $precision) : number_format($price, $precision, '.', '');
	}
    
	public static function jsonEncode($json)
	{
		if (function_exists('json_encode'))
			return json_encode($json);
		elseif (method_exists('Tools', 'jsonEncode'))
			return Tools::jsonEncode($json);
		else
		{
			if (is_null($json)) return 'null';
			if ($json === false) return 'false';
			if ($json === true) return 'true';
			if (is_scalar($json))
			{
				if (is_float($json))
				{
					// Always use "." for floats.
					return (float)(str_replace(",", ".", strval($json)));
				}

				if (is_string($json))
				{
					static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
					return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $json) . '"';
				}
				else
					return $json;
			}
			
			$isList = true;
			
			for ($i = 0, reset($json); $i < count($json); $i++, next($json))
			{
				if (key($json) !== $i)
				{
					$isList = false;
					break;
				}
			}
			
			$result = array();
			
			if ($isList)
			{
				foreach ($json as $v) $result[] = self::jsonEncode($v);
				return '[' . join(',', $result) . ']';
			}
			else
			{
				foreach ($json as $k => $v) $result[] = self::jsonEncode($k).':'.self::jsonEncode($v);
				return '{' . join(',', $result) . '}';
			}
		}
	}
    
	public static function getProductOutOfStock($id_product, $valueIfNotExists = 2)
	{
		$outOfStock = Db::getInstance()->getValue('
		SELECT `out_of_stock` FROM `' . _DB_PREFIX_ . 'product` WHERE `id_product` = ' . (int)$id_product);
		
		return $outOfStock ? (int)$outOfStock : (int)$valueIfNotExists;
	}
    
	public static function deleteProductFromOrder($id_order_detail, $restoreQuties = true)
	{
		$detailObj = new OrderDetail($id_order_detail);
		
		if (Validate::isLoadedObject($detailObj))
		{
			$id_product = $detailObj->product_id;
			
			$id_product_attribute = isset($detailObj->product_attribute_id) && $detailObj->product_attribute_id != 0 ? $detailObj->product_attribute_id : null;
			$quantity  = $detailObj->product_quantity;
			
			if ($restoreQuties)
			{
				Product::updateQuantity(array(
						'id_product'           => $id_product,
						'id_product_attribute' => $id_product_attribute,
						'quantity'             => (int)($quantity) * -1,
						'cart_quantity'        => (int)($quantity) * -1,
						'out_of_stock'         => self::getProductOutOfStock($id_product),
					),
					$detailObj->id_order
				);
			}
			
			$detailObj->delete();
		}
	}
    
	public static function updateQtyInCart(Cart $cart, $products, $delete = false)
	{
		if ($delete)
		{
			$cartProducts = $cart->getProducts();
			
			if (sizeof($cartProducts))	
				foreach ($cartProducts as $product)
					$cart->deleteProduct($product['id_product'], (isset($product['id_product_attribute']) && $product['id_product_attribute'] != 0) ? $product['id_product_attribute'] : null);
		}
		
		foreach ($products as $orderDetail)
		{
			$productId       = $orderDetail['product_id'];
			$attribute       = $orderDetail['product_attribute_id'] != 0 ? $orderDetail['product_attribute_id'] : null;
			$wantedQuantity  = $orderDetail['product_quantity'];
            
			$cart->updateQty($wantedQuantity, $productId, $attribute);
		}
	}
    
	public static function getShippingTax(Carrier $carrier, $id_address)
	{
		if (method_exists('Tax', 'getCarrierTaxRate'))
			$carrierTax = (float)Tax::getCarrierTaxRate((int)$carrier->id, (int)$id_address);
		elseif (property_exists($carrier, 'id_tax') && $carrier->id_tax)
		{
			$tax     = new Tax($carrier->id_tax);
			$id_zone = Address::getZoneById((int)($id_address));
			
			if (Validate::isLoadedObject($tax) && Tax::zoneHasTax((int)($tax->id), (int)($id_zone)) && ! Tax::excludeTaxeOption())
				$carrierTax = (float)$tax->rate;
		}
		
		return isset($carrierTax) ? (float)$carrierTax : null;
	}
    
    public static function checkCurrency($id_currency)
	{
		return (Validate::isUnsignedInt($id_currency) && Currency::getCurrency((int)$id_currency));
	}
    
	public static function getCurrencyDetails($id_currency)
	{
		if ( ! self::checkCurrency($id_currency))
			$id_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
			
		$currency = new Currency($id_currency);
		
		$currencyInfo = array(
			'currencySign'   => $currency->sign,
			'currencyRate'   => $currency->conversion_rate,
			'currencyFormat' => $currency->format,
			'currencyBlank'  => $currency->blank
		);
		
		return self::jsonEncode($currencyInfo);
	}
    
	public static function getCustomerIdByOrderId($id_order)
	{
		if ( ! Validate::isUnsignedId($id_order))
			return false;
		
		$sql = '
		SELECT `id_customer` FROM `' . _DB_PREFIX_ . 'orders` WHERE `id_order` = ' . (int)$id_order;
		
		return Db::getInstance()->getValue($sql);
	}
    
	public static function getOrderMessages($id_order)
	{
		if (Validate::isUnsignedId($id_order))
		{
			$messages = Message::getMessagesByOrderId((int)$id_order, true);
			
			return OrliqueHelper::jsonEncode(array('messages' => $messages));
		}
        
        return false;
	}
    
    public static function checkId($ids) 
    {
        if ( ! is_array($ids))
            $ids = array($ids);
            
        foreach ($ids as $id)
            if ( ! Validate::isUnsignedId($id))
                return false;
                
        return true;
    }
	
	public static function updateTable($table, $identifier, $id, $data)
	{
		return Db::getInstance()->AutoExecute(_DB_PREFIX_ . $table, $data, 'UPDATE', $identifier . ' = ' . $id);
	}
	
	public static function insertIntoTable($table, $data)
	{
		$result = Db::getInstance()->AutoExecute(_DB_PREFIX_ . $table, $data, 'INSERT');

		if ($result && $id = Db::getInstance()->Insert_ID())
			return $id;

		return false;
	}
	
	public static function getCurrentOrderDetails(Order $order)
	{
		if ( ! Validate::isLoadedObject($order))
			return false;
		
		$products = $order->getProductsDetail();
		
		if ( ! $products || ! sizeof($products))
			return false;
		
		$result = array();
		
		foreach ($products as $product)
			array_push($result, $product['id_order_detail']);
		
		return $result;
    }
	
	public static function getAmountAlreadyInOrder($id_order_detail, $id_product, $id_product_attribute = null)
	{
		$sql = '
		SELECT `product_quantity`
		FROM `' . _DB_PREFIX_ . 'order_detail`
		WHERE `id_order_detail`    = ' . (int)$id_order_detail . '
		AND `product_id`           = ' . (int)$id_product .
		((isset($id_product_attribute) && Validate::isUnsignedId($id_product_attribute)) ? ' AND `product_attribute_id` = ' . (int)$id_product_attribute : '');

		$result = Db::getInstance()->getValue($sql);
		
		return (int)$result;
	}
	
	public static function orderDetailIsDeletable($id_order_detail)
	{
		$sql = '
		SELECT `id_order`
		FROM `' . _DB_PREFIX_ . 'order_detail`
		WHERE `id_order_detail` = ' . (int)$id_order_detail;
		
		if ( ! $id_order = Db::getInstance()->getValue($sql))
			return false;
		
		$sql = '
		SELECT COUNT(*) AS `nb`
		FROM `' . _DB_PREFIX_ . 'order_detail`
		WHERE `id_order` = ' . (int)$id_order;
		
		return Db::getInstance()->getValue($sql) > 1;
	}
	
	public static function purgeOrder($id_order)
	{
		$sql = '
		DELETE FROM `' . _DB_PREFIX_ . 'order_detail` WHERE `id_order` = ' . (int)$id_order;

		Db::getInstance()->Execute($sql);
		
		if (Validate::isLoadedObject($order = new Order($id_order)))
		{
			$order->total_products = $order->total_products_wt = $order->total_paid = $order->total_paid_real = 0;
			
			return $order->update();
		}
		
		return false;
	}
	
	public static function getProductSupplierReference(Product $product, $id_product_attribute = null)
	{
		$reference = $product->supplier_reference;
		
		if (isset($id_product_attribute) && Validate::isUnsignedId($id_product_attribute) && (int)$id_product_attribute != 0)
		{
			$attributeReference = Db::getInstance()->getValue('
				SELECT `supplier_reference`
				FROM `' . _DB_PREFIX_ . 'product_attribute`
				WHERE `id_product` = ' . (int)$product->id . '
				AND `id_product_attribute` = ' . (int)$id_product_attribute);
			
			if ($attributeReference && ! Tools::isEmpty($attributeReference))
				$reference = $attributeReference;
		}
		
		return $reference;
	}
	
	public static function getProductEAN(Product $product, $id_product_attribute = null)
	{
		if ( ! property_exists($product, 'ean13'))
		{
			return false;
		}
		
		$ean = $product->ean13;
		
		if (isset($id_product_attribute) && Validate::isUnsignedId($id_product_attribute) && (int)$id_product_attribute != 0)
		{
			$attrEan = Db::getInstance()->getValue('
				SELECT `ean13`
				FROM `' . _DB_PREFIX_ . 'product_attribute`
				WHERE `id_product` = ' . (int)$product->id . '
				AND `id_product_attribute` = ' . (int)$id_product_attribute);
			
			if ($attrEan && ! Tools::isEmpty($attrEan))
				$ean = $attrEan;
		}
		
		return $ean;
	}
    
    public static function getImage($id_product, $id_product_attribute = null)
    {
		$image = array();
		
		if (isset($id_product_attribute) && Validate::isUnsignedInt($id_product_attribute))
			$image = Db::getInstance()->getRow('
				SELECT `id_image`
				FROM `' . _DB_PREFIX_ . 'product_attribute_image`
				WHERE `id_product_attribute` = ' . (int)($id_product_attribute)
			);
            
		if ( ! isset($image['id_image']) || ! $image['id_image'])
			$image = Db::getInstance()->getRow('
				SELECT `id_image`
				FROM `' . _DB_PREFIX_ . 'image`
				WHERE `id_product` = ' . (int)$id_product . '
				AND `cover`        = 1'
			);
            
        return $image;
    }
    
    public static function getImageHtmlStr($id_image, $id_product, $id_product_attribute)
    {
        if (method_exists('Image', 'getExistingImgPath'))
        {
            $imageObj = new Image((int)$id_image);
            
            $imageHtml = cacheImage(_PS_IMG_DIR_ . 'p/' . $imageObj->getExistingImgPath() . '.jpg', 'product_mini_' . (int)($id_product) . (isset($id_product_attribute) ? '_' . (int)($id_product_attribute) : '') . '.jpg', 45, 'jpg');
        }
        else
            $imageHtml = cacheImage(_PS_IMG_DIR_ . 'p/' . $id_product . '-' . (int)($id_image) . '.jpg', 'product_mini_' . $id_product . (isset($id_product_attribute) ? '_' . (int)($id_product_attribute) : '') . '.jpg', 45, 'jpg');
    
        return isset($imageHtml) ? $imageHtml : false;
    }
    
    public static function getTabToken($tab, $id_employee)
    {
        return Tools::getAdminToken($tab . (int)(Tab::getIdFromClassName($tab)) . (int)($id_employee));
    }
    
    public static function purgeObsoleteDiscounts($discountsOld, $discountsNew)
    {
        $listToDelete = array();
        
        foreach ($discountsOld as $coupon)
        {
            $found = false;
            
            foreach ($discountsNew as $id_discount => $discount)
            {
                if ($id_discount == $coupon['id_discount'])
                {
                    $found = true;
                    
                    break;
                }
            }
            
            if ( ! $found)
                array_push($listToDelete, (int)($coupon['id_order_discount']));
        }
        
        self::purgeOrderDiscounts($listToDelete);
    }
    
    public static function purgeOrderDiscounts($discounts)
    {
        if (is_array($discounts) && sizeof($discounts) > 0)
            foreach($discounts as $discount)
                if (Validate::isUnsignedId($discount))
                    Db::getInstance()->Execute('
                        DELETE FROM `' . _DB_PREFIX_ . 'order_discount`
                        WHERE `id_order_discount` = ' . (int)($discount)
                    );
    }
	
	public static function ajaxDebugVarOutput($varToOutput, $explanation = false)
	{
		$data = debug_backtrace();
		
		echo 'DEBUG VAR CALL:' . "\n" . str_repeat('=', 80) . "\n";
		echo 'Called from: ' . $data[0]['file'] . '(' . $data[0]['line'] . ')' . "\n";
		
		if ($explanation)
		{
			echo str_repeat('-', 80) . "\n" . 'Explanation provided:' . "\n";
			echo $explanation . "\n" . str_repeat('-', 80) . "\n";
		}
		
		echo 'Var dump:' . "\n";
		
		if (is_array($varToOutput) || is_object($varToOutput))
			print_r($varToOutput);
		else
		{
			var_dump($varToOutput) . "\n";
		}
		
		echo str_repeat('=', 80) . "\n\n";
	}

    public static function getOrderProducts($order) {
        $products = self::getOrderProductsDetail($order);
        ini_set('display_errors', '1');
        $resultArray = array();
        foreach ($products AS $row)
        {
            self::setOrderProductPrices($row, $order);

            /* Add information for virtual product */
            if ($row['download_hash'] AND !empty($row['download_hash']))
                $row['filename'] = ProductDownload::getFilenameFromIdProduct($row['product_id']);

            /* Stock product */
            $resultArray[(int)($row['id_order_detail'])] = $row;
        }
        return $resultArray;
    }
    public static function setOrderProductPrices(&$row, $order)
    {
        //if ($order->getTaxCalculationMethod() == PS_TAX_EXC)
            $row['product_price'] = self::roundPrice($row['product_price'], 2);
       /*
        else
            $row['product_price_wt'] = Tools::ps_round($row['product_price'] * (1 + $row['tax_rate'] / 100), 2);
*/
        /*$group_reduction = 1;
        if ($row['group_reduction'] > 0)
            $group_reduction =  1 - $row['group_reduction'] / 100;
        */

        if ($row['reduction_percent'] != 0)
        {
            //if ($order->getTaxCalculationMethod() == PS_TAX_EXC)
                $row['product_price'] = ($row['product_price'] - $row['product_price'] * ($row['reduction_percent'] * 0.01));
          /*
            else
            {
                $reduction = Tools::ps_round($row['product_price_wt'] * ($row['reduction_percent'] * 0.01), 2);
                $row['product_price_wt'] = Tools::ps_round(($row['product_price_wt'] - $reduction), 2);
            }
            */
        }

        if ($row['reduction_amount'] != 0)
        {
            //if ($order->getTaxCalculationMethod() == PS_TAX_EXC)
                $row['product_price'] = ($row['product_price'] - ($row['reduction_amount'] / (1 + $row['tax_rate'] / 100)));
           /*
            else
                $row['product_price_wt'] = Tools::ps_round(($row['product_price_wt'] - $row['reduction_amount']), 2);
        */
        }
        

        /*
        if ($row['group_reduction'] > 0)
        {
            if ($this->_taxCalculationMethod == PS_TAX_EXC)
                $row['product_price'] = $row['product_price'] * $group_reduction;
            else
                $row['product_price_wt'] = Tools::ps_round($row['product_price_wt'] * $group_reduction , 2);
        }
        */

      /*
        if (($row['reduction_percent'] OR $row['reduction_amount'] OR $row['group_reduction']) AND $order->getTaxCalculationMethod() == PS_TAX_EXC)
            $row['product_price'] = Tools::ps_round($row['product_price'], 2);
*/
        //if ($order->getTaxCalculationMethod() == PS_TAX_EXC)
            $row['product_price_wt'] = self::roundPrice($row['product_price'] * (1 + ($row['tax_rate'] * 0.01)), 2) + self::roundPrice($row['ecotax'] * (1 + $row['ecotax_tax_rate'] / 100), 2);
       /*
        else
        {
            $row['product_price_wt_but_ecotax'] = $row['product_price_wt'];
            $row['product_price_wt'] = Tools::ps_round($row['product_price_wt'] + $row['ecotax'] * (1 + $row['ecotax_tax_rate'] / 100), 2);
        }
*/
        $row['total_wt'] = $row['product_quantity'] * $row['product_price_wt'];
        $row['total_price'] = $row['product_quantity'] * $row['product_price'];
    }
    public static function getOrderProductsDetail($order)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'order_detail` od
		WHERE od.`id_order` = '.(int)($order->id));
    }
}
?>