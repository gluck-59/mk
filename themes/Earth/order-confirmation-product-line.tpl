<script src="http://maps.google.com/maps?file=api&amp;v=2.x"></script>
<div id="order-detail-content" class="table_block nonprintable">

    <h2>Содержимое кофра</h2>
    {* подгрузим "содержимое кофра" и детали по заказу *}
    {include file=$tpl_dir./product-list.tpl}
    
    <h2>Адрес доставки</h2>
	<fieldset class="address" id="{$id_address}" style="border-color: #ccc">
		<legend class="address_title" style="background-color: #ccc; color: #000">{$address->alias}</legend>
        <div id="map_canvas_{$address->id_address}" class="map_canvas"></div>
		<p class="address_name">{$address->firstname} {$address->address2} {$address->lastname}</p>
		<p class="address_address1">{$address->address1}</p>
		<p class="address_country">{$address->postcode}, {$address->country}, {if isset($address->state)} ({$address->state}), {/if} {$address->city}</p>
		<p>&nbsp;</p>
		{if $address->phone}<p class="address_phone">{$address->phone}</p>{/if}
		
		{if $address->other}<p class="address_other">{$address->other}</p>		
		{else}<p class="address_additem">&bull; Добавь марку-модель-год мотоцикла</p>			
		{/if}

		{if $address->phone_mobile}<p class="address_phone_mobile">{$address->phone_mobile}</p>
		{else}<p class="address_additem">&bull; Добавь телефон для курьера</p>
		{/if}
		
		{if $address->company}<p class="address_passport">Паспорт {$address->company}</p>
		{else}<p class="address_additem">&bull; Добавь {if !$address->address2}отчество и{/if} паспортные данные для таможни (необязательно)</p>
		{/if}

		<div class="buttons">
		    <a class="ebutton blue small" href="{$base_dir_ssl}address.php?id_address={$id_address|intval}" title="Изменить">Изменить</a>
		&nbsp;&nbsp;&nbsp;
			<!--a class="ebutton red small" href="{$base_dir_ssl}address.php?id_address={$id_address|intval}&amp;delete" onclick="return confirm('{l s='Удалить адрес'} {$id_address}?');" title="Удалить">Удалить</a-->
		</div>
	</fieldset>
</div>

<!-- gmaps -->
{literal}
<script type="text/javascript">
function gmaps(){
//var map = null;
//var geocoder = null;
var id = null;		

var address = '{/literal}{$address->country} {$address->city} {$address->address1}{literal}';
var baloon = '{/literal}{$address->alias}{literal}';
var id = '{/literal}{$address->id_address}{literal}';
    
if (GBrowserIsCompatible()) 
{
idg = new GClientGeocoder();

  if (idg) 
  {
    idg.getLatLng(address, function(point) 
    {
        if (!point) 
        {
          map = new GMap2(document.getElementById("map_canvas_"+id));

        } 
        else 
        {
          map = new GMap2(document.getElementById("map_canvas_"+id));
          map.setCenter(point, 15);
          var marker = new GMarker(point);
          map.addOverlay(marker);
          marker.openInfoWindowHtml(baloon);
        }
      }
    );
  }
}
}
setTimeout(gmaps(), 1000);
    </script>
{/literal}
<!-- /gmaps -->

