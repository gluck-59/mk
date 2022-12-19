<?php
/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');

// подсадим глобальную куку чтобы узнавать юзера
// данные для глобальной куки возьмем из сессионной
setcookie("email", $cookie->email);
setcookie("firstname", $cookie->customer_firstname);

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=my-account.php');

// предзагрузим 
$preload = array(_PS_BASE_URL_.__PS_BASE_URI__.'history.php');

include(dirname(__FILE__).'/header.php');
$smarty->assign(array(
	'customer_firstname' => $cookie->customer_firstname,
	'voucherAllowed' => intval(Configuration::get('PS_VOUCHERS')),
	'returnAllowed' => intval(Configuration::get('PS_ORDER_RETURN')),
	'HOOK_CUSTOMER_ACCOUNT' => Module::hookExec('customerAccount')));

$smarty->display(_PS_THEME_DIR_.'my-account.tpl');

//print_r($_COOKIE);

include(dirname(__FILE__).'/footer.php');


?>