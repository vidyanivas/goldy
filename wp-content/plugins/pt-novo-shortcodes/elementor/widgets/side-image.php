<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

class Elementor_Side_Image_Widget extends \Elementor\Widget_Base {

  public function get_name() {
    return 'yprm_side_image';
  }

  public function get_title() {
    return esc_html__('Side Image', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-side-image';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'image_section', [
        'label' => __('Image', 'novo'),
      ]
    );

    $this->add_control(
      'image',
      [
        'label' => __('Image', 'novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
      ]
    );

    $this->add_control(
      'style',
      [
        'label' => __('Style', 'novo'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'square' => 'Square',
          'circle' => 'Circle',
        ],
        'default' => 'circle',
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
      ]
    );

    $this->add_responsive_control(
      'max_width',
      [
        'label' => __('Max Width', 'the7mk2'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 500,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .side-img' => 'max-width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => 'box_shadow',
        'selector' => '{{WRAPPER}} .side-img',
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if ('' === $settings['image']['url']) {
      return;
    }

    $this->add_render_attribute('wrapper', 'class', 'side-img');

    $this->add_render_attribute('wrapper', 'class', 'style-' . $settings['style']);
    $this->add_render_attribute('wrapper', 'class', 'align-' . $settings['align']);

    $bg = yprm_get_image($settings['image']['id'], 'bg');
    ?>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<div style="<?php echo esc_attr($bg) ?>"></div>
			</div>
		<?php
}
}
