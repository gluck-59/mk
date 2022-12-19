<?php

/* валюта магазина — рубли */

include(dirname(__FILE__).'/../config/settings.inc.php');
include(dirname(__FILE__).'/../config/config.inc.php');

// готовим мыло
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= ('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html><body>');
$to = 'support@motokofr.com';
$subject = 'Курсы валют';


// возьмем старые курсы для сравнения
$usd_old = Currency::getCurrency(2);
$eur_old = Currency::getCurrency(1);
$gbp_old = Currency::getCurrency(4);
$aud_old = Currency::getCurrency(8);
$usd_old = round(1 / $usd_old['conversion_rate'], 2);
$eur_old = round(1 / $eur_old['conversion_rate'], 2);
$gbp_old = round(1 / $gbp_old['conversion_rate'], 2);
$aud_old = round(1 / $aud_old['conversion_rate'], 2);


//получим новые курсы
//  $usd = 0;
//  $eur = 0;
  error_reporting(0);
  $kov = simplexml_load_file('http://informer.kovalut.ru/webmaster/xml-table.php?kod=7701');

  if ($kov == false)
  	{
  	$message .= ('<p><span style="background:#fdd">kovalut.ru в дауне, получаем курсы с ЦБРФ + математика</span></p>');
  	//получаем курсы (старый способ)
	$cbr = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?d=0');
		if ($cbr == false)
	  	{
  		$message .= ('<p><span style="background:#fdd">cbr.ru тоже в дауне, используем старые курсы</span></p>');
  		$usd = $usd_old;
   		$eur = $eur_old;
	  	}
	  	
	  	else
	  	{	  	
		foreach ($cbr->Valute as $item) {
			if ($item->NumCode=="840")  {
				$usd = $item->Value;
			}
			if ($item->NumCode=="978")  {
				$eur = $item->Value;
			}
		}
		// математика для ЦБРФ
		$usd = round($usd * 1.044, 2);
		$eur = round($eur * 1.04, 2);
		}
  	}

	else
	{
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
	$usd = floatval($usd);
	$eur = floatval($eur);
	}
	
// получаем GBP и AUD
	$cbr = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?d=0');
		if ($cbr == false)
	  	{
	  		$message .= ('<p><span style="background:#fdd">cbr.ru в дауне, GBP и AUD не обновлены</span></p>');
	  	}
	  	
	  	else
	  	{	  	
			foreach ($cbr->Valute as $item) 
			{
				if ($item->NumCode=="036")  {
					$aud = $item->Value;
				}
				if ($item->NumCode=="826")  {
					$gbp = $item->Value;
				}
			}
		}
		
		
	

if ($usd == 0) $usd = $usd_old;
if ($eur == 0) $eur = $eur_old;
if ($aud == 0) $aud = $aud_old;
if ($gbp == 0) $gbp = $gbp_old;

//$usd = $usd-1;
//$eur = $eur+1;

$message .= '<p style="margin-bottom:-10px;"><span style="font-size:16pt; ';
	if ($usd < $usd_old) $message .= 'background:#dfd';
	if ($usd > $usd_old) $message .= 'background:#fdd';
$message .= '">$<strong> ' .$usd.'</strong></span></p>';
$message .= '<p><span style="font-size:16pt; ';
	if ($eur < $eur_old) $message .= 'background:#dfd';
	if ($eur > $eur_old) $message .= 'background:#fdd';
$message .= '">€<strong> ' .$eur.'</strong></span></p>';

// старый способ вывода в мыло
//$message .= "<p><span style='background:#fdd'>€" .$eur."\r\n</span></p>";

// добавим графики курсов
$from_date = date('d.m.Y', time()-604800);
$to_date = date('d.m.Y');
$message .='<p><img style="width:300px" src="http://www.cbr.ru/currency_base/GrafGen.aspx?date_req1='.$from_date.'&amp;date_req2='.$to_date.'&amp;VAL_NM_RQ=R01235">
<img style="width:300px" src="http://www.cbr.ru/currency_base/GrafGen.aspx?date_req1='.$from_date.'&amp;date_req2='.$to_date.'&amp;VAL_NM_RQ=R01239">
</p>';


$usd = 1 / $usd;
$eur = 1 / $eur;
$gbp = 1 / $gbp;
$aud = 1 / $aud;
$usd = number_format($usd, 6, '.', '');
$eur = number_format($eur, 6, '.', '');
$gbp = number_format($gbp, 6, '.', '');
$aud = number_format($aud, 6, '.', '');

$message .='<p>Старые курсы:<br>$';
$message .= $usd_old;
$message .='<br>€';
$message .= $eur_old;
$message .='<br>£';
$message .= round(1 / $gbp,2);
$message .='</p>';


/* пишем в базу */
$link = mysql_connect('localhost', _DB_USER_, _DB_PASSWD_);
if (!$link) {
	$message .= mysql_error();
//    die('ERROR: ' . mysql_error());
}

$db_selected = mysql_select_db(_DB_NAME_, $link);
if (!$db_selected) {
	$message .= mysql_error();
//    die ('Не удалось выбрать базу: ' . mysql_error());
}

if ($usd !=0 and $eur !=0 and $gbp !=0 and $aud !=0)
	{
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$usd.' where id_currency = 2');
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$eur.' where id_currency = 1');
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$gbp.' where id_currency = 4');	
	$result = mysql_query('UPDATE presta_currency SET conversion_rate = '.$aud.' where id_currency = 8');	
		
		if (!$result) {
			$message .= mysql_error();
	//	    die(' — Неверный запрос: ' . mysql_error());
		}
	$message .= mysql_error();	
	$message .= "1/$=" .$usd.", 1/€=" .$eur;
	$message .= "<br>Запись в базу ОК";
	}
else $message .= ('<p><span style="background:#fdd">в значениях нули, в базу не пишем</span></p>');
	
mysql_close($link);
$message .= ('</body></html>');

mail($to, $subject, $message, $headers);
echo $message;

?>

