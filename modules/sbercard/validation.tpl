{capture name=path}<a href="{$base_dir_ssl}order.php">{l s='Your shopping cart' mod='paypalapi'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='PayPal' mod='paypalapi'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{* номер карты Сбера *}
{assign var=card_no value="5469490013591751"}

{capture name=path}{l s='Оплата на карту Сбербанка'}{/capture}
  <hr id="print" noshade size="6">

{*include file=$tpl_dir./breadcrumb.tpl*}

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h2>Оплата на карту Сбербанка</h2>

{*<div class="tcscard">
    <div class="tcs-sidebar-menu-account-big__number nonprintable">
        <span class="tcs-sidebar-menu-account-big__number-value">{$card_no}</span>
    </div>
    <div class="tcs-sidebar-menu-account-big__holder nonprintable">    
        <span class="tcs-sidebar-menu-account-holder">MOTOKOFR</span>
    </div>
</div>*}


<div class="rte" style="float: left; margin-right: 20px">
    <img src="{$this_path}sbercard.png">
</div>

<div class="rte">
    <p>Сервис перевода с карты на карту предоставлен Сбербанком России<p>
    <p>Комиссия от 0% до 1%</p>
    
    <form action="{$this_path_ssl}validation.php" method="post">
    	<input type="hidden" name="confirm" value="1" />
        <br><br>
        <p>Войдите в Сбербанк Онлайн и выберите "Перевод клиенту Сбербанка"</p>
        
        <img style="max-width:270px; height: 285px" src="{$this_path}step1.jpg">&nbsp;
        <img style="max-width:270px; height: 285px" src="{$this_path}step2.jpg">
        <br>
        <p>Скопируйте номер нашей карты (чуть ниже)</p>
        <p>Вставьте его в поле "Номер карты получателя"</p>
        
        <h3>Не заполняйте "Сообщение получателю"!<br>Банк может не пропустить его!</h3>
        
        <fieldset class="address" style="min-height: 0;margin:20px 0 10px 0; ">
            <legend>Скопируйте номер карты сейчас</legend>
            <center><p style="font-size: 24pt!important;font-weight: bold;  padding: 10px 0; width: auto;">{$card_no}</p></center>
        </fieldset>
        
        <p style="margin:0; text-align: center; width: auto">
            Сумма к оплате:
        </p>
        <p style="margin: 0px 0 0px 0; font-size: 24pt;font-weight: normal;text-align: center;">
     		{convertPrice price=$total}
        </p>	
        <br>
        
    <p style="margin:0; text-align: center;">
    	<input type="submit" name="submit" value="Оплатить (в новом окне)" class="ebutton green large" onclick="window.open('https://online.sberbank.ru','_blank')" />
    	<br><br>
    	<a class="nonprintable" href="{$base_dir_ssl}order.php?step=3">Другие способы оплаты</a>
    </p>
    
    </form>
</div>
<br><br>
{* подгрузим "содержимое кофра" и детали по заказу *}
{assign var=nobutton value=1}
{include file=$tpl_dir./order-confirmation-product-line.tpl}

