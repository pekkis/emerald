$j(document).ready(function() {
	
	var form = $("group_edit");
	var extForm = new Emerald.Form(form);
	form.observe("submit", function(evt)
	{
		Event.stop(evt);
		new Ajax.Request("/emerald-admin/user/saveGroup",
		{
			method: "post",
			parameters: Event.element(evt).serialize(),
			onSuccess: function(transport, msg)
			{
				if(msg.type == 1)
				{
					window.location = "/emerald-admin/user#groupList";
				}
				else if(msg.message.fields)
				{
					extForm.setErrors(msg.message.fields);
				}
			}
		});
	});
});
