$(document).ready(function() {
			
	$("form").jsonSubmit({
	
		success: function() { top.document.location.reload(); }
		
	});	
	
});




