<?php
require __DIR__.'/../vendor/autoload.php';
$config = require __DIR__.'/../configuration.php';

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

$service = new Services\FindingService([
    'credentials' => $config['production']['credentials'],
    'globalId'    => Constants\GlobalIds::MOTORS
]);


$request = new Types\FindItemsAdvancedRequest();

$request->categoryId = ['6000'];

$request->keywords = 'Cobra Exhaust vtx1300'; // Cobra Exhaust vtx1300


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

//$request->paginationInput = new Types\PaginationInput();
//$request->paginationInput->entriesPerPage = 100;
//$request->paginationInput->pageNumber = 1;

$response = $service->findItemsAdvanced($request);

if (isset($response->errorMessage)) {
    foreach ($response->errorMessage->error as $error) {
        printf(
            "%s: %s\n\n",
            $error->severity=== Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
            $error->message
        );
    }
}

printf(
    "%s items found over %s pages.\n\n",
    $response->paginationOutput->totalEntries,
    $response->paginationOutput->totalPages,
);


echo '<pre>';
print_r($response->searchResult->toArray());
echo '</pre>';

/*
echo "==================\nResults for page 1\n==================\n";
if ($response->ack !== 'Failure') {
    foreach ($response->searchResult->item as $item) {
        echo 'первая страница<pre>';
        print_r($item);
        printf(
            "(%s, %s) %s: %s %.2f\n",
            $item->itemId,
            $item->primaryCategory->categoryName,
            $item->title,
            $item->sellingStatus->currentPrice->currencyId,
            $item->sellingStatus->currentPrice->value
        );
    }
}
*/


/*
$limit = min($response->paginationOutput->totalPages, 3);
for ($pageNum = 2; $pageNum <= $limit; $pageNum++) {
    $request->paginationInput->pageNumber = $pageNum;
    $response = $service->findItemsAdvanced($request);

    echo "==================\nResults for page $pageNum\n==================\n";

    if ($response->ack !== 'Failure') {
        foreach ($response->searchResult->item as $item) {
            echo 'последующие страницы<pre>';
            print_r($item);
            printf(
                "(%s) %s: %s %.2f\n",
                $item->itemId,
                $item->title,
                $item->sellingStatus->currentPrice->currencyId,
                $item->sellingStatus->currentPrice->value
            );
        }
    }
}
*/