jQuery(document).on("click", ".header-field-item .image .img", function (e) {
	e.preventDefault();
	var $button = jQuery(this);

	var file_frame = wp.media({
		title: 'Select or upload image',
		library: {
			type: 'image'
		},
		button: {
			text: 'Select'
		},
		multiple: false
	});


	file_frame.on('select', function () {

		var attachment = file_frame.state().get('selection').first().toJSON(),
		url = attachment.url;

		$button.parent().addClass('selected').parents('.header-field-item').attr('data-param-value', attachment.id).trigger('change');
		
		if(typeof attachment.sizes.medium !== 'undefined') {
			url = attachment.sizes.medium.url
		}

		$button.css({
			backgroundImage: 'url('+url+')'
		})
	});

	file_frame.open();
}).on("click", ".header-field-item .image .cross", function (e) {
	e.preventDefault();

  jQuery(this).parents('.header-field-item').attr('data-param-value', '').trigger('change');

	jQuery(this).parent().removeClass('selected').find('.img').css({
		backgroundImage: ''
	})
});