<div class="addressItem">
    <div class="address">
        <input type="hidden" class="id_address"  value="{$address.id_address}" />
        <input type="hidden" class="id_customer" value="{$address.id_customer}" />
        
        <div style="float: right">
            <a target="_blank" href="{$addressEditUrl}">
                <img src="../img/admin/edit.gif" />
            </a>
            <a href="{$addressGoogleUrl}">
                <img src="../img/admin/google.gif" alt="" class="middle" />
            </a>
        </div>
        
        {if $address.company != ""}
            {$address.company}<br />
        {/if}
        
        {$address.firstname} {$address.lastname}<br />
        {$address.address1}<br />
        
        {if $address.address2 != ""}
            {$address.address2}<br />
        {/if}
        
        {$address.postcode} {$address.city}<br />
        {$address.country} {if $address.id_state}{$address.state}{/if}<br />
        
        {if $address.phone != ""}
            {$address.phone}<br />
        {/if}
        
        {if $address.phone_mobile != ""}
            {$address.phone_mobile}<br />
        {/if}
        
        {if $address.other != ""}
            {$address.other}<br />
        {/if}
    </div>
    <hr />
    <span class="control addAddress shipping">
        <img src="../img/admin/delivery.gif" />
        {l s='Set as shipping address' mod='orlique'}
    </span>
    <span class="control addAddress invoice">
        <img src="../img/admin/invoice.gif" />
        {l s='Set as invoice address' mod='orlique'}
    </span>
</div>