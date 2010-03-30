$(document).ready(function() {
		
	$('#tabs').tabs({ 
		show: function(event, ui) {
			if(ui.index == 1) {
				$("#accordion").accordion();
			}
		}
	});
	
	
	
		
	$('form').submit(function(){
		
		var $form = $(this);
		
		$('.error').removeClass('error');
		
		$('input[type=submit]', $form).attr('disabled', 'disabled');
		
		$.ajax({
			url: this.action,
			data: $(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(response){
				var msg = response.message;
				$('input[type=submit]', $form).attr('disabled', '');
				if(msg.type == 4) {
					$.each(msg.errors, function(key, value) {
						var identifier = 'label[for=' + key + ']';
						$(identifier, $form).addClass('error');
					});
				} else {
					Emerald.message(msg.message);
				}
			}
		});
								
		return false;
				
	});
	
			
});
