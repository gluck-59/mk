{if isset($productData)}
    {foreach from=$productData item=product}
    <tr{if $product.rowId} id="{$product.rowId}"{/if} {$product.imgStr}>
        <td align="center">
            {$product.image}
            <input type="hidden" class="indexProduct" name="product[{$product.index}][{$product.productId}][index]" value="{$product.index}" />
            <input type="hidden" name="product[{$product.index}][{$product.productId}][product_attribute]" value="{$product.combination}" />
        </td>
        <td class="editable productName">
            <span class="customValue"></span>
            <span class="publicView">
                {$product.productNamePublic}
            </span>
            <span class="realValue">
                <textarea name="product[{$product.index}][{$product.productId}][name]">{$product.productNameReal}</textarea>
            </span>
        </td>
        <td class="editable productReference">
            <span class="customValue"></span>
            <span class="publicView">
                {if $product.reference != ""}{$product.reference}{else}N/A{/if}
            </span>
            <span class="realValue">
                <input type="text" size="20" name="product[{$product.index}][{$product.productId}][reference]" value="{$product.reference}" />
            </span>
        </td>
        <td class="editable productQuantity">
            <span class="customValue"></span>
            <span class="publicView">
                {$product.quantity}
            </span>
            <span class="realValue">
                <input type="text" size="3" class="orderPQuantity" name="product[{$product.index}][{$product.productId}][quantity]" value="{$product.quantity}" />
            </span>
        </td>
        <td class="editable productWeight">
            <span class="customValue"></span>
            <span class="publicView">
                {$product.weight}{$product.weightUnit}
            </span>
            <span class="realValue">
                <input type="text" class="weightFormat" size="3" name="product[{$product.index}][{$product.productId}][weight]" value="{$product.weight}" />
            </span>
        </td>
        <td class="editable">
            <span class="customValue"></span>
            <span class="publicView">
                {displayPrice price=$product.price currency=$product.currency}
            </span>
            <span class="realValue">
                <input type="text" size="6" class="orderPPrice cpOnKeyUp priceFormat" name="product[{$product.index}][{$product.productId}][price]" value="{$product.price}" />
            </span>
        </td>
        <td class="editable">
            <span class="customValue productTax"></span>
            <span class="publicView">
                {$product.taxRate}
            </span>
            <span class="realValue">
                <input type="text" class="cpOnKeyUp percentageFormat" size="6" name="product[{$product.index}][{$product.productId}][tax_rate]" value="{$product.taxRate}" />
            </span>
        </td>
        <td class="editable">
            <span class="customValue"></span>
            <span class="publicView">
                {displayPrice price=$product.priceTaxed currency=$product.currency}
            </span>
            <span class="realValue">
                <input type="text" class="orderPPriceWt cpOnKeyUp priceFormat" size="6" name="product[{$product.index}][{$product.productId}][price_wt]" value="{$product.priceTaxed}" />
            </span>
        </td>
        <td class="productsTotalPrice">
            {displayPrice price=$product.priceTotal currency=$product.currency}
        </td>
        <td align="center">
            <span class="control deleteProduct"></span>
        </td>
    </tr>
    {/foreach}
{/if}