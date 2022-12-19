{capture name=path}<a href="{$base_dir_ssl}order.php">{l s='Your shopping cart' mod='paypalapi'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='PayPal' mod='paypalapi'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h2>Оплата через Paypal</h2>

<!-- PayPal Logo -->
<table style="float:left; margin: 0px 30px 100% 0px;" border="0" cellpadding="10" cellspacing="0" align="center">
	<tr>
		<td align="center"></td>
		</tr>
	<tr>
		<td align="center">
		<a href="https://www.paypal.com/ru/webapps/mpp/paypal-popup" title="PayPal Как это работает" onclick="javascript:window.open('https://www.paypal.com/ru/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;">
		<img src="https://www.paypalobjects.com/webstatic/ru_RU/mktg/business/pages/logo-center/RU-bdg_secured_by_pp_2ln.png" border="0" alt="Secured by PayPal">
		</a>
		
		<div style="text-align:center"><a href="https://www.paypal.com/ru/webapps/mpp/buy" target="_blank">
		<font size="2" face="Arial" color="#0079CD"><strong>Как это работает?</strong></font>
		</a>
		</div>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
    		<img src="../verified.png">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
    		<img src="../securecode.png">
		</td>
	</tr>    	
</table>
<!-- PayPal Logo -->




{*<h3>{l s='PayPal payment' mod='paypalapi'}</h3>*}
<form action="{$this_path_ssl}{$mode}submit.php" method="post">
	<input type="hidden" name="token" value="{$ppToken|escape:'htmlall'|stripslashes}" />
	<input type="hidden" name="payerID" value="{$payerID|escape:'htmlall'|stripslashes}" />
	
<div class="rte">
	<p>
		<!--img src="{$content_dir}modules/paypalapi/paypalapi.gif" alt="{l s='PayPal' mod='paypalapi'}" style="float:left; margin: 0px 30px 60px 0px;" /-->
		
		{l s='You have chosen to pay with PayPal.' mod='paypalapi'}<br><br>
		{l s='The total amount of your order is' mod='paypalapi'}&nbsp;
		<span id="amount_{$currency->id}" class="price">{convertPriceWithCurrency price=$total currency=$currency}</span> {l s='(tax incl.)' mod='paypalapi'}.
		<br>
		Комиссия Paypal (3,9%) будет автоматически рассчитана на сайте Paypal.
	</p>		
    <p>&nbsp;</p>

	<!--p align="center">
		<img style="height:50px" src="/themes/Earth/img/icon/alert.png">
	</p>

	{l s='Here is a short summary of your order:' mod='paypalapi'}
    -->

	
	
	{*<p>
		{l s='We accept the following currency to be sent by PayPal:' mod='paypalapi'}&nbsp;<b>{$currency->name}</b>
	</p>
	
	<p>
		<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='paypalapi'}.</b>
	</p>*}
	<p class="" align="center">
		<input type="hidden" name="currency_payement" value="{$currency->id}">
		<input type="submit" name="submitPayment" value="{l s='Оплатить' mod='paypalapi'}" class="ebutton green large" onclick="javascript:$('#getpp').show()" />
		<br><br>
		<a href="{$base_dir_ssl}order.php?step=3">{l s='Other payment methods' mod='paypalapi'}</a>
	</p>

	<div hidden id="getpp" style="">
        <div id="getpp_in">
        	<p><img src="http://motokofr.com/img/loader.gif"></p>
            <p>Переходим на защищенный сайт Paypal...</p>
        </div>
	</div>				


</form>
</div>

{* подгрузим "содержимое кофра" и детали по заказу *}
{*include file=$tpl_dir./order-confirmation-product-line.tpl*}
