$(document).ready(function() {
	
	$(".form-delete").jsonClick({
		success: function(elm) { elm.parents('tr').remove(); }
	});
	
	$("form").jsonSubmit({
		success: function(elm) { location.reload(); }		
	});
	
	
	
});


