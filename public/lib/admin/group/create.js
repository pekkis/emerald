$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = '/admin/group/edit/id/' + msg.group_id; }
	});
});
