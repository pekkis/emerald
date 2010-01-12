$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) { location = '/admin/page/edit/id/' + msg.page_id; }
	});
});
