
// tinyMCE.init(Emerald.TinyMCE.init({ mode: "exact", elements: "content" }));

$(document).ready(function() {
	$(".tinymce").tinymce(Emerald.TinyMCE.options());
	$('form').jsonSubmit();
});



