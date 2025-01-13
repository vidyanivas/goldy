<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Categories_Slider_Widget extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('categories-slider-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/categories-slider.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_categories_slider';
  }

  public function get_title() {
    return esc_html__('Categories Slider', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-categories-slider';
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['owl-carousel', 'categories-slider-handler'];
    }

    return ['owl-carousel', 'categories-slider-handler'];
  }

  public function get_style_depends() {
    return ['owl-carousel'];
  }

  public function get_categories() {
    return ['novo-elements'];
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
        'label' => __('Carousel navigation', 'novo'),
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

    $this->end_controls_section();

    $this->start_controls_section(
      'categories_section',
      [
        'label' => esc_html__('Categories item', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'source',
      [
        'label' => esc_html__('Source', 'pt-addons'),
        'type' => Controls_Manager::SELECT2,
        'options' => [
          "blog" => esc_html__("Blog", "novo"),
          "portfolio" => esc_html__("Portfolio", "novo"),
          "product" => esc_html__("Product", "novo"),
          "custom_link" => esc_html__("Custom link", "novo"),
        ],
        'multiple' => false,
        'default' => 'blog',
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'category_portfolio',
      [
        'label' => esc_html__('Portfolio Category', 'pt-addons'),
        'type' => Controls_Manager::SELECT2,
        'options' => \novo_get_term_list('pt-portfolio-category'),
        'multiple' => false,
        'condition' => [
          'source' => 'portfolio',
        ],
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'category_blog',
      [
        'label' => esc_html__('Blog Category', 'pt-addons'),
        'type' => Controls_Manager::SELECT2,
        'options' => \novo_get_term_list('category'),
        'multiple' => false,
        'condition' => [
          'source' => 'blog',
        ],
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'category_product',
      [
        'label' => esc_html__('Product Category', 'pt-addons'),
        'type' => Controls_Manager::SELECT2,
        'options' => \novo_get_term_list('product_cat'),
        'multiple' => false,
        'condition' => [
          'source' => 'product',
        ],
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'custom_link',
      [
        'label' => esc_html__('Link', 'pt-addons'),
        'type' => Controls_Manager::URL,
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
        'condition' => [
          'source' => 'custom_link',
        ],
        'dynamic' => [
          'active' => true,
          ],
      ]
    );

    $repeater->add_control(
      'image',
      [
        'label' => esc_html__('Background Image', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'heading',
      [
        'label' => esc_html__('Heading', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $this->add_control(
      'categories_items',
      [
        'label' => esc_html__('Categories', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{heading}}}',
      ]
    );

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
          '{{WRAPPER}} .item .h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'heading_typography',
        'selector' => '{{WRAPPER}} .item .h',
      ]
    );

    $this->add_control(
      'heading_size',
      [
        'label' => __('Heading size', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'h3',
        'options' => [
          'h1' => esc_html__('H1', 'pt-addons'),
          'h2' => esc_html__('H2', 'pt-addons'),
          'h3' => esc_html__('H3', 'pt-addons'),
          'h4' => esc_html__('H4', 'pt-addons'),
          'h5' => esc_html__('H5', 'pt-addons'),
          'h6' => esc_html__('H6', 'pt-addons'),
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'button_style_section',
      [
        'label' => __('Button', 'marlon-elementor'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'source' => 'custom_link',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'button_typography',
        'selector' => '{{WRAPPER}} .button-style1',
      ]
    );

    $this->add_control(
      'button_text_color',
      [
        'label' => __('Button Color', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-style1' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_hover_text_color',
      [
        'label' => __('Button Hover Color', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .button-style1:hover' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'button_border',
        'selector' => '{{WRAPPER}} .button-style1',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'button_border_radius',
      [
        'label' => __('Border Radius', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
          '{{WRAPPER}} .button-style1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'button_text_padding',
      [
        'label' => __('Text Padding', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', '%'],
        'selectors' => [
          '{{WRAPPER}} .button-style1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  public function render() {
    $this->settings = $settings = $this->get_settings_for_display();

    $this->add_render_attribute('block', 'class', 'category-slider-area');

    $this->add_render_attribute(
      [
        'block' => [
          'data-portfolio-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("yes" == $this->settings["infinite_loop"]) ? true : false,
              "autoplay" => ("yes" == $this->settings["autoplay"]) ? true : false,
              "arrows" => ("yes" == $this->settings["carousel_nav"]) ? true : false,
              "mousewheel" => ("yes" == $this->settings["mousewheel"]) ? true : false,
              "autoplay_speed" => $this->settings['autoplay_speed'],
              "speed" => $this->settings['speed'],
            ])),
          ],
        ],
      ]
    );

    ob_start();?>
		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<div class="category-slider-images"></div>
			<div class="category-slider owl-carousel">
				<?php echo $this->get_categories_html() ?>
			</div>
		</div>

		<?php echo ob_get_clean();
  }

  protected function get_categories_html() {
    if (count($this->settings['categories_items']) < 1) {
      return;
    }

    ob_start(); ?>
		<?php foreach ($this->settings['categories_items'] as $key => $categories) {
      $this->get_category_item($categories, $key + 1);
    }?>
		<?php echo ob_get_clean();
  }

  protected function get_category_item($atts, $index) {
    $block_setting_key = $this->get_repeater_setting_key('item_block', 'categories', $index);
    $link_setting_key = $this->get_repeater_setting_key('link', 'categories', $index);

    $bg = '';
    if (isset(wp_get_attachment_image_src($atts['image']['id'], 'full')[0])) {
      $bg = esc_url(wp_get_attachment_image_src($atts['image']['id'], 'full')[0]);
    }

    $this->add_render_attribute($block_setting_key, 'class', [
      'item',
      'elementor-repeater-item-' . $atts['_id'],
    ]);

    $this->add_render_attribute($block_setting_key, 'data-image', $bg);
    $this->add_render_attribute($block_setting_key, 'data-eq', $index);

    $link = $this->get_item_link($atts, $link_setting_key);

    ob_start(); ?>

		<div <?php echo $this->get_render_attribute_string($block_setting_key); ?>>
			<?php if (!empty($link)): ?>
				<a <?php echo $this->get_render_attribute_string($link_setting_key); ?>>
					<?php echo strip_tags($atts['heading']); ?>
				</a>
			<?php endif;?>
		</div>

		<?php
echo ob_get_clean();
  }

  protected function get_item_link($atts, $index_key) {
    $link = '';
    if ($atts['source'] == 'blog' && $atts['category_blog']) {
      $link = $this->validate_term_link($atts['category_blog'], 'category');
    }

    if ($atts['source'] == 'portfolio' && $atts['category_portfolio']) {
      $link = $this->validate_term_link($atts['category_portfolio'], 'pt-portfolio-category');
    }

    if ($atts['source'] == 'product' && $atts['category_product']) {
      $link = $this->validate_term_link($atts['category_product'], 'product_cat');
    }

    if ($atts['source'] == 'custom_link' && $atts['custom_link']['url']) {
      $target = $atts['custom_link']['is_external'] ? '_blank' : '';
      $nofollow = $atts['custom_link']['nofollow'] ? 'nofollow' : '';
      $link = $atts['custom_link']['url'];

      $this->add_render_attribute($index_key, 'target', $target);
      $this->add_render_attribute($index_key, 'rel', $nofollow);
    }

    $this->add_render_attribute($index_key, 'href', $link ? $link : '#');

    return $link;
  }

  protected function validate_term_link($term, $taxonomy) {
    if (!$term || !$taxonomy) {
      return false;
    }

    $term = get_term_by('ID', $term, $taxonomy);

    if (!$term || is_wp_error($term)) {
      return false;
    }

    $link = get_term_link($term, $term->taxonomy);

    if (!$link || is_wp_error($link)) {
      return false;
    }

    return $link;
  }
}