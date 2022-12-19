<p class="payment_module">
	<a href="javascript:$('#liqpay_form').submit();" title="{l s='Оплата через Liqpay' mod='liqpay'}">
		<img src="{$module_template_dir}LiqPay.gif" alt="{l s='Оплата через Liqpay' mod='liqpay'}" />
		{l s='Для жителей Украины: оплата через Liqpay-Приватбанк' mod='liqpay'}
	</a>
</p>

<form  id="liqpay_form" action="{$liqpayUrl}" method="post" />
    <input type="hidden" name="operation_xml" value="{$operation_xml}">
    <input type="hidden" name="signature" value="{$signature}">
</form>