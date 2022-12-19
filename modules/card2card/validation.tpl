{capture name=path}<a href="{$base_dir_ssl}order.php">{l s='Your shopping cart' mod='paypalapi'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='PayPal' mod='paypalapi'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{* 
	показываем разные карты в зависимости от даты 
	защита от превышения лимита пополнений в 300 000
*}

{* затычка на случай отключения определения дат *}
{assign var=card_no value="4841570441724215"} 

{* 	
	определяем даты 
	отключать здесь

{if $smarty.now|date_format:"%d" >= 25} 
{assign var=card_no value="5213 2437 3548 8627"}
{else} 
{assign var=card_no value="5213 2437 3502 2129"}
{/if}
*}

{capture name=path}{l s='Оплата с карты на карту'}{/capture}
  <hr id="print" noshade size="6">

{*include file=$tpl_dir./breadcrumb.tpl*}

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h2>Оплата с карты на карту</h2>

{*<div class="tcscard">
    <div class="tcs-sidebar-menu-account-big__number nonprintable">
        <span class="tcs-sidebar-menu-account-big__number-value">{$card_no}</span>
    </div>
    <div class="tcs-sidebar-menu-account-big__holder nonprintable">    
        <span class="tcs-sidebar-menu-account-holder">MOTOKOFR</span>
    </div>
</div>*}


<div class="rte" style="float: left; margin-right: 20px">
    <img src="{$this_path}card2card.png"  >
    <br><br><img src="../paypalapi/verified.png">
    &nbsp;&nbsp;<img src="../paypalapi/securecode.png">
</div>

<div class="rte">
    <p>Сервис перевода с карты на карту предоставлен Тинькофф Банк.<p>
    <p>Комиссия от 0% до 1,5%.</p>
    <p>Ваша карта должна поддерживать технологию 3DSecure или SecureCode.</p>
    
    <form action="{$this_path_ssl}validation.php" method="post">
    	<input type="hidden" name="confirm" value="1" />
    
</div>

    <fieldset class="address" style="min-height: 0;margin:90px 0 10px 0; ">
        <legend>Скопируйте номер карты сейчас</legend>
        <center><p style="font-size: 24pt!important;font-weight: bold;  padding: 10px 0; width: auto;">{$card_no}</p></center>
    </fieldset>

<div class="rte">    
    <p style="margin:0; text-align: center; width: auto">
    Сумма к оплате:
    </p>
    <p style="margin: 0px 0 0px 0; font-size: 24pt;font-weight: normal;text-align: center;">
     		{convertPrice price=$total}
    </p>	
    <br>
    <p style="margin:0; text-align: center;">
    	<input type="submit" name="submit" value="Оплатить (в новом окне)" class="ebutton green large" onclick="window.open('https://www.tinkoff.ru/cardtocard/','_blank')" />
    	<br><br>
    	<a class="nonprintable" href="{$base_dir_ssl}order.php?step=3">Другие способы оплаты</a>
    </p>
    
    </form>
</div>

{* подгрузим "содержимое кофра" и детали по заказу *}
{assign var=nobutton value=1}
{include file=$tpl_dir./order-confirmation-product-line.tpl}

