<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Text')) {

	class Header_Builder_View_Text {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_text', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-text-'),
						'text' => '',
						'font_size' => '',
						'color' => '',
					),
					$args
				)
			);

			if(!$text) return false;

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color
			));

			?>
				<div class="header-text <?php echo esc_attr($uniqid) ?>"><?php echo strip_tags($text) ?></div>
			<?php
		}
	}

	new Header_Builder_View_Text($this->header_css);
}