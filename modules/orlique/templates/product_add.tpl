{if isset($product)}
    <h2>{$product->name}{if $product->reference != ""} ({$product->reference}){/if}</h2>
    <dl class="productShortInfo">
        <dt>{l s='Quantity:' mod='orlique'}</dt>
        <dd>{$product->quantity}</dd>
        <dt>{l s='Price:' mod='orlique'}</dt>
        <dd>{displayPrice price=$product->price currency=$currencyId}</dd>
	</dl>
    
    {if isset($combinations) && $combinations|@count > 0}
    
    <table class="table" cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <th>{l s='Combination' mod='orlique'}</th>
            <th class="center">{l s='Quantity' mod='orlique'}</th>
            <th class="center">{l s='Price' mod='orlique'}</th>
            <th class="center">{l s='Weight' mod='orlique'}</th>
            <th class="left">{l s='Reference' mod='orlique'}</th>
            <th class="right" width="16">{l s='Add' mod='orlique'}</th>
        </tr>
        
        {foreach from=$combinations key=attribute_id item=combination name=i}
            <tr {if $smarty.foreach.i.index % 2 != 0}class="alt_row"{/if} {if $combination.default_on}style="background-color:#D1EAEF"{/if}>
                <td>{$combination.list}</td>
                <td class="center">{$combination.quantity}</td>
                <td class="center">{$combination.price}</td>
                <td class="center">{$combination.weight} {$weightUnit}</td>
                <td class="left">{$combination.reference}</td>
                <td class="right" width="16">
                    <span class="control addProduct" id="combo_{$product->id}_{$attribute_id}"></span>
                </td>
            </tr>
        {/foreach}
    {else}

{*
    <table class="table" id="orderContents" cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <th>1</th>
            <th class="center">2</th>
            <th class="center">3</th>
            <th class="center">4</th>
            <th class="center">5</th>
            <th class="center">6</th>            
            <th class="center">7</th>            
            <th class="center">8</th>            
            <th class="center">9</th>            
            <th class="right" width="16">{l s='Add' mod='orlique'}</th>
        </tr>    
*}
    
    <span class="control addProduct withLabel" id="product_{$product->id}">{l s='Add to order' mod='orlique'}</span>
    {/if}
{else}
<h4 class="error err">{l s='Invalid product selected' mod='orlique'}</h4>
{/if}