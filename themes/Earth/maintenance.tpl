<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}" lang="{$lang_iso}">
	<head>
		<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{if isset($meta_description)}
		<meta name="description" content="{$meta_description|escape:'htmlall':'UTF-8'}" />
{/if}
{if isset($meta_keywords)}
		<meta name="keywords" content="{$meta_keywords|escape:'htmlall':'UTF-8'}" />
{/if}
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />
		<link rel="shortcut icon" href="../img/favicon.ico" />
		<link href="{$css_dir}maintenance.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="maintenance">
			 <p><img src="{$content_dir}img/logo.jpg" alt="logo" /><br /><br /></p>
			 <p id="message">
				 Мотокофр временно приостанавливает работу из-за нарушения логистических цепочек в мире. При первой же возможности мы вернемся и продолжим радовать вас новыми ништяками.
				 <br><br>Все отправленные посылки едут как обычно и мы надеемся, что даже если они и опоздают, то ненадолго.
				 <small><br><br>При задержке вашей посылки обратитесь к перевозчику, доставляющему посылку.
				 <br>Перевозчика можно определить по трек-номеру, который мы прислали вам при отправке посылки.
                 </small>
			 </p>
			 <span style="clear:both;">&nbsp;</span>
		</div>
	</body>
</html>
