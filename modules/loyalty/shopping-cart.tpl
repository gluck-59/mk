<!-- MODULE Loyalty -->
<p id="loyalty">
	{if $points > 0}
	<img src="{$module_template_dir}loyalty.gif" alt="{l s='loyalty' mod='loyalty'}" class="icon" />
		{l s='By check out this shopping cart you can collect' mod='loyalty'} {*$points*} {l s='loyalty points as a voucher of' mod='loyalty'} {convertPrice price=$voucher}.
	{*else*}
		{*l s='Add some products to your shopping cart to collect some loyalty points.' mod='loyalty'*}
	<a href="{$module_template_dir}loyalty-program.php" title="{l s='Loyalty program' mod='loyalty'}">{l s='Your reward points.' mod='loyalty'}</a>		
	{/if}

</p>
<!-- END : MODULE Loyalty -->