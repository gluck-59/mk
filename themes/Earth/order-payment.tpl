{* прежде всего насильно переключим валюту в рубли *}
{if $cart->id_currency !=3} 
	{literal}
	<script defer language="JavaScript">
	setCurrency(3);
	</script>
	{/literal}
{/if}

<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Your payment method'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<div class="">
{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}
<h2>{l s='Choose your payment method'}</h2>

{include file=$tpl_dir./errors.tpl}

{if $HOOK_PAYMENT}
	{$HOOK_PAYMENT}
{else}
	<p class="warning">{l s='No payment modules have been installed yet.'}<br>
		Скажи нам, куда отправлять посылку.<br>
		<a class="ebutton orange" href="/address.php" style="text-align:center;">Новый адрес</a>
	</p>
{/if}
</div>

<!--p><a target="_blank" href="/cms.php?id_cms=5">Подробнее о разных способах оплаты (в новом окне)</a><br-->
<a href="{$base_dir_ssl}order.php?step=2" title="{l s='Previous'}" >&laquo; {l s='Previous'}</a></p>


{literal}
<!-- Yandex.Metrika order-payment --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459536 = new Ya.Metrika({id:24459536, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459536" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika order-payment -->
{/literal}

