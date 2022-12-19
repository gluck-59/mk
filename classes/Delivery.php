<?php

/**
  * Delivery class, Delivery.php
  * Delivery management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

class Delivery extends ObjectModel
{
	/** @var integer */
	public $id_delivery;
	
	/** @var integer */
	public $id_carrier;

	/** @var integer */
	public $id_range_price;

	/** @var integer */
	public $id_range_weight;

	/** @var integer */	
	public $id_zone;

	/** @var float */	
	public $price;

	protected	$fieldsRequired = array ('id_carrier', 'id_range_price', 'id_range_weight', 'id_zone', 'price');	
	protected	$fieldsValidate = array ('id_carrier' => 'isUnsignedId', 'id_range_price' => 'isUnsignedId', 
	'id_range_weight' => 'isUnsignedId', 'id_zone' => 'isUnsignedId', 'price' => 'isPrice');

	protected 	$table = 'delivery';
	protected 	$identifier = 'id_delivery';
	
	
	public function getFields()
	{
		parent::validateFields();

		$fields['id_carrier'] = intval($this->id_carrier);
		$fields['id_range_price'] = intval($this->id_range_price);
		$fields['id_range_weight'] = intval($this->id_range_weight);
		$fields['id_zone'] = intval($this->id_zone);
		$fields['price'] = floatval($this->price);
		
		return $fields;
	}	
	

    // ищет цену доставки с Почтой России по весу
	public function getPriceByWeight($weight)
	{
		$weight_price = Db::getInstance()->getValue('
            SELECT `price` FROM `presta_delivery`
            where `id_carrier` = 79
            and `id_zone` = 1
            and `id_range_weight` = 
            (
                SELECT `id_range_weight` 
                FROM `presta_range_weight`
                where `id_carrier` = 79
                and `delimiter1` <= '.$weight.'
                and `delimiter2` >= '.$weight.'
            )
        ');
        return (float)$weight_price;
	}


    public function getQwintryCost($weight = null, $hub = 'DE1') {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://q3-api.qwintry.com/ru/frontend/calculator/calculate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "hub":"'.$hub.'",
                "weight":"'.$weight.'",
                "weightMeasurement":"kg",
                "country":"RU", 
                "city": 4290
                }
            ',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $ship = json_decode($response, true, 512, JSON_NUMERIC_CHECK);
//prettyDump($ship['costs']['ecopost']['cost']['totalCost'], 1);

        $deliveryPrice = $ship['costs']['ecopost']['cost']['totalCost'];
        echo '<script>toastr.info(\''.$weight.' кг через '.$hub.' = $'.$deliveryPrice.'\', \'Ответ Бандерольки:\')</script>';
        return $deliveryPrice;
    }
}

?>