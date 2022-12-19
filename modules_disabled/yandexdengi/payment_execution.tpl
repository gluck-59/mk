{capture name=path}{l s='Bank wire payment' mod='yandexdengi'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Order summary' mod='yandexdengi'}</h2>

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.'}</p>
{else}

<h3>{l s='Bank wire payment' mod='yandexdengi'}</h3>
<form action="{$this_path_ssl}validation.php" method="post">
<p>
	<img src="{$this_path}yandexdengi.jpg" alt="{l s='bank wire' mod='yandexdengi'}" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='You have chosen to pay by bank wire.' mod='yandexdengi'}
	<br/><br />
	{l s='Here is a short summary of your order:' mod='yandexdengi'}
</p>
<p style="margin-top:20px;">
	- {l s='The total amount of your order is' mod='yandexdengi'}
	<span id="amount" class="price">{displayPrice price=$total}</span>
	{l s='(tax incl.)' mod='yandexdengi'}
</p>
<p>
	-
	{if $currencies|@count > 1}
		{l s='We accept several currencies to be sent by bank wire.' mod='yandexdengi'}
		<br /><br />
		{l s='Choose one of the following:' mod='yandexdengi'}
		<select id="currency_payement" name="currency_payement" onchange="setCurrency($('#currency_payement').val());">
			{foreach from=$currencies item=currency}
				<option value="{$currency.id_currency}" {if $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
			{/foreach}
		</select>
	{else}
		{l s='We accept the following currency to be sent by bank wire:' mod='yandexdengi'}&nbsp;<b>{$currencies.0.name}</b>
		<input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}" />
	{/if}
</p>
<p>
	{l s='Bank wire account information will be displayed on the next page.' mod='yandexdengi'}
	<br /><br />
	<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='yandexdengi'}.</b>
</p>
<p class="cart_navigation">
	<a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Other payment methods' mod='yandexdengi'}</a>
	<input type="submit" name="submit" value="{l s='I confirm my order' mod='yandexdengi'}" class="exclusive_large" />
</p>
</form>
{/if}
