{capture name=path}{l s='Search'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Search'}&nbsp;{if $nbProducts > 0}"{if $query}{$query|escape:'htmlall':'UTF-8'}{elseif $tag}{$tag|escape:'htmlall':'UTF-8'}{elseif $ref}{$ref|escape:'htmlall':'UTF-8'}{/if}"{/if}
{*if $error}&nbsp;(Исправлено: {$error}){/if*}
<noindex>
<span>({$nbProducts|intval}&nbsp;{if $nbProducts == 1}{l s='result has been found.'}{else}{l s='results have been found.'}{/if})</span>
</noindex>
</h2>

{include file=$tpl_dir./errors.tpl}

{if !$nbProducts}
		{if $query}
			<p class="warning" style="word-break: break-all;">
				{l s='No results found for your search'}<br><br>
				<strong>{$query|escape:'htmlall':'UTF-8'}</strong>
				<br><br>
		◄ Попробуй найти похожий ништяк через меню слева
		{else}
			{l s='Please type a search keyword'}
		{/if}
	</p>
{else}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
	
	{if $smarty.const.site_version == "full"}
		{* отскроллимся чуть ниже после загрузки страницы *}					
		{literal}
		<script defer language="JavaScript">
				$('body,html').animate({
					scrollTop: 340
				}, 40);
		</script>
		{/literal}
	{/if}
{/if}
