
$j(document).ready(function() {
	
	if($j('#sort_div .sortable_div').length) {
		$j('#sort_div').sortable({ items: '.sortable_div' });
	}
		

	$j('form#editFields').submit(function(){
		
		$j('.validationError').removeClass('validationError');
		$j('form button[type=submit]').attr('disabled', 'disabled');
		
		$j.ajax({
			url: this.action,
			data: $j(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				$j('form button[type=submit]').attr('disabled', '');
				if(msg.type == 4) {
															
					$j.each(msg.errorFields, function() {
						var identifier = 'form#editFields label[for=' + this + ']';
						if(identifier)
							$j(identifier).addClass('validationError');
					});
					
					Emerald.message(msg.message);
					
				} else {
					Emerald.message(msg.message);					
				}
			}
		});
								
		return false;
				
	});


	$j('form#createField').submit(function(){
		
		$j('.validationError').removeClass('validationError');
		$j('form button[type=submit]').attr('disabled', 'disabled');
		
		$j.ajax({
			url: this.action,
			data: $j(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				$j('form button[type=submit]').attr('disabled', '');
				if(msg.type == 4) {
					$j.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$j(identifier).addClass('validationError');
					});
				} else {
					
					alert('xoo xoo');
					Emerald.message(msg.message);					
					top.document.location.reload();
					//window.close();
				}
			}
		});
								
		return false;
				
	});


				


				
				
});
