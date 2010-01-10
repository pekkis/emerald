$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = '/admin/user/edit/id/' + msg.user_id; }
	});
});
