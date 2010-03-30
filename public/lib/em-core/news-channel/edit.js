
$(document).ready(function() {
	$('form').jsonSubmit({
		success: function() { window.opener.location.reload(); }
	});
});



