<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Cart')) {

	class Header_Builder_View_Cart {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_cart', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-minicart-'),
						'font_size' => '',
						'color' => '',
						'color_on_hover' => '',
					),
					$args
				)
			);

			yprm_buildCSS(".$this->header_css .$uniqid .hm-count" , array(
				'font-size' => $font_size,
				'color' => $color,
			));

			yprm_buildCSS(".$this->header_css .$uniqid .hm-count:hover" , array(
				'color' => $color_on_hover
			));

			echo yprm_wc_minicart(' '.$uniqid);
		}
	}

	new Header_Builder_View_Cart($this->header_css);
}