(function($){
	$.entwine('ss', function($) {
		$('input[name="showBlockbyClass"]').entwine({
			onmatch: function(e) {
				if ($('input[name="showBlockbyClass"]:checked').val() == 0) {
					$('#Pages').show();
					$('#shownInClass').hide();
				}else{
					$('#shownInClass').show();
					$('#Pages').hide();
				}
				
				var backlink = $('.backlink').attr('href');
					backlinks = backlink.split('/');
				if (backlinks[1] == 'pages') {
					var pid = backlinks[backlinks.length-1],
						cb = $('ul[name="Pages"] input.checkbox[value="'+pid+'"]').length > 0 ? $('ul[name="Pages"] input.checkbox[value="'+pid+'"]') : $('#Pages input.checkbox[value="'+pid+'"]');
					cb.prop('disabled', true);
					cb.parent().attr('title', 'You cannot remove block from the current working page. Please try to use Blocks list, or a different page');
				}
				
			},
			onchange: function(e) {
				if ($(this).val() == 0) {
					$('#Pages').show();
					$('#shownInClass').hide();
				}else{
					$('#shownInClass').show();
					$('#Pages').hide();
				}
			}
		});
	});

}(jQuery));