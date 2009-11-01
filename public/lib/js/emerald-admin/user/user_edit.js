$j(document).ready(function() {
	
	var form = $("user_edit");
	var extForm = new Emerald.Form(form);
	form.observe("submit", function(evt)
	{
		Event.stop(evt);
		new Ajax.Request("/emerald-admin/user/saveUser",
		{
			method: "post",
			parameters: Event.element(evt).serialize(),
			onSuccess: function(transport, msg)
			{
				if(msg.type == 1)
				{
					window.location = "/emerald-admin/user#userList";
				}
				else if(msg.message.fields)
				{
					extForm.setErrors(msg.message.fields);
				}
			}
		});
	});
});
