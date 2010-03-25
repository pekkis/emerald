$(document).ready(function() {
	$('form').jsonSubmit({
		success: function(msg) {
			location = Emerald.baseUrl + '/admin/page/edit/id/' + msg.page_id;
		}
	});
});
