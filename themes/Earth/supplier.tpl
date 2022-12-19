{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='List of products by supplier:'}&nbsp;{$supplier->name|escape:'htmlall':'UTF-8'}<span>({$nb_products|intval}&nbsp;{declension nb="$nb_products|intval" expressions="ништяк,ништяка,ништяков"})</span></noindex></h2>

{include file=$tpl_dir./errors.tpl}

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No products for this supplier.'}</p>
{/if}