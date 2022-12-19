{*$create_wishlist*}
<script type="text/javascript" src="{$content_dir}modules/blockwishlist/js/ajax-wishlist.js"></script>
<div id="wishlist_block" class="block wishlist">
	<h4>{l s='Wishlist' mod='blockwishlist'}</h4>
{* не работает if $logged *}


{if $smarty.const.site_version == "full"}
	<div class="block_content">
		<div id="wishlist_block_list" class="expanded">
		{if $products}
			<dl class="products">
			{foreach from=$products item=product name=i}
				<dt class="clear {if $smarty.foreach.i.first}first_item{elseif $smarty.foreach.i.last}last_item{else}item{/if}">
					<a style="font-weight:normal" class="cart_block_product_name" href="{$link->getProductLink($product.id_product, $product.link_rewrite)}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|truncate:80:'...'|escape:'htmlall':'UTF-8'}</a>
				</dt>
			{/foreach}
			</dl>
		{else}
		{* выводится независимо от существования вишлистов  *}
			<dl class="products">
				{*<dt>{l s='Ничего не хочу...' mod='blockwishlist'}</dt>*}
				<br><dt style="text-align:center">Хотелки других байкеров</dt>
				<p style="text-align:center"><a class="ebutton blue" href="/wishlists.php">Посмотреть</a></p><br>
			</dl>
		{/if}
		</div>
{/if}		




{* со свайпером *}
{if $smarty.const.site_version == "mobile"}
		<div id="wishlist_block_list" class="block products_block">
		{if $products}
		<!-- swiper -->
				<div class="swiper-container1">
				  <div class="swiper-wrapper">
						{foreach from=$products item=product name=i}
						  	<div class="swiper-slide swiper-block">
								<a class="cart_block_product_name" href="{$link->getProductLink($product.id_product, $product.link_rewrite)}" title="{$product.name|escape:'htmlall':'UTF-8'}">
								    <img src="{$img_prod_dir}{$product.id_product}-{$product.id_image}-home.jpg" />
                                    <p style="line-height: 120%">{$product.name|truncate:80|escape:'htmlall':'UTF-8'}</p>
                                </a>
						  	</div>
						{/foreach}
				  </div>
				  </div>
		
		{else}
		<dl class="products">
			<dt><b>{l s='No products' mod='blockwishlist'}</b></dt>
			<br><dt style="text-align:center"><a href="/wishlists.php">Посмотреть списки других байкеров</a></dt>
		</dl>
		{/if}
{/if}		

		
		
		
{if $smarty.const.site_version == "full"}		
		<p class="align_center">
		{if $wishlists}
			<select name="wishlists" id="wishlists" onchange="WishlistChangeDefault('wishlist_block_list', $('#wishlists').val());">
			{foreach from=$wishlists item=wishlist name=i}
				<option value="{$wishlist.id_wishlist}"{if $id_wishlist eq $wishlist.id_wishlist or ($id_wishlist == false and $smarty.foreach.i.first)} selected="selected"{/if}>{$wishlist.name|truncate:40:'...'|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
			</select>
		</p>

		<p class="align_center"><br>
			<a href="{$base_dir_ssl}modules/blockwishlist/mywishlist.php" class="ebutton orange" title="{l s='My wishlists' mod='blockwishlist'}">{l s='My wishlists' mod='blockwishlist'}</a>
		</p>
			
		{else}
		{* нет ни одного вишлиста *}
		{* создаем вишлист *}
		<form action="{$base_dir_ssl}modules/blockwishlist/mywishlist.php" method="post" class="std" name="create_wishlist" id="create_wishlist">

<dl class="products">
			<br><dt style="text-align:center"><a href="/wishlists.php">Создать список хотелок</a></dt>
		</dl>
		
				<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
	<!--[if IE]><label class="align_right" for="name">{l s='Name' mod='blockwishlist'}</label><![endif]-->
				<input required type="text" id="name" name="name" placeholder="Для моей Ямашки" style="width: 100%;" />
				<center>
				<input type="submit" name="submitWishlist" id="submitWishlist" value="{l s='Создать' mod='blockwishlist'}" class="ebutton orange" style="font-size: 10pt; text-align:center; width:140px; margin-top:10px; height: 26px;"/>
				</center>

		</form>
		

		{/if}
{/if}
		
		
{if $smarty.const.site_version == "mobile"}	
		<br>
		<span id="select_wishlist">
		{if $wishlists}
			<select name="wishlists" id="wishlists" onchange="WishlistChangeDefault('wishlist_block_list', $('#wishlists').val());">
			{foreach from=$wishlists item=wishlist name=i}
				<option value="{$wishlist.id_wishlist}"{if $id_wishlist eq $wishlist.id_wishlist or ($id_wishlist == false and $smarty.foreach.i.first)} selected="selected"{/if}>{$wishlist.name|truncate:22:'...'|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
			</select>
		{/if}
		</span>

		<span style="text-align:center;" id="select_wishlist_button">
			<a href="{$base_dir_ssl}modules/blockwishlist/mywishlist.php" class="ebutton orange" title="{l s='My wishlists' mod='blockwishlist'}">{l s='My wishlists' mod='blockwishlist'}</a>
		</span>
{/if}
	</div>
</div>
