
$(document).ready(function() {
	$('.news-item-delete').jsonClick({ success: function(msg, evt) { $(evt.currentTarget).parents('.news-item').remove(); }});
});



