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