{if $products}

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
                        {if $smarty.const.site_version == "full"}									
						<img  width="129px" height="129px" src="{$img_prod_dir}{$product.cover}-home.jpg" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
						{else}
						<img  width="200px" height="200px" src="{$img_prod_dir}{$product.cover}-large.jpg" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />						
						{/if}
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
						{if $product.active > 0 AND $product.product_quantity > 0}
						{if ($product.on_sale > 0 ) OR ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
						{assign var="final_price" value=$product.price-$product.reduction_price}
							<span id="old_price">{convertPrice price=$product.price}</span>
							<span class="price" style="">{convertPrice price=$final_price}</span>
						{else}
							<span class="price" style="">{convertPrice price=$product.price}</span>
						{/if}{/if}


				
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

				<!--span class="align_center">
				<a href="javascript:;" class="ebutton orange" onclick="WishlistProductManage('wlp_bought_{$product.id_product_attribute}', 'update', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Save' mod='blockwishlist'}">{l s='Save' mod='blockwishlist'}</a>
				</span-->
	
				<span class="align_center">
				{if $product.active == 0 OR $product.product_quantity == 0}
				<a class="ebutton inactive plist" href="javascript:;">Нет на складе</a>				
				{else}
				<a class="ebutton green plist" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir}cart.php?add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}">{l s='В корзину'}</a>
				{/if}
				</span>
				
				<span class="align_center">
				<a href="javascript:;" class="ebutton red plist" onclick="WishlistProductManage('wlp_bought', 'delete', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Delete' mod='blockwishlist'}">{l s='Delete' mod='blockwishlist'}</a>
				</span>

				{*<span class="align_center"><a class="ebutton blue plist" href="{$link->getProductlink($product.id_product, $product.link_rewrite)}" title="{l s='Подробнее'}">{l s='Подробнее'}</a>
				</span>*}
			</div>

</div>
</div>

<br class="clear"/>
	</li>
	</a>
		</div>

	{/foreach}
	
	
	
	<div class="clear"></div>
	<br />

	{if !$refresh}
	{*<a href="javascript:;" id="hideBoughtProducts" class="button_account_large"  onclick="WishlistVisibility('wlp_bought', 'BoughtProducts');">{l s='Hide products' mod='blockwishlist'}</a>
	<a href="javascript:;" id="showBoughtProducts" class="button_account_large"  onclick="WishlistVisibility('wlp_bought', 'BoughtProducts');">{l s='Show products' mod='blockwishlist'}</a>*}
	{*if count($productsBoughts)}
	<a href="javascript:;" id="hideBoughtProductsInfos" class="button_account_large" onclick="WishlistVisibility('wlp_bought_infos', 'BoughtProductsInfos');">{l s='Hide bought product\'s infos' mod='blockwishlist'}</a>
	<a href="javascript:;" id="showBoughtProductsInfos" class="button_account_large"  onclick="WishlistVisibility('wlp_bought_infos', 'BoughtProductsInfos');">{l s='Show bought product\'s infos' mod='blockwishlist'}</a>
	{/if*}
	<div id="sendtofriend">
	<p align="center"><a href="javascript:;" id="showSendWishlist" class="ebutton blue" onclick="WishlistVisibility('wl_send', 'SendWishlist');">{l s='Send this wishlist' mod='blockwishlist'}</a>
	<a href="javascript:;" id="hideSendWishlist" class="ebutton blue" onclick="WishlistVisibility('wl_send', 'SendWishlist');">{l s='Close send this wishlist' mod='blockwishlist'}</a>
	</p></div>


	<form class="wl_send std hidden" method="post" onsubmit="return (false);">
		<fieldset>
			<p class="required">
<!--[if IE]><label for="email1">E-mail друга</label><![endif]-->
				<input required placeholder="E-mail друга" type="email" name="email1" id="email1" />
				<input style="font-size: 10pt; height: 27px; " class="ebutton blue" type="submit" value="{l s='Send' mod='blockwishlist'}" name="submitWishlist" onclick="WishlistSend('wl_send', '{$id_wishlist}', 'email');" />
<!--[if IE]><sup>*</sup><![endif]-->
			</p>
<!-- 			<p class="submit" style="margin-left:9.5em">
				<input class="button" type="submit" value="{l s='Send' mod='blockwishlist'}" name="submitWishlist" onclick="WishlistSend('wl_send', '{$id_wishlist}', 'email');" />
			</p>
-->
		</fieldset>
	</form>
	{if count($productsBoughts)}
	<table class="wlp_bought_infos hidden std">
		<thead>
			<tr>
				<th class="first_item">{l s='Product' mod='blockwishlist'}</td>
				<th class="item">{l s='Quantity' mod='blockwishlist'}</td>
				<th class="item">{l s='Offered by' mod='blockwishlist'}</td>
				<th class="last_item">{l s='Date' mod='blockwishlist'}</td>
			</tr>
		</thead>
		<tbody>
		{foreach from=$productsBoughts item=product name=i}
			{foreach from=$product.bought item=bought name=j}
			{if $bought.quantity > 0}
				<tr>
					<td class="first_item">
					<span style="float:left;"><img src="{$img_prod_dir}{$product.cover}-small.jpg" alt="{$product.name|escape:'htmlall':'UTF-8'}" /></span>
					<span style="float:left;">{$product.name|truncate:40:'...'|escape:'htmlall':'UTF-8'}
					{if isset($product.attributes_small)}
						<br /><i>{$product.attributes_small|escape:'htmlall':'UTF-8'}</i>
					{/if}</span>
					</td>
					<td class="item align_center">{$bought.quantity|intval}</td>
					<td class="item align_center">{$bought.firstname} {$bought.lastname}</td>
					<td class="last_item align_center">{$bought.date_add|date_format:"%Y-%m-%d"}</td>
				</tr>
			{/if}
			{/foreach}
		{/foreach}
		</tbody>
	</table>
	{/if}
	{/if}
{else}
	{l s='No products' mod='blockwishlist'}
{/if}
