{if isset($products)}
<!-- Wishlist list -->

{if $p ==1}
	<div class="rte">
	<p style="margin-bottom:-50px">Список хотелок — этакий блокнот. Сюда можно положить понравившиеся ништяки чтобы они не затерялись</p>
	</div>
{else}
	<div style="margin-bottom:-60px;">&nbsp;</div>
{/if}

<ul id="product_list" class="clear">

{foreach from=$products item=product name=products}

	{assign var="customer1" value=$product.id_customer}	
	{assign var="id1" value=$product.id_customer|cat:$product.id_product}
	<article itemscope itemtype="http://schema.org/Offer">	
	
	{if $id1 !== $id2} 

		{if $customer1 !== $customer2} <h2 style="margin-top:60px"><noindex>Байкер {$product.firstname}{if $product.other}, для {$product.other}{/if}:</noindex></h2>{/if}
		
		<div id="out">
		<div id="in">
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
						<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
					{/if}
				</a>
				<link itemprop="image" href="{$link->getImageLink($product.link_rewrite, $product.id_image)}" />
				<link itemprop="url" href="{$product.link|escape:'htmlall':'UTF-8'}"/ >
				<!-- </a> -->
				<h3 itemprop="name">{if $product.new == 1}<span class="new">{l s='new'}</span>{/if}
				<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:150:'...'|escape:'htmlall':'UTF-8'}</a>
				</h3>
				<p itemprop="description" class="product_desc">
				<a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</a>


{if $smarty.const.site_version == "mobile"}				
<p class="product_desc">
	{if $product.on_sale}
		<span class="on_sale">{l s='On sale!'}</span>
			{elseif ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
		<span class="discount">✸ УСПЕВАЙ! ✸</span>
	{/if}
	
	{if !$priceDisplay || $priceDisplay == 2}
	{convertPrice price=$product.price}
		{if $priceDisplay == 2} {l s='+Tx'}
		{/if}
</p>	
{/if}

	<meta itemprop="price" content="{$product.price}" />
	<meta itemprop="priceCurrency" content="USD" />
	
	{if $priceDisplay}
	<p class="product_desc">
	{convertPrice price=$product.price_tax_exc}</p>{if $priceDisplay == 2} {l s='-Tx'}{/if}
	</p>
	{/if}
	
{/if}
	</p>				
				
			{if $product.sales}<br><div style="position: absolute;bottom: -2px; left: 135px;"><p class="product_desc">Этот ништяк уже есть у {$product.sales} байкеров</p></div>{/if}


			</div>
{if $smarty.const.site_version == "full"}
			<div class="right_block">
				{if $product.on_sale}
					<span class="on_sale">{l s='On sale!'}</span>
						{elseif ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
					<span class="discount">✸ УСПЕВАЙ! ✸</span>
				{/if}
				
				{if !$priceDisplay || $priceDisplay == 2}
				<div><span class="price" style="display: inline;">{convertPrice price=$product.price}</span>
					{if $priceDisplay == 2} {l s='+Tx'}
					{/if}</div>
				{/if}
				
                <meta itemprop="price" content="{$product.price}" />
				<meta itemprop="priceCurrency" content="USD" />
				
				{if $priceDisplay}
				<div>
				<span class="price" style="display: inline;">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx'}{/if}</div>{/if}
				{if ($product.allow_oosp OR $product.quantity > 0) && $product.customizable != 2}
				<span class="align_center">
					<a class="ebutton green plist" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir}cart.php?add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}">{l s='Add to cart'}</a>
				{else}
				</span>
				<span class="ebutton inactive plist">{l s='Add to cart'}</span>
				{/if}
				
				<span class="align_center">
				<a href="javascript:;" class="ebutton orange plist" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}',0,1);">
				{l s='В список хотелок' mod='blockwishlist'}</a>
				</span>				
				
				{*<span class="align_center"><a class="ebutton blue plist" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View'}</a>
				</span>*}
				<div class="availability">{if ($product.allow_oosp OR $product.quantity > 0)}{l s='Available'}{else}{l s='Out of stock'}{/if}</div>
			</div>
{/if}			
			
</div>
</div>


			<br class="clear"/>
		</li>
{assign var="customer2" value=$customer1}	
{/if}
{assign var="id2" value=$id1}		

		
	{/foreach}
	</ul>
	<!-- /Products list -->
{/if}
