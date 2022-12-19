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
<script type="text/javascript" src="{$js_dir}layer.js"></script>
<script type="text/javascript" src="{$content_dir}js/conditions.js"></script>
{if !$virtual_cart && $giftAllowed && $cart->gift == 1}
<script type="text/javascript">{literal}
// <![CDATA[
    $('document').ready( function(){
        $('#gift_div').toggle('slow');
    });
//]]>
{/literal}</script>
{/if}
{include file=$tpl_dir./thickbox.tpl}

{capture name=path}{l s='Shipping'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{assign var='current_step' value='shipping'}
{assign var="preload" value="shipping"}
{include file=$tpl_dir./order-steps.tpl}

<h2>{l s='Shipping'}</h2>

{include file=$tpl_dir./errors.tpl}

<div class="rte">
<form id="form" action="{$base_dir_ssl}order.php" method="post" onsubmit="return acceptCGV('{l s='Please accept the terms of service before the next step.' js=1}');">

{if $conditions}
	<h3 class="condition_title">{l s='Terms of service'}</h3>
	<p class="checkbox">
		<input type="checkbox" name="cgv" id="cgv" value="1" {if $checkedTOS}checked="checked"{/if} />
		<label for="cgv">{l s='I agree with the terms of service and I adhere to them unconditionally.'}</label> <a href="{$base_dir}cms.php?id_cms=3&amp;content_only=1&amp;TB_iframe=true&amp;width=450&amp;height=500&amp;thickbox=true" class="thickbox">{l s='(read)'}</a>
	</p>
{/if}

{if $virtual_cart}
	<input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
{else}
	<!--p>Мы огорчены тем, что пока не можем отправлять посылки в Россию наложенным платежом. Мы включим наложенный платеж сразу же, как только Почта России повернется к клиентам лицом.</p>
	<p>Зато ты никогда не услышишь от нас слов «за пределы МКАД не доставляем».</p>
	<p>Согласись, это круто? :)</p-->
		{if $recyclablePackAllowed}
		<p class="checkbox">
			<input type="checkbox" name="recyclable" id="recyclable" value="1" {if $recyclable == 1}checked="checked"{/if} />
			<label for="recyclable">{l s='I agree to receive my order in recycled packaging'}.</label>
		</p>
		{/if}
</div>

	{if $carriers && count($carriers)}
	<div class="table_block">
		<table class="std">
			<!--thead>
				<tr>
					<th class="carrier_action first_item"></th>
					<th class="carrier_name item">{l s='Carrier'}</th>
					<th class="carrier_infos item">{l s='Information'}</th>
					<th class="carrier_price last_item"{if $smarty.const.site_version == "mobile"} style="width: 156px"{/if}>{l s='Price'}</th>
				</tr>
			</thead-->
			<tbody>
			{foreach from=$carriers item=carrier name=myLoop}
				<tr height="90px" class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{/if} {*if $smarty.foreach.myLoop.index % 2}alternate_item{else}item{/if*}">
					<td width="10%" class="carrier_action radio">
{* не работает установка default carrier *}
{* ID СПСР СТАВИТЬ ВРУЧНУЮ *}
						<input type="radio" 
						{if $carrier.id_carrier == 79}
					        checked
						{/if}
						{if $carrier.id_carrier == 76}
							{if !$address2 or !$company or !$phone_mobile}
							disabled 
							{/if}
						{/if}
						{if $carrier.id_carrier == 74}
							{if !$phone_mobile OR !$inn}
							disabled 
							{/if}
						{/if}
						{if $carrier.id_carrier == 77}
							disabled 
							{/if}

	
						name="id_carrier" value="{$carrier.id_carrier|intval}" id="id_carrier{$carrier.id_carrier|intval}" 
						{if ($checked == 0 && $i == 1) || ($carriers|@sizeof == 1) || $carrier.id_carrier == $checked}checked="checked"{/if} />
					</td>
					<td width="10%" class="carrier_name">
						<label for="id_carrier{$carrier.id_carrier|intval}">
							{if $carrier.img}<img src="{$carrier.img|escape:'htmlall':'UTF-8'}" alt="{$carrier.name|escape:'htmlall':'UTF-8'}" />{else}{$carrier.name|escape:'htmlall':'UTF-8'}{/if}
						</label>
					</td>

					
					
					<td width="60%" class="carrier_infos">
					<label for="id_carrier{$carrier.id_carrier|intval}">
					
					{if $carrier.id_carrier == 78}
						Временно недоступен для этого региона <span><img  style="-webkit-animation: delivery 0.8s ease infinite; height: 26px; padding-left: 10px;" src="/themes/Earth/img/petr.png"></span>
					{else}
						Срок: примерно {$carrier.avg_delibery_days} дней{if $carrier.id_carrier == 79} для посылок, {math equation="x * 2" x=$carrier.avg_delibery_days} дней для бандеролей {else} для всех{/if}
						&nbsp;<span class="tooltip" style="border-bottom:0;" tooltip="На основе статистики доставки наших посылок в {$smarty.now-2592000|date_format:"%Y"} году"><img src="../../img/admin/help.png"></span>
						<br>
						{$carrier.delay} 
					{/if}
					
					
					
						{if $carrier.id_carrier == 76}
<abbr title="Пожалуйста убедись, что твоя посылка поедет из США. Если не уверен — свяжись с нами. Доставка через СПСР из других стран пока невозможна.">	
<b>Только для посылок из США!</b></abbr>

							{if !$address2 or !$company or !$phone_mobile} <br><br>СПСР требует указывать: 
							{if !$company} <br><b>Паспортные данные</b> {/if}							
							{if !$address2} <br><b>Отчество</b> {/if}						
							{if !$phone_mobile} <br><b>Телефон для курьера</b> {/if}
							{if !$inn} <br>ФТС требует указывать <b>ИНН получателя</b> {/if}
							<br>Пожалуйста <a href="/order.php?step=1">вернись в "Адреса"</a> и добавь его.
							{/if}
						{/if}
						
												
						{if $carrier.id_carrier == 74}						
							{if !$phone_mobile OR !$inn} <div style="border: 2px dashed #F73;margin-top: 10px;  padding: 10px;"> 
    							
    							{if !$phone_mobile}EMS требует указывать <b>Телефон для курьера</b>{/if}
    							{if !$inn}ФТС требует указывать <b>ИНН получателя</b>{/if}

							<br>Пожалуйста <a href="/order.php?step=1">вернись в "Адреса"</a> и добавь недостающее.	
												</div>
							{/if}
						{/if}
						
					{if $carrier.id_carrier == 55}
							{if !$inn} <div style="border: 2px dashed #F73;margin-top: 10px;  padding: 10px;"> 
    							{if !$inn}ФТС требует указывать <b>ИНН получателя</b>{/if}
							<br>Пожалуйста <a href="/order.php?step=1">вернись в "Адреса"</a> и добавь недостающее.	
								</div>
							{/if}
						{/if}
						
						
					</label>
					</td>
					<td width="15%" align="right" class="carrier_price">
					<label for="id_carrier{$carrier.id_carrier|intval}">
						{if $carrier.price}
							<span class="price">
								{if $priceDisplay == 1}{convertPrice price=$carrier.price_tax_exc}{else}{convertPrice price=$carrier.price}{/if}
							</span>
							{if $priceDisplay == 1} {l s='(tax excl.)'}{else} {l s='(tax incl.)'}{/if}
						{else}
							{l s='Free!'}
						{/if}
					</label>
					</td>
				</tr>
			{/foreach}
			{$HOOK_EXTRACARRIER}
			</tbody>
		</table>
		<div style="display: none;" id="extra_carrier"></div>
	</div>
	{else}
		<p class="warning">{l s='There is no carrier available that will deliver to this address!'}</td></tr>
	{/if}

	{*if $giftAllowed}
		<h3 class="gift_title">{l s='Gift'}</h3>
		<p class="checkbox">
			<input type="checkbox" name="gift" id="gift" value="1" {if $cart->gift == 1}checked="checked"{/if} onclick="$('#gift_div').toggle('slow');" />
			<label for="gift">{l s='I would like the order to be gift-wrapped.'}</label>
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			{if $gift_wrapping_price > 0}
				({l s='Additional cost of'}
				<span class="price">
					{if $priceDisplay == 1}{convertPrice price=$total_wrapping_tax_exc}{else}{convertPrice price=$total_wrapping}{/if}
				</span>
				{if $priceDisplay == 1} {l s='(tax excl.)'}{else} {l s='(tax incl.)'}{/if})
			{/if}
		</p>
		<p id="gift_div" class="textarea">
			<label for="gift_message">{l s='If you wish, you can add a note to the gift:'}</label>
			<textarea rows="5" cols="35" id="gift_message" name="gift_message">{$cart->gift_message|escape:'htmlall':'UTF-8'}</textarea>
		</p>
	{/if*}
{/if}

<p>&nbsp;</p>
<p><a target="_blank" href="/cms.php?id_cms=1">Подробнее о разных способах доставки (в новом окне)</a></p>
<p>&nbsp;</p>
	<p class="cart_navigation" align="center">
		<input type="hidden" name="step" value="3" />
		<input type="hidden" name="back" value="{$back}" />
		<!--a href="{$base_dir_ssl}order.php?step=1{if $back}&back={$back}{/if}" title="{l s='Previous'}" class="ebutton gray small">&laquo; {l s='Previous'}</a-->&nbsp;&nbsp;
		<input style="border-radius:15px;" type="submit" name="processCarrier" value="{l s='Оплатить заказ'}" class="ebutton green large" />
	</p>
</form>

{literal}
<!-- Yandex.Metrika order-carrier --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459512 = new Ya.Metrika({id:24459512, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459512" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika order-carrier -->
{/literal}

