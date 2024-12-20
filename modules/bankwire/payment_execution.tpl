{*capture name=path}{l s='Bank wire payment' mod='bankwire'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl*}

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h2>{l s='Order summary' mod='bankwire'}</h2>

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.'}</p>
{else}

<div class="rte">
<form action="{$this_path_ssl}validation.php" method="post">
<p>
	<img src="{$this_path}bankwire.jpg" alt="{l s='bank wire' mod='bankwire'}" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='You have chosen to pay by bank wire.' mod='bankwire'}
	<br />
<!--	{l s='Here is a short summary of your order:' mod='bankwire'} -->

	{l s='The total amount of your order is' mod='bankwire'}
	{l s='(tax incl.)' mod='bankwire'}
</p>	
<p>
	{if $currencies|@count > 1}
		{foreach from=$currencies item=currency}
			<span id="amount_{$currency.id_currency}" class="price" style="display:none;">{convertPriceWithCurrency price=$total currency=$currency}</span>
		{/foreach}
	{else}
		<span id="amount_{$currencies.0.id_currency}" class="price">Сумма платежа — {convertPriceWithCurrency price=$total currency=$currencies.0}.</span>
	{/if}





{* <p>
	-
	{if $currencies|@count > 1}
		{l s='We accept several currencies to be sent by bank wire.' mod='bankwire'}
		<br /><br />
		{l s='Choose one of the following:' mod='bankwire'}
		<select id="currency_payement" name="currency_payement" onchange="showElemFromSelect('currency_payement', 'amount_')">
			{foreach from=$currencies item=currency}
				<option value="{$currency.id_currency}" {if $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
			{/foreach}
		</select>
		<script language="javascript">showElemFromSelect('currency_payement', 'amount_');</script>
	{else}
		{l s='We accept the following currency to be sent by bank wire:' mod='bankwire'}&nbsp;<b>{$currencies.0.name}</b>
		<input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}">
	{/if}
</p>
*}



{*<br>
<p align="center"><img src="http://motokofr.com/themes/Earth/img/icon/alert.png"></p>
<p style="font-size:10pt">
В последнее время платежи через российские банки ходят очень медленно. Время прохождения платежа достигает 2 недель.
<br>
Пожалуйста используй этот способ только если готов ждать.
</p>
</p>
<br><br>*}



	{l s='Bank wire account information will be displayed on the next page.' mod='bankwire'}
	<br /><br />
	<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='bankwire'}</b>
</p>
</div>	
<p class="" align="center">
	<input style="width: auto;" type="submit" name="submit" value="{l s='I confirm my order' mod='bankwire'}" class="ebutton green large" /><br><br>
	<a style="width: 238px;" class="ebutton blue" href="{$base_dir_ssl}order.php?step=3">{l s='Other payment methods' mod='bankwire'}</a>
</p>

</form>
{/if}

{* подгрузим "содержимое кофра" и детали по заказу *}
{*include file=$tpl_dir./order-confirmation-product-line.tpl*}

{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
	jQuery(document).ready(function()
			{   
			$('body,html').animate({
				scrollTop: 320
			}, 40);
			// setTimeout("document.getElementById('product_raise').style.backgroundColor = '#fff'", 500);
		});
	</script>
	{/literal}
{/if}

