<?php

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/product-sort.php');

$nbProducts = intval(ProductSale::getNbSales());
//include(dirname(__FILE__).'/pagination.php');
//$nbProducts = 11;
	
$smarty->assign(array(
	'products' => ProductSale::getBestSales(intval($cookie->id_lang), intval($p) - 1, intval($n), sale_nbr, DESC),
	'nbProducts' => $nbProducts));

$smarty->display(_PS_THEME_DIR_.'best-sales.tpl');

//echo '<pre>';
//print_r($smarty);



include(dirname(__FILE__).'/footer.php');

?>


<!-- Yandex.Metrika best-sales --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459857 = new Ya.Metrika({id:24459857, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459857" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika best-sales -->