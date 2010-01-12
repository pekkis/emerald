$(document).ready(function() {
	
	$(".form-delete").jsonClick({
		success: function(elm) { elm.parents('tr').remove(); }
	});
	
	$("form").jsonSubmit({
		success: function(msg) { location = '/admin/form/edit/id/' + msg.form_id; }		
	});
	
	
});


