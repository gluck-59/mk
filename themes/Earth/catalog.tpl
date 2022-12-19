{capture name=path}{l s='Заказ по каталогам (пока в ручном режиме)'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<div class="rte">
{*<h1>Заказ по каталогам <span>(пока в ручном режиме)</span></h1>*}

<p>Полистай каталоги, выбери нужные ништяки.</br>
Скопируй их парт-номера и запроси стоимость доставки по любым <a href="/contact-form.php">контактам.</a></br>
Стоимость самих ништяков чаще всего равна &laquo;каталог + 10%&raquo;.<br></br>

<h2>Saddlemen</h2>
<p align="center">
<a href="/catalog/saddlemen/index.html"><img src="/catalog/saddlemen/files/screen.jpg"></a>
</p>
<p>&nbsp;</p>
<p>В <a  target="_blank" href="http://motokofr.com/modules/blockadvancesearch_3/advancesearch.php?manufacturer=28">Saddlemen</a> работают байкеры и принадлежит фирма также байкерам. Большая часть команды Saddlemen активно катает по дорогам и вне дорог, и демонстрирует свой тюнинг зрителям.

Совместный опыт персонала Saddlemen с разными классами мотоциклов позволяет компании превосходить конкурентов: "Мы понимаем, что работает, а что нет".

В фирме считают, что продукция Saddlemen означает "больше продукта за те же деньги" и это является принципом их работы.

</p>
<p>&nbsp;</p><p>&nbsp;</p>


<h2>Kuryakyn</h2>
<p align="center">
<a href="/catalog/kuryakyn/index.html"><img src="/catalog/kuryakyn/files/screen.jpg"></a>
</p>
<p>&nbsp;</p>
<p>В <a  target="_blank" href="http://motokofr.com/modules/blockadvancesearch_3/advancesearch.php?manufacturer=18">Kuryakyn</a> идеи рождаются из страсти к мотоциклам, которая охватывает 145 лет совместного опыта и активное участие в мотоспорте. Наши идеи являются продуктом творческих умов, реализующих мотоцикл как не просто средство передвижения, а, скорее, представление самой личности человека.</p>
<p>&nbsp;</p><p>&nbsp;</p>


<h2>Big bike parts</h2>
<p align="center">
<a href="/catalog/bbp/index.html"><img src="/catalog/bbp/files/screen.jpg"></a>
</p>
<p>&nbsp;</p>
<p>Big Bike Parts производит аксессуары для мотоциклов и квадроциклов под брендами <a   target="_blank" href="http://motokofr.com/modules/blockadvancesearch_3/advancesearch.php?manufacturer=12">Show Chrome</a>, UltraGard, Hopnel и Prairie Dog. Big Bike Parts предлагает самый широкий выбор хромового тюнинга, освещения, чехлов и мягких багажных приспособлений.
</p>
<p>&nbsp;</p><p>&nbsp;</p>


<h2>Harley Davidson 2016</h2>
<p align="center">
<a href="/catalog/hd/index.html"><img src="/catalog/hd/files/screen.jpg"></a>
</p>
<p>&nbsp;</p><p>&nbsp;</p>


<h2>National Cycle — Harley-Davidson</h2>
<p align="center">
<a href="/catalog/nc_hd/index.html"><img src="/catalog/nc_hd/files/screen.jpg"></a>
</p>
<p>&nbsp;</p>
<p>National Cycle (Мэйвуд, Иллинойс, США) производит множество различного тюнинга для мотоциклов: стекла, дуги, кофры, люстры и т.д. Известна в основном своими легендарными небьющимися стеклами из поликарбоната.
В нашем магазине доступна любая продукция National Cycle по каталогу.</p>

<h2>National Cycle — Япония и Европа</h2>
<p align="center">
<a href="/catalog/nc_metric/index.html"><img src="/catalog/nc_metric/files/screen.jpg"></a>
</p>

<h2>Vance & Hines — Япония и Европа (2011)</h2>
<p align="center">
<a href="/catalog/vh2011mertic/index.html"><img src="/catalog/vh2011mertic/files/screen.jpg"></a>
</p>
<p>&nbsp;</p>
<p>Мы делаем продукты для американских V-Tvin, метрических круизеров, спортбайков и эндуро: выхлопные систем, управление впрыском топлива и системы забора воздуха. Наша репутация качества и инноваций не имеет себе равных. Мы были первыми на рынке спорта с SS2R и S4 в 80-х и будем продолжать поднимать планку в будущем.</p>


<h2>Vance & Hines — Япония и Европа (2012)</h2>
<p align="center">
<a href="/catalog/vh2012metric/index.html"><img src="/catalog/vh2012metric/files/screen.jpg"></a>
</p>

<h2>Vance & Hines — Япония и Европа (2013)</h2>
<p align="center">
<a href="/catalog/vh2013metric/index.html"><img src="/catalog/vh2013metric/files/screen.jpg"></a>
</p>

<h2>Vance & Hines — Harley Davidson (2014)</h2>
<p align="center">
<a href="/catalog/vh2014hd/index.html"><img src="/catalog/vh2014hd/files/screen.jpg"></a>
</p>

<h2>Cobra</h2>
<p align="center">
<a href="/catalog/cobra/index.html"><img src="/catalog/cobra/files/screen.jpg"></a>
</p>


{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
			$('body,html').animate({
				scrollTop: 340
			}, 40);
	</script>
	{/literal}
{/if}



{*$name|count}
<br>

{$name}
<br>
{$url}


{*foreach from=$name item=nam}
{$nam}
{/foreach}

<h2>{$nam}</h2>

<iframe src="{$url}" width="560" height="770" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
*}
</div>

{literal}
<!-- Yandex.Metrika catalog --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459554 = new Ya.Metrika({id:24459554, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459554" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika catalog -->
{/literal}