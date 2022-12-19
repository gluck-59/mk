<div class="{if $smarty.foreach.product_defer.last}last_item{elseif $smarty.foreach.product_defer.first}first_item{/if}{if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0}alternate_item{/if} cart_item">

		{if $product_defer.active == 0 OR $product_defer.product_quantity == 0}
			<div class="inactive">
				<p class="inactive">Этого ништяка сейчас нет на складе</br></br>
{*				<a href="javascript:;" class="ebutton red mini" style="padding-right: 22px;" onclick="WishlistProductManage('wlp_bought', 'delete', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Delete' mod='blockwishlist'}">{l s='Delete' mod='blockwishlist'}</a>	*}
				</p>
			<div class="inactive-bg"></div></div>
		{/if}
		
		
	<div  class="cart_product">
		<a href="{$link->getProductLink($product_defer.id_product, $product_defer.link_rewrite, $product_defer.category)|escape:'htmlall':'UTF-8'}">
		<img src="/img/p/{$product_defer.id_product}-{$product_defer.id_image}-home.jpg"></a>
	</div>
	
	
	<div class="cart_description">
		<h3><a href="{$link->getProductLink($product_defer.id_product, $product_defer.link_rewrite, $product_defer.category)|escape:'htmlall':'UTF-8'}">{$product_defer.name|escape:'htmlall':'UTF-8'}</a></h3>
		{if $product_defer.attributes}<a href="{$link->getProductLink($product_defer.id_product, $product_defer.link_rewrite, $product_defer.category)|escape:'htmlall':'UTF-8'}">{$product_defer.attributes|escape:'htmlall':'UTF-8'}</a>{/if}
	</div>
	
	<div class="cart_total">
		{if ($product_defer.on_sale > 0 ) OR ($product_defer.reduction_price != 0 || $product_defer.reduction_percent != 0) && ($product_defer.reduction_from == $product_defer.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product_defer.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product_defer.reduction_from))}
			{assign var="final_price" value=$product_defer.price-$product_defer.reduction_price}
			<span class="price" style="text-decoration: line-through; color:#aaa">{convertPrice price=$product_defer.price}</span><br>
			<span class="price">{convertPrice price=$final_price}</span>
		{else}
			<span class="price">{convertPrice price=$product_defer.price}</span>
		{/if}
		</span>
	</div>	
	



		
			
		<div class="defer">
			<form name="tocart_{$product_defer.id_product}" action="{$base_dir}cart.php" method="post">
		
			<!-- hidden datas -->
			<p class="hidden">
				<input type="hidden" name="token" value="{$static_token}" />
				<input type="hidden" name="id_product" value="{$product_defer.id_product|intval}" id="product_page_product_id" />
				<input type="hidden" name="add" value="1" />
				<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
			</p>
				
			<span>
			<a style="color:#5d717e;" href="{$base_dir_ssl}cart.php?delete&amp;id_product={$product_defer.id_product|intval}&amp;ipa={$product_defer.id_product_attribute|intval}&amp;token={$token_cart}" onclick="javascript:WishlistCart('wishlist_block_list', 'delete', '{$product_defer.id_product|intval}', $('#idCombination').val(), 1);">Удалить совсем</a>
			</span>		 

			<span style="color:#5d717e;">&nbsp;&nbsp;|&nbsp;&nbsp;</span>

			<span>
		 	<a style="color:#5d717e;" type="submit" href="javascript:document.tocart_{$product_defer.id_product}.submit()" onclick="javascript:WishlistCart('wishlist_block_list', 'delete', '{$product_defer.id_product|intval}', $('#idCombination').val(), 1);" name="Submit">Обратно в корзину</a>
			</span>

		</form>
	</div>	
</div>

