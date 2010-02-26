$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = Emerald.baseUrl + '/admin/group/edit/id/' + msg.group_id; }
	});
});
