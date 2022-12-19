<a href="{$accessory.link|escape:'htmlall':'UTF-8'}" title="{$accessory.name|escape:'htmlall':'UTF-8'}" class="category_image">
    <div class="item">
        <p class="desc">{$accessory.name|strip_tags}</p>
        <p class="category_description"></p>
    	<div>
        	<center style="position: relative">
        		<img src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home')}" title="{$accessory.name|escape:'htmlall':'UTF-8'}" alt="{$accessory.legend|escape:'htmlall':'UTF-8'}" />
                {if $accessory.acc_hot == 1}<div title="Этот ништяк уже есть у {$accessory.acc_sales}{declension nb=$accessory.acc_sales expressions="байкера,байкеров,байкеров"}" class="hot">{l s='hot'}</div>{/if}
                {if $accessory.acc_new == 1}<div title="Этот ништяк был недавно обновлен" class="new">{l s='new'}</div>{/if}
        	</center>
    		<p>&nbsp;</p>
            <p class="desc">{displayWtPrice p=$accessory.price}</p>
    	</div>
    </div>
</a>
