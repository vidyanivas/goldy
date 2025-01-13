<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

class Elementor_Skills_Widget extends \Elementor\Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('skills-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/skills.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_skills';
  }

  public function get_title() {
    return esc_html__('Skills ', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-skills';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['skills-handler'];
  }

  public function get_style_depends() {
    return ['circle-animations'];
  }

  public function get_keywords() {
    return ['Skills', 'List', 'Number', 'pt', 'novo'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'section_our_skills_layout',
      [
        'label' => __('Layout', 'novo'),
      ]
    );

    $this->add_control(
      'columns',
      [
        'label' => __('Columns', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'col-sm-6',
        'options' => [
          'col-sm-12' => __('1 Columns', 'novo'),
          'col-sm-6' => __('2 Columns', 'novo'),
          'col-sm-4' => __('3 Columns', 'novo'),
          'col-sm-3' => __('4 Columns', 'novo'),
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_our_skills',
      [
        'label' => __('Our Skills', 'novo'),
      ]
    );

    $repeater = new \Elementor\Repeater();

    $repeater->add_control('heading', [
      'type' => \Elementor\Controls_Manager::TEXT,
      'label' => __('Heading', 'novo'),
      'label_block' => true,
    ]);

    $repeater->add_control(
      'style',
      [
        'label' => __('Style', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'circle',
        'options' => [
          'circle' => __('Circle', 'novo'),
          'line' => __('Line', 'novo'),
        ],
      ]
    );

    $repeater->add_control(
      'skill_level',
      [
        'label' => __('Skill Level (%)', 'novo'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
      ]
    );

    $repeater->add_control('desc', [
      'type' => \Elementor\Controls_Manager::TEXTAREA,
      'label' => __('Description', 'novo'),
    ]);

    $this->add_control('our_skills_list', [
      'label' => __('Skills List', 'novo'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'title_field' => '{{{ heading }}} - {{{skill_level.size}}}%',
    ]);

    $this->end_controls_section();

    // Style Tab
    $this->start_controls_section(
      'section_title_style',
      [
        'label' => __('Heading', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'heading_hex',
      [
        'label' => __('Heading Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .skill-item-line h6, {{WRAPPER}} .skill-item h6' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'heading_typography',
        'selector' => '{{WRAPPER}} .skill-item-line h6, {{WRAPPER}} .skill-item h6',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_desc_style',
      [
        'label' => __('Description', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'options_color',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .skill-item-line p, {{WRAPPER}} .skill-item p' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'selector' => '{{WRAPPER}} .skill-item-line p, {{WRAPPER}} .skill-item p',
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if ('' === $settings['our_skills_list']) {
      return false;
    }

    $this->add_render_attribute('wrapper', 'class', 'skill-items-list row');

    ?>
    <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
      <?php foreach ($settings['our_skills_list'] as $skills): ?>
        <?php if ($skills['style'] === 'circle'): ?>
          <div class="skill-item <?php echo esc_attr($settings['columns']); ?>">
            <?php if (isset($skills['skill_level']['size'])): ?>
              <figure class="chart" data-percent="<?php echo esc_attr(esc_attr($skills['skill_level']['size'])); ?>">
                <figcaption><?php echo esc_attr($skills['skill_level']['size']); ?>%</figcaption>
                <svg width="60" height="60">
                  <circle class="outer" cx="160" cy="29" r="28" transform="rotate(-90, 95, 95)" />
                </svg>
              </figure>
            <?php endif;?>
            <?php if ($skills['heading']): ?>
              <h6><?php echo esc_html($skills['heading']); ?></h6>
            <?php endif;?>
            <?php if ($skills['desc']): ?>
              <p><?php echo wp_kses_post(nl2br($skills['desc'])); ?></p>
            <?php endif;?>
          </div>
        <?php else: ?>
          <div class="skill-item-line <?php echo esc_attr($settings['columns']); ?>">
            <?php if ($skills['heading']): ?>
                <h6><?php echo esc_html($skills['heading']); ?></h6>
            <?php endif;?>
            <?php if (isset($skills['skill_level']['size'])): ?>
              <div class="line">
                <div style="width: <?php echo esc_attr($skills['skill_level']['size']); ?>%;">
                  <span><?php echo esc_html($skills['skill_level']['size']); ?>%</span>
                </div>
              </div>
            <?php endif;?>
            <?php if ($skills['desc']): ?>
              <p><?php echo wp_kses_post($skills['desc']); ?></p>
            <?php endif;?>
          </div>
        <?php endif;?>
      <?php endforeach;?>
    </div>
  <?php }
}
