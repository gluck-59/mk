{* прежде всего насильно переключим валюту в рубли *}
{if $cart->id_currency !=3} 
	{literal}
	<script defer language="JavaScript">
	setCurrency(3);
	</script>
	{/literal}
{/if}


<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
	var addresses = new Array();
	{foreach from=$addresses key=k item=address}
		addresses[{$address.id_address|intval}] = new Array('{$address.company|addslashes}', '{$address.firstname|addslashes}', '{$address.lastname|addslashes}', '{$address.address1|addslashes}', '{$address.address2|addslashes}', '{$address.postcode|addslashes}', '{$address.city|addslashes}', '{$address.country|addslashes}', '{$address.state|default:''|addslashes}', '{$address.phone_mobile|addslashes}', '{$address.alias|addslashes}', '{$address.other|addslashes}', '{$address.phone|addslashes}');
	{/foreach}
-->
</script>
<script type="text/javascript" src="{$js_dir}order-address.js"></script>

{capture name=path}{l s='Addresses'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{assign var='current_step' value='address'}
{include file=$tpl_dir./order-steps.tpl}

<h2>{l s='Addresses'}</h2>

{include file=$tpl_dir./errors.tpl}

<form action="{$base_dir_ssl}order.php" method="post">
	<div class="addresses">
		<p class="address_delivery select">
			<label for="id_address_delivery" style="margin-right: 7px;" >{l s='Choose a delivery address:'}</label>
			<select name="id_address_delivery" id="id_address_delivery" class="address_select" onchange="updateAddressesDisplay();">
			{foreach from=$addresses key=k item=address}
				<option value="{$address.id_address|intval}" {if $address.id_address == $cart->id_address_delivery}selected="selected"{/if}>{$address.alias|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
			</select>


{*foreach from=$addresses key=k item=address}
<label for="{$address.alias|escape:'htmlall':'UTF-8'}">{$address.alias|escape:'htmlall':'UTF-8'}</label>
<input type="radio" value="{$address.id_address|intval}" {if $address.id_address == $cart->id_address_delivery}checked="selected"{/if}>

{/foreach*}
			

			
		</p>
		<p class="hidden">
			<input type="checkbox" name="same" id="addressesAreEquals" value="1" onclick="updateAddressesDisplay();" {if $cart->id_address_invoice == $cart->id_address_delivery || $addresses|@count == 1}checked="checked"{/if} />
			<label for="addressesAreEquals">{l s='Use the same address for billing.'}</label>
		</p>
		<p id="address_invoice_form" class="select" {if $cart->id_address_invoice == $cart->id_address_delivery}style="display: none;"{/if}>
		{if $addresses|@count > 1}
			<label for="id_address_invoice" class="strong">{l s='Choose a billing address:'}</label>
			<select name="id_address_invoice" id="id_address_invoice" class="address_select" onchange="updateAddressesDisplay();">
			{section loop=$addresses step=-1 name=address}
				<option value="{$addresses[address].id_address|intval}" {if $addresses[address].id_address == $cart->id_address_invoice && $cart->id_address_delivery != $cart->id_address_invoice}selected="selected"{/if}>{$addresses[address].alias|escape:'htmlall':'UTF-8'}</option>
			{/section}
			</select>
			{else}
				<a style="margin-left: 221px;" href="{$base_dir_ssl}address.php?back=order.php&amp;step=1&select_address=1" title="{l s='Add'}" class="button_large">{l s='Add a new address'}</a>
			{/if}
		</p>
		
		<div class="addresses"></div>
		
		<fieldset class="address item" id="address_delivery">
			<legend id="address_title" class="address_title"></legend>
			{*<div id="map_canvas" class="map_canvas"></div>*}
			{*<p class="address_title">{l s='Посылка поедет сюда:'}</p>*}
			<p class="address_name"></p>
			<p class="address_inn"><span style="background-color: red;color: white;padding: 4px;">Пожалуйста допиши ИНН</span></p>
			<p>&nbsp;</p>
			<p class="address_country"></p>
			<p class="address_city"></p>
			<p class="address_address1"></p>
			<p class="address_address2"></p> 			
			<p class="other"></p>
			<p class="address_phone_mobile"></p>
			<p class="address_company"></p>

			<p hidden id="maps" class="maps"></p>
			<div class="buttons">
				<a class="ebutton blue small" href="{$base_dir_ssl}address.php?id_address={$address.id_address|intval}&amp;back=order.php&amp;step=1" title="{l s='Редактировать адрес'}">{l s='Изменить'}</a>
			</div>
		</fieldset>
{*		<fieldset class="address alternate_item" id="address_invoice">
			<p class="address_title">{l s='Your billing address'}</p>
			<p class="address_name"></p>
			<p class="address_address2"></p>			
			<p class="address_address1"></p>
			<p class="address_city"></p>
			<p class="address_country"></p>
			<p class="address_phone_mobile"></p> 			
			<p class="address_company"></p> 
			<p class="address_update"><a href="{$base_dir_ssl}address.php?id_address={$address.id_address|intval}&amp;back=order.php&amp;step=1" title="{l s='Update'}">{l s='Update'}</a></p>
		</fieldset> *}
		<br class="clear" />
		{*<p class="address_add submit">
			<a class="" href="{$base_dir_ssl}address.php?back=order.php&amp;step=1" title="{l s='Add'}" style="text-align:center" >{l s='Add a new address'}</a>
		</p>*}
		<div id="ordermsg">
			<h2>{l s='If you want to leave us comment about your order, please write it below.'}</h2>
			<textarea style="height:80px;" rows="7" cols="35" name="message">{$oldMessage}</textarea>
		</div>
	</div>
	<p class="cart_navigation" align="center">
		<input type="hidden" class="hidden" name="step" value="2" />
		<input type="hidden" name="back" value="{$back}" />
		{*<a href="{$base_dir_ssl}order.php?step=0{if $back}&back={$back}{/if}" title="{l s='Previous'}" class="ebutton gray small">&laquo; {l s='Previous'}</a>*}
		<input type="submit" name="processAddress" value="{l s='Способ доставки'}" class="ebutton blue large" style="border-radius: 15px 0 0 15px;"/>
		<input type="submit" name="processAddress" value="{l s='Оплатить заказ'}" class="ebutton green large" style="border-radius: 0 15px 15px 0;" />
	</p>
</form>


{literal}
<!-- Yandex.Metrika order-address --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459479 = new Ya.Metrika({id:24459479, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459479" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika order-address -->{/literal}


<!-- gmaps -->
{literal}
    <script type="text/javascript">

    var map = null;
    var geocoder = null;

    function initialize() {
      if (1==2 && GBrowserIsCompatible()) {
        geocoder = new GClientGeocoder();
      }
    
    var address = document.getElementById('maps').innerHTML;
    var baloon = document.getElementById('address_title').innerHTML;

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


