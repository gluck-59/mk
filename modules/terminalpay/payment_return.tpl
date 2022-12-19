{if $status == 'ok'}
<div class="rte">
	<p>Твой заказ в <span class="bold">{$shop_name}</span> размещен. Осталось только его оплатить и получить посылку на почте.
	<br>Не переписывай номер кошелька. Скопируй его или распечатай эту страницу.
	</p>
	
	{if $smarty.const.site_version == "full"}
    	<noindex>
    	<div style="text-align: center" id="">
    		<a href="javascript:print();"><img src="{$img_dir}icon/print.png" alt="{l s='Print'}">
    		<br>Печать</a>
    	</div>
    	</noindex>
    {/if}	


{*l s='Пожалуйста переведи через терминал следующую сумму:' mod='terminalpay'*}
{*<br />{l s='Сумма оплаты' mod='terminalpay'}: <span class="price">{$total_to_pay}</span>*}

<p>
<br />Пункты оплаты: любой терминал, умеющий работать с систмой {$terminalpayPAYSYS}.
<br />Номер кошелька {$terminalpayPAYSYS} и сумма для оплаты: {*<span class="price">{$terminalpayOwner}</span>*}
</p>
	
<br />
<p style="margin: -3px 0 0px 0; font-size: 16pt;font-weight: bold;text-align: center;">
{$terminalpayOwner}
</p>

<p style="margin: -5px 0 0px 0; font-size: 16pt;font-weight: normal;text-align: center;">
{$total_to_pay}
</p>

<p>
<strong>Инструкция:</strong>
<br />В меню терминала выбери "Webmoney" {*<b>{$terminalpayPAYSYS}</b>*} и введи номер кошелька. Часто терминалы не берут комиссию, но это зависит от конкретного владельца терминала. 
<br /><a href="{$base_dir_ssl}contact-form.php">Cообщи нам</a> номер оплаченного заказа.
<br><br> На всякий случай мы продублируем эту инструкцию письмом.		

<br /><br />
		Другие способы связи:
        {if NOT empty($terminalpayICQ)}
            <br />ICQ: <span class="price"><img src="http://status.icq.com/online.gif?web={$terminalpayICQ}&img=5">{$terminalpayICQ}</span>
        {/if}
        {if NOT empty($terminalpaySKYPE)}
       		<br />Skype: <span class="price"><img src="http://mystatus.skype.com/smallicon/sanikov" style="border: none;" alt="Skype status indicator" /><a href="callto:{$terminalpaySKYPE}">{$terminalpaySKYPE}</a></span>
        {/if}
        {if NOT empty($terminalpayPOCHTA)}
       		<br />E-mail: <span class="price"><a href="mailto:{$terminalpayPOCHTA}?subject=Оплата через терминал">{$terminalpayPOCHTA}</a></span>
        {/if}

{*	<br /><br />Если что-то не так — <a href="{$base_dir_ssl}contact-form.php">служба поддержки</a> ответит на все вопросы.*}
	</p>
</div>		
{else}
	<p class="warning">
		Возникла проблема с оформлением заказа. Если эта ошибка повторится снова, свяжись со <a href="{$base_dir_ssl}contact-form.php">службой технической поддержки</a>.
	</p>
{/if}
