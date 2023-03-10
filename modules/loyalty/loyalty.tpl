<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account' mod='loyalty'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='My loyalty points' mod='loyalty'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<!-- <h2>{l s='My reward points' mod='loyalty'}</h2> -->

{if $orders}
<div class="block-center" id="block-history">
	{if $orders && count($orders)}
{*	<table id="order-list" class="std">
		<thead>
			<tr>
				<th class="first_item">{l s='Order' mod='loyalty'}</th>
				<th class="item">{l s='Date' mod='loyalty'}</th>
				<th class="item">{l s='Points' mod='loyalty'}</th>
				<th class="item">{l s='Points Status' mod='loyalty'}</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="alternate_item">
				<td colspan="2" class="history_method bold" style="text-align:center;">{l s='Total points available:' mod='loyalty'}</td>
				<td class="history_method" style="text-align:left;">{$totalPoints|intval}</td>
				<td class="history_method">&nbsp;</td>
			</tr>
		</tfoot>
		<tbody>
		{foreach from=$orders item='order'}
			<tr class="alternate_item">
				<td class="history_link bold">{l s='#' mod='loyalty'}{$order.id|string_format:"%06d"}</td>
				<td class="history_date">{dateFormat date=$order.date full=1}</td>
				<td class="history_method">{$order.points|intval}</td>
				<td class="history_method">{$order.state|escape:'htmlall':'UTF-8'}</td>
			</tr>
		{/foreach}
		</tbody>
	</table> *}
	<div id="block-order-detail" class="hidden">&nbsp;</div>
	{else}
		<p class="success">{l s='You have not placed any orders.'}</p>
	{/if}
</div>


{if $transformation_allowed} 
<h2 id="cabinet">{l s='Новый купон на скидку!' mod='loyalty'}</h2>
<div class="transformation_allowed">
	<a href="{$base_dir}modules/loyalty/loyalty-program.php?transform-points=true" onclick="return confirm('{l s='При новом заказе введи код купона в Корзине.' mod='loyalty' js=1}');"> 
		<img src="../../themes/Earth/img/icon/new_coupon.png">
		<div class="transformation_allowed_price">{convertPrice price=$voucher}
		</div>
		</a>
</div>
{else}
<h2 id="cabinet">{l s='Твои купоны' mod='loyalty'}</h2>
{/if}

{if $nbDiscounts}
<div class="block-center" id="block-history">
	<table id="loyalty" class="std">
		<thead>
			<tr>
				<th class="first_item">{l s='От' mod='loyalty'}</th>
				<th class="item">{l s='Value' mod='loyalty'}</th>
				<th class="item">{l s='Код' mod='loyalty'}</th>
				<th class="item">{l s='До' mod='loyalty'}</th>
				<th class="item">{l s='Status' mod='loyalty'}</th>
				<th class="last_item">{l s='Details' mod='loyalty'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$discounts item=discount name=myLoop}
			<tr class="alternate_item">
				<td class="history_date">{dateFormat date=$discount->date_add}</td>
				<td class="history_price"><span class="price">{if $discount->id_discount_type == 1}
						{$discount->value}%
					{elseif $discount->id_discount_type == 2}
						{convertPrice price=$discount->value}
					{else}
						{l s='Free shipping' mod='loyalty'}
					{/if}</span></td>
				<td class="history_method bold">{$discount->name}</td>
				<td class="history_date">{dateFormat date=$discount->date_to}</td>
				<td class="history_method">{if $discount->quantity > 0}{l s='To use' mod='loyalty'}{else}{l s='Used' mod='loyalty'}{/if}</td>
				<td class="history_method bold"><a href="{$smarty.server.SCRIPT_NAME}" onclick="return false" class="tips" title="{l s='Generated by these following orders' mod='loyalty'}|{foreach from=$discount->orders item=myorder name=myLoop}{l s='Order #' mod='loyalty'}{$myorder.id_order} ({convertPrice price=$myorder.total_paid}) : {if $myorder.points > 0}{$myorder.points} {l s='points.' mod='loyalty'}{else}{l s='Cancelled' mod='loyalty'}{/if}{if !$smarty.foreach.myLoop.last}|{/if}{/foreach}">{l s='more...' mod='loyalty'}</a></td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div id="block-order-detail" class="hidden">&nbsp;</div>
</div>
<script type="text/javascript">
{literal}
$(document).ready(function()
{
	$('a.tips').cluetip({
		showTitle: false,
		splitTitle: '|',
		arrows: false,
		fx: {
			open: 'fadeIn',
			openSpeed: 'fast'
		}
	});
});
{/literal}
</script>
{else}
<p class="success">{l s='No vouchers yet.' mod='loyalty'}</p>
{/if}
{else}
<p class="success">{l s='No reward points yet.' mod='loyalty'}</p>
{/if}

<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">В Кабинет </a></div></td>
    <td><div align="center"><a href="{$base_dir}">На главную</a></div></td>
  </tr>
</table>
{literal}<!-- Yandex.Metrika loyalty-program --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459800 = new Ya.Metrika({id:24459800, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459800" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika loyalty-program -->{/literal}