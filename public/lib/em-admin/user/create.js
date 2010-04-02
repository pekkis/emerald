$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = Emerald.url('/em-admin/user/edit/id/' + msg.user_id); }
	});
});
