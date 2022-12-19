{capture name=path}{l s='Price drop'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Price drop'} <!--noindex><span>({$nbProducts} {declension nb="$nbProducts" expressions="ништяк,ништяка,ништяков"})</span></noindex--></h2> 

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No price drop.'}</p>
{/if}

{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
	jQuery(document).ready(function()
			{   
			$('body,html').animate({
				scrollTop: 340
			}, 40);
			setTimeout("document.getElementById('product_raise').style.backgroundColor = '#fff'", 500);
		});
	</script>
	{/literal}
{/if}


{literal}<!-- Yandex.Metrika prices-drop --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459986 = new Ya.Metrika({id:24459986, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459986" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika prices-drop -->{/literal}