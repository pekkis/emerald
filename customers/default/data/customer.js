$(function() {

	$('img[src$=mini.jpg]').each(function(key, value) {
		var target = this.src.replace(/mini\.jpg$/, 'thumb.jpg'); 
		$(this).wrap('<a rel="fancybox" title="' + this.title + '" href="' + target + '">');
	});
	
	$("a[rel=fancybox]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over'
	});
	
});