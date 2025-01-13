<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Elementor_Icon_Box_Widget extends Widget_Base {

	public function get_name() {
		return 'yprm_icon_box';
	}

	public function get_title() {
		return esc_html__( 'Icon Box', 'pt-addons' );
	}

	public function get_icon() {
		return 'pt-el-icon-icon-box';
	}

	public function get_categories() {
		return [ 'novo-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'pt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

    $this->add_control(
      'style',
      [
        'label' => esc_html__( 'Style', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'style1'  => esc_html__( 'Style 1', 'pt-addons' ),
          'style2'  => esc_html__( 'Style 2', 'pt-addons' ),
          'style3'  => esc_html__( 'Style 3', 'pt-addons' ),
          'style4'  => esc_html__( 'Style 4', 'pt-addons' ),
        ],
        'default' => 'style1',
      ]
    );

		$this->add_group_control(
			Group_Control_Cols::get_type(),
			[
				'name' => 'cols',
				'label' => esc_html__( 'Cols', 'pt-addons' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon Box', 'pt-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'pt-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'pt-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true
			]
		);

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'pt-addons' ),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

    $repeater->add_control(
      'link',
      [
        'label' => esc_html__( 'Link', 'pt-addons' ),
        'type' => Controls_Manager::URL,
		'dynamic' => [
			'active' => true,
		  ],
      ]
    );

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Items', 'pt-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'pt-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => __( 'Normal', 'pt-addons' ),
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Primary Color', 'pt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon-box .icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => __( 'Hover', 'pt-addons' ),
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => __( 'Primary Color', 'pt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon-box .icon:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'pt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .icon-box .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'pt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .icon-box .icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->add_control(
			'rotate',
			[
				'label' => __( 'Rotate', 'pt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .icon-box .icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'pt-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'pt-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'pt-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'pt-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'pt-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'pt-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .icon-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'pt-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bottom_space',
			[
				'label' => __( 'Spacing', 'pt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .icon-box .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'pt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon-box .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .icon-box .title',
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => __( 'Description', 'pt-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'pt-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon-box .desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .icon-box .desc',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute('block', 'class', 'icon-box-block');

    if(isset($settings['style'])) {
      $this->add_render_attribute('block', 'class', 'icon-box-'.$settings['style']);
    }

		$this->add_render_attribute('item', 'class', [
			'icon-box',
		]);

		$this->add_render_attribute('col', 'class', yprm_el_cols($settings) );

		ob_start();
		?>
			<div <?php echo $this->get_render_attribute_string('block') ?>>
				<div class="row">
					<?php $this->get_elements($settings) ?>
				</div>
			</div>
		<?php

		echo ob_get_clean();
	}

	public function get_elements($settings) {
		foreach( $settings['items'] as $item ) { 
      $link_key = '';

      if(isset($item['link']['url']) && $item['link']['url']) {
        $link_key = uniqid('link');

        $this->add_link_attributes($link_key, $item['link']);
      } ?>
			<div <?php echo $this->get_render_attribute_string('col') ?>>
				<div <?php echo $this->get_render_attribute_string('item') ?>>
					<?php $this->get_item_icon($item) ?>
          <?php if($settings['style'] == 'style4') { ?>
            <div class="ct">
              <?php $this->get_item_title($item) ?>
              <?php $this->get_item_desc($item) ?>
            </div>
          <?php } else { ?>
            <?php $this->get_item_title($item) ?>
            <?php $this->get_item_desc($item) ?>
          <?php } ?>
          <?php if($link_key) { ?>
            <a <?php echo $this->get_render_attribute_string($link_key) ?>></a>
          <?php } ?>
				</div>
			</div>
		<?php
		}
	}

	public function get_item_icon( $item ) {

		if ( ! isset( $item['icon'] ) && ! \Icons_Manager::is_migration_allowed() ) {
			// add old default
			$item['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $item['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $item['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $item['selected_icon']['value'] ) ) {
			$has_icon = true;
		}

		$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $item['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( $has_icon ) : ?>
			<div class="icon">
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
				} elseif ( ! empty( $item['icon'] ) ) {
					?><i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i><?php
				}
				?>
			</div>
		<?php endif;
	}

	public function get_item_title( $atts ) {
		if(!empty($atts['title'])) { ?>
			<h5 class="title"><?php echo nl2br($atts['title']) ?></h5>
		<?php }
	}

	public function get_item_desc( $atts ) {
		if(!empty($atts['text'])) { ?>
			<div class="desc"><?php echo wp_kses_post( nl2br($atts['text']) ); ?></div>
		<?php }
	}
}	