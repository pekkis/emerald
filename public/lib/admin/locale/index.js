$(document).ready(function() {
	
		
	$('form').submit(function(){
						
		$.ajax({
			url: this.action,
			data: $(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(response){
				Emerald.message(response.message.message);
			}
		});
								
		return false;
				
	});

	
	
	
	
	
	
});


