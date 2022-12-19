{if $products}
	<dl class="products" style="font-size:10px;{if $products}border-bottom:1px solid #fff;margin:0 0 5px 0;padding:0 0 5px 0;{/if}">
	{foreach from=$products item=product name=i}
		<dt class="{if $smarty.foreach.i.first}first_item{elseif $smarty.foreach.i.last}last_item{else}item{/if}" style="clear:both;margin:4px 0 4px 0;">
			<a style="font-weight:normal" class="cart_block_product_name" href="{$link->getProductLink($product.id_product, $product.link_rewrite)}" title="{$product.name|escape:'htmlall':'UTF-8'}" style="font-weight:bold;">{$product.name|truncate:100:'...'|escape:'htmlall':'UTF-8'}</a>
		</dt>
		{if isset($product.attributes_small)}
		<dd class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}" style="font-style:italic;margin:0 0 0 10px;">
		</dd>
		{/if}
	{/foreach}
	</dl>
{else}
	<dl class="products" style="font-size:10px;border-bottom:1px solid #fff;margin:0 0 5px 0;padding:0 0 5px 0;">
	{if $error}
		<dt>{l s='You must create a wishlist before adding products' mod='blockwishlist'}</dt>
		<dt style="text-align:center"><a style="text-align:center" class="ebutton blue" href="/modules/blockwishlist/mywishlist.php">Создать</a></dt>				
	{else}
	{* в этом месте у юзера нет вишлистов *}
		{*literal}
		<script type="text/javascript">
			document.create_wishlist.submit();
		</script>
		{/literal*}

		<dt>{l s='У тебя еще нет списка хотелок' mod='blockwishlist'}</dt>
		{*<dt style="text-align:center"><a style="text-align:center" class="ebutton blue" href="/modules/blockwishlist/mywishlist.php">Создать</a></dt>	*}

	{/if}
	</dl>
{/if}
