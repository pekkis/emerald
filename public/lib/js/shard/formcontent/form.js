$j(document).ready(function() {
	
	$j('form').submit(function(){
		
		$j('.validationError').removeClass('validationError');
		
		$j.ajax({
			url: this.action,
			data: $j(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				if(msg.type == 4) {
					$j.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$j(identifier).addClass('validationError');
					});
				} else {
					
					top.document.location = '/' + msg.redirect_beautifurl;
					
				}
			}
		});
								
		return false;
				
	});
	
			
});
