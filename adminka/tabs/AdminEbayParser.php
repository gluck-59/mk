<?php
error_reporting(E_ALL^E_WARNING^E_NOTICE);
ini_set('display_errors','on');

//$i = 1;
//$arr = [0,1,2,3];
//prettyDump(sizeof($arr));
//$size = sizeof($arr);
//foreach ($arr as $elem) {
//	prettyDump($elem);
//	if ($i % $size ==0) echo 'yes<br>';
//	$i++;
//}
//die();

//получаем курсы
$usd = Currency::getCurrency(3);
$eur = Currency::getCurrency(1);

//$storeUrl = parse_url('https://www.ebay.com/sch/i.html?_ssn=oemcycles&store_name=oemcycleshop&_oac=1&_trksid=p4429486.m3561.l161210');
//$params = parse_str($storeUrl['query'], $store);
//prettyDump($store['store_name'], 1);

// форма
echo '
<style type="text/css">
 	{ font-size: 12px;font-family: PT Sans Caption, Arial, Helvetica, Sans-Serif;}
dd {margin-top: -18px;margin-left: 220px;}
dt {margin: 16px 175px 0 0; text-align: right;}

.cathint > .catblock { 
position: absolute; top: -5px;
left: 274px;
background-color: #fffff0;
width: 125px;
height: 26px;
overflow: hidden;
opacity: 0.4; 
-webkit-transition: all 0.3s ease;
}

.cathint:focus > .catblock { 
position: absolute;top: -330px;left: 380px;background-color: #fffff0;width: 350px;overflow-y: auto;height: 800px;opacity: 1;"
}
</style>
  	<form  style="width:45%" method="post">
	<fieldset style="border-radius: 6px;"><legend>Ebay Parser</legend>
	<dd style="margin: 10px 0 0 0;width: 93%;">
	<!--input required style="width: 100%;"autofocus name="ebay" placeholder="Адрес RSS-фида продавца на Ebay" type="url"-->
	<input style="width: 100%;" name="request" value="'.$_POST['request'].'" placeholder="Ключевые слова, + / - или один номер лота" type="text">
	<dl>
	<dt>
	
	<select disabled style="width: 147px;" name="site_id" id="site_id" >
	<option value="EBAY-MOTOR" selected>eBay Motors</option>
	<option value="EBAY-US">eBay USA</option>
	<option value="EBAY-GB">eBay UK</option>
	<option value="EBAY-DE">eBay Germany</option>
	<option value="EBAY-AU">eBay Australia</option>
	<option value="EBAY-HK">eBay Hong Kong</option>
	<option value="EBAY-PL">eBay Poland</option>
	</select>
	
	</dt>
	<dd><input disabled style="width: 168px;" name="store" placeholder="Ebay Store, необязательно" type="text"></dd>
	<br>
	<p align="center">дороже&nbsp;<input size="2" name="minprice" placeholder="" value="" type="text">
	<input size="3" name="maxprice" placeholder="" value="" type="text">&nbsp;дешевле</p>
	
	
	<div style="margin: 27px 0 0 162px; text-align: center;position: absolute;">
	<span style="float: left;top: -12px;left: -162px;position: relative;" class="cathint" tabindex="0">';
	echo 'Категории (можно несколько)<div class="catblock" id="categories">';

	function recurseCategory($categories, $current, $id_category = 1, $id_selected = 1)
		{
			global $currentIndex;
			echo '<div class="catblock" id="categoryList">';
			echo '<table cellspacing="0" cellpadding="0" class="table"><tr>';
			echo '<th><input type="checkbox" name="category_'.$id_category.'" id="'.$id_category.'" value="'.$id_category.'" /></th>
			<td><label for="'.$id_category.'" style="text-align: left;font-weight: normal;width:300px;line-height: 20pt;">';
			if ($current['infos']['level_depth'] == 0)echo ('<img src="../img/admin/lv1.gif">');
			if ($current['infos']['level_depth'] == 1)echo ('<img src="../img/admin/lv2_b.gif">');
			if ($current['infos']['level_depth'] == 2)echo ('<img src="../img/admin/lv3_f.gif">');
			if ($current['infos']['level_depth'] == 3)echo ('<img src="../img/admin/lv4_b.gif">');
			echo preg_replace('/\d+\./','',$current['infos']['name']).'</label></td>
				</tr></table></div>';

			if (isset($categories[$id_category]))
				foreach ($categories[$id_category] AS $key => $row)
					recurseCategory($categories, $categories[$id_category][$key], $key, $id_selected);
		}

	$categories = Category::getCategories(3, false, $order = 'id_parent');
	recurseCategory($categories, $categories[0][1]);
	echo'</div></span></div><br><br>
	
	
	<dt>Марка (для фильтра марок)</dt>
	<dd>
	<select style="width: 147px;" name="supplier" id="supplier" >
	<option value="" selected>-= выберите =-</option>';
	$supplier = Supplier::getSuppliers($getNbProducts = false, $id_lang = 0, $active = false, $p = false, $n = false);
	foreach ($supplier as $items=>$item){
	$id_supplier = $item['id_supplier'];
	$name = $item['name'];
	echo '<option value="'.$id_supplier.'">'.$name.'</option>';
	}
	echo '</select>
	
	
	<dt>Производитель</dt>
	<dd>
	<select style="width: 147px;" name="manufacturer" id="manufacturer" >
	<option value="" selected>-= выберите =-</option>';
	$manufacturer = Manufacturer::getManufacturers($getNbProducts = false, $id_lang = 3, $active = true, $p = false, $n = false);
	foreach ($manufacturer as $items=>$item){
	$id_manufacturer = $item['id_manufacturer'];
	$name = $item['name'];
	echo '<option value="'.$id_manufacturer.'">'.$name.'</option>';
	}
	echo '</select>
	<dt>% наценки</dt>
	<dd><input style="width: 40px;" name="nacenka_percent" value="15" type="number"></dd>
	<dt>Вес товара</dt>
	<dd><input  style="width: 40px;" name="weight" value="2" type="number"></dd>
	<dt>Количество товара</dt>
	<dd><input  style="width: 40px;" name="quantity" value="2" type="number"></dd>
	<!--dt>Средняя стоимость доставки<br> по этому RSS (в валюте лота)</dt>
	<dd><input style="width: 40px;" name="delivery" value="13" type="number"></dd-->
	<dt>Мета-теги через запятую</dt>
	<dd><input name="tags" type="text"></dd>
	<dt>Краткое описание</dt>
	<dd><input name="desc_short" type="text"></dd>
	<dt>Активный товар?</dt>
	<dd><input name="active" type="checkbox" checked value="1"></dd>
	';
		if ($usd['conversion_rate'] == 0 or $usd['conversion_rate'] / $eur['conversion_rate'] == 0)
		{
			echo '<dt>Курс $</dt>
			<dd><input type="text" style="width: 40px;" required name="usd"></dd>
			<dt>Курс &euro;</dt>
			<dd><input type="text" style="width: 40px;" required name="eur"></dd>';
		}

		else
		{
			echo '<div style="background-color: #DEF;float: right;padding: 3px;border-radius: 5px;position: absolute;margin: -187px 276px; padding: 10px">
			<div style="font-weight: bold;line-height: 27px";>
			<span style=" color: #fc1921; ">$</span> <span style=" color: #0a2973; ">'.round($usd['conversion_rate'], 2).'</span><br>
			<span style=" color: #fc1921; ">&euro;</span> <span style=" color: #0a2973; ">'.round($usd['conversion_rate'] / $eur['conversion_rate'], 2).'</span>
			</div>
			</div>';
		}

	echo '
		<dt>Экспорт в csv</dt>
		<dd><input name="export" type="checkbox" '.($_POST['export'] ? 'checked':'').' value="csv"></dd>
	</dl>
	
	<input style="margin: 20px 0 0 165px" value="Выполнить" type="submit" />
	</fieldset>
</form>';


if ($_POST) {
//prettyDump($_POST);
	$ebay = new EbayParser();
	$parsed = $ebay->parse($_POST);
prettyDump($parsed);
}

?>
