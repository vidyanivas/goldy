<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_Field_Color_Picker')) {

	class Header_Builder_Field_Color_Picker {
		public function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);

			add_filter('header_builder_field_colorpicker', array($this, 'render'));
		}

		public function enqueue_back() {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('pt-header-builder-colorpicker', plugin_dir_url(__FILE__) . '/js/scripts.js');
		}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'type' => '',
						'heading' => '',
						'param_name' => '',
						'desc' => '',
						'value' => '',
						'dependency' => ''
					),
					$args
				)
			);

			?>
			<div class="header-field-item"<?php echo $dependency ? ' data-dependency-element="'.esc_attr($dependency['element']).'" data-dependency-value="'.esc_attr($dependency['value']).'"' : '' ?>>
				<div class="label"><?php echo strip_tags($heading) ?></div>
				<input class="iris_color" type="text" name="<?php echo esc_attr($param_name) ?>" value="<?php echo esc_attr($value) ?>">
			</div>
			<?php
}
	}

	new Header_Builder_Field_Color_Picker();
}