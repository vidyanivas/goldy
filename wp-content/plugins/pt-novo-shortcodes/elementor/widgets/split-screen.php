<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Elementor_Split_Screen_Widget extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('split-screen-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/split-screen.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_split_screen';
  }

  public function get_title() {
    return esc_html__('Split Screen ', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-split-screen';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['owl-carousel', 'touch-swipe', 'split-screen-handler'];
    }

    return ['owl-carousel', 'touch-swipe', 'split-screen-handler'];
  }

  public function get_style_depends() {
    return ['owl-carousel'];
  }

  public function get_keywords() {
    return ['split-screen', 'tabs', 'toggle'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'section_title',
      [
        'label' => __('General', 'novo'),
      ]
    );

    $this->add_control(
      'pagination',
      [
        'label' => __('Pagination', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'dots_color',
      [
        'label' => esc_html__('Pagination Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .split-screen' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'screen_title',
      [
        'label' => __('Screens', 'novo'),
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'images',
      [
        'label' => esc_html__('Images', 'pt-addons'),
        'type' => Controls_Manager::GALLERY,
      ]
    );

    $repeater->add_control(
      'content_type',
      [
        'label' => esc_html__('Content Type', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'content' => esc_html__('Content', 'pt-addons'),
          'section' => esc_html__('Saved Section', 'pt-addons'),
        ],
        'default' => 'content',
      ]
    );

    $repeater->add_control(
      'saved_section',
      [
        'label' => esc_html__('Choose Section', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => yprm_get_page_templates('section'),
        'condition' => [
          'content_type' => 'section',
        ],
      ]
    );

    $repeater->add_control(
      'content',
      [
        'label' => esc_html__('Content', 'pt-addons'),
        'type' => Controls_Manager::WYSIWYG,
        'condition' => [
          'content_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'screens',
      [
        'label' => __('Screens', 'novo'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    $this->add_render_attribute('wrapper', 'class', [
      'split-screen',
      $settings['pagination'],
    ]);

    if ($settings['pagination'] != 'yes') {
      $this->add_render_attribute('wrapper', 'class', 'pagination-off');
    }

    ?>
		<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
      <?php foreach ($settings['screens'] as $index => $screen):
        if (!count($screen['images'])) {continue;}
      ?>
        <div class="item">
          <div class="image">
            <?php foreach($screen['images'] as $image) { ?>
              <div class="img-item" style="<?php echo yprm_get_image($image['id'], 'bg') ?>"></div>
            <?php } ?>
          </div>
          <div class="content">
            <div class="cell">
              <?php if ($screen['content_type'] == 'section') {
                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($screen['saved_section']);
              } elseif ($screen['content_type'] == 'content') {
                echo do_shortcode($screen['content']);
              } ?>
            </div>
          </div>
        </div>
      <?php endforeach;?>
		</div>
		<?php
}
}
