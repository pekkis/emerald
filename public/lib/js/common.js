
Emerald = { };
/**
 * Emerald.Popup handles all things popup-related.
 * 
 */
Emerald.Popup = { };

/**
 * 
 * Opens a popup window.
 * 
 * @param {String} theurl
 * @param {String} winname
 * @param {String} features
 */
Emerald.Popup.open = function(theurl,winname,features) {
  msgWindow = window.open(theurl,winname,features);
  if (msgWindow != null && msgWindow.opener != null) msgWindow.opener=window;
  msgWindow.focus();
  return msgWindow;
}

/**
 * 
 * Converts feature array to feature string for window.open.
 * 
 * @param {Object} obj Feature array
 */
Emerald.Popup.featureStringFromObject = function(obj) {
	
	var features = [ ];
	var featureStr = '';
	$j.each(obj, function(key, value) {
		features.push(key + '=' + value);
	});
	return features.join(',');	
		
}

/**
 * Listener for all popup clicks ('popup(_*)'). 
 * 
 * @param {Object} myEvent Click event
 */
Emerald.Popup.listener = function(myEvent) {
	
	var elm = $j(this);
	var features = { };
	
	if(elm.hasClass('popup_small')) {
		features.width = 400;
		features.height = 400;
	} else if(elm.hasClass('popup_large')) {
		features.width = 800;
		features.height = 600;
	} else if(elm.hasClass('popup_medium')) {
		features.width = 500;
		features.height = 500;
	}
	
	features.resizable = (elm.hasClass('popup_resizable')) ? 'yes' : 'no';
	features.scrollbars = (elm.hasClass('popup_scrollbars')) ? 'yes' : 'no';

	console.debug(features);

	Emerald.Popup.open(elm.attr('href'), elm.attr('id'), Emerald.Popup.featureStringFromObject(features)); 
	return false;
}




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
/**
 * Localization class
 */
Emerald.Localization = 
{
	_localizations: {},
	_language: null,
	
	init: function(language, data)
	{
		Emerald.Localization._language = language;
		Emerald.Localization._localizations = data;
	},
	
	/**
	 * Returns the language the localization was initialized with.
	 */
	getLanguage: function()
	{
		return Emerald.Localization._language;
	},
	
	
	/**
	 * translate
	 * 
	 * @param {String} pathname Language Path which can contain printf-like placeholders
	 * @param {Array} llParams Parameters to inject to the path
	 */
	translate: function(pathname, llParams)
	{
		if(Emerald.Localization._localizations[pathname]) 
		{
			if(!llParams) llParams = [];
			
			var arrParams = $A(llParams);
			pathname = Emerald.Localization._localizations[pathname];
									
			// dirty fix, this accepts "kinda like formatted" strings and no formatting will happen
			pathname = pathname.gsub(/%./,function(){return arrParams.shift();});
					
						
		}	
		
		return pathname;
	}
	
};
Emerald.t = Emerald.Localization.translate; // shortcut

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


/*
 * TinyMCE Filemanager 
 */
Emerald.FileManager = {

	open: function(field_name, url, type, win)
	{
		tinyMCE.activeEditor.windowManager.open({
        file : '/emerald-admin/filelib/select/type/image',
        width : 800,  // Your dimensions may differ - toy around with them!
        height : 600,
        resizable : "no",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no"
	    }, {
	        window : win,
	        input : field_name
	    });
	    return false;
		
	},

    init : function () {
        
		// Remove tinymces own poo.
		$j('link:last').remove();					
		
		// Associate clicks to all filelib files and push em back to the tinymce dialog.		
		$j('.filelibFile').click(function() {
			var href= $j(this).attr('href');
		        var win = tinyMCEPopup.getWindowArg("window");
    		    win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = href;
        	// for image browsers: update image dimensions
        	if (win.getImageData) win.getImageData();
        	// close popup window
        	tinyMCEPopup.close();
			return false;
		});
		
    }
	
}

var bodyCount = 0;
var aCount = 0;

$j(document).ready(function() {

	return;
	$j('body').click(function(myEvent) {
								
		bodyCount++;
		
		console.debug('body clicked: ' + bodyCount);
		console.debug(this);
		console.debug(myEvent);
		return false;
	});

	$j('div').click(function(myEvent) {
		console.debug('div clicked: ' + bodyCount);
		console.debug(this);
		console.debug(myEvent);
		return true;	
		
	});

	
	$j('a').click(function(myEvent) {
		
		aCount++;
						
		console.debug('a clicked: ' + aCount);
		console.debug(this);
		console.debug(myEvent);
		return false;
	});
	
	
});


Emerald.TinyMCE = {
	
	init: function(config)
	{
		var common = {
			width: "100%",
			theme : "advanced",
			file_browser_callback : Emerald.FileManager.open,
			convert_urls: true,
			relative_urls: false,
			content_css: "/data/css/editor.css",
			external_link_list_url : "/emerald-admin/sitemap/tinymcelinklist",
			language: Emerald.Localization.getLanguage(),
			plugins: "table",
			theme_advanced_buttons2_add : "tablecontrols"
		};
		
		var returner = Object.extend(common, config);
		return returner;
		
	}
	
	
};


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



$j(document).ready(function()
{
	
	// Parse nice buttons.
	if(window["$$"] != undefined)
	$$('button.niceButton').each(function(btn)
	{
		var innerWrapper = new Element("span");
		innerWrapper.innerHTML = btn.innerHTML;
		btn.update(innerWrapper.wrap("span").wrap("span"));
		btn.addClassName("parsedButton");
	});
	
	// Bind global ajaxisms.
	$j("#loading").bind("ajaxStart", function(){
		$j(this).css('top', $j(window).scrollTop());
		$j(this).show();
	}).bind("ajaxStop", function(){
		$j(this).hide();
	});

	$j.listen('click', '.confirmable', function() {
		return confirm(Emerald.t('common/are_you_sure'));
	})
	
	$j.listen('click', '.popup', Emerald.Popup.listener);

});


