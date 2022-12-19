<!-- MODULE blockshopbyprice -->
<noindex>
<div class="block">
	<h4>{l s='Shop By Price' mod='blockshopbyprice'}</h4>
         <div id="categories_block_left">
             <ul class="block_content bullet">
                  {section name=nr loop=$pricerange}
                   <li>
                      {if $pricerange[nr].maxprice == "NoMax"}
                      <a href="{$base_dir}modules/blockshopbyprice/shopbyprice.php?minprice={$pricerange[nr].minprice}"/>{$pricerange[nr].minprice}&nbsp;&nbsp;—&nbsp;&infin;</a>
                       {else}
                     <a href="{$base_dir}modules/blockshopbyprice/shopbyprice.php?minprice={$pricerange[nr].minprice}&maxprice={$pricerange[nr].maxprice}"/>{$pricerange[nr].minprice}&nbsp; — {$pricerange[nr].maxprice}&nbsp;</a>
                   {/if}
                   </li>
                  {/section} 
             </ul> 
        </div> 

</div>
</noindex>
<!-- /MODULE blockshopbyprice-->
