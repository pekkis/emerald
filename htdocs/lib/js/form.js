/**
 * Class for handling form population and validation
 */
Emerald.Form = Class.create();
Emerald.Form.prototype = 
{
	/**
	 * Constructor
	 * @param {String|Object} form Either the DOM_Node or the id of the form
	 */
	initialize: function(form)
	{
		this.form = $(form);
		this.errors = $A();
		form.observe("submit", this.clearErrors.bind(this));
	},
	/**
	 * Loads the object json from the url and populates the form
	 * 
	 * @param {Object} url 
	 */
	populate: function(url)
	{
		new Ajax.Request(url,
		{
			method: "post",
			onSuccess: (function(transport, json)
			{
				if(json && json.message)
				{
					this._populateForm(json.message);
				}
			}).bind(this)
		});
	},
	/**
	 * Does the actual populating
	 * @param {Object} formData JSON object, keys are used to locate form elements
	 */
	_populateForm: function(formData)
	{
		
		$H(formData).each((function(pair)
		{
			var name = pair.key;
			var value = pair.value;
			
			if(this.form[name] !== undefined && value != null)
			{
				$(this.form[name]).setValue(value);
			}
			
		}).bind(this));
	},
	
	/**
	 * Sets the error labels on the form
	 * 
	 * @param {Array} fields An array containing the erroneous fieldnames
	 */
	setErrors: function(fields)
	{
		this.clearErrors();
		
		$A(fields).each((function(field)
		{
			if(this.form[field])
			{
				var labelFor = this.form[field].id;
				var errorElms = this.form.select("label[for='"+labelFor+"']");
				this.errors = this.errors.concat(errorElms);
			}
		}).bind(this));
		
		this.errors.each(this._addError);	
	},
	
	/**
	 * Cleans the error labels
	 */
	clearErrors: function()
	{
		this.errors.each(this._removeError);
		this.errors = $A();
	},
	
	_addError: function(element)
	{
		element.addClassName("validationError");	
	},
	
	_removeError: function(element)
	{
		element.removeClassName("validationError");	
	}
};
var Emerald_EIP_Form = Class.create();
Emerald_EIP_Form.prototype = 
{
	initialize: function(options)
	{
		this.options = Object.extend(
		{
			postUrl: null, // the url to send the data to
			autoSave: false
		},options);
		this.data = $H();
		this.elements = $H();
	},
	addElement: function(editableElement, type)
	{
		if(!type)
		{
			var type = null;
			switch(editableElement.nodeName)
			{
				case "UL":
				case "OL":
					type = "list";
				break;
				default:
					type = "text";
				break;
			}
		}
		var options = clone(this.options);
		var eipElement = null;
		switch(type)
		{
			case "list":
				eipElement = new Emerald_EIP_Element_List(editableElement, options);
			break;
			
			case "text":
			default:
				eipElement = new Emerald_EIP_Element_Text(editableElement, this.options);
			break;
		}
		this.elements.set(eipElement.getId(), eipElement);
	},
	save: function()
	{
		var postData = $H();
		this.data.map(function(pair)
		{
			postData.set("eip["+pair[0]+"]", pair[1])
		});
		$j.ajax({
			url: this.options.postUrl,
			async: true,
			data: postData.toQueryString(),
			type: 'post',
			dataType: 'json',
			success: (function(data)
			{
				if(data.type != 1) Emerald.message("sitemap/not_changed");
				
			}).bind(this)
		});	
	},
	_elementUpdated: function(eipElement)
	{
		
		this.data.set(eipElement.getId(), eipElement.getValue());
		if(this.options.autoSave) this.save();
	}
}
var Emerald_EIP_Element = Class.create();
Emerald_EIP_Element.prototype = 
{
	initialize: function(editableElement, options, eipForm)
	{
		if(!eipForm)
		{
			eipForm = new Emerald_EIP_Form(options);
		}
		this.eipForm = eipForm;
		
		this.editable = editableElement;
		this.editable.addClassName("eipElement");
		this.options = Object.extend({},options);
		this.active = false;
		this.element = this._createElement();
		this.value = null;
		
		$(this.element).observe("blur",this.passivate.bind(this));
		$(this.editable).observe("click",this.activate.bind(this));
	},
	getId: function()
	{
		return this.editable.id.replace("eipId_","");
	},
	activate: function()
	{
		
		this.active = true;
		this.element.setValue(this.value ? this.value : this.editable.innerHTML.unescapeHTML());
		this.editable.replace(this.element);
		this.element.focus();
	},
	passivate: function()
	{
		
		this.value = this._getValue();
		this.active = false;
		var escapedContent = this.value.escapeHTML().strip();
		if(escapedContent)
		{
			this.editable.update(escapedContent);
			this.eipForm._elementUpdated(this);
		}
		this.element.replace(this.editable);
	},
	getValue: function(){ return this.value; },
	_createElement: function(){},
	_getValue: function()
	{
		if(!this.active) return;
		return this.element.getValue();
	}
}


var Emerald_EIP_Element_Text = Class.extend(Emerald_EIP_Element,
{
	initialize: function(editableElement, options, eipForm)
	{
		Emerald_EIP_Element.prototype.initialize.call(this, editableElement, options, eipForm);
	},
	_createElement: function()
	{
		var elm = new Element("input",{"type": "text"});
		elm.observe("keypress", function(evt){
			if(evt.keyCode == Event.KEY_RETURN)
			{
				Event.element(evt).blur();
			}
		});
		return elm;
	}
});