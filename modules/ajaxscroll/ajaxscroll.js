var thisPageNum = 1;
var thisWork = 1;
var id_category=0;
var orderby=0;
var orderway=0;

function getNextP(){
    //global id_category, orderby, orderway, thisPageNum;
	if(thisWork == 1)
	{
		thisWork = 0;
		$("#ajaxscroll_ajax_loader").css('display','block');
		$.get("modules/ajaxscroll/ajax.php?id_category="+id_category+"&orderby="+orderby+"&orderway="+orderway+"&p="+thisPageNum+"&n=1", 
	
		function(data)
		{
    		$("#ajaxscroll_ajax_loader").css('display','none');
//setTimeout(function(){$("div#out").css("opacity", "1");}, 1000);
$("div#out").css("opacity", "1");
$("div#out").css("top", "0px");
    		
    		$('#product_list').append(data);
    		thisPageNum = thisPageNum + 1;
    		if(data)
    			thisWork = 1;
		});
	}
}

$(document).ready(function(){
	var scrH = $(window).height();
	var scrHP = $("#product_list").height();
	$("#pagination").css('display','none');
	$(".select").css('display','none');	
	$('#product_list').prepend('<div id="ajaxscroll_ajax_loader"><img src="'+baseDir+'img/loader.gif" alt="" /></div>');	
//	id_category=$("#pagination input[name=id_category]").val();
	id_category=$("input[name=id_category]").val();

	orderby=$("input[name=orderby]").val();
	orderway=$("input[name=orderway]").val();

	$(window).scroll(function(){
		var scro = $(this).scrollTop();
		var scrHP = $("#product_list").height();
		var scrH2 = 0;
		scrH2 = scrH + scro;
		var leftH = scrHP - scrH2;
		if(leftH < 300)
		{
			getNextP();
        }			
	});
});



