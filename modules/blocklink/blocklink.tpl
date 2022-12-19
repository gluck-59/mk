{if $smarty.const.site_version == "full"}
<!-- Block links module -->
<div id="tracking" class="block">
	<h4>Статистика посылок</h4>
	
	<ul class="block_content">
	{foreach from=$tracks item=shipping_numbers}	
		{if $shipping_numbers.2|count_characters == 13} {* показываем только международные треки *}
		{assign var=trackability value=$shipping_numbers.2|truncate:2:""|upper}

		<li> 
			<a title="{$shipping_numbers.1}" style="{if $trackability == "L"}color: #aaa;{/if}" rel="gdeposylka" href="http://gamma.gdeposylka.ru/{$shipping_numbers.2}?tos=accept&apikey=180.ba2ef1012d" target="_blank">{$shipping_numbers.0}</a>
			<a title="{$shipping_numbers.1}" style="float:right;text-transform: uppercase; {if $trackability == "L"}color: #aaa;{/if}" rel="gdeposylka" href="http://gamma.gdeposylka.ru/{$shipping_numbers.2}?tos=accept&apikey=180.ba2ef1012d" target="_blank">{$shipping_numbers.2}</a>
		</li>

		{/if}
	{/foreach}
	</ul>

{*<!-- pre>jopa{$tracks|print_r}</pre-->*}

{* <script type="text/javascript" src="{$content_dir}modules/blocklink/blocklink.js"></script>

{* поставить ?tos=accept&apikey=180.ba2ef1012d&country=RU если сглючит *}
</div>

{/if}