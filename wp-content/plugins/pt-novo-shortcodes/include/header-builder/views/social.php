<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Social')) {

	class Header_Builder_View_Social {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_social', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('social-links-'),
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
				'opacity' => $color ? 1 : ''
			));

			yprm_buildCSS(".$this->header_css .$uniqid a:hover" , array(
				'color' => $color_on_hover
			));

			?>
				<div class="header-social-links <?php echo esc_attr($uniqid) ?>"><?php echo yprm_build_social_links() ?></div>
			<?php
		}
	}

	new Header_Builder_View_Social($this->header_css);
}