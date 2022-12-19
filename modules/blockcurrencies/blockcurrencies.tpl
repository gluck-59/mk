<!-- Block currencies module -->
<script type="text/javascript" src="{$module_dir}blockcurrencies.js"></script>
<div id="currencies_block_top">
	<form id="setCurrency" action="{$request_uri}" method="post">
		<ul>
			{foreach from=$currencies key=k item=f_currency}
				{* покажем рубли, доллары и евры *}
				{if $f_currency.id_currency == 3 || $f_currency.id_currency == 1 || $f_currency.id_currency == 2}
					<li {if $id_currency_cookie == $f_currency.id_currency}class="selected"{/if}>
						<a href="javascript:setCurrency({$f_currency.id_currency});" title="{$f_currency.name}">{$f_currency.sign}</a>
					</li>
				{/if}
			{/foreach}
		</ul>
		<p>
				<input type="hidden" name="id_currency" id="id_currency" value=""/>
				<input type="hidden" name="SubmitCurrency" value="" />
			{*l s='Currency' mod='blockcurrencies'*}
		</p>
	</form>
</div>
<!-- /Block currencies module -->
