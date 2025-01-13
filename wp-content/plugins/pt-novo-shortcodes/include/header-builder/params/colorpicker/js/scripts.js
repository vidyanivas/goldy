jQuery(document).on('opened-header-builder', function($){
	jQuery('body').find('.iris_color').each(function() {
		jQuery(this).wpColorPicker({
			defaultColor: false,
		});
	});
});