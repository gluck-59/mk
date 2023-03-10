<?php

/**
  * WishList class, WishList.php
  * WishLists management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

class		WishList extends ObjectModel
{
	/** @var integer Wishlist ID */
	public		$id;

	/** @var integer Customer ID */
	public 		$id_customer;

	/** @var integer Token */
	public 		$token;

	/** @var integer Name */
	public 		$name;

	/** @var string Object creation date */
	public 		$date_add;

	/** @var string Object last modification date */
	public 		$date_upd;
	
	protected	$fieldsSize = array('name' => 64, 'token' => 64);
	protected	$fieldsRequired = array('id_customer', 'name', 'token');
	protected	$fieldsValidate = array('id_customer' => 'isUnsignedId', 'name' => 'isMessage',
		'token' => 'isMessage');
	protected 	$table = 'wishlist';
	protected 	$identifier = 'id_wishlist';

	public function getFields()
	{
		parent::validateFields(false);
		$fields['id_customer'] = intval($this->id_customer);
		$fields['token'] = pSQL($this->token);
		$fields['name'] = pSQL($this->name);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
		return ($fields);
	}

	public function WishlistDelete()
	{
		global $cookie;
		Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'wishlist_email` WHERE `id_wishlist` = '.intval($this->id));
		Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'wishlist_product` WHERE `id_wishlist` = '.intval($this->id));
		Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'wishlist_product_customer` WHERE `id_wishlist` = '.intval($this->id));
		if (isset($cookie->id_wishlist))
			unset($cookie->id_wishlist);
		return (parent::delete());
	}
	
	/**
	 * Increment counter
	 *
	 * @return boolean succeed
	 */
	static public function incCounter($id_wishlist)
	{
		if (!Validate::isUnsignedId($id_wishlist))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT `counter`
		  FROM `'._DB_PREFIX_.'wishlist`
		WHERE `id_wishlist` = '.intval($id_wishlist));
		if ($result == false OR !sizeof($result) OR empty($result) === true)
			return (false);
		return (Db::getInstance()->Execute('
		UPDATE `'._DB_PREFIX_.'wishlist` SET
		`counter` = '.intval($result['counter'] + 1).'
		WHERE `id_wishlist` = '.intval($id_wishlist)));
	}
	
	/**
	 * Return true if wishlist exists else false
	 *
	 *  @return boolean exists
	 */
	static public function exists($id_wishlist, $id_customer, $return = false)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT `id_wishlist`, `name`, `token`
		  FROM `'._DB_PREFIX_.'wishlist`
		WHERE `id_wishlist` = '.intval($id_wishlist).'
		AND `id_customer` = '.intval($id_customer));
		if (empty($result) === false AND $result != false AND sizeof($result))
		{
			if ($return === false)
				return (true);
			else
				return ($result);
		}
		return (false);
	}
	
	/**
	 * Get ID wishlist by Token
	 *
	 * @return array Results
	 */
	static public function getByToken($token)
	{
		if (!Validate::isMessage($token))
			die (Tools::displayError());
		return (Db::getInstance()->getRow('
		SELECT w.`id_wishlist`, w.`name`, w.`id_customer`, c.`firstname`, c.`lastname`
		  FROM `'._DB_PREFIX_.'wishlist` w
		INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
		WHERE `token` = \''.pSQL($token).'\''));
	}

	/**
	 * Get Wishlists by Customer ID
	 *
	 * @return array Results
	 */
	static public function getByIdCustomer($id_customer)
	{
		if (!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		return (Db::getInstance()->ExecuteS('
		SELECT w.`id_wishlist`, w.`name`, w.`token`, w.`date_add`, w.`date_upd`, w.`counter`
		  FROM `'._DB_PREFIX_.'wishlist` w
		WHERE `id_customer` = '.intval($id_customer).'
		ORDER BY w.`name` ASC'));
	}

	static public function refreshWishList($id_wishlist)
	{
		$old_carts = Db::getInstance()->ExecuteS('
			SELECT wp.id_product, wp.id_product_attribute, wpc.id_cart, UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(wpc.date_add) AS timecart
			FROM `'._DB_PREFIX_.'wishlist_product_cart` wpc
			JOIN `'._DB_PREFIX_.'wishlist_product` wp ON (wp.id_wishlist_product = wpc.id_wishlist_product)
			JOIN `'._DB_PREFIX_.'cart` c ON  (c.id_cart = wpc.id_cart)
			JOIN `'._DB_PREFIX_.'cart_product` cp ON (wpc.id_cart = cp.id_cart)
			LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.id_cart = c.id_cart)
			WHERE (wp.id_wishlist='.intval($id_wishlist).' AND o.id_cart IS NULL)
			HAVING timecart  >= 3600*6 
		');

		if(isset($old_carts) AND $old_carts != false)
			foreach ($old_carts AS $old_cart)
				Db::getInstance()->Execute('
					DELETE FROM `'._DB_PREFIX_.'cart_product` 
					WHERE id_cart='.intval($old_cart['id_cart']).' AND id_product='.intval($old_cart['id_product']).' AND id_product_attribute='.intval($old_cart['id_product_attribute'])
				);

		$freshwish = Db::getInstance()->ExecuteS('
			SELECT  wpc.id_cart, wpc.id_wishlist_product
			FROM `'._DB_PREFIX_.'wishlist_product_cart` wpc
			JOIN `'._DB_PREFIX_.'wishlist_product` wp ON (wpc.id_wishlist_product = wp.id_wishlist_product)
			JOIN `'._DB_PREFIX_.'cart` c ON (c.id_cart = wpc.id_cart)
			LEFT JOIN `'._DB_PREFIX_.'cart_product` cp ON (cp.id_cart = wpc.id_cart AND cp.id_product = wp.id_product AND cp.id_product_attribute = wp.id_product_attribute)
			WHERE (wp.id_wishlist = '.intval($id_wishlist).' AND ((cp.id_product IS NULL AND cp.id_product_attribute IS NULL)))
			');
		$res = Db::getInstance()->ExecuteS('
			SELECT wp.id_wishlist_product, cp.quantity AS cart_quantity, wpc.quantity AS wish_quantity, wpc.id_cart
			FROM `'._DB_PREFIX_.'wishlist_product_cart` wpc
			JOIN `'._DB_PREFIX_.'wishlist_product` wp ON (wp.id_wishlist_product = wpc.id_wishlist_product)
			JOIN `'._DB_PREFIX_.'cart` c ON (c.id_cart = wpc.id_cart)
			JOIN `'._DB_PREFIX_.'cart_product` cp ON (cp.id_cart = wpc.id_cart AND cp.id_product = wp.id_product AND cp.id_product_attribute = wp.id_product_attribute)
			WHERE wp.id_wishlist='.intval($id_wishlist)
		);
		
		if(isset($res) AND $res != false)
			foreach ($res AS $refresh)
				if($refresh['wish_quantity'] > $refresh['cart_quantity'])
				{
					Db::getInstance()->Execute('
						UPDATE `'._DB_PREFIX_.'wishlist_product` 
						SET `quantity`= `quantity` + '.(intval($refresh['wish_quantity']) - intval($refresh['cart_quantity'])).'
						WHERE id_wishlist_product='.intval($refresh['id_wishlist_product']) 
					);
					Db::getInstance()->Execute('
						UPDATE `'._DB_PREFIX_.'wishlist_product_cart` 
						SET `quantity`='.intval($refresh['cart_quantity']).'
						WHERE id_wishlist_product='.intval($refresh['id_wishlist_product']).' AND id_cart='.intval($refresh['id_cart'])
					);
				}
		if(isset($freshwish) AND $freshwish != false)
			foreach ($freshwish AS $prodcustomer)
			{
				Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'wishlist_product` SET `quantity`=`quantity` +
					(
						SELECT `quantity` FROM `'._DB_PREFIX_.'wishlist_product_cart`
						WHERE `id_wishlist_product`='.intval($prodcustomer['id_wishlist_product']).' AND `id_cart`='.intval($prodcustomer['id_cart']).'
					)
					WHERE `id_wishlist_product`='.intval($prodcustomer['id_wishlist_product']).' AND `id_wishlist`='.intval($id_wishlist)
					);
				Db::getInstance()->Execute('
					DELETE FROM `'._DB_PREFIX_.'wishlist_product_cart` 
					WHERE `id_wishlist_product`='.intval($prodcustomer['id_wishlist_product']).' AND `id_cart`='.intval($prodcustomer['id_cart'])
					);
			}
	}
	
	/**
	 * Get Wishlist products by Customer ID
	 *
	 * @return array Results
	 */
	static public function getProductByIdCustomer($id_wishlist, $id_customer, $id_lang, $id_product = null, $quantity = false)
	{
		if (!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_lang) OR
			!Validate::isUnsignedId($id_wishlist))
			die (Tools::displayError());
		$products = Db::getInstance()->ExecuteS('
		SELECT wp.`id_product`, wp.`quantity`, p.`quantity` AS product_quantity, pl.`name`, wp.`id_product_attribute`, wp.`priority`, i.`id_image`, p.`price`, p.`reduction_price`, p.`on_sale`, p.`reduction_percent`, p.`reduction_to`, p.`reduction_from`, p.`active`   
	  FROM `'._DB_PREFIX_.'wishlist_product` wp
		JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = wp.`id_product`
		JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = wp.`id_product`
		JOIN `'._DB_PREFIX_.'wishlist` w ON w.`id_wishlist` = wp.`id_wishlist`
		JOIN `'._DB_PREFIX_.'image` i ON wp.`id_product` = i.`id_product`
		WHERE w.`id_customer` = '.intval($id_customer).'
		AND i.`cover` = 1
		AND pl.`id_lang` = '.intval($id_lang).'
		AND wp.`id_wishlist` = '.intval($id_wishlist).
		(empty($id_product) === false ? ' AND wp.`id_product` = '.intval($id_product) : '').
		($quantity == true ? ' AND wp.`quantity` != 0' : '').'
		order by p.`active` desc, p.`quantity` desc
		');
		if (empty($products) === true OR !sizeof($products))
			return array();
		for ($i = 0; $i < sizeof($products); ++$i)
		{
			if (isset($products[$i]['id_product_attribute']) AND
				Validate::isUnsignedInt($products[$i]['id_product_attribute']))
			{
				$result = Db::getInstance()->ExecuteS('
				SELECT al.`name` AS attribute_name, pa.`quantity` AS "attribute_quantity"
				  FROM `'._DB_PREFIX_.'product_attribute_combination` pac
				LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.intval($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.intval($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
				WHERE pac.`id_product_attribute` = '.intval($products[$i]['id_product_attribute']));
				$products[$i]['attributes_small'] = '';
				if ($result)
					foreach ($result AS $k => $row)
						$products[$i]['attributes_small'] .= $row['attribute_name'].', ';
				$products[$i]['attributes_small'] = rtrim($products[$i]['attributes_small'], ', ');
				if (isset($result[0]))
					$products[$i]['attribute_quantity'] = $result[0]['attribute_quantity'];
			}
			else
				$products[$i]['attribute_quantity'] = $products[$i]['product_quantity'];
		}
		return ($products);
	}
	
	/**
	 * Get Wishlists number products by Customer ID
	 *
	 * @return array Results
	 */
	static public function getInfosByIdCustomer($id_customer)
	{
		if (!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		return (Db::getInstance()->ExecuteS('
		SELECT SUM(wp.`quantity`) AS nbProducts, wp.`id_wishlist`
		  FROM `'._DB_PREFIX_.'wishlist_product` wp
		INNER JOIN `'._DB_PREFIX_.'wishlist` w ON (w.`id_wishlist` = wp.`id_wishlist`)
		WHERE w.`id_customer` = '.intval($id_customer).'
		GROUP BY w.`id_wishlist`
		ORDER BY w.`name` ASC'));
	}
	
	/**
	 * Add product to ID wishlist
	 *
	 * @return boolean succeed
	 */
	static public function addProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute, $quantity)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT wp.`quantity`
		  FROM `'._DB_PREFIX_.'wishlist_product` wp
		JOIN `'._DB_PREFIX_.'wishlist` w ON (w.`id_wishlist` = wp.`id_wishlist`)
		WHERE wp.`id_wishlist` = '.intval($id_wishlist).'
		AND w.`id_customer` = '.intval($id_customer).'
		AND wp.`id_product` = '.intval($id_product).'
		AND wp.`id_product_attribute` = '.intval($id_product_attribute));
		if (empty($result) === false AND sizeof($result))
		{
			if (($result['quantity'] + $quantity) <= 0)
				return (WishList::removeProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute));
			else
				return (Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'wishlist_product` SET
				`quantity` = '.intval($quantity + $result['quantity']).'
				WHERE `id_wishlist` = '.intval($id_wishlist).'
				AND `id_product` = '.intval($id_product).'
				AND `id_product_attribute` = '.intval($id_product_attribute)));
		}
		else
			return (Db::getInstance()->Execute('
			INSERT INTO `'._DB_PREFIX_.'wishlist_product` (`id_wishlist`, `id_product`, `id_product_attribute`, `quantity`, `priority`) VALUES(
			'.intval($id_wishlist).',
			'.intval($id_product).',
			'.intval($id_product_attribute).',
			'.intval($quantity).', 1)'));
			
	}
	
	/**
	 * Update product to wishlist
	 *
	 * @return boolean succeed
	 */
	static public function updateProduct($id_wishlist, $id_product, $id_product_attribute, $priority, $quantity)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity) OR
			$priority < 0 OR $priority > 2)
			die (Tools::displayError());
		return (Db::getInstance()->Execute('
		UPDATE `'._DB_PREFIX_.'wishlist_product` SET
		`priority` = '.intval($priority).',
		`quantity` = '.intval($quantity).'
		WHERE `id_wishlist` = '.intval($id_wishlist).'
		AND `id_product` = '.intval($id_product).'
		AND `id_product_attribute` = '.intval($id_product_attribute)));
	}
	
	/**
	 * Remove product from wishlist
	 *
	 * @return boolean succeed
	 */
	static public function removeProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_product))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT `id_wishlist`
		  FROM `'._DB_PREFIX_.'wishlist`
		WHERE `id_customer` = '.intval($id_customer).'
		AND `id_wishlist` = '.intval($id_wishlist));
		if (empty($result) === true OR
			$result === false OR
			!sizeof($result) OR
			$result['id_wishlist'] != $id_wishlist)
			return (false);
		$result = Db::getInstance()->Execute('
		DELETE FROM `'._DB_PREFIX_.'wishlist_product`
		WHERE `id_wishlist` = '.intval($id_wishlist).'
		AND `id_product` = '.intval($id_product).'
		AND `id_product_attribute` = '.intval($id_product_attribute));
		if ($result == false)
			return (false);
/*		return (Db::getInstance()->Execute('
		DELETE FROM `'._DB_PREFIX_.'wishlist_product_cart` wpc
		JOIN `'._DB_PREFIX_.'wishlist_product` pc ON (wp.id_wishlist_product = wpc.id_wishlist_product)
		WHERE `wp.id_wishlist` = '.intval($id_wishlist).'
		AND `wp.id_product` = '.intval($id_product).'
		AND `wp.id_product_attribute` = '.intval($id_product_attribute)));
*/	}
	
	/**
	 * Return bought product by ID wishlist
	 *
	 * @return Array results
	 */
	static public function getBoughtProduct($id_wishlist)
	{
		
		if (!Validate::isUnsignedId($id_wishlist))
			die (Tools::displayError());
		return (Db::getInstance()->ExecuteS('
		SELECT wp.`id_product`, wp.`id_product_attribute`, wpc.`quantity`, wpc.`date_add`, cu.`lastname`, cu.`firstname`
		FROM `'._DB_PREFIX_.'wishlist_product_cart` wpc
		JOIN `'._DB_PREFIX_.'wishlist_product` wp ON (wp.id_wishlist_product = wpc.id_wishlist_product)
		JOIN `'._DB_PREFIX_.'cart` ca ON (ca.id_cart = wpc.id_cart)
		JOIN `'._DB_PREFIX_.'customer` cu ON (cu.`id_customer` = ca.`id_customer`)
		WHERE wp.`id_wishlist` = '.intval($id_wishlist)));
	}
	
	/**
	 * Add bought product
	 *
	 * @return boolean succeed
	 */
	static public function addBoughtProduct($id_wishlist, $id_product, $id_product_attribute, $id_cart, $quantity)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
			SELECT `quantity`, `id_wishlist_product`
		  FROM `'._DB_PREFIX_.'wishlist_product` wp
			WHERE `id_wishlist` = '.intval($id_wishlist).'
			AND `id_product` = '.intval($id_product).'
			AND `id_product_attribute` = '.intval($id_product_attribute));
		
		if (!sizeof($result) OR
			($result['quantity'] - $quantity) < 0 OR
			$quantity > $result['quantity'])
			return (false);

			Db::getInstance()->Execute('
			SELECT *  
			FROM `'._DB_PREFIX_.'wishlist_product_cart`
			WHERE `id_wishlist_product`='.intval($result['id_wishlist_product']).' AND `id_cart`='.intval($id_cart) 
			);

		if (Db::getInstance()->NumRows() > 0)
			$result2= Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'wishlist_product_cart`
				SET `quantity`=`quantity` + '.intval($quantity).'
				WHERE `id_wishlist_product`='.intval($result['id_wishlist_product']).' AND `id_cart`='.intval($id_cart)
				);

		else
			$result2 = Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'wishlist_product_cart`
				(`id_wishlist_product`, `id_cart`, `quantity`, `date_add`) VALUES(
				'.intval($result['id_wishlist_product']).',
				'.intval($id_cart).',	
				'.intval($quantity).',
				\''.pSQL(date('Y-m-d H:i:s')).'\')');

		if ($result2 === false)
			return (false);
		return (Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'wishlist_product` SET
			`quantity` = '.intval($result['quantity'] - $quantity).'
			WHERE `id_wishlist` = '.intval($id_wishlist).'
			AND `id_product` = '.intval($id_product).'
			AND `id_product_attribute` = '.intval($id_product_attribute)));
	}
	
	/**
	 * Add email to wishlist
	 *
	 * @return boolean succeed
	 */
	static public function addEmail($id_wishlist, $email)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isEmail($email))
			die (Tools::displayError());
		return (Db::getInstance()->Execute('
		INSERT INTO `'._DB_PREFIX_.'wishlist_email` (`id_wishlist`, `email`, `date_add`) VALUES(
		'.intval($id_wishlist).',
		\''.pSQL($email).'\',
		\''.pSQL(date('Y-m-d H:i:s')).'\')'));
	}
	
	/**
	 * Get email from wishlist
	 *
	 * @return Array results
	 */
	static public function getEmail($id_wishlist, $id_customer)
	{
		if (!Validate::isUnsignedId($id_wishlist) OR
			!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		return (Db::getInstance()->ExecuteS('
		SELECT we.`email`, we.`date_add`
		  FROM `'._DB_PREFIX_.'wishlist_email` we
		INNER JOIN `'._DB_PREFIX_.'wishlist` w ON w.`id_wishlist` = we.`id_wishlist`
		WHERE we.`id_wishlist` = '.intval($id_wishlist).'
		AND w.`id_customer` = '.intval($id_customer)));
	}
};
