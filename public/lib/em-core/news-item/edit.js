
$(document).ready(function() {
	$(".tinymce").tinymce(Emerald.TinyMCE.options());
	$('form').jsonSubmit({
		success: function() {
			window.opener.location.reload();
		}
	});
	
    $("#valid_start_date").datepicker({"dateFormat":"yy-mm-dd"});
    $("#valid_end_date").datepicker({"dateFormat":"yy-mm-dd"});
	
});



