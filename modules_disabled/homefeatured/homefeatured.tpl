{if $smarty.const.site_version == "full"}
<!-- MODULE Home Featured Products -->
<div id="featured-products_block_center" class="block products_block">
	<h4>{l s='популярное' mod='homefeatured'}</h4>
	{if isset($products) AND $products}
		<div class="homefeatured">
			{assign var='liHeight' value=360}
			{assign var='nbItemsPerLine' value=4}
			{assign var='nbLi' value=$products|@count}
			{assign var='nbLines' value=$nbLi/$nbItemsPerLine|ceil}
			{assign var='ulHeight' value=$nbLines*$liHeight}
			<ul style="height:{$ulHeight}px;">
			{foreach from=$products item=product name=homeFeaturedProducts}
				<a href="{$product.link}" title="{$product.name|escape:'htmlall':'UTF-8'}">
				<li class="ajax_block_product {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{else}item{/if} {if $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 1}first_item_of_line{/if} {if $smarty.foreach.homeFeaturedProducts.iteration > ($smarty.foreach.homeFeaturedProducts.total - ($smarty.foreach.homeFeaturedProducts.total % $nbItemsPerLine))}last_line{/if}">

					<article itemscope itemtype="http://schema.org/Offer">
					<h5 itemprop="name">{$product.name|escape:'htmlall':'UTF-8'}</h5>
					<link itemprop="url" href="{$product.link}">


					<p hidden itemprop="description" class="product_desc">
					{$product.description_short|strip_tags|truncate:130:'...'}
					{*Этот ништяк уже есть <br>у {$product.sales} {declension nb=$product.sales expressions="байкера,байкеров,байкеров"*}
					</p>
					{*<p><br><br><br><br></p>*}



					<img itemprop="image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" height="{$homeSize.height}" width="{$homeSize.width}" />
					{*<img itemprop="image" src="{$link->getImageLink($product.link_rewrite, $product.id_image)}" style="display:none" />*}
					{if $product.hot == 1}<div title="Этот ништяк уже есть у {$product.sales}{declension nb=$sales expressions="байкера,байкеров,байкеров"}" class="hot">{l s='hot'}</div>{/if}
					<div>
						{if !$priceDisplay || $priceDisplay == 2}<p class="price_container"><span class="price" itemprop="price"><meta itemprop="priceCurrency" content="RUB" />

							{convertPrice price=$product.price}</span>{if $priceDisplay == 2} {l s='+Tx' mod='homefeatured'}{/if}</p>
						{/if}
						{if $priceDisplay}<p class="price_container"><span class="price">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx' mod='homefeatured'}{/if}</p>{/if}

						<p class="price_container">
						{if ($product.quantity > 0 OR $product.allow_oosp) AND $product.customizable != 2}
						<a style="margin: 7px 0 7px 0" class="ebutton green small" rel="ajax_id_product_{$product.id_product}" href="{$base_dir}cart.php?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='homefeatured'}">{l s='Add to cart' mod='homefeatured'}</a>
						{else}
						<span style="margin: 7px 0 7px 0" class="ebutton small noactive">{l s='Add to cart' mod='homefeatured'}</span>
						{/if}
						<a class="ebutton blue small" href="{$product.link}" title="{l s='View' mod='homefeatured'}">{l s='View' mod='homefeatured'}</a>
						
						</p>
					</div>
				</li>
				</a>
			{/foreach}
			</ul>
		</div>
	{else}
		<p>{l s='No featured products' mod='homefeatured'}</p>
	{/if}
</div>
{/if}
