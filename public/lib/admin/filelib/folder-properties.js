$j(document).ready(function() {
	
	
	
	$j('form').submit(function(){
		
		$j('.validationError').removeClass('validationError');
		$j('form button[type=submit]').attr('disabled', 'disabled');
		
		$j.ajax({
			url: this.action,
			data: $j(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				$j('form button[type=submit]').attr('disabled', '');
				Emerald.message(msg.message);
			}
		});
								
		return false;
				
	});

	
	
	
	
});




