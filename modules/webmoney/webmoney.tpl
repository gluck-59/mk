<p class="payment_module">

    {*if $total <= 14700}    
    <a href="javascript:submit_wm(18, 14);" title="Сбербанк Онлайн">
    		<img src="{$this_path}sberonline.png" style="margin-bottom:20px" />Несколько минут</a>
    {/if*}
    		
    <a href="javascript:submit_wm(18, 3);" title="Альфа-клик, Альфа-mobile">
    		<img src="{$this_path}alfa_click.jpg" style="margin-bottom:20px" />От нескольких минут до нескольких часов. <br>Комиссия 3%.</a>
    		
    <a href="javascript:submit_wm(18, 5);" title="Банк Русский Стандарт">
    		<img src="{$this_path}rus_stand.png" style="margin-bottom:20px" />От нескольких минут до нескольких часов.<br>Комиссия 2.5%.</a>

    <a href="javascript:submit_wm(18, 6);" title="Телебанк ВТБ24">
    		<img src="{$this_path}telebank.png" style="margin-bottom:20px" />От нескольких минут до нескольких часов.<br>Комиссия 1%.</a>    		

    <a href="javascript:submit_wm(3, 0);" title="WebMoney">
    		<img src="{$this_path}wmlogo_vector_blue.png" style="margin-bottom:20px" />Моментально.<br>Комиссия 3.5%.</a>

    <a href="javascript:submit_wm(16, 4);" title="Банковской картой через Webmoney">
    		<img src="{$this_path}webmoney-all.gif" style="margin-bottom:20px" />Банковской картой через Webmoney. Моментально. <br>Комиссия 3.5%.</a>

    <!--a href="javascript:submit_wm(19, 12);" title="<Мобильная коммерция Билайн">
    		<img src="{$this_path}beeline.png" style="margin-bottom:20px" />От нескольких минут до нескольких часов.<br>Комиссия 3.5%.</a-->

    <a href="javascript:submit_wm(14, 0);" title="Системы денежных переводов">
    		<img src="{$this_path}perevod.png" style="margin-bottom:20px" />ОЧЕНЬ долго! Несколько дней. <br>Комиссия заранее неизвестна.</a>

</p>


<form id="webmoney" accept-charset="windows-1251" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">
	<div hidden id="getpp" style="position: fixed; ">
        <div id="getpp_in" style="position: fixed; margin: 40vh auto auto 40vw;">
        	<p><img src="http://motokofr.com/img/loader.gif"></p>
            <p>Переходим на защищенный сайт Webmoney...</p>
        </div>
	</div>				

<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{$total}">
<input type="hidden" name="LMI_PAYMENT_DESC" value="Оплата корзины {$id_cart}">
<input type="hidden" name="LMI_PAYMENT_NO" value="{$id_cart}">
<input type="hidden" name="LMI_PAYEE_PURSE" value="{$purse}">
<input type="hidden" name="LMI_ALLOW_SDP" id="LMI_ALLOW_SDP">
</form>

{literal}
<script>

function submit_wm(authtype, sdp) {
    var sdp_field = document.getElementById('LMI_ALLOW_SDP');
    if (!sdp)
        sdp_field.remove();
    else
        sdp_field.value = sdp;
    
    var action = document.getElementById('webmoney').action;
    if (authtype != 0)
        action = action+'?at=authtype_'+authtype;    
    
//    var height = $('div#page').height()-325-81;
     $('#getpp').show();

//     var height_window = $('body').height();
//     document.getElementById('getpp_in').style.top = height_window/2+'px';

     $('#webmoney').submit();
    
}
    
</script>
{/literal}
