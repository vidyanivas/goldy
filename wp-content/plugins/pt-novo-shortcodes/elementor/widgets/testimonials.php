<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Testimonials_Widget extends Widget_Base {

  var $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('testimonials-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/testimonials.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_testimonials';
  }

  public function get_title() {
    return esc_html__('Testimonials', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-testimonials';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['owl-carousel', 'testimonials-handler'];
    }

    return ['owl-carousel', 'testimonials-handler'];
  }

  public function get_style_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['owl-carousel'];
    }

    return ['owl-carousel'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'slider_settings_section', [
        'label' => __('Slider Settings', 'novo'),
      ]
    );

    $this->add_control(
      'carousel_nav',
      [
        'label' => __('Navigation', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
      ]
    );

    $this->add_control(
      'infinite_loop',
      [
        'label' => __('Infinite loop', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'mousewheel',
      [
        'label' => __('Mousewheel', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'speed',
      [
        'label' => __('Transition speed', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 300,
        "min" => 100,
        "max" => 10000,
        "step" => 100,
        "description" => esc_html__("Speed at which next slide comes.", "novo"),
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => __('Autoplay Slides', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
        "description" => esc_html__("Speed at which next slide comes.", "novo"),
      ]
    );

    $this->add_control(
      'autoplay_speed',
      [
        'label' => __('Autoplay Speed', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 5000,
        "min" => 100,
        "max" => 10000,
        "step" => 10,
      ]
    );

    $this->add_control(
      'pauseohover',
      [
        'label' => __('Pause on hover', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'yes',
        'default' => 'yes',
        'condition' => [
          'autoplay' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'testimonials_section',
      [
        'label' => esc_html__('Slides', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'image',
      [
        'label' => __('Image', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'name',
      [
        'label' => __('Name', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'author_post',
      [
        'label' => __('Post', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'author_content',
      [
        'label' => __('Text', 'pt-addons'),
        'type' => Controls_Manager::TEXTAREA,
      ]
    );

    $this->add_control(
      'testimonial_items',
      [
        'label' => esc_html__('Testimonials', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{name}}}',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'name_customizing',
      [
        'label' => esc_html__('Name Customizing', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'name_typography',
        'label' => esc_html__('Typography', 'pt-addons'),
        'selector' => '{{WRAPPER}} .testimonials .item h4',
      ]
    );

    $this->add_control(
      'name_color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials .item h4' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'name_margin',
      [
        'label' => esc_html__('Margin', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em'],
        'selectors' => [
          '{{WRAPPER}} .testimonials .item h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'post_customizing',
      [
        'label' => esc_html__('Post Customizing', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'post_typography',
        'label' => esc_html__('Typography', 'pt-addons'),
        'selector' => '{{WRAPPER}} .testimonials .item .post',
      ]
    );

    $this->add_control(
      'post_color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials .item .post' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'post_margin',
      [
        'label' => esc_html__('Margin', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em'],
        'selectors' => [
          '{{WRAPPER}} .testimonials .item .post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'text_customizing',
      [
        'label' => esc_html__('Text Customizing', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'text_typography',
        'label' => esc_html__('Typography', 'pt-addons'),
        'selector' => '{{WRAPPER}} .quote',
      ]
    );

    $this->add_control(
      'text_color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials .item .quote' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'text_margin',
      [
        'label' => esc_html__('Margin', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em'],
        'selectors' => [
          '{{WRAPPER}} .testimonials .item .quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();

    if ($settings['testimonial_items'] == '') {
      return false;
    }

    $this->add_render_attribute('block', 'class', [
      'testimonials',
      'row',
      'owl-carousel',
	  'owl-loaded'
    ]);

    $this->add_render_attribute(
      [
        'block' => [
          'data-portfolio-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("yes" == $this->settings["infinite_loop"]) ? true : false,
              "autoplay" => ("yes" == $this->settings["autoplay"]) ? true : false,
              "arrows" => ("yes" == $this->settings["carousel_nav"]) ? true : false,
              "mousewheel" => ("yes" == $this->settings["mousewheel"]) ? true : false,
              "pauseohover" => isset($this->settings["pauseohover"]) && $this->settings["pauseohover"] != 'yes' ? false : true,
              "autoplay_speed" => $this->settings['autoplay_speed'],
              "speed" => $this->settings['speed'],
            ])),
          ],
        ],
      ]
    );

    ob_start();?>

		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<?php echo $this->get_testimonials($settings['testimonial_items']); ?>
		</div>

		<?php echo ob_get_clean();
  }

  public function get_testimonials($items) {
    foreach ($items as $index => $item) {
      echo $this->get_testimonial_item($item, $index);
    }
  }

  public function get_testimonial_item($atts, $index = 0) {
    $settings = $this->get_settings_for_display();

    $item_setting_key = $this->get_repeater_setting_key('block', 'items', $index);

    if (isset($atts['image']['id']) && $atts['image']['id']) {
      $avatar = yprm_get_image($atts['image']['id'], 'bg', 'large');
    } else {
      $avatar = false;
    }

    $this->add_render_attribute($item_setting_key, 'class', [
      'item',
      'testimonial-item',
      $avatar ? 'with-avatar' : '',
    ]);

    ob_start(); ?>
		<div <?php echo $this->get_render_attribute_string($item_setting_key) ?>>
			<div class="row">
				<?php if ($avatar): ?>
					<div class="col-12 col-sm-6 image"><div style="<?php echo esc_attr($avatar) ?>"></div></div>
						<div class="col-12 col-sm-6 offset-sm-6">
				<?php else: ?>
					<div class="col-12">
				<?php endif;?>

					<?php if ($atts['author_content']): ?>
						<div class="quote"><div class="q">“</div>
							<?php echo wp_kses_post(nl2br($atts['author_content'])); ?>
						</div>
					<?php endif;?>

					<?php if ($atts['name']): ?>
						<h4>
							<?php echo wp_kses_post($atts['name']); ?>
						</h4>
					<?php endif;?>

					<?php if ($atts['author_post']): ?>
						<div class="post">
							<?php echo wp_kses_post($atts['author_post']); ?>
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
  }

}