<?php

//include(dirname(__FILE__).'/start.php');

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (stripos($user_agent, 'MSIE 6.0') !== false && stripos($user_agent, 'MSIE 8.0') == false && stripos($user_agent, 'MSIE 7.0') === false) 
{
if (!isset($HTTP_COOKIE_VARS["ie"])) 
	{
	setcookie("ie", "yes", time()+60*60*24*360);
	header ("Location: /ie6/index.html");
	}
}

// P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

require_once(dirname(__FILE__).'/init.php');

/* CSS */
$css_files[_THEME_CSS_DIR_.'global.css'] = 'all';

/* Hooks are volontary out the initialize array (need those variables already assigned) */
$smarty->assign(array(
	'HOOK_HEADER' => Module::hookExec('header'),
	'HOOK_LEFT_COLUMN' => Module::hookExec('leftColumn'),
	'HOOK_TOP' => Module::hookExec('top'),
	'static_token' => Tools::getToken(false),
	'token' => Tools::getToken(),
	'priceDisplayPrecision' => _PS_PRICE_DISPLAY_PRECISION_,
	'content_only' => intval(Tools::getValue('content_only'))
));

if(isset($css_files) AND !empty($css_files)) $smarty->assign('css_files', $css_files);
if(isset($js_files) AND !empty($js_files)) $smarty->assign('js_files', $js_files);
if(isset($preload) AND !empty($preload)) $smarty->assign('preload', $preload);

/* Display a maintenance page if shop is closed */
if (isset($maintenance) AND (!isset($_SERVER['REMOTE_ADDR']) OR $_SERVER['REMOTE_ADDR'] != Configuration::get('PS_MAINTENANCE_IP')))
{
	header('HTTP/1.1 503 temporarily overloaded');
	$smarty->display(_PS_THEME_DIR_.'maintenance.tpl');
	exit;
}

$smarty->display(_PS_THEME_DIR_.'header.tpl');

/*
echo '<!--jopa<pre>';
print_r($cookie);
echo '</pre-->';
*/


?>
