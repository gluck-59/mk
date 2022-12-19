$(document).ready(function()
{
	updateAddressesDisplay(true);
});

//update the display of the addresses
function updateAddressesDisplay(first_view)
{
	// update content of delivery address
	updateAddressDisplay('delivery');

	// update content of invoice address
	//if addresses have to be equals...
	var txtInvoiceTitle = $('fieldset#address_invoice p.address_title').html();	
	if ($('input[type=checkbox]#addressesAreEquals:checked').length == 1)
	{
		$('#address_invoice_form:visible').hide('slow');
		$('fieldset#address_invoice').html($('fieldset#address_delivery').html());
		$('fieldset#address_invoice p.address_title').html(txtInvoiceTitle);
	}
	else
	{
		$('#address_invoice_form:hidden').show('slow');
		if ($('select#id_address_invoice').val())
			updateAddressDisplay('invoice');
		else
		{
			$('fieldset#address_invoice').html($('fieldset#address_delivery').html());
			$('fieldset#address_invoice p.address_title').html(txtInvoiceTitle);
		}	
	}
	
	if(!first_view)
		updateAddresses();
		
	return true;
}

function updateAddressDisplay(addressType)
{
	var idAddress = $('select#id_address_' + addressType + '').val();
	$('fieldset#address_' + addressType + ' p.address_company').html('Паспорт '+addresses[idAddress][0]);
$('fieldset#address_' + addressType + ' legend').html(addresses[idAddress][10]);
$('fieldset#address_' + addressType + ' p.other').html(addresses[idAddress][11]);
$('fieldset#address_' + addressType + ' p.maps').html(addresses[idAddress][7] + ' ' + addresses[idAddress][6] + ' ' + addresses[idAddress][3]);

initialize();

	if(addresses[idAddress][0] == '')
		$('fieldset#address_' + addressType + ' p.address_company').hide();
	else
		$('fieldset#address_' + addressType + ' p.address_company').show();
	$('fieldset#address_' + addressType + ' p.address_name').html(addresses[idAddress][1] + ' ' + addresses[idAddress][4] + ' ' + addresses[idAddress][2]);
	$('fieldset#address_' + addressType + ' p.address_address1').html(addresses[idAddress][3]);
$('fieldset#address_' + addressType + ' p.address_phone_mobile').html(addresses[idAddress][9]);

if (addresses[idAddress][12].length == 0) 
{
    $('fieldset#address_' + addressType + ' p.address_inn').html('<span style="background-color: red;color: white;padding: 4px;">Пожалуйста допиши ИНН</span>');
    toastr.error('Нажми кнопку Изменить и укажи ИНН получателя этой посылки.', 'Пожалуйста допиши ИНН');
}
else 
{
    $('fieldset#address_' + addressType + ' p.address_inn').html('ИНН ' + addresses[idAddress][12]);
    toastr.clear();
}



	if(addresses[idAddress][4] == '')
		$('fieldset#address_' + addressType + ' p.address_address2').hide();
	else
		$('fieldset#address_' + addressType + ' p.address_address2').show();
	$('fieldset#address_' + addressType + ' p.address_city').html(addresses[idAddress][6]);
	$('fieldset#address_' + addressType + ' p.address_country').html(addresses[idAddress][5] + ' ' + addresses[idAddress][7] + (addresses[idAddress][8] != '' ? ' (' + addresses[idAddress][8] +')' : ''));
	// change update link
	var link = $('fieldset#address_' + addressType + ' div.buttons a').attr('href');
	var expression = /id_address=\d+/;
	link = link.replace(expression, 'id_address='+idAddress);
	$('fieldset#address_' + addressType + ' div.buttons a').attr('href', link);
}

function updateAddresses()
{
	var idAddress_delivery = $('select#id_address_delivery').val();
	var idAddress_invoice = $('input[type=checkbox]#addressesAreEquals:checked').length == 1 ? idAddress_delivery : $('select#id_address_invoice').val();
   
   $.ajax({
           type: 'POST',
           url: baseDir + 'order.php',
           async: true,
           cache: false,
           dataType : "json",
           data: 'processAddress=true&step=2&ajax=true&id_address_delivery=' + idAddress_delivery + '&id_address_invoice=' + idAddress_invoice+ '&token=' + static_token ,
           success: function(jsonData)
           {
           		if (jsonData.hasError)
				{
					var errors = '';
					for(error in jsonData.errors)
						//IE6 bug fix
						if(error != 'indexOf')
							errors += jsonData.errors[error] + "\n";
					alert(errors);
				}
			},
           error: function(XMLHttpRequest, textStatus, errorThrown) {alert("TECHNICAL ERROR: unable to save adresses \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);}
       });
}