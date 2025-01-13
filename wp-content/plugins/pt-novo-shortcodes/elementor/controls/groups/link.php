<?php 

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Group_Control_Link extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the link control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Link control fields.
	 */
	protected static $fields;

	/**
	 * Get link control type.
	 *
	 * Retrieve the control type, in this case `link`.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'link';
	}

	/**
	 * Init fields.
	 *
	 * Initialize link control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function init_fields() {
		$fields = [];

		$fields['url'] = [
			'label' => esc_html__( 'Url', 'pt-addons' ),
			'type' => Controls_Manager::URL,
			'show_external' => false,
		];

		$fields['label'] = [
			'label' => esc_html__( 'Label', 'pt-addons' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
		];

		return $fields;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the link control. Used to return the
	 * default options while initializing the link control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Default link control options.
	 */
	/* protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'link',
				'starter_title' => _x( 'Link', 'Link Control', 'elementor' ),
			],
		];
	} */
}