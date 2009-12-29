$(document).ready(function() {
		
	$('#tabs').tabs();

	
	$('.user-delete, .group-delete').jsonClick({
		success: function(elm) { elm.parent().remove(); }			
	});
	
	
});
