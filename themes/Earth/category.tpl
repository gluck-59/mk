{include file=$tpl_dir./breadcrumb.tpl} 
{include file=$tpl_dir./errors.tpl}

{if $category->id AND $category->active}
	<h2 class="category_title">{$category->name|escape:'htmlall':'UTF-8'}</h2>
	{if $scenes}
		<!-- Scenes -->
		{include file=$tpl_dir./scenes.tpl scenes=$scenes}
	{else}
		<!-- Category image -->
        {if $smarty.const.site_version == "full"}
    		{if $category->description}
                <noindex>
        		    <div class="cat_desc">{$category->description}</div>
                </noindex> 
    		{/if}
    		{if $category->id_image}
    			<center><img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" /></center>
    		{/if} 
        {/if}
	{/if}

	{if isset($subcategories)}
	<!-- Subcategories -->
	<div id="subcategories">
		<h3 style="text-shadow:none; color:#374853;; margin-left:22px;">{l s='Subcategories'}</h3>
		<ul class="inline_list">
		{foreach from=$subcategories item=subcategory}
			<li>
				<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}">
					{if $subcategory.id_image}
						<img src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium')}" alt="" />
					{else}
						<img src="{$img_cat_dir}default-medium.jpg" alt="" />
					{/if} 
				{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
			</li>
		{/foreach}
		</ul>
		<br class="clear"/>
	</div>
	{/if}

	{if $products}
			{include file=$tpl_dir./product-sort.tpl}
			{include file=$tpl_dir./product-list.tpl products=$products}
			{include file=$tpl_dir./pagination.tpl}
		{elseif !isset($subcategories)}
			<p class="warning">{l s='There is no product in this category.'}</p>
		{/if}
{elseif $category->id}
	<p class="warning">{l s='This category is currently unavailable.'}</p>
{/if}
