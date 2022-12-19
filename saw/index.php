<?php

Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
Header("Cache-Control: private, no-cache, max-age=0"); // HTTP/1.1 
Header("Pragma: no-cache"); // HTTP/1.1 
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");



include('header.html');
//include('plan.svg');
//include('footer.html');
// теоресиськи если php на этом этапе нам не нужен, можно index.php не использовать вообще
    
?>