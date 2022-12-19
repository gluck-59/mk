<!-- add to wishlist -->
{if $smarty.const.site_version == "full"}
<p class="align_center">
<a href="javascript:;" class="ebutton orange" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value);">
{l s='Add to my wishlist' mod='blockwishlist'}</a>
</p>
{/if}
{if $smarty.const.site_version == "mobile"}
<span id="add_to_wishlist">
<a href="javascript:;" class="ebutton orange" onclick="javascript:WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value);">
{l s='Add to my wishlist' mod='blockwishlist'}</a>
</span>
{/if}