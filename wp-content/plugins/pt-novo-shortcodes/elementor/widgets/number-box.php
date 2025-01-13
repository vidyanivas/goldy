<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

class Elementor_Num_Box_Widget extends \Elementor\Widget_Base {

  public function get_name() {
    return 'yprm_num_box';
  }

  public function get_title() {
    return esc_html__('Number Box', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-number-box';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'num_section', [
        'label' => __('General', 'novo'),
      ]
    );

    $this->add_control(
      'desktop_cols',
      [
        'label' => __('Columns on Desktop', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 12,
        'step' => 1,
        'default' => 3,
      ]
    );

    $this->add_control(
      'tablet_cols',
      [
        'label' => __('Columns on Tablet', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 12,
        'step' => 1,
        'default' => 3,
      ]
    );

    $this->add_control(
      'mobile_cols',
      [
        'label' => __('Columns on Mobile', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 12,
        'step' => 1,
        'default' => 2,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_counter',
      [
        'label' => __('Items', 'novo'),
      ]
    );

    $repeater = new \Elementor\Repeater();

    $repeater->add_control('counter_title', [
      'type' => \Elementor\Controls_Manager::TEXTAREA,
      'label' => __('Heading', 'novo'),
      'label_block' => true,
    ]);

    $repeater->add_control('counter_number', [
      'type' => \Elementor\Controls_Manager::NUMBER,
      'label' => __('Counter', 'novo'),
      'label_block' => true,
    ]);

    $repeater->add_control('counter_suffix', [
      'type' => \Elementor\Controls_Manager::TEXT,
      'label' => __('Suffix', 'novo'),
      'label_block' => true,
    ]);

    $this->add_control('counter_list', [
      'label' => __('Counters', 'novo'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'title_field' => '{{{ counter_title }}}',
    ]);

    $this->end_controls_section();

    $this->start_controls_section(
      'section_title_style',
      [
        'label' => __('Title Style', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .num-box-items .num-box .title' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'title_typography',
        'selector' => '{{WRAPPER}} .num-box-items .num-box .title',
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'text_shadow',
        'selector' => '{{WRAPPER}} .num-box-items .num-box .title',
      ]
    );

    $this->add_responsive_control(
      'title_margin',
      [
        'label' => __('Margin', 'novo'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', '%'],
        'selectors' => [
          '{{WRAPPER}} .num-box-items .num-box .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_number_style',
      [
        'label' => __('Number', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'text_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .num-box-items .num-box .num' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'selector' => '{{WRAPPER}} .num-box-items .num-box .num',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_decore_line_style',
      [
        'label' => __('Decor', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'color',
      [
        'label' => __('Color', 'elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .num-box-items .num-box:before' => 'background: {{VALUE}}',
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
          '{{WRAPPER}} .num-box-items .num-box:before' => 'width: {{SIZE}}{{UNIT}}',
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
          '{{WRAPPER}} .num-box-items .num-box:before' => 'height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if ('' === $settings['counter_list']) {
      return;
    }

    $this->add_render_attribute('wrapper', 'class', 'num-box-items row');
  ?>
    <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
      <?php if (!empty($settings['counter_list'])): ?>
        <?php foreach ($settings['counter_list'] as $counter): ?>
          <?php if (empty($counter['counter_title'])) {continue;} ?>
          <div class="<?php echo esc_attr($this->parse_cols_class()); ?>">
            <div class="num-box">
              <div class="num">
                <span><?php echo esc_attr($counter['counter_number']) ?></span>
                <?php if (!empty($counter['counter_suffix'])): ?>
                  <em><?php echo strip_tags($counter['counter_suffix']) ?></em>
                <?php endif;?>
              </div>
              <div class="title"><?php echo wp_kses_post(nl2br($counter['counter_title'])) ?></div>
            </div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  <?php }

  protected function parse_cols_class() {
    $settings = $this->get_settings_for_display();

    $block_class = array();

    $block_class[] = 'col-lg-' . 12 / $settings['desktop_cols'];
    $block_class[] = 'col-md-' . 12 / $settings['tablet_cols'];
    $block_class[] = 'col-sm-' . 12 / $settings['mobile_cols'];

    return yprm_implode($block_class);
  }
}
