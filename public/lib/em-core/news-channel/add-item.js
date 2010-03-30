
$(document).ready(function() {
	$(".tinymce").tinymce(Emerald.TinyMCE.options());
	$('form').jsonSubmit({
		success: function(msg) {
			top.document.location = Emerald.url("/em-core/news-item/edit/id/" + msg.saved_item_id);
			window.opener.location.reload();
		} 
	});
});



