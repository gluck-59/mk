<?php

class Push extends ObjectModel
{
    public $title = 'нету заголовка';
    public $message = 'нету мессаги';
    public $url;
    public $type;
    public $id;    
    protected $key;
    public $icon;    
//    const _ICON_ = 'http://motokofr.com/img/logo/logo.png';
//    const _KEY_ = '65d28daa7a2f17944bbde01f49e70c53';

    public function __construct($type)
    {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->url = '//motokofr.com';
        $this->icon = '//motokofr.com/img/logo/logo.png';
        $this->type = $type;
            
        if (!$type OR $type == 'self')        // приватные пуши
        {
            $this->id = '1105';
            $this->key = '65d28daa7a2f17944bbde01f49e70c53';
            $this->type = 'self';  // self - самому себе и broadcast - вещание по каналу
        }

        
        if ($type == 'broadcast')   // канал рассылки
        {
            $this->id = '74';
            $this->key = '1adbcb37cc049e1b9115936ec1747c9b';
            $this->type = 'broadcast';
        }
        
// unicast - https://plus.google.com/+%D0%9A%D0%B0%D1%80%D0%BD%D0%B0%D1%83%D1%85%D0%BE%D0%B2%D0%9E%D0%BB%D0%B5%D0%B3/posts/6hUVxtw5cMz        
        
    }
    
    function send()
    {
ob_start();        
        curl_setopt_array($ch = curl_init(), array(
        CURLOPT_URL => "https://pushall.ru/api.php",
        CURLOPT_POSTFIELDS => array(
            "type" => $this->type,
            "id" => $this->id,
            "key" => $this->key,
            "text" => $this->message,
            "title" => $this->title,
            "icon" => $this->icon,
            "url" => $this->url
          ),
          CURLOPT_SAFE_UPLOAD => true,
          CURLOPT_RETURNTRANSFER => true
        ));

        curl_exec($ch);
        curl_close($ch);
ob_end_clean();        
    }


    function getType()
    {
        return $a->type;
    }

    function icon() {
      return self::_ICON_; 
    }
}
  

?>

<!--Для отправки уведомлений вы можете отправлять GET или POST запрос на адрес:
https://pushall.ru/api.php
Параметры:
key - ключ вашей подписки, или ключ безопасности для отправки Push себе
title - заголовок Push-уведомления
text - основной текст Push-уведомления
icon - иконка уведомления (необязательно)
url - адрес по которому будет осуществлен переход по клику (не обязательно)
hidden - 1 или 0 сразу скрыть уведомление из истории (по-умолчанию 0)
encode - ваша кодировка. (не обязательно) например cp1251-->