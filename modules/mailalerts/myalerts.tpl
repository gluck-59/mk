<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>


	{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account' mod='mailalerts'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='My alerts' mod='mailalerts'}{/capture}
	{include file=$tpl_dir./breadcrumb.tpl}

	<h2 id="cabinet">{l s='My alerts' mod='mailalerts'}</h2>

	{include file=$tpl_dir./errors.tpl}

	{if $id_customer|intval neq 0}
		{if $alerts}
	<ul id="product_list" class="clear">
		
				{foreach from=$alerts item=product name=i}
				

		
		
		
	<div id="out">
	<div id="in">
	
		{if $smarty.const.site_version == "full"}				
		<p id="background_pic">
		<img width="300px" height="300px" src="{$img_prod_dir}{$product.cover}-home.jpg" alt="{$product.name|escape:'htmlall':'UTF-8'}">
		</p>
		{/if}		
	
		<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if}">
		<div class="center_block">
			<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
					<img  width="129px" height="129px" src="{$img_prod_dir}{$product.cover}-home.jpg" alt="{$product.name|escape:'htmlall':'UTF-8'}" />
					{*if $product.new == 1}<div title="Этот ништяк был недавно обновлен" class="new">{l s='new'}</div>{/if}
					{if $product.hot == 1}<div title="Этот ништяк уже есть у {$product.sales} {declension nb=$sales expressions="байкера,байкеров,байкеров"}"class="hot">{l s='hot'}</div>{/if*}
			</a>

			<h3>
			<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:150:'...'|escape:'htmlall':'UTF-8'}</a>
			</h3>
		
			<p class="product_desc">
				<a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</a>
			</p>				
	
			{if $product.sales > 0}<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}"><div class="product_sales"><p class="product_desc">Этот ништяк уже есть у {$product.sales} {declension nb=$product.sales expressions="байкера,байкеров,байкеров"}</p></a></div>{/if}
		</div>

		<div class="right_block">
			<span class="align_center">
				<a class="ebutton red plist" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir_ssl}modules/mailalerts/myalerts.php?action=delete&id_product={$product.id_product}&id_product_attribute={$product.id_product_attribute}">{l s='Удалить'}</a>
			</span>				

			<span class="align_center">
			<a href="javascript:;" class="ebutton orange plist" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}',0,1);">
			{l s='В список хотелок' mod='blockwishlist'}</a>
			</span>				

			<span class="align_center"><a class="ebutton blue plist" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='Подробнее'}">{l s='Подробнее'}</a>
			</span>

		</div>
			
	</div>
	</div>
	<br class="clear">
	
	{/foreach}
</ul>
		
		
		<div id="block-order-detail">&nbsp;</div>

		{else}
			<p class="warning">{l s='You are not subscribed to any alerts.' mod='mailalerts'}</p>
		{/if}
	{/if}

	<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">В Кабинет</a></div></td>
    <td><div align="center"><a href="{$base_dir}">На главную</a></div></td>
  </tr>
</table>


