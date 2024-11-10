<?php
/**
  * BlockAdvanceSearch Front Office Feature
  *
  * @category tools
  * @authors Jean-Sebastien Couvert & Stephan Obadia / Presta-Module.com <support@presta-module.com>
  * @copyright Presta-Module 2010
  * @version 3.4
  *
  * ************************************
  * *       BlockAdvanceSearch V3      *
  * *   http://www.presta-module.com   *
  * *               V 3.4 :            *
  * ************************************
  * +
  * +Languages: EN, FR
  * +PS version:1.3, 1.2, 1.1
  *
  **/
//header("Expires: Tue, 13 Jan 2015 05:00:00 GMT");  
include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/blockadvancesearch_3.php');

$ajax_mode = Tools::getValue('advc_ajax_mode',false);
$get_block = Tools::getValue('advc_get_block',false);

if(!$ajax_mode)
include_once(dirname(__FILE__).'/../../header.php');

$oAdvaceSearch = new BlockAdvanceSearch_3();

if($get_block)
	echo $oAdvaceSearch->hookLeftColumn(false);
else
	echo $oAdvaceSearch->getSearchResult();
if(!$ajax_mode)
include(dirname(__FILE__).'/../../footer.php');
?>

