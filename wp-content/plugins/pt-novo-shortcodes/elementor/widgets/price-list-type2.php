<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

class Elementor_Price_List_Type2_Widget extends \Elementor\Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('price-list-type2-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/price-list-type2.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'pt_price_list_type2';
  }

  public function get_title() {
    return esc_html__('Price List Type 2', 'novo');
  }

  public function get_icon() {
    return 'pt-el-icon-price-list-type2';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['owl-carousel', 'price-list-type2-handler'];
  }

  public function get_style_depends() {
    return ['owl-carousel'];
  }

  public function get_keywords() {
    return ['Price', 'List', 'pricing', 'pt', 'novo'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'general_section', [
        'label' => __('Layout', 'novo'),
      ]
    );

    $this->add_control(
      'carousel',
      [
        'label' => __('Carousel', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_group_control(
      Group_Control_Cols::get_type(),
      [
        'name' => 'cols',
        'label' => esc_html__('Cols', 'pt-addons'),
        'frontend_available' => true,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'slider_settings_section', [
        'label' => __('Carousel Settings', 'novo'),
        'condition' => [
          'carousel' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'speed',
      [
        'label' => __('Transition speed', 'novo'),
        'type' => Controls_Manager::NUMBER,
        'default' => 300,
        "min" => 100,
        "max" => 10000,
        "step" => 100,
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => __('Autoplay Slides', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'autoplay_speed',
      [
        'label' => __('Autoplay Speed', 'novo'),
        'type' => Controls_Manager::NUMBER,
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

    $this->add_control(
      'arrows',
      [
        'label' => __('Navigation Arrows', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'infinite_loop',
      [
        'label' => __('Infinite loop', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_price_list',
      [
        'label' => __('List', 'novo'),
      ]
    );

    $repeater = new \Elementor\Repeater();

    $repeater->add_control(
      'attach_image',
      [
        'label' => __('Background Image', 'novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control('heading', [
      'type' => \Elementor\Controls_Manager::TEXT,
      'label' => __('Heading', 'novo'),
      'label_block' => true,
    ]);

    $repeater->add_control('price', [
      'type' => \Elementor\Controls_Manager::TEXT,
      'label' => __('Price', 'novo'),
    ]);

    $repeater->add_control('options_heading', [
      'type' => \Elementor\Controls_Manager::TEXT,
      'label' => __('Options heading', 'novo'),
    ]);

    $repeater->add_control('options', [
      'type' => \Elementor\Controls_Manager::TEXTAREA,
      'label' => __('Options', 'novo'),
      'Description' => __('Add Options in One Line', 'novo'),
    ]);

    $repeater->add_control('link_button', [
      'type' => \Elementor\Controls_Manager::SWITCHER,
      'label' => __('Link Button', 'novo'),
    ]);

    $repeater->add_control(
      'link_text',
      [
        'label' => __('Link text', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'link_button' => 'yes',
        ],
      ]
    );

    $repeater->add_control(
      'custom_link',
      [
        'label' => __('Link', 'novo'),
        'type' => \Elementor\Controls_Manager::URL,
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
        'condition' => [
          'link_button' => 'yes',
        ],
        'dynamic' => [
          'active' => true,
          ],
      ]
    );

    $this->add_control('price_list_items', [
      'label' => __('Price List', 'novo'),
      'type' => \Elementor\Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'title_field' => '{{{ heading }}}',
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
          '{{WRAPPER}} .price-list-type2 .item .h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'heading_typography',
        'selector' => '{{WRAPPER}} .price-list-type2 .item .h',
      ]
    );

    $this->add_control(
      'heading_size',
      [
        'label' => __('Heading size', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'h6',
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
      'section_price_style',
      [
        'label' => __('Price', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'price_hex',
      [
        'label' => __('Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .price-list-type2 .item .price' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'price_typography',
        'selector' => '{{WRAPPER}} .price-list-type2 .item .price',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_desc_style',
      [
        'label' => __('Options Style', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'options_color',
      [
        'label' => __('Options Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .price-list-type2 .item .options' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'options_typography',
        'selector' => '{{WRAPPER}} .price-list-type2 .item .options',
      ]
    );

    $this->add_control(
      'options_heading_size',
      [
        'label' => __('Heading size', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'h5',
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
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'button_typography',
        'selector' => '{{WRAPPER}} .load-button a.loadmore-button',
      ]
    );

    $this->add_control(
      'button_text_color',
      [
        'label' => __('Button Color', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .price-list-type2 .item .button-style1:not(:hover), {{WRAPPER}} .price-list-type2 .item .button-style2:not(:hover)' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_hover_text_color',
      [
        'label' => __('Button Hover Color', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .price-list-type2 .item .button-style1:hover' => 'color: #ffffff !important; background: {{VALUE}};',
          '{{WRAPPER}} .price-list-type2 .item .button-style1 span, {{WRAPPER}} .price-list-type2 .item .button-style1 span:after' => 'background: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'button_border',
        'selector' => '{{WRAPPER}} .price-list-type2 .item .button-style1',
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
          '{{WRAPPER}} .price-list-type2 .item .button-style1, {{WRAPPER}} .price-list-type2 .item .button-style2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
          '{{WRAPPER}} .price-list-type2 .item .button-style1, {{WRAPPER}} .price-list-type2 .item .button-style2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_price_items_style',
      [
        'label' => __('Price Items', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'items_bg_color',
      [
        'label' => __('Background Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .price-list-type2 .item' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'item_border',
        'selector' => '{{WRAPPER}} .price-list-type2 .item',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'item_border_radius',
      [
        'label' => __('Border Radius', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
          '{{WRAPPER}} .price-list-type2 .item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    $id = 'price-list-type2-' . uniqid();
    $category_class = $id;

    if ('' === $settings['price_list_items']) {
      return false;
    }

    $this->add_render_attribute('wrapper', 'class', 'price-list-type2 row');
    $this->add_render_attribute('wrapper', 'class', $category_class);

    $this->add_render_attribute(
      [
        'wrapper' => [
          'data-banner-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("yes" == $settings["infinite_loop"]) ? true : false,
              "autoplay" => ("yes" == $settings["autoplay"]) ? true : false,
              "arrows" => ("yes" == $settings["arrows"]) ? true : false,
              "autoplay_speed" => $settings['autoplay_speed'],
              "pauseohover" => isset($settings["pauseohover"]) && $settings["pauseohover"] != 'yes' ? false : true,
              "speed" => $settings['speed'],
              "carousel" => ("yes" == $settings["carousel"]) ? true : false,
              'cols_xs' => $settings['cols_xs'],
              'cols_sm' => $settings['cols_sm'],
              'cols_md' => $settings['cols_md'],
              'cols_lg' => $settings['cols_lg'],
              'cols_xl' => $settings['cols_xl'],
            ])),
          ],
        ],
      ]
    );

    ?>
	    	<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
	    		<?php foreach ($settings['price_list_items'] as $price_list):
      $item_attr = "";
      if (isset($price_list['attach_image']['url']) && !empty($price_list['attach_image']['url'])) {
        $item_attr = 'background-image: url(' . esc_url($price_list['attach_image']['url']) . ')';
      }
      ?>
						<div class="item price-list-type2-item">
							<?php if ($price_list['heading']): ?>
								<<?php echo esc_attr($settings['heading_size']); ?> class="h">
									<?php echo esc_html($price_list['heading']); ?>
								</<?php echo esc_attr($settings['heading_size']); ?>>
							<?php endif;?>
						<?php if ($price_list['price']): ?>
							<div class="price"><?php echo esc_html($price_list['price']); ?></div>
						<?php endif;?>
						<div class="img" style="<?php echo esc_attr($item_attr); ?>"></div>
						<div class="options">
							<?php if ($price_list['options_heading']): ?>
								<div class="heading-decor"><<?php echo esc_attr($settings['options_heading_size']); ?>><?php echo esc_html($price_list['options_heading']); ?></<?php echo esc_attr($settings['options_heading_size']); ?>></div>
							<?php endif;?>
							<?php
if ($price_list['options']):
      $options = preg_split('/\r\n|[\r\n]/', $price_list['options']);
      foreach ($options as $option): ?>
										<div class="o-row"><?php echo esc_html($option); ?></div>
									<?php
endforeach;
    endif;
    ?>
						</div>
						<?php
if ($price_list['link_button'] && isset($price_list['custom_link']['url']) && $price_list['link_text']):
      $target = $price_list['custom_link']['is_external'] ? ' target="_blank"' : '';
      $nofollow = $price_list['custom_link']['nofollow'] ? ' rel="nofollow"' : '';?>
									<a class="button-style1" href="<?php echo esc_url($price_list['custom_link']['url']); ?>"<?php echo $target; ?> <?php echo $nofollow; ?>>
										<?php echo esc_html($price_list['link_text']); ?>
									</a>
								<?php
endif;
    ?>
					</div>
	    		<?php endforeach;?>
	    	</div>
	    <?php
}
}
