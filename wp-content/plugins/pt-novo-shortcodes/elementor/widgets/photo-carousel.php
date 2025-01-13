<?php

namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Photo_Carousel_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'photo-carousel-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/photo-carousel.js', array( 'jquery', 'elementor-frontend' ), '', true );
	}

	public function get_name() {
		return 'yprm_photo_carousel';
	}

	public function get_title() {
		return esc_html__( 'Photo Carousel ', 'pt-addons' );
	}

	public function get_icon() {
		return 'pt-el-icon-photo-carousel';
	}

	public function get_categories() {
		return [ 'novo-elements' ];
	}

	public function get_script_depends() {
		return [ 'photo-carousel-handler' ];
	}

	public function get_keywords() {
		return [ 'Gallery', 'Carousel', 'photo', 'pt', 'novo' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Image Gallery', 'elementor' ),
			]
		);

		$this->add_control(
			'images',
			[
				'label' => __( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
        'label' => 'Images Quality',
				'exclude' => [ 'custom' ],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_btn',
			[
				'label' => __( 'Button', 'elementor' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Button Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => __( 'Link text', 'novo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

        $this->add_control(
			'link',
			[
				'label' => __( 'Link', 'novo' ),
				'type' => \Elementor\Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'dynamic' => [
					'active' => true,
				  ],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'slider_settings_section', [
                'label' => __( 'Carousel Settings', 'novo' ),
            ]
        );

        $this->add_control(
			'speed',
			[
				'label'        => __( 'Transition speed', 'novo' ),
				'type'         => Controls_Manager::NUMBER,
				'default'      => 300,
				"min" 			=> 100,
				"max" 			=> 10000,
				"step" 			=> 100,
			]
		);

        $this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay Slides', 'novo' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			]
		);

        $this->add_control(
			'autoplay_speed',
			[
				'label'        => __( 'Autoplay Speed', 'novo' ),
				'type'         => Controls_Manager::NUMBER,
				'default'      => 5000,
				"min" 			=> 100,
				"max" 			=> 10000,
				"step" 			=> 10
			]
		);

        $this->add_control(
			'arrows',
			[
				'label'        => __( 'Navigation Arrows', 'novo' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			]
		);

		$this->add_control(
			'infinite_loop',
			[
				'label'        => __( 'Infinite loop', 'novo' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .photo-carousel > a .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .photo-carousel > a .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .photo-carousel > a .elementor-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => __( 'Primary Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .photo-carousel > a .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .photo-carousel > a .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .photo-carousel > a .elementor-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_responsive_control(
			'size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'button_style_section',
            [
                'label'     => __( 'Button', 'novo' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .load-button a.loadmore-button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => __( 'Button Color', 'novo' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .photo-carousel>a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => __( 'Button Hover Color', 'novo' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .photo-carousel>a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'button_border',
                'selector'  => '{{WRAPPER}} .photo-carousel>a',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __( 'Border Radius', 'novo' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .photo-carousel>a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_text_padding',
            [
                'label'      => __( 'Text Padding', 'novo' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .photo-carousel>a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['images'] ) {
			return;
		}

		$ids = wp_list_pluck( $settings['images'], 'id' );

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
		$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

	    $this->add_render_attribute( 'wrapper', 'class', 'photo-carousel' );

	    $this->add_render_attribute(
			[
				'wrapper' => [
					'data-banner-settings' => [
						wp_json_encode(array_filter([
							"loop"            	 => ("yes" == $settings["infinite_loop"]) ? true : false,
							"autoplay"           => ("yes" == $settings["autoplay"]) ? true : false,
							"arrows"             => ("yes" == $settings["arrows"]) ? true : false,
							"autoplay_speed"     => $settings['autoplay_speed'],
							"speed"     		 => $settings['speed']
				        ] ) )
					]
				]
			]
		);

	    ?>
	    	<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
	    		<?php
				if ( isset( $settings['link']['url'] ) && $settings['link_text'] ) :
					$target = $settings['link']['is_external'] ? ' target="_blank"' : '';
					$nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
						<a class="button-style1" href="<?php echo esc_url( $settings['link']['url'] ); ?>"<?php echo $target; ?> <?php echo $nofollow; ?>>
							<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
							<?php echo esc_html( $settings['link_text'] ); ?>
						</a>
					<?php
				endif;
				?>
				<div class="carousel">
					<?php foreach ( $settings['images'] as $image ) :
						if ( ! isset( $image['id'] ) || empty( $image['id'] ) ) { continue; }
						$img_url = wp_get_attachment_image_url( $image['id'], $settings['thumbnail_size'] );
					?>
				    	<div class="item" style="background-image: url(<?php echo esc_url( $img_url ); ?> );"></div>
				    <?php endforeach; ?>
				</div>
	    	</div>	
	    <?php
	}
}
