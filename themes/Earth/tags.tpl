{capture name=path}{l s='Product tags'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{$nbProducts} {declension nb="$nbProducts" expressions="ништяк,ништяка,ништяков"} с тегом &laquo;{$tag}&raquo;</h2>

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No products with this tags'}</p>
{/if}

{* отскроллимся ниже после загрузки страницы *}
{if $smarty.const.site_version == "full"}
{literal}						
<script async type="text/javascript">
	jQuery(document).ready(function()
			{   
			$('body,html').animate({
				scrollTop: 340
			}, 40);
		});
</script>		
{/literal}						
{/if}						