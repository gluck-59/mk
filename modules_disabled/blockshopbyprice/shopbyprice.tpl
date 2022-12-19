{capture name=path}{l s='Shop By Price' mod='blockshopbyprice'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Shop By Price' mod='blockshopbyprice'}<noindex><span>({$interval} руб)</span></noindex></h2>

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{else}
	<p class="warning">{l s='No variants' mod='blockshopbyprice'}</p>
	<p><a href="javascript:history.back()">Назад</a></p>
{/if}
