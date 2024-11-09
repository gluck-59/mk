<?php
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    include(dirname(__FILE__).'/../config/config.inc.php');
    $cookie = new Cookie('psAdmin');
    $token = Tools::getAdminToken('AdminCatalog'.intval(Tab::getIdFromClassName('AdminCatalog')).intval($cookie->id_employee));

/*$_POST: Array
(
    [itemNo] => 387267543745
    [id_product] => 9668
)*/

    $ebay = new EbayParser();
    $lot = $ebay->getitemDetails($_POST['itemNo']);
    if (empty($lot))
    {
        echo json_encode(array('error' => 'Пустой ответ от Ебея. Лот протух?'));
        die;
    }
    $ebayPrice = $lot['ebayPrice'];
    $prestaPrice = $ebay->calculateProfit($ebayPrice);
    $product = new Product($_POST['id_product']);
    $product->wholesale_price = $ebayPrice;
    $product->price = $prestaPrice;
    $product->quantity = 2;

    $result = $product->save();
    //$res = ['$lot' => $lot, '$product' => $product->id ];

    echo json_encode(['result' => $result]);
?>