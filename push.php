<?php
//error_reporting(0);
  
if ($_POST)  
{
    echo '<pre>$_POST=';
    print_r($_POST);
    $text = $_POST['text'];
//    echo '$text = '.$text;
    push($text);
}

else
{
    echo '
    <html><body><meta charset="UTF-8">
        <form action="push.php" method="post">
            <input autofocus name="text" type="text">
            <input type="submit">
        </form>
    </body></html>
    ';
}  



  
function push($text)
{
    curl_setopt_array($ch = curl_init(), array(
    CURLOPT_URL => "https://pushall.ru/api.php",
    CURLOPT_POSTFIELDS => array(
        "type" => "broadcast",
        "id" => "74",
        "key" => "1adbcb37cc049e1b9115936ec1747c9b",
        "text" => "$text",
        "title" => "help.moto59"
      ),
      CURLOPT_SAFE_UPLOAD => true,
      CURLOPT_RETURNTRANSFER => true
    ));
    $a = curl_exec($ch); 
    curl_close($ch);
    return $a;
}

?>
