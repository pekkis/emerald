$(document).ready(function() {
		
	$('#tabs').tabs();
		
		
	$('form').submit(function(){
		
		$('.validationError').removeClass('validationError');
		$('form button[type=submit]').attr('disabled', 'disabled');
		
		$.ajax({
			url: this.action,
			data: $(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				$('form button[type=submit]').attr('disabled', '');
				if(msg.type == 4) {
					$.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$(identifier).addClass('validationError');
					});
				} else {
					Emerald.message(msg.message);
				}
			}
		});
								
		return false;
				
	});
	
			
});
