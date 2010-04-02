$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = Emerald.url('/em-admin/group/edit/id/' + msg.group_id); }
	});
});
