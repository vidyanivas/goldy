<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Link')) {

	class Header_Builder_View_Link {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_link', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-link-'),
						'label' => '',
						'link' => '',
						'font_size' => '',
						'color' => '',
						'background_color' => '',
						'color_on_hover' => '',
						'background_color_on_hover' => '',
					),
					$args
				)
			);

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color,
				'background-color' => $background_color
			));

			yprm_buildCSS(".$this->header_css .$uniqid:hover" , array(
				'color' => $color_on_hover,
				'background-color' => $background_color_on_hover
			));

			?>
				<a href="<?php echo esc_url($link) ?>" class="header-link <?php echo esc_attr($uniqid) ?>">
					<span><?php echo strip_tags($label) ?></span>
					<i class="base-icon-next"></i>
				</a>
			<?php
		}
	}

	new Header_Builder_View_Link($this->header_css);
}