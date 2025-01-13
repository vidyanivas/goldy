<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Button')) {

	class Header_Builder_View_Button {
		
		var $header_css;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_button', array($this, 'render'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-button-'),
						'label' => '',
						'link' => '',
						'icon' => '',
						'style' => 'style1',
						'font_size' => '',
						'color' => '',
						'color_on_hover' => '',
						'bg_color' => '',
						'bg_color_on_hover' => '',
					),
					$args
				)
			);

			if(!$label && !$link) return false;

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color,
				'background-color' => $bg_color,
			));

			yprm_buildCSS(".$this->header_css .$uniqid:hover" , array(
				'color' => $color_on_hover,
				'background-color' => $bg_color_on_hover,
			));

			$icon_html = '';
			if($icon == 'calendar') {
				$icon_html = '<div class="icon"><svg width="17" height="17" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.187 3.263a.987.987 0 00-.986.987v9.563c0 .544.441.986.986.986h10.625a.987.987 0 00.987-.986V4.25a.987.987 0 00-.987-.987H3.187zm-2.2.987a2.2 2.2 0 012.2-2.2h10.625a2.2 2.2 0 012.201 2.2v9.563a2.2 2.2 0 01-2.2 2.2H3.186a2.2 2.2 0 01-2.2-2.2V4.25z"/><path d="M9.828 8.5a.797.797 0 100-1.594.797.797 0 000 1.594zM12.484 8.5a.797.797 0 100-1.594.797.797 0 000 1.594zM9.828 11.156a.797.797 0 100-1.593.797.797 0 000 1.593zM12.484 11.156a.797.797 0 100-1.593.797.797 0 000 1.593zM4.516 11.156a.797.797 0 100-1.593.797.797 0 000 1.593zM7.172 11.156a.797.797 0 100-1.593.797.797 0 000 1.593zM4.516 13.813a.797.797 0 100-1.594.797.797 0 000 1.594zM7.172 13.813a.797.797 0 100-1.594.797.797 0 000 1.594zM9.828 13.813a.797.797 0 100-1.594.797.797 0 000 1.594z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M4.25.987c.335 0 .607.271.607.607v1.062a.607.607 0 11-1.214 0V1.594c0-.336.271-.607.607-.607zm8.5 0c.335 0 .607.271.607.607v1.062a.607.607 0 01-1.214 0V1.594c0-.336.271-.607.607-.607zM1.594 4.705h13.812V5.92H1.594V4.705z"/></svg></div>';
			} else if($icon) {
				$icon_html = '<div class="icon"><i class="'.esc_attr($icon).'"></i></div>';
			}

			?>
				<a href="<?php echo esc_url($link) ?>" class="header-button <?php echo esc_attr($style.' '.$uniqid) ?>">
					<?php echo $icon_html ?>
					<span><?php echo strip_tags($label) ?></span>
				</a>
			<?php
		}
	}

	new Header_Builder_View_Button($this->header_css);
}