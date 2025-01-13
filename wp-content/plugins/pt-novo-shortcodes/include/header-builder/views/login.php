<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Login')) {

	class Header_Builder_View_Login {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_login', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-login-'),
						'custom_link' => '',
						'font_size' => '',
						'color' => '',
						'color_on_hover' => '',
					),
					$args
				)
			);

			if($custom_link) {
				$link = $custom_link;
			} else {
				$link = get_permalink( get_option('woocommerce_myaccount_page_id') );
			}

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color
			));

			yprm_buildCSS(".$this->header_css .$uniqid:hover" , array(
				'color' => $color_on_hover
			));

			?>
				<a class="header-login-button <?php echo esc_attr($uniqid) ?>" href="<?php echo esc_url($link); ?>">
					<i class="base-icon-login"></i>
					<span><?php echo esc_html__('Login', 'pt-addons') ?></span>
				</a>
			<?php
		}
	}

	new Header_Builder_View_Login($this->header_css);
}