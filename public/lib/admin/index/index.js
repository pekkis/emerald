$(function() {
	
	$("#clear-cache").jsonClick({
		success: function(elm, msg) { Emerald.message(msg.message); }		
	});
	
	
});