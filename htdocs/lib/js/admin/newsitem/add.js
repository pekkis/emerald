tinyMCE.init(Emerald.TinyMCE.init({ mode : "exact", elements : "article" }));

$j(document).ready(function() {
	
	$j('form').submit(function(){
		
		$j('.validationError').removeClass('validationError');
		tinyMCE.get('article').save();
		
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
