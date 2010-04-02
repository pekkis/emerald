$(document).ready(function() {
	
	$(".locale-delete").jsonClick({
		success: function(msg, evt) { $(evt.currentTarget).parents('tr').remove(); }
	});
		
	$('form').jsonSubmit({
		success: function() { top.location.reload(); }
	});
	
	
	
	
	
	
});


