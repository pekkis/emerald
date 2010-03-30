Emerald = { };

Emerald.url = function(url) {
	return Emerald.URL_BASE + url;
}

Emerald.libUrl = function(url) {
	return Emerald.URL_BASE_LIB + url;
}

Emerald.dataUrl = function(url) {
	return Emerald.URL_BASE_DATA + url;
}

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
		features.height = 650;
	} else if(elm.hasClass('popup-medium')) {
		features.width = 500;
		features.height = 500;
	} else if(elm.hasClass('popup-huge')) {
		features.width = 1024;
		features.height = 768;
	}
	
	features.resizable = (elm.hasClass('popup-resizable')) ? 'yes' : 'no';
	features.scrollbars = (elm.hasClass('popup-scrollbars')) ? 'yes' : 'no';

	Emerald.Popup.open(elm.attr('href'), elm.attr('rel'), Emerald.Popup.featureStringFromObject(features)); 
	return false;
}


Emerald.TinyMCE = {
		
		options: function(options)
		{
			var defaultOptions = {
				script_url : Emerald.libUrl('/lib/tinymce/jscripts/tiny_mce/tiny_mce.js'),
				width: "100%",
				theme : "advanced",
				file_browser_callback : Emerald.FileManager.open,
				convert_urls: true,
				relative_urls: false,
				content_css: "/data/editor.css",
				external_link_list_url : Emerald.libUrl("/lib/em-admin/sitemap/link-list/format/js"),
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
        file : Emerald.url('/admin/filelib/select/type/image'),
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
			tstr = Emerald.Localization._localizations[tstr];
		}
		
		if(params) {
			$.each(params, function(key, value) {
				
				tstr = tstr.replace("%" + (key + 1) + "$s", value);
			})
		}
		
		return tstr;
	}
	
};

String.prototype.t = function(params)
{
	return Emerald.Localization.translate(this.toString(), params);

}


Emerald.message = function(msg, params)
{
	if(typeof(msg) == 'string') {
		if(!params) {
			params = { };
		}
		return $.jGrowl(msg, params);
	}
		
	$.jGrowl(msg.message);
	
	
	// message class handling
	
	
	
	
}

Emerald.Json = { };

Emerald.Json.Message = {
		
	SUCCESS : 1,
	INFO : 2,
	ERROR : 4

};

Emerald.Message = {
		
		SUCCESS : 1,
		INFO : 2,
		ERROR : 4

};

jQuery.fn.jsonClick = function(options) {
	  
	var defaultOptions = {
		success: function(elm, msg) { },
		failure: function(elm, msg) { }
	};
	
	var finalOptions = jQuery.extend(defaultOptions, options);
			
	return this.each(function(){
	
		
	$this = $(this);
	
	var eventName = $this.hasClass('emerald-confirm') ? 'clickConfirmed' : 'click';
			
	  $this.data("callback", finalOptions);
	  
	  $this.bind(eventName, function(evt) {
		  
		  $that = $(this);
		  $.ajax({
			type: "post",
			url: this.href + "/format/json",
			dataType: "json",
			success: function(response) 
			{
				Emerald.Messenger.handleJson(response, evt);
			}
		});
		return false;
	  });
  });
};

	




jQuery.fn.jsonSubmit = function(options) {
	  
		var defaultOptions = {
			success: function(msg) {  },
			failure: function(msg) {  }
		};
		
		var finalOptions = jQuery.extend(defaultOptions, options);
				
		return this.each(function(){
			
			$this = $(this);
			$this.data("callback", finalOptions);
		  
		  $this.submit(function(evt) {
									  
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
				 	Emerald.Messenger.handleFormErrors(response, evt);
				 	Emerald.Messenger.handleJson(response, evt);
				 	
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


Emerald.Messenger = {
		
		handleFormErrors: function(data, evt)
		{
			var $that = $(evt.currentTarget);
			
			if(data.length != 0) {
		
				for(var i in data.messages) {
					
					var msg = data.messages[i];
					
					if(msg.type == Emerald.Message.ERROR) {
					
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
					}
				}
			}	

	
		},
		
		
		handleJson: function(data, evt) {
								
			var success = [];
			var info = [];
			var error = [];
			if(data.length != 0) {
				
				if(data.message) {
					data.messages = [ data.message ];
				}
				
				for(var i in data.messages) {
					
					var msg = data.messages[i];
					
					switch(msg.type) {
						case Emerald.Message.SUCCESS:
						Emerald.Messenger.publishMessage(msg.message,'success');
						break;
					case Emerald.Message.INFO:
						Emerald.Messenger.publishMessage(msg.message,'info');
						break;
					case Emerald.Message.ERROR:
						Emerald.Messenger.publishMessage(msg.message,'error');
						break;
				}
					
				var callback = $(evt.currentTarget).data('callback');
				if(callback) {
					if(msg.type == Emerald.Message.ERROR) {
						callback.failure(msg, evt);						
					} else {
						callback.success(msg, evt);
					}
				}
				
					
			}
		}
	},
	publishMessage: function(msg,type) {
		
		var params = [];
		switch(type) {
			case 'error':
				params.sticky = true;
				params.theme = 'jgrowl-ERROR';
				break;
			case 'info':
				params.theme = 'jgrowl-INFO';
				break;
			case 'success':
				params.theme = 'jgrowl-SUCCESS';
				break;
		}
		$.jGrowl(msg,params);
	}
};

	
	


$(document).ready(function()
{		
	$("#emerald-message").hide();
	
	$("#emerald-loading").bind("ajaxStart", function(){
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
	
	$('.emerald-popup').live('click', Emerald.Popup.listener);

});
