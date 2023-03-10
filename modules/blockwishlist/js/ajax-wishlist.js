/**
 * Update WishList Cart by adding, deleting, updating objects
 *
 * @return void
 */
function WishlistCart(id, action, id_product, id_product_attribute, quantity)
{
	$.ajax({
		type: 'GET',
		url:	baseDir + 'modules/blockwishlist/cart.php',
		async: true,
		cache: false,
		data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute,
		success: function(data)
		{
		
			$('#' + id).slideUp('normal');
			document.getElementById(id).innerHTML = data;
			$('#' + id).slideDown('normal');
			
		}
	});	

    if (action == 'add')
    {
        toastr.success('Ништяк сохранен в списке хотелок', 'Готово!');
    }

}

/**
 * Change customer default wishlist
 *
 * @return void
 */
function WishlistChangeDefault(id, id_wishlist)
{
	$.ajax({
		type: 'GET',
		url:	baseDir + 'modules/blockwishlist/cart.php',
		async: true,
		data: 'id_wishlist=' + id_wishlist + '&token=' + static_token,
		cache: false,
		success: function(data)
		{
			$('#' + id).slideUp('normal');
			document.getElementById(id).innerHTML = data;
			$('#' + id).slideDown('normal');
		}
	});
}

/**
 * Buy Product
 *
 * @return void
 */
function WishlistBuyProduct(token, id_product, id_product_attribute, id_quantity, button, ajax)
{
	if(ajax)
		ajaxCart.add(id_product, id_product_attribute, false, button, 1, [token, id_quantity]);
	else
	{

		WishlistAddProductCart(token, id_product, id_product_attribute, id_quantity)
		document.forms['addtocart' + '_' + id_product  + '_' + id_product_attribute].method='POST';
		document.forms['addtocart' + '_' + id_product  + '_' + id_product_attribute].action=baseDir + 'cart.php';
		document.forms['addtocart' + '_' + id_product  + '_' + id_product_attribute].elements['token'].value = static_token;
		document.forms['addtocart' + '_' + id_product  + '_' + id_product_attribute].submit();
	}
	return (true);
}

function WishlistAddProductCart(token, id_product, id_product_attribute, id_quantity)
{
	if ($('#' + id_quantity).val() <= 0)
		return (false);
	$.ajax({
		type: 'GET',
		url: baseDir + 'modules/blockwishlist/buywishlistproduct.php',
		data: 'token=' + token + '&static_token=' + static_token + '&id_product=' + id_product  + '&id_product_attribute=' + id_product_attribute,
		async: true,
		cache: false, 
		success: function(data)
		{
			if (data)
				alert(data);
			else
			{
				$('#' + id_quantity).val($('#' + id_quantity).val() - 1);
			}
		}
	});
	return (true);
}

/**
 * Show wishlist managment page
 *
 * @return void
 */
function WishlistManage(id, id_wishlist)
{
	$.ajax({
		type: 'GET',
		async: true,
		url: baseDir + 'modules/blockwishlist/managewishlist.php',
		data: 'id_wishlist=' + id_wishlist + '&refresh=' + false,
		cache: false,
		success: function(data)
		{
			$('#' + id).hide();
			document.getElementById(id).innerHTML = data;
			$('#' + id).fadeIn('slow');

	$('html, body').animate({ scrollTop: $('#block-order-detail').offset().top-50 }, 500);


		}
	});
}

/**
 * Show wishlist product managment page
 *
 * @return void
 */
function WishlistProductManage(id, action, id_wishlist, id_product, id_product_attribute, quantity, priority)
{
	$.ajax({
		type: 'GET',
		async: true,
		url: baseDir + 'modules/blockwishlist/managewishlist.php',
		data: 'action=' + action + '&id_wishlist=' + id_wishlist + '&id_product=' + id_product + '&id_product_attribute=' + id_product_attribute + '&quantity=' + quantity + '&priority=' + priority + '&refresh=' + true,
		cache: false,
		success: function(data)
		{
			if (action == 'delete')
				$('#wlp_' + id_product + '_' + id_product_attribute).fadeOut('fast');
			else if (action == 'update')
			{
				$('#wlp_' + id_product + '_' + id_product_attribute).fadeOut('fast');
				$('#wlp_' + id_product + '_' + id_product_attribute).fadeIn('fast');
			}
		}
	});

    if (action == 'delete')
    {
        toastr.success('Ништяк удален из списка хотелок', 'Готово!');
    }

}

/**
 * Delete wishlist
 *
 * @return boolean succeed
 */
function WishlistDelete(id, id_wishlist, msg)
{
	var res = confirm(msg);
	if (res == false)
		return (false);
	$.ajax({
		type: 'GET',
		async: true,
		url: baseDir + 'modules/blockwishlist/mywishlist.php',
		cache: false,
		data: 'deleted&id_wishlist=' + id_wishlist,
		success: function(data)
		{
			//$('#' + id).fadeOut('fast');
			$('#' + id).remove();
		}
	});
}

/**
 * Hide/Show bought product
 *
 * @return void
 */
function WishlistVisibility(bought_class, id_button)
{
	if ($('#hide' + id_button).css('display') == 'none')
	{
		$('.' + bought_class).slideDown('fast');
		$('#show' + id_button).hide();
		$('#hide' + id_button).fadeIn('fast');
	}
	else
	{
		$('.' + bought_class).slideUp('fast');
		$('#hide' + id_button).hide();
		$('#show' + id_button).fadeIn('fast');
	}
}

/**
 * Send wishlist by email
 *
 * @return void
 */
function WishlistSend(id, id_wishlist, id_email)
{
	$.post(baseDir + 'modules/blockwishlist/sendwishlist.php',
	{ token: static_token,
	  id_wishlist: id_wishlist,
	  email1: $('#' + id_email + '1').val()
	  },
	function(data)
	{
		if (data)
			alert(data);
		else
			WishlistVisibility(id, 'hideSendWishlist');
	});
}
