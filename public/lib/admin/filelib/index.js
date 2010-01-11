$(function() {
	
	$("#create-folder").jsonSubmit({
		success: function(msg) { location.reload(); }
	});
	
	$(".delete-folder").jsonClick({
		success: function(elm) { location = '/admin/filelib'; }
	});
	
	$(".delete-file").jsonClick({
		success: function(elm) { elm.parents('li').remove(); }
	});

	
});




