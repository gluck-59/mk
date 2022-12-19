{capture name=path}<a href="{$base_dir_ssl}order.php">{l s='Your shopping cart' mod='paypalapi'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='PayPal' mod='paypalapi'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{* прежде всего насильно переключим валюту в рубли *}
{if $cart->id_currency !=3} 
	{literal}
	<script defer language="JavaScript">
	setCurrency(3);
	</script>
	{/literal}
{/if}


{* 
	показываем разные карты в зависимости от даты 
	защита от превышения лимита пополнений в 300 000
*}

{* затычка на случай отключения определения дат 
   всегда С ПРОБЕЛАМИ *}
{assign var=card value="4841 5704 4172 4215"} 

{* 	
	определяем даты 
	отключать здесь

{if $smarty.now|date_format:"%d" >= 25} 
{assign var=card value="5213 2437 3548 8627"}
{else} 
{assign var=card value="5213 2437 3502 2129"}
{/if}
*}

{capture name=path}{l s='Оплата через салоны связи'}{/capture}
  <hr id="print" noshade size="6">

{*include file=$tpl_dir./breadcrumb.tpl*}

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h2>Оплата через салоны связи</h2>

<div class="tcscard">
    <div class="tcs-sidebar-menu-account-big__number nonprintable">
        <span class="tcs-sidebar-menu-account-big__number-value">{$card}</span>
    </div>
    <div class="tcs-sidebar-menu-account-big__holder nonprintable">    
        <span class="tcs-sidebar-menu-account-holder">MOTOKOFR</span>
    </div>
</div>


<div class="rte">
<p>
{*<img src="{$this_path}card.png" alt="{l s='Cash on delivery (COD) payment' mod='tinkoff'}" style="float:left; margin: 0px 25px 0px 0px;height: 45px;" />*}
Найди поблизости от дома/работы:
<ul class="saloons">
    <li>Салон МТС</li>
    <li>Связной</li>
    <li>Евросеть</li>
    <li>Золотая Корона</li>
</ul>
</p>
<p style="margin-bottom: 70px;">
Попроси оператора пополнить карту. 
<br>
Распечатай эту страницу, не переписывай номер вручную!
</p>	


<form action="{$this_path_ssl}validation.php" method="post">
	<input type="hidden" name="confirm" value="1" />

<center>Номер карты и сумма к оплате:</center>
<p style="margin: 10px 0 0px 0; font-size: 24pt!important;font-weight: bold;text-align: center;">
<elem class="block_hidden_only_for_print">{$card|strip:""}</elem>
<elem class="block_hidden_only_for_screen">{$card}</elem>
</p>
<br>
<p style="margin: -15px 0 -15px 0; font-size: 24pt;font-weight: normal;text-align: center;">
 		{convertPrice price=$total}
 		<span class="block_hidden_only_for_screen">	
             Система: "Золотая Корона".
 		</span>
</p>	

<p style="font-size: 10px; text-align: center; font-style: italic; padding: 0 0 2em 0">

{*if $total_terminal < 15000}	
	*этот заказ можно оплатить без очереди в терминале
{else}	
	*этот заказ нельзя оплатить в терминале
{/if*}
</p>


{if $smarty.const.site_version == "full"}
	<noindex>
	<div align="center" id="likebtn">
		<a href="javascript:print();"><img src="{$img_dir}icon/print.png" alt="{l s='Print'}">
		<br>
		Распечатать страницу
		</a>
	</div>
	</noindex>
{/if}	

<br>
<p class="block_hidden_only_for_screen">	
	Инструкция для оператора:<br>
	<strong>Пожалуйста не разбивайте платеж на части.</strong> Это противоречит условиям Партнерского Соглашения с Банком и влечет за собой санкции со стороны Банка.
	<br><br>
	Инструкция для клиента:<br>
	<strong>Пожалуйста проследи, чтобы платеж был один.</strong> Идентификация разбитого на части платежа затруднена.
	
	<br/><br/><br/>
	Байкерский магазин MOTOKOFR: www.motokofr.com
</p>

<p class="" align="center">
	<input type="submit" name="submit" value="Оплатить заказ" class="ebutton green large" /> <br><br>
	<a class="nonprintable" href="{$base_dir_ssl}order.php?step=3">Другие способы оплаты</a>
</p>
</div>

{* подгрузим "содержимое кофра" и детали по заказу *}
{assign var=nobutton value=1}
{include file=$tpl_dir./order-confirmation-product-line.tpl}

<p style="margin-top: 20px" align="center">
	<input type="submit" name="submit" value="Оплатить заказ" class="ebutton green large" /> <br><br>
</p>

</form>
