<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Your shopping cart'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}
{assign var="preload" value="/order.php?step=1"}


<!-- shopping-cart -->
{assign var='current_step' value='summary'}
{include file=$tpl_dir./errors.tpl}

{if isset($empty)}
	<p class="warning">Твой кофр пока пуст.<br>Положи в него что-нибудь.</p>
    <p class="cart_navigation" align="center">	
		<a href="{if $smarty.server.HTTP_REFERER && strstr($smarty.server.HTTP_REFERER, 'order.php')}{$base_dir}index.php{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'}{/if}" class="ebutton blue large" style="width: 165px;" title="{l s='Continue shopping'}">&laquo;&nbsp;&nbsp;&nbsp;{l s='Continue shopping'}</a>
	</p>
{else}

{include file=$tpl_dir./order-steps.tpl}
<h2>{l s='Shopping cart summary'} {if !isset($empty)}: {$productNumber} {declension nb="$productNumber" expressions="ништяк,ништяка,ништяков"}{/if}</h2>

{if isset($lastProductAdded) AND $lastProductAdded}
	{foreach from=$products item=product}
		{if $product.id_product == $lastProductAdded.id_product AND (!$product.id_product_attribute OR ($product.id_product_attribute == $lastProductAdded.id_product_attribute))}
			<table class="std cart_last_product">
				<thead>
					<tr>
						<th class="cart_product first_item">&nbsp;</th>
						<th class="cart_description item">{l s='Last added product'}</th>
						<th class="cart_total last_item">&nbsp;</th>
					</tr>
				</thead>
			</table>
			<table class="cart_last_product_content">
				<tr>
					<td class="cart_product"><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" /></a></td>
					<td class="cart_description">
						<h5><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h5>
						{if $product.attributes}<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.attributes|escape:'htmlall':'UTF-8'}</a>{/if}
					</td>
				</tr>
			</table>
		{/if}
	{/foreach}
{/if}
{* <p>
	{l s='Your shopping cart contains'} {$productNumber} {if $productNumber > 1}{l s='products'}{else}{l s='product'}{/if}
</p> *}




<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">
		<tfoot style="line-height: 0pt;">
			{if $priceDisplay}
				<tr class="cart_price">
					<td>{l s='Total products (tax excl.):'}&nbsp;</td>
					<td class="price">{convertPrice price=$total_products}</td>
				</tr>
			{/if}
			{if !$priceDisplay || $priceDisplay == 2}
				<tr class="cart_price">
    				<td style="float: left; padding-left: 0;"><a href="/cms.php?id_cms=2" target="_blank">Условия возврата и обмена</a></td>
					<td>{l s='Total products (tax incl.):'}&nbsp;</td>
					<td class="price">{convertPrice price=$total_products_wt}</td>
				</tr>
			{/if}
			{if $total_discounts != 0}
				{if $priceDisplay}
					<tr class="cart_total_voucher">
    					<td></td>
						<td>{l s='Total vouchers (tax excl.):'}&nbsp;</td>
						<td class="price-discount">{convertPrice price=$total_discounts_tax_exc}</td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_voucher">
    					<td></td>
						<td>{l s='Total vouchers (tax incl.):'}&nbsp;</td>
						<td class="price-discount">{convertPrice price=$total_discounts}</td>
					</tr>
				{/if}
			{/if}
			{if $total_wrapping > 0}
				{if $priceDisplay}
					<tr class="cart_total_voucher">
    					<td></td>
						<td>{l s='Total gift-wrapping (tax excl.):'}&nbsp;</td>
						<td class="price-discount">{convertPrice price=$total_wrapping_tax_exc}</td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_voucher">
    					<td></td>
						<td>{l s='Total gift-wrapping (tax incl.):'}&nbsp;</td>
						<td class="price-discount">{convertPrice price=$total_wrapping}</td>
					</tr>
				{/if}
			{/if}
			
				{if $priceDisplay}
					<tr class="cart_total_delivery">
    					<td></td>
						<td>{l s='Total shipping (tax excl.):'}&nbsp;</td>
						<td class="price">
							{if $shippingCost > 0}{convertPrice price=$shippingCostTaxExc}
							{else}{l s='бесплатно'}{/if}
						</td>
					</tr>
				{/if}
				
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_delivery">
    					<td></td>
						<td>{l s='Total shipping (tax incl.):'}&nbsp;</td>
						<td class="price">
							{if $shippingCost > 0}{convertPrice price=$shippingCost}
							{else}{l s='бесплатно'}{/if}
						</td>
					</tr>
				{/if}
			
			{if $priceDisplay}
				<tr class="cart_total_price">
    				<td></td>
					<td >{l s='Total (tax excl.):'}&nbsp;</td>
					<td class="price">{convertPrice price=$total_price_without_tax}</td>
				</tr>
				<tr class="cart_total_voucher">
    				<td></td>
					<td >{l s='Total tax:'}&nbsp;</td>
					<td class="price">{convertPrice price=$total_tax}</td>
				</tr>
			{/if}
			<tr class="cart_total_price">
    			<td></td>
				<td >{l s='Total (tax incl.):'}&nbsp;</td>
				<td class="price">{convertPrice price=$total_price}</td>
			</tr>
			{if $free_ship > 0}
			<tr class="cart_free_shipping">
    			<td></td>
				<td style="white-space: normal;">{l s='Remaining amount to be added to your cart in order to obtain free shipping:'}&nbsp;</td>
				<td class="price">{convertPrice price=$free_ship}</td>
			</tr>
			{/if}
		</tfoot>
		
		
		
		
		
		
		
		
		{foreach from=$products item=product name=productLoop}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			{assign var='quantityDisplayed' value=0}
			{* Display the product line *}
			{include file=$tpl_dir./shopping-cart-product-line.tpl}
			{* Then the customized datas ones*}
			{if isset($customizedDatas.$productId.$productAttributeId)}
				{foreach from=$customizedDatas.$productId.$productAttributeId key='id_customization' item='customization'}
					<tr class="alternate_item cart_item">
						<td colspan="5">
							{foreach from=$customization.datas key='type' item='datas'}
								{if $type == $CUSTOMIZE_FILE}
									<div class="customizationUploaded">
										<ul class="customizationUploaded">
											{foreach from=$datas item='picture'}<li><img src="{$pic_dir}{$picture.value}_small" alt="" class="customizationUploaded" /></li>{/foreach}
										</ul>
									</div>
								{elseif $type == $CUSTOMIZE_TEXTFIELD}
									<ul class="typedText">
										{foreach from=$datas item='textField' name='typedText'}<li>{l s='Text #'}{$smarty.foreach.typedText.index+1}{l s=':'} {$textField.value}</li>{/foreach}
									</ul>
								{/if}
							{/foreach}
						</td>
						<td class="cart_quantity">
							<a class="cart_quantity_delete" href="{$base_dir_ssl}cart.php?delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" title="{l s='Delete this customization'}" class="icon" /></a>
							<p>{$customization.quantity}</p>
							<a class="cart_quantity_up" href="{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}" title="{l s='Add'}"><img src="{$img_dir}icon/quantity_up.gif" alt="{l s='Add'}" /></a><br />
							<a class="cart_quantity_down" href="{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}" title="{l s='Substract'}"><img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Substract'}" /></a>
						</td>
						<td class="cart_total"></td>
					</tr>
					{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
				{/foreach}
				{* If it exists also some uncustomized products *}
				{if $product.quantity-$quantityDisplayed > 0}{include file=$tpl_dir./shopping-cart-product-line.tpl}{/if}
			{/if}
		{/foreach}




	{if $discounts}
		{foreach from=$discounts item=discount name=discountLoop}
			<div class="cart_discount {if $smarty.foreach.discountLoop.last}last_item{elseif $smarty.foreach.discountLoop.first}first_item{else}item{/if}">

				<div class="cart_discount_delete">
					<a href="{$base_dir_ssl}order.php?deleteDiscount={$discount.id_discount}" title="{l s='Delete'}">
				
					{if $smarty.const.site_version == "full"}
						<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="icon" />
					{/if}				
					{if $smarty.const.site_version == "mobile"}
						<div class="cart_delete">&nbsp;&nbsp;&nbsp;</div>
					{/if}				
				</a></div>

				<div class="cart_discount_price">
					<span class="price-discount">
					{if $discount.value_real > 0}
						{if !$priceDisplay || $priceDisplay == 2}{convertPrice price=$discount.value_real*-1}{if $priceDisplay == 2} {l s='+Tx'}<br />{/if}{/if}
						{if $priceDisplay}{convertPrice price=$discount.value_tax_exc*-1}{if $priceDisplay == 2} {l s='-Tx'}{/if}{/if}
					{/if}
				</span></div>
				<div class="cart_discount_description">"{$discount.description}"</div>
				<div class="cart_discount_name">Купон {$discount.name}</div>

			</div>
		{/foreach}
	{/if}





	</table>
</div>


{if $total_price|replace:' ':'' >= $notax}
	{assign var=overprice value=$total_price-$notax}
	{assign var=customs value=$overprice*0.15}
	
	<div id="ajax_tax">
    <h2>Внимание, растаможка!</h2>
		<div class="rte">
		<p style="color: red"> 
			<img src="{$img_dir}icon/import-taxes.png" style="float:left; margin:-10px 20px 0px 0px">
			С 1 января 2020 в России посылки стоимостью выше {$porog} &euro; облагаются ввозной пошлиной.<br> Стоимость доставки — считается!<br><br>
			Приблизительный  размер пошлины для этой посылки — {convertPrice price=$customs}. Пошлина оплачивается на месте при получении посылки.
		</p>
		<br>
		<p>Если ты согласен оплатить пошлину, ты сможешь сделать это на своей почте при получении посылки. Сейчас оплачивть пошлину не нужно.</p>
		<p>Если не согласен — убери какой-нибудь товар, чтобы общая стоимость посылки не превысила <nobr>{convertPrice price=$notax}</nobr> с небольшим запасом на случай колебания курса евро.
		</p>
		<br><br>
		</div>
	</div>
{/if}

{$HOOK_SHOPPING_CART}
{if ($carrier->id AND !$virtualCart) OR $delivery->id OR $invoice->id}
<div class="order_delivery; hidden">
	{if $delivery->id}
	<ul id="delivery_address" class="address item">
		<li class="address_title">{l s='Delivery address'}</li>
		{if $delivery->company}<li class="address_company">{$delivery->company|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_name">{$delivery->lastname|escape:'htmlall':'UTF-8'} {$delivery->firstname|escape:'htmlall':'UTF-8'}</li>
		<li class="address_address1">{$delivery->address1|escape:'htmlall':'UTF-8'}</li>
		{if $delivery->address2}<li class="address_address2">{$delivery->address2|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_city">{$delivery->postcode|escape:'htmlall':'UTF-8'} {$delivery->city|escape:'htmlall':'UTF-8'}</li>
		<li class="address_country">{$delivery->country|escape:'htmlall':'UTF-8'}</li>
	</ul>
	{/if}
	{if $invoice->id}
	<ul id="invoice_address" class="address alternate_item">
		<li class="address_title">{l s='Invoice address'}</li>
		{if $invoice->company}<li class="address_company">{$invoice->company|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_name">{$invoice->lastname|escape:'htmlall':'UTF-8'} {$invoice->firstname|escape:'htmlall':'UTF-8'}</li>
		<li class="address_address1">{$invoice->address1|escape:'htmlall':'UTF-8'}</li>
		{if $invoice->address2}<li class="address_address2">{$invoice->address2|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_city">{$invoice->postcode|escape:'htmlall':'UTF-8'} {$invoice->city|escape:'htmlall':'UTF-8'}</li>
		<li class="address_country">{$invoice->country|escape:'htmlall':'UTF-8'}</li>
	</ul>
	{/if}
	{if $carrier->id AND !$virtualCart}
	<div id="order_carrier">
		<h4>{l s='Carrier:'}</h4>
		{if isset($carrierPicture)}<img src="{$img_ship_dir}{$carrier->id}.jpg" alt="{l s='Carrier'}" />{/if}
		<span>{$carrier->name|escape:'htmlall':'UTF-8'}</span>
	</div>
	{/if}
</div>
{/if}


	<p class="clear"><br /></p>
	<p class="cart_navigation" align="center">
		<a href="{$base_dir_ssl}order.php?step=1" class="ebutton orange large" style="border-radius: 15px 0 0 15px;" title="{l s='Уточнить адрес'}">{l s='Уточнить адрес'}</a>
		<a href="{$base_dir_ssl}order.php?step={if !$invoice->address1}1{else}3{/if}" class="ebutton green large" style="border-radius: 0 15px 15px 0; border-left: 1px solid #aaa;" title="{l s='Оплатить заказ'}">{l s='Оплатить заказ'}</a>
	</p>
	<p class="cart_navigation" align="center">	
		<a href="{if $smarty.server.HTTP_REFERER && strstr($smarty.server.HTTP_REFERER, 'order.php')}{$base_dir}index.php{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'}{/if}" class="ebutton blue large" style="width: 165px;" title="{l s='Continue shopping'}">&laquo;&nbsp;&nbsp;&nbsp;{l s='Continue shopping'}</a>
	</p>


<p class="clear"><br /></p>
<p class="cart_navigation_extra">
	{$HOOK_SHOPPING_CART_EXTRA}
</p>
{/if}


{if $wishlist}
<br><h2 id="cabinet">{l s='Отложенные ништяки'}</h2>
		{foreach from=$wishlist item=product_defer name=product_defer}
			{include file=$tpl_dir./shopping-cart-product-defer-line.tpl}
		{/foreach}
{/if}

{if $voucherAllowed}
<br><div id="cart_voucher" class="rte">
	{if $errors_discount}
		<ul class="error">
		{foreach from=$errors_discount key=k item=error}
			<li>{$error|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ul>
	{/if}
	
	<p  onclick="javascript:$('#voucher').toggle(100)">{l s='Vouchers'}</p>
	<br>
	<form hidden action="{$base_dir_ssl}order.php" method="post" id="voucher">
		<fieldset>
			<div style="border:none" class="submit" align="center">
<!--[if IE]><label for="discount_name">{l s='Code:'}</label><![endif]-->
				<input type="text" id="discount_name" name="discount_name" placeholder="Код купона" value="{if $discount_name}{$discount_name}{/if}" />
			
			<input style="width:auto" type="submit" name="submitDiscount" value="Получить скидку" class="ebutton yellow" /></div>
		</fieldset>
        <center><br><br><p class="tooltip" tooltip="Они прилетают сами. Например на твой День Варенья ;)">Откуда берутся купоны? <img src="../../img/admin/help.png"></p></center>
	</form>
</div>

{/if}

