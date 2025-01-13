<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Repeater;
use \Elementor\Widget_Base;

class Elementor_Accordion_Widget extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('accordion-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/accordion.js', array('jquery', 'elementor-frontend', 'pt-accordion'), '', true);
  }

  public function get_name() {
    return 'yprm_accordion';
  }

  public function get_title() {
    return esc_html__('Accordion ', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-accordion';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['accordion-handler', 'pt-accordion'];
  }

  public function get_keywords() {
    return ['accordion', 'tabs', 'toggle'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'section_title',
      [
        'label' => __('Accordion', 'novo'),
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'tab_title',
      [
        'label' => __('Title', 'novo'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'tab_content',
      [
        'label' => __('Content', 'novo'),
        'type' => Controls_Manager::WYSIWYG,
        'show_label' => false,
      ]
    );

    $this->add_control(
      'tabs',
      [
        'label' => __('Accordion Items', 'novo'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ tab_title }}}',
      ]
    );

    $this->add_control(
      'view',
      [
        'label' => __('View', 'novo'),
        'type' => Controls_Manager::HIDDEN,
        'default' => 'traditional',
      ]
    );

    $this->add_control(
      'selected_icon',
      [
        'label' => __('Icon', 'novo'),
        'type' => Controls_Manager::ICONS,
        'separator' => 'before',
        'fa4compatibility' => 'icon',
        'default' => [
          'value' => 'fas fa-plus',
          'library' => 'fa-solid',
        ],
        'recommended' => [
          'fa-solid' => [
            'chevron-down',
            'angle-down',
            'angle-double-down',
            'caret-down',
            'caret-square-down',
          ],
          'fa-regular' => [
            'caret-square-down',
          ],
        ],
        'skin' => 'inline',
        'label_block' => false,
      ]
    );

    $this->add_control(
      'selected_active_icon',
      [
        'label' => __('Active Icon', 'novo'),
        'type' => Controls_Manager::ICONS,
        'fa4compatibility' => 'icon_active',
        'default' => [
          'value' => 'fas fa-minus',
          'library' => 'fa-solid',
        ],
        'recommended' => [
          'fa-solid' => [
            'chevron-up',
            'angle-up',
            'angle-double-up',
            'caret-up',
            'caret-square-up',
          ],
          'fa-regular' => [
            'caret-square-up',
          ],
        ],
        'skin' => 'inline',
        'label_block' => false,
        'condition' => [
          'selected_icon[value]!' => '',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_title_style',
      [
        'label' => __('Accordion', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'border_width',
      [
        'label' => __('Border Width', 'novo'),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 10,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .item' => 'border-width: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .item .wrap' => 'border-width: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .item .top.active' => 'border-width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'border_color',
      [
        'label' => __('Border Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .item' => 'border-color: {{VALUE}};',
          '{{WRAPPER}} .item .wrap' => 'border-top-color: {{VALUE}};',
          '{{WRAPPER}} .item .top.active' => 'border-bottom-color: {{VALUE}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_toggle_style_title',
      [
        'label' => __('Title', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'title_background',
      [
        'label' => __('Background', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .top' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-accordion-icon, {{WRAPPER}} .cell' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'tab_active_color',
      [
        'label' => __('Active Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .active .elementor-accordion-icon, {{WRAPPER}} .active .cell' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'title_typography',
        'selector' => '{{WRAPPER}} .cell',
      ]
    );

    $this->add_group_control(
      Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'title_shadow',
        'selector' => '{{WRAPPER}} .cell',
      ]
    );

    $this->add_responsive_control(
      'title_padding',
      [
        'label' => __('Padding', 'novo'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', '%'],
        'selectors' => [
          '{{WRAPPER}} .top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_toggle_style_icon',
      [
        'label' => __('Icon', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'selected_icon[value]!' => '',
        ],
      ]
    );

    $this->add_control(
      'icon_align',
      [
        'label' => __('Alignment', 'novo'),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => __('Start', 'novo'),
            'icon' => 'eicon-h-align-left',
          ],
          'right' => [
            'title' => __('End', 'novo'),
            'icon' => 'eicon-h-align-right',
          ],
        ],
        'default' => is_rtl() ? 'right' : 'left',
        'toggle' => false,
      ]
    );

    $this->add_control(
      'icon_color',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .top .elementor-accordion-icon i:before' => 'color: {{VALUE}};',
          '{{WRAPPER}} .top .elementor-accordion-icon svg' => 'fill: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'icon_active_color',
      [
        'label' => __('Active Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .top.active .elementor-accordion-icon i:before' => 'color: {{VALUE}};',
          '{{WRAPPER}} .top.active .elementor-accordion-icon svg' => 'fill: {{VALUE}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'icon_space',
      [
        'label' => __('Spacing', 'novo'),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-accordion-icon.elementor-accordion-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-accordion-icon.elementor-accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_toggle_style_content',
      [
        'label' => __('Content', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'content_background_color',
      [
        'label' => __('Background', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .wrap' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'content_color',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .wrap' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'content_typography',
        'selector' => '{{WRAPPER}} .wrap',
      ]
    );

    $this->add_group_control(
      Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'content_shadow',
        'selector' => '{{WRAPPER}} .wrap',
      ]
    );

    $this->add_responsive_control(
      'content_padding',
      [
        'label' => __('Padding', 'novo'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', '%'],
        'selectors' => [
          '{{WRAPPER}} .wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Render accordion widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function render() {
    $settings = $this->get_settings_for_display();
    $migrated = isset($settings['__fa4_migrated']['selected_icon']);

    if (!isset($settings['icon']) && !Icons_Manager::is_migration_allowed()) {
      $settings['icon'] = 'fa fa-plus';
      $settings['icon_active'] = 'fa fa-minus';
      $settings['icon_align'] = $this->get_settings('icon_align');
    }

    $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
    $has_icon = (!$is_new || !empty($settings['selected_icon']['value']));
    $id_int = substr($this->get_id_int(), 0, 3);
    ?>
		<div class="accordion-items elementor-accordion" role="tablist">
			<?php foreach ($settings['tabs'] as $index => $item):
        $tab_count = $index + 1;

        $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

        $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

        $this->add_render_attribute($tab_title_setting_key, [
          'id' => 'top-' . $id_int . $tab_count,
          'class' => ['top elementor-tab-title'],
          'data-tab' => $tab_count,
          'role' => 'tab',
          'aria-controls' => 'wrap-' . $id_int . $tab_count,
          'aria-expanded' => 'false',
        ]);

        $this->add_render_attribute($tab_content_setting_key, [
          'id' => 'wrap-' . $id_int . $tab_count,
          'class' => ['wrap', 'elementor-clearfix'],
          'data-tab' => $tab_count,
          'role' => 'tabpanel',
          'aria-labelledby' => 'top-' . $id_int . $tab_count,
        ]);

        $this->add_inline_editing_attributes($tab_content_setting_key, 'advanced');
      ?>
        <div class="item">
          <div <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>>
            <?php if ($has_icon): ?>
              <span class="elementor-accordion-icon elementor-accordion-icon-<?php echo esc_attr($settings['icon_align']); ?>" aria-hidden="true">
                <span class="elementor-accordion-icon-closed"><?php Icons_Manager::render_icon($settings['selected_icon']);?></span>
                <span class="elementor-accordion-icon-opened"><?php Icons_Manager::render_icon($settings['selected_active_icon']);?></span>
              </span>
            <?php endif;?>
            <div class="cell"><?php echo $item['tab_title']; ?></div>
          </div>
          <div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>><?php echo $this->parse_text_editor($item['tab_content']); ?></div>
        </div>
			<?php endforeach;?>
		</div>
		<?php }
}
