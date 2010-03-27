$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = Emerald.url('/admin/user/edit/id/' + msg.user_id); }
	});
});
