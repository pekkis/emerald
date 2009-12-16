tinyMCE.init(Emerald.TinyMCE.init({ mode: "exact", elements: "content" }));

$(document).ready(function() {
	
	$('form').submit(function(){
		
		$('.validationError').removeClass('validationError');
		tinyMCE.get('content').save();
		
		$.ajax({
			url: this.action,
			data: $(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				if(msg.type == 4) {
					$.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$(identifier).addClass('validationError');
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



