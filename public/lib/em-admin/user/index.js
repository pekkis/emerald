$(document).ready(function() {
		
	$('#tabs').tabs();

	
	$('.user-delete, .group-delete').jsonClick({
		success: function(msg, evt) { $(evt.currentTarget).parent().remove(); }			
	});
	
	
});
