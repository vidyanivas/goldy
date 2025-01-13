<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Elementor_Video_Widget extends Widget_Base {

	public function get_name() {
		return 'yprm_video';
	}

	public function get_title() {
		return esc_html__( 'Video', 'pt-addons' );
	}

	public function get_icon() {
		return 'pt-el-icon-video';
	}

	public function get_categories() {
		return [ 'novo-elements' ];
	}

	protected function register_controls() {

		// Style Tab
		$this->start_controls_section(
			'section_video_style',
			[
				'label' => __( 'Video', 'novo' ),
			]
		);

		$this->add_control(
			'video_url',
			[
				'label' => __( 'Video url', 'pt-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your URL', 'pt-addons' ),
				'description'	=> 'YouTube or Vimeo link',
				'label_block' => true,
			]
		);

		$this->add_control(
			'video_cover_image',
			[
				'label' => __( 'Video Cover Image', 'pt-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
        'label' => 'Images Quality',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_control(
			'height',
			[
				'label' => __( 'Height (px)', 'pt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 5,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .popup-item' => 'height: {{SIZE}}{{UNIT}}; padding-bottom: 0;',
				],
			]
		);

		$this->add_control(
			'heading',
			[
				'label' => __( 'Heading', 'pt-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'pt-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		// Style Tab
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Heading', 'novo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_hex',
			[
				'label' => __( 'Heading Color', 'novo' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .h' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .h',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sub_heading_style',
			[
				'label' => __( 'Sub Heading', 'novo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'options_color',
			[
				'label' => __( 'Text Color', 'novo' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'options_typography',
				'selector' => '{{WRAPPER}} .text',
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( '' == $settings['video_url'] ) {
			return false;
		}

		$this->add_render_attribute( 'video-wrapper', 'class', 'video-block popup-gallery' );

		if ( ! empty( $settings['heading'] ) || ! empty( $settings['text'] ) ) {
			$this->add_render_attribute( 'video-wrapper', 'class', 'with-content' );
		}

		if ( isset( $settings['height']['size'] ) && ! empty( $settings['height']['size'] ) ) {
			$this->add_render_attribute( 'video-wrapper', 'class', 'fix-height' );
		}

    $popup_array = [];
    $popup_array['video'] = [
      'html' => \VideoUrlParser::get_player($settings['video_url']),
      'w' => 1920,
      'h' => 1080
    ];
		?>
		<div <?php echo $this->get_render_attribute_string( 'video-wrapper' ); ?>>
			<div class="popup-item" style="<?php echo yprm_get_image( $settings['video_cover_image']['id'], 'bg', $settings['thumbnail_size'] ); ?>">
				<div class="area">
					<div class="content">
						<?php if ( $settings['heading'] ) : ?>
							<h2 class="h"><?php echo wp_kses_post( $settings['heading'] ); ?></h2>
						<?php endif; ?>
						<?php if ( $settings['text'] ) : ?>
							<div class="text"><?php echo wp_kses_post( $settings['text'] ); ?></div>
						<?php endif; ?>
					</div>
					<a href="#" data-type="video" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="0">
						<div><i class="music-and-multimedia-play-button"></i></div>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}