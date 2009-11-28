$j(document).ready(function() {
		
	$j('#container-1 > ul').tabs();
		
		
	$$('.localeSwither').invoke("observe","change", function(evt)
	{
		var elm = Event.element(evt);
		var locale = elm.getValue();
		window.location = "/admin/options/index/locale/"+locale;
	});
		
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
				if(msg.type == 4) {
					$j.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$j(identifier).addClass('validationError');
					});
				} else {
					Emerald.message(msg.message);
				}
			}
		});
								
		return false;
				
	});
	
			
});
