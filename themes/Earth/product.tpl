{include file=$tpl_dir./errors.tpl}
{if $errors|@count == 0}
<script type="text/javascript">
// <![CDATA[

// internal settings
var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
var currencyRate = '{$currencyRate|floatval}';
var currencyFormat = '{$currencyFormat|intval}';
var currencyBlank = '{$currencyBlank|intval}';
var taxRate = {$product->tax_rate|floatval};
var jqZoomEnabled = {if $jqZoomEnabled}true{else}false{/if};

//JS Hook
var oosHookJsCodeFunctions = new Array();

// Parameters
var id_product = '{$product->id|intval}';
var productHasAttributes = {if isset($groups)}true{else}false{/if};
var quantitiesDisplayAllowed = {if $display_qties == 1}true{else}false{/if};
var quantityAvailable = {if $display_qties == 1 && $product->quantity}{$product->quantity}{else}0{/if};
var allowBuyWhenOutOfStock = {if $allow_oosp == 1}true{else}false{/if};
var availableNowValue = '{$product->available_now|escape:'quotes':'UTF-8'}';
var availableLaterValue = '{$product->available_later|escape:'quotes':'UTF-8'}';
var productPriceWithoutReduction = {$product->getPriceWithoutReduct()|default:'null'};
var reduction_percent = {if $product->reduction_percent}{$product->reduction_percent}{else}0{/if};
var reduction_price = {if $product->reduction_percent}0{else}{$product->getPrice(true, $smarty.const.NULL, 2, $smarty.const.NULL, true)}{/if};
var reduction_from = '{$product->reduction_from}';
var reduction_to = '{$product->reduction_to}';
var group_reduction = '{$group_reduction}';
var default_eco_tax = {$product->ecotax};
var currentDate = '{$smarty.now|date_format:'%Y-%m-%d'}';
var maxQuantityToAllowDisplayOfLastQuantityMessage = {$last_qties};
var noTaxForThisProduct = {if $no_tax == 1}true{else}false{/if};
var displayPrice = {if isset($priceDisplay)} $priceDisplay {else} 0{/if};

// Customizable field
var img_ps_dir = '{$img_ps_dir}';
var customizationFields = new Array();
{assign var='imgIndex' value=0}
{assign var='textFieldIndex' value=0}
{foreach from=$customizationFields item='field' name='customizationFields'}
{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
	customizationFields[{$smarty.foreach.customizationFields.index|intval}] = new Array();
	customizationFields[{$smarty.foreach.customizationFields.index|intval}][0] = '{if $field.type|intval == 0}img{$imgIndex++}{else}textField{$textFieldIndex++}{/if}';
	customizationFields[{$smarty.foreach.customizationFields.index|intval}][1] = {if $field.type|intval == 0 AND $pictures.$key}2{else}{$field.required|intval}{/if};
{/foreach}

// Images
var img_prod_dir = '{$img_prod_dir}';
var combinationImages = new Array();
{foreach from=$combinationImages item='combination' key='combinationId' name='f_combinationImages'}
combinationImages[{$combinationId}] = new Array();
{foreach from=$combination item='image' name='f_combinationImage'}
combinationImages[{$combinationId}][{$smarty.foreach.f_combinationImage.index}] = {$image.id_image|intval};
{/foreach}
{/foreach}

combinationImages[0] = new Array();
{foreach from=$images item='image' name='f_defaultImages'}
combinationImages[0][{$smarty.foreach.f_defaultImages.index}] = {$image.id_image};
{/foreach}

// Translations
var doesntExist = '{l s='The product does not exist in this model. Please choose another.' js=1}';
var doesntExistNoMore = '{l s='This product is no longer in stock' js=1}';
var doesntExistNoMoreBut = '{l s='with those attributes but is available with others' js=1}';
var uploading_in_progress = '{l s='Uploading in progress, please wait...' js=1}';
var fieldRequired = '{l s='Please fill all required fields' js=1}';


{if isset($groups)}
	// Combinations
	{foreach from=$combinations key=idCombination item=combination}
		addCombination({$idCombination|intval}, new Array({$combination.list}), {$combination.quantity}, {$combination.price}, {$combination.ecotax}, {$combination.id_image}, '{$combination.reference|addslashes}');
	{/foreach}
	// Colors
	{if $colors|@count > 0}
		{if $product->id_color_default}var id_color_default = {$product->id_color_default|intval};{/if}
	{/if}
{/if}

//]]>
</script>

{include file=$tpl_dir./breadcrumb.tpl}

<div id="product_raise" style="">
<div id="primary_block">
<article itemscope itemtype="http://schema.org/Offer">
	<h1 itemprop="name">{$product->name|escape:'htmlall':'UTF-8'}</h1>
	<p hidden itemprop="description">{$product->description_short|strip_tags}</p>
	{if $confirmation}
	<p class="confirmation">
		{$confirmation}
	</p>
	{/if}

	<!-- right infos-->
	<div id="pb-right-column">
		<!-- product img-->
		{if $smarty.const.site_version == "full"} 

				<div id="image-block">
					{if $have_image}
					<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}" {if $jqZoomEnabled}class="jqzoom" alt="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox')}"{else} title="{$product->name|escape:'htmlall':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" {/if} id="bigpic"/>
					{else}
					<img src="{$img_prod_dir}{$lang_iso}-default-large.jpg" title="{$product->name|escape:'htmlall':'UTF-8'}" />
					{/if}
					{if $product_hot == 1}<div title="Этот ништяк уже есть у {$sales} {declension nb=$sales expressions="байкера,байкеров,байкеров"}" class="hot">{l s='hot'}</div>{/if}
					{if $product_new}<div title="Этот ништяк недавно обновлен" class="new">{l s='new'}</div>{/if}
				</div>
					{if count($images) > 0}

					<!-- thumbnails -->
					<div id="views_block" {if count($images) < 2}class="hidden"{/if}>

						{if count($images) > 3}
						<span class="view_scroll_spacer">
							<a id="view_scroll_left" class="hidden" href="javascript:{ldelim}{rdelim}">{l s='Previous'}
							</a>
						</span>
						{/if}

						<div id="thumbs_list">
				 			<ul style="width: {math equation="width * nbImages" width=110 nbImages=$images|@count}px" id="thumbs_list_frame"> 
							{foreach from=$images item=image name=thumbnails}
							{assign var=imageIds value=`$product->id`-`$image.id_image`}
							
							<li id="thumbnail_{$image.id_image}">
							<a href="{$link->getImageLink($product->link_rewrite, $imageIds, '')}" rel="prettyPhoto[mao]" class="{if !$jqZoomEnabled}{/if} {if $smarty.foreach.thumbnails.first}shown{/if}" title="{$image.name|htmlspecialchars}">
							<img itemprop="image" style="border: solid 1px #AAA; border-radius: 5px;" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium')}" alt="{$image.legend|htmlspecialchars}" height="{$mediumSize.height}" width="{$mediumSize.width}" />
							</a>
							<link href="{$link->getImageLink($product->link_rewrite, $imageIds)}" />
							</li>
							{/foreach}
							</ul>
						</div>

					{if count($images) > 3}
						<span class="view_scroll_spacer">
						<a id="view_scroll_right" href="javascript:{ldelim}{rdelim}">{l s='Next'}</a>
						</span>
					{/if}
				</div>
				{/if}		

			{*if count($images) < 2}
				{if $product->tags}
					<!-- теги -->
					<div class="tags">
					{foreach from=$product->tags[3] item=tags}
					<a class="tag" href="{$base_dir}tags.php?tag={$tags|urlencode}">{$tags}<span class="arrow"></span></a>
					{/foreach}
					</div>
				{/if}
			{/if*}
		{/if}	

	{if $smarty.const.site_version == "mobile"}
		<!-- swiper -->
		{* http://www.idangero.us/sliders/swiper/index.php *}

		<div class="swiper-container">
		  <div class="swiper-wrapper">
			  {foreach from=$images item=image name=thumbnails}
			  {assign var=imageIds value=`$product->id`-`$image.id_image`}
			  	<div class="swiper-slide"> 
					<img src="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox')}" />
					{*<link itemprop="image" href="{$link->getImageLink($product->link_rewrite, $imageIds)}" />*}
				</div>
				{/foreach}
		  </div>
          {if count($images) > 1}
            <!-- Add Pagination -->
            <!--div class="swiper-pagination"></div-->
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <script>
            count = 2;
            </script>

            {else}   
            <script>
            count = 1;
            </script>
           {/if}

		</div>
	

	{/if}		
	
	
</div>



	<!-- left infos-->
	{if $smarty.const.site_version == "mobile"}
	<br>
	{/if}
	
	
	<div id="pb-left-column">
{*		{if $packItems|@count > 0}
		<div id="short_description_block">
			{if $product->description}
			<p class="buttons_bottom_block" align="center">
  			<a href="#more_info_block" class="ebutton small">{l s='More details'}</a>
  			<link itemprop="url" href="#"</a>
		<a href="javascript:scroll()" class="ebutton small">{l s='More details'}</a> 
			<a href="javascript:scroll()" class="button small">{l s='More details'}</a> 
			</p>

			{/if}
			{if $packItems|@count > 0}
				<h3>{l s='Pack content'}</h3>
				{foreach from=$packItems item=packItem}
					<div class="pack_content">
						{$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)}">{$packItem.name|escape:'htmlall':'UTF-8'}</a>
						<p>{$packItem.description_short}</p>
					</div>
				{/foreach}
			{/if} 
		</div>
		{/if}*}

		{if $colors}
		<!-- colors -->
		<div id="color_picker">
			<p>{l s='Pick a color:' js=1}</p>
			<div class="clear"></div>
			<ul id="color_to_pick_list">
			{foreach from=$colors key='id_attribute' item='color'}
				<li><a id="color_{$id_attribute|intval}" class="color_pick" style="background: {$color.value};" onclick="updateColorSelect({$id_attribute|intval});">{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}<img src="{$img_col_dir}{$id_attribute}.jpg" alt="" title="{$color.name}" />{/if}</a></li>
			{/foreach}
			</ul>
				<a id="color_all" onclick="updateColorSelect(0);"><img src="{$img_dir}icon/cancel.gif" alt="" title="{$color.name}" /></a>
			<div class="clear"></div>
		</div>
		{/if}

{if $smarty.const.site_version == "full"}
<!-- Block currencies module -->
<noindex>
<div id="currencies_block">
	<form id="setCurrency" action="{$request_uri}" method="post">
		<ul>
			{foreach from=$currencies key=k item=f_currency}
				{if $f_currency.id_currency == 3 || $f_currency.id_currency == 1 || $f_currency.id_currency == 2}
					<li {if $id_currency_cookie == $f_currency.id_currency}class="selected"{/if}>
						<a href="javascript:setCurrency({$f_currency.id_currency});" title="{$f_currency.name}">{$f_currency.sign}</a>
					</li>
				{/if}
			{/foreach}
		</ul>
				<input type="hidden" name="id_currency" id="id_currency" value=""/>
				<input type="hidden" name="SubmitCurrency" value="" />
	</form>
</div>
</noindex>
<!-- /Block currencies module -->
{/if}
			
		<!-- add to cart form-->
		<form id="buy_block" action="{$base_dir}cart.php" method="post" onsubmit="toastr.success('В корзине');">

			<!-- hidden datas -->
			<p class="hidden">
				<input type="hidden" name="token" value="{$static_token}" />
				<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
				<input type="hidden" name="add" value="1" />
				<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
			</p>

			<!-- prices -->
			<p class="price">
				{if $product->on_sale}
					<div class="on_sale">{l s='On sale!'}</div>
				{elseif ($product->reduction_price != 0 || $product->reduction_percent != 0) && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
					<img src="{$img_dir}onsale_{$lang_iso}.png" alt="{l s='On sale'}" class="on_sale_img" />			
						<div class="discount"><span class="tooltip" style="border-bottom:0;" tooltip="Скидка считается по дню оплаты">Успевай&nbsp;до<br></span>
							{*$product->reduction_to|date_format:'%e %B'*}
							<span class="discounts">{$product->reduction_to|date_format:'%d'}</span> <span class="discounts">{$product->reduction_to|date_format:'%m'}</span> <span class="discounts">{$product->reduction_to|date_format:'%G'}</span>
						</div>
					</abbr>
				{/if}

				<div class="our_price_display">
				{if !$priceDisplay || $priceDisplay == 2}
					<div {if $product->quantity == 0} style="color:#bbb"{/if}>{convertPrice price=$product->getPrice(true, $smarty.const.NULL, 2)}</div>
					<meta itemprop="price" content="{$product->getPrice(true, $smarty.const.NULL, 0)}" />
					<meta itemprop="priceCurrency" content="USD" />
					<meta itemprop="alternateName" content="{$product->meta_description}" />
					
				{/if}
				
				{if $priceDisplay == 1}
					<div id="our_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL, 2)}</div>
				{/if}
				</div>
				
			{if $quantity_discounts}
			<!-- quantity discount -->
			<p style="text-align: left;margin: 0 0 4px;">Скидка:</p>
			<div id="quantityDiscount">
				<table class="std">
						<tr style="line-height: 0px;">
							{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
							<th>за {$quantity_discount.quantity|intval} 
							{if $quantity_discount.quantity|intval > 1}
								шт
							{else}
								шт
							{/if}
							</th>
							{/foreach}
						</tr>
						<tr>
							{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
							<td>
							{if $quantity_discount.id_discount_type|intval == 1}
								-{$quantity_discount.value|floatval}%
							{else}
								-{convertPrice price=$quantity_discount.value|floatval}
							{/if}
							</td>
							{/foreach}
						</tr>
				</table>
			</div>
			{/if}

				{if $priceDisplay == 2}
					<br />
					<span id="pretaxe_price"><span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL, 2)}</span>
				<br />
				{/if}
				
			
{if $smarty.const.site_version == "mobile"}
				{if ($product->reduction_price != 0 || $product->reduction_percent != 0) && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
				{if !$priceDisplay || $priceDisplay == 2}
					<p align="center" id="old_price_display">{convertPrice price=$product->getPriceWithoutReduct()}</p>
<!-- 						{l s='tax incl.'} -->
				{/if}
				{if $priceDisplay == 1}
					<span id="old_price_display">{convertPrice price=$product->getPriceWithoutReduct(true)}</span>
<!--						{l s='tax excl.'} -->
				{/if}

			{/if}
{/if}			
			
			</p>

{if $smarty.const.site_version == "full"}
			{if ($product->reduction_price != 0 || $product->reduction_percent != 0) && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
				<p id="old_price">
				<span class="">
				{if !$priceDisplay || $priceDisplay == 2}
					<span id="old_price_display">{convertPrice price=$product->getPriceWithoutReduct()}</span>
<!-- 						{l s='tax incl.'} -->
				{/if}
				{if $priceDisplay == 1}
					<span id="old_price_display">{convertPrice price=$product->getPriceWithoutReduct(true)}</span>
<!--						{l s='tax excl.'} -->
				{/if}
				</span>
				</p>
			{/if}
{/if}			
			
			
			
			{if $product->reduction_percent != 0 && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
				<p id="reduction_percent">{l s='(price reduced by'} <span id="reduction_percent_display">{$product->reduction_percent|floatval}</span> %{l s=')'}</p>
			{/if}
			{*if $packItems|@count}
				<p class="pack_price">{l s='instead of'} <span style="text-decoration: line-through;">{convertPrice price=$product->getNoPackPrice()}</span></p>
				<br class="clear" />
			{/if*}
			{if $product->ecotax != 0}
				<p class="price-ecotax">{l s='include'} <span id="ecotax_price_display">{convertPrice price=$product->ecotax}</span> {l s='for green tax'}</p>
			{/if}

			{if isset($groups)}

			<!-- attributes -->
			<div id="attributes">
			{foreach from=$groups key=id_attribute_group item=group}
			<p>
				<label for="group_{$id_attribute_group|intval}">{$group.name|escape:'htmlall':'UTF-8'} :</label>
				{assign var='groupName' value='group_'|cat:$id_attribute_group}
				<select name="{$groupName}" id="group_{$id_attribute_group|intval}" onchange="javascript:findCombination();">
					{foreach from=$group.attributes key=id_attribute item=group_attribute}
						<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if}>{$group_attribute|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</p>
			{/foreach}
			</div>
			{/if}

{*			 {if $product->reference}<p id="product_reference" {if isset($groups)}style="display:none;"{/if}><label for="product_reference">{l s='Reference :'} </label><span class="editable">{$product->reference|escape}</span></p>{/if}
*}

			<noindex>
			<p class="product_desc">Арт. № {$product->id}</p>
			</noindex>
			
{if $smarty.const.site_version == "full"}
    <noindex>
			<!-- quantity wanted нужен для wishlist -->
			<p hidden id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity == 0) || $virtual} style="display:none;"{/if}>
				<label>{l s='Quantity :'}</label>
  				<input type="number" min="1" max="10" step="1" value="1" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}1{/if}" size="2" maxlength="3" /> 
			</p>


			<!-- number of item in stock -->
			<p id="pQuantityAvailable"{if $display_qties != 1 || ($allow_oosp && $product->quantity == 0)} style="display:none;"{/if}>
				<span id="quantityAvailable">({if $product->quantity|intval > 1}{$product->quantity|intval}{/if}{if $product->quantity|intval == 1}последний&nbsp;{/if}{if $product->quantity|intval == 0}нет&nbsp;{/if}</span>
				{*<span{if $product->quantity > 0} style="display:none;"{/if} id="quantityAvailableTxt">{l s='item in stock'}</span>*}				
				<span{if $product->quantity < 2} style="display:none;"{/if} id="quantityAvailableTxtMultiple">{l s='items in stock'}</span>
			<!-- availability -->
			<span id="availability_statut"{if ($allow_oosp && $product->quantity == 0 && !$product->available_later) || (!$product->available_now && $display_qties != 1) } style="display:none;"{/if}>
				<span id="availability_label">{l s='Availability:'}</span>
				<span hidden id="availability_value"{if $product->quantity == 0} class="warning-inline"{/if}>
					{if $product->quantity == 0}{if $allow_oosp}{$product->available_later}{else}{l s='This product is no longer in stock'}{/if}{else}{$product->available_now}{/if}
				</span>
			</p>
    </noindex>

{/if} 	 						


			
			<!-- Out of stock hook -->
			<p id="oosHook"{if $product->quantity > 0} style="display:none;"{/if}>
				<br>{$HOOK_PRODUCT_OOS}
			</p>
			
			
		{if $smarty.const.site_version == "mobile"}
			<!-- quantity wanted -->
			<noindex>
			<input hidden type="number" min="1" max="10" step="1" value="1" name="qty" id="quantity_wanted" class="text" value="1" size="2" maxlength="3" /> 
			</noindex>
			
		{/if}
			<p class="warning-inline" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity == 0) || $allow_oosp} style="display:none;"{/if} >{l s='Warning: Last items in stock!'}</p>
			
		{if $smarty.const.site_version == "full"}
			<!-- кнопки -->
 	 			  <p{if !$allow_oosp && $product->quantity == 0} style="display:none;"{/if} id="add_to_cart" class="align_center"><input type="submit" name="Submit" value="{if $in_cart}{l s='Already in cart'}{else}{l s='Add to cart'}{/if}" class="ebutton {if $in_cart}in_cart{else}green{/if}" style="width: 138px;margin: 6px auto 2px 0px;padding: 11px;"/></p>
		{/if} 	 			
 	 		
 	 		
		{if $smarty.const.site_version == "mobile"}
			<!-- кнопки -->
     			<span{if !$allow_oosp && $product->quantity == 0} style="display:none;"{/if} id="add_to_cart" class="align_left">
         			<input type="submit" name="Submit" value="{if $in_cart}{l s='Already in cart'}{else}{l s='Add to cart'}{/if}" class="ebutton {if $in_cart}in_cart{else}green{/if}"/>
     			</span>
 		{/if}			

			
		{if $HOOK_PRODUCT_ACTIONS}
			{$HOOK_PRODUCT_ACTIONS}
		{/if}

		{if $smarty.const.site_version == "mobile"}
			<br><br>
		{/if}
<p>&nbsp;</p>

{if $smarty.now|date_format:"%d %b %Y" != $delivery_date|date_format:"%d %b %Y" && $product->quantity != 0}
<p class="geo">{if $product->weight == 0}Бесплатная доставка{else}Доставка{/if}{if $city} в {$city}{/if}:
<br>примерно {$delivery_date|date_format:"%d %b %Y"|mb_strtolower:"utf-8"}&nbsp;

<span class="tooltip" style="border-bottom:0;" tooltip="На основе статистики доставки наших посылок в {$smarty.now-2592000|date_format:"%Y"} году."><img src="../../img/admin/help.png"></span>
</p>
<p>&nbsp;</p>
{/if}

{if $sales > 0 && $sales > 0}<p style="text-align:left">Этот ништяк{/if}

{if $sales > 0}уже есть у {$sales} {declension nb=$sales expressions="байкера,байкеров,байкеров"}.<br>{/if}
{if $sales > 0 && $wishlists > 0}Его{elseif $wishlists > 0}<p style="text-align:left">Этот ништяк{/if}
{if $wishlists > 0}{declension nb=$wishlists expressions="хочет,хотят,хотят"}{if $sales > 0}&nbsp;еще{/if} {$wishlists} {declension nb=$wishlists expressions="байкер,байкера,байкеров"}.{/if}

{if $sales > 0 or $wishlists > 0}</p><p>&nbsp;</p>{/if}


		{if $smarty.const.site_version == "full"}
				{if $product->tags}
					<!-- теги full -->
					<div class="tags">
					{foreach from=$product->tags[3] item=tags}
					<a class="tag" href="{$base_dir}tags.php?tag={$tags|urlencode}">{$tags}<span class="arrow"></span></a>
					{/foreach}
					</div>
				{/if}
		{/if}	


		</form>
		<p class="product_desc">&nbsp;</p>


		{if $HOOK_EXTRA_RIGHT}
			{$HOOK_EXTRA_RIGHT}
		{/if}	

		{if $smarty.const.site_version == "mobile"}
				{if $product->tags}
					<!-- теги mobile -->
					<div class="tags">
					{foreach from=$product->tags[3] item=tags}
					<a class="tag" href="{$base_dir}tags.php?tag={$tags|urlencode}">{$tags}<span class="arrow"></span></a>
					{/foreach}
					</div>
				{/if}
		{/if}	
	</div>
</div>


<!-- description and features -->
{if $product->description || $features || $HOOK_PRODUCT_TAB || $attachments}
<div id="more_info_block" class="clear">


{if $smarty.const.site_version == "full"}
	<noindex>
	<div id="usefull_link_block">
		<a href="javascript:print();"><img style="height: 50px;" src="{$img_dir}icon/print.png" title="{l s='Print'}" alt="{l s='Print'}"></a>
	</div>
	</noindex>
{/if}	
	

<!-- таб кнопки -->
<noindex>
	<div id="more_info_tabs" class="idTabs idTabsShort">
							<div><a class="ebutton blue selected" id="idTab1" onclick="showtabs('idTab1');" href="javascript:">{l s='More info'}</a></div>
		{if $features}		<div><a class="ebutton blue" id="idTab2" onclick="showtabs('idTab2');" href="javascript:">{l s='Data sheet'}</a></div>{/if}
		{if $attachments}	<div><a class="ebutton blue" id="idTab9" onclick="showtabs('idTab9');" href="javascript:">{l s='Инструкция'}</a></div>{/if}
							{$HOOK_PRODUCT_TAB} {* здесь добавится кнопка вкантакте *}
	</div>
</noindex>	
<!-- /таб кнопки -->

	

{*	<div id="more_info_sheets" class="sheets align_justify">*}


<!-- таб description №1 -->
			<div id="idTab1" class="rte description">
				{**if $product->id_manufacturer != 0}
					<div id="manufacturer_logo">
						<a href="{$base_dir}manufacturer.php?id_manufacturer={$product->id_manufacturer}">
						<img alt="Все товары производства {$product->manufacturer_name}" title="Все товары производства {$product->manufacturer_name}" src="{$base_dir}img/tmp/manufacturer_{$product->id_manufacturer}.jpg">
						</a>
					</div>
				{/if**}
			
				{**if $product->id_supplier != 0 && $product->id_supplier != 12}
				<div id="supplier_logo">
					<a href="{$base_dir}supplier.php?id_supplier={$product->id_supplier}">
						<img title="Все товары для {$product->supplier_name}" alt="Все товары для {$product->supplier_name}" src="{$base_dir}img/tmp/supplier_{$product->id_supplier}.jpg">
					</a>
				</div>
				{/if**}

				{if $product->description_short}
					<p>{$product->description_short}</p>
				{/if}
			
				{if $category_desc}
				<noindex>
				<!--div style="position:relative; float: left; margin-right:5px"><img style=" height:30px;" src="../themes/Earth/img/icon/best-sales.png"></div-->
				<div class="product_cat_desc">{$category_desc}</div>
				</noindex>
			{/if}

			{if $product_manufacturer->name}
				<p>Производство: <a title="Все товары производства {$product->manufacturer_name}" alt="Все товары производства {$product->manufacturer_name}" href="{$base_dir}manufacturer.php?id_manufacturer={$product->id_manufacturer}">{$product_manufacturer->name}</a>
				</p>
			{/if}
			{if $product->description}<p>{$product->description}</p>{/if}
		</div>	
<!-- /таб description №1 -->




<!-- таб features №2 - не используется -->
	{if $features}
		<div id="idTab2" class="rte $features">
			{foreach from=$features item=feature}
				<span>{$feature.name|escape:'htmlall':'UTF-8'}</span> {$feature.value|escape:'htmlall':'UTF-8'}
			{/foreach}
		</div>
	{/if}
<!-- /таб features №2 - не используется -->
	
<!-- таб attachment №9 - инструкции -->
	{if $attachments}
		<div id="idTab9" class="rte attachment">
			{foreach from=$attachments item=attachment}
				<p align="center">
				<a style="border:0;" href="{$base_dir}attachment.php?id_attachment={$attachment.id_attachment}">
				<img src="{$img_dir}/icon/download.png"><br>
				{$attachment.name|escape:'htmlall':'UTF-8'}<br />{$attachment.description|escape:'htmlall':'UTF-8'}</a>
				</p>
			{/foreach}
		</div>
	{/if}
<!-- /таб attachment №9 - инструкции -->		


<!-- таб attachment №6 - вконтакте -->
	{$HOOK_PRODUCT_TAB_CONTENT}{* здесь добавится див вкантакте *}
<!-- /таб attachment №6 - вконтакте -->
	
	</div>
</div>
{/if}

<!-- Customizable products -->
{if $product->customizable}
	<ul class="idTabs">
		<li><a style="cursor: pointer">{l s='Product customization'}</a></li>
	</ul>
	<div class="customization_block">
		<form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm">
			<p>
				<img src="{$img_dir}icon/infos.gif" alt="Informations" />
				{l s='After saving your customized product, do not forget to add it to your cart.'}
				{if $product->uploadable_files}<br />{l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
			</p>
			{if $product->uploadable_files|intval}
			<h2>{l s='Pictures'}</h2>
			<ul id="uploadable_files">
				{counter start=0 assign='customizationField'}
				{foreach from=$customizationFields item='field' name='customizationFields'}
					{if $field.type == 0}
						<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
							{if isset($pictures.$key)}<div class="customizationUploadBrowse"><img src="{$pic_dir}{$pictures.$key}_small" alt="" /><a href="{$link->getUrlWith('deletePicture', $field.id_customization_field)}"><img src="{$img_dir}icon/delete.gif" alt="{l s='delete'}" class="customization_delete_icon" /></a></div>{/if}
							<div class="customizationUploadBrowse"><input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="customization_block_input {if isset($pictures.$key)}filled{/if}" />{if $field.required}<sup>*</sup>{/if}
							<div class="customizationUploadBrowseDescription">{if !empty($field.name)}{$field.name}{else}{l s='Please select an image file from your hard drive'}{/if}</div></div>
						</li>
						{counter}
					{/if}
				{/foreach}
			</ul>
			{/if}
			<div class="clear"></div>
			{if $product->text_fields|intval}
			<h2>{l s='Texts'}</h2>
			<ul id="text_fields">
				{counter start=0 assign='customizationField'}
				{foreach from=$customizationFields item='field' name='customizationFields'}
					{if $field.type == 1}
						<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
							{if !empty($field.name)}{$field.name}{/if}<input type="text" name="textField{$field.id_customization_field}" id="textField{$customizationField}" value="{if isset($textFields.$key)}{$textFields.$key|stripslashes}{/if}" class="customization_block_input" />{if $field.required}<sup>*</sup>{/if}
						</li>
						{counter}
					{/if}
				{/foreach}
			</ul>
			{/if}
			<p align="center" style="clear: left;" id="customizedDatas">
				<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
				<input type="hidden" name="submitCustomizedDatas" value="1" />
				<input type="button" class="ebutton green" value="{l s='Save'}" onclick="javascript:saveCustomization()" />
			</p>
		</form>
		<p class="clear required"><sup>*</sup> {l s='required fields'}</p>
	</div>
{/if}

			{if $packItems|@count > 0}
			<noindex>
					<div id="more_info_tabs" class="idTabs idTabsShort">
					<div><a class="ebutton blue inactive" id="more_info_tab_more_info">{l s='Pack content'}</a></div>
					</div>
				<div id="packitems">
					{include file=$tpl_dir./product-list.tpl products=$packItems}
					</div>
			</noindex>
			{/if}
{/if}

{$HOOK_PRODUCT_FOOTER} 

		{* вставляем аксессуары под описание *}
		<!-- accessories -->
		<noindex>
		<div style="opacity: 0; transition: opacity 0.3s ease; " class="nonprintable" id="accessories_container" style="margin-right: -7px;">
			<h2>{l s='accessories'}</h2>
	    	<div class="accessories" id="accessories">
                 {* аксессуары пихать аяксом сюда *}				
		    </div>
		</div>
		</noindex>
		<!-- /accessories -->

{literal}
<!-- Yandex.Metrika counter --><script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459353 = new Ya.Metrika({ id:24459353, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/24459353" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
{/literal}

{* отскроллимся на карточку товара после загрузки страницы *}
{if $smarty.const.site_version == "full"}
	{literal}						
	<script async type="text/javascript">
				$('body,html').animate({
					scrollTop: 340
				}, 10);
				setTimeout("document.getElementById('product_raise').style.backgroundColor = '#fff'", 1500);
	</script>		
	{/literal}						
{/if}						


{* подгрузим аксессуары аяксом *}
{literal}		
<script>
$(document).ready(
function ajaxx(){
{
   xhttp=new XMLHttpRequest();
   xhttp.onreadystatechange=function()
   {
    if (xhttp.readyState==4 && xhttp.status==200)
    {
        document.getElementById('accessories').innerHTML += xhttp.responseText;
        document.getElementById('accessories_container').style.opacity = 1;
    }
   }
   xhttp.open('GET','product-accessories.php?id_product='+id_product, true);
   xhttp.send();
  };
}
);
</script>


{/literal}

{* swiper перенесен в footer.tpl чтобы работать с wishlist *}

