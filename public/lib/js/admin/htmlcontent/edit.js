tinyMCE.init(Emerald.TinyMCE.init({ mode: "exact", elements: "content" }));

$j(document).ready(function() {
	
	$j('form').submit(function(){
		
		$j('.validationError').removeClass('validationError');
		tinyMCE.get('content').save();
		
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
					window.opener.document.location.reload();
					if(Emerald.confirm("admin/htmlcontent/save_ok_confirm_close"))
					{
						window.close();
					}
				}
			}
		});
								
		return false;
				
	});
	
			
});



