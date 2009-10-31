$j(document).ready(function() {
	
	
	$j('#showObscure').click(function() {
		
		if($j(this).attr('checked')) {
			$j('.obscureLocale').removeClass('hidden');
		} else {
			$j('.obscureLocale').addClass('hidden');
		}
		
		
	});
	
	
	$j('form').submit(function(){
		
		$j('.message').html('');
						
		$j.ajax({
			url: this.action,
			data: $j(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				$j('.message').html(msg.message);
			}
		});
								
		return false;
				
	});

	
	
	
	
	
	
});


