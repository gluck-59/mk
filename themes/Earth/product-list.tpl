{if isset($products)}

	<!-- Products list -->
	<ul id="product_list" class="clear">
	{foreach from=$products item=product name=products}
	
		<div id="out" {if $ajax}style="opacity: 0; top: 100px; transition: opacity, top 0.3s cubic-bezier(0, 0, 0, 1.5);"{/if}>
		<div id="in">
			<article itemscope itemtype="http://schema.org/Offer">	
			<meta itemprop="price" content="{$product.price}" />
			<meta itemprop="priceCurrency" content="USD" />
			<link itemprop="image" href="{$link->getImageLink($product.link_rewrite, $product.id_image)}" />
			<link itemprop="url" href="{$product.link|escape:'htmlall':'UTF-8'}"/ >

				

				{if $smarty.const.site_version == "full"}				
				<p id="background_pic">
				<img width="300px" height="300px" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}">
				</p>
				{/if}		
			
		
			<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if}">
			<div class="center_block">
				<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
					{if $smarty.const.site_version == "full"}				
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
					{/if}
					{if $smarty.const.site_version == "mobile"}				
					<img  width="200px" height="200px" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'large')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
					{/if}
					
					
					{if $product.new == 1}<div title="Этот ништяк был недавно обновлен" class="new">{l s='new'}</div>{/if}
					{if $product.hot == 1}<div title="Этот ништяк уже есть у {$product.sales} {declension nb=$sales expressions="байкера,байкеров,байкеров"}"class="hot">{l s='hot'}</div>{/if}
				</a>
			
				<!-- </a> -->
				<h3 itemprop="name">
				<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a>
				</h3>
				<h5 itemprop="description" class="product_desc">
    				{if $smarty.const.site_version == "full"}
    				<a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:150:'...'}
    				{else}
    				<a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'}
    				{/if}
				</a>
                </h5>				
				{if $product.sales}<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}"><div class="product_sales">
				<h5 class="product_desc">Этот ништяк уже есть у {$product.sales} {declension nb=$product.sales expressions="байкера,байкеров,байкеров"}</h5></a></div>{/if}
			</div>

			{*if $smarty.const.site_version == "full"*}
			<div class="right_block">
				{if $product.on_sale}
					<span class="on_sale">{l s='On sale!'}</span>
						{elseif ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
					<span class="discount">✸ УСПЕВАЙ! ✸</span>
				{/if}
				
				{if !$priceDisplay || $priceDisplay == 2}
				<div><span class="price" style="display: inline; 				
					{if ($product.allow_oosp OR $product.quantity == 0) && $product.customizable != 2}color:#bbb; font-weight:normal; {/if}">
					{convertPrice price=$product.price}</span>
					{if $priceDisplay == 2} {l s='+Tx'}
					{/if}</div>
				{/if}
				
				{if !$nobutton}{* если product-list.tpl грузится со страницы подтверждения заказа, кнопки не нужны *}
    				{if $priceDisplay}
        				<div>
        				<span class="price" style="display: inline;">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx'}{/if}</div>{/if}
        				{if ($product.allow_oosp OR $product.quantity > 0) && $product.customizable != 2}
        				<span class="align_center">
        					<a class="ebutton {if $product.in_cart}in_cart{else} green{/if} plist" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir}cart.php?add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}">{if $product.in_cart}{l s='Already in cart'}{else}{l s='Add to cart'}{/if}</a>
    				{else}
        				</span>
        				<span class="ebutton inactive plist">{l s='Add to cart'}</span>
    				{/if}
    
    				<span class="align_center">
    				<a href="javascript:;" class="ebutton orange plist" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}',0,1);">
    				{l s='В список хотелок' mod='blockwishlist'}</a>
    				</span>				
				{/if}

				{*<span class="align_center"><a class="ebutton blue plist" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View'}</a>
				</span>*}
				<div class="availability">{if ($product.allow_oosp OR $product.quantity > 0)}{l s='Available'}{else}{l s='Out of stock'}{/if}</div>
			</div>
		{*/if*}
			
</div>
</div>

<br class="clear"/>
	</li>
	
	{/foreach}
</ul>

<!-- /Products list -->
{/if}

