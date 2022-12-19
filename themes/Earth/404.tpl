<h2>{l s='Page not available'}</h2>

		<img src="/themes/Earth/img/not_found.jpg">
{*<p align="center" class="error">
	<img src="{$img_dir}icon/error.gif" alt="{l s='Error'}" class="middle" />
	l s='We\'re sorry, but the Web address you entered is no longer available'
</p>*}
<br>


<p style="font-size: 30pt;">&#9668;<span style="font-size: 10pt;vertical-align: middle;">Попробуй найти похожий ништяк через меню слева</span> </p>
<p>&nbsp;</p>



<form action="{$base_dir}search.php" method="post" class="std">
	<fieldset>
		<p align="center">
<h3>{l s='To find a product, please type its name in the field below'}</h3>		
<!--[if IE]><label for="search">{l s='Search our product catalog:'}</label><![endif]-->
<input style="width:80%" required type="search" id="search_query" name="search_query" placeholder="спинка багажник shadow 1100"{if isset($smarty.get.search_query)}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if} onclick="$(this).closest('form').submit()"  x-webkit-speech="" onwebkitspeechchange="this.form.submit();">
			<input style="border:none" type="submit" name="Submit" value="Искать" class="ebutton green small" />
		</p>
	</fieldset>
</form>

<div align="center"><a href="/" title="На главную"><img src="/themes/Earth/img/icon/home.png" alt="На главную" class="icon" /></a><a href="/" title="{l s='Home'}">{l s='Home'}</a></div>