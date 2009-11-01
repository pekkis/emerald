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
				if(msg.type == 4) {
					$j.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$j(identifier).addClass('validationError');
					});
				} else {
					window.close();
				}
			}
		});
								
		return false;
				
	});
	
			
});

$j(window).unload(function() {
	this.opener.document.location.reload();
});
