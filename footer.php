<?php

if (isset($smarty))
{
	$smarty->assign(array(
		'HOOK_RIGHT_COLUMN' => Module::hookExec('rightColumn'),
		'HOOK_FOOTER' => Module::hookExec('footer'),
		'content_only' => intval(Tools::getValue('content_only'))));
	$smarty->display(_PS_THEME_DIR_.'footer.tpl');
}

/*
echo '<!-- jopa<pre>';
print_r($cookie);
echo '</pre-->';
*/


//<!-- SiteHeart code -->

// в опере не работает,периодически проверять
//if ( !strpos($_SERVER['HTTP_USER_AGENT'], 'OPR') AND !strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') ) 
{
    $json = array( 
    'nick' => (isset($cookie->customer_firstname) ? $cookie->customer_firstname." ".$cookie->customer_lastname : 'Я'), 
    'avatar' => '//motokofr.com/img/vtwin.png',
    'id' => (isset($cookie->id_customer) ? $cookie->id_customer : $cookie->id_guest),  
    'email' => $cookie->email,
    'data' => array('Город' => $city, 'Смотрел' => $cookie->viewed, 'Вишлист' => $cookie->id_wishlist, 'Валюта' => $cookie->id_currency)
    );
    
    $time        = time();
    $secret      = 'OYlcpGONf5'; // Секретный ключ,
    $user_base64 = base64_encode( json_encode($json) );
    $sign        = md5($secret . $user_base64 . $time);
    $auth        = $user_base64 . "_" . $time . "_" . $sign;
    
    ?>
    <script type="text/javascript">
        /*(function(){
        var widget_id = 650648;
        _shcp =[];
        _shcp.push({widget_id : 650648, auth : '<?php echo $auth;?>'});
        
        
        var lang =(navigator.language || navigator.systemLanguage 
        || navigator.userLanguage ||"en")
        .substr(0,2).toLowerCase();
        var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
        var hcc = document.createElement("script");
        hcc.type ="text/javascript";
        hcc.async =true;
        hcc.src =("https:"== document.location.protocol ?"https":"http")
        +"://"+ url;
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hcc, s.nextSibling);
        })();*/
    </script>
    <?php
}
//<!-- End SiteHeart code -->




/*echo "
<!-- Convead Widget -->
<script>
window.ConveadSettings = {
    visitor_uid: '".$cookie->id_guest."',
    visitor_info: {
        first_name: '".$cookie->customer_firstname."',
        last_name: '".$cookie->customer_lastname."',
        email: '".$cookie->email."',
    }, 
    app_key: '0827e3e940b26d3b9c28e27a84e9552f'
};

(function(w,d,c){w[c]=w[c]||function(){(w[c].q=w[c].q||[]).push(arguments)};var ts = (+new Date()/86400000|0)*86400;var s = d.createElement('script');s.type = 'text/javascript';s.async = true;s.src = '//tracker.convead.io/widgets/'+ts+'/widget-0827e3e940b26d3b9c28e27a84e9552f.js';var x = d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);})(window,document,'convead');
</script>
<!-- /Convead Widget -->
";
*/


//printf('<center><p style="color:#666; font-size:smallest;">%.2f с</p></center>', (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]));

//    echo 'jopa';
//    var_dump(error_reporting());
?>

