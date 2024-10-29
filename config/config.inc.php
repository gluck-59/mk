<?php
/* Improve PHP configuration to prevent issues */
@error_reporting(E_ERROR );
@ini_set('display_errors', (in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1', '0.0.0.0', 'localhost']) ? 'on' : 'off'));

//var_dump(error_reporting());

@ini_set('upload_max_filesize', '100M');
@ini_set('default_charset', 'utf-8');
// @ini_set('max_input_vars', 10000); //не работает, надо увеличить колво полей

/* Correct Apache charset */
header('Content-Type: text/html; charset=utf-8');

/*
 * It is not safe to rely on the system's timezone settings, but we can\'t easily determine the user timezone and the use of this function cause trouble for some configurations.
 * This will generate a PHP Strict Standards notice. To fix it up, uncomment the following line.
 */

/* Autoload */
/*function __autoload($className)
{
	if (!class_exists($className, false))
		require_once(dirname(__FILE__).'/../classes/'.$className.'.php');
}*/

    spl_autoload_register(function($className) {
//        include 'classes/' . $class . '.class.php';
        require_once(dirname(__FILE__).'/../classes/'.$className.'.php');
    });




/* No settings file? goto installer...*/
if (!file_exists(dirname(__FILE__).'/settings.inc.php'))
{
	$dir = ((is_dir($_SERVER['REQUEST_URI']) OR substr($_SERVER['REQUEST_URI'], -1) == '/') ? $_SERVER['REQUEST_URI'] : dirname($_SERVER['REQUEST_URI']).'/');
	if(!file_exists(dirname(__FILE__).'/../install'))
		die('Error: \'install\' directory is missing');
	Tools::redirect('install', $dir);
}
include(dirname(__FILE__).'/settings.inc.php');

/* Redefine REQUEST_URI if empty (on some webservers...) */
if (!isset($_SERVER['REQUEST_URI']) OR empty($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
	if (isset($_SERVER['QUERY_STRING']) AND !empty($_SERVER['QUERY_STRING']))
		$_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
}

$currentDir = dirname(__FILE__);
/* Base and themes */

define('_THEMES_DIR_',     __PS_BASE_URI__.'themes/'); // оригинал
//define('_THEMES_DIR_',     'http://cdn.motokofr.com/themes/');

define('_THEME_IMG_DIR_',  _THEMES_DIR_._THEME_NAME_.'/img/'); 
define('_THEME_CSS_DIR_',  _THEMES_DIR_._THEME_NAME_.'/css/');  
define('_THEME_JS_DIR_',   _THEMES_DIR_._THEME_NAME_.'/js/');

define('_THEME_CAT_DIR_',  __PS_BASE_URI__.'img/c/'); // оригинал
//define('_THEME_CAT_DIR_',  'http://cdn.motokofr.com/img/c/'); 

define('_THEME_PROD_DIR_', __PS_BASE_URI__.'img/p/'); // оригинал
//define('_THEME_PROD_DIR_', 'http://cdn.motokofr.com/img/p/');

define('_THEME_PROD_PIC_DIR_', __PS_BASE_URI__.'upload/');
define('_THEME_MANU_DIR_', __PS_BASE_URI__.'img/m/');
define('_THEME_SCENE_DIR_', __PS_BASE_URI__.'img/scenes/');
define('_THEME_SCENE_THUMB_DIR_', __PS_BASE_URI__.'img/scenes/thumbs');
define('_THEME_SUP_DIR_',  __PS_BASE_URI__.'img/su/');
define('_THEME_SHIP_DIR_', __PS_BASE_URI__.'img/s/');
define('_THEME_LANG_DIR_', __PS_BASE_URI__.'img/l/');
define('_THEME_COL_DIR_',  __PS_BASE_URI__.'img/co/');
define('_SUPP_DIR_',       __PS_BASE_URI__.'img/su/');
define('_THEME_DIR_',      _THEMES_DIR_._THEME_NAME_.'/');
define('_MAIL_DIR_',        __PS_BASE_URI__.'mails/');
define('_MODULE_DIR_',        __PS_BASE_URI__.'modules/');

define('_PS_IMG_',         __PS_BASE_URI__.'img/');  // оригинал
//define('_PS_IMG_',         'http://cdn.motokofr.com/img/');  

define('_PS_ADMIN_IMG_',   _PS_IMG_.'admin/');

/* Directories */
define('_PS_ROOT_DIR_',             realpath($currentDir.'/..'));
define('_PS_CLASS_DIR_',            _PS_ROOT_DIR_.'/classes/');
define('_PS_TRANSLATIONS_DIR_',     _PS_ROOT_DIR_.'/translations/');
define('_PS_DOWNLOAD_DIR_',         _PS_ROOT_DIR_.'/download/');
define('_PS_MAIL_DIR_',             _PS_ROOT_DIR_.'/mails/');
define('_PS_MODULE_DIR_',           _PS_ROOT_DIR_.'/modules/');
define('_PS_ALL_THEMES_DIR_',       _PS_ROOT_DIR_.'/themes/');
define('_PS_THEME_DIR_',            _PS_ROOT_DIR_.'/themes/'._THEME_NAME_.'/');
define('_PS_IMG_DIR_',              _PS_ROOT_DIR_.'/img/');
define('_PS_CAT_IMG_DIR_',          _PS_IMG_DIR_.'c/');
define('_PS_PROD_IMG_DIR_',         _PS_IMG_DIR_.'p/');
define('_PS_SCENE_IMG_DIR_',        _PS_IMG_DIR_.'scenes/');
define('_PS_SCENE_THUMB_IMG_DIR_',  _PS_IMG_DIR_.'scenes/thumbs/');
define('_PS_MANU_IMG_DIR_',         _PS_IMG_DIR_.'m/');
define('_PS_SHIP_IMG_DIR_',         _PS_IMG_DIR_.'s/');
define('_PS_SUPP_IMG_DIR_',         _PS_IMG_DIR_.'su/');
define('_PS_COL_IMG_DIR_',			_PS_IMG_DIR_.'co/');
define('_PS_TMP_IMG_DIR_',          _PS_IMG_DIR_.'tmp/');
define('_PS_PROD_PIC_DIR_',			_PS_ROOT_DIR_.'/upload/');
define('_PS_TOOL_DIR_',             _PS_ROOT_DIR_.'/tools/');
define('_PS_SMARTY_DIR_',           _PS_TOOL_DIR_.'smarty/');
define('_PS_STEST_DIR_',            _PS_TOOL_DIR_.'simpletest/');
define('_PS_SWIFT_DIR_',            _PS_TOOL_DIR_.'swift/');
//define('_PS_FPDF_PATH_',            _PS_TOOL_DIR_.'fpdf/');

############# fix #############
define('_PS_TCPDF_PATH_',            _PS_TOOL_DIR_.'tcpdf/');
############# fix #############

define('_PS_PEAR_XML_PARSER_PATH_', _PS_TOOL_DIR_.'pear_xml_parser/');


define('_PS_CSS_DIR_',              __PS_BASE_URI__.'css/'); // оригинал
//define('_PS_CSS_DIR_',               'http://cdn.motokofr.com/css/');

define('_PS_JS_DIR_',               __PS_BASE_URI__.'js/'); // оригинал
//define('_PS_JS_DIR_',               'http://cdn.motokofr.com/js/');

/* settings php */
define('_PS_MAGIC_QUOTES_GPC_',         get_magic_quotes_gpc());
define('_PS_MYSQL_REAL_ESCAPE_STRING_', function_exists('mysql_real_escape_string'));
define('_PS_TRANS_PATTERN_',            '(.*[^\\\\])');
define('_PS_MIN_TIME_GENERATE_PASSWD_', '360');

/* aliases */
function p($var) {
	Tools::p($var);
}
function d($var) {
	Tools::d($var);
}

/* Order states */
define('_PS_OS_card2card_',   1);
define('_PS_OS_PAYMENT_',     2);
define('_PS_OS_PREPARATION_', 3);
define('_PS_OS_SHIPPING_',    4);
define('_PS_OS_DELIVERED_',   5);
define('_PS_OS_CANCELED_',    6);
define('_PS_OS_REFUND_',      7);
define('_PS_OS_ERROR_',       8);
define('_PS_OS_OUTOFSTOCK_',  9);
define('_PS_OS_BANKWIRE_',    10);
define('_PS_OS_PAYPAL_',      11);
define('_PS_OS_TERMINALPAY_', 21);
define('_PS_OS_tinkoff_',     20);
define('_PS_OS_sbercard_',    22);

/* Tax behavior */
define('PS_PRODUCT_TAX', 0);
define('PS_STATE_TAX', 1);
define('PS_BOTH_TAX', 2);

define('_PS_PRICE_DISPLAY_PRECISION_', 2);


global $_MODULES;
$_MODULES = array();

/* Globals */


/* определяем комп или планшет */
//global $site_version;
/*
$useragent = $_SERVER['HTTP_USER_AGENT']; // Let's do some device detection
if(preg_match('/android|(android|bb\d+|meego).+mobile|iPad|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
define(site_version, 'mobile');
else
*/

// @TODO добавил кавычки
define('site_version', 'full');



// беспошлинная сумма 
$porog = 1000; //порог беспошлинного ввоза в евро
$tax = Db::getInstance()->getValue('
SELECT `conversion_rate`
FROM `presta_currency`
where `iso_code` = "EUR" 
and `deleted` = 0
');
	global $notax;
	if ($tax > 0)	$notax = round($porog / $tax);
	
/* Load default country */
global $defaultCountry;

/* Load all configuration keys */
Configuration::loadConfiguration();

/* Load all language definitions */
Language::loadLanguages();

/* Load all zone/tax relations */
Tax::loadTaxZones();

/* Loading default country */
$defaultCountry = new Country(intval(Configuration::get('PS_COUNTRY_DEFAULT')));

/* Define default timezone */
$timezone = Tools::getTimezones(Configuration::get('PS_TIMEZONE'));

if (function_exists('date_default_timezone_set'))
	date_default_timezone_set($timezone);

/* Smarty */
include(dirname(__FILE__).'/smarty.config.inc.php');

setlocale(LC_ALL, "ru_RU.utf-8"); // это портит DECIMAL и использует десятичные с запятой (1,1) что не позволяет писать их в базу
setlocale(LC_NUMERIC, "en_US.utf-8"); // это возвращает DECIMAL к правильному виду
