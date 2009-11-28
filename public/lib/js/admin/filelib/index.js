$j(document).ready(function() {
	
	$j('form#createFolder').submit(function(){
		
		$j('.validationError').removeClass('validationError');
				
		$j.ajax({
			url: this.action,
			data: $j(this).serialize(),
			type: 'post',
			dataType: 'json',
			success: function(msg){
				if(msg.type == 4) {
					$j.each(msg.errorFields, function() {
						var identifier = 'label[for=' + this + ']';
						$j(identifier).addClass('validationError');
					});
				} else {
					top.document.location.reload();
				}
			}
		});
								
		return false;
				
	});
	
	
	$j('.hoverAction').css('opacity', 0);
	
	$j('.hoverAction').parents('.hoverActivator').mouseover(function() {
		$j(this).find('.hoverAction').css('opacity', 1);
	});
	
	$j('.hoverAction').parents('tr').mouseout(function() {
		$j(this).find('.hoverAction').css('opacity', 0);
	});
	
	$j('.hoverAction a').css('opacity', '0.5')
						.mouseover(function() {
							$j(this).css('opacity', '1');
						}).mouseout(function() {
							$j(this).css('opacity', '0.5');
						});
	
	
	var uploadMonitor = new Emerald.FileUploadMonitor();	
			
});


Emerald.FileUploadMonitor = function() {
			
	this._token =  $j('#UPLOAD_IDENTIFIER').attr('value');
	var theForm = $j('#UPLOAD_IDENTIFIER').parent();
	this._uri = '/admin/filelib/monitorUpload/id/' + this._token;
			
	$j(theForm).submit(this._init.bind(this));			
		
};

Emerald.FileUploadMonitor.prototype = {
	
	
	_init: function()
	{
		this._interval = setInterval(this._updater.bind(this), 1000);
		return true;
	},
	
	
	_updater: function()
	{
		$j.getJSON(this._uri,
        (function(data){
			if(data)
				$j('#messages').html(data.bytes_uploaded + ' / ' + data.bytes_total);
			else {
				clearInterval(this._interval);
				$j('#messages').html(Emerald.t('admin/filelib/upload_finished'));
				top.document.location.reload();
			}
				
	}).bind(this));

		
	}
	
	
};




