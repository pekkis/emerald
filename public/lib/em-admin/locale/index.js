$(document).ready(function() {
	
	$(".locale-delete").jsonClick({
		success: function(elm) { elm.parents('tr').remove(); }
	});
		
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


