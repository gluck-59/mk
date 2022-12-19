{if isset($errors) && $errors}
		<div class="error">
		<p>{if $errors|@count > 1}{l s='There are'}{else}{l s='There is'}{/if} {$errors|@count} {if $errors|@count > 1}{l s='errors'}{else}{l s='error'}{/if}:</p><br>
 		{foreach from=$errors key=k item=error} 
			<p>&#10077;&nbsp;{$error}&nbsp;&#10078;</p>
			<p>&nbsp;</p>
		{/foreach}
		<p align="left" style="border-top: 1px solid #aaa;">
			<a href="{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'}" title="{l s='Back'}"><strong>{l s='Back'}</strong></a>
			&nbsp;или&nbsp;
			<a href="/my-account.php" title="Войти"><strong>Вход</strong></a>

		</p>
	</div>
{/if}