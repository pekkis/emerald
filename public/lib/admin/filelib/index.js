$(function() {
	
	$("#create-folder").jsonSubmit({
		success: function(msg, evt) { location.reload(); }
	});
	
	$(".delete-folder").jsonClick({
		success: function(msg, evt) { location = Emerald.baseUrl + '/admin/filelib'; }
	});
	
	$(".delete-file").jsonClick({
		success: function(msg, evt) { $(evt.currentTarget).parents('li').remove(); }
	});

	
});




