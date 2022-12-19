{capture name=path}{l s='Manufacturers'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Manufacturers'} {if $nbManufacturers > 1}<span>({$nbManufacturers} фирм)</span></span>{/if}
</h2>

{if isset($errors) AND $errors}
	{include file=$tpl_dir./errors.tpl}
{else}


{if $nbManufacturers > 0}
	{include file=$tpl_dir./product-sort.tpl}
	<ul id="manufacturers_list">
	{foreach from=$manufacturers item=manufacturer}
		<li>
			<div class="left_side">
				<!-- logo -->
				<div class="logo">
				{if $manufacturer.nb_products > 0}<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$manufacturer.name|escape:'htmlall':'UTF-8'}">{/if}
					<img src="{$img_manu_dir}{$manufacturer.image|escape:'htmlall':'UTF-8'}-medium.jpg" alt="" />
				{if $manufacturer.nb_products > 0}</a>{/if}
				</div>
				<!-- name -->
				<h3>
					{if $manufacturer.nb_products > 0}<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'htmlall':'UTF-8'}">{/if}
					{$manufacturer.name|truncate:60:'...'|escape:'htmlall':'UTF-8'}
					{if $manufacturer.nb_products > 0}</a>{/if}
				</h3>
				<p class="description rte">
				{if $manufacturer.nb_products > 0}<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'htmlall':'UTF-8'}">{/if}
				{$manufacturer.description}
				{if $manufacturer.nb_products > 0}</a>{/if}
				</p>
			</div>

			<div class="right_side">
{* 			{if $manufacturer.nb_products > 0}<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'htmlall':'UTF-8'}">{/if}
				<span>{$manufacturer.nb_products|intval} {if $manufacturer.nb_products > 1}{l s='products'}{else}{l s='product'}{/if}</span>
			{if $manufacturer.nb_products > 0}</a>{/if}
*}
			{if $manufacturer.nb_products > 0}
				<br><a class="ebutton blue small" href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'htmlall':'UTF-8'}">{$manufacturer.nb_products|intval} {if $manufacturer.nb_products > 1}{l s='products'}{else}{l s='product'}{/if}</a>
			{/if}

			</div>
			<br class="clear"/>
		</li>
	{/foreach}
	</ul>
	{include file=$tpl_dir./pagination.tpl}
{/if}
{/if}
