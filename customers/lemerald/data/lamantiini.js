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
		
		var truutta = false;
		
		if($link.attr('href') == navi.uri) {
			truutta = true;
			Lamantiini.setTitle(navi.label);
			$link.parents('li').addClass('active');
			
			var uri = navi.uri;
			if(navi.redirect_uri) {
				uri = navi.redirect_uri
			}
			$('#content-container').slideUp(500, function() {
				$.get( uri, { 'format': 'html' }, function (html) {
										
					var $html = $("<div>" + html + "</div>");
					
					$("#block-content").empty();
					$(".emerald-response-segment-content", $html).each(function(key, html) {
						$("#block-content").append(html);
					});
					
					var insert;
					if($(".emerald-response-segment-sidebar", $html).length) {
						
						if(!$("#block-sidebar").length) {
							$("#block-content").before($('<div id="block-sidebar"></div>'));
						} else {
							$("#block-sidebar").empty();
						}
					
						$(".emerald-response-segment-sidebar", $html).each(function(key, html) {
							$("#block-sidebar").append(html);
						});
					} else {
						$("#block-sidebar").remove();
					}
					
					$('#content-container').slideDown(500);
				}, 'html');
			});		
			
		}
		
		if(navi.pages) {
			Lamantiini.naviUpdate($link, navi.pages);
		}
		
	});
};

Lamantiini.Mode = {
	
	mode: 'oldskool',
	
	init: function()
	{
		$('#manatee-mode').click(Lamantiini.Mode.toggle);
		
		$('#manatee-mode').text(this.mode); 
		
		if(Lamantiini.Mode.mode == 'hardcore') {
			this.execute();
		}
		
	},
		
	execute: function() {
		
		if(this.mode == 'oldskool') {
			this.oldskool();
		} else {
			this.hardcore();
		}
		
		$('#manatee-mode').text(this.mode); 
	
	},
	
	toggle: function(evt)
	{
		evt.preventDefault();
		
		Lamantiini.Mode.mode = (Lamantiini.Mode.mode == 'oldskool') ? 'hardcore' : 'oldskool';
		$.cookie('manateeMode', Lamantiini.Mode.mode);
		Lamantiini.Mode.execute();
	},
	
	
	hardcore: function() {
	
		$('body').addClass('hardbody');
		
		$('a[href]').live('click', function(e) {
			
			if(e.button != 0) {
				return false;
			}
							
			var $this = $(this);
			
			if($this.hasClass('noajax')) {
				return true;
			}
			
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

	
	
	},
	
	oldskool: function() {
		top.location = '/';
	},
	
	get: function() {
		return Lamantiini.Mode.mode;
	},
	
	
	set: function() {
		
	}
	
	
		
};



/**
 * I stole and shamelessly ripped this from Sakari. Thanks, Sakari! Goto http://sakarituominen.com now.
 * 
 */
Lamantiini.smallBalls = 
{
	
	gotBalls: 100,
	
	init: function($canvasElement) {
	
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
				
				// Draw lotsof em manatee balls!
				for (var i = 0; i < this.gotBalls; i++) {
					canvas.beginPath();
					canvas.arc(Math.round(width * Math.random()), Math.round(height * Math.random()), Math.round(20 * Math.random() + 20), 0, Math.PI * 2, true);
					canvas.closePath();
					
					var tussi = 125;
					var tussi2 = 125;
					
					var r = Math.round(tussi * Math.random() + tussi2);
					var g = Math.round(tussi * Math.random() + tussi2);
					var b = Math.round(tussi * Math.random() + tussi2);
					
					// canvas.fillStyle = 'rgba(255, 224, 240, .55)';
	
					canvas.fillStyle = 'rgba(' + r + ', ' + g +', ' + b + ', .55)';
					canvas.fill();
				}
				
			}
			catch(e) {
				// canvas multifail!
			}

	}
		
};




$(window).hashchange(function(){
	
	var $link = $('a[href=' + location.hash.substr(1) + ']');
		
	// if($link.length) {

	$("li").removeClass('active');
	
	if(Lamantiini.navi) {
		Lamantiini.naviUpdate($link, Lamantiini.navi);
	} else {
		$.getJSON("/menu/index", { 'format' : 'json' }, function(r) {
			Lamantiini.navi = r;
			Lamantiini.naviUpdate($link, Lamantiini.navi);
		});
	}

	
	
	
		
	// }
	
	

 });
 


$(document).ready(function() {
	
	Lamantiini.smallBalls.init($('#luss'));
	
	var manateeMode = $.cookie('manateeMode');
		
	if(manateeMode == 'hardcore') {
		Lamantiini.Mode.mode = 'hardcore';
	} else {
		Lamantiini.Mode.mode = 'oldskool';
		
	}
	Lamantiini.Mode.init();
	
	
	
		
});

