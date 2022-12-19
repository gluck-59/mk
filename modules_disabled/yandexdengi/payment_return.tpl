{if $status == 'ok'}
	<p>{l s='Your order on' mod='yandexdengi'} <span class="bold">{$shop_name}</span> {l s='is complete.' mod='yandexdengi'}
		<br /><br />
		{l s='Please send us a bank wire with:' mod='yandexdengi'}
		<br /><br />- {l s='an amout of' mod='yandexdengi'} <span class="price">{$total_to_pay}</span>
		<br /><br />- {l s='to the account owner of' mod='yandexdengi'} <span class="bold">{if $yandexdengiOwner}{$yandexdengiOwner}{else}___________{/if}</span>
		<br /><br />- {l s='with theses details' mod='yandexdengi'} <span class="bold">{if $yandexdengiDetails}{$yandexdengiDetails}{else}___________{/if}</span>
		<br /><br />- {l s='to this bank' mod='yandexdengi'} <span class="bold">{if $yandexdengiAddress}{$yandexdengiAddress}{else}___________{/if}</span>
		<br /><br />- {l s='Do not forget to insert your order #' mod='yandexdengi'} <span class="bold">{$id_order}</span> {l s='in the subjet of your bank wire' mod='yandexdengi'}
		<br /><br />{l s='An e-mail has been sent to you with this information.' mod='yandexdengi'}
		<br /><br /><span class="bold">{l s='Your order will be sent as soon as we receive your settlement.' mod='yandexdengi'}</span>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='yandexdengi'} <a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='yandexdengi'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='yandexdengi'} 
		<a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='yandexdengi'}</a>.
	</p>
{/if}
