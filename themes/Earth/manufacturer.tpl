{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='List of products by manufacturer:'}&nbsp;{$manufacturer->name|escape:'htmlall':'UTF-8'}<noindex><span>({$nb_products|intval}&nbsp;{declension nb="$nb_products|intval" expressions="ништяк,ништяка,ништяков"})</span></noindex></h2>

{if $manufacturer->description && $smarty.const.site_version == "full"}
<div class="rte">
<img style="float:right; margin-left:20px;" src="/img/tmp/manufacturer_mini_{$manufacturer->id_manufacturer}.jpg">
{$manufacturer->description}</div>
{/if}

{include file=$tpl_dir./errors.tpl}

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No products for this manufacturer.'}</p>
{/if}


