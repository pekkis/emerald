
// tinyMCE.init(Emerald.TinyMCE.init({ mode: "exact", elements: "content" }));

$(document).ready(function() {
	
	if(window.opener.location.pathname != beautifurl) {
		window.opener.location.pathname = beautifurl;
	}
	
	$(".tinymce").tinymce(Emerald.TinyMCE.options());
	
	$('form').jsonSubmit({
		success: function(msg) { window.opener.location.reload(); }
	});
	
	$('#siblings').change(function() {
		
		if(this.value) {
			var url = Emerald.url('/em-core/html-content/edit/page_id/' + this.value + '/block_id/' + $('#block_id').val());
			top.location = url;  
		}
		
	});
	
});



