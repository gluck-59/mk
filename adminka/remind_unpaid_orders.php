<?php
//include(dirname(__FILE__).'/../config/settings.inc.php');
include(dirname(__FILE__).'/../config/config.inc.php');
$link = mysql_connect('localhost', _DB_USER_, _DB_PASSWD_);
if (!$link) {
    die('ERROR: ' . mysql_error());
}

$db_selected = mysql_select_db(_DB_NAME_, $link);
if (!$db_selected) {
    die ('Не удалось выбрать базу: ' . mysql_error());
}

// готовим мыло
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= ('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html><body>');
$to = 'support@motokofr.com';
$subject = 'Просроченные заказы';

echo('<pre>');

// возьмем все невалидные заказы за последнюю неделю
// статус "отменено" не проверяется!
$date_from = date('Ymd', strtotime('-17 days'));
$today = date('Ymd');
$orders = Order::getOrdersRemindByDate($date_from, $today, NULL, 1);

// зададим через сколько дней посылать напоминания и отменять заказ
$remind1 = 1;
$remind2 = 2;
$cancel_order = 3;

foreach ($orders as $order)
{
    if ($order['date_add'] == $today-$remind1)
    { 
        $message .= $order['id_order']." — напоминание №1<br>";
    }


    if ($order['date_add'] == $today-$remind2)
    {
        $message .= $order['id_order']." — напоминание №2<br>";
    }


    if ($order['date_add'] == $today-$cancel_order)
    {
        $message .= $order['id_order']." — отменить заказ<br>";
    }
}

$states = OrderState::getOrderStates(3);

print_r($orders);
//print_r($states);

echo('</pre>');

if ($message) mail($to, $subject, $message, $headers);
echo $message;


?>