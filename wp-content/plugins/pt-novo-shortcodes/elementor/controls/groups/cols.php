<?php 

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Group_Control_Cols extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the cols control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Cols control fields.
	 */
	protected static $fields;

	/**
	 * Get cols control type.
	 *
	 * Retrieve the control type, in this case `cols`.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'cols';
	}

	/**
	 * Init fields.
	 *
	 * Initialize cols control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function init_fields() {
		$fields = [];

		$fields['xs'] = [
			'label' => esc_html__( 'Small Phone', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 4,
			'step' => 1,
			'description' => esc_html__('Extra small <576px', 'pt-addons'),
			'frontend_available' => true
		];
		$fields['sm'] = [
			'label' => esc_html__( 'Phone', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 12,
			'step' => 1,
			'description' => esc_html__('Small ≥576px', 'pt-addons'),
			'frontend_available' => true
		];
		$fields['md'] = [
			'label' => esc_html__( 'Tablet', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 12,
			'step' => 1,
			'description' => esc_html__('Medium ≥768px', 'pt-addons'),
			'frontend_available' => true
		];
		$fields['lg'] = [
			'label' => esc_html__( 'Laptop', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 12,
			'step' => 1,
			'description' => esc_html__('Large ≥992px', 'pt-addons'),
			'frontend_available' => true
		];
		$fields['xl'] = [
			'label' => esc_html__( 'Desktop', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 12,
			'step' => 1,
			'description' => esc_html__('Extra large ≥1200px', 'pt-addons'),
			'frontend_available' => true
		];

		return $fields;
	}
}