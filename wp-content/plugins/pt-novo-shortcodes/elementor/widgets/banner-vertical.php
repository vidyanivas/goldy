<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Banner_Vertical_Widget extends Widget_Base {

  var $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('banner-vertical-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/banner-vertical.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_banner_vertical';
  }

  public function get_title() {
    return esc_html__('Banner Vertical', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-banner-vertical';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['banner-vertical-handler'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'general_section',
      [
        'label' => esc_html__('General', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'social_links',
      [
        'label' => esc_html__('Social Links', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'pt-addons'),
        'label_off' => esc_html__('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'arrows',
      [
        'label' => __('Navigation Arrows', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'dots',
      [
        'label' => __('Pagination', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'slides_section',
      [
        'label' => esc_html__('Slides', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'list_title', [
        'label' => __('Title', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'label_block' => true,
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
      'text',
      [
        'label' => __('Text', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
      ]
    );

    $repeater->add_control(
      'with_price',
      [
        'label' => __('With Price', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $repeater->add_control(
      'price_text',
      [
        'label' => __('Pice pre Text', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'with_price' => 'on',
        ],
      ]
    );

    $repeater->add_control(
      'price',
      [
        'label' => __('Pice', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'with_price' => 'on',
        ],
      ]
    );

    $repeater->add_control(
      'link_button',
      [
        'label' => __('Link', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $repeater->add_control(
      'link',
      [
        'label' => __('Link', 'novo'),
        'type' => \Elementor\Controls_Manager::URL,
        'placeholder' => __('https://your-link.com', 'novo'),
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
        'condition' => [
          'link_button' => 'on',
        ],
        'dynamic' => [
          'active' => true,
          ],
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
      'heading_size',
      [
        'label' => __('Heading size', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'h1',
        'options' => [
          'h1' => esc_html__('H1', 'pt-addons'),
          'h2' => esc_html__('H2', 'pt-addons'),
          'h3' => esc_html__('H3', 'pt-addons'),
        ],
      ]
    );

    $repeater->add_control(
      'content_position',
      [
        'label' => __('Content Alignment', 'novo'),
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
      'text_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'black',
        'options' => [
          'black' => esc_html__('Black', 'pt-addons'),
          'white' => esc_html__('White', 'pt-addons'),
          'custom' => esc_html__('Custom', 'pt-addons'),
        ],
      ]
    );
    
    $repeater->add_control(
      'text_color_hex',
      [
        'label' => esc_html__( 'Text Color', 'pt-addons' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{CURRENT_ITEM}} ' => 'color: {{VALUE}}',
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
        'return_value' => 'yes',
        'default' => 'off',
      ]
    );

    $this->add_control(
      'slides',
      [
        'label' => esc_html__('Slides', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ list_title }}}',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'social_links_settings',
      [
        'label' => esc_html__('Social Links', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
        'condition' => [
          'social_links' => 'yes',
        ],
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'link_name',
      [
        'label' => esc_html__('Name', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'link_url',
      [
        'label' => esc_html__('Link', 'pt-addons'),
        'type' => Controls_Manager::URL,
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
      ]
    );

    $repeater->add_control(
      'link_icon',
      [
        'label' => esc_html__('Icon', 'pt-addons'),
        'type' => Controls_Manager::ICONS,
        'default' => [
          'value' => 'fas fa-star',
          'library' => 'solid',
        ],
      ]
    );

    $this->add_control(
      'social_links_items',
      [
        'label' => esc_html__('Links', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ link_name }}} - {{{ link_url.url }}}',
        'prevent_empty' => false,
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

  protected function get_social_links() {
    $links = $this->settings['social_links_items'];
    $links_html = [];

    if ($this->settings['social_links'] != 'yes') {
      return false;
    }

    if (is_array($links) && count($links) > 0) {
      foreach ($links as $link) {
        if (!$link['link_url']['url'] || !isset($link['link_icon']['value'])) {
          continue;
        }

        $target = yprm_get_theme_setting('social_target');

        $links_html[] = '<a class="item" href="' . esc_url($link['link_url']['url']) . '" target="' . esc_attr($target) . '"><i class="' . esc_attr($link['link_icon']['value']) . '"></i><span>' . $link['link_name'] . '</span></a>';
      }
    }

    ob_start();?>
		<div class="banner-social-buttons">
			<div class="links"><?php echo implode('', $links_html) ?></div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();

    if (!is_array($settings['slides']) || count($settings['slides']) == 0) {
      return false;
    }

    $this->add_render_attribute('block', 'class', [
      'vertical-banner',
      'vertical-parallax-area',
    ]);

    ob_start(); ?>

		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<?php if ($social_links_html = $this->get_social_links()) {?>
				<div class="banner-bottom">
					<div class="container">
						<div class="social-links"><?php echo $social_links_html ?></div>
					</div>
				</div>
			<?php }?>
			<div class="vertical-parallax-slider">
				<?php echo $this->get_slides(); ?>
			</div>
			<?php echo $this->get_dots(); ?>
			<?php echo $this->get_arrows(); ?>
		</div>

		<?php echo ob_get_clean();
  }

  public function get_dots() {
    if ($this->settings['dots'] !== 'yes') {
      return false;
    }

    ob_start(); ?>
      <div class="pagination-dots"></div>
    <?php return ob_get_clean();
  }

  public function get_arrows() {
    if ($this->settings['arrows'] !== 'yes') {
      return false;
    }

    ob_start(); ?>
      <div class="nav-arrows"><div class="next multimedia-icon-up-arrow-2"></div><div class="prev multimedia-icon-down-arrow-2"></div></div>
    <?php return ob_get_clean();
  }

  public function get_slides() {
    foreach ($this->settings['slides'] as $index => $item) {
      echo $this->get_slide_item($item, $index);
    }
  }

  public function get_slide_item($atts, $index = 0) {
    $item_setting_key = $this->get_repeater_setting_key('item_block', 'items', $index);

    if ($atts['inner_shadow'] == 'yes') {
      $this->add_render_attribute($item_setting_key, 'class', 'inner-shadow');
    }

    $this->add_render_attribute($item_setting_key, [
      'class' => [
        'slide',
        'item',
        $atts['content_position'],
        $atts['text_color'],
      ],
      'style' => yprm_get_image($atts['image']['id'], 'bg', 'full'),
    ]);

    ob_start();?>
		<div <?php echo $this->get_render_attribute_string($item_setting_key) ?>>
			<div class="container">
				<div class="cell">
					<?php echo $this->item_price($atts); ?>
					<?php echo $this->item_heading($atts); ?>
					<?php echo $this->item_text($atts); ?>
					<?php echo $this->item_button($atts); ?>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
  }

  protected function item_button($item) {

    if ('on' !== $item['link_button']) {return false;}

    ob_start();
    if (!empty($item['link']['url']) && !empty($item['link_text'])): ?>
      <a href="<?php echo esc_url($item['link']['url']); ?>" class="button-style1">
        <span><?php echo $item['link_text']; ?></span>
			</a>
		<?php endif;
    return ob_get_clean();
  }

  protected function item_price($atts) {
    if ('on' !== $atts['with_price'] || !$atts['price']) {
      return;
    }

    ob_start();?>
			<?php if ($atts['price_text']): ?>
				<div class="sub-h">
					<?php echo wp_kses_post($atts['price_text']); ?>
				</div>
			<?php endif;?>
			<?php if ($atts['price']): ?>
		    	<div class="price"><?php echo wp_kses_post($atts['price']); ?></div>
      <?php endif;?>
		<?php return ob_get_clean();
  }

  protected function item_heading($atts) {
    if (!$atts['list_title']) {
      return;
    }

    ob_start();?>
			<<?php echo esc_attr($atts['heading_size']); ?> class="h">
				<?php echo yprm_heading_filter($atts['list_title']); ?>
			</<?php echo esc_attr($atts['heading_size']); ?>>
		<?php return ob_get_clean();
  }

  protected function item_text($atts) {
    if (!$atts['text']) {
      return;
    }

    ob_start();?>
			<div class="text">
				<div><?php echo wp_kses_post(nl2br($atts['text'])); ?></div>
			</div>
		<?php return ob_get_clean();
  }
}