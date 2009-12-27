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

	console.debug(features);

	Emerald.Popup.open(elm.attr('href'), elm.attr('id'), Emerald.Popup.featureStringFromObject(features)); 
	return false;
}


Emerald.TinyMCE = {
		
		options: function(options)
		{
			var defaultOptions = {
				script_url : '/lib/tinymce/jscripts/tiny_mce/tiny_mce.js',
				width: "100%",
				theme : "advanced",
				file_browser_callback : Emerald.FileManager.open,
				convert_urls: true,
				relative_urls: false,
				content_css: "/data/editor.css",
				external_link_list_url : "/admin/sitemap/tinymcelinklist",
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



Emerald.message = function(msg)
{
	$("#message p").text(msg);
	$("#message").show();
	
	$("#message").show();
	
	location = "#message";
}

Emerald.Json = { };

Emerald.Json.Message = {
		
	SUCCESS : 1,
	INFO : 2,
	ERROR : 4

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
			
			$("label", $this).removeClass("error");
			
			// $("input[type=submit], button[type=submit]", $this).attr("disabled", "disabled");
			
			 $.ajax({
				type: "post",
				url: this.action + "/format/json",
				dataType: "json",
				data: $(this).serialize(),
				success: function(response) 
				{
					var msg = response.message;
					// $("input[type=submit], button[type=submit]", $this).attr("disabled", "");
					//console.debug(msg);
					
					var callback = $this.data("callback");
					
					if(msg.type == Emerald.Json.Message.ERROR) {
						
						if(msg.errors) {
							$.each(msg.errors, function(key, value) {
								$("label[for=" + key + "]", $this).addClass("error");
							});
						}
						
						callback.failure();						
					} else {
						callback.success();
					}
					
					
					
					
					
					
				}
			});
			return false;
			
		  });
		  
		  
		 
	  });
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

	$('.confirmable').live('click', function() {
		return confirm(Emerald.t('common/are_you_sure'));
	})
	
	$('.popup').live('click', Emerald.Popup.listener);

});
