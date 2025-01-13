<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Wishlist')) {

	class Header_Builder_View_Wishlist {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_wishlist', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('wishlist-button-'),
						'font_size' => '',
						'color' => '',
						'color_on_hover' => '',
					),
					$args
				)
			);

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color
			));

			yprm_buildCSS(".$this->header_css .$uniqid:hover" , array(
				'color' => $color_on_hover
			));

			var_dump('sds');

			yprm_get_wishlist_html(' '.$uniqid);
		}
	}

	new Header_Builder_View_Wishlist($this->header_css);
}