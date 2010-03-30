
$(document).ready(function() {
	$('.news-item-delete').jsonClick({ success: function(elm) { elm.parents('.news-item').remove(); }});
});



