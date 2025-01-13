<?php 

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Group_Control_Background_Video extends Group_Control_Base {

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
		return 'background_video';
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

		$fields['video_url'] = [
			'label' => esc_html__( 'Background Video Url', 'pt-addons' ),
			'type' => Controls_Manager::TEXT,
			'description' => esc_html__( 'Source YouTube, Vimeo and mp4 file', 'pt-addons' ),
			'label_block' => true,
		];
		$fields['background_video_controls'] = [
			'label' => esc_html__( 'Control Buttons', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
			'condition' => [
				'video!' => ''
			]
		];
		$fields['background_video_mute'] = [
			'label' => esc_html__( 'Mute', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'on',
			'condition' => [
				'video!' => ''
			]
		];

		return $fields;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the background video control. Used to return the
	 * default options while initializing the background video control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Default background video control options.
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'background_video',
				'starter_title' => _x( 'Background Video', 'Video Control', 'pt-addons' ),
			],
		];
	}
}