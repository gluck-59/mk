{foreach from=$universalpay item=ps}
	<p class="payment_module">
		<a href="{$this_path_ssl}payment.php?id_universalpay_system={$ps.id_universalpay_system}" title="{$ps.name}">
			<img src="{$img_ps_dir}pay/{$ps.id_universalpay_system}.jpg" alt="{$ps.name}"/>
			{$ps.description_short}
		</a>
	</p>
{/foreach}