<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/terminalpay.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
$terminalpay = new TerminalPay();
echo $terminalpay->execPayment($cart);

include_once(dirname(__FILE__).'/../../footer.php');

?>