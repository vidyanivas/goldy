<?php 

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Group_Control_Background_Overlay extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the background control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Background control fields.
	 */
	protected static $fields;

	/**
	 * Get background control type.
	 *
	 * Retrieve the control type, in this case `background`.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'background_overlay';
	}

	/**
	 * Init fields.
	 *
	 * Initialize background control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function init_fields() {
		$fields = [];

		$fields['background_color'] = [
			'label' => esc_html__( 'Background Color', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
		];

		$fields['hr'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['background_image'] = [
			'label' => esc_html__( 'Background Image', 'pt-addons' ),
			'type' => Controls_Manager::MEDIA,
		];

		$fields['background_size'] = [
			'label' => esc_html__( 'Background Size', 'pt-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'auto' => esc_html__( 'Auto', 'pt-addons' ),
				'contain' => esc_html__( 'Contain', 'pt-addons' ),
				'cover' => esc_html__( 'Cover', 'pt-addons' ),
			],
			'default' => 'cover',
			'condition' => [
				'background_image[url]!' => ''
			]
		];

		$fields['background_position'] = [
			'label' => esc_html__( 'Background Position', 'pt-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'0% 0%' => esc_html__( 'Top Left', 'pt-addons' ),
				'50% 0%' => esc_html__( 'Top Center', 'pt-addons' ),
				'100% 0%' => esc_html__( 'Top Right', 'pt-addons' ),
				'0% 50%' => esc_html__( 'Center Left', 'pt-addons' ),
				'50% 50%' => esc_html__( 'Center', 'pt-addons' ),
				'100% 50%' => esc_html__( 'Center Right', 'pt-addons' ),
				'0% 100%' => esc_html__( 'Bottom Left', 'pt-addons' ),
				'50% 100%' => esc_html__( 'Bottom Center', 'pt-addons' ),
				'100% 100%' => esc_html__( 'Bottom Right', 'pt-addons' ),
			],
			'default' => '50% 50%',
			'condition' => [
				'background_image[url]!' => ''
			]
		];

		/* $fields['hr2'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['background_parallax'] = [
			'label' => esc_html__( 'Background Parallax', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
		]; */

		$fields['hr3'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['color_overlay'] = [
			'label' => esc_html__( 'Color Overlay', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
		];

		$fields['color_overlay_hex'] = [
			'label' => esc_html__( 'Color', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'color_overlay' => 'on'
			]
		];

		$fields['color_overlay_opacity'] = [
			'label' => esc_html__( 'Opacity (%)', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
			'max' => 100,
			'default' => 20,
			'condition' => [
				'color_overlay' => 'on'
			]
		];

		$fields['hr4'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['gradient_overlay'] = [
			'label' => esc_html__( 'Gradient Overlay', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
		];

		$fields['gradient_overlay_hex'] = [
			'label' => esc_html__( 'Gradient Color', 'pt-addons' ),
			'type' => Gradient_Control::GRADIENT,
			'condition' => [
				'gradient_overlay' => 'on'
			]
		];

		$fields['hr5'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['decor_lines_overlay'] = [
			'label' => esc_html__( 'Decor Lines', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
		];

		$fields['decor_lines_overlay_color'] = [
			'label' => esc_html__( 'Decor Lines Color', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'decor_lines_overlay' => 'on'
			]
		];

		/* $fields['hr6'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['wave_overlay'] = [
			'label' => esc_html__( 'Wave Background', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'pt-addons' ),
			'label_off' => esc_html__( 'Hide', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
		];

		$fields['wave_overlay_color1'] = [
			'label' => esc_html__( 'Wave Background Color 1', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'wave_overlay' => 'on'
			]
		];

		$fields['wave_overlay_color2'] = [
			'label' => esc_html__( 'Wave Background Color 2', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'wave_overlay' => 'on'
			]
		];

		$fields['wave_overlay_color3'] = [
			'label' => esc_html__( 'Wave Background Color 3', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'wave_overlay' => 'on'
			]
		]; */

		$fields['hr7'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['text_overlay'] = [
			'label' => esc_html__( 'Text', 'pt-addons' ),
			'type' => Controls_Manager::TEXTAREA,
		];

		$fields['text_overlay_color'] = [
			'label' => esc_html__( 'Color', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['text_overlay_stroke_color'] = [
			'label' => esc_html__( 'Stroke Color', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['text_overlay_align'] = [
			'label' => esc_html__( 'Text Align', 'pt-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'start' => esc_html__( 'Left', 'pt-addons' ),
				'middle' => esc_html__( 'Center', 'pt-addons' ),
				'end' => esc_html__( 'Right', 'pt-addons' ),
			],
			'default' => 'middle',
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['text_overlay_transform'] = [
			'label' => esc_html__( 'Text Transform', 'pt-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'' => esc_html__( 'Default', 'pt-addons' ),
				'capitalize' => esc_html__( 'Capitalize', 'pt-addons' ),
				'lowercase' => esc_html__( 'Lowercase', 'pt-addons' ),
				'uppercase' => esc_html__( 'Uppercase', 'pt-addons' ),
			],
			'default' => '',
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['text_overlay_fs'] = [
			'label' => esc_html__( 'Font Size', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 10,
			'max' => 300,
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['text_overlay_top_offset'] = [
			'label' => esc_html__( 'Top Offset', 'pt-addons' ),
			'type' => Controls_Manager::TEXT,
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['text_overlay_left_offset'] = [
			'label' => esc_html__( 'Left Offset', 'pt-addons' ),
			'type' => Controls_Manager::TEXT,
			'condition' => [
				'text_overlay!' => ''
			]
		];

		$fields['hr8'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['decor_lines_on_bottom_overlay'] = [
			'label' => esc_html__( 'Decor Lines on Bottom', 'pt-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'On', 'pt-addons' ),
			'label_off' => esc_html__( 'Off', 'pt-addons' ),
			'return_value' => 'on',
			'default' => 'no',
		];

		$fields['decor_lines_on_bottom_overlay_color'] = [
			'label' => esc_html__( 'Decor Lines Color', 'pt-addons' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'decor_lines_on_bottom_overlay' => 'on'
			]
		];

		$fields['hr9'] = [
			'type' => Controls_Manager::DIVIDER,
		];

		$fields['background_offset_top'] = [
			'label' => esc_html__( 'Top Offset (px)', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
		];

		$fields['background_offset_left'] = [
			'label' => esc_html__( 'Left Offset (px)', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
		];

		$fields['background_offset_right'] = [
			'label' => esc_html__( 'Right Offset (px)', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
		];

		$fields['background_offset_bottom'] = [
			'label' => esc_html__( 'Bottom Offset (px)', 'pt-addons' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
		];

		return $fields;
	}
}