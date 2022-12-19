<p class="payment_module">
<a href="javascript:$('#webmoney').submit(); document.getElementById('getpp').style.height = '800px'" title="Оплата с помощью WebMoney">
		<img src="{$this_path}webmoney-all.gif" style="float:right; margin-bottom:20px" />
		Карта Сбербанка
		<br>
		Терминалы
		<br>
		Альфа-клик, Альфа-мобайл
		<br>
		Телебанк ВТБ24
		<br>
		и еще несколько удобных способов

</a>
</p>

<form id="webmoney" accept-charset="windows-1251" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp?at=authtype_16">
	<div id="getpp" style="height:0px">
	<p style="margin-top: 300px;"><img src="http://motokofr.com/img/loader.gif"></p>
	<p>Переходим на защищенный сайт Webmoney...</p>
	</div>				

<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{$total}">
<input type="hidden" name="LMI_PAYMENT_DESC" value="Оплата корзины №{$id_cart}">
<input type="hidden" name="LMI_PAYMENT_NO" value="{$id_cart}">
<input type="hidden" name="LMI_PAYEE_PURSE" value="{$purse}">
<input type="hidden" name="LMI_SUCCESS_URL" value="{$returnUrl}">
<input type="hidden" name="LMI_SUCCESS_METHOD" value="1">
<input type="hidden" name="LMI_FAIL_URL" value="{$cancelUrl}">
<input type="hidden" name="LMI_FAIL_METHOD" value="1">
</form>