<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('YPRM_Footer_Builder')) {
	class YPRM_Footer_Builder {
		
		var $settings;

		public function __construct() {
      add_action('init', array($this, 'register_post_type'));
      add_action('init', array($this, 'settings_fields'));

			add_action('yprm_render_footer', array($this, 'render'));
      add_filter('single_template', array($this, 'content_template'));

			$this->settings = array(
				'version' => '1.0.0',
				'url' => plugin_dir_url( __FILE__ ),
				'path' => plugin_dir_path( __FILE__ )
			);
		}

		public function register_post_type() {
			register_post_type('yprm_footer_builder', array(
				'labels' => array(
					'name' => esc_html__('Footers', 'pt-addons'),
					'singular_name' => esc_html__('Footer', 'pt-addons'),
					'add_new' => esc_html__('Add Footer', 'pt-addons'),
					'add_new_item' => esc_html__('Add New Footer', 'pt-addons'),
					'edit_item' => esc_html__('Edit Footer', 'pt-addons'),
					'new_item' => esc_html__('New Footer', 'pt-addons'),
					'view_item' => esc_html__('View Footer', 'pt-addons'),
					'search_items' => esc_html__('Search Footer', 'pt-addons'),
					'menu_name' => esc_html__('Footer Builder', 'pt-addons'),
				),
				'supports' => array('title', 'editor'),
				'public' => true,
				'menu_icon' => 'dashicons-editor-kitchensink',
				'publicly_queryable' => true,
				'query_var' => true,
				'menu_position' => 54,
			));
		}

		public function settings_fields() {
			if(!function_exists('register_field_group')) return;

			register_field_group(array(
				'id' => 'acf_footer-settings',
				'title' => esc_html__('Footer Settings', 'novo'),
				'fields' => array(
					array(
						'key' => 'field_2dec8e2eb269',
						'label' => esc_html__('Footer Background Color', 'novo'),
						'name' => 'footer_bg_color',
						'type' => 'color_picker',
					),
					array(
						'key' => 'field_59450627fa7e',
						'label' => esc_html__('Footer Text Color', 'novo'),
						'name' => 'footer_text_color',
						'type' => 'color_picker',
					),
          array (
            'key' => 'field_59b7de64cddee',
            'label' => 'Social buttons in footer',
            'name' => 'footer_social_buttons',
            'type' => 'select',
            'choices' => array (
              'default' => 'Default',
              'show' => 'Show',
              'hide' => 'Hide',
            ),
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
          ),
          array (
            'key' => 'field_58a43c22d66154',
            'label' => esc_html__('Scroll Up Button', 'novo'),
            'name' => 'footer_scroll_up',
            'type' => 'radio',
            'choices' => array (
              'show' => esc_html__('Show', 'novo'),
              'hide' => esc_html__('Hide', 'novo'),
            ),
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => 'show',
            'layout' => 'horizontal',
          ),
          array (
            'key' => 'field_2c367f476ac1',
            'label' => esc_html__('Decor Memphis', 'novo'),
            'name' => 'footer_decor',
            'type' => 'radio',
            'choices' => array (
              'show' => esc_html__('Show', 'novo'),
              'hide' => esc_html__('Hide', 'novo'),
            ),
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => 'show',
            'layout' => 'horizontal',
          ),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'yprm_footer_builder',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array(
					'position' => 'side',
					'layout' => 'default',
					'hide_on_screen' => array(
					),
				),
				'menu_order' => 0,
			));
		}

    public function content_template($template) {

      if(get_post_type() == 'yprm_footer_builder' && class_exists('Elementor\Plugin') && !\Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        $template = dirname(  __FILE__  ) . '/content.php';
      }
      return $template;
    }

		public function render($id) {
			$post_data = get_post($id);
			if(!$post_data || $post_data->post_type != 'yprm_footer_builder') return false;

			$block_css = array();
			$block_css[] = $footer_id = 'footer-'.$id;

			$footer_bg_color = '';
			$footer_text_color = '';
      $footer_scroll_up = 'show';
      $footer_decor = 'show';

			if(function_exists('get_field')) {
				$footer_bg_color = get_field('footer_bg_color', $id);
				$footer_text_color = get_field('footer_text_color', $id);
				$footer_scroll_up = get_field('footer_scroll_up', $id);
				$footer_decor = get_field('footer_decor', $id);
			}

      if($footer_decor != 'show') {
        $block_css[] = 'hide-decor';
      }

			yprm_buildCSS(".$footer_id", array(
				'background' => $footer_bg_color,
				'color' => $footer_text_color
			)); ?>
				<footer class="site-footer custom<?php echo yprm_implode($block_css) ?>">
          <?php if(class_exists('Elementor\Plugin') && Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor()) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content($id);
          } else { 
            if(function_exists('visual_composer')) {
				$css = visual_composer()->parseShortcodesCss($post_data->post_content, 'custom');
  
              if($css) {
                do_action('yprm_inline_css', $css);
              }
            }
          ?>
            <div class="container"><?php echo do_shortcode($post_data->post_content); ?></div>
          <?php } if($footer_scroll_up != 'hide') { ?>
            <div id="scroll-top" class="scroll-up-button basic-ui-icon-up-arrow"></div>
          <?php } ?>
				</footer>
			<?php
			wp_reset_postdata();
		}
	}

	new YPRM_Footer_Builder();
}
