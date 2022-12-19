<!-- productcategory -->
{if count($categoryProducts) > 0}
<script type="text/javascript">var middle = {$middlePosition};</script>
<script type="text/javascript" src="{$content_dir}modules/productscategory/js/productscategory.js"></script>
<ul class="idTabs">
	<li><a href="../category.php?id_category={$id_category}">{l s='In the same category' mod='productscategory'}</a></li>
</ul>

<div id="{if count($categoryProducts) > 5}productscategory{else}productscategory_noscroll{/if}">


{if $smarty.const.site_version == "mobile"}
<!-- swiper -->
{* http://www.idangero.us/sliders/swiper/index.php *}
<div class="swiper-container1">
  <div class="swiper-wrapper">
		{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
		  	<div class="swiper-slide">
			<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category)}" title="{$categoryProduct.name|htmlspecialchars}">
				<img style="border: solid 1px #AAA;border-radius: 5px;" src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'medium')}" alt="{$categoryProduct.name|htmlspecialchars}" />
			<br/>
			{$categoryProduct.name|truncate:45:'...'|escape:'htmlall':'UTF-8'}
			</a>
	  	</div>
		{/foreach}
  </div>
</div>
{/if}		

{$categoryProducts.id_category_default}

{if $smarty.const.site_version == "full"}
{if count($categoryProducts) > 5}<a id="productscategory_scroll_left" title="{l s='Previous' mod='productscategory'}" href="javascript:{ldelim}{rdelim}">{l s='Previous' mod='productscategory'}</a>{/if}
<div id="productscategory_list">
	<ul {if count($categoryProducts) > 5}style="width: {math equation="width * nbImages" width=107 nbImages=$categoryProducts|@count}px"{/if}>
		{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
		<li {if count($categoryProducts) < 6}style="width: {math equation="width / nbImages" width=94 nbImages=$categoryProducts|@count}%"{/if}>
			<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category)}" title="{$categoryProduct.name|htmlspecialchars}">
				<img style="border: solid 1px #AAA;border-radius: 5px;" src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'medium')}" alt="{$categoryProduct.name|htmlspecialchars}" />
			<br/>
			{$categoryProduct.name|truncate:45:'...'|escape:'htmlall':'UTF-8'}
			</a>
		</li>
		{/foreach}
	</ul>
</div>
{if count($categoryProducts) > 5}<a id="productscategory_scroll_right" title="{l s='Next' mod='productscategory'}" href="javascript:{ldelim}{rdelim}">{l s='Next' mod='productscategory'}</a>{/if}
{/if}
	
	

</div>
{/if}
