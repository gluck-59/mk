{capture name=path}{l s='New products - breadcrumb'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='New products - H2'} <noindex><span> ({$nbProducts} {declension nb="$nbProducts" expressions="ништяк,ништяка,ништяков"})</span></noindex></h2>

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No new products.'}</p>
{/if}


{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
			$('body,html').animate({
				scrollTop: 340
			}, 10);
	</script>
	{/literal}
{/if}
