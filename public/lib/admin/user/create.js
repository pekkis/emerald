$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = Emerald.baseUrl + '/admin/user/edit/id/' + msg.user_id; }
	});
});
