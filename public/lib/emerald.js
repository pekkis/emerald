Emerald = { };

Emerald.baseUrl = '';

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
	$.each(obj, function(key, value) {
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
	
	var elm = $(this);
	var features = { };
	
	if(elm.hasClass('popup-small')) {
		features.width = 400;
		features.height = 400;
	} else if(elm.hasClass('popup-large')) {
		features.width = 800;
		features.height = 600;
	} else if(elm.hasClass('popup-medium')) {
		features.width = 500;
		features.height = 500;
	}
	
	features.resizable = (elm.hasClass('popup-resizable')) ? 'yes' : 'no';
	features.scrollbars = (elm.hasClass('popup-scrollbars')) ? 'yes' : 'no';

	Emerald.Popup.open(elm.attr('href'), elm.attr('id'), Emerald.Popup.featureStringFromObject(features)); 
	return false;
}


Emerald.TinyMCE = {
		
		options: function(options)
		{
			var defaultOptions = {
				script_url : Emerald.baseUrl + '/lib/tinymce/jscripts/tiny_mce/tiny_mce.js',
				width: "100%",
				theme : "advanced",
				file_browser_callback : Emerald.FileManager.open,
				convert_urls: true,
				relative_urls: false,
				content_css: "/data/editor.css",
				external_link_list_url : Emerald.baseUrl + "/admin/sitemap/link-list/format/js",
				language: Emerald.Localization.getLanguage(),
				plugins: "table",
				theme_advanced_buttons2_add : "tablecontrols"
			};
			
			var combinedOptions = $.extend(defaultOptions, options);
			return combinedOptions;
			
		}
		
		
	};


/*
 * TinyMCE Filemanager 
 */
Emerald.FileManager = {

	open: function(field_name, url, type, win)
	{
		tinyMCE.activeEditor.windowManager.open({
        file : '/admin/filelib/select/type/image',
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
        
		// Remove tinymces own stylez.
		$('link:last').remove();					
		
		// Associate clicks to all filelib files and push em back to the tinymce dialog.		
		$('.file').click(function() {
			
			var href= $(this).attr('href');
			

			
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

/**
 * Localization class
 */
Emerald.Localization = 
{
	_localizations: {},
	_language: null,
	_locale: null,
	
	init: function(locale, language, data)
	{
		Emerald.Localization._language = language;
		Emerald.Localization._localizations = data;
	},
	
	/**
	 * Returns the language the localization was initialized with.
	 */
	getLanguage: function()
	{
		console.debug(Emerald.Localization._language);
		return Emerald.Localization._language;
	},
	
	
	/**
	 * translate
	 * 
	 * @param {String} tstr String to translate
	 * @param {Array} params Parameters to inject to the path
	 */
	translate: function(tstr, params)
	{
		if(Emerald.Localization._localizations[tstr]) 
		{
			return Emerald.Localization._localizations[tstr];			
		}	
		
		return tstr;
	}
	
};

String.prototype.t = function(params)
{
	return Emerald.Localization.translate(this.toString(), params);

}


Emerald.message = function(msg)
{
	
	$("#message p").text(msg);
	$("#message").dialog('open');
		
	location = "#message";
}

Emerald.Json = { };

Emerald.Json.Message = {
		
	SUCCESS : 1,
	INFO : 2,
	ERROR : 4

};


jQuery.fn.jsonClick = function(options) {
	  
	var defaultOptions = {
		success: function(elm) {},
		failure: function(elm) {}
	};
	
	var finalOptions = jQuery.extend(defaultOptions, options);
			
	return this.each(function(){
	
		
	$this = $(this);
	
	var eventName = $this.hasClass('emerald-confirm') ? 'clickConfirmed' : 'click';
			
	  $this.data("callback", finalOptions);
	  
	  $this.bind(eventName, function() {
		  
		  $that = $(this);
		  $.ajax({
			type: "post",
			url: this.href + "/format/json",
			dataType: "json",
			success: function(response) 
			{
				var msg = response.message;
				var callback = $that.data("callback");
				if(msg.type == Emerald.Json.Message.ERROR) {
					callback.failure($that, msg);						
				} else {
					callback.success($that, msg);
				}
			}
		});
		return false;
	  });
  });
};

	




jQuery.fn.jsonSubmit = function(options) {
	  
		var defaultOptions = {
			success: function() {},
			failure: function() {}
		};
		
		var finalOptions = jQuery.extend(defaultOptions, options);
				
		return this.each(function(){
			
			$this = $(this);
			$this.data("callback", finalOptions);
		  
		  $this.submit(function() {
			
			  $that = $(this);
							  
			$("label", $that).removeClass("error");
			
			// $("input[type=submit], button[type=submit]", $that).attr("disabled", "disabled");
						
			
			
			if(this.action.indexOf("?") != -1) {
				var addon = '&format=json';
			} else {
				var addon = "/format/json";
			}
					
			 $.ajax({
				type: "post",
				url: this.action + addon,
				dataType: "json",
				data: $(this).serialize(),
				success: function(response) 
				{
				 	var msg = response.message;
					// $("input[type=submit], button[type=submit]", $that).attr("disabled", "");
						
															
					var callback = $that.data("callback");
					
					if(msg.type == Emerald.Json.Message.ERROR) {
																		
						if(msg.errors) {
																												
							$.each(msg.errors, function(key, value) {
								
								// Catch subforms too
								$.each(value, function(key2, value2) {
									if(typeof(value2) == 'object') {
										$("label[for=" + key2 + "]", $that).addClass("error");
									}
								});
																								
								$("label[for=" + key + "]", $that).addClass("error");
							});
						}
						
						callback.failure(msg);						
					} else {
						callback.success(msg);
					}
					
					
					
					
					
					
				}
			});
			return false;
			
		  });
		  
		  
		 
	  });
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




$(document).ready(function()
{
	$("#message").hide();
	
	$("#loading").bind("ajaxStart", function(){
		$(this).css('top', $(window).scrollTop());
		$(this).show();
	}).bind("ajaxStop", function(){
		$(this).hide();
	});

	$('.emerald-confirm').live('click', function(event) {
		event.preventDefault();
		if(confirm('Are you sure?'.t())) {
			$(this).trigger('clickConfirmed');
		}
	});
	
	$('.popup').live('click', Emerald.Popup.listener);

	$('#message').dialog({ autoOpen: false });
	

});
