<!-- Block Viewed products -->
{if $smarty.const.site_version == "full"}
<div id="viewed-products_block_left" class="block products_block" style="overflow: hidden">
	<h4>{l s='Viewed products' mod='blockviewed'}</h4>
	<div style="height:{$qty*99}px; max-height: 440px; overflow-y: scroll" class="block_content" onmouseover="$('body').css('overflow', 'hidden')" onmouseout="$('body').css('overflow', 'scroll')">
		<ul class="products">
			{foreach from=$productsViewedObj item=viewedProduct name=myLoop}
			<li class="{if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{else}item{/if}">

				<table width="100%" border="0" bgcolor="#fff">
				<tr>
					<td valign="middle" height="90px" width="87px"><a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}"><img src="{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'medium')}"  width="{$mediumSize.width}" alt="{$viewedProduct->legend|escape:htmlall:'UTF-8'}"></a>
					</td>
					
					<td valign="middle">
						<a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">{$viewedProduct->name|truncate:80:"...":true:false|escape:htmlall:'UTF-8'}
						</a>
					</td>
				</tr>
				</table>		

			</li>
			{/foreach}
		</ul>
	</div> 
</div>
{/if}


{if $smarty.const.site_version == "mobile"}
<div id="viewed-products_block_left" class="block products_block">
	<h4>{l s='Viewed products' mod='blockviewed'}</h4>
		<div style="width:100%; height:250px; overflow:hidden">
			<div class="swiper-container1">
			  <div class="swiper-wrapper">
				  {foreach from=$productsViewedObj item=viewedProduct name=myLoop}

				  	<div class="swiper-slide">
					<a class="cart_block_product_name" href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">
					    <img src="{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'home')}">
						<p style="line-height: 120%">{$viewedProduct->name|truncate:80:'...'|escape:htmlall:'UTF-8'}</p>
					</a>
				  	</div>

				  {/foreach}
			</div>
		</div>
</div>



			
	</div> 
</div>
{/if}
