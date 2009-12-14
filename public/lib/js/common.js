


/**
 * Parses a sql formatted date and returns the Date object
 * @param {String} strDate
 * @return {Date}
 */
Emerald.parseSQLDate = function(strDate)
{
	return new Date(strDate.replace(/^(....).(..).(.{11}).*$/, "$1/$2/$3"));
}
/**
 * Message wrapper for the better future (tm)
 * @param {String} msgPath Translation path
 */
Emerald.message = function(msgPath, llParams)
{
	if(msgPath.substr(0, 2) == 'l:') {
		msgPath = Emerald.t(msgPath);
	}
	alert(msgPath, llParams);
};

/**
 * Confirm wrapper for the better future (tm)
 * @param {String} msgPath Translation path
 * @return boolean
 */
Emerald.confirm = function(msgPath, llParams)
{
	return confirm(Emerald.t(msgPath, llParams));
};

/**
 * A Cookie class for managing cookies
 * 
 * Value can be about anything, it is encoded in JSON and decoded on retrieval
 */
Emerald.Cookie = 
{
	set: function (name, value, days) 
	{
		var expires = "";
		if (days) 
		{
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			expires = "; expires=" + date.toGMTString();
		}
		
		switch(typeof value) 
		{
		  case 'undefined':
		  case 'function' :
		  case 'unknown'  : return false;
		  case 'boolean'  : 
		  case 'string'   : 
		  case 'number'   : value = String(value.toString());
		}
		value = escape(value.toJSON());

		document.cookie = name+"="+value+expires+"; path=/";
	},
	
	get: function (name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0)
			{ 
				return (unescape(c.substring(nameEQ.length,c.length))).evalJSON();
			}
		}
		return null;
	},
	erase: function (name) 
	{
		Emerald.Cookie.set(name,"",-1);
	}
};


Emerald.Icon = 
{
	// the root of all evil
	iconPath: "/lib/gfx/nuvola/",
	xsmall: function(path, contextHelpPath)
	{
		return 	Emerald.Icon._getIcon("x-small", path, contextHelpPath);
	},
	small: function(path, contextHelpPath)
	{
		return 	Emerald.Icon._getIcon("small", path, contextHelpPath);
	},
	medium: function(path, contextHelpPath)
	{
		return 	Emerald.Icon._getIcon("medium", path, contextHelpPath);
	},
	large: function(path, contextHelpPath)
	{
		return 	Emerald.Icon._getIcon("large", path, contextHelpPath);
	},
	_getIcon: function(size, path, contextHelpPath)
	{
		var link = new Element("a", 
		{
			href: "", 
			title: Emerald.Localization.translate(contextHelpPath)
		});
		var sizes = 
		{
			"x-small": "16x16",
			"small": "22x22",
			"medium": "32x32",
			"large": "64x64",
			"x-large": "128x12x"
		};
		if(sizes[size] == undefined) throw ("Undefined icon size :"+size);
		var icon = new Element("img",
		{
			src: Emerald.Icon.iconPath + sizes[size] + "/" + path + ".png",
			className: "Emerald_Icon"
		});
		link.appendChild(icon);
		return link;
	}
};



var bodyCount = 0;
var aCount = 0;

$(document).ready(function() {

	return;
	$('body').click(function(myEvent) {
								
		bodyCount++;
		
		console.debug('body clicked: ' + bodyCount);
		console.debug(this);
		console.debug(myEvent);
		return false;
	});

	$('div').click(function(myEvent) {
		console.debug('div clicked: ' + bodyCount);
		console.debug(this);
		console.debug(myEvent);
		return true;	
		
	});

	
	$('a').click(function(myEvent) {
		
		aCount++;
						
		console.debug('a clicked: ' + aCount);
		console.debug(this);
		console.debug(myEvent);
		return false;
	});
	
	
});



/**
 * Binds options to standard list menu
 */
Emerald.RowMenu = Class.create();
Emerald.RowMenu.prototype = {
	initialize: function(menuContainer, options){
		this.container = menuContainer;
		this.options = Object.extend({
			hoverActivate: false /* string|false - style definition of a containing element that triggers the hover */
		}, options);
		this.setupMenu();
	},
	setupMenu: function()
	{
		if(this.options.hoverActivate)
		{
			var dimOpacity = 0.5;
			
			this.container.setStyle({visibility: "hidden"});
			this.container.up(this.options.hoverActivate).observe("mouseover", (function(){
				this.container.setStyle({visibility: "visible"});
			}).bind(this));
			this.container.up(this.options.hoverActivate).observe("mouseout", (function(){
				this.container.setStyle({visibility: "hidden"});
			}).bind(this));
			this.container.select("a").invoke("setStyle",{opacity: dimOpacity});
			this.container.select("a").invoke("observe", "mouseover", (function(x){
				Event.findElement(x, "a").setStyle({opacity: 1});
			}).bind(this));
			this.container.select("a").invoke("observe", "mouseout", (function(x){
				Event.findElement(x, "a").setStyle({opacity: dimOpacity});
			}).bind(this));
		}
	}	
};






