<?php
include(dirname(__FILE__).'/../config/settings.inc.php');
include(dirname(__FILE__).'/../config/config.inc.php');
$cfgFile = dirname(__FILE__).'/../config/settings.inc.php';
require_once($cfgFile);
include_once "../gdeposylka_test/GdePosylkaClient.php";
require_once _PS_ROOT_DIR_."/composer/vendor/gdeposylka/api-client/src/apikey.php";
$client = new GdePosylkaClient($apikey);
//die 'jopa';
$db = mysql_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_);
if (!$db)
{
    die('No connection to database');
}
mysql_select_db(_DB_NAME_, $db);
mysql_query("SET NAMES 'utf8'");

//echo '<pre>';

// готовим мыло
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= ('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html><body>');
$to = 'support@motokofr.com';
$subject = 'Статистика доставки посылок';

// запросим gdeposylka раздел 
$track_list = $client->getTrackingList('archive');
//$track_list = $client->getTrackingList('default'); // default = текущий раздел

/*
echo '<pre>--';
print_r($client);
die;
*/

if (!$track_list) 
{
	echo $message = '<p><span style="background:#fdd">Gdeposylka в дауне?</span></p>';
	mail($to, $subject, $message, $headers);
	}
else 
{
	$days_list_ems = array();
	$days_list_post = array();	
	$count_ems = 0;
	$count_post = 0;	
	foreach ($track_list['result']['trackings'] as $track )
	{

//echo ($track['courier_slug'].' '.$track['tracking_number'].'<br>');
		// запросим gdeposylka "время в пути" по каждому доставленному "is_delivered" треку 
        if ($track['is_delivered'] == 1)
        {
            // для EMS
            $ems = strripos($track['tracking_number'], 'E');
            $spsr = strripos($track['tracking_number'], 'WNM');
            if ($ems === 0)
            {
                $c = $client->getTrackingInfo($track['courier_slug'], $track['tracking_number']);
                $days_list_ems[] = $client->getTravelTime($c);
                $count_ems++;
            }
            // для СПСР - пропускаем
            elseif ($spsr === 0)
            {
                continue;
            }
            // для почты
            else
            {
                $c = $client->getTrackingInfo($track['courier_slug'], $track['tracking_number']);
                $days_list_post[] = $client->getTravelTime($c);
                $count_post++;
            }
		}
    }

	$avg_delibery_days_ems = round(median($days_list_ems));
	$avg_delibery_days_post = round(median($days_list_post));	

	// EMS: если колво дней не равно 0 && колво дней меньше 300 (для оборотных треков)    	
	if ($avg_delibery_days_ems && $avg_delibery_days_ems != 0 && $avg_delibery_days_ems < 300)
		$result_ems = mysql_query('update presta_carrier set `avg_delibery_days` = '.intval($avg_delibery_days_ems).' where `id_carrier` = 74');

    else
    {
        //сколько дней было в базе раньше
        $avg_old = new Carrier(74); 
        $avg_delibery_days_ems = $avg_old->avg_delibery_days;
    }

    // остальные: если колво дней не равно 0 && колво дней меньше 300 (для оборотных треков)		
    if ($avg_delibery_days_post && $avg_delibery_days_post != 0 && $avg_delibery_days_post < 300)
		$result_post = mysql_query('update presta_carrier set `avg_delibery_days` = '.intval($avg_delibery_days_post).' where `id_carrier` = 79');

    else
    {
        //сколько дней было в базе раньше
        $avg_old = new Carrier(79); 
        $avg_delibery_days_ems = $avg_old->avg_delibery_days;
    }

	$message .= 'Обработано '.$count_ems.' треков EMS и '.$count_post.' треков обычной почты.<br>';
	$message .= '<p>Статистика доставки EMS ';
	$message .= $avg_delibery_days_ems;
	$message .= ' дней<br>';
	$message .= 'Статистика доставки почты-СПСР-др. ';
	$message .= $avg_delibery_days_post;
	$message .= ' дней</p>';
	$message .= "<p>Запись в базу вроде как ОК<p>";
	


	if (!$result_ems OR !$result_post)
	{
		$message .= '<p><span style="background:#fdd">Глюк статистики доставки посылок, нет одного из $result</span></p>';
	}

	
	mail($to, $subject, $message, $headers);
	echo $message;
}



//Медиана от массива $days_list
function median($days_list) 
{ 
	sort ($days_list);
	$count = count($days_list);
	$middle = floor($count/2);
	if ($count%2) return $days_list[$middle];
	else return ($days_list[$middle-1]+$days_list[$middle])/2;
}

?>