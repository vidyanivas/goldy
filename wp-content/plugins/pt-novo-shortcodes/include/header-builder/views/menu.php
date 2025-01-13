<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_View_Menu')) {

	#[AllowDynamicProperties]

	class Header_Builder_View_Menu {
		
		var $header_css;
		var $uniq_id;

		function __construct($css_class) {
			$this->header_css = $css_class;

			add_filter('header_builder_view_menu', array($this, 'render'), 10, 2);
		}

		public function render($args = array(), $mobile = false) {

			extract(
				$this->atts = shortcode_atts(
					array(
						'uniqid' => uniqid('navigation-'),
						'navigation' => '',
						'type' => 'visible_menu',
						'image' => '',
						'width' => '',
						'height' => '',
						'hover_type' => 'style1',
					),
					$args
				)
			);

      global $mobile_nav_atts;

      $mobile_nav_atts = [];

			$this->atts['block_css'] = array();
			$this->atts['block_css'][] = $this->uniq_id = $uniqid;
			$this->atts['block_css'][] = $type;
      $this->mobile = $mobile;

      $mobile_nav_atts['uniq_id'] = $this->uniq_id;
      $mobile_nav_atts['type'] = $type;

			if($hover_type) {
				$this->atts['block_css'][] = 'hover-'.$hover_type;
        $mobile_nav_atts['hover_type'] = 'hover-'.$hover_type;
			}

			$nav_args = array(
				'container' => 'ul', 
				'menu_class' => 'menu', 
				'link_before' => '<span>', 
				'link_after' => '</span>',
				'echo' => false,
				//'walker' => new YPRM_Nav_Walker()
			);

			if(!$navigation || $navigation == 'default') {
				$nav_args['theme_location'] = 'navigation';

				if(!has_nav_menu('navigation')) return false;
			} else {
				$nav_args['menu'] = (int) $navigation;
			}

      $mobile_nav_atts['nav_args'] = $nav_args;

			$this->atts['navigation_html'] = wp_nav_menu($nav_args);

			if(!$this->atts['navigation_html']) return false;

			$this->css($args);

			yprm_buildCSS(".$this->header_css .$uniqid .site-logo", array(
				'width' => $width,
				'height' => $height
			));

			if($type == 'visible_menu' || $type == 'hidden_menu') { ?>
        <?php if(!$mobile) { ?>
          <nav class="navigation<?php echo yprm_implode($this->atts['block_css']) ?>">
            <?php echo $this->atts['navigation_html'] ?>
          </nav>
        <?php } ?>
				<div class="butter-button nav-button <?php echo esc_attr($type) ?>" data-type=".<?php echo esc_attr($uniqid) ?>"><div></div></div>
			<?php } else if($type == 'centered_with_logo') { ?>
				<div class="center-navigation <?php echo esc_attr($uniqid) ?>">
          <?php if(!$mobile) { ?>
            <nav class="navigation on-left <?php echo yprm_implode($this->atts['block_css']) ?>">
              <?php echo $this->atts['navigation_html'] ?>
            </nav>
          <?php } ?>
					<?php if($image = yprm_get_image($image)) { ?>
						<div class="site-logo">
							<a href="<?php echo esc_url(home_url('/')) ?>" data-magic-cursor="link">
								<img src="<?php echo esc_url($image[0]) ?>" alt="<?php echo esc_attr(get_bloginfo('name')) ?>">
							</a>
						</div>
					<?php } ?>
          <?php if(!$mobile) { ?>
            <nav class="navigation on-right <?php echo yprm_implode($this->atts['block_css']) ?>">
              <?php echo $this->atts['navigation_html'] ?>
            </nav>
          <?php } ?>
				</div>
			<?php } else if($type == 'full_screen') { ?>
        <div class="butter-button nav-button <?php echo esc_attr($type) ?>" data-type=".<?php echo esc_attr($uniqid) ?>" data-mouse-magnetic="true" data-mouse-scale="1.4" data-hide-cursor="true"><div></div></div>
        <?php add_action('yprm_after_header', array($this, 'fullscreenNavigation')); ?>
      <?php }
		}

    public function fullscreenNavigation($uniq_id) {
      ?>
      <nav class="full-screen-nav main-row<?php echo esc_attr($uniq_id) ?>">
        <div class="fsn-container">
          <?php echo $this->atts['navigation_html'] ?>
        </div>
      </nav>
      <?php
    }

		public function css($args = array()) {
			extract(
				shortcode_atts(
					array(
						'uniqid' => $this->uniq_id,
						'color' => '',
						'current_page_color' => '',
						'submenu_color' => '',
						'submenu_bg_color' => '',
            'submenu_border_color' => '',
						'font_size' => '',
						'submenu_font_size' => '',
					),
					$args
				)
			);
			
			yprm_buildCSS(".$this->header_css .$uniqid > .menu > li > a, .mobile-navigation.$uniqid > .menu > li > a", array(
				'color' => $color,
        'font-size' => $font_size
			));
			yprm_buildCSS(".$this->header_css .$uniqid > .menu > li.current-menu-item > a,
			.$this->header_css .$uniqid > .menu > li.current-menu-ancestor > a,
			.$this->header_css .$uniqid > .menu > li.current_page_item > a,
			.$this->header_css .$uniqid > .menu > li.current_page_parent > a,
			.$this->header_css .$uniqid > .menu > li.current-menu-ancestor > a,
			.$this->header_css .$uniqid > .menu > li:hover > a,
      .mobile-navigation.$uniqid > .menu > li.current-menu-item > a,
			.mobile-navigation.$uniqid > .menu > li.current-menu-ancestor > a,
			.mobile-navigation.$uniqid > .menu > li.current_page_item > a,
			.mobile-navigation.$uniqid > .menu > li.current_page_parent > a,
			.mobile-navigation.$uniqid > .menu > li.current-menu-ancestor > a,
			.mobile-navigation.$uniqid > .menu > li:hover > a", array(
				'color' => $current_page_color
			));
			yprm_buildCSS(".$this->header_css .$uniqid .sub-menu, 
      .$this->header_css .$uniqid .children,
      .mobile-navigation.$uniqid .sub-menu, 
      .mobile-navigation.$uniqid .children", array(
				'background-color' => $submenu_bg_color,
				'color' => $submenu_color,
        'font-size' => $submenu_font_size,
        'border-color' => $submenu_border_color
			));
			yprm_buildCSS(".$this->header_css .$uniqid .sub-menu li, 
      .$this->header_css .$uniqid .children li,
      .mobile-navigation.$uniqid .sub-menu li, 
      .mobile-navigation.$uniqid .children li", array(
        'border-color' => $submenu_border_color
			));
		}
	}

	new Header_Builder_View_Menu($this->header_css);
}