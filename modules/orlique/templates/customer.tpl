<form method="post" id="newCustomerWrapper" style="display: none;">

        <label for="lastname">
            {l s='Customer Lastname' mod='orlique'}
        </label>
        <div class="margin-form">
            <input type="text" name="lastname" size="20" class="customerNw" />
        </div>
        
        <label for="firstname">
            {l s='Customer Firstname' mod='orlique'}
        </label>
        <div class="margin-form">
            <input type="text" name="firstname" size="20" class="customerNw" />
        </div>
        
        <label for="email">
            {l s='Email' mod='orlique'}
        </label>
        <div class="margin-form">
            <input type="text" name="email" size="20" class="customerNw" />
        </div>

{if $groups && $groups|@count > 0}
        <label for="id_default_group">
            {l s='Default Group' mod='orlique'}
        </label>
        <div class="margin-form">
            <select name="id_default_group" class="customerNw">
        
            {foreach from=$groups item=group}
                <option value="{$group.id_group}">{$group.name}</option>
            {/foreach}
    
            </select>
        </div>
        
        <label for="groupBox">
            {l s='Groups' mod='orlique'}
        </label>
        <div class="margin-form">
            <table class="table" cellspacing="0" cellpadding="0" style="width: 29em;">
                <tr>
                    <th>
                        <input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'groupBox[]\', this.checked)" />
                    </th>
                    <th width="30">
                        {l s='ID' mod='orlique'}
                    </th>
                    <th>
                        {l s='Name' mod='orlique'}
                    </th>
                </tr>
        
    {foreach from=$groups item=group}
                <tr>
                    <td>
                        <input type="checkbox" name="groupBox[]" class="groupBox" id="groupBox_{$group.id_group}" value="{$group.id_group}">
                    </td>
                    <td>
                        {$group.id_group}
                    </td>
                    <td>
                        {$group.name}
                    </td>
                </tr>
    {/foreach}

            </table>
        </div>
{/if}

    <div id="addrWrapper">
        <div class="duplicatedRow addressRow">
            <label>{l s='Company' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][company]" />
            </div>
            
            <label>{l s='VAT number' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][vat_number]" />
            </div>
            
{if $displayDni}
            <label>{l s='DNI' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][dni]" />
            </div>
{/if}
            <label for="address1">{l s='Address' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][address1]" />
            </div>
            
            <label for="address2">{l s='Address' mod='orlique'} (2)</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][address2]" />
            </div>
            
            <label for="postcode">{l s='Postcode/ Zip Code' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][postcode]" />
            </div>
            
            <label for="city">{l s='City' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][city]" />
            </div>
    
{if $countries && $countries|@count > 0}
            <label for="id_country">{l s='Country' mod='orlique'}</label>
            <div class="margin-form">
                <select class="countrySelect" name="addressNw[0][id_country]">
    
    {foreach from=$countries item=country}
        <option value="{$country.id_country}"{if $country.id_country == $selectedCountry} selected="selected"{/if}>{$country.name}</option>
    {/foreach}
                </select>
            </div>
{/if}
            <label for="phone">{l s='Home phone' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][phone]" />
            </div>
            <label>{l s='Mobile phone' mod='orlique'}</label>
            <div class="margin-form">
                <input type="text" size="33" name="addressNw[0][phone_mobile]" />
            </div>
            <label>{l s='Other' mod='orlique'}</label>
            <div class="margin-form">
                <textarea name="addressNw[0][other]" cols="36" rows="4"></textarea>
            </div>
        </div>
        <a href="#" class="duplicator">{l s='Add another address' mod='orlique'}</a>
    </div>
    <div class="margin-form">
        <input type="submit" value="{l s='Save' mod='orlique'}" name="submitAddCustomer" class="button" />
    </div>
</form>