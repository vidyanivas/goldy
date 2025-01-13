<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_Field_Image_Picker')) {

	class Header_Builder_Field_Image_Picker {
		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);

			add_filter('header_builder_field_image_picker', array($this, 'render'));
		}

		public function enqueue_back() {
			wp_enqueue_media();

			wp_enqueue_script('pt-header-builder-image-picker', plugin_dir_url( __FILE__ ) . '/js/scripts.js');
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

			$bg_image = '';

			if($value) {
				$bg_image = yprm_get_image($value, 'bg', 'medium');
			}
			?>
			<div class="header-field-item" data-param="<?php echo esc_attr($param_name) ?>" data-param-value="<?php echo esc_attr($value) ?>"<?php echo $dependency ? ' data-dependency-element="'.esc_attr($dependency['element']).'" data-dependency-value="'.esc_attr($dependency['value']).'"' : '' ?>>
				<div class="label"><?php echo strip_tags($heading) ?></div>
				<div class="image<?php echo $value ? ' selected' : '' ?>">
					<div class="cross">
						<i class="base-icon-close"></i>
					</div>
					<i class="base-icon-plus"></i>
					<div class="img"<?php echo $bg_image ? ' style="'.$bg_image.'"' : '' ?>></div>
				</div>
				<?php if($desc) { ?>
					<div class="desc"><?php echo wp_kses($desc, 'post') ?></div>
				<?php } ?>
			</div>
			<?php
		}
	}

	new Header_Builder_Field_Image_Picker();
}