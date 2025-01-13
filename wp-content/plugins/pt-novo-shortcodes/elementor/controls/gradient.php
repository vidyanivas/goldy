<?php 

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Gradient_Control extends Control_Base_Multiple {
	
	const GRADIENT = 'gradient';

	public function get_type() {
		return self::GRADIENT;
	}

	public function enqueue() {
		wp_register_style('pt-admin', YPRM_PLUGINS_HTTP . '/assets/css/admin.css', false, '1.0.0');
		wp_enqueue_style( 'pt-admin' );

		wp_register_style('gpickr', YPRM_PLUGINS_HTTP . '/assets/css/gpickr.min.css', false, '1.0.0');
		wp_enqueue_style( 'gpickr' );

		wp_register_script('gpickr', YPRM_PLUGINS_HTTP . '/assets/js/gpickr.min.js', ['jquery'], '1.0.0', true);
		wp_register_script('gradient-control', YPRM_PLUGINS_HTTP . '/elementor/js/gradient-control.js', ['gpickr', 'jquery'], '1.0.0', true);
		wp_enqueue_script('gradient-control');
	}

	/**
	 * Get media control default values.
	 *
	 * Retrieve the default value of the media control. Used to return the default
	 * values while initializing the media control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'gradient' => '',
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field yprm-gradient-editor">
			<label class="elementor-control-title">{{ data.label }}</label>
			<div class="clear-button e-color-picker__clear e-control-tool"><i class="eicon-undo"></i></div>
			<div class="gradient-button">
				<div></div>
			</div>
			<div class="gradient-editor"></div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	protected function get_default_settings() {
		return [
			'alpha' => true,
			'scheme' => '',
			'dynamic' => [
				'active' => true,
			],
			'global' => [
				'active' => true,
			],
		];
	}
}