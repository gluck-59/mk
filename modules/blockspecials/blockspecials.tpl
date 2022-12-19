{if $smarty.const.site_version == "full"}
<!-- MODULE Block specials -->
	{if $special}
<div id="special_block_right" class="block products_block exclusive blockspecials">
	<h4>{l s='Specials' mod='blockspecials'}</h4>

	<div class="block_content">
		<ul class="products">
			<li class="product_image" style="line-height: 10pt">
				<a href="{$special.link}"><img style="border: solid 1px #AAA; border-radius: 5px;" src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'medium')}" alt="{$special.legend|escape:htmlall:'UTF-8'}" height="{$mediumSize.height}" width="{$mediumSize.width}" title="{$special.name|escape:htmlall:'UTF-8'}" />
			</li>
			<li id="text">
				{$special.name|escape:htmlall:'UTF-8'|truncate}</a>
				<br>
				<span style="text-decoration: line-through">{displayWtPrice p=$special.price_without_reduction}</span><br>
				{if $special.reduction_percent}<span class="reduction">(-{$special.reduction_percent}%)</span>{/if}
				{if !$priceDisplay || $priceDisplay == 2}<span class="price">{displayWtPrice p=$special.price}</span>{if $priceDisplay == 2} {l s='+Tx'}{/if}{/if}
				{if $priceDisplay == 2}<br />{/if}
				{if $priceDisplay}<span class="price">{displayWtPrice p=$special.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx'}{/if}{/if}
			</li>
		</ul>
		<p>
			<a href="{$base_dir}prices-drop.php" title="{l s='All specials' mod='blockspecials'}" class="ebutton yellow">{l s='All specials' mod='blockspecials'}</a>
		</p>
{* {else}
		<p>{l s='No specials at this time' mod='blockspecials'}</p>
*}
	</div>
</div>
		 {/if}
{/if}