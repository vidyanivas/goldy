<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Heading_Block_Widget extends \Elementor\Widget_Base {

  public function get_name() {
    return 'yprm_heading';
  }

  public function get_title() {
    return esc_html__('Heading', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-heading';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_keywords() {
    return ['heading', 'text', 'novo'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'heading_section', [
        'label' => __('General', 'novo'),
      ]
    );

    $this->add_control(
      'sub_heading',
      [
        'label' => __('Sub Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $this->add_control(
      'heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'label_block' => true,
      ]
    );

    $this->add_control(
      'editor',
      [
        'label' => __('Content', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'label_block' => true,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_title_style',
      [
        'label' => __('Title', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'heading!' => ''
        ]
      ]
    );

    $this->add_responsive_control(
      'align',
      [
        'label' => __('Alignment', 'novo'),
        'type' => \Elementor\Controls_Manager::CHOOSE,
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
          '{{WRAPPER}} .h' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'heading_size',
      [
        'label' => __('HTML Tag', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'h1' => 'H1',
          'h2' => 'H2',
          'h3' => 'H3',
          'h4' => 'H4',
          'h5' => 'H5',
          'h6' => 'H6',
          'div' => 'div',
          'span' => 'span',
          'p' => 'p',
        ],
        'default' => 'h2',
      ]
    );

    $this->add_control(
      'decor_type',
      [
        'label' => __('Decor', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          '' => 'None',
          'bottom-line' => 'Bottom Line',
        ],
        'default' => '',
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'selector' => '{{WRAPPER}} .h',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_decore_line_style',
      [
        'label' => __('Decor', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'decor_type' => 'bottom-line',
        ],
      ]
    );

    $this->add_control(
      'color',
      [
        'label' => __('Color', 'elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .heading-block.with-line:after' => 'background: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'weight',
      [
        'label' => __('Width', 'elementor'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 1,
            'max' => 500,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .heading-block.with-line:after' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_control(
      'height',
      [
        'label' => __('Height', 'elementor'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 1,
            'max' => 50,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .heading-block.with-line:after' => 'height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_sub_title_style',
      [
        'label' => __('Sub Title', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'sub_heading!' => ''
        ]
      ]
    );

    $this->add_responsive_control(
      'sub_title_align',
      [
        'label' => __('Alignment', 'novo'),
        'type' => \Elementor\Controls_Manager::CHOOSE,
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
          'justify' => [
            'title' => __('Justified', 'novo'),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'default' => 'left',
        'selectors' => [
          '{{WRAPPER}} .sub-h' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'sub_title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .sub-h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'sub_typography',
        'selector' => '{{WRAPPER}} .sub-h',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_description_style',
      [
        'label' => __('Description', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'editor!' => ''
        ]
      ]
    );

    $this->add_responsive_control(
      'desc_align',
      [
        'label' => __('Alignment', 'novo'),
        'type' => \Elementor\Controls_Manager::CHOOSE,
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
          'justify' => [
            'title' => __('Justified', 'novo'),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'default' => 'left',
        'selectors' => [
          '{{WRAPPER}} .heading-content' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'desc_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .heading-content' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'desc_typography',
        'selector' => '{{WRAPPER}} .heading-content',
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if (!$settings['heading'] && !$settings['sub_heading']) {
      return;
    }

    $this->add_render_attribute('title', 'class', 'heading-block');

    if ($settings['decor_type'] == 'dots') {
      $this->add_render_attribute('title', 'class', 'with-dots');
    } else if ($settings['decor_type'] == 'bottom-line') {
      $this->add_render_attribute('title', 'class', 'with-line');
    }

    if (isset($settings['align'])) {
      if ($settings['align'] == 'left') {
          $this->add_render_attribute('title', 'class', 'tal');
      } else if ($settings['align'] == 'right') {
          $this->add_render_attribute('title', 'class', 'tar');
      } else if ($settings['align'] == 'center') {
          $this->add_render_attribute('title', 'class', 'tac');
      }
  }
    ?>
			<div <?php echo $this->get_render_attribute_string('title'); ?>>
				<?php if (!empty($settings['sub_heading'])): ?>
					<div class="sub-h"><?php echo wp_kses_post($settings['sub_heading']); ?></div>
				<?php endif;?>
        <?php if (!empty($settings['heading'])): ?>
          <<?php echo $settings['heading_size']; ?> class="h"><?php echo yprm_heading_filter(nl2br($settings['heading'])); ?></<?php echo $settings['heading_size']; ?>>
        <?php endif;?>
			</div>
      <?php if (!empty($settings['editor'])): ?>
        <div class="heading-content"><?php echo wp_kses_post(nl2br($settings['editor'])); ?></div>
      <?php endif;?>
		<?php
}
}
