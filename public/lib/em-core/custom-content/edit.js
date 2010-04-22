
$(document).ready(function() {
	
	$('form').jsonSubmit({
		success: function(msg) { window.opener.location.reload(); }
	});
	
	$('#siblings').change(function() {
						
		if(this.value) {
			var url = Emerald.url('/em-core/custom-content/edit/page_id/' + this.value + '/block_id/' + $('#block_id').val());
			top.location = url;  
		}
		
	});
	
});



