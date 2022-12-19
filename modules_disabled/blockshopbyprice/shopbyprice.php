<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');

include(dirname(__FILE__).'/../../product-sort.php');

$errors = array();


       function getminPrice()
       {
           $minPrice = $_GET["minprice"];
           return $minPrice;
       }
 

        function getmaxPrice()
        {
            if (isset($_GET["maxprice"]))
            {
              $maxPrice = $_GET["maxprice"];
              if (preg_match("[0-9]", $maxPrice) OR $maxPrice != $_GET["minprice"])
              {
                  return $maxPrice;
              }
            }
            else
            {
            return false;
            }
        }

        function getQuery()
        {

            if (getmaxPrice())
            {
                $query = 'AND p.`price` BETWEEN '.getminPrice().' and '.getmaxPrice().'';
            }
             else
            {
                $query = 'AND p.`price` >= '.getminPrice().'';
            }
        
        return $query;
        }

if (!isset($_GET["minprice"])|| empty($_GET["minprice"])){
        echo "No Min Price Set";      
        return;
       }
else
{

        function productsbyPrice($id_lang, $pageNumber = 0, $nbProducts = 10, $count = false, $orderBy = NULL, $orderWay = NULL)
                {
                        global $link, $cookie;
                        if ($pageNumber < 0) $pageNumber = 0;
                        if ($nbProducts < 1) $nbProducts = 10;
                        if (empty($orderBy) || $orderBy == 'position') $orderBy = 'date_add';
                        if (empty($orderWay)) $orderWay = 'DESC';
                        if ($orderBy == 'id_product' OR $orderBy == 'price' OR $orderBy == 'date_add')
                                $orderByPrefix = 'p';
                        elseif ($orderBy == 'name')
                                $orderByPrefix = 'pl';
                        if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
                                die(Tools::displayError());

                        if ($count)
                        {
                                $result = Db::getInstance()->getRow('
                                SELECT COUNT(`id_product`) AS nb
                                FROM `'._DB_PREFIX_.'product` p
                                WHERE `active` = 1
                                AND p.`id_product` IN (
                                        SELECT cp.`id_product`
                                        FROM `'._DB_PREFIX_.'category_group` cg
                                        LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                                        WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').'
                                )
                                ');

                                return intval($result['nb']);
                        }

                        $result = Db::getInstance()->ExecuteS('
                        SELECT p.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, p.`ean13`,
                                i.`id_image`, il.`legend`, t.`rate`, m.`name` AS manufacturer_name
                                
                        FROM `'._DB_PREFIX_.'product` p
                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
                        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
                        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
                        LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = p.`id_tax`)
                        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                        WHERE p.`active` = 1 AND `quantity` > 0
                        '.getQuery().'
                        AND p.`id_product` IN (
                                SELECT cp.`id_product`
                                FROM `'._DB_PREFIX_.'category_group` cg
                                LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                                WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').'
                        )
                        ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
                        LIMIT '.intval($pageNumber * $nbProducts).', '.intval($nbProducts));

                        if ($orderBy == 'price')
                                Tools::orderbyPrice($result, $orderWay);
                        if (!$result)
                                return false;

                        return Product::getProductsProperties(intval($id_lang), $result);
                }


        $nbProducts = intval(productsbyPrice(intval($cookie->id_lang), isset($p) ? intval($p) - 1 : NULL, isset($n) ? intval($n) : NULL, true));
        include(dirname(__FILE__).'/../../pagination.php');

        $smarty->assign(array(
                'products' => productsbyPrice(intval($cookie->id_lang), intval($p) - 1, intval($n), false, $orderBy, $orderWay),
                'nbProducts' => intval($nbProducts),
                'interval' => (getminPrice()."&nbsp;&mdash;&nbsp;".getmaxPrice())
                ));

        $smarty->display(_PS_ROOT_DIR_.'/modules/blockshopbyprice/shopbyprice.tpl');

//echo (getminPrice()."&nbsp;&mdash;&nbsp;".getmaxPrice());

        include(dirname(__FILE__).'/../../footer.php');

}

?>
