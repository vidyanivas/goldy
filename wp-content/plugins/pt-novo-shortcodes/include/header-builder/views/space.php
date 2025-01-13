<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Space')) {

	class Header_Builder_View_Space {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_space', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-h-space-'),
						'width' => '5',
						'custom_width' => '',
					),
					$args
				)
			);

			if($width == 'custom' && $custom_width) {
				yprm_buildCSS(".$this->header_css .$uniqid" , array(
					'flex' => '0 0 '.$custom_width,
					'max-width' => $custom_width
				));
			} ?>
				<div class="header-h-space header-h-space-<?php echo $width.' '.$uniqid ?>"></div>
			<?php
		}
	}

	new Header_Builder_View_Space($this->header_css);
}