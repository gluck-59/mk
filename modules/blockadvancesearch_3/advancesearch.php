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

<!-- Yandex.Metrika BlockAdvanceSearch --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459038 = new Ya.Metrika({id:24459038, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459038" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika BlockAdvanceSearch -->