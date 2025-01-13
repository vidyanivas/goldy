<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Header_Builder_Field_Switch')) {

	class Header_Builder_Field_Switch {
		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);

			add_filter('header_builder_field_switch', array($this, 'render'));
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
			);

			$isChecked = false;
			$i = 0;

			foreach($options as $key => $option) {
				if($value == $key && $i == 1) {
					$isChecked = true;
				} else {
					$isChecked = false;
				}

				$i++;
			}

			?>
			<div class="header-field-item"<?php echo $dependency ? ' data-dependency-element="'.esc_attr($dependency['element']).'" data-dependency-value="'.esc_attr($dependency['value']).'"' : '' ?>>
				<div class="label"><?php echo strip_tags($heading) ?></div>
				<label class="switch-block">
					<input type="checkbox" name="<?php echo esc_attr($param_name) ?>"<?php echo $isChecked ? ' checked' : '' ?>>
					<span>
						<?php foreach($options as $key => $option) { ?>
							<span data-value="<?php echo esc_attr($key) ?>"><?php echo $option ?></span>
						<?php } ?>
					</span>
				</label>
				<?php if($desc) { ?>
					<div class="desc"><?php echo wp_kses($desc, 'post') ?></div>
				<?php } ?>
			</div>
			<?php
		}
	}

	new Header_Builder_Field_Switch();
}