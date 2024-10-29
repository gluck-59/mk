<?php
error_reporting(E_ERROR);
/* валюта магазина — доллары */

include(dirname(__FILE__).'/../config/settings.inc.php');
include(dirname(__FILE__).'/../config/config.inc.php');

// внутри системы
//Currency::refreshCurrencies();
//die();

const GENERAL_COEFF = 1.153;
const USD_COEFF = 1.049;
const EUR_COEFF = 1.017;

//$curr = new Currency();
//$cuurency['iso_code'] = 'code';
//$cuurency['rate'] = 111;
//$currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
//prettyDump($curr->refreshCurrency($cuurency, '', new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))), 1);


// готовим мыло
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= ('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html><body>');
$to = 'support@motokofr.com';
$subject = 'Курсы валют';


// возьмем старые курсы для сравнения
$usd_old = Currency::getCurrency(3);
$eur_old = Currency::getCurrency(1);
$gbp_old = Currency::getCurrency(4);
$aud_old = Currency::getCurrency(8);

$usd_old = round($usd_old['conversion_rate'], 2);
$eur_old = round($usd_old / $eur_old['conversion_rate'], 2);
$gbp_old = round($usd_old / $gbp_old['conversion_rate'], 2);
//$aud_old = round($usd_old / $aud_old['conversion_rate'], 2);


//получим новые курсы
//  $usd = 0;
//  $eur = 0;

// kovalut сейчас не нужен, берем с центробанка  $kov = simplexml_load_file('http://informer.kovalut.ru/webmaster/xml-table.php?kod=7701');
    //if ($kov == false)

  	{
	$cbr = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?d=0');
echo '<pre>';
		if (empty($cbr))
	  	{
	  		$message = ('<p><span style="background:#fdd">cbr.ru в дауне, ничего не пишем</span></p>');
	  		unset($usd);
	  	}
	  	else
	  	{	  	
    		foreach ($cbr->Valute as $item) 
    		{
    			if ($item->NumCode=="840")  {
    				$usd = $item->Value;
    			}
    			if ($item->NumCode=="978")  {
    				$eur = $item->Value;
    			}
//    			if ($item->NumCode=="036")  {
//    				$aud = $item->Value;
//                }
//    			if ($item->NumCode=="826")  {
//    				$gbp = $item->Value;
//    			}
    		}
    		// математика для ЦБРФ
			$qwintry = 1.153;
    		$usd = round($usd*GENERAL_COEFF * USD_COEFF, 2);
    		$eur = round($eur*GENERAL_COEFF * EUR_COEFF, 2);
//    		$aud = round($aud*$qwintry * 1.017, 2);
//    		$gbp = round($gbp*$qwintry * 1.017, 2);
		}
  	}

/*
	else
	{
    //	берем с kovalut
        error_reporting(E_ALL ^ E_NOTICE);
    	foreach ($kov->Actual_Rates->Bank as $bank) 
    	{
    		if ($bank->Name=="Сбербанк России")  
    		    {
    		       $usd = $bank->USD->Sell;
    		       $eur = $bank->EUR->Sell;
    		}
    	}
    	if (!$usd or !$eur) // смотрим старые курсы
    	{
    	  $message .= ('<p><span style="background:#fdd">Новых курсов нет, используем старые</span></p>');
    	  foreach ($kov->Not_Actual_Rates->Bank as $bank) 
    		{
    		if ($bank->Name=="Сбербанк России")  
    		    	{
    		       $usd = $bank->USD->Sell;
    		       $eur = $bank->EUR->Sell;
    			}
    		}
    	}

        $usd = str_replace(",", ".", $usd);
        $eur = str_replace(",", ".", $eur);
	}
*/
	

		
		

$message .= '<p style="margin-bottom:-10px;"><span style="font-size:16pt; ';
	if ($usd < $usd_old) $message .= 'background:#dfd';
	if ($usd > $usd_old) $message .= 'background:#fdd';
$message .= '">$<strong> ' .$usd.'</strong></span>&nbsp;</p>';
$message .= '<p><span style="font-size:16pt; ';
	if ($eur < $eur_old) $message .= 'background:#dfd';
	if ($eur > $eur_old) $message .= 'background:#fdd';
$message .= '">€<strong> ' .$eur.'</strong></span></p>';

// добавим графики курсов
$from_date = date('m/d/Y', time()-1209600);
$to_date = date('m/d/Y');

//echo $from_date.'<br>'.$to_date;


$message .='<p>
<img style="width: 400px;" src="http://www.cbr.ru//Queries/UniDbQuery/GenerateChart/41392?Posted=True&mode=2&VAL_NM_RQ=R01235&FromDate='.$from_date.'&ToDate='.$to_date.'&grafIndex=0">
<img style="width:400px;" src="http://www.cbr.ru//Queries/UniDbQuery/GenerateChart/41392?Posted=True&mode=2&VAL_NM_RQ=R01239&FromDate='.$from_date.'&ToDate='.$to_date.'&grafIndex=0">
</p>';



$eur = $usd / $eur;

//$usd = number_format($usd, 3, '.', '');
//$eur = number_format($eur, 3, '.', '');


$prestaUsd = new Currency(Currency::getIdByIsoCode('RUB'));
$prestaUsd->conversion_rate = $usd;
$prestaUsd->save();

$prestaEur = new Currency(Currency::getIdByIsoCode('EUR'));
$prestaEur->conversion_rate = $eur;
$prestaEur->save();


//var_dump($usd);
//var_dump($eur);
//prettyDump(new Currency(Currency::getIdByIsoCode('USD')));
//prettyDump(new Currency(Currency::getIdByIsoCode('EUR')));

$message .='<p>Старые курсы:<br>$';
$message .= $usd_old;
$message .='<br>€';
$message .= $eur_old;
$message .='<br>£';
$message .= round($usd_old / $gbp,2);
$message .='</p>';

/* блок записи в базу -- ДЛЯ ОТЛАДКИ ОТКЛЮЧАТЬ ЗДЕСЬ
$link = mysql_connect('localhost', _DB_USER_, _DB_PASSWD_);
if (!$link) {
	$message .= mysql_error();
}

$db_selected = mysql_select_db(_DB_NAME_, $link);
if (!$db_selected) {
	$message .= mysql_error();
}

if ($usd !=0 and $eur !=0 and $gbp !=0 and $aud !=0)
	{
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$usd.' where id_currency = 3');
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$eur.' where id_currency = 1');
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$gbp.' where id_currency = 4');	
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$aud.' where id_currency = 8');	
		
		if (!$result) {
			$message .= mysql_error();
		}
	$message .= mysql_error();	
	$message .= "<br>Запись в базу ОК";
	}
else $message .= ('<p><span style="background:#fdd">в значениях нули, в базу не пишем</span></p>');
mysql_close($link);

/* блок записи в базу */

$message .= ('</body></html>');
//mail($to, $subject, $message, $headers);

//echo $message;

?>

