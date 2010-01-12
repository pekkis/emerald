
$(document).ready(function() {
	
	$('#form-sort').sortable({ items: '.form-sortable' });

	$('form#field-edit').jsonSubmit({
		success: function(msg) { Emerald.message(msg.message); }
	});
		
	
	$('form#field-create').jsonSubmit({
		success: function() { location.reload(); } 
	});

	
	$('.field-delete').jsonClick({
		success: function(elm) { elm.parents('.form-sortable').remove(); }
	});
	
				
				
});
