$j(document).ready(function() {
	
	var form = $('pageproperties');
	var extForm = new Emerald.Form(form);
	form.observe("submit", function(evt)
	{
		Event.stop(evt);
		
		new Ajax.Request("/admin/sitemap/savePage",
		{
			method: "post",
			parameters: Event.element(evt).serialize(true),
			onSuccess: function(transport, msg)
			{
				if(msg.type == 1)
				{
					window.opener.location.reload();
					if(Emerald.confirm("sitemap/save_ok_confirm_close"))
					{
						window.close();
					}
				}
				else if(msg.message.fields)
				{
					extForm.setErrors(msg.message.fields);
				}
			}
		});
		
	});
	
	var id = $(form['id']);
	if(id.getValue()) extForm.populate("/admin/sitemap/getPageData/id/"+id.getValue());
	
});
