<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Selectize_Control extends Base_Data_Control {

  const SELECTIZE = 'selectize';

  public function get_type() {
    return self::SELECTIZE;
  }

  public function enqueue() {
    wp_register_style('selectize', YPRM_PLUGINS_HTTP . '/assets/css/selectize.css', false, '0.13.3');
    wp_enqueue_style('selectize');

    wp_register_script('selectize', YPRM_PLUGINS_HTTP . '/assets/js/selectize.min.js', ['jquery'], '0.13.3', true);
    wp_register_script('selectize-control', YPRM_PLUGINS_HTTP . '/elementor/js/selectize-control.js', ['selectize'], '1.0.0', true);
    wp_enqueue_script('selectize-control');
  }

  protected function get_default_settings() {
		return [
			'label_block' => true,
		];
	}

  public function get_default_value() {
    return [
      'selectize' => '',
    ];
  }

  public function content_template() {
    $control_uid = $this->get_control_uid();
    ?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2" multiple data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
  }
}