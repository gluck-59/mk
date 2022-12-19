<?php

//require_once '../../composer/vendor/autoload.php';
//require_once '../../composer/vendor/gdeposylka/api-client/src/apikey.php';

//$client = new GdePosylka\Client\Client($apikey);

//echo '<pre>';

$track_id = $_GET['track_id'];
$order_id = $_GET['order_id']; // была нужна для addTracking в API 3, теперь это выключено
$carrier_url = $_GET['carrier_url'];

//if (empty($track_id))
//    die('$track_id не передан');

$shipping_numbers = explode(";",$track_id);

foreach ($shipping_numbers as $shipping_number) 
{
    // вычислим перевозчика 
    $couriersResponse = send('detect', $shipping_number);

    if ($couriersResponse->length != 1)
    {
        // если посылка из Германии DHL, установим deutsche-post
        if (preg_match('/^\d{12}$/', trim($shipping_number)))
            $courierSlug = 'deutsche-post';
        else // если посылка ХЗ чья, установим russian-post
            $courierSlug = 'russian-post';
    }
    else 
    {
        $courierSlug = $couriersResponse->data[0]->courier->slug;
    }
    
    


    // отследим что получилось
    $response = send($courierSlug, $shipping_number);
    
    if ( $response->result == 'waiting' )
    {
        echo '<p style="padding:5px; font-size:small">'.$response->data->tracking_number. ($response->data->courier->name ? ' ('.$response->data->courier->name.')' : '')." недавно добавлен, чекпоинтов пока нет</p>";
    }

    if ( $response->result == 'success' )
    {
        foreach ($response->data->checkpoints as $checkpoint) 
        {
            // покрасим фон в оранжевый, если хотя бы один чекпоинт имеет status = arrived
            if ( $checkpoint->status_code == 'arrived' OR $checkpoint->status_code == 'other' )
            {
                $background = 'background-color:#fb7;';
            }                


        // покрасим фон в зеленый, если посылка доставлена
        if ( $response->data->is_delivered === true  )
        {
            $background = 'background-color:#cfc;';
        }

        echo "<p style=\"$background padding:5px; font-size:small\">";
        echo '<a href="'.str_ireplace('@', '/#'.strtoupper($response->data->tracking_number), $carrier_url).'" target="_blank"><b><u>'.$response->data->tracking_number.'</a></u></b>'.($response->data->courier->name ? ' ('.$response->data->courier->name.')' : '').'<br>';
        echo $checkpoint->location_translated .'<br>'. $checkpoint->time.'<br>'. $checkpoint->status_name.'</p>';
        unset($background);        
        break; // нужен только первый статус
        }
    }
}

unset($track_id, $order_id, $carrier_url);





function send($method, $shipping_number)
{
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://gdeposylka.ru/api/v4/tracker/{$method}/{$shipping_number}",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "x-authorization-token: 22fc09c472a627c525373311a54ffa7d20e44fb9d1f9cab00c2d413402be853e367f71b1569cb26c"
      ),
    ));

    try   
    {  
        $response = curl_exec($curl);
     
        if (curl_error($curl)) 
            return curl_error($curl);
        else
            return json_decode($response);
    }
    catch (Exception $e) 
    {
/*
        $err = curl_error($curl);
        //echo "cURL Error #:" . $err;
        $push = new Push('self');
        $push->title = 'Гдепосылка-Кабинет ошибка:';
        $push->message = $track.' Ошибка CUrl '.$err;
        $push->url = 'http://pushall.ru/';
        $push->send();
        die;
*/
    }

    curl_close($curl);

}



    /*
    // возвращает последний статус трека
    // используется в кабинете и в админке
    */    
    function getStatus($track, $last = 0)
    {
        $courier = send('detect', $track);

        if ($courier->length != 1)
        {
            // если посылка из Германии DHL, установим deutsche-post
            if (preg_match('/^\d{12}$/', trim($track)))
                $courierSlug = 'deutsche-post';
            else // если посылка ХЗ чья, установим russian-post
                $courierSlug = 'russian-post';
        }
        else 
        {
            $courierSlug = $courier->data[0]->courier->slug;
        }


        
        $statuses = send($courierSlug, $track);
        if (isset($statuses->error))
        {
           return $statuses->error;
        }




        if ($last != 1 && $statuses)
        {
            $all_status = array();
            foreach ($statuses->data->checkpoints as $status)
            {
                $status->track = $track;
                $all_status[] = $status;
            }

            
//            $all_status['track'] = $track;
            return $all_status;
        }
        
        if ($last == 1 && $statuses)
        {
            foreach ($statuses->data->checkpoints as $last_status)
            {
                $last_status->track = $track;
                return $last_status;
            }
        }
    }
    
					
?>