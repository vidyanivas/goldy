<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Decor_Elements_Widget extends Widget_Base {

  protected $settings;

  public function get_name() {
    return 'yprm_decor_elements';
  }

  public function get_title() {
    return esc_html__('Decor Elements', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-decor-elements';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'decor_elements',
      [
        'label' => esc_html__('Decor Elements', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'type',
      [
        'label' => esc_html__('Type', 'pt-addons'),
        'type' => Controls_Manager::SELECT2,
        'options' => [
          'type1' => esc_html__('Type1', 'pt-addons'),
          'type2' => esc_html__('Type2', 'pt-addons'),
        ],
      ]
    );

    $repeater->add_control(
      'align',
      [
        'label' => esc_html__('Align', 'pt-addons'),
        'type' => Controls_Manager::SELECT2,
        'options' => [
          'left' => esc_html__('Left', 'pt-addons'),
          'right' => esc_html__('Right', 'pt-addons'),
        ],
      ]
    );

    $repeater->add_control(
      'top',
      [
        'label' => __('Top(px)', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => -200,
            'max' => 200,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $repeater->add_control(
      'bottom',
      [
        'label' => __('Bottom(px)', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => -200,
            'max' => 200,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'items',
      [
        'label' => esc_html__('Decor Elements', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();
    $id = 'decor-elements-' . $this->get_id();

    if ('' == $settings['items']) {
      return false;
    }

    $this->add_render_attribute(
      [
        'wrapper' => [
          'id' => esc_attr($id),
          'class' => implode(' ', [
            'decor-elements-block',
            'decor-elements-' . esc_attr($this->get_id()),
          ]),
        ],
      ]
    );

    ob_start(); ?>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
        <?php if(\Elementor\Plugin::$instance->editor->is_edit_mode()) { ?>
        <span>&nbsp;</span>
        <?php } ?>
				<?php foreach ($settings['items'] as $index => $item):
          $item_setting_key = $this->get_repeater_setting_key('block', 'items', $index);
          $this->add_render_attribute($item_setting_key, 'class', [
            'decor-el',
            'type-' . $item['type'],
            'align-' . $item['align'],
            'elementor-repeater-item-'. $item['_id']
          ]);
        ?>
          <div <?php echo $this->get_render_attribute_string($item_setting_key) ?>></div>
        <?php endforeach;?>
      </div>
		<?php echo ob_get_clean();
  }
}