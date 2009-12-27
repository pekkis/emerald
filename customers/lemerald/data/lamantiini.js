Lamantiini = { };

Lamantiini.titleSeparator = ' - ';

Lamantiini.navi = null;

Lamantiini.cache = { };

Lamantiini.setTitle = function(newTitle)
{
	var title = $("title").text().split(Lamantiini.titleSeparator);
	title[0] = newTitle;
	$("title").text(title.join(Lamantiini.titleSeparator));
};

Lamantiini.naviUpdate = function($link, navitree) {
	$.each(navitree, function(key, navi) {
		
		if($link.attr('href') == navi.uri) {
			Lamantiini.setTitle(navi.title);
			$link.parents('li').addClass('active');
		}
		
		if(navi.pages) {
			Lamantiini.naviUpdate($link, navi.pages);
		}
		
	});
};


/**
 * I stole and shamelessly ripped this from Sakari. Thanks, Sakari! Goto http://sakarituominen.com now.
 * 
 */
Lamantiini.stealFromSakari = function($canvasElement)
{
			
	try {
		
			var canvasElement = $canvasElement.get(0); 
		
			var 
				width = canvasElement.clientWidth,
				height = canvasElement.clientHeight,
				canvas = canvasElement.getContext('2d'),
				x = Math.round(width / 2), // center x
				y = Math.round(height / 2), // center y
				d = Math.round(Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2)));
				
			canvasElement.setAttribute('width', width);
			canvasElement.setAttribute('height', height);
			
			// Draw lotsof em smallballs!
			for (var i = 0; i < 100; i++) {
				canvas.beginPath();
				canvas.arc(Math.round(width * Math.random()), Math.round(height * Math.random()), Math.round(20 * Math.random() + 20), 0, Math.PI * 2, true);
				canvas.closePath();
				canvas.fillStyle = 'rgba(255, 224, 240, .55)';
				canvas.fill();
			}
			
		}
		catch(e) {
			// canvas multifail!
		}

	
		
};




$(window).hashchange(function(){
	
	var $link = $('a[href=' + location.hash.substr(1) + ']');
			
	
	// if($link.length) {
								
		$('#content-container').slideUp(500, function() {
				
			$('#content-container').load($link.attr('href'), { 'format': 'html' }, function () {
				// $("title").text($link.attr('title'));
				
				$('#content-container').slideDown(500);
				
				$("li").removeClass('active');
				
				if(Lamantiini.navi) {
					Lamantiini.naviUpdate($link, Lamantiini.navi);
				} else {
					$.getJSON("/menu/index", { 'format' : 'json' }, function(r) {
						Lamantiini.navi = r;
						Lamantiini.naviUpdate($link, Lamantiini.navi);
					});
					
				}
				
				

			});
		});		
		
	// }
	
	

 });

$(document).ready(function() {
				
	Lamantiini.stealFromSakari($('#luss'));
	
	$('#content-container > h3 + div').hide();
	
	$('#content-container > h3 + div.active').show();
	
	$('#content-container > h3').click(function() {
		$(this).next('div').toggle();
	});
	
	$('marquee').marquee();
	
	$('a[href]').live('click', function(e) {
		
		if(e.button != 0) {
			return false;
		}
						
		var $this = $(this);
		
		if($this.attr('href').substr(0, 4) != 'http') {
			location = "#" + $this.attr('href');
			return false;
		} else {
			alert('external!');
			return true;
		}
		
		
		return false;
	});
	

	if(location.hash && location.hash != '/') {
		$(window).trigger('hashchange');
	}
	
	
});

