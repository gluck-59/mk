<?php
include_once(dirname(__FILE__).'/config/config.inc.php');
include_once(dirname(__FILE__).'/init.php');
//error_reporting(0);

//echo '<pre>';
//print_r($_GET);

	$product = new Product(intval($_GET['id_product']), true, 3);
//print_r($product);

    //назначаем недостающие аксессуары рандомно
    $accessories = $product->getAccessories(intval($cookie->id_lang));
    if ($accessories===false) 
        $acc_count = 0;
    else 
    	$acc_count = count($accessories);
    
    $qty = (12 - $acc_count);
    $accessories_rand = $product->getAccessoriesRandom(intval($cookie->id_lang), abs($qty));
    
    	if ($accessories_rand != false) {
    		if ($acc_count != 0) {
    		$accessories = array_merge($accessories, $accessories_rand);
    		}
    		else	$accessories = $accessories_rand;
    	}	
    	else 	$accessories = $accessories;	
    
//    print_r($accessories);
//echo '<br><br>';    
       
foreach ( $accessories as $accessory )
{
    $smarty->assign(array(
    'accessory' => $accessory));
    $smarty->display(_PS_THEME_DIR_.'product-accessories.tpl');
//unset ($accessories[0]);
}
       


//echo '</pre>';
?>
