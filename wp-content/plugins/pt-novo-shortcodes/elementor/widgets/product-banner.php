<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

class Elementor_Products_Banner_Widget extends \Elementor\Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('product-banner-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/product-banner.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_product_banner';
  }

  public function get_title() {
    return esc_html__('Product Banner ', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-product-banner';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['product-banner-handler', 'owl-carousel'];
  }

  public function get_style_depends() {
    return ['owl-carousel'];
  }

  public function get_all_products_items($param = 'All') {
    $result = array();

    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => -1,
    );

    $product_array = new \WP_Query($args);

    if (is_array($product_array->posts) && !empty($product_array->posts) && count($product_array->posts) > 0) {
      foreach ($product_array->posts as $item) {
        $result[$item->ID] = 'ID [' . $item->ID . '] ' . $item->post_title;
      }
    }

    return $result;
  }

  protected function register_controls() {

    $this->start_controls_section(
      'banner_section', [
        'label' => __('General', 'novo'),
      ]
    );

    $this->add_control(
      'external_indent',
      [
        'label' => __('External indent', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $this->add_control(
      'infinite_loop',
      [
        'label' => __('Infinite loop', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'speed',
      [
        'label' => __('Transition speed', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 100,
        'max' => 10000,
        'step' => 100,
        'default' => 300,
        "description" => esc_html__("Speed at which next slide comes.", "novo"),
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => __('Autoplay Slides', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'autoplay_speed',
      [
        'label' => __('Autoplay speed', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 100,
            'max' => 10000,
            'step' => 10,
          ],
        ],
        'default' => [
          'unit' => 'px',
          'size' => 5000,
        ],
        'selectors' => [
          "{{WRAPPER}} .banner-circle-nav .active svg" => 'transition-duration: {{SIZE}}ms;',
        ],
        'condition' => [
          'autoplay' => 'on',
        ],
      ]
    );

    $this->add_control(
      'pauseohover',
      [
        'label' => __('Pause on hover', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
        'condition' => [
          'autoplay' => 'on',
        ],
      ]
    );

    $this->add_control(
      'adaptive_height',
      [
        'label' => __('Adaptive Height', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'height',
      [
        'label' => __('Height', 'novo'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 100,
            'max' => 1000,
            'step' => 10,
          ],
        ],
        'selectors' => [
          "{{WRAPPER}} .banner.banner-items, {{WRAPPER}} .banner.banner-items .cell" => 'height: {{SIZE}}{{UNIT}} !important;',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'navigation_section', [
        'label' => __('Navigation', 'novo'),
      ]
    );

    $this->add_control(
      'arrows',
      [
        'label' => __('Navigation Arrows', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'arrows_position',
      [
        'label' => __('Arrow position', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'left-bottom',
        'options' => [
          'left-bottom' => __('Left Bottom', 'novo'),
          'right-bottom' => __('Right Bottom', 'novo'),
          'bottom' => __('Bottom', 'novo'),
        ],
        'condition' => [
          'arrows' => 'on',
        ],
      ]
    );

    $this->add_control(
      'dots',
      [
        'label' => __('Pagination', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'dots_position',
      [
        'label' => __('Pagination position', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'left',
        'options' => [
          "left" => esc_html__("Left", "novo"),
          "left-outside" => esc_html__("Left Outside", "novo"),
          "left-bottom" => esc_html__("Left Bottom", "novo"),
          "bottom" => esc_html__("Bottom", "novo"),
          "right-bottom" => esc_html__("Right Bottom", "novo"),
          "right" => esc_html__("Right", "novo"),
          "right-outside" => esc_html__("Right Outside", "novo"),
        ],
        'condition' => [
          'dots' => 'on',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'banner_social_links', [
        'label' => __('Social Links', 'novo'),
      ]
    );

    $this->add_control(
      'social_buttons',
      [
        'label' => __('Social buttons', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $repeater = new Repeater();
    $repeater->add_control(
      'social_icon',
      [
        'label' => __('Icon', 'novo'),
        'type' => \Elementor\Controls_Manager::ICONS,
        'fa4compatibility' => 'social',
        'default' => [
          'value' => 'fab fa-wordpress',
          'library' => 'fa-brands',
        ],
        'recommended' => [
          'fa-brands' => [
            'android',
            'apple',
            'behance',
            'bitbucket',
            'codepen',
            'delicious',
            'deviantart',
            'digg',
            'dribbble',
            'elementor',
            'facebook',
            'flickr',
            'foursquare',
            'free-code-camp',
            'github',
            'gitlab',
            'globe',
            'houzz',
            'instagram',
            'jsfiddle',
            'linkedin',
            'medium',
            'meetup',
            'mix',
            'mixcloud',
            'odnoklassniki',
            'pinterest',
            'product-hunt',
            'reddit',
            'shopping-cart',
            'skype',
            'slideshare',
            'snapchat',
            'soundcloud',
            'spotify',
            'stack-overflow',
            'steam',
            'telegram',
            'thumb-tack',
            'tripadvisor',
            'tumblr',
            'twitch',
            'twitter',
            'viber',
            'vimeo',
            'vk',
            'weibo',
            'weixin',
            'whatsapp',
            'wordpress',
            'xing',
            'yelp',
            'youtube',
            '500px',
          ],
          'fa-solid' => [
            'envelope',
            'link',
            'rss',
          ],
        ],
      ]
    );

    $repeater->add_control(
      'link',
      [
        'label' => __('Link', 'novo'),
        'type' => Controls_Manager::URL,
        'default' => [
          'is_external' => 'true',
        ],
        'dynamic' => [
          'active' => true,
        ],
        'dynamic' => [
          'active' => true,
          ],
        'placeholder' => __('https://your-link.com', 'novo'),
      ]
    );

    $this->add_control(
      'social_items_list',
      [
        'label' => __('Social Icons', 'novo'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [
          [
            'social_icon' => [
              'value' => 'fab fa-facebook',
              'library' => 'fa-brands',
            ],
          ],
          [
            'social_icon' => [
              'value' => 'fab fa-twitter',
              'library' => 'fa-brands',
            ],
          ],
          [
            'social_icon' => [
              'value' => 'fab fa-youtube',
              'library' => 'fa-brands',
            ],
          ],
        ],
        'condition' => [
          'social_buttons' => 'on',
        ],
        'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'banner_items', [
        'label' => __('Banner Items', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $repeater = new \Elementor\Repeater();

    $repeater->add_control(
      'item',
      [
        'label' => __('Item', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $this->get_all_products_items(),
      ]
    );

    $repeater->add_control(
      'image', [
        'label' => __('Background image', 'novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control(
      'heading', [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => __('List Title', 'novo'),
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'link_button',
      [
        'label' => __('Link Button', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $repeater->add_control(
      'link_text',
      [
        'label' => __('Link Text', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'link_button' => 'on',
        ],
      ]
    );

    $repeater->add_control(
      'inner_shadow',
      [
        'label' => __('Inner shadow', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'off',
        'default' => 'off',
      ]
    );

    $repeater->add_control(
      'about_banner_price',
      [
        'label' => __('Price', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'banner_items_price_typo',
        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .price',
      ]
    );

    $repeater->add_control(
      'text_color_hex',
      [
        'label' => __('Price color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .price' => 'color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'about_banner_heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'banner_items_typo',
        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cell .h',
      ]
    );

    $repeater->add_control(
      'heading_color_hex',
      [
        'label' => __('color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .cell .h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'heading_size',
      [
        'label' => __('Heading size', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'h1',
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

    $repeater->add_control(
      'text_align',
      [
        'label' => __('Heading Alignment', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'tal',
        'options' => [
          'tal' => esc_html__('Left', 'pt-addons'),
          'tac' => esc_html__('Center', 'pt-addons'),
          'tar' => esc_html__('Right', 'pt-addons'),
        ],
      ]
    );

    $repeater->add_control(
      'text_vertical_align',
      [
        'label' => __('Heading vertical align', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'middle',
        'options' => [
          "top" => esc_html__("Top", "novo"),
          "middle" => esc_html__("Middle", "novo"),
          "bottom" => esc_html__("Bottom", "novo"),
        ],
      ]
    );

    $repeater->add_control(
      'about_banner_item_button_heading',
      [
        'label' => __('Button', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'banner_items_buttons_typo',
        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1, {{WRAPPER}} {{CURRENT_ITEM}} .button-style2',
      ]
    );

    $repeater->add_control(
      'button_text_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1' => 'color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'button_text_color_hover',
      [
        'label' => __('Hover color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1:hover' => 'color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'button_border_color',
      [
        'label' => __('Border color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1' => 'border-color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'button_border_color_hover',
      [
        'label' => __('Border Hover color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1:hover' => 'border-color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'button_bg_color',
      [
        'label' => __('Background color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'button_bg_color_hover',
      [
        'label' => __('Background Hover color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style1:hover' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'banner_items_list',
      [
        'label' => __('Banner Items', 'novo'),
        'type' => \Elementor\Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ heading ? heading : item  }}}',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'general_style',
      [
        'label' => __('Navigation', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'arrow_color',
      [
        'label' => __('Arrow Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-area .owl-nav .owl-prev, {{WRAPPER}} .banner-area .owl-nav .owl-next' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'arrows' => 'on',
        ],
      ]
    );

    $this->add_control(
      'arrow_hover_color',
      [
        'label' => __('Arrow Hover Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-area .owl-nav .owl-prev:hover, {{WRAPPER}} .banner-area .owl-nav .owl-next:hover' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'arrows' => 'on',
        ],
      ]
    );

    $this->add_control(
      'pagination_heading',
      [
        'label' => __('Pagination', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'dots_color',
      [
        'label' => __('Pagination color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-items .owl-dots' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'dots' => 'on',
        ],
      ]
    );

    $this->add_control(
      'dots_hover_color',
      [
        'label' => __('Pagination Hover color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-items .owl-dots:hover' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'dots' => 'on',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'social_links_tabs',
      [
        'label' => __('Social Links', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'social_buttons' => 'on',
        ],
      ]
    );

    $this->add_control(
      'social_buttons_color_hex',
      [
        'label' => __('color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-social-buttons' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'social_buttons_hover_color_hex',
      [
        'label' => __('Hover color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-social-buttons:hover' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    $this->add_render_attribute('wrapper', [
      'class' => 'banner-wrapper banner-area banner-' . uniqid(),
    ]);

    $this->add_render_attribute('banner-items', [
      'class' => 'banner banner-items banner-' . uniqid(),
      'id' => 'banner-' . uniqid(),
    ]);

    if (array_key_exists('height', $settings) && !empty($settings['height'])) {
      $this->add_render_attribute('banner-items', 'class', 'fixed-height');
  }

    if ($settings['external_indent'] == 'on') {
      $this->add_render_attribute('wrapper', 'class', 'external-indent');
    }

    if ($settings['arrows'] == 'on') {
      $this->add_render_attribute('banner-items', 'class', 'arrows-' . $settings['arrows_position']);
    }

    if ($settings['dots'] == 'on') {
      $this->add_render_attribute('banner-items', 'class', 'pagination-' . $settings['dots_position']);
    }

    $this->add_render_attribute(
      [
        'banner-items' => [
          'data-banner-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("on" == $settings["infinite_loop"]) ? true : false,
              "autoplay" => ("on" == $settings["autoplay"]) ? true : false,
              "arrows" => ("on" == $settings["arrows"]) ? true : false,
              "dots" => ("on" == $settings["dots"] && $settings['dots_position'] != 'bottom') ? true : false,
              "pauseohover" => ("on" == $settings["pauseohover"]) ? true : false,
              "autoplay_speed" => isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : 3000, 
            ])),
          ],
        ],
      ]
    );

    if ($settings['dots'] == 'on') {
      $this->add_render_attribute('wrapper', 'class', 'pagination-' . $settings['dots_position']);
    }

    ?>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<?php echo $this->social_icons(); ?>
				<div <?php echo $this->get_render_attribute_string('banner-items'); ?>>
					<?php echo $this->banner_items(); ?>
				</div>
			</div>
		<?php
}

  public function social_icons() {
    $settings = $this->get_settings_for_display();

    ob_start();
    ?>
			<?php if ($settings['social_buttons'] == 'on' && $settings['social_items_list']): ?>
				<div class="banner-social-buttons">
					<div class="links">
						<?php
$fallback_defaults = [
      'fa fa-facebook',
      'fa fa-twitter',
      'fa fa-google-plus',
    ];

    $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();

    foreach ($settings['social_items_list'] as $index => $item) {
      $migrated = isset($item['__fa4_migrated']['social_icon']);
      $is_new = empty($item['social']) && $migration_allowed;
      $social = '';

      // add old default
      if (empty($item['social']) && !$migration_allowed) {
        $item['social'] = isset($fallback_defaults[$index]) ? $fallback_defaults[$index] : 'fa fa-wordpress';
      }

      if (!empty($item['social'])) {
        $social = str_replace('fa fa-', '', $item['social']);
      }

      if (($is_new || $migrated) && 'svg' !== $item['social_icon']['library']) {
        $social = explode(' ', $item['social_icon']['value'], 2);
        if (empty($social[1])) {
          $social = '';
        } else {
          $social = str_replace('fa-', '', $social[1]);
        }
      }
      if ('svg' === $item['social_icon']['library']) {
        $social = get_post_meta($item['social_icon']['value']['id'], '_wp_attachment_image_alt', true);
      }

      $link_key = 'link_' . $index;

      $this->add_render_attribute($link_key, 'class', 'item');

      $this->add_link_attributes($link_key, $item['link']);

      ?>
							<a <?php echo $this->get_render_attribute_string($link_key); ?>>
								<?php
if ($is_new || $migrated) {
        \Elementor\Icons_Manager::render_icon($item['social_icon']);
      } else {?>
									<i class="<?php echo esc_attr($item['social']); ?>"></i>
								<?php }?>
								<span><?php echo $social; ?></span>
							</a>
						<?php }?>
					</div>
				</div>
			<?php endif;?>
		<?php
echo ob_get_clean();
  }
  public function banner_items() {
    $settings = $this->get_settings_for_display();
    $settings['heading_style'] = 'default';

    if (!$settings['banner_items_list']) {
      return false;
    }

    // Fill $html var with data
    $item_attr = $animation = $image_animation_attr = "";

    foreach ($settings['banner_items_list'] as $index => $item):
      $tab_count = $index + 1;

      $product = wc_get_product($item['item']);

      if (!$product) {continue;}

      if (!$item['heading'] && is_object($product)) {
        $item['heading'] = $product->get_name();
      }

      $tab_title_setting_key = $index;

      $this->add_render_attribute($tab_title_setting_key, 'class', [
        'item',
        'item-banner',
        'banner-item-' . uniqid(),
        'elementor-repeater-item-' . $item['_id'],
      ]);

      if (!empty($item['heading']) && $words_array = yprm_get_string_from($item['heading'], '{', '}')) {
        $words = array();
        foreach (explode('||', $words_array) as $word_item) {
          $words[] = $word_item;
        }

        if (count($words) > 1) {
          $new_string = '<span class="words" data-array="' . yprm_implode($words, '', ',') . '"></span>';
          $item['heading'] = str_replace('{' . $words_array . '}', $new_string, $item['heading']);
          wp_enqueue_script('typed');
        }
      }

      $item_attr = '';
      if (isset(wp_get_attachment_image_src($item['image']['id'], 'full')[0])) {
        $item_attr = 'background-image: url(' . esc_url(wp_get_attachment_image_src($item['image']['id'], 'full')[0]) . ')';
      }

      $this->add_render_attribute($tab_title_setting_key, 'class', $item['text_align']);

      if ($item['inner_shadow'] == 'on') {
        $this->add_render_attribute($tab_title_setting_key, 'class', 'with-shadow');
      }

      $this->add_render_attribute($tab_title_setting_key, 'data-heading', $item['heading']);
      $this->add_render_attribute($tab_title_setting_key, 'data-text', $product->get_price_html());

    ?>
      <div <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?> style="<?php echo esc_attr($item_attr); ?>">
        <div class="container">
          <div class="cell <?php echo esc_attr($item['text_vertical_align']); ?>">
            <?php if ($product->get_price_html()): ?>
              <div class="heading-decor"><div class="price"><?php echo $product->get_price_html(); ?></div></div>
            <?php endif; ?>
            <div class="heading">
              <<?php echo esc_attr($item['heading_size']); ?> class="h">
                <?php echo wp_kses_post($item['heading']); ?>
              </<?php echo esc_attr($item['heading_size']); ?>>
            </div>
						<?php if ($item['link_button'] == 'on' && $item['link_text']): ?>
							<a href="<?php echo esc_url(get_permalink($item['item'])); ?>" class="button-style1"><?php echo esc_html($item['link_text']); ?></a>
						<?php endif;?>
					</div>
				</div>
			</div>
		<?php endforeach;
  }

}
