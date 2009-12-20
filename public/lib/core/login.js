
$(document).ready(function() {
	
		
	$("input,button,select").attr('disabled', '');
	
	
	$("form").submit(function() {
				
		
		var postData = { };
		if($('#foo').attr('value')) postData.email = $('#foo').attr('value'); 
		if($('#bar').attr('value')) postData.passwd = $('#bar').attr('value');
	
		$.ajax({
			type: "post",
			url: "/login/handle",
			dataType: "json",
			data: postData,
			success: function(msg) 
			{
				
				if(msg.type != 1) 
				{
					$('#messages').text(msg.message);						
				} 
				else  
				{	
					if(top.document.location.pathname.substr(0, 6) == "/login")
					{	// if not in some admin action, just redirect to root
						top.document.location = '/';	
					}
					else
					{	// reload and continue whatever the user was doing
						top.document.location.reload();
					}
					
				}
			}
		});


		return false;
	
	});

	$('#locale').change(function() {
		
		top.document.location = '/login/' + this.value;	
	})
	
	
});




