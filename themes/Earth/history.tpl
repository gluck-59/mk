<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>
<meta http-equiv="Cache-Control" content="no-cache">
{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='Order history'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2 id="cabinet">{l s='Order history'}</h2>


<div class="block-center" id="block-history" style=" margin: 19px 0px 0px -1px; ">
	{if $orders && count($orders)}
	<table id="order-list" class="std">
		<!--thead>
			<tr>
				<th class="first_item" id="Order">{l s='Order'}</th>
				<th class="item" id="Date">{l s='Date'}</th>
				<th class="item" id="Total price">{l s='Total price'}</th>
				<th class="item" id="Payment">{l s='Payment'}</th>
				<th class="item" id="Status">{l s='Status'}</th>
{*				<th class="last_item" id="Invoice">{l s='Invoice'}</th>
				<th class="last_item" id="history_detail">Ссылка</th> *}
			</tr>
		</thead-->
		<tbody>
		{foreach from=$orders item=order name=myLoop}
			<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
				<td class="history_link">
					{if $order.invoice && $order.virtual}<img src="{$img_dir}icon/download_product.gif" class="icon" alt="{l s='Product(s) to download'}" title="{l s='Product(s) to download'}" />{/if}
					{if $smarty.const.site_version == "full"}{l s='#'} {/if}{$order.id_order|string_format:"%d"}&nbsp;
				</td>
				<td class="history_date">{$order.date_add|date_format:"%d %b %Y"}</td>
				<td class="history_price">{displayPrice price=$order.total_paid_real currency=$order.id_currency no_utf8=false convert=false}</td>

				<td class="history_method">{$order.payment|escape:'htmlall':'UTF-8'}</td>
				<td class="history_state"><a class="ebutton blue small" title="Подробности" href="javascript:showOrder(1, {$order.id_order|intval}, 'order-detail');">{$order.order_state|regex_replace:"/[0-9.]/":""}
    				{if $order.id_order_state == 4}
    				    <span class="tooltip" style="border-bottom:0;" tooltip="В твоем заказе больше одной посылки. Сейчас отправлены еще не все, но это ненадолго :)">&nbsp;<img src="../../img/admin/help.png"></span>
    				{/if}
    				
				</a></td>

				<!--td align="center" class="history_invoice">
				
<!-- отключаем PDF	{if ($order.invoice or $order.invoice_number) AND $invoiceAllowed}
					<a href="{$base_dir}pdf-invoice.php?id_order={$order.id_order|intval}" title="{l s='Invoice'} {$order.name|escape:'htmlall':'UTF-8'}"><img src="{$img_dir}icon/pdf.gif" alt="{l s='Invoice'} {$order.name|escape:'htmlall':'UTF-8'}" class="icon" /></a>
					<a href="{$base_dir}pdf-invoice.php?id_order={$order.id_order|intval}" title="{l s='Invoice'} {$order.name|escape:'htmlall':'UTF-8'}">{l s='PDF'}</a>
				{else}-{/if}
			<a href="{$base_dir}modules/bankform/form.php?id_order={$order.id_order|intval}" title="{l s='bank'} {$order.name|escape:'htmlall':'UTF-8'}"><img src="{$base_dir}modules/bankform/logo.gif" alt="{l s='bank'} {$order.name|escape:'htmlall':'UTF-8'}" class="icon" /></a>
			
-->
{*			<a target="_blank" class="bold" href="{$base_dir}modules/bankform/form.php?id_order={$order.id_order|intval}">Скачать</a>
							
				</td>
				<td class="history_detail"><a class="color-myaccount" href="javascript:showOrder(1, {$order.id_order|intval}, 'order-detail');">{l s='details'}</a></td> 
*}
			</tr>
		{/foreach}
		</tbody>
	</table>

<div id="getContent" style="height:0px;" class="rte">
<p style=" padding-top:50%"><img src="/img/loader.gif"></p>
<p>Пытаемся отследить посылку...</p>
</div>				
	
	<div id="block-order-detail" class="hidden">&nbsp;</div>
	{else}
		<p class="error">{l s='You have not placed any orders.'}</p>
	{/if}
</div>	
	
<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">{l s='Back to Your Account'}</a></div></td>
    <td><div align="center"><a href="{$base_dir}">{l s='Home'}</a></div></td>
  </tr>
</table>

{literal}<!-- Yandex.Metrika history --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459761 = new Ya.Metrika({id:24459761, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459761" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika history -->{/literal}