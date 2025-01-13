<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Button_Widget extends Widget_Base {

  public function get_name() {
    return 'yprm_button';
  }

  public function get_title() {
    return esc_html__('Button', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-button';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Content', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_responsive_control(
      'align',
      [
        'label' => __('Alignment', 'novo'),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => __('Left', 'novo'),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => __('Center', 'novo'),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => __('Right', 'novo'),
            'icon' => 'eicon-text-align-right',
          ],
        ],
        'default' => 'left',
        'selectors' => [
          '{{WRAPPER}} .button-container' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'style',
      [
        'label' => esc_html__( 'Style', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'style1'  => esc_html__( 'Button', 'pt-addons' ),
          'style2'  => esc_html__( 'Link', 'pt-addons' ),
        ],
        'default' => 'style1',
      ]
    );

    $this->add_group_control(
      Group_Control_Link::get_type(),
      [
        'name' => 'link',
        'label' => esc_html__('Link', 'pt-addons'),
        'dynamic' => [
          'active' => true,
          ],
      ]
    );

    $this->add_control(
      'hr',
      [
        'type' => Controls_Manager::DIVIDER,
      ]
    );

    $this->add_control(
      'with_icon',
      [
        'label' => esc_html__('Icon', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'pt-addons'),
        'label_off' => esc_html__('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'no',
        'condition' => [
          'style' => 'style1'
        ]
      ]
    );

    $this->add_control(
      'icon',
      [
        'label' => esc_html__('Icon', 'pt-addons'),
        'type' => Controls_Manager::ICONS,
        'default' => [
          'value' => 'fas fa-star',
          'library' => 'solid',
        ],
        'condition' => [
          'with_icon' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'icon_position',
      [
        'label' => esc_html__('Position', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__('After', 'pt-addons'),
        'label_off' => esc_html__('Before', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'no',
        'condition' => [
          'with_icon' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'customization',
      [
        'label' => esc_html__('Customization', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'button_background_color',
      [
        'label' => __('Background Color', 'marlon-elementor'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor' => 'background: {{VALUE}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'padding',
      [
        'label' => esc_html__('Padding', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'range' => [
          'px' => [
            'max' => 160,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .button-elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'border_radius',
      [
        'label' => esc_html__('Border Radius', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px', 'em'],
        'range' => [
          'px' => [
            'max' => 60,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .button-elementor' => 'border-radius: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .button-elementor:before' => 'border-radius: {{SIZE}}{{UNIT}};',
        ],

      ]
    );

    $this->add_responsive_control(
      'border_width',
      [
        'label' => esc_html__('Border Width', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px', 'em'],
        'range' => [
          'px' => [
            'max' => 60,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .button-elementor, {{WRAPPER}} .button-elementor:before' => 'border-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          'style!' => ['style1', 'style2'],
        ],
      ]
    );

    $this->add_control(
      'border_color',
      [
        'label' => esc_html__('Border Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor, {{WRAPPER}} .button-elementor:before' => 'border-color: {{VALUE}}; opacity: 1;',
        ],
        'condition' => [
          'style!' => ['style1', 'style2', 'style4'],
        ],
      ]
    );

    $this->add_control(
      'background_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor:before' => 'background-color: {{VALUE}}; opacity: 1;',
        ],
        'condition' => [
          'style!' => 'style4',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'typography_customization',
      [
        'label' => esc_html__('Typography', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'label' => esc_html__('Typography', 'pt-addons'),
        'selector' => '{{WRAPPER}} .button-elementor',
        'fields_options' => [
          'font_size' => [
            'range' => [
              'px' => [
                'min' => 12,
                'max' => 60,
              ],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor:not(:hover)' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'icon_customization',
      [
        'label' => esc_html__('Icon Customization', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'with_icon' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'icon_color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} i' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'icon_size',
      [
        'label' => esc_html__('Size', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px', 'em', 'vh'],
        'selectors' => [
          '{{WRAPPER}} i' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'icon_rotation',
      [
        'label' => esc_html__('Rotation (deg)', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 360,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} i' => 'transform: rotate({{SIZE}}deg);',
        ],
      ]
    );

    $this->add_responsive_control(
      'icon_margin',
      [
        'label' => esc_html__('Margin', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'selectors' => [
          '{{WRAPPER}} i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'hover_customization',
      [
        'label' => esc_html__('Hover Colors', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'hover_color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor:hover' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'icon_hover_color',
      [
        'label' => esc_html__('Icon', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor:hover i' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'with_icon' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'hover_border_color',
      [
        'label' => esc_html__('Border', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor:hover:before' => 'border-color: {{VALUE}}; opacity: 1;',
        ],
      ]
    );

    $this->add_control(
      'hover_bg_color',
      [
        'label' => esc_html__('Background', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-elementor:hover:before' => 'background-color: {{VALUE}}; opacity: 1;',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();
    $before_html = $after_html = "";

    $link = yprm_el_link($settings);

    if (!$link) {
      return false;
    }

    if ($settings['with_icon'] == 'yes' && isset($settings['icon']['value']) && $settings['icon']['value']) {
      if ($settings['icon_position'] != 'yes') {
        $before_html = '<i class="' . esc_attr($settings['icon']['value']) . '"></i>';
      } else {
        $after_html = '<i class="' . esc_attr($settings['icon']['value']) . '"></i>';
      }
    }

    $this->add_render_attribute('block', [
      'class' => [
        'button-container',
      ],
    ]);

    $this->add_render_attribute('block-anchor', [
      'class' => [
        'button-elementor',
        'button-'.$settings['style'],
      ],
      'href' => esc_url($link['url']),
      'target' => esc_attr($link['target']),
    ]);

    ob_start();?>

		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<a <?php echo $this->get_render_attribute_string('block-anchor') ?>>
				<?php echo wp_kses($before_html, 'post') ?>
				<span><?php echo strip_tags($link['title']) ?></span>
				<?php echo wp_kses($after_html, 'post') ?>
			</a>
		</div>

		<?php echo ob_get_clean();
  }
}