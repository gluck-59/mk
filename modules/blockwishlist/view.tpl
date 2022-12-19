<h2>{l s='Wishlist' mod='blockwishlist'} {if $current_wishlist} байкера {$firstname}{/if}</h2>


{if $wishlists|@count > 1}
<div class="rte">
	<p>	У байкера {$firstname} есть несколько списков хотелок: </p><br>
</div>		
{foreach from=$wishlists item=wishlist name=i}
	<a class="ebutton blue" href="{$base_dir_ssl}modules/blockwishlist/view.php?token={$wishlist.token}"><p align="center">{$wishlist.name|truncate:18:'...'|escape:'htmlall':'UTF-8'}</p></a>&nbsp;&nbsp;
{/foreach}
<p>&nbsp;</p>
{/if}


{if $products}
<div class="wlp_bought" id="featured-products_block_center">
	{if $wishlists|@count > 1}<h2>{$firstname} — {$current_wishlist.name}</h2>{/if}
	<p> 
	{foreach from=$products item=product name=i}
	
	
	
	<a href="{$link->getProductlink($product.id_product, $product.link_rewrite)}" title="{l s='Product detail' mod='blockwishlist'}">
	<div class="wishlist {if $smarty.foreach.i.index % 2}alternate_{/if}item" id="wlp_{$product.id_product}_{$product.id_product_attribute}">
		<div id="out">
			<div id="in">
				<ul id="product_list" class="clear">
		
				{* ништяка нет на складе *}
						{*if $product.active == 0 OR $product.product_quantity == 0}
							<div class="inactive">
								<p class="inactive">Этого ништяка сейчас нет на складе</a></br></br>
								<a href="javascript:;" class="ebutton red mini" style="padding-right: 22px;" onclick="WishlistProductManage('wlp_bought', 'delete', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Delete' mod='blockwishlist'}">{l s='Delete' mod='blockwishlist'}</a>	
								</p>
							<div class="inactive-bg"></div></div>
						{/if*}
				{* /ништяка нет на складе *}

				{if $smarty.const.site_version == "full"}				
				<p id="background_pic">
				<img width="300px" height="300px" src="{$img_prod_dir}{$product.cover}-home.jpg">
				</p>
				{/if}		
			
		
				<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if}">
				<div class="center_block">
					<a href="{$link->getProductlink($product.id_product, $product.link_rewrite)}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
						<img  width="129px" height="129px" src="{$img_prod_dir}{$product.cover}-home.jpg" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
						{if $product.new == 1}<div title="Этот ништяк был недавно обновлен" class="new">{l s='new'}</div>{/if}
						{if $product.hot == 1}<div title="Этот ништяк уже есть у {$product.sales} {declension nb=$sales expressions="байкера,байкеров,байкеров"}"class="hot">{l s='hot'}</div>{/if}
					</a>
				
					<h3>
					<a href="{$link->getProductlink($product.id_product, $product.link_rewrite)}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:80:'...'|escape:'htmlall':'UTF-8'}</a>
					</h3>
	
					<p itemprop="description" class="product_desc">
					<a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:150:'...'}
					</a>
					</p>				
					{if $product.sales}<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}"><div class="product_sales"><p class="product_desc">Этот ништяк уже есть у {$product.sales} {declension nb=$product.sales expressions="байкера,байкеров,байкеров"}</p></a></div>{/if}
				</div>

				{*if $smarty.const.site_version == "full"*}
					<div class="right_block">
						{if ($product.on_sale > 0 ) OR ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
						{assign var="final_price" value=$product.price-$product.reduction_price}
							<span id="old_price">{convertPrice price=$product.price}</span>
							<span class="price" style="">{convertPrice price=$final_price}</span>
						{else}
							<span class="price" style="">{convertPrice price=$product.price}</span>
						{/if}


				
				{* Quantity - Priority *}
				<!--{l s='Quantity' mod='blockwishlist'}: -->
				<input hidden type="number" size="10px" min="1" max="10" step="1" value="1" id="quantity_{$product.id_product}_{$product.id_product_attribute}" value="{$product.quantity|intval}" size="4pt" />
				<!--{l s='Priority' mod='blockwishlist'}: -->
				<select hidden id="priority_{$product.id_product}_{$product.id_product_attribute}">
					<option value="0"{if $product.priority eq 0} selected="selected"{/if}>{l s='High' mod='blockwishlist'}</option>
					<option value="1"{if $product.priority eq 1} selected="selected"{/if}>{l s='Medium' mod='blockwishlist'}</option>
					<option value="2"{if $product.priority eq 2} selected="selected"{/if}>{l s='Low' mod='blockwishlist'}</option>
				</select>
				{* /Quantity - Priority *}

				<span class="align_center">
				<a href="javascript:;" class="ebutton orange" onclick="WishlistProductManage('wlp_bought_{$product.id_product_attribute}', 'update', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Save' mod='blockwishlist'}">{l s='Тоже хочу!' mod='blockwishlist'}</a>
				</span>
	
				<!--span class="align_center">
				<a href="javascript:;" class="ebutton red" onclick="WishlistProductManage('wlp_bought', 'delete', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Delete' mod='blockwishlist'}">{l s='Delete' mod='blockwishlist'}</a>
				</span-->

				<span class="align_center">
				{if $product.active == 0 OR $product.product_quantity == 0}
				<a class="ebutton inactive plist" href="javascript:;">Нет на складе</a>				
				{else}
				<a class="ebutton green plist" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir}cart.php?add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}">{l s='В корзину'}</a>
				{/if}
				</span>

				<span class="align_center"><a class="ebutton blue plist" href="{$link->getProductlink($product.id_product, $product.link_rewrite)}" title="{l s='Подробнее'}">{l s='Подробнее'}</a>
				</span>
				<div class="availability">
					{if ($product.allow_oosp OR $product.quantity > 0)}{l s='Available'}{else}{l s='Out of stock'}{/if}
				</div>
			</div>

</div>
</div>

<br class="clear"/>
	</li>
	</li>

	</a>
		</div>	
	
	
	
{*			<a href="{$link->getProductlink($product.id_product, $product.link_rewrite)}" title="{l s='Product detail' mod='blockwishlist'}">
		<ul class="wishlist {if $smarty.foreach.i.index % 2}alternate_{/if}item" id="wlp_{$product.id_product}_{$product.id_product_attribute}">
		<li class="wishlist_title"><h3>{$product.name|truncate:57:'...':true:false|escape:'htmlall':'UTF-8'}</h3></li>
		<li style="text-align: center;">
			<img style="margin:10px 10px 10px 0px" src="{$img_prod_dir}{$product.cover}-large.jpg" width="250px" alt="{$product.name|escape:'htmlall':'UTF-8'}" />
			<br>
		<p align="center" class="wishlist_product_detail" style="margin: 8px 0px 10px -4px;">
		{if ($product.on_sale > 0 ) OR ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
			{assign var="final_price" value=$product.price-$product.reduction_price}
			<span class="price" style="text-decoration: line-through; color:#aaa">{convertPrice price=$product.price}</span>
			<span class="price">{convertPrice price=$final_price}</span>
		{else}
			<span class="price">{convertPrice price=$product.price}</span>
		{/if}	
		</a>	
<!--			{l s='Quantity' mod='blockwishlist'}: -->
			<input hidden type="number" size="10px" min="1" max="10" step="1" value="1" id="quantity_{$product.id_product}_{$product.id_product_attribute}" value="{$product.quantity|intval}" size="4pt" />
<!--			{l s='Priority' mod='blockwishlist'}: -->
			<select class="priority" id="priority_{$product.id_product}_{$product.id_product_attribute}">
				<option disabled value="0"{if $product.priority eq 0} selected="selected"{/if}>{l s='High' mod='blockwishlist'}</option>
				<option disabled value="1"{if $product.priority eq 1} selected="selected"{/if}>{l s='Medium' mod='blockwishlist'}</option>
				<option disabled value="2"{if $product.priority eq 2} selected="selected"{/if}>{l s='Low' mod='blockwishlist'}</option>
			</select>
		</p>
		<p align="center">
{if $smarty.const.site_version == "full"}		
<a href="javascript:;" class="ebutton orange plist" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}',0,1);">{l s='Тоже хочу!' mod='blockwishlist'}</a>
{/if}			

{if $smarty.const.site_version == "mobile"}		
<a href="javascript:;" class="ebutton orange plist" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}',0,1);">{l s='Тоже хочу!' mod='blockwishlist'}</a>
{/if}			
		</p>
		</li>
	</ul>
	</a>
	*}
	
	
	
	{/foreach}
	
	<p class="clear" />
</div>
{else}
	<p class="warning">{l s='В этом списке нет товаров. Выбери другой.' mod='blockwishlist'}</p>
{/if}

