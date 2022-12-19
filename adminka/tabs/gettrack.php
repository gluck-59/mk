<?php
error_reporting(0);
$track_id = $_GET['track_id'];
$country_id = $_GET['country_id'];
$order_id = $_GET['order_id'];
$carrier_url = $_GET['carrier_url'];

//sleep(3);
//print_r($track_id);
	
	if (empty($track_id))
	 { 
		die('No parameters!');
	 }
	 
	$shipping_numbers = explode(";",$track_id);
	foreach ($shipping_numbers as $shipping_number) 
	{
	
		$track = simplexml_load_file('http://gdeposylka.ru/ws/x1/track.status/xml/?apikey=180.ba2ef1012d&id='.$shipping_number);
// https://code.google.com/p/gdeposylka-api/wiki/API_X1
		if ($track===false)
			{
				echo('Gdeposylka в дауне');
				exit;
			}
		
//		if ($track->tracks->track->code == "403") // если трек неизвестный, то добавляем
		if ($track->tracks->track->code > "0") // если трек неизвестный, то добавляем		
			{
				file_get_contents('http://gdeposylka.ru/ws/x1/track.add/xml/?apikey=180.ba2ef1012d&id='.$shipping_number.'&country='.$country_id.'&descr='.sprintf('%04d',$order_id));
				echo ('<p style="font-size:small">');
			}
		
		
			if ($track->tracks->track->info->status->message)
			{
echo $track->tracks->track->status.'<br>';			
				$string = $track->tracks->track->info->status->message;
				if ($track->tracks->track->status == "COMPLETE")
					{
						echo ('<p style="background-color:lightgreen; padding:5px; font-size:small">');	
					}
					elseif (stristr($string = $track->tracks->track->info->status->message, 'Прибыло в место вручения') !== false)
					{
						echo ('<p style="background-color:#fb7; padding:5px; font-size:small">');											
					}
					elseif (stristr($string = $track->tracks->track->info->status->message, 'Неудачная попытка вручения') !== false)
					{
						echo ('<p style="background-color:#fb7; padding:5px; font-size:small">');											
					}
					
					else
					{
						echo ('<p style="font-size:small">');
					}
					
			}
						
		//print_r($track->tracks->track);					
		echo ('<a href="'.str_replace('@', '#'.$shipping_number, $carrier_url).'" target="_blank"><b><u>'.$shipping_number.'</a></u></b> ');
		echo ('<u><a target="_blank" href="https://code.google.com/p/gdeposylka-api/wiki/API_X1">');
		
		if ($track->tracks->track->code == "300")
			echo ('<font color="orange">Внутренняя ошибка сервиса ('.$track->tracks->track->code.')</font>');
			elseif ($track->tracks->track->code == "302")
			echo ('<font color="orange">Таймаут при обработке трека ('.$track->tracks->track->code.')</font>');
			elseif ($track->tracks->track->code == "303")
			echo ('<font color="orange">Трек обрабатывается, будет доступен позже ('.$track->tracks->track->code.')</font>');
			elseif ($track->tracks->track->code == "304")
			echo ('<font color="orange">Слишком много треков в запросе ('.$track->tracks->track->code.')</font>');				
			elseif ($track->tracks->track->code == "400")
			echo ('<font color="red">Неизвестная команда ('.$track->tracks->track->code.')</font>');								
			elseif ($track->tracks->track->code == "401")
			echo ('<font color="red">Неправильный API Key ('.$track->tracks->track->code.')</font>');
			elseif ($track->tracks->track->code == "402")
			echo ('<font color="red">Неправильный формат трека ('.$track->tracks->track->code.')</font>');															
			elseif ($track->tracks->track->code == "403")
			echo ('<font color="green">Трек добавлен ('.$track->tracks->track->code.')</font>');			
			elseif ($track->tracks->track->code == "404")
			echo ('<font color="red">Не хватает некоторых обязательныx параметров ('.$track->tracks->track->code.')</font>');				
			elseif ($track->tracks->track->code == "405")
			echo ('<font color="red">Некорректное действие над треком ('.$track->tracks->track->code.')</font>');								
			elseif ($track->tracks->track->code == "406")
			echo ('<font color="red">Нет COUNTRY ('.$track->tracks->track->code.')</font>');
			elseif ($track->tracks->track->code == "407")
			echo ('<font color="red">Неправильный формат телефона ('.$track->tracks->track->code.')</font>');
			elseif ($track->tracks->track->code == "408")
			echo ('<font color="red">Недостаточно средств на счету ('.$track->tracks->track->code.')</font>');														
			elseif ($track->tracks->track->code == "409")
			echo ('<font color="red">Превышено количество треков на данном тарифном плане ('.$track->tracks->track->code.')</font>');	
			elseif ($track->tracks->track->code == "410")
			echo ('<font color="red">Превышено максимально возможное количество API запросов в единицу времени. На текущий момент разрешается делать не более 100 запросов в минуту, не более 2000 запросов в час ('.$track->tracks->track->code.')</font>');									
						
		echo ('</a></u> ');

		if ($track->tracks->track->code > "0")
		{
			echo ($track->tracks->track->info->status->date.'<br>');
			echo ($track->tracks->track->info->status->message.'</p>');
		}
}	
					
?>