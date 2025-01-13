<?php 

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Group_Control_Swiper extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the swiper control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Swiper control fields.
	 */
	protected static $fields;

	/**
	 * Get swiper control type.
	 *
	 * Retrieve the control type, in this case `swiper`.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'swiper';
	}

	/**
	 * Init fields.
	 *
	 * Initialize swiper control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function init_fields() {
		$fields = [];

		$fields['loop'] = [
			'label' => esc_html__( 'Loop', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'On', 'pt-addons' ),
			'label_off' => esc_html__( 'Off', 'pt-addons' ),
			'return_value' => 'yes',
			'default' => 'yes',
			'frontend_available' => true
		];

		
		$fields['autoplay'] = [
			'label' => esc_html__( 'Autoplay', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'On', 'pt-addons' ),
			'label_off' => esc_html__( 'Off', 'pt-addons' ),
			'return_value' => 'yes',
			'default' => 'no',
			'frontend_available' => true
		];

		
		$fields['autoplay_timeout'] = [
			'label' => esc_html__( 'Autoplay TimeOut', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'default' => 5000,
			'description' => esc_html__('ms', 'pt-addons'),
			'frontend_available' => true,
			'condition' => [
				'autoplay' => 'yes',
			],
		];
	
		$fields['arrows'] = [
			'label' => esc_html__( 'Arrows', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'On', 'pt-addons' ),
			'label_off' => esc_html__( 'Off', 'pt-addons' ),
			'return_value' => 'yes',
			'default' => 'yes',
			'frontend_available' => true
		];
		
		$fields['pagination'] = [
			'label' => esc_html__( 'Pagination', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'On', 'pt-addons' ),
			'label_off' => esc_html__( 'Off', 'pt-addons' ),
			'return_value' => 'yes',
			'default' => 'yes',
			'frontend_available' => true
		];

		return $fields;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the swiper control. Used to return the
	 * default options while initializing the swiper control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Default swiper control options.
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'swiper',
				'starter_title' => _x( 'Swiper', 'Swiper Control', 'elementor' ),
			],
		];
	}
}