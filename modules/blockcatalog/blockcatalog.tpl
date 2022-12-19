{if $smarty.const.site_version == "full"}
<!-- Block catalog -->
<div id="catalog_block_left" class="block products_block">
	<h4>{l s='По каталогам' mod='blockcatalog'}</h4>
	<div style="height: auto" class="block_content">
		<ul class="products" style="margin: 0px auto 0px -2px;">
			{*foreach from=$catalog item=man*}
			<li>
<ul class="bullet">
<li><a href="/catalog.php">Kuryakyn</a></li>
<li><a href="/catalog.php">Big bike parts</a></li>
<li><a href="/catalog.php">Harley 2016</a></li>
<li><a href="/catalog.php">Show Chrome</a></li>
<li><a href="/catalog.php">UltraCard</a></li>
<li><a href="/catalog.php">Hopnel</a></li>
<li><a href="/catalog.php">Prairie Dog</a></li>
{*<li><a href="/catalog.php">Highway Hawk</a></li>*}
<li><a href="/catalog.php">National Cycle</a></li>
<li><a href="/catalog.php">Vance & Hines</a></li>


{*				<a href="{$catalog.url}">{$catalog.name}
				</a>
*}				


			{*/foreach*}
</ul>

		
		
	</div> 
</div>



{*if $smarty.const.site_version == "mobile"}
<div id="viewed-products_block_left" class="block products_block">
	<h4>{l s='Viewed products' mod='blockviewed'}</h4>
<div id="viewed_block_list" class="expanded">

			<div class="swiper-container2">
			  <div class="swiper-wrapper">
			{foreach from=$name item=viewedProduct name=myLoop}
				  	<div class="swiper-slide">
					<a class="cart_block_product_name" href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">
					<img src="{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'medium')}">
					</a>
					<br>
					<a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">{$viewedProduct->name|escape:htmlall:'UTF-8'}
					</a>
				  	</div>
			{/foreach}
		  </div>
</div>
</div>



			
	</div> 
</div>
{/if*}
{/if}