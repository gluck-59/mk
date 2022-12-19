{if $status == 'ok'}
	<p>{l s='Your order on' mod='westernunion'} <span class="bold">{$shop_name}</span> {l s='is complete.' mod='westernunion'}
		<br /><br />
		{l s='Please send us a WesternUnion transfer with:' mod='westernunion'}
		<br /><br />- {l s='an amout of' mod='westernunion'} <span class="price">{$total_to_pay}</span>
		<br /><br />- {l s='to the account owner of' mod='westernunion'} <span class="bold">{if $westernunionOwner}{$westernunionOwner}{else}___________{/if}</span>
		<br /><br />- {l s='with theses details' mod='westernunion'} <span class="bold">{if $westernunionDetails}{$westernunionDetails}{else}___________{/if}</span>
		<br /><br />- {l s='to this CIF/DNI/NIF' mod='westernunion'} <span class="bold">{if $westernunionAddress}{$westernunionAddress}{else}___________{/if}</span>
		<br /><br />- {l s='Do not forget to insert your order #' mod='westernunion'} <span class="bold">{$id_order}</span> {l s='in the subjet of your Western Union' mod='westernunion'}
		<br /><br />{l s='An e-mail has been sent to you with this information.' mod='westernunion'}
		<br /><br /><span class="bold">{l s='Your order will be sent as soon as we receive your settlement.' mod='westernunion'}</span>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='westernunion'} <a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='westernunion'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='westernunion'} 
		<a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='westernunion'}</a>.
	</p>
{/if}
