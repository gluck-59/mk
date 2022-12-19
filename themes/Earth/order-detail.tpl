<meta http-equiv="Cache-Control" content="no-cache">
<h4>{l s='Order placed on'} <span class="color-myaccount">{l s='#'}{$order->id|string_format:"%d"}</span> от {$order->date_add|date_format:"%d %b %Y"}</h4>
{if $followup}
	<p>&nbsp;</p>
{*11<pre>{$track_last|print_r}</pre>22*}
        {* разберем последний статус трека и покажем картинку *}
    	{foreach from=$track_last item=tracks_last name="tracks_last"}
       	    <div class="tracking_no">
  	    	    {if $tracks_last->shipping_number}ПОСЫЛКА {$tracks_last->shipping_number|upper}{/if}
      	    </div>
    	    <span {if $tracks_last->checkpoints->time}class="tooltip" tooltip="Обновлено {$tracks_last->checkpoints->time|date_format:"%e %b %Y %k:%M"}"{/if}>
        	    <div class="message {if $tracks_last->checkpoints->status_code}{$tracks_last->checkpoints->status_code}{else}not_found{/if}">&nbsp;</div>
    	    </span>
    	{/foreach}


        {* разберем все статусы трека и выведем таблицу *}
        {foreach from=$track item=tracks name="tracks"}
            {*<pre>{$tracks.checkpoints|@print_r}</pre>*}
            {if $tracks.checkpoints} {* если нет checkpoints, таблицу не выводим *}
            	<div class="block-center" id="{$tracks.shipping_number|strip}">
            		<table class="std" id="sipping-list">
            			<thead>
            				<tr>
            					<th class="first_item">{$tracks.shipping_number|upper}</th>
            					<th colspan="2" class="last_item">
                					{if $tracks.weight}&nbsp;&nbsp;|&nbsp;&nbsp;{$tracks.weight} г.{/if}
                					{if $tracks.service}&nbsp;&nbsp;|&nbsp;&nbsp;{$tracks.service}{/if}
                					{if $tracks.recipient}&nbsp;&nbsp;|&nbsp;&nbsp;{$tracks.recipient}{/if}            					
                				</th>
            				</tr>
            			</thead>
            			<tbody>
                			
            			{foreach from=$tracks.checkpoints item=states name="shipStates"}
                			
            				{if $states->time != NULL}
            					<tr class="{if $smarty.foreach.shipStates.first}first_item{elseif $smarty.foreach.shipStates.last}last_item{/if} {if $smarty.foreach.shipStates.index % 2}alternate_item{else}item{/if}">
            						<td><span class="states_location">{$states->time|date_format:"%e %b %Y"}<br>{$states->time|date_format:"%k:%M"}</span></td>
            						<td><span class="states_message">{$states->status_name}</span>
                						<br><span class="states_location">{if $states->location_translated}{$states->location_translated}{elseif $states->location_raw}{$states->location_raw}{else}{/if}
                						{if $states->location_zip_code}<a target="_blank" href="http://gdeposylka.ru/courier/{$states->courier->slug}/{$states->location_zip_code}">(<u>{$states->location_zip_code}</u>)</a>{/if}
                						{*if $states.weight != "" AND $states.weight != "0"}<br>Контроль веса {$states.weight} кг.{/if}</span*> [* вес на уровень выше *}
                				    </td>
                					<td>
                    					<a href="http://gdeposylka.ru/courier/{$states->courier->slug}" target="_blank">
                    					<img class="courier" title="" height="32px" src="http://gdeposylka.ru/img/icons/128x128/{$states->courier->slug}.png"
                    					</a>
                					</td>
            					</tr>
            				{/if}
                        {/foreach}				
            				
            				
            			</tbody>
            		</table>    
{*
    			<a target="_blank" href="{$followup|replace:'@':$tracks->shipping_number|escape:'htmlall':'UTF-8'}">
        		<p class="tracking">Запасной способ отследить посылку {$tracks->shipping_number|upper}</p>
                </a>
*}
            		
           		</div>
      	
            		
           		
           		<p>&nbsp;</p><p>&nbsp;</p>
       		{/if}			
        {/foreach}
        
        
        	
        {else}
           	<p>&nbsp;</p>
            <div class="tracks_last">
                <div class="message wait">
                </div>
            </div>
        {/if}



{if count($order_history)}
<div class="table_block">
	<table class="detail_step_by_step std">
		<thead>
			<tr>
				<th colspan="2" class="first_item">{l s='Follow your order step by step'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$order_history item=state name="orderStates"}
			<tr class="{if $smarty.foreach.orderStates.first}first_item{elseif $smarty.foreach.orderStates.last}last_item{/if} {if $smarty.foreach.orderStates.index % 2}alternate_item{else}item{/if}">
				<td>{$state.date_add|date_format:"%d %b %Y"}</td>
				<td>{$state.ostate_name|regex_replace:"/[0-9.]/":""}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
{/if}
{if $invoice AND $invoiceAllowed}
<p>
	<img src="{$img_dir}icon/pdf.gif" alt="" class="icon" />
	<a href="{$base_dir}pdf-invoice.php?id_order={$order->id|intval}">{l s='Download your invoice as a .PDF file'}</a>
</p>
{/if}
{if $order->recyclable}
<p><img src="{$img_dir}icon/recyclable.gif" alt="" class="icon" />&nbsp;{l s='You have given permission to receive your order in recycled packaging.'}</p>
{/if}
{if $order->gift}
	<p><img src="{$img_dir}icon/gift.gif" alt="" class="icon" />&nbsp;{l s='You requested gift-wrapping for your order.'}</p>
	<p>{l s='Message:'} {$order->gift_message|nl2br}</p>
{/if}
<br />

<fieldset class="address">
			<legend class="address_title">{$address_delivery->alias}</legend>
			{*<div id="map_canvas" class="map_canvas"></div>*}
			<p class="address_address">Получатель:</p>			
			<p class="address_name">&bull; {$address_delivery->firstname|escape:'htmlall':'UTF-8'} {$address_delivery->address2|escape:'htmlall':'UTF-8'} {$address_delivery->lastname|escape:'htmlall':'UTF-8'}</p>
			<p class="address_address">&bull; {$address_delivery->address1|escape:'htmlall':'UTF-8'} {if $deliveryState} - {$deliveryState->name|escape:'htmlall':'UTF-8'}{/if}</p>
			<p class="address_country">&bull; {$address_delivery->postcode|escape:'htmlall':'UTF-8'}, {$address_delivery->country|escape:'htmlall':'UTF-8'}, {$address_delivery->city|escape:'htmlall':'UTF-8'}</p>
			<p>&nbsp;</p>
			{if $address_delivery->phone}<p class="address_phone">&bull; {$address_delivery->phone|escape:'htmlall':'UTF-8'}</p>{/if}
			
			{if $address_delivery->other}<p class="address_other">{$address_delivery->other|escape:'htmlall':'UTF-8'}</p>{/if}			
			{if $address_delivery->phone_mobile}<p class="address_phone_mobile">{$address_delivery->phone_mobile|escape:'htmlall':'UTF-8'}</p>{/if}
			{if $address_delivery->company}<p class="address_passport">Паспорт {$address_delivery->company}</p>{/if}
		</fieldset>
		
		
<form action="{$base_dir_ssl}order-follow.php" method="post">
<div id="order-detail-content" class="table_block">
	<table class="std">
		<thead>
			<tr>
				{if $return_allowed}<th class="first_item"><input type="checkbox" /></th>{/if}
				<th width="15%" class="{if $return_allowed}item{else}first_item{/if}">{l s='Ништяки в этом заказе'}</th>
				<th width="30%" class="item"></th>
					{if $smarty.const.site_version == "full"}				
						<th class="item">{l s='К-во'}</th>
						<th class="item">{l s='Unit price'}</th>
					{/if}
				<th class="last_item">{l s='Total price'}</th>
			</tr>
		</thead>
		<tfoot style="line-height: 0pt;">
			{if $priceDisplay}
				<tr class="item">
					<td colspan="{if $return_allowed}6{else}5{/if}">
						{l s='Total products (tax excl.):'} <span class="price">{displayWtPriceWithCurrency price=$order->getTotalProductsWithoutTaxes() currency=$currency convert=0}</span>
					</td>
				</tr>
			{/if}
			<tr class="item">
				<td colspan="{if $return_allowed}6{else}5{/if}">
					{l s='Total products (tax incl.):'} <span class="price">{displayWtPriceWithCurrency price=$order->getTotalProductsWithTaxes() currency=$currency convert=0}</span>
				</td>
			</tr>
			{if $order->total_discounts > 0}
			<tr class="item">
				<td colspan="{if $return_allowed}6{else}5{/if}">
					{l s='Total vouchers:'} <span class="price-discount">{displayWtPriceWithCurrency price=$order->total_discounts currency=$currency convert=0}</span>
				</td>
			</tr>
			{/if}
			{if $order->total_wrapping > 0}
			<tr class="item">
				<td colspan="{if $return_allowed}6{else}5{/if}">
					{l s='Total gift-wrapping:'} <span class="price-wrapping">{displayWtPriceWithCurrency price=$order->total_wrapping currency=$currency convert=0}</span>
				</td>
			</tr>
			{/if}
			<tr class="item">
				<td colspan="{if $return_allowed}6{else}5{/if}">
					{l s='Total shipping (tax incl.):'} <span class="price-shipping">{displayWtPriceWithCurrency price=$order->total_shipping currency=$currency convert=0}</span>
				</td>
			</tr>
			<tr class="item">
				<td colspan="{if $return_allowed}6{else}5{/if}">
					{l s='Total:'} <span class="price">{displayWtPriceWithCurrency price=$order->total_paid currency=$currency convert=0}</span>
				</td>
			</tr>
		</tfoot>
		<tbody>
		{foreach from=$products item=product name=products}
			{if !$product.deleted}
				{assign var='productId' value=$product.product_id}
				{assign var='productAttributeId' value=$product.product_attribute_id}
				{if isset($customizedDatas.$productId.$productAttributeId)}{assign var='productQuantity' value=$product.product_quantity-$product.customizationQuantityTotal}{else}{assign var='productQuantity' value=$product.product_quantity}{/if}
				<!-- Customized products -->
				{if isset($customizedDatas.$productId.$productAttributeId)}
					<tr class="item">
						{if $return_allowed}<td class="order_cb"></td>{/if}
						<td>
						<label for="cb_{$product.id_order_detail|intval}">{if $product.product_reference}{$product.product_reference|escape:'htmlall':'UTF-8'}{else}--{/if}</label>
						</td>
						<td class="item">
							<label for="cb_{$product.id_order_detail|intval}">{$product.product_name|escape:'htmlall':'UTF-8'}</label>
						</td>
						<td><input class="order_qte_input" name="order_qte_input[{$smarty.foreach.products.index}]" type="text" size="2" value="{$customizationQuantityTotal|intval}" /><label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$product.customizationQuantityTotal|intval}</span></label></td>
						
						<td><label for="cb_{$product.id_order_detail|intval}">{convertPriceWithCurrency price=$product.product_price_wt currency=$currency convert=0}</label></td>
						<td><label for="cb_{$product.id_order_detail|intval}">{if isset($customizedDatas.$productId.$productAttributeId)}{convertPriceWithCurrency price=$product.total_customization_wt currency=$currency convert=0}{else}{convertPriceWithCurrency price=$product.total_wt currency=$currency convert=0}{/if}</label></td>
					</tr>
					{foreach from=$customizedDatas.$productId.$productAttributeId item='customization' key='customizationId'}
					<tr class="alternate_item">
						{if $return_allowed}<td class="order_cb"><input type="checkbox" id="cb_{$product.id_order_detail|intval}" name="customization_ids[{$product.id_order_detail|intval}][]" value="{$customizationId|intval}" /></td>{/if}
						<td colspan="2">
						{foreach from=$customization.datas key='type' item='datas'}
							{if $type == $CUSTOMIZE_FILE}
							<ul class="customizationUploaded">
								{foreach from=$datas item='data'}
									<li><img src="{$pic_dir}{$data.value}_small" alt="" class="customizationUploaded" /></li>
								{/foreach}
							</ul>
							{elseif $type == $CUSTOMIZE_TEXTFIELD}
							<ul class="typedText">{counter start=0 print=false}
								{foreach from=$datas item='data'}
									<li>{l s='Text #'}{counter}{l s=':'} {$data.value}</li>
								{/foreach}
							</ul>
							{/if}
						{/foreach}
						</td>
						<td>
							<input class="order_qte_input" name="customization_qty_input[{$customizationId|intval}]" type="text" size="2" value="{$customization.quantity|intval}" /><label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$customization.quantity|intval}</span></label>
						</td>
						<td colspan="2"></td>
					</tr>
					{/foreach}
				{/if}
				<!-- Classic products -->
				{if $product.product_quantity > $product.customizationQuantityTotal}
					<tr class="item">
						{if $return_allowed}<td class="order_cb"><input type="checkbox" id="cb_{$product.id_order_detail|intval}" name="ids_order_detail[{$product.id_order_detail|intval}]" value="{$product.id_order_detail|intval}" /></td>{/if}
						<td>
						
						<label for="cb_{$product.id_order_detail|intval}">{if {$product.product_id}<a target="_blank"} href="product.php?id_product={$product.product_id}">
						<img src="http://img.{$smarty.server.SERVER_NAME}/p/{$product.product_id}-{$product.image}-home.jpg"></a>{else}-{/if}</label><br>

						</td>
						<td class="item">
							<label for="cb_{$product.id_order_detail|intval}">
								{if $product.download_hash && $invoice}
									<a href="{$base_dir}get-file.php?key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}" title="{l s='download this product'}">
										<img src="{$img_dir}icon/download_product.gif" class="icon" alt="{l s='Download product'}" />
									</a>
									<a href="{$base_dir}get-file.php?key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}" title="{l s='download this product'}">
										{$product.product_name|escape:'htmlall':'UTF-8'}
									</a>
								{else}
									<a target="_blank" href="product.php?id_product={$product.product_id}">{$product.product_name|escape:'htmlall':'UTF-8'}</a>
								{/if}
							</label>
						</td>
					{if $smarty.const.site_version == "full"}				
						<td><input class="order_qte_input" name="order_qte_input[{$product.id_order_detail|intval}]" type="text" size="2" value="{$productQuantity|intval}" /><label for="cb_{$product.id_order_detail|intval}"><span>{$productQuantity|intval}</span></label></td>
						<td><label for="cb_{$product.id_order_detail|intval}">{convertPriceWithCurrency price=$product.product_price_wt currency=$currency convert=0}</label></td>
					{/if}
						<td class="price"><label for="cb_{$product.id_order_detail|intval}">{convertPriceWithCurrency price=$product.total_wt currency=$currency convert=0}</label></td>
					</tr>
				{/if}
			{/if}
		{/foreach}
		{foreach from=$discounts item=discount}
			<tr class="item">
				<td>{$discount.name|escape:'htmlall':'UTF-8'}</td>
				<td>{$discount.description|escape:'htmlall':'UTF-8'}</td>
				<td><span class="order_qte_span editable"></span></td>
				<td>&nbsp;</td>
				<td>{*l s='-'}{convertPriceWithCurrency price=$discount.value*}</td>
				{if $return_allowed}
				<td>&nbsp;</td>
				{/if}
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
{if $return_allowed}
<br />
<p class="">{l s='Merchandise return'}</p>
<p>&nbsp;</p>
<p>{l s='If you want to return one or several products, please mark the corresponding checkbox(es) and provide an explanation for the return. Then click the button below.'}</p>
<p>&nbsp;</p>
<p class="textarea">
	<textarea name="returnText"></textarea>
</p>
<p class="submit">
	<input type="submit" value="{l s='Make a RMA slip'}" name="submitReturnMerchandise" class="button_large" />
	<input type="hidden" class="hidden" value="{$order->id|intval}" name="id_order" />
</p>
<br />
{/if}
</form>
{if count($messages)}
<div class="table_block">
	<table class="detail_step_by_step std">
		<thead>
			<tr>
				<th colspan="2" class="first_item">{l s='Messages'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$messages item=message name="messageList"}
			<tr class="{if $smarty.foreach.messageList.first}first_item{elseif $smarty.foreach.messageList.last}last_item{/if} {if $smarty.foreach.messageList.index % 2}alternate_item{else}item{/if}">
				<td style="">
					{if $message.ename}
						<b>{$message.efirstname|escape:'htmlall':'UTF-8'} {$message.elastname|escape:'htmlall':'UTF-8'}</b>
					{elseif $message.clastname}
						<b>{$message.cfirstname|escape:'htmlall':'UTF-8'} {$message.clastname|escape:'htmlall':'UTF-8'}</b>
					{else}
						<b>{$shop_name|escape:'htmlall':'UTF-8'}</b>
					{/if}
					<br />
					{dateFormat date=$message.date_add full=1}
				</td>
				<td>{$message.message|nl2br}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
{/if}
{if isset($errors) && $errors}
	<div class="error">
		<p>{if $errors|@count > 1}{l s='There are'}{else}{l s='There is'}{/if} {$errors|@count} {if $errors|@count > 1}{l s='errors'}{else}{l s='error'}{/if} :</p>
		<ol>
		{foreach from=$errors key=k item=error}
			<li>{$error}</li>
		{/foreach}
		</ol>
	</div>
{/if}
<p>&nbsp;</p><p>&nbsp;</p>
<form action="{$base_dir}order-detail.php" method="post" class="std" id="sendOrderMessage">
	<p class="address_address">{l s='Add a message:'}</p>
	<p>&nbsp;</p>
	<p class="textarea">
		<textarea required style="height: 90pt;" placeholder="{l s='If you want to leave us comment about your order, please write it below.'}" name="msgText"></textarea>
	</p>
	<p class="submit" align="center">
		<input type="hidden" name="id_order" value="{$order->id|intval}" />
		<br><input style="" type="submit" class="ebutton blue" name="submitMessage" value="{l s='Send'}"/>
	</p>
</form>


<!-- gmaps -->
{literal}
    <script type="text/javascript">

    var map = null;
    var geocoder = null;

      if (GBrowserIsCompatible()) {
        geocoder = new GClientGeocoder();
    
    var address = '{/literal}{$address_delivery->country|escape:'htmlall':'UTF-8'} {$address_delivery->city|escape:'htmlall':'UTF-8'} {$address_delivery->address1|escape:'htmlall':'UTF-8'}{literal}';
    var baloon = '{/literal}{$address_delivery->alias}{literal}';

      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              map = new GMap2(document.getElementById("map_canvas"));
			  // адрес не найден - не показываем ничего
            } else {
              map = new GMap2(document.getElementById("map_canvas"));
              map.setCenter(point, 17);
              var marker = new GMarker(point);
              map.addOverlay(marker);
              marker.openInfoWindowHtml(baloon);
            }
          }
        );
      }
    }
    </script>
{/literal}
<!-- /gmaps -->

