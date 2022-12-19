//quand vous dupliquez le module, faites un rechercher remplacer de blockAdvanceSearch_X dans ce fichier (X étant le numéro de duplication)
var blockAdvanceSearch_3 = {
timerAdvcShow: 	new Array(),
advc_href_temp : false,
isSearchPage : false,
dupliq_id : 3,
submitMode :false,
advcViewMoreToogle : function(e,showMsg,hideMsg) {
	if($(e).parent('span').parent('div').children('ul').children('li.advcHideCrit').css('display') == 'none') {
		blockAdvanceSearch_3.advcShowMore($(e).parent('span').parent('div').children('ul'),e,hideMsg);
	}
	else {
		blockAdvanceSearch_3.advcHideMore($(e).parent('span').parent('div').children('ul'),e,showMsg);
	}
},
advcShowMore : function(e,link,hideMsg) {
	$(e).parent('.advc_crits').addClass('advc_crits_show');
	$(e).children('li.advcHideCrit').show('fast',function() {
		if(hideMsg)
			$(link).html(hideMsg);
		else
			$(link).hide();
	});
},
advcHideMore : function(e,link,showMsg) {
	$(e).parent('.advc_crits').removeClass('advc_crits_show');
	$(e).children('li.advcHideCrit').hide('fast',function() {
		if(showMsg)
			$(link).html(showMsg);
		else
			$(link).show();
	});
},
resetCart : function() {
	if(typeof(ajaxCart) != 'undefined') {
		ajaxCart.overrideButtonsInThePage();
	}
	else if(typeof(modalAjaxCart) != 'undefined') {
		modalAjaxCart.overrideButtonsInThePage();
	}
},
advcLoadAdvc : function(url) {
	blockAdvanceSearch_3.advcBlocLoader();

	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content').load(url, {advc_ajax_mode: 1,advc_get_block: 1,dupliq_id:blockAdvanceSearch_3.dupliq_id},function(response, status, xhr) {
		if(!$(this).html()) $(this).html(response);
		//$('.advc_loader').fadeOut('fast');
		$('.advc_loader').fadeTo( 500, 0);
		blockAdvanceSearch_3.advcAjaxMode();
	});
},
advcLoadContent : function(url) {
	$('.advcHideCrit').hide();
	blockAdvanceSearch_3.advcContentLoader();
	$('.advc_loader').fadeTo( 500, 0.8);

	$('#center_column').load(url, {advc_ajax_mode: 1},function(response, status, xhr) {
		if(!$(this).html()) $(this).html(response);
		blockAdvanceSearch_3.resetCart();
		//$('.advc_loader').fadeOut('fast');
		$('.advc_loader').fadeTo( 500, 0);
		blockAdvanceSearch_3.advcAjaxMode();
	});
},
advcLoadUrl : function(url)
{
	$('.advcHideCrit').hide();
	blockAdvanceSearch_3.advcContentLoader();
	blockAdvanceSearch_3.advcBlocLoader();
	$('.advc_loader').fadeTo( 500, 0.8);

	$('#center_column').load(url, {advc_ajax_mode: 1},function(response, status, xhr) {
		blockAdvanceSearch_3.resetCart();
		if(!$(this).html()) $(this).html(response);
		$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content').load(url, {advc_ajax_mode: 1,advc_get_block: 1,dupliq_id:blockAdvanceSearch_3.dupliq_id},function(response, status, xhr) {
			if(!$(this).html()) $(this).html(response);
			$('.advc_loader').fadeOut('fast');
			//$('.advc_loader').fadeTo( 500, 0);
			blockAdvanceSearch_3.advcAjaxMode();
		});
	});
},
advcContentLoader : function() {
	var contentHeight = $('#center_column').innerHeight( );
	if ( ! $('#center_column .advc_loader').length )
		$('#center_column').append('<div class="advc_loader"><span class="advc_loader_msg">'+ADVC_WaitMsg_3+'</span></div>');
	$('#center_column .advc_loader').css('height',contentHeight+'px');
},
advcBlocLoader : function() {
	var blocHeight = $('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content').innerHeight( );
	if ( ! $('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content .advc_loader').length )
		$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content').append('<div class="advc_loader"></div>');
	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content .advc_loader').css('height',blocHeight+'px');
},
selectionAjaxMode : function()
{

	$('#advcCurSelection_'+blockAdvanceSearch_3.dupliq_id+' a[href!="javascript:void(0)"]').unbind('click').bind('click', function() {
		var advc_href = $(this).attr('href');
		blockAdvanceSearch_3.advcLoadUrl(advc_href);
		return false;
	});
},

advcAjaxMode : function(start)
{

	if(blockAdvanceSearch_3.isSearchPage) {
		$('#productsSortForm select').attr('onchange','return false;');
		$('.pagination a').unbind('click').bind('click', function() {
			var advc_href = $(this).attr('href');
			blockAdvanceSearch_3.advcLoadContent(advc_href);
			return false;
		});
		$('#productsSortForm select').unbind('change').bind('change', function() {
			var advc_href = $(this).val();
			blockAdvanceSearch_3.advcLoadContent(advc_href);
			return false;
		});
	}
	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' a[href!="javascript:void(0)"]').unbind('click').bind('click', function() {
		var advc_href = $(this).attr('href');
		if(blockAdvanceSearch_3.submitMode == 1)
			blockAdvanceSearch_3.advcLoadUrl(advc_href);
		else { blockAdvanceSearch_3.advcLoadAdvc(advc_href);$('.advc_loader').fadeTo( 500, 0.8);}
		return false;
	});
	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' input[type=radio], #AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' input[type=checkbox]').unbind('click').bind('click', function() {
		if(blockAdvanceSearch_3.submitMode == 1) {
			var advc_href = $(this).val();
			if(advc_href)
				blockAdvanceSearch_3.advcLoadUrl(advc_href);
		}else {
			$('#AdvancedSearchForm_'+blockAdvanceSearch_3.dupliq_id+'').ajaxSubmit(blockAdvanceSearch_3.submitOptionsBloc);
		}
		return false;
	});
	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' select').unbind('change').bind('change', function() {

		if(blockAdvanceSearch_3.submitMode == 1) {
			var advc_href = $(this).val();
			if(advc_href)
				blockAdvanceSearch_3.advcLoadUrl(advc_href);
		}else {
			$('#AdvancedSearchForm_'+blockAdvanceSearch_3.dupliq_id+'').ajaxSubmit(blockAdvanceSearch_3.submitOptionsBloc);
		}
		return false;
	});
	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .AdvcSubmitSearch').unbind('click').bind('click', function() {
		var advc_parent_form = $(this).parent('form');
		var advc_href = advc_parent_form.attr('action');
		var options = {
		    target:        '#center_column',   // target element(s) to be updated with server response
		    beforeSubmit:  blockAdvanceSearch_3.showRequestAdvcSearch,  // pre-submit callback
		    success:       blockAdvanceSearch_3.showResponseAdvcSearch,  // post-submit callback
			type:      'get',
			url:       advc_href+'?advc_ajax_mode=1&dupliq_id='+blockAdvanceSearch_3.dupliq_id
		};
		advc_parent_form.ajaxSubmit(options);
	});

	if(typeof(start) != 'undefined') {
		blockAdvanceSearch_3.advcLoadAdvc(document.URL);
	}
},
showRequestAdvcSearch : function(formData, jqForm, options) {
    var queryString = $.param(formData);

    // jqForm is a jQuery object encapsulating the form element.  To access the
    // DOM element for the form do this:
    var formElement = jqForm[0];
    var advc_href = formElement.action+'?'+queryString;
   // alert(formElement.action);
    //alert('About to submit: \n\n' + queryString);
    $('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content').load(advc_href, {advc_ajax_mode: 1,advc_get_block: 1,dupliq_id:blockAdvanceSearch_3.dupliq_id},function() {
		blockAdvanceSearch_3.advcAjaxMode();
	});
    // here we could return false to prevent the form from being submitted;
    // returning anything other than false will allow the form submit to continue
    return true;
},

// post-submit callback
showResponseAdvcSearch : function(responseText, statusText)  {
	if(typeof(ajaxCart) != 'undefined') {
		ajaxCart.overrideButtonsInThePage();
	}
	else if(typeof(modalAjaxCart) != 'undefined') {
		modalAjaxCart.overrideButtonsInThePage();
	}
   return true;
},

showRequestAdvcSearchSubmit : function(formData, jqForm, options) {
    var queryString = $.param(formData);
    var formElement = jqForm[0];
    blockAdvanceSearch_3.advc_href_temp = formElement.action+'?'+queryString;
    blockAdvanceSearch_3.advcContentLoader();
	blockAdvanceSearch_3.advcBlocLoader();
	$('.advc_loader').fadeTo( 500, 0.8);

    // here we could return false to prevent the form from being submitted;
    // returning anything other than false will allow the form submit to continue
    return true;
},

// post-submit callback
showResponseAdvcSearchSubmit : function(responseText, statusText)  {
	blockAdvanceSearch_3.resetCart();

	 $('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content').load(blockAdvanceSearch_3.advc_href_temp, {advc_ajax_mode: 1,advc_get_block: 1,dupliq_id:blockAdvanceSearch_3.dupliq_id},function() {
		 blockAdvanceSearch_3.advcAjaxMode();
		});
   return true;
},
showRequestAdvcBloc : function(formData, jqForm, options) {
    blockAdvanceSearch_3.advcBlocLoader();
    $('.advc_loader').fadeTo( 500, 0.8);
    return true;
},
showResponseAdvcBloc : function(responseText, statusText)  {
	  blockAdvanceSearch_3.advcAjaxMode();
	  return true;
},

advcViewMoreToogleMouseOut : function() {

	$('#AdvancedSearchBloc_'+blockAdvanceSearch_3.dupliq_id+' .block_content ul:has(li.advcHideCrit)').each(function(i) {
		if(typeof(blockAdvanceSearch_3.timerAdvcShow[i]) == 'undefined') {
			blockAdvanceSearch_3.timerAdvcShow[i] = false;
		}
		$(this).hover(
			function() {
				if(blockAdvanceSearch_3.timerAdvcShow[i] != false) clearTimeout(blockAdvanceSearch_3.timerAdvcShow[i]);
				blockAdvanceSearch_3.advcShowMore($(this),$(this).parent('div').children('span.advcViewMoreToogle').children('a'),false);
			},function () {
				var e = $(this);
				var link = $(this).parent('div').children('span.advcViewMoreToogle').children('a');
				blockAdvanceSearch_3.timerAdvcShow[i] = setTimeout(function() {
					blockAdvanceSearch_3.advcHideMore(e,link,false);
				},500);
			});
	});
}
}