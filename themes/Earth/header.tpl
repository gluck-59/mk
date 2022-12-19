<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" lang="{$lang_iso}">
<!--html lang="{$lang_iso}"-->
	<head>
		<title>{if $smarty.get.search_query}{$smarty.get.search_query} — {/if}{$meta_title|escape:'htmlall':'UTF-8'}</title>
		<meta property="og:title" content="{if $smarty.get.search_query}{$smarty.get.search_query} — {/if}{$meta_title|escape:'htmlall':'UTF-8'}" />
        <meta property="og:url" content="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" />

<!--[if IE]>
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->
{*<!-- <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script> -->*}

		<meta name="description" content="{if isset($meta_description) AND $meta_description}{$meta_description|escape:htmlall:'UTF-8'}
{elseif isset($supplier_name) AND $supplier_name}Подходит для {$supplier_name|escape:htmlall:'UTF-8'}. 
{elseif isset($manufacturer_name) AND $manufacturer_name}Пр-во {$manufacturer_name|escape:htmlall:'UTF-8'}
{else} Продаем и доставляем мото-запчасти и мото-тюнинг со всего мира по почте. Никаких санкций и ограничений{/if}
"/>

{*$supplier_name}--
{$manufacturer_name*}

 {if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:htmlall:'UTF-8'}, {$supplier_name|escape:htmlall:'UTF-8'}, {$manufacturer_name|escape:htmlall:'UTF-8'}" />
{else}
		<meta name="keywords" content="купить аксессуары на мотоцикл, кофры мотоцикл, мото тюнинг, тюнинг мотоцикла, обвес, мото кофры, багажник, батон, спинка мотоцикл, cумки на чоппер, sissy bar, багажник, защитные дуги, люстра на мотоцикл, стекло для мотоцикла, выносы для мотоцикла, мотозапчасти, запчасти на мотоцикл, интернет магазин, motokofr, мотокофр, motocofr" />

{/if}
		<meta http-equiv="Content-Type" content="text/html" />
		<meta charset="utf-8" />
		{*<meta http-equiv="Content-Language" content="ru_RU" />*}
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$img_ps_dir}favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="{$img_ps_dir}favicon.ico" />
		<link href="{$content_dir}js/toastr/toastr.css" rel="stylesheet"/>
{if isset($css_files)}


    	<link rel="stylesheet" type="text/css" href="{$smarty.const._THEME_CSS_DIR_}global.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="{$smarty.const._THEME_CSS_DIR_}print.css" media="print" />


	{*foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach*}

	{if $smarty.const.site_version == "mobile"}			
		<meta name="HandheldFriendly" content="true"/> 
		<link rel="stylesheet" href="{$smarty.const._THEME_CSS_DIR_}tablet.css" />		
		<link rel="stylesheet" href="{$smarty.const._THEME_CSS_DIR_}swiper.css" />		
		<script defer src="//motokofr.com/js/idangerous.swiper.js"></script>
	{/if} 
{/if}

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
{* <meta name="viewport" content="width=device-width"> *}
{* <link rel="stylesheet" media="screen and (max-width: 1024px) " href="/themes/Earth/css/tablet.css" /> 
{* для iPhone 4 @media only screen and (-webkit-min-device-pixel-ratio: 2) *}
{* для iPad @media screen and (max-device-width: 1024px) and (orientation: landscape) *}
{* для смартов @media screen and (min-device-width : 321px) and (max-device-width : 800px) *}

		<script defer type="text/javascript" src="{$content_dir}js/tools.js"></script>
		<script type="text/javascript">
			var baseDir = '{$content_dir}';
			var static_token = '{$static_token}';
			var token = '{$token}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
		</script>

{* сюда нельзя async *}
<script type="text/javascript" src="{$smarty.const._PS_JS_DIR_}jquery191.min.js"></script>		
<script async src="{$content_dir}js/toastr/toastr.js"></script>


{if isset($js_files)}
	{foreach from=$js_files item=js_uri}
    	<script type="text/javascript" src="{$js_uri}"></script>
	{/foreach}
{/if}


{* предзагрузим только в полную версию *}
{if $smarty.const.site_version == "full"}
	{if isset($preload)}
		{foreach from=$preload item=pr_uri}
		<link rel="prefetch" href="{$pr_uri}" />
		<link rel="prerender" href="{$pr_uri}" />
		{*все картинки для опенграфа <meta property="og:image"content="{$pr_uri}">*}
		{/foreach}
		{* послеледняя картинка будет заглавной *}
		<meta property="og:image"content="{$pr_uri}">
	{/if}
{/if}


{* hook header отдаем в обе версии *}	
{$HOOK_HEADER}
        <noscript>
            <div style="width: 100%;position: absolute;background: #333;color: whitesmoke;font-size: 10pt;padding: 15px;text-align: center;top: 160px;">
                Увы, но современный сайт не может работать без Javascript.<br> Включите их в установках браузера.
            </div>
        </noscript>

{* грузим scroll to top только в полную версию *}
{if $smarty.const.site_version == "full"}
	{literal}
	<script defer language="JavaScript">
		$(window.onload = function(){   
			//fade in/out based on scrollTop value
			$(window).scroll(function () {
				if ($(this).scrollTop() > 320) {
					$('#scroller').fadeIn();
					$('#header').css('z-index', '-2');
				} else {
					$('#scroller').fadeOut();
					$('#header').css('z-index', '-1');					
				}
			});
		
			// scroll body to 0px on click
			$('#scroller').click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 400);
				return false;
			});
		});
	</script>
	{/literal}
{/if}


</head>


	<body {if $page_name}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}>
	{if !$content_only}
		<div id="page" class="">

<!-- Header -->
{if $smarty.const.site_version == "full"}

			<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">
            <!--[if IE]>
            	<center>
            	<div style="width: 900px; position: absolute;background: #333;color: whitesmoke;font-size: 10pt;padding: 15px;text-align: center;top: 160px;">
                    <strong>Скачайте и установите современный браузер.</strong><br>Просим прощения, но в Internet Explorer будет недоступна половина функционала сайта.
                </center>
            </div>
        <![endif]-->    
				<img id="logo" src="{$img_ps_dir}logo/logo.png" alt="{$shop_name|escape:'htmlall':'UTF-8'}">
			</a>

       <div id="headermenu">
   
            <ul>
            	<li><a class="rss" style=" background: none; " href="{$base_dir}modules/feeder/rss.php"><img class="rss" style="margin-top: -14px; margin-right: -52px;" src="{$base_dir}img/rss.png" height="38px"></a></li>
                <li><a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">Главная</a></li>
                <li><a href="{$base_dir}my-account.php">Кабинет</a></li>
                <li><a href="{$base_dir}best-sales.php">ТОП-50</a></li>                
                <li><a href="{$base_dir}new-products.php">Свежачок</a></li>
                <li><a href="{$base_dir}catalog.php">Каталоги</a></li>
            </ul>
		</div>
		  <div id="gluck">
				<script language="JavaScript"> 
			      user = "support"; 
			      site = "motokofr.com"; 
			      document.write('<a href=\"mailto:' + user + '@' + site + '?subject=Глюк на сайте\">'); 
			      document.write('Если видишь в сайте глюк,<br> сообщи скорее нам!</a>'); 
              </script>
			</div>


	<div class="header_links">
    	{$HOOK_TOP}
	</div> 
{/if}
			
<div>
    
{if $smarty.const.site_version == "full"}
{*if $page_name != "order" && $page_name != "validation" && $page_name != "submit" && $page_name != "payment"} {* отключим header на страницах оформления заказа **}
	<div id="header">
		<div class="browsers" id="browsers">
			<table align="left" backgrond="none" width="200px" border="0" cellpadding="0" cellspacing="0"> 
				<tr>
		        <td align="center"><a href="http://www.google.com/chrome" target="_blank"><img src="{$base_dir}ie6/img/gc.png" border="0" alt="Google Chrome"></a></td>
		        <td align="center"><a href="http://www.mozilla.com/firefox/" target="_blank"><img src="{$base_dir}ie6/img/mf.png" border="0" alt="Mozilla Firefox"></a></td>
		        <td align="center"><a href="http://ru.opera.com/browser/" target="_blank"><img src="{$base_dir}ie6/img/op.png" border="0" alt="Opera Browser"></a></td>
		        <td align="center"><a href="http://www.apple.com/ru/safari/download/" target="_blank"><img src="{$base_dir}ie6/img/as.png" border="0" alt="Apple Safari"></a></td>
				</tr>
			</table>
		</div>
	  </div> 
    </div>

<div onclick="showtits()" style="position: absolute;top: -255px;right: 249px;height: 39px;width: 24px;">&nbsp;</div>
<img onclick="hidetits()" src="{$img_ps_dir}tits.png" id="tits" style="position: absolute;top: -166px;right: 200px; display:none;">

{literal}
<script defer type="text/javascript">
function showtits()
{
    toastr.info('Правильно! Сиськи — здесь :)');
    document.getElementById('tits').style.display='block';
}
function hidetits()
{
    document.getElementById('tits').style.display='none';
}

</script>
{/literal}



    
{*/if*}
<div class="logo"></div>
<img id="logo_print" src="{$img_ps_dir}logo/logo_paypal.png">
{/if}



{if $smarty.const.site_version == "mobile"}
	<div id="header">
	{$HOOK_TOP}
	     <a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">
	     <img id="logo" src="{$img_ps_dir}logo/logo_paypal.png" alt="{$shop_name|escape:'htmlall':'UTF-8'}" /></a>
	</div> 
	
	<div id="search_block_top" />
	<form method="get" action="{$base_dir}search.php" id="searchbox">
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<span style="">
		    <input required type="search" id="search_query" name="search_query" 
            value="{if $smarty.get.search_query}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if}"
            placeholder="Что искать будем
			 {if isset($smarty.cookies.firstname)}
			 	, {$smarty.cookies.firstname}
			 {/if}
            ?">
		</span>
		<span><input onclick="$(this).closest('form').submit()" type="submit" class="ebutton blue" id="search" value=" "></span>
	</form>
</div>
{/if}

{if $smarty.const.site_version == "full"}
			<!-- Left -->
			{* левая колонка для mobile отдается в footer.tpl *}
			<div id="left_column" class="column">
				{$HOOK_LEFT_COLUMN}
			</div>
{/if}			
<!-- Center -->
			<div id="center_column"  {if $smarty.get.search_query == 'поворот'}style="-webkit-transform: rotate(-1deg);"{/if}>
	{/if}


