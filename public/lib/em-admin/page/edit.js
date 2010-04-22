String.prototype.repeat = function(l){
	return new Array(l+1).join(this);
};


naviUpdate = function(pages, level, navitree) {
			
	$.each(navitree, function(key, navi) {
		
		if(navi.id) {
			pages.push({ key: navi.global_id, value: "-".repeat(level) + navi.label});
		}
		
		
		if(navi.pages) {
			var lev2 = level++;
			naviUpdate(pages, lev2, navi.pages);
		}
		
	});
};

$(window).unload(function() {
	this.opener.location.reload();
});


$(document).ready(function() {
		
	$('form').jsonSubmit();
	
	$('#interlink_page').change(function() {
		
		if(this.value) {
			$("#global_id").val(this.value);
		}
		
	});
	
	
	$('#interlink_locale').change(function() {
		
		var $this = $(this);
						
		$.getJSON("/em-core/menu/index", { 'locale': this.value, 'format' : 'json' }, function(r) {
		
			var pages = [];
			
			naviUpdate(pages, 1, r.pages);
						
			$("#interlink_page").empty().append($('<option value="">--</option>'));
			
			$.each(pages, function(key, value) {
				$("#interlink_page").append($('<option value="' + value.key + '">' + value.value + '</option>'));
			});
			
			
		});
		
	
	});
	

});
