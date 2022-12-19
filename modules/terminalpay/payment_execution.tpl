{*capture name=path}Оплата через терминал{/capture}
{include file=$tpl_dir./breadcrumb.tpl*}

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h2>Оплата через терминал</h2>

{if $nbProducts <= 0}
	<p class="warning">{l s='Корзина пуста.'}</p>
{else}

<form action="{$this_path_ssl}validation.php" method="post">
<div class="rte">
<p>	<img src="{$this_path}terminalpay.jpg" alt="оплата через терминал" style="float:left; margin: 0px 10px -15px 0px;" />
	Терминалы оплаты размещаются во многих торговых точках. 
<br>Нам нужен терминал, умеющий делать переводы в Webmoney.
<br>
	{*l s='Сумма' mod='terminalpay'}
 	{if $currencies|@count > 1}
		{foreach from=$currencies item=currency}
			<span id="amount_{$currency.id_currency}" class="price" style="display:none;">{convertPriceWithCurrency price=$total currency=$currency}</span>
		{/foreach}
	{else}
		<span id="amount_{$currencies.0.id_currency}" class="price">{convertPriceWithCurrency price=$total currency=$currencies.0}</span>
	{/if}
	{l s='(вкл. налоги)' mod='terminalpay'*}
</p>
<p>
	{*if $currencies|@count > 1}
		{l s='Мы принимаем оплату в рублях.' mod='terminalpay'}
		<br /><br />
		{l s='Выбери валюту "РУБЛИ":' mod='terminalpay'}
		<select id="currency_payement" name="currency_payement" onchange="showElemFromSelect('currency_payement', 'amount_')">
			{foreach from=$currencies item=currency}
				<option value="{$currency.id_currency}" {if $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
			{/foreach}
		</select>
		<script language="javascript">showElemFromSelect('currency_payement', 'amount_');</script>
	{else}
<!-- 		{l s='Ты можешь оплатить заказ в следующей валюте:' mod='terminalpay'}&nbsp;<b>{$currencies.0.name}</b> -->
		<input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}">
	{/if*}
</p>
<p>
	<b>Удобен ли этот способ?</b>
</p>
</div>
<p class="" align="center">
	<input type="submit" name="submit" value="{l s='Да, заказываю' mod='terminalpay'}" class="ebutton green large" />
	<br><br>
	<a href="{$base_dir_ssl}order.php?step=3">{l s='Нет, я оплачу заказ другим способом' mod='terminalpay'}</a>
</p>
</form>
{/if}



{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
	jQuery(document).ready(function()
			{   
			$('body,html').animate({
				scrollTop: 320
			}, 40);
			setTimeout("document.getElementById('product_raise').style.backgroundColor = '#fff'", 500);
		});
	</script>
	{/literal}
{/if}

