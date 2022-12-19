<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/yandexdengi.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
$yandexdengi = new YandexDengi();
echo $yandexdengi->execPayment($cart);

include_once(dirname(__FILE__).'/../../footer.php');

?>