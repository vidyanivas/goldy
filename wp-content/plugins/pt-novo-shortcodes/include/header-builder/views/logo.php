<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Logo')) {

	class Header_Builder_View_Logo {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_logo', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('site-logo-'),
						'image_light' => '',
						'image_dark' => '',
						'text' => get_bloginfo('name'),
            'text_fz' => '',
						'width' => '',
						'height' => '',
						'logo_on_center' => 'no'
					),
					$args
				)
			);

			$image_light = yprm_get_image($image_light);
			$image_dark = yprm_get_image($image_dark);

			if(!$image_light && !$image_dark && !$text) return false;

			$withColorScheme = false;

			if($image_light && $image_dark) {
				$withColorScheme = true;
			}

			yprm_buildCSS(".$this->header_css .$uniqid img", array(
				'width' => $width,
				'height' => $height
			));

			yprm_buildCSS(".$this->header_css .$uniqid", array(
				'font-size' => $text_fz
			));

			?>
				<div class="logo-block<?php echo $logo_on_center == 'yes' ? ' on-center' : '' ?>">
					<div class="logo <?php echo esc_attr($uniqid) ?>">
						<a href="<?php echo esc_url(home_url('/')) ?>" data-magic-cursor="link">
							<?php if($image_light) { ?>
								<img <?php echo $withColorScheme ? 'class="light" ' : '' ?>src="<?php echo esc_url($image_light[0]) ?>" alt="<?php echo esc_attr(get_bloginfo('name')) ?>">
							<?php } if($image_dark) { ?>
								<img <?php echo $withColorScheme ? 'class="dark" ' : '' ?>src="<?php echo esc_url($image_dark[0]) ?>" alt="<?php echo esc_attr(get_bloginfo('name')) ?>">
							<?php } if($text && !$image_light && !$image_dark) { ?>
								<span><?php echo strip_tags($text) ?></span>
							<?php } ?>
						</a>
					</div>
				</div>
			<?php
		}
	}

	new Header_Builder_View_Logo($this->header_css);
}