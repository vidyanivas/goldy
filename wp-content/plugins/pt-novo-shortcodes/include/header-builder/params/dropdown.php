<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_Field_Dropdown')) {

	class Header_Builder_Field_Dropdown {
		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);

			add_filter('header_builder_field_dropdown', array($this, 'render'));
		}

		public function enqueue_back() {}

		public function render($args = array()) {
			extract(
				shortcode_atts(
					array(
						'type' => '',
						'heading' => '',
						'param_name' => '',
						'value' => '',
						'desc' => '',
						'options' => array(),
						'dependency' => ''
					),
					$args
				)
			); ?>
			<div class="header-field-item"<?php echo $dependency ? ' data-dependency-element="'.esc_attr($dependency['element']).'" data-dependency-value="'.esc_attr($dependency['value']).'"' : '' ?>>
				<div class="label"><?php echo strip_tags($heading) ?></div>
				<select class="select-item" name="<?php echo esc_attr($param_name) ?>">
					<?php if(count($options) > 0) { ?>
						<?php foreach($options as $label => $option) { ?>
							<option value="<?php echo strip_tags($option) ?>"<?php echo $option == $value ? ' selected' : '' ?>><?php echo esc_attr($label) ?></option>
						<?php } ?>
					<?php } ?>
				</select>
				<?php if($desc) { ?>
					<div class="desc"><?php echo wp_kses($desc, 'post') ?></div>
				<?php } ?>
			</div>
			<?php
		}
	}

	new Header_Builder_Field_Dropdown();
}