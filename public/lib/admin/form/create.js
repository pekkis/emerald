$(document).ready(function() {
	
	$(".form-delete").jsonClick({
		success: function(msg, evt) { $(evt.currentTarget).parents('tr').remove(); }
	});
	
	$("form").jsonSubmit({
		success: function(msg, evt) { location = Emerald.baseUrl + '/admin/form/edit/id/' + msg.form_id; }		
	});
	
	
});


