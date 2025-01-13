<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Sidebar')) {

	class Header_Builder_View_Sidebar {
		
		var $header_css;
		var $color_mode;
    var $type;

		function __construct($css_class, $color_mode) {
			$this->header_css = $css_class;
			$this->color_mode = $color_mode;

			add_filter('header_builder_view_sidebar', array($this, 'render'));
			add_action('yprm_after_header', array($this, 'after_header'));
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => uniqid('header-sidebar-button-'),
            'type' => '',
						'font_size' => '',
						'color' => '',
						'color_on_hover' => '',
					),
					$args
				)
			);

      $this->type = $type;

      if(!is_active_sidebar($type)) return;

			yprm_buildCSS(".$this->header_css .$uniqid" , array(
				'font-size' => $font_size,
				'color' => $color
			));

			yprm_buildCSS(".$this->header_css .$uniqid:hover" , array(
				'color' => $color_on_hover
			));

			?>
        <div class="side-bar-button multimedia-icon-list <?php echo esc_attr($uniqid) ?>" data-mouse-magnetic="true" data-mouse-scale="1.4" data-hide-cursor="true"></div>
			<?php
		}

		public function after_header() {
      if(!is_active_sidebar($this->type)) return;

      
      $copyright_text = yprm_get_theme_setting('copyright_text');

			?>
				<div class="side-bar-area main-row">
          <div class="close basic-ui-icon-cancel"></div>
          <?php if($sidebar_word = yprm_get_theme_setting('header_sidebar_word')) { ?>
            <div class="bg-word"><?php echo strip_tags($sidebar_word) ?></div>
          <?php } ?>
					<div class="wrap">
						<?php dynamic_sidebar($this->type); ?>
					</div>
					<?php if(!empty($copyright_text)) { ?>
						<div class="copyright"><?php echo $copyright_text ?></div>
					<?php } ?>
				</div>
			<?php
		}
	}

	new Header_Builder_View_Sidebar($this->header_css, $this->color_mode);
}