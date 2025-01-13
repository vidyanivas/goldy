<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Search')) {

	class Header_Builder_View_Search {
		
		var $header_css;
		var $color_mode;

		function __construct($css_class, $color_mode) {
			$this->header_css = $css_class;
			$this->color_mode = $color_mode;

			add_filter('header_builder_view_search', array($this, 'render'));
			add_action('yprm_after_header', array($this, 'after_header'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-search-button-'),
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

			?>
				<div class="header-search-button <?php echo esc_attr($uniqid) ?>" data-mouse-magnetic="true" data-mouse-scale="1.4" data-hide-cursor="true"><i class="base-icon-magnifying-glass"></i><i class="base-icon-close"></i></div>
			<?php
		}

		public function after_header() {
			?>
				<div class="search-popup main-row">
					<div class="centered-container"><?php get_search_form(); ?></div>
				</div>
			<?php
		}
	}

	new Header_Builder_View_Search($this->header_css, $this->color_mode);
}