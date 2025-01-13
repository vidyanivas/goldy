<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_Field_Textfield')) {

	class Header_Builder_Field_Textfield {
		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);

			add_filter('header_builder_field_textfield', array($this, 'render'));
		}

		public function enqueue_back() {}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'type' => '',
						'heading' => '',
						'param_name' => '',
						'placeholder' => '',
						'value' => '',
						'desc' => '',
						'dependency' => ''
					),
					$args
				)
			);
			?>
			<div class="header-field-item"<?php echo $dependency ? ' data-dependency-element="'.esc_attr($dependency['element']).'" data-dependency-value="'.esc_attr($dependency['value']).'"' : '' ?>>
				<div class="label"><?php echo strip_tags($heading) ?></div>
				<input class="input-block" name="<?php echo esc_attr($param_name) ?>" value="<?php echo esc_attr($value) ?>" placeholder="<?php echo esc_attr($placeholder) ?>" />
				<?php if($desc) { ?>
					<div class="desc"><?php echo wp_kses($desc, 'post') ?></div>
				<?php } ?>
			</div>
			<?php
		}
	}

	new Header_Builder_Field_Textfield();
}