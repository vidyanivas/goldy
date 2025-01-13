<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_Field_Textarea')) {

	class Header_Builder_Field_Textarea {
		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);

			add_filter('header_builder_field_textarea', array($this, 'render'));
		}

		public function enqueue_back() {}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'type' => '',
						'heading' => '',
						'param_name' => '',
						'rows' => '3',
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
				<textarea class="textarea-block" rows="<?php echo esc_attr($rows) ?>" name="<?php echo esc_attr($param_name) ?>"><?php echo $value ?></textarea>
				<?php if($desc) { ?>
					<div class="desc"><?php echo wp_kses($desc, 'post') ?></div>
				<?php } ?>
			</div>
			<?php
		}
	}

	new Header_Builder_Field_Textarea();
}