<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('YPRM_Header_Builder')) {
	class YPRM_Header_Builder {
		
		var $settings;

		public function __construct() {
      add_action('init', array($this, 'include_params'));
      add_action('init', array($this, 'register_post_type'));
      add_action('init', array($this, 'register_field_group'));
      add_action('acf/include_field_types', array($this, 'include_field'));
      add_action('init', array($this, 'frontend_builder'));
      add_filter('single_template', array($this, 'content_template'));

			$this->settings = array(
				'version' => '1.0.0',
				'url' => plugin_dir_url( __FILE__ ),
				'path' => plugin_dir_path( __FILE__ )
			);
		}

		public function register_post_type() {
			register_post_type('yprm_header_builder', array(
				'labels' => array(
					'name' => esc_html__('Headers', 'pt-addons'),
					'singular_name' => esc_html__('Header', 'pt-addons'),
					'add_new' => esc_html__('Add Header', 'pt-addons'),
					'add_new_item' => esc_html__('Add New Header', 'pt-addons'),
					'edit_item' => esc_html__('Edit Header', 'pt-addons'),
					'new_item' => esc_html__('New Header', 'pt-addons'),
					'view_item' => esc_html__('View Header', 'pt-addons'),
					'search_items' => esc_html__('Search Header', 'pt-addons'),
					'menu_name' => esc_html__('Header Builder', 'pt-addons'),
				),
				'supports' => array('title'),
				'public' => true,
				'menu_icon' => 'dashicons-welcome-widgets-menus',
				'menu_position' => 54,
			));
		}

    public function content_template($template) {
      if(get_post_type() == 'yprm_header_builder') {
        $template = dirname(  __FILE__  ) . '/content.php';
      }
      return $template;
    }

		public function register_field_group() {
			if(function_exists('acf_add_local_field_group')) {
				acf_add_local_field_group(array(
					'key' => 'group_5f5f45fdcdd13',
					'title' => esc_html__('Options', 'pt-addons'),
					'fields' => array(
						array(
							'key' => 'field_5f5f4600d87f3',
							'label' => esc_html__('Header Builder', 'pt-addons'),
							'name' => 'header_builder',
							'type' => 'yprm_header_builder',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
						),
					),
					'location' => array(
						array(
							array(
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'yprm_header_builder',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => true,
					'description' => '',
				));
			}
		}

		public function include_field() {
			include_once('acf-field.php');
		}

		public function include_params() {
			include_once('params/image_picker/image_picker.php');
			include_once('params/colorpicker/colorpicker.php');
			include_once('params/textarea.php');
			include_once('params/textfield.php');
			include_once('params/dropdown.php');
			include_once('params/switch.php');
		}

		public function frontend_builder() {
			include_once('frontend.php');
		}
	}
}

new YPRM_Header_Builder();
