<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Phone')) {

	class Header_Builder_View_Phone {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_phone', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-phone-'),
						'phone' => '',
						'is_link' => '',
						'font_size' => '',
						'color' => '',
					),
					$args
				)
			);

			if(!$phone) return false;

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color
			));

			?>
				<?php if($is_link == 'yes') { ?>
				<a href="tel:<?php echo esc_attr($phone) ?>" class="header-phone <?php echo esc_attr($uniqid) ?>">
				<?php } else { ?>
				<div class="header-phone <?php echo esc_attr($uniqid) ?>">
				<?php } ?>
					<i class="base-icon-phone-call"></i>
					<span><?php echo strip_tags($phone); ?></span>
				<?php if($is_link == 'yes') { ?>
				</a>
				<?php } else { ?>
				</div>
				<?php } ?>
			<?php
		}
	}

	new Header_Builder_View_Phone($this->header_css);
}