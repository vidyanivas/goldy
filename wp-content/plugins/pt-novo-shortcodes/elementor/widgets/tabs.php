<?php
namespace Elementor;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

class Elementor_Tabs_Widget extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('tabs-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/tabs.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_tabs';
  }

  public function get_title() {
    return esc_html__('Tabs ', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-tabs';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['tabs-handler'];
  }

  public function get_keywords() {
    return ['tabs', 'tabs', 'toggle'];
  }

  protected function register_controls() {
    $this->start_controls_section(
      'section_tabs',
      [
        'label' => __('Tabs', 'novo'),
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'title',
      [
        'label' => __('Title', 'novo'),
        'type' => Controls_Manager::TEXT,
        'default' => __('Tab Title', 'novo'),
        'label_block' => true,
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
      'tabs',
      [
        'label' => __('Tabs Items', 'novo'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ title }}}',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_tabs_style',
      [
        'label' => __('Tabs', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'border_width',
      [
        'label' => __('Border Width', 'novo'),
        'type' => Controls_Manager::SLIDER,
        'default' => [
          'size' => 1,
        ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 10,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .tabs .tabs-head .item.active-tab' => 'border-bottom-width: {{SIZE}}{{UNIT}};border-style:solid;',
        ],
      ]
    );

    $this->add_control(
      'border_color',
      [
        'label' => __('Border Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .tabs .tabs-head .item.active-tab' => 'border-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'background_color',
      [
        'label' => __('Background Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .tabs-body' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'heading_title',
      [
        'label' => __('Title', 'novo'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'tab_color',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .tabs-head .item, {{WRAPPER}} .tabs-head .item a' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'tab_active_color',
      [
        'label' => __('Active Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .tabs-head .item.active-tab,
					 {{WRAPPER}} .tabs-head .item.active-tab a' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'tab_typography',
        'selector' => '{{WRAPPER}} .tabs-head .item',
      ]
    );

    $this->add_control(
      'heading_content',
      [
        'label' => __('Content', 'novo'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'content_color',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .pt-tab-content' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'content_typography',
        'selector' => '{{WRAPPER}} .pt-tab-content',
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Render tabs widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function render() {
    $tabs = $this->get_settings_for_display('tabs');

    $id_int = substr($this->get_id_int(), 0, 3);

    $this->add_render_attribute('elementor-tabs', 'class', 'pt-tabs tabs elementor-block');
    $this->add_render_attribute('elementor-tabs', 'class', 'tabs-' . uniqid());

    ?>
		<div <?php echo $this->get_render_attribute_string('elementor-tabs'); ?>>
			<div class="tabs-head" role="tablist"></div>
			<div class="tabs-body" role="tablist" aria-orientation="vertical">
				<?php foreach ($tabs as $index => $item):
          if (!$item['title']) {continue;}
          $tab_count = $index + 1;
          $hidden = 1 === $tab_count ? 'false' : 'hidden';
          $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

          $this->add_render_attribute($tab_content_setting_key, [
            'id' => 'pt-tab-content-' . $id_int . $tab_count,
            'class' => ['pt-tab-content', 'elementor-clearfix', 'item'],
            'data-tab' => $tab_count,
            'data-name' => $item['title'],
            'role' => 'tabpanel',
            'aria-labelledby' => 'pt-tab-title-' . $id_int . $tab_count,
            'tabindex' => '0',
          ]);
        ?>
          <div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>>
            <?php if ($item['content_type'] == 'section') {
              echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($item['saved_section']);
            } elseif ($item['content_type'] == 'content') {
              echo do_shortcode($item['content']);
            } ?>
					</div>
				<?php endforeach;?>
			</div>
		</div>
		<?php
}
}
