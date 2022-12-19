{if $points}
<noindex>
<p id="loyalty">
	<img src="{$module_template_dir}loyalty.gif" alt="{l s='Loyalty program' mod='loyalty'}" />
	{l s='By buying this product you can collect up to' mod='loyalty'} {convertPrice price=$voucher} {l s='reward points as a voucher of' mod='loyalty'} 
	<a href="{$module_template_dir}loyalty-program.php" title="{l s='Loyalty program' mod='loyalty'}">{l s='Your reward points.' mod='loyalty'}</a>
</p>
</noindex>
<br class="clear" />
{/if}
