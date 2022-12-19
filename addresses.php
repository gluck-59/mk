<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* SSL Management */
$useSSL = true;

// подключим скрипт google maps с левым API ключом (мой не работает)
// ;key=ABQIAAAACHCJdlgAEGcD_flKUFEmVhQlAYd20Yeej0MiKNuYPUGBnCwDThQlwUCPtCqtX3RC7LUKE-JYan3T4g"
$js_files = array('https://maps.google.com/maps?file=api&amp;v=2.x&amp></script>');

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=addresses.php');

$customer = new Customer(intval($cookie->id_customer));
if (!Validate::isLoadedObject($customer))
	die(Tools::displayError('customer not found'));

include(dirname(__FILE__).'/header.php');
$smarty->assign('addresses', $customer->getAddresses(intval($cookie->id_lang)));
$smarty->display(_PS_THEME_DIR_.'addresses.tpl');
include(dirname(__FILE__).'/footer.php');

?>
