{* если заказ меньше Х тысяч *}
{if $total_terminal < 15000}
<p class="payment_module">
	<a href="{$this_path_ssl}payment.php" title="Оплата через терминал">
		<img style="margin-right: 25px;float: left;" src="{$this_path}terminalpay.jpg" alt="Оплата заказа через терминал" />
		Терминалы моментальной оплаты.
		<br>
		От нескольких минут до нескольких часов.
		<br>
		Комиссия зависит от терминала.
	</a>
</p>




<div id="order-detail-content" class="table_block nonprintable">
	<table class="std payment">
		<tbody>
		{foreach from=$products item=product name=products}
		

			{if !$product.deleted}
				{assign var='productId' value=$product.id_product}
				{assign var='productAttributeId' value=$product.id_product_attribute}
				{if isset($customizedDatas.$productId.$productAttributeId)}{assign var='productQuantity' value=$product.quantity-$product.customizationQuantityTotal}{else}{assign var='productQuantity' value=$product.quantity}{/if}
				
				<!-- Classic products -->
				{if $product.quantity > $product.customizationQuantityTotal}
					<tr class="item">
						{if $return_allowed}<td class="order_cb"><input type="checkbox" id="cb_{$product.id_product|intval}" name="ids_order_detail[{$product.id_product|intval}]" value="{$product.id_product|intval}" /></td>{/if}
						<td>
							<label for="cb_{$product.id_product|intval}">{if {$product.id_product}<a target="_blank"} href="/../product.php?id_product={$product.id_product}"><img src="/../img/p/{$product.id_image}-medium.jpg"></a>{else}-{/if}</label><br>
						</td>

						<td class="item">
							<label for="cb_{$product.id_product|intval}">
								{if $product.download_hash && $invoice}
									<a href="{$base_dir}get-file.php?key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}" title="{l s='download this product'}">
										<img src="{$img_dir}icon/download_product.gif" class="icon" alt="{l s='Download product'}" />
									</a>
									<a href="{$base_dir}get-file.php?key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}" title="{l s='download this product'}">
										{$product.name|escape:'htmlall':'UTF-8'}
										<br>
										{$product.description_short}										
									</a>
								{else}
									<a target="_blank" href="/../product.php?id_product={$product.id_product}">
									{$product.name|escape:'htmlall':'UTF-8'}
									<br>
									{$product.description_short}																			
									</a>
								{/if}
							</label>
						</td>
						<td class="price"><label for="cb_{$product.id_product|intval}">{convertPriceWithCurrency price=$product.total_wt currency=$currency convert=0}</label></td>
					</tr>
				{/if}
			{/if}
		{/foreach}
		{foreach from=$discounts item=discount}
			<tr class="item">
				<td>{$discount.name|escape:'htmlall':'UTF-8'}</td>
				<td>{$discount.description|escape:'htmlall':'UTF-8'}</td>
				<td><span class="order_qte_span editable">1</span></td>
				<td>&nbsp;</td>
				<td>{l s='-'}{convertPriceWithCurrency price=$discount.value currency=$currency convert=0}</td>
				{if $return_allowed}
				<td>&nbsp;</td>
				{/if}
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
{/if}
{*$total_terminal*}