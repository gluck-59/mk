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
				Мотокофр выходит из спячки!<br><br>
				 Из-за нарушения логистических цепочек в мире мы долго формировали надежные пути поставок и уже скоро начнем радовать вас новыми ништяками.
				 <small><br><br>Терпение! Совсем скоро всё заработает :)</small>
			 </p>
			 <span style="clear:both;">&nbsp;</span>
		</div>
	</body>
</html>
