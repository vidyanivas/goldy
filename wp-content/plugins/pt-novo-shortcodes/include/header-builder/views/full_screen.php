<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Full_Screen')) {

	class Header_Builder_View_Full_Screen {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_full_screen', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-fullscreen-button-'),
						'font_size' => '',
						'color' => '',
						'color_on_hover' => '',
					),
					$args
				)
			);

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color,
			));

			yprm_buildCSS(".$this->header_css .$uniqid:hover" , array(
				'color' => $color_on_hover,
			));

			?>
				<div class="header-fullscreen-button <?php echo esc_attr($uniqid) ?>"><i class="base-icon-expand"></i></div>
			<?php
		}
	}

	new Header_Builder_View_Full_Screen($this->header_css);
}