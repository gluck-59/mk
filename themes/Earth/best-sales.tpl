{capture name=path}{l s='Top sellers'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Top sellers'}</h2>

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No top sellers.'}</p>
{/if}

{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
	jQuery(document).ready(function()
			{   
			$('body,html').animate({
				scrollTop: 340
			}, 40);
		});
	</script>
	{/literal}
{/if}
