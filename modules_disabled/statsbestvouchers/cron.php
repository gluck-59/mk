<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

mysql_query("SET lc_time_names = 'ru_RU'");
 

$users = Db::getInstance()->ExecuteS('
SELECT c.firstname, c.lastname, c.email, d.name, round(d.value) as value, round(d.minimal) as minimal, d.date_to as date, DATE_FORMAT(d.`date_to`, "%d %M %Y") as d
FROM presta_discount d
LEFT JOIN presta_customer c ON d.id_customer = c.id_customer AND d.active = 1
WHERE d.quantity > 0
AND c.email !=""
AND c.`deleted` = 0
AND DATE(d.date_to) BETWEEN NOW() + INTERVAL 7 DAY AND NOW() + INTERVAL 8 DAY
	');

if (!$users) 
{
	die ('Протухающих купонов сегодня нет.');
}
else
{
	$report = ("Протухающие купоны:\r\n");
			foreach ($users as $user)
			{
				Mail::Send(intval(Configuration::get('PS_LANG_DEFAULT')), 'voucher', '3аканчивается купон на скидку!', array('{firstname}' => $user['firstname'], '{name}' => $user['name'], '{value}' => $user['value'], '{minimal}' => $user['minimal'], '{d}' => $user['d']), $user['email'], NULL, strval(Configuration::get('PS_SHOP_EMAIL')), strval(Configuration::get('PS_SHOP_NAME')), NULL, NULL, dirname(__FILE__).'/mails/');
	
				$report = $report.$user['firstname'].' '.$user['lastname'].' ('.$user['email'].")\r\n";
			}
					
	//Вывод в отчетное мыло 
	echo($report);
}

		
?>