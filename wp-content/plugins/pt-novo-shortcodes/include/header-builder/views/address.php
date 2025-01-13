<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Address')) {

	class Header_Builder_View_Address {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_address', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-address-'),
						'address' => '',
						'font_size' => '',
						'color' => '',
					),
					$args
				)
			);

			if(!$address) return false;

			yprm_buildCSS(".$this->header_css .$uniqid", array(
				'font-size' => $font_size,
				'color' => $color,
			))

			?>
				<div class="header-address <?php echo esc_attr($uniqid) ?>">
					<i class="base-icon-location"></i>
					<span><?php echo strip_tags($address) ?></span>
				</div>
			<?php
		}
	}

	new Header_Builder_View_Address($this->header_css);
}