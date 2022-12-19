<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='My addresses'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2 id="cabinet">{l s='My addresses'}</h2>

{if $addresses}
{*<p>{l s='Configure your billing and delivery addresses that will be preselected by default when you make an order. You can also add additional addresses, which can be useful for sending gifts or receiving your order at the office.'}</p>*}

<div class="addresses">
	{*
	<h3>{l s='Your addresses are listed below.'}</h3>
	<p>{l s='Be sure to update them if they have changed.'}</p>
	*}

	{foreach from=$addresses item=address name=myLoop}
		<fieldset class="address" id="{$address.id_address}">
			<legend class="address_title">{$address.alias}</legend>
			{*<div id="map_canvas_{$address.id_address}" class="map_canvas"></div>*}
			<p class="address_name">{$address.firstname} {$address.address2} {$address.lastname}</p>
			<p class="address_address1">{$address.address1}</p>
			<p class="address_country">{$address.postcode}, {$address.country}, {if isset($address.state)} ({$address.state}), {/if} {$address.city}</p>
			<p>&nbsp;</p>
			{if $address.phone}<p class="address_phone">ИНН: {$address.phone}</p>
            {else}<p class="address_additem">&bull; Добавь ИНН получателя посылки</p>			
            {/if}
			
			{if $address.other}<p class="address_other">{$address.other}</p>		
			{else}<p class="address_additem">&bull; Добавь марку-модель-год мотоцикла</p>			
			{/if}	

			{if $address.phone_mobile}<p class="address_phone_mobile">{$address.phone_mobile}</p>
			{else}<p class="address_additem">&bull; Добавь телефон для курьера</p>
			{/if}
			
			{if $address.company}<p class="address_passport">Паспорт {$address.company}</p>
			{else}<p class="address_additem">&bull; Добавь {if !$address.address2}отчество и{/if} паспортные данные для таможни (необязательно)</p>
			{/if}
	
			<div class="buttons">
			<a class="ebutton blue small" href="{$base_dir_ssl}address.php?id_address={$address.id_address|intval}" title="{l s='Update'}">{l s='Update'}</a>
			&nbsp;&nbsp;&nbsp;
			<a class="ebutton red small" href="{$base_dir_ssl}address.php?id_address={$address.id_address|intval}&amp;delete" onclick="return confirm('{l s='Удалить адрес'} {$address.address1}?');" title="{l s='Delete'}">{l s='Delete'}</a>
			</div>
		</fieldset>
		
		
<!-- gmaps -->
{literal}
<script type="text/javascript">
function gmaps(){
//var map = null;
//var geocoder = null;
var id = null;		

var address = '{/literal}{$address.country} {$address.city} {$address.address1}{literal}';
var baloon = '{/literal}{$address.alias}{literal}';
var id = '{/literal}{$address.id_address}{literal}';

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
//setTimeout(gmaps(), 1000);
    </script>
{/literal}
<!-- /gmaps -->
	{/foreach}
	



</div>

<p class="clear">&nbsp;</p>
	<p align="center" class="clear address_add"><a href="{$base_dir_ssl}address.php" title="{l s='Добавить адрес'}" class="ebutton orange large">{l s='Добавить новый адрес'}</a></p>
{else}
		<p class="success">{l s='No addresses available.'}&nbsp;{l s='add a new one!'}</p>
	<p align="center" class="clear address_add"><a href="{$base_dir_ssl}address.php" title="{l s='Добавить адрес'}" class="ebutton orange">{l s='Добавить адрес'}</a></p>
{/if}

<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">{l s='Back to Your Account'}</a></div></td>
    <td><div align="center"><a href="{$base_dir}">{l s='Home'}</a></div></td>
  </tr>
</table>

