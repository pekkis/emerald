$(document).ready(function() {
		
	$('#tabs').tabs({ 
		show: function(event, ui) {
			if(ui.index == 1) {
				$("#accordion").accordion();
			}
		}
	});
	
	
	
		
	$('form').jsonSubmit({});
				
		
			
});
