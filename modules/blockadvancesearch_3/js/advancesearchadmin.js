var queue = false;
var next = false;
function show_info(id,content) {
	//alert(content);
	if(queue){next = new Array(id,content);return;}
	queue = true;
	if($('#'+id).is("div") === false) 
		$('body').append('<div id="'+id+'" class="info_screen ui-state-hover"></div>');
	else return
	$('#'+id).html(content);
	$('#'+id).slideDown('slow');

	setTimeout(function() { $('#'+id).slideUp('slow',function() {$('#'+id).remove();queue = false;if(next){show_info(next[0],next[1]);next = false;} }) },2000);
}

var oldorder;
function disablefromsearch(e) {
	if(!$(e).attr("checked")) {
	 $(e).parent("td").parent("tr").find("td").children("input select").attr("disabled","disabled");
	 $(e).removeAttr("disabled");
	}
	else {
		$(e).parent("td").parent("tr").find("td").children("input select").removeAttr("disabled")
	}					 
	setorder(document.getElementById("tableadvancesearch"), "id");
}
function setMaxVisible(e) {
	$.get(location.href, { "maxvisible-id": $(e).attr("name"), maxvisible: $(e).val() },function() {
		show_info('setMaxVisible','Sauvegardé');
	});
}
function setRange(e) {
	$.get(location.href, { "range-id": $(e).attr("name"), range: $(e).val() },function() {
		show_info('setRange','Sauvegardé');
	});
}
function setLabelRange(e) {
	$.get(location.href, { "labelrange-id": $(e).attr("name"), labelrange: $(e).val() },function() {
		show_info('setLabelRange','Sauvegardé');
	});
}
function disablefromsearchTab(e) {
	$.get(location.href, { "searchtab-id": $(e).attr("name"), setactifortab: $(e).attr("checked") },function() {
		show_info('disablefromsearchTab','Sauvegardé');
	});
}
function setWidthHookTop(e) {
	$.get(location.href, { "widthhooktop-id": $(e).attr("name"), width: $(e).val() },function() {
		show_info('setWidthHookTop','Sauvegardé');
	});
}
function setSearchType(e,rangeable) {
	var search_type= $(e).children("option:selected").val();
	var hidable = critStyles[search_type]["hidable"];
	var multicritere = critStyles[search_type]["multicritere"];
	var rangeable = (rangeable?critStyles[search_type]["rangeable"]:false);
	
	if(hidable) {
		$(e).parent("td").parent("tr").find("td#check_max_visible").children("input").removeAttr("disabled");
	}
	else {
		$(e).parent("td").parent("tr").find("td#check_max_visible").children("input").attr("disabled","disabled");
	}
	
	if(multicritere) {
		$(e).parent("td").parent("tr").find("td#check_multi").children("input").removeAttr("disabled");
	}
	else {
		$(e).parent("td").parent("tr").find("td#check_multi").children("input").attr("disabled","disabled");
	}
	if(rangeable) {
		$(e).parent("td").parent("tr").find("td#check_range").children("input").removeAttr("disabled");
		$(e).parent("td").parent("tr").find("td#check_label_range").children("input").removeAttr("disabled");
	}
	else {
		$(e).parent("td").parent("tr").find("td#check_range").children("input").attr("disabled","disabled");
		$(e).parent("td").parent("tr").find("td#check_label_range").children("input").attr("disabled","disabled");
	}
	$.get(location.href, { "search-id": $(e).attr("name"), "search-type": search_type} ,function() {
		show_info('setSearchType','Sauvegardé');
	});
}

function setMulticriteres(e) {
 $.get(location.href, { "multicritere-id": $(e).attr("name"), setmulticritere: $(e).attr("checked") } ,function() {
		show_info('setMulticriteres','Sauvegardé');
	});
}
 function setorder(table, row) {
 		var rows = table.tBodies[0].rows;
var order = objectToarray(rows,'id').join(',');
if(oldorder != order) {
		oldorder = order;
		saveorder();
}
 }
 function saveorder() {
	$.get(location.href, { position: oldorder },function() {
		show_info('saveorder','Sauvegardé');
	});
}
$(document).ready(function() {
// Initialise the second table specifying a dragClass and an onDrop function that will display an alert
$('.table_sort').tableDnD({
		
    onDragClass: 'myDragClass',
    onDrop: function(table, row) {
        setorder(table, row);
    },
	onDragStart: function(table, row) {
		 	var rows = table.tBodies[0].rows;
			oldorder = objectToarray(rows,'id').join(',');
	}
});
});
function objectToarray (o,e) {
	a = new Array;
	for (var i=1; i<o.length; i++) {
			var name = "ADVC_actif_"+$(o[i]).attr("id");
			
			var ischecked = $("input[@id="+name+"][@checked]").length;
			if(ischecked)
				a.push(o[i][e]);
	}
	return a;
}