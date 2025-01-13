jQuery(function ($) {
	'use strict';

	/**
	 * ---------------------------------------
	 * ------------- Events ------------------
	 * ---------------------------------------
	 */

	/**
	 * No or Single predefined demo import button click.
	 */
	$('.js-ocdi-import-data').on('click', function () {

		// Reset response div content.
		$('.js-ocdi-ajax-response').empty();

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append('action', 'ocdi_import_demo_data');
		data.append('security', ocdi.ajax_nonce);
		data.append('selected', $('#ocdi__demo-import-files').val());
		if ($('#ocdi__content-file-upload').length) {
			data.append('content_file', $('#ocdi__content-file-upload')[0].files[0]);
		}
		if ($('#ocdi__widget-file-upload').length) {
			data.append('widget_file', $('#ocdi__widget-file-upload')[0].files[0]);
		}
		if ($('#ocdi__customizer-file-upload').length) {
			data.append('customizer_file', $('#ocdi__customizer-file-upload')[0].files[0]);
		}
		if ($('#ocdi__redux-file-upload').length) {
			data.append('redux_file', $('#ocdi__redux-file-upload')[0].files[0]);
			data.append('redux_option_name', $('#ocdi__redux-option-name').val());
		}

		// AJAX call to import everything (content, widgets, before/after setup)
		ajaxCall(data);

	});


	/**
	 * Grid Layout import button click.
	 */
	$('.js-ocdi-gl-import-data').on('click', function () {
		var selectedImportID = $(this).val();
		var $itemContainer = $(this).closest('.js-ocdi-gl-item');

		// If the import confirmation is enabled, then do that, else import straight away.
		if (ocdi.import_popup) {
			displayConfirmationPopup(selectedImportID, $itemContainer);
		} else {
			gridLayoutImport(selectedImportID, $itemContainer);
		}
	});


	/**
	 * Grid Layout categories navigation.
	 */
	(function () {
		// Cache selector to all items
		var $items = $('.js-ocdi-gl-item-container').find('.js-ocdi-gl-item'),
			fadeoutClass = 'ocdi-is-fadeout',
			fadeinClass = 'ocdi-is-fadein',
			animationDuration = 200;

		// Hide all items.
		var fadeOut = function () {
			var dfd = jQuery.Deferred();

			$items
				.addClass(fadeoutClass);

			setTimeout(function () {
				$items
					.removeClass(fadeoutClass)
					.hide();

				dfd.resolve();
			}, animationDuration);

			return dfd.promise();
		};

		var fadeIn = function (category, dfd) {
			var filter = category ? '[data-categories*="' + category + '"]' : 'div';

			if ('all' === category) {
				filter = 'div';
			}

			$items
				.filter(filter)
				.show()
				.addClass('ocdi-is-fadein');

			setTimeout(function () {
				$items
					.removeClass(fadeinClass);

				dfd.resolve();
			}, animationDuration);
		};

		var animate = function (category) {
			var dfd = jQuery.Deferred();

			var promise = fadeOut();

			promise.done(function () {
				fadeIn(category, dfd);
			});

			return dfd;
		};

		$('.js-ocdi-nav-link').on('click', function (event) {
			event.preventDefault();

			// Remove 'active' class from the previous nav list items.
			$(this).parent().siblings().removeClass('active');

			// Add the 'active' class to this nav list item.
			$(this).parent().addClass('active');

			var category = this.hash.slice(1);

			// show/hide the right items, based on category selected
			var $container = $('.js-ocdi-gl-item-container');
			$container.css('min-width', $container.outerHeight());

			var promise = animate(category);

			promise.done(function () {
				$container.removeAttr('style');
			});
		});
	}());


	/**
	 * Grid Layout search functionality.
	 */
	$('.js-ocdi-gl-search').on('keyup', function (event) {
		if (0 < $(this).val().length) {
			// Hide all items.
			$('.js-ocdi-gl-item-container').find('.js-ocdi-gl-item').hide();

			// Show just the ones that have a match on the import name.
			$('.js-ocdi-gl-item-container').find('.js-ocdi-gl-item[data-name*="' + $(this).val().toLowerCase() + '"]').show();
		} else {
			$('.js-ocdi-gl-item-container').find('.js-ocdi-gl-item').show();
		}
	});

	/**
	 * ---------------------------------------
	 * --------Helper functions --------------
	 * ---------------------------------------
	 */

	/**
	 * Prepare grid layout import data and execute the AJAX call.
	 *
	 * @param int selectedImportID The selected import ID.
	 * @param obj $itemContainer The jQuery selected item container object.
	 */
	function gridLayoutImport(selectedImportID, $itemContainer, is_elementor) {

		// Reset response div content.
		$('.js-ocdi-ajax-response').empty();

		// Hide all other import items.
		$itemContainer.siblings('.js-ocdi-gl-item').fadeOut(500);

		$itemContainer.animate({
			opacity: 0
		}, 500, 'swing', function () {
			$itemContainer.animate({
				opacity: 1
			}, 500)
		});

		// Hide the header with category navigation and search box.
		$itemContainer.closest('.js-ocdi-gl').find('.js-ocdi-gl-header').fadeOut(500);

		// Append a title for the selected demo import.
		$itemContainer.parent().prepend('<h3>' + ocdi.texts.selected_import_title + '</h3>');

		// Remove the import button of the selected item.
		$itemContainer.find('.js-ocdi-gl-import-data').remove();

		let theme_options = $('#js-ocdi-modal-content').find('[name="theme_options"]').is(':checked'),
		content = $('#js-ocdi-modal-content').find('[name="content"]').is(':checked'),
		media_files = $('#js-ocdi-modal-content').find('[name="media_files"]').is(':checked'),
		header = $('#js-ocdi-modal-content').find('[name="header"]').is(':checked'),
		footer = $('#js-ocdi-modal-content').find('[name="footer"]').is(':checked'),
		posts = $('#js-ocdi-modal-content').find('[name="posts"]').is(':checked'),
		projects = $('#js-ocdi-modal-content').find('[name="projects"]').is(':checked'),
		products = $('#js-ocdi-modal-content').find('[name="products"]').is(':checked'),
		widgets = $('#js-ocdi-modal-content').find('[name="widgets"]').is(':checked'),
		albums = $('#js-ocdi-modal-content').find('[name="albums"]').is(':checked'),
		contact_forms = $('#js-ocdi-modal-content').find('[name="contact_forms"]').is(':checked')

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append('action', 'ocdi_import_demo_data');
		data.append('security', ocdi.ajax_nonce);
		data.append('selected', selectedImportID);
		data.append('theme_options', theme_options);
		data.append('content', content);
		data.append('media_files', media_files);
		data.append('header', header);
		data.append('footer', footer);
		data.append('posts', posts);
		data.append('projects', projects);
		data.append('products', products);
		data.append('widgets', widgets);
		data.append('albums', albums);
		data.append('contact_forms', contact_forms);
		data.append('is_elementor', is_elementor);

		// AJAX call to import everything (content, widgets, before/after setup)
		ajaxCall(data);
	}

	/**
	 * Display the confirmation popup.
	 *
	 * @param int selectedImportID The selected import ID.
	 * @param obj $itemContainer The jQuery selected item container object.
	 */
	function displayConfirmationPopup(selectedImportID, $itemContainer) {
		var $dialogContiner = $('#js-ocdi-modal-content');
		var currentFilePreviewImage = ocdi.import_files[selectedImportID]['import_preview_image_url'] || ocdi.theme_screenshot;
		var previewImageContent = '';
		var importNotice = ocdi.import_files[selectedImportID]['import_notice'] || '';
		var importNoticeContent = '';
		var buttonsArray = [{
			text: 'Import for WP Backery',
			class: 'button  button-primary',
			click: function () {
				$(this).dialog('close');
				gridLayoutImport(selectedImportID, $itemContainer, false);
			}
		}];

		if(ocdi.import_files[selectedImportID]['has_elementor']) {
			buttonsArray.push({
				text: 'Import for Elementor',
				class: 'button elementor-import button-primary',
				click: function () {
					$(this).dialog('close');
					gridLayoutImport(selectedImportID, $itemContainer, true);
				}
			})
		}

    if(
      ocdi.import_files[selectedImportID]['slug'] == 'blog-samples' || 
      ocdi.import_files[selectedImportID]['slug'] == 'project-samples' ||
      ocdi.import_files[selectedImportID]['slug'] == 'product-samples' ||
      ocdi.import_files[selectedImportID]['slug'] == 'album-samples' ||
      ocdi.import_files[selectedImportID]['categories'][0] == 'Footers' ||
      ocdi.import_files[selectedImportID]['categories'][0] == 'Headers' ||
      ocdi.import_files[selectedImportID]['categories'][0] == 'Contact Forms'
    ) {
      buttonsArray = [{
        text: 'Import',
        class: 'button content-button button-primary',
        click: function () {
          $(this).dialog('close');
          gridLayoutImport(selectedImportID, $itemContainer, false);
        }
      }];
    }

		var dialogOptions = $.extend({
				'dialogClass': 'wp-dialog one-click-demo-content-dialog',
				'resizable': false,
				'height': 'auto',
				'modal': true
			},
			ocdi.dialog_options, {
				title: 'Demo content import',
				width: 800,
				buttons: buttonsArray
			});

		if ('' === currentFilePreviewImage) {
			previewImageContent = '<p>' + ocdi.texts.missing_preview_image + '</p>';
		} else {
			previewImageContent = '<div class="ocdi__modal-image-container"><img src="' + currentFilePreviewImage + '" alt="' + ocdi.import_files[selectedImportID]['import_file_name'] + '"></div>'
		}

		// Prepare notice output.
		if ('' !== importNotice) {
			importNoticeContent = '<div class="ocdi__modal-notice  ocdi__demo-import-notice">' + importNotice + '</div>';
		}

		var isChecked = function(part) {
			if(ocdi.import_parts[part]) {
				return ' checked'
			}
		}

		var paramFields = `
			<div class="fields">
				<div class="h6">Choose what will be imported with the demo:</div>
				<div class="step-label">Step 1</div>
        <div class="checkbox-area">
          <label class="checkbox-block"><input type="checkbox" name="content" checked><i></i><span>Content</span></label>
          `+(
              ocdi.import_files[selectedImportID].categories != 'Full' && 
              ocdi.import_files[selectedImportID].categories != 'Headers' && 
              ocdi.import_files[selectedImportID].categories != 'Footers' && 
              ocdi.import_files[selectedImportID].categories != 'Contact Forms' && 
              ocdi.import_files[selectedImportID].categories != 'Content Samples'
          ? `
          <label class="checkbox-block"><input type="checkbox" name="theme_options"`+isChecked('theme_options')+`><i></i><span>Theme Options</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].media_files ? `
          <label class="checkbox-block"><input type="checkbox" name="media_files" checked><i></i><span>Media Files</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].header ? `
          <label class="checkbox-block"><input type="checkbox" name="header" checked><i></i><span>Header</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].footer ? `
          <label class="checkbox-block"><input type="checkbox" name="footer" checked><i></i><span>Footer</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].posts ? `
          <label class="checkbox-block"><input type="checkbox" name="posts"`+isChecked('posts')+`><i></i><span>Posts</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].projects ? `
          <label class="checkbox-block"><input type="checkbox" name="projects"`+isChecked('projects')+`><i></i><span>Projects</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].products ? `
          <label class="checkbox-block"><input type="checkbox" name="products"`+isChecked('products')+`><i></i><span>Products</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].albums ? `
          <label class="checkbox-block"><input type="checkbox" name="albums"`+isChecked('albums')+`><i></i><span>Products</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].widgets ? `
          <label class="checkbox-block"><input type="checkbox" name="widgets"`+isChecked('widgets')+`><i></i><span>Widgets</span></label>
          ` : ``)+`
          `+(ocdi.import_files[selectedImportID].contact_forms ? `
          <label class="checkbox-block"><input type="checkbox" name="contact_forms"`+isChecked('contact_forms')+`><i></i><span>Contact Forms</span></label>
          ` : ``)+`
        </div>
			</div>
		`

		var secondStep = '<div class="step-label">Step 2</div>';

		// Populate the dialog content.
		$dialogContiner.prop('title', ocdi.texts.dialog_title);
		$dialogContiner.html(
			'<p class="ocdi__modal-item-title">' + ocdi.import_files[selectedImportID]['import_file_name'] + '</p>' +
			'<div class="content-row">' +
			previewImageContent +
			paramFields +
			'</div>' +
			secondStep +
			importNoticeContent
		);

		// Display the confirmation popup.
		$dialogContiner.dialog(dialogOptions);
	}

	/**
	 * The main AJAX call, which executes the import process.
	 *
	 * @param FormData data The data to be passed to the AJAX call.
	 */
	function ajaxCall(data) {
		$.ajax({
				method: 'POST',
				url: ocdi.ajax_url,
				data: data,
				contentType: false,
				processData: false,
				beforeSend: function () {
					$('.js-ocdi-ajax-loader').show();
				}
			})
			.done(function (response) {
				if ('undefined' !== typeof response.status && 'newAJAX' === response.status) {
					ajaxCall(data);
				} else if ('undefined' !== typeof response.status && 'customizerAJAX' === response.status) {
					// Fix for data.set and data.delete, which they are not supported in some browsers.
					var newData = new FormData();
					newData.append('action', 'ocdi_import_customizer_data');
					newData.append('security', ocdi.ajax_nonce);

					// Set the wp_customize=on only if the plugin filter is set to true.
					if (true === ocdi.wp_customize_on) {
						newData.append('wp_customize', 'on');
					}

					ajaxCall(newData);
				} else if ('undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status) {
					// Fix for data.set and data.delete, which they are not supported in some browsers.
					var newData = new FormData();
					newData.append('action', 'ocdi_after_import_data');
					newData.append('security', ocdi.ajax_nonce);
					ajaxCall(newData);
				} else if ('undefined' !== typeof response.message) {
					$('.js-ocdi-ajax-response').append('<p>' + response.message + '</p>');
					$('.js-ocdi-ajax-loader').hide();

					// Trigger custom event, when OCDI import is complete.
					$(document).trigger('ocdiImportComplete');
				} else {
					$('.js-ocdi-ajax-response').append('<div class="notice  notice-error  is-dismissible"><p>' + response + '</p></div>');
					$('.js-ocdi-ajax-loader').hide();
				}
			})
			.fail(function (error) {
				$('.js-ocdi-ajax-response').append('<div class="notice  notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>');
				$('.js-ocdi-ajax-loader').hide();
			});
	}
});