<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Divider')) {

	class Header_Builder_View_Divider {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_divider', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-divider-'),
						'color' => '',
					),
					$args
				)
			);

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'background-color' => $color
			)) 
			?>
				<div class="header-divider <?php echo esc_attr($uniqid) ?>"></div>
			<?php
		}
	}

	new Header_Builder_View_Divider($this->header_css);
}