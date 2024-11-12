<?php
error_reporting(E_ALL^E_WARNING^E_NOTICE^E_DEPRECATED);
ini_set('display_errors',1);
require_once(dirname(__FILE__) . '/../config/config.inc.php');

//$product = new Product();
//print_r($product);

$ebay = new EbayParser();
print_r($ebay);
