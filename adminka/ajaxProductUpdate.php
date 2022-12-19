<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

    include(dirname(__FILE__).'/../config/config.inc.php');
    $token = Tools::getAdminToken('AdminCatalog'.intval(Tab::getIdFromClassName('AdminCatalog')).intval($cookie->id_employee));

    $prib = 1.15;   // % прибыль
    $min_prib = 17;	// в долларах
    $max_prib = 100;// в долларах	

    $item = $_POST['lot'];


    //echo "\r\ngetSingleItem\r\n"; 
    // идем на ебей за лотом
    $lot = Ebay_shopping::getSingleItem(array($item), 1, 1);
echo __FILE__.' line '.__LINE__;
var_dump($lot);
die;
    if (empty($lot))
    {
        echo json_encode(array('error' => 'Пустой ответ от Ебея. Лот протух?'));
        die;
    }

    // если товар уже есть, нужно передать в $_POST его id_product
    // и взять вес товара из базы
    if (isset($_POST['id_product']) and !empty($_POST['id_product']))
    {
        $product = new Product($_POST['id_product']);
        $weight = $product->weight;
    }
    // если товар новый, его id_product не передается и вес назначается = 2 кг
    else 
    {
        $weight = 2;
    }


//var_dump($product); die;

	// посчитаем цену продажи и все остальное
	$ebay_price = $lot[$item]['ebay_price'];
	$new_price = ($ebay_price * $prib);
	if ( ($new_price - $ebay_price) < $min_prib ) 
		{
			$new_price = ($ebay_price + $min_prib);
		}
	if (($new_price - $ebay_price) > $max_prib) 
		{
			$new_price = ($ebay_price + $max_prib);
		}			
  
    // отнимем от цены продажи цену доставки
    $delivery = new Delivery();		
    $deliveryPrice = $delivery->getPriceByWeight($product->weight);
    $new_price = $new_price - $deliveryPrice;
    $lot[$item]['weight'] = $weight;
    $lot[$item]['priceTE'] = $new_price;

/*
    echo "\r\n ebay_price $ebay_price ";		
    echo "\r\n new_price $new_price ";		
    echo "\r\n weight $weight ";
    echo "\r\n deliveryPrice $deliveryPrice\r\n";
*/

//print_r($lot);
    echo json_encode($lot);
		
    
?>