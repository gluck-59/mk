<?php
error_reporting(E_ALL^E_WARNING^E_NOTICE);
ini_set('display_errors','on');
prettyDump($_POST);


require __DIR__.'/../../ebay-sdk/vendor/autoload.php';
$config = require __DIR__.'/../../ebay-sdk/configuration.php';

//var_dump(__DIR__);
//var_dump($config);


use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

$service = new Services\FindingService([
    'credentials' => $config['production']['credentials'],
//    'globalId' => Constants\GlobalIds::MOTORS
    'globalId' => $_POST['site_id']
]);

//prettyDump($service, 1);

$request = new Types\FindItemsAdvancedRequest();
//$request->categoryId = ['6000'];
$request->keywords = $_POST['request']; // Cobra Exhaust vtx1300


// все фильтры: https://developer.ebay.com/devzone/finding/callref/extra/fnditmsadvncd.rqst.tmfltr.nm.html
$itemFilter = new Types\ItemFilter();
$request->itemFilter[] = new Types\ItemFilter([
    'name' => 'ListingType',
    'value' => ['FixedPrice', 'AuctionWithBIN'] // Auction, FixedPrice, AuctionWithBIN
]);

///  //$request->itemFilter[] = new Types\ItemFilter([
//    'name' => 'ExcludeSeller',
//    'value' => ['MotoNow', 'easternpc'] // банлист в виде массива — сюда
//]);

// проверить
//$request->itemFilter[] = new Types\ItemFilter([
//    'name' => 'AvailableTo',
//    'value' => ['ES']
//]);

$request->itemFilter[] = new Types\ItemFilter([
    'name' => 'Condition',
    'value' => ['New']
]);
$request->itemFilter[] = new Types\ItemFilter([
    'name' => 'descriptionSearch',
    'value' => ['false']
]);
$request->itemFilter[] = new Types\ItemFilter([
    'name' => 'HideDuplicateItems',
    'value' => ['true']
]);

$request->itemFilter[] = $itemFilter;
// itemFilter


// что возвращать
$request->outputSelector = [
    'SellerInfo',
    'PictureURLSuperSize'
];
$request->sortOrder = 'PricePlusShippingLowest';

$response = $service->findItemsAdvanced($request);

if (isset($response->errorMessage)) {
    foreach ($response->errorMessage->error as $error) {
        printf(
            "%s: %s\n\n",
            $error->severity === Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
            $error->message
        );
    }
}

if ($response->paginationOutput->totalEntries) {
    $lots = $response->searchResult->toArray();
} else echo '<br>Ни одного лота не найдено<br>';
