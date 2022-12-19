<?php
$group_id = 26469673; // id группы
$my_id = 18107751;
$confirmation_token = "9f9e80f1"; // Код со страницы настроек
$access_token = "46d51bd90ae693e35450a6a248d5efb6b1a02d80a378ea4fd1d73f91e60279284bcb7029782ff7a114c94"; // Ключ доступа, полученный из вк
$secret = "zhopaNhbUdjplz"; // секртная фраза которую вы указали в вк
$log_file = "vk_events_log.txt";
    
$data = json_decode(file_get_contents('php://input'));
if (!isset($data->secret))
    {
        if ($data->type == "confirmation")  
            die($confirmation_token);
        else
            die();
} 
else 
{
    check_secret($data->secret);
    unset($data->secret);
    
    $user_id = $data->object->user_id;
    if (!$user_id) $user_id = $data->object->from_id;
    $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&v=5.0"));
    $user_firstname = $user_info->response[0]->first_name;
    $user_lastname = $user_info->response[0]->last_name;
    $attachments = $data->object->attachments;
    
    $group = json_decode(file_get_contents("https://api.vk.com/method/groups.getById?group_id={$data->group_id}&v=5.0"));
    $group_name = $group->response[0]->name;

    switch ($data->type) 
    {
        // Новое сообщение
        case 'message_new':
            // формируем текст для отправки сообщения
            $text = "Новое сообщение в группе {$group_name} от {$user_firstname} {$user_lastname} https://vk.com/id{$user_id} \r\n {$data->object->body} \r\n https://vk.com/gim{$group_id}";
            echo('ok');
            break;
    
    
        // Пользователь покинул группу
        case 'group_leave':
            $self = $data->object->self;
            
            // формируем текст для отправки сообщения            
            $text = "{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) покинул группу {$group_name} ";
            $text .= $self == 1 ? "" : "по решению администрации";
    
            echo('ok');
            break;
            
            
            
        // Пользователь вступил в группу
        case 'group_join':
            // Получаем информацию о пользователе, который покинул группу
            $join_type = $data->object->join_type;
            // https://vk.com/dev/callback_api
            if ($join_type == "join") 
                $text = "{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) вступил в группу {$group_name}";

            elseif ($join_type == "unsure")
                $text = "{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) возможно пойдет на {$group_name}";
                
            elseif ($join_type == "accepted")
                $text = "{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) принял приглашение в {$group_name}";                
                
            elseif ($join_type == "approved")
                $text = "Заявка {$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) на вступление в {$group_name} была одобрена руководителем сообщества";

            elseif ($join_type == "request")
                $text = "{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) подал заявку на вступление в {$group_name}";

            else
                $text = "Неизвестный join_type в группе {$group_name}, юзер {$user_firstname} {$user_lastname} (https://vk.com/id{$user_id})";
                
            echo('ok');
            break;
            
            
        // Ответ в группу
        case 'wall_reply_new':
            $post_id = $data->object->id;
            $text = ("{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) ответил в группу {$group_name}: \r\n {$data->object->text} \r\n https://vk.com/wall-{$group_id}_{$post_id}");

            echo('ok');
            break;                
               
                
                
        // Новая запись на стене - оповещение о предложенных записях - нужно поставить соответствующую галочку в настройках типов событий
        case "wall_post_new":
            $post_id = $data->object->id;
            $post_type = $data->object->post_type;
            
            // Если это предложенная запись            
            if ($post_type == "suggest")  
            {                 
                $text = "{$user_firstname} {$user_lastname} (https://vk.com/id{$user_id}) предложил запись в группу {$group_name}: \r\n {$data->object->text} \r\n https://vk.com/wall-{$group_id}_{$post_id}";
            }
            
            // Если это пост от юзера
            elseif ($post_type == "post")                 
            {
                $text = "Новый пост в {$group_name} от {$user_firstname} {$user_lastname} https://vk.com/id{$user_id}: \r\n {$data->object->text} \r\n https://vk.com/wall-{$group_id}_{$post_id}";
            }
            echo('ok');
            break;
    


            // обсуждения
        case 'board_post_new':
            $post_id = $data->object->id;
            $text = ("Ответ в Обсуждения от {$user_firstname} {$user_lastname} в группе {$group_name}: {$data->object->text}\r\n https://vk.com/topic{$data->object->topic_owner_id}_{$data->object->topic_id}?post={$post_id}");
            echo('ok');
            break;       
    



        default:
            $text = __LINE__." Произошло неописанное событие {$data->type} в группе {$group_name}\r\n";
            $text .= print_r($data->object, true);
            //echo('не описано событие '.$data->type);
            break;
        }
        
        // отправляем сформированное сообщение
        send_message_to_me($text);
}
    




    
function check_secret($key) {
    global $secret;
    if ($key != $secret) {
        echo("error");
    }
}

function send_message_to_me($text) {
    global $my_id, $access_token, $log_file;
    
    $request_params = array(
        'message' => $text,
        'user_id' => $my_id, 
        'access_token' => $access_token,
        'v' => '5.0'
    );
    
    $get_params = http_build_query($request_params);
    
    $log = date('d-m-Y G:i:s').'  ';
    $log .= file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
    $log .= "\r\n";
    file_put_contents($log_file, $log, FILE_APPEND);
}





?>