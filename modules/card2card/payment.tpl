{if $total < 75000}
<p class="payment_module">
	<a href="{$this_path_ssl}validation.php" title="{l s='Pay with cash on delivery (COD)' mod='card2card'}">
		<img src="{$this_path}card2card.png" alt="{l s='Pay with cash on delivery (COD)' mod='card2card'}" />
		{l s='Pay with cash on delivery (COD)' mod='card2card'}
		<br />{l s='You pay for the merchandise upon delivery' mod='card2card'}
		<br style="clear:both;" />
	</a>
</p>
{/if}