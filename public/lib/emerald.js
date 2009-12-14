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
				external_link_list_url : "/admin/sitemap/tinymcelinklist",
				language: Emerald.Localization.getLanguage(),
				plugins: "table",
				theme_advanced_buttons2_add : "tablecontrols"
			};
			
			var returner = $.extend(common, config);
			return returner;
			
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
        
		// Remove tinymces own poo.
		$('link:last').remove();					
		
		// Associate clicks to all filelib files and push em back to the tinymce dialog.		
		$('.filelibFile').click(function() {
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







$(document).ready(function()
{
		
	$("#loading").bind("ajaxStart", function(){
		$(this).css('top', $(window).scrollTop());
		$(this).show();
	}).bind("ajaxStop", function(){
		$(this).hide();
	});

	$('.confirmable').live('click', function() {
		return confirm(Emerald.t('common/are_you_sure'));
	})
	
	$('.popup').live('click', Emerald.Popup.listener);

});
