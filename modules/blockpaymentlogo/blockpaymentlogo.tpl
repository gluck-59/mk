{if $smarty.const.site_version == "full"}
<!-- Block payment logo module -->


{* 
<div id="block paiement" class="block" style="text-align: center;">
<div class="block_content" style="   border-top: solid 1px #AAA;    border-radius: 6px 6px 0 0;">
<!-- a target=_blank href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*http://market.yandex.ru/shop/35996/reviews/"><img src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*http://img.yandex.ru/market/informer12.png" border="0" alt="Оцените качество магазина на Яндекс.Маркете." /></a -->

	<a href="{$link->getCMSLink(5, $securepayment)}">
		<img align="middle" src="{$img_dir}visa-master.jpg" /></a>
	<p>&nbsp;</p>
	<a href="{$link->getCMSLink(5, $securepayment)}" onclick="javascript:window.open('https://www.paypal.com/ru/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img src="{$img_dir}paypal.gif" width="80px" height="21px" alt="Что такое PayPal"></a>
	<a href="https://passport.webmoney.ru/asp/certview.asp?wmid=196530046929" target=_blank><IMG SRC="{$img_dir}attestated.png" width="80px" height="28px" title="Здесь находится аттестат нашего WM идентификатора 196530046929" border="0"></a>
		
	<a href="{$link->getCMSLink(5, $securepayment)}">		
		<img align="middle" src="{$img_dir}banki.jpg" />
		</a>
</div>
*}

<div id="paiement" style="text-align: center;">

<a href="{$link->getCMSLink(5, $securepayment)}">
	<img class="banki" src="{$img_dir}visa.png" />
</a>

<a href="{$link->getCMSLink(5, $securepayment)}">
	<img class="banki" src="{$img_dir}mastercard.png" />
</a>

<a href="{$link->getCMSLink(5, $securepayment)}" onclick="javascript:window.open('https://www.paypal.com/ru/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');">
	<img  class="banki" src="{$img_dir}paypal.png" alt="Что такое PayPal" />
</a>

<!--a href="https://passport.webmoney.ru/asp/certview.asp?wmid=196530046929" target=_blank>
	<img class="banki" SRC="{$img_dir}webmoney.png" title="Здесь находится аттестат нашего WM идентификатора 196530046929" border="0">
</a>

<a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}sberbank.png" />
</a>

<a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}alfabank.png" />
</a>

<a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}vtb24.png" />
</a>


<!--a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}tcs.gif" />
</a>		

<a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}mts.jpg" />
</a>		

<a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}svz.png" />
</a>		

<a href="{$link->getCMSLink(5, $securepayment)}">		
	<img class="banki" src="{$img_dir}euroset.png" />
</a-->		

{literal}<!-- Yandex.Metrika informer -->
<a href="https://metrika.yandex.ru/stat/?id=924826&amp;from=informer"
target="_blank" rel="nofollow"><img hidden src="//bs.yandex.ru/informer/924826/3_0_A67171FF_865151FF_1_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:924826,lang:'ru'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->{/literal}

<br><center style="color: #641; padding: 10px">
© Motokofr 2010–{$smarty.now|date_format:"%Y"}</center>
	
</div>

<!-- /Block payment logo module -->
{/if}
