<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='Скидки'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2 id="cabinet">{l s='Скидки'}</h2>

{if $discount && count($discount) && $nbDiscounts}
<table class="discount std">
	<thead>
		<tr>
			<th class="discount_code bold first_item">{l s='Code'}</th>
			<th class="discount_description item">{l s='Description'}</th>
			<th class="discount_quantity item">{l s='Quantity'}</th>
			<th class="discount_value item">{l s='Value'}</th>
			<th class="discount_minimum item">{l s='Minimum'}</th>
<!--			<th class="discount_cumulative item">{l s='Cumulative'}</th> -->
			<th class="discount_expiration_date last_item">{l s='Expiration date'}</th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$discount item=discount name=myLoop}
		<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
			<td class="discount_code bold"><span class="tooltip" tooltip="Введи этот код в Кофр при заказе">{$discount.name}</span></td>
			<td class="discount_description">{$discount.description}</td>
			<td class="discount_quantity"><center>{$discount.quantity_for_user}</center></td>
			<td class="discount_value">
				{if $discount.id_discount_type == 1}
					{$discount.value|escape:'htmlall':'UTF-8'}%
				{elseif $discount.id_discount_type == 2}
					{convertPrice price=$discount.value}
				{else}
					{l s='Free shipping'}
				{/if}
			</td>
			<td class="discount_minimum"><center>
				{if $discount.minimal == 0}
					{l s='none'}
				{else}
					{convertPrice price=$discount.minimal}
				{/if}
			</center></td>
<!--			<td class="discount_cumulative">
				{if $discount.cumulable == 1}
					<img src="{$img_dir}icon/yes.gif" alt="{l s='Yes'}" class="icon" />
				{else}
					<img src="{$img_dir}icon/no.gif" alt="{l s='No'}" class="icon" />
				{/if}
			</td>
-->			
			<td class="discount_expiration_date">{dateFormat date=$discount.date_to}</td>
		</tr>
	{/foreach}
	</tbody>
</table>

{else}
	<p class="success">{l s='You do not possess any vouchers.'}</p>
{/if}

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

{literal}<!-- Yandex.Metrika discount --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459851 = new Ya.Metrika({id:24459851, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459851" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika discount -->{/literal}