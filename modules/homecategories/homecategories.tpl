{* из топ-50 товаров вычисляются наиболее популярные категории *}
<!-- MODULE Home categories -->
    <h2>Популярное</h2>
    {if isset($categories) AND $categories}
        <div class="home_categories">
            {foreach from=$categories item=category name=homeCategories}
                {assign var='categoryLink' value=$link->getcategoryLink($category.id_category, $category.link_rewrite)}
                <a href="{$categoryLink}" q="{$category.sold}" class="category_image" title="{$category.name}">
                
                <div class="item {if $smarty.foreach.homeCategories.first}first_item{elseif $smarty.foreach.homeCategories.last}last_item{else}item{/if}">
                    {$category.name}
                    <div>
                        <img src="{$img_cat_dir}{$category.id_category}.jpg" alt="{$category.name}" title="{$category.name}" class="categoryImage" />
                       <p class="category_description">{$category.description}</p>
                    </div>
                </div>
                
                </a>
            {/foreach}
        </div>
    {else}
        <p>{l s='No categories' mod='homecategories'}</p>
  {/if}
<!-- /MODULE Home categories -->
