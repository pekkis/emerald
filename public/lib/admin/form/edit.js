
$(document).ready(function() {
	
	$('#form-sort').sortable({ items: '.form-sortable' });

	$('form#field-edit').jsonSubmit({
		
	});
		
	
	$('form#field-create').jsonSubmit({
		success: function() { location.reload(); } 
	});

	
	$('.field-delete').jsonClick({
		success: function(msg, evt) { $(evt.currentTarget).parents('.form-sortable').remove(); }
	});
	
				
				
});
