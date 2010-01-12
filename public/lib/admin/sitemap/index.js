$(document).ready(function() {
	
	$("ul > li > ul").sortable({
		// connectWith: "ul > li > ul",
		stop: function(event, ui) {
			
			$parent = ui.item.parent().parent();
		
			var darr = [];
						
			var parentId = $parent.attr('id').split('-')[1] ? $parent.attr('id').split('-')[1] : '';
			
			var count = 0;
			
			$parent.find('li.node').each(function(key, value) {

				var nid = value.id.split('-')[1];
				var xoo = { 'order_id': ++count, 'parent_id': parentId };
			
				$.post('/admin/page/save-partial/id/' + nid + '/format/json',  xoo , function() {
				
				});
							
				
			});
			
			
		}
	});


	
	$(".delete").jsonClick({
		success: function(elm) { elm.parent().parent().remove(); }
	});
	

	$(".shard").change(function() {
		$this = $(this);
		var id = $this.attr('id').split('-')[1];
		$.post('/admin/page/save-partial/id/' + id + '/format/json', { 'shard_id': $(this).val() }, function() {
		});
	});
	
	$(".layout").change(function() {
		$this = $(this);
		var id = $this.attr('id').split('-')[1];
		$.post('/admin/page/save-partial/id/' + id + '/format/json', { 'layout': $(this).val() }, function() {
		});
	});
	
	
	
	$(".label-editable > span").dblclick(function() {
		
		$label = $(this).parent();
		
		$input = $('<input type="text" />').val($label.find('span').text());
		$label.find('span').hide();
		$label.append($input);
				
		$input.focus();
		
		$input.blur(function() {
			
			$label = $(this).parent();
							
			var id = $label.parent().parent().attr('id').split('-')[1];
			
			$.post('/admin/page/save-partial/id/' + id + '/format/json', { 'title': $(this).val() }, function() {
					
				
				
			});
												
			$label.find('span').text($(this).val()).show();
			$(this).remove();
			
		});
				
	});
	
		
	
});
