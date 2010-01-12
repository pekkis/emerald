$(document).ready(function() {

	
	$("ul > li > ul").sortable({ connectWith: "ul > li > ul" });


	
	$(".delete").jsonClick({
		success: function(elm) { elm.parent().remove(); }
	});

	
	
	$(".label-editable").dblclick(function() {
		
		$input = $('<input type="text" />');
		$(this).after($input);
		
		
	});
	
		
	
});
