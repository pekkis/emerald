
$j(document).ready(function() {
	
	
	$j("input,button,select").attr('disabled', '');
	
	
	$j("#login").submit(function() {
		
		
		
		var postData = { };
		if($j('#foo').attr('value')) postData.email = $j('#foo').attr('value'); 
		if($j('#bar').attr('value')) postData.passwd = $j('#bar').attr('value');
	
		$j.ajax({
			type: "post",
			url: "/login/handle",
			dataType: "json",
			data: postData,
			success: function(msg) 
			{
				
				if(msg.type != 1) 
				{
					$j('#messages').text(msg.message);						
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

	$j('#locale').change(function() {
		
		top.document.location = '/login/' + this.value;	
	})
	
	
});




