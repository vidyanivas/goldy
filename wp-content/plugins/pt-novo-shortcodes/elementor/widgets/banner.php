<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

#[AllowDynamicProperties]

class Elementor_Banner_Widget extends \Elementor\Widget_Base {

  protected $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('banner-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/banner.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_banner';
  }

  public function get_title() {
    return esc_html__('Banner', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-banner';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['banner-handler', 'gsap', 'pt-youtube-video', 'pt-categories', 'owl-carousel', 'swiper11', 'typed'];
    }

    $scripts = ['banner-handler', 'gsap', 'pt-categories', 'owl-carousel', 'swiper11', 'typed'];

    return $scripts;
  }

  public function get_style_depends() {
    return [ 'e-swiper' ];
    return ['owl-carousel'];
  }

  public function get_keywords() {
    return ['banner', 'home', 'slider', 'novo'];
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
      'autoplay_speed',
      [
        'label' => __('Autoplay speed', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 100,
        'max' => 10000,
        'default' => 5000,
        'selectors' => [
          "{{WRAPPER}} .banner-circle-nav .active svg" => 'transition-duration: {{SIZE}}ms;',
        ],
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
        'range' => [
          'px' => [
            'max' => 1500,
          ],
        ],
        'selectors' => [
          "{{WRAPPER}} .banner.banner-items, {{WRAPPER}} .banner.banner-items .cell" => 'height: {{SIZE}}{{UNIT}} !important;',
        ],
      ]
    );

    $this->add_control(
      'default_color',
      [
        'label' => __('Default Color', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          "inherit" => esc_html__("Inherit", "novo"),
          "white" => esc_html__("White", "novo"),
          "black" => esc_html__("Black", "novo"),
          //"custom" => esc_html__("Custom", "novo"),
        ],
        'default' => 'inherit'
      ]
    );

    $this->add_control(
      'slider_animation',
      [
        'label' => __('Animation', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => '',
        'options' => [
          '' => __('Inherit', 'novo'),
          'slide-wave' => __('Slide Wave', 'novo'),
          'zoom-in' => __('ZoomIn', 'novo'),
          'zoom-out' => __('ZoomOut', 'novo'),
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
        'default' => 'right-bottom',
        'options' => [
          'left' => __('Left Bottom', 'novo'),
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
            'threads',
            'thumb-tack',
            'tripadvisor',
            'tumblr',
            'twitch',
            'x-twitter',
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
      'banner_about_block', [
        'label' => __('About Block', 'novo'),
      ]
    );

    $this->add_control(
      'about_block',
      [
        'label' => __('About block', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $this->add_control(
      'about_label',
      [
        'label' => __('Button label', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => __('About', 'novo'),
        'condition' => [
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_type',
      [
        'label' => __('Type', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'content',
        'options' => [
          "content" => esc_html__("Content", "novo"),
          "custom_link" => esc_html__("Custom Link", "novo"),
        ],
        'condition' => [
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_link',
      [
        'label' => __('Custom link', 'novo'),
        'type' => \Elementor\Controls_Manager::URL,
        'placeholder' => __('https://your-link.com', 'novo'),
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
        'condition' => [
          'about_type' => 'custom_link',
        ],
      ]
    );

    $this->add_control(
      'about_show_bg_text',
      [
        'label' => __('Show Bg Text', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'on',
        'condition' => [
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_image',
      [
        'label' => __('About image', 'novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_sub_heading',
      [
        'label' => __('Sub Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 3,
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 3,
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_text',
      [
        'label' => __('Text', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 3,
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_read_more_link',
      [
        'label' => __('Read More link', 'novo'),
        'type' => \Elementor\Controls_Manager::URL,
        'placeholder' => __('https://your-link.com', 'novo'),
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_read_more_link_title',
      [
        'label' => __('Read More Title', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'about_block' => 'on',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'banner_button_category', [
        'label' => __('Category', 'novo'),
      ]
    );

    $this->add_control(
      'categories',
      [
        'label' => __('Show categories', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $this->add_control(
      'categories_label',
      [
        'label' => __('Button label', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'categories' => 'on',
        ],
      ]
    );

    $this->add_control(
      'categories_source',
      [
        'label' => __('Categories source', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => '',
        'options' => [
          "" => esc_html__("", "novo"),
          "blog" => esc_html__("Blog", "novo"),
          "portfolio" => esc_html__("Portfolio", "novo"),
          "custom" => esc_html__("Custom", "novo"),
        ],
        'condition' => [
          'categories' => 'on',
        ],
      ]
    );

    $this->add_control(
      'categories_show_description',
      [
        'label' => __('Show Description', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
        'condition' => [
          'categories_source' => 'blog',
          'categories_source' => 'portfolio',
          'categories' => 'on',
        ],
      ]
    );

    $this->add_control(
      'categories_blog_items',
      [
        'label' => __('Categories', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'multiple' => true,
        'options' => $this->get_all_categories('category'),
        'condition' => [
          'categories_source' => 'blog',
          'categories' => 'on',
        ],
      ]
    );

    $this->add_control(
      'categories_portfolio_items',
      [
        'label' => __('Categories', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'multiple' => true,
        'options' => $this->get_all_categories('pt-portfolio-category'),
        'condition' => [
          'categories_source' => 'portfolio',
          'categories' => 'on',
        ],
      ]
    );

    $repeater = new \Elementor\Repeater();
    $repeater->add_control(
      'category_label', [
        'label' => __('label', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => __('List Title', 'novo'),
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'category_image',
      [
        'label' => __('Category Image', 'novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control(
      'category_link',
      [
        'label' => __('Category link', 'novo'),
        'type' => \Elementor\Controls_Manager::URL,
        'placeholder' => __('https://your-link.com', 'novo'),
        'show_external' => true,
        'default' => [
          'url' => '',
          'is_external' => true,
          'nofollow' => true,
        ],
      ]
    );

    $this->add_control(
      'category_items_list',
      [
        'label' => __('Categories Items', 'novo'),
        'type' => \Elementor\Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [],
        'title_field' => '{{{ category_label }}}',
        'condition' => [
          'categories_source' => 'custom',
          'categories' => 'on',
        ],
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
      'image', [
        'label' => __('Background image', 'novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control(
      'video_url',
      [
        'label' => __('Background Video url', 'novo'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
        "description" => esc_html__("Source YouTube, Vimeo and mp4 file", "pt-addons"),
      ]
    );

    $repeater->add_control(
      'controls',
      [
        'label' => __('Control buttons', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'off',
        'default' => 'off',
        'condition' => [
          'video_url!' => '',
        ],
      ]
    );

    $repeater->add_control(
      'mute',
      [
        'label' => __('Mute', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'off',
        'default' => 'off',
        'condition' => [
          'video_url!' => '',
        ],
      ]
    );

    $repeater->add_control(
      'play_in_lightbox',
      [
        'label' => __('Play video in lightbox', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
        'condition' => [
          'video_url!' => '',
        ],
      ]
    );

    $repeater->add_control(
      'sub_heading',
      [
        'label' => __('Sub Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
      ]
    );

    $repeater->add_control(
      'heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        "description" => wp_kses_post(__("Wrap the text in { } if you want the text to be another color.<br>Example: Minds your work {level}<br>If you want changes words:{Design||Work||Logo}", "pt-addons")),
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
      'link_play',
      [
        'label' => __('Play icon', 'novo'),
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
      'css_animation',
      [
        'label' => __('Entrance Animation', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'top-to-bottom' => esc_html__('Top to bottom', 'pt-addons'),
          'bottom-to-top' => esc_html__('Bottom to top', 'pt-addons'),
          'left-to-right' => esc_html__('Left to right', 'pt-addons'),
          'right-to-left' => esc_html__('Right to left', 'pt-addons'),
          'appear' => esc_html__('Appear from center', 'pt-addons'),
        ],
        'description' => esc_html__('Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'pt-addons'),
      ]
    );

    $repeater->add_control(
      'text_color',
      [
        'label' => __('Item Color', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          "inherit" => esc_html__("Inherit", "novo"),
          "white" => esc_html__("White", "novo"),
          "black" => esc_html__("Black", "novo"),
          //"custom" => esc_html__("Custom", "novo"),
        ],
        'default' => 'inherit'
      ]
    );

    $repeater->add_control(
      'inner_shadow',
      [
        'label' => __('Inner shadow', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $repeater->add_control(
      'decor_elements',
      [
        'label' => __('Decor Elements', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('On', 'novo'),
        'label_off' => __('Off', 'novo'),
        'return_value' => 'on',
        'default' => 'off',
      ]
    );

    $repeater->add_control(
      'color_overlay',
      [
        'label' => __('Color Overlay', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}:after' => 'content: "";position: absolute;top: 0;left: 0;right: 0;bottom: 0;z-index: 1;pointer-events: none; background-color: {{VALUE}};',
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
      'text_color_hex',
      [
        'label' => __('Color', 'novo'),
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
        ],
      ]
    );

    $repeater->add_control(
      'heading_style',
      [
        'label' => __('Heading style', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'default',
        'options' => [
          'default' => esc_html__('Default', 'pt-addons'),
          'number' => esc_html__('Decor line sub heading', 'pt-addons'),
          'decor-line' => esc_html__('Decor line after heading', 'pt-addons'),
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
      'banner_item_sub_heading',
      [
        'label' => __('Sub Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'banner_items_sub_typo',
        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cell .sub-heading, {{WRAPPER}} {{CURRENT_ITEM}} .cell .sub-h',
      ]
    );

    $repeater->add_control(
      'sub_text_color_hex',
      [
        'label' => __('color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .cell .sub-heading, {{WRAPPER}} {{CURRENT_ITEM}} .cell .sub-h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $repeater->add_control(
      'banner_item_desc',
      [
        'label' => __('Text', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'banner_items_desc_typo',
        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cell .text',
      ]
    );

    $repeater->add_control(
      'desc_color_hex',
      [
        'label' => __('color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .cell .text' => 'color: {{VALUE}};',
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
      'link_style',
      [
        'label' => __('Default Color', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'style1',
        'options' => [
          'style1' => __('Style 1', 'novo'),
          'style2' => __('Style 2', 'novo'),
        ],
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
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style2' => 'color: {{VALUE}};',
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
          '{{WRAPPER}} {{CURRENT_ITEM}} .button-style2:hover' => 'color: {{VALUE}};',
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
        'condition' => [
          'link_style' => 'style1',
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
        'condition' => [
          'link_style' => 'style1',
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
        'condition' => [
          'link_style' => 'style1',
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
        'condition' => [
          'link_style' => 'style1',
        ],
      ]
    );

    $this->add_control(
      'banner_items_list',
      [
        'label' => __('Banner Items', 'novo'),
        'type' => \Elementor\Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ heading }}}',
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
          '{{WRAPPER}} .banner-social-buttons .item' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'social_buttons_hover_color_hex',
      [
        'label' => __('Hover color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-social-buttons .item:hover' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'about_us_style_tab',
      [
        'label' => __('About Block', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_sub_heading_heading',
      [
        'label' => __('Sub Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'about_sub_text_heading',
        'selector' => '{{WRAPPER}} .banner-about .sub-h',
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_sub_heading_hex',
      [
        'label' => __('Sub Heading Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-about .sub-h' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'about_type' => 'content',
          'about_block' => 'on',
        ],
      ]
    );

    $this->add_control(
      'about_heading_heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'about_heading_typo',
        'selector' => '{{WRAPPER}} .banner-about .heading-deco h3',
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'about_heading_hex',
      [
        'label' => __('Heading Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-about .heading-deco h3r' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'about_text_heading',
      [
        'label' => __('Text', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'about_text_heading_typo',
        'selector' => '{{WRAPPER}} .banner-about .text-p',
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'about_text_hex',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-about .text-p' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'about_button_heading',
      [
        'label' => __('Button', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'about_button_heading',
        'selector' => '{{WRAPPER}} .banner-about .button-style2',
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'about_button_hex',
      [
        'label' => __('Button Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-about .button-style2:not(:hover)' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->add_control(
      'about_button_hover_hex',
      [
        'label' => __('Button Hover Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .banner-about .button-style2:hover' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'about_type' => 'content',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'caregories_style_tab',
      [
        'label' => __('Category', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'categories' => 'on',
        ],
      ]
    );

    $this->add_control(
      'categories_index_number_hex',
      [
        'label' => __('Index Number Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .banner-categories .item a .num' => 'color: {{VALUE}}; opacity: 1;',
        ],
        'condition' => [
          'categories_source!' => '',
        ],
      ]
    );

    $this->add_control(
      'categories_title_hex',
      [
        'label' => __('Title Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .banner-categories .item a .h' => 'color: {{VALUE}};',
        ],
        'condition' => [
          'categories_source!' => '',
        ],
      ]
    );

    $this->add_control(
      'categories_vertical_align',
      [
        'label' => __('Vertical Align', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'bottom',
        'options' => [
          "top" => esc_html__("Top", "novo"),
          "middle" => esc_html__("Middle", "novo"),
          "bottom" => esc_html__("Bottom", "novo"),
        ],
        'condition' => [
          'categories_source!' => '',
        ],
      ]
    );

    $this->add_control(
      'categories_text_align',
      [
        'label' => __('Text Align', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'left',
        'options' => [
          "left" => esc_html__("Left", "novo"),
          "center" => esc_html__("Center", "novo"),
          "right" => esc_html__("Right", "novo"),
        ],
        'condition' => [
          'categories_source!' => '',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'categories_index_typo',
        'selector' => '{{WRAPPER}} .banner-categories .item a .num',
        'condition' => [
          'categories_source!' => '',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'categories_title_font_size',
        'label' => __('Title Typography', 'novo'),
        'selector' => '{{WRAPPER}} .banner-categories .item a .h',
        'condition' => [
          'categories_source!' => '',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();

    if (!is_array($settings['banner_items_list']) || count($settings['banner_items_list']) == 0) {
      return false;
    }

    $this->add_render_attribute('wrapper', [
      'class' => 'banner-wrapper banner-area banner-' . uniqid(),
      'style' => '--transition-speed: '.($settings['speed']+2000).'ms;'
    ]);

    $this->add_render_attribute('banner-items', [
      'class' => 'banner banner-items banner-' . uniqid(),
      'id' => 'banner-' . uniqid(),
    ]);

    if ($settings['slider_animation']) {
      $this->add_render_attribute('banner-items', 'class', 'animation-' . $settings['slider_animation']);
    }

    if (array_key_exists('height', $settings) && !empty($settings['height'])) {
      $this->add_render_attribute('banner-items', 'class', 'fixed-height');
  }
  

    if ($settings['external_indent'] == 'on') {
      $this->add_render_attribute('wrapper', 'class', 'external-indent');
    }

    if($settings['default_color'] != 'inherit') {
      $this->add_render_attribute('wrapper', 'class', 'banner-color-'.$settings['default_color']);
    }

    if ($settings['arrows'] == 'on') {
      $this->add_render_attribute('banner-items', 'class', 'arrows-' . $settings['arrows_position']);
    }

    if ($settings['dots'] == 'on') {
      $this->add_render_attribute('banner-items', 'class', 'pagination-' . $settings['dots_position']);
    }


    if ($settings['dots'] == 'on' && $settings['dots_position'] == 'bottom') {
      $this->add_render_attribute('wrapper', 'class', 'with-circle-nav');
    }

    $params_json = array(
      'loop' => $settings["infinite_loop"] == 'on' ? true : false,
      'speed' => intval($settings['speed']),
      'autoplay' => false,
      'arrows' => $settings["arrows"] == 'on' ? true : false,
      'dots' => $settings["dots"] == 'on' ? true : false,
      'dots_position' => $settings['dots_position'],
      'animation' => $settings['slider_animation']
    );

    if ($settings["autoplay"] == 'on') {
      $params_json['autoplay'] = [
          'delay' => isset($settings['autoplay_speed']) ? intval($settings['autoplay_speed']) : 3000, // Set a default value if not set
          'disableOnInteraction' => false,
          'pauseOnMouseEnter' => $settings['pauseohover'] == 'on' ? true : false
      ];
      if (!$params_json['loop']) {
          $params_json['autoplay']['stopOnLastSlide'] = true;
      }
  }
    
    $this->add_render_attribute(
      [
        'wrapper' => [
          'data-settings' => [
            wp_json_encode($params_json),
          ],
        ],
      ]
    );

    ?>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<?php echo $this->get_social_buttons(); ?>
				<?php echo $this->get_categories_carousel(); ?>
				<?php echo $this->get_about_block(); ?>
				<?php echo $this->get_banner_categories_block(); ?>

				<div <?php echo $this->get_render_attribute_string('banner-items'); ?>>
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php echo $this->banner_items(); ?>
            </div>
          </div>
          
          <?php if($settings["arrows"] == 'on') {
            echo '<div class="owl-nav"><div class="owl-prev basic-ui-icon-left-arrow"></div><div class="owl-next basic-ui-icon-right-arrow"></div></div>';
          } ?>

          <?php if($settings['dots'] == 'on') {
            if($settings['dots_position'] == 'bottom') {
              echo '<div class="banner-circle-nav container"></div>';
            } else {
              echo '<div class="owl-dots"></div>';
            }
          } ?>
          
				</div>

			</div>
		<?php
}

  public function get_banner_categories_block() {
    if ($this->settings['categories'] !== 'on' && $this->settings['about_block'] !== 'on') {
      return false;
    }

    ob_start();
    ?>
			<div class="banner-right-buttons widget-elementor">
				<div class="cell">
					<?php if ($this->settings['categories'] == 'on' && (!empty($this->settings['categories_portfolio_items']) || !empty($this->settings['categories_blog_items']) || !empty($this->settings['category_items_list']))):
            if (empty($this->settings['categories_label'])):
              $this->settings['categories_label'] = esc_html__('Categories', 'novo');
            endif; ?>
            <div class="button category">
              <span class="h"><?php echo esc_html($this->settings['categories_label']); ?></span>
              <span class="close">
                <i class="basic-ui-icon-cancel"></i>
                <?php if (yprm_get_theme_setting('tr_close')): ?>
                  <?php echo yprm_get_theme_setting('tr_close'); ?>
                <?php else: ?>
									<?php echo esc_html__('Close', 'novo'); ?>
								<?php endif;?>
							</span>
						</div>
					<?php elseif ($this->settings['categories'] == 'on' && $this->settings['categories_source'] == 'custom_link'):
            if (empty($this->settings['categories_label'])) {
              $categories_label = esc_html__('Categories', 'novo');
            }

            $target = $this->settings['categories_link']['is_external'] ? ' target="_blank"' : '';
            $nofollow = $this->settings['categories_link']['nofollow'] ? ' rel="nofollow"' : '';
          ?>
            <a href="<?php echo esc_url($this->settings['categories_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> class="button category">
              <span class="h"><?php echo esc_html($this->settings['categories_label']); ?></span>
            </a>
          <?php endif;?>

					<?php if ($this->settings['about_block'] == 'on'):
            if (empty($this->settings['about_label'])):
              $this->settings['about_label'] = esc_html__('About', 'novo');
            endif;

            if ($this->settings['about_type'] == 'content' || $this->settings['about_type'] == 'shortcode_from_page'): ?>
              <div class="button about">
                <span class="h"><?php echo esc_html($this->settings['about_label']); ?></span>
                <span class="close"><i class="basic-ui-icon-cancel"></i>
                  <?php if (yprm_get_theme_setting('tr_close')): ?>
                    <?php echo yprm_get_theme_setting('tr_close'); ?>
                  <?php else: ?>
										<?php echo esc_html__('Closed', 'novo'); ?>
									<?php endif;?>
								</span>
							</div>
						<?php else:
              $target = $this->settings['about_link']['is_external'] ? ' target="_blank"' : '_self';
              $nofollow = $this->settings['about_link']['nofollow'] ? ' rel="nofollow"' : '';
            ?>
              <a href="<?php echo esc_url($this->settings['about_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> class="button category">
                <span class="h"><?php echo esc_html($this->settings['about_label']); ?></span>
              </a>
            <?php endif; ?>
          <?php endif; ?>
				</div>
			</div>
		<?php echo ob_get_clean();
  }

  public function get_about_block() {
    if ($this->settings['about_block'] !== 'on' || $this->settings['about_type'] == '') {
      return false;
    }

    ob_start();
    ?>
			<div class="banner-about <?php echo esc_attr($this->settings['about_type']); ?>">
				<div class="row">
					<?php if ($this->settings['about_type'] == 'content'):
            echo $this->about_content_text();
          else:
            echo $this->about_link_content();
          endif; ?>
				</div>
			</div>
		<?php echo ob_get_clean();
  }

  public function about_content_text() {
    ob_start();
    ?>
			<?php if (isset($this->settings['about_image']['id']) && !empty($this->settings['about_image']['id'])): ?>
				<div class="image col-12 col-md-6" style="<?php echo \yprm_get_image($this->settings['about_image']['id'], 'bg', 'full'); ?>"></div>
        <div class="text col-12 col-md-6">
			<?php else: ?>
				<div class="text col-12">
			<?php endif;?>
				<div class="wrap">
					<div class="cell">
						<?php if (isset($this->settings['about_image']['id']) && !empty($this->settings['about_image']['id'])): ?>
							<div class="image" style="<?php echo \yprm_get_image($this->settings['about_image']['id'], 'bg', 'full'); ?>"></div>
						<?php endif;?>
						<?php echo $this->about_sub_heading(); ?>
						<?php echo $this->about_heading(); ?>
						<?php echo $this->about_text(); ?>
						<?php echo $this->about_read_more_button(); ?>
					</div>
				</div>
				<?php echo $this->about_show_bg_text(); ?>
			</div>
		<?php echo ob_get_clean();
  }

  public function about_sub_heading() {
    if ($this->settings['about_sub_heading'] == '') {return false;}

    ob_start(); ?>
			<div class="sub-h"><?php echo wp_kses_post(nl2br($this->settings['about_sub_heading'])); ?></div>
		<?php echo ob_get_clean();
  }

  public function about_heading() {
    if ($this->settings['about_heading'] == '') {return false;}

    ob_start(); ?>
			<div class="heading-decor"><h3><?php echo yprm_heading_filter(nl2br($this->settings['about_heading'])); ?></h3></div>
		<?php echo ob_get_clean();
  }

  public function about_text() {
    if ($this->settings['about_text'] == '') {return false;}

    ob_start(); ?>
			<div class="text-p"><?php echo wp_kses_post(nl2br($this->settings['about_text'])); ?></div>
		<?php echo ob_get_clean();
  }

  public function about_read_more_button() {
    if ($this->settings['about_text'] == '') {return false;}

    ob_start();
    if (is_array($this->settings['about_read_more_link']) && isset($this->settings['about_read_more_link']['url']) && !empty($this->settings['about_read_more_link']['url'])):
      $target = $this->settings['about_read_more_link']['is_external'] ? ' target="_blank"' : '';
      $nofollow = $this->settings['about_read_more_link']['nofollow'] ? ' rel="nofollow"' : '';
    ?>
      <a href="<?php echo esc_url($this->settings['about_read_more_link']['url']); ?>" class="button-style2" <?php echo $target; ?> <?php echo $nofollow; ?>>
        <?php echo esc_html($this->settings['about_read_more_link_title']); ?>
      </a>
    <?php endif;
    echo ob_get_clean();
  }

  public function about_show_bg_text() {
    ob_start();
    if ($this->settings['about_show_bg_text'] == 'on'): ?>
			<div class="bg-word">
				<?php if ($this->settings['about_label']): ?>
					<?php echo wp_kses_post($this->settings['about_label']); ?>
				<?php else: ?>
					<?php echo esc_html__('About', 'pt-addons'); ?>
				<?php endif;?>
			</div>
		<?php endif;
    echo ob_get_clean();
  }

  public function about_link_content() {

  }

  public function get_social_buttons() {
    if ($this->settings['social_buttons'] !== 'on' || !$this->settings['social_items_list']) {
      return false;
    }

    ob_start(); ?>
		<div class="banner-social-buttons">
			<div class="links">
				<?php echo $this->render_social_items(); ?>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  public function render_social_items() {

    ob_start();

    foreach ($this->settings['social_items_list'] as $index => $item):
      if (!isset($item['social_icon']['value']) || empty($item['social_icon']['value'])) {
        continue;
      }

      if (!empty($item['social_icon']['value'])) {
        $social = str_replace('fa fa-', '', $item['social_icon']['value']);
      }

      if ('svg' === $item['social_icon']['library']) {
        $social = get_post_meta($item['social_icon']['value']['id'], '_wp_attachment_image_alt', true);
      } else {
        $social = explode(' ', $item['social_icon']['value'], 2);
        if (empty($social[1])) {
          continue;
        }

        $social = str_replace('fa-', '', $social[1]);
      }

      $link_key = 'link_' . $index;

      $this->add_render_attribute($link_key, 'class', 'item');

      $this->add_link_attributes($link_key, $item['link']);

    ?>
      <a <?php echo $this->get_render_attribute_string($link_key); ?>>
        <?php \Elementor\Icons_Manager::render_icon($item['social_icon']);?>
        <span><?php echo $social; ?></span>
      </a>
    <?php endforeach;
    echo ob_get_clean();
  }

  public function get_categories_carousel() {
    if ($this->settings['categories'] !== 'on' || $this->settings['categories_source'] == '') {
      return false;
    }

    $this->add_render_attribute('banner-categories', [
      'class' => 'banner-categories',
    ]);

    $this->add_render_attribute('banner-categories', 'class', 'vertical-align-' . $this->settings['categories_vertical_align']);
    $this->add_render_attribute('banner-categories', 'class', 'text-align-' . $this->settings['categories_text_align']);

    ob_start();
    ?>
			<div <?php echo $this->get_render_attribute_string('banner-categories'); ?>>
				<?php
          if ($this->settings['categories_source'] == 'blog') {
            echo $this->categories_carousel();
          } elseif ($this->settings['categories_source'] == 'portfolio') {
            echo $this->portfolio_categories_carousel();
          } elseif ($this->settings['categories_source'] == 'custom') {
            echo $this->custom_catgories_carousel();
          }
        ?>
			</div>
		<?php echo ob_get_clean();
  }

  public function custom_catgories_carousel() {
    if ($this->settings['category_items_list'] == '') {
      return false;
    }

    ob_start();
    foreach ($this->settings['category_items_list'] as $item):
      if (!$item) {
        continue;
      }

      $target = $item['category_link']['is_external'] ? ' target="_blank"' : '';
      $nofollow = $item['category_link']['nofollow'] ? ' rel="nofollow"' : '';
    ?>
      <div class="item">
        <a href="<?php echo esc_url($item['category_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> style="<?php echo \yprm_get_image($item['category_image']['id'], 'bg', 'full'); ?>">
          <span>
            <span class="num"></span>
            <span class="h"><?php echo esc_html($item['category_label']); ?></span>
          </span>
        </a>
      </div>
    <?php endforeach;
    echo ob_get_clean();
  }

  public function categories_carousel() {
    if (empty($this->settings['categories_blog_items'])) {
      return false;
    }

    ob_start();

    foreach ($this->settings['categories_blog_items'] as $item) {
      $id = $item;
      $term = get_term((int) $id, 'category');
      if (empty($term) || is_wp_error($term)) {
        continue;
      }

      $term_link = get_term_link((int) $id, 'category');
      $image = "";
      if (function_exists('get_field') && $cat_image_array = get_field('category_image', $term)) {
        $image = $cat_image_array['url'];
      }
      $description = '';
      if (!empty($term->description)) {
        $description = mb_strimwidth(strip_tags($term->description), 0, 120, '...');
      }
    ?>
      <div class="item">
        <a href="<?php echo esc_url($term_link); ?>" <?php if ($image): ?>style="background-image: url(<?php echo esc_url($image); ?>);"<?php endif;?>>
          <span>
            <span class="num"></span>
            <span class="h"><?php echo esc_html($term->name); ?></span>
            <?php if ($this->settings['categories_show_description'] == 'on' && $description): ?>
              <span class="desc"><?php echo wp_kses_post($description); ?></span>
            <?php endif;?>
          </span>
        </a>
      </div>
    <?php }

    echo ob_get_clean();
  }

  public function portfolio_categories_carousel() {
    if ($this->settings['categories_portfolio_items'] == '') {
      return false;
    }

    ob_start();

    foreach ($this->settings['categories_portfolio_items'] as $item) {
      $id = $item;
      $term = get_term((int) $id, 'pt-portfolio-category');
      if (empty($term) || is_wp_error($term)) {
        continue;
      }

      $term_link = get_term_link((int) $id, 'pt-portfolio-category');
      $image = "";
      if (function_exists('get_field') && $cat_image_array = get_field('category_image', $term)) {
        $image = $cat_image_array['url'];
      }
      $description = '';
      if (!empty($term->description)) {
        $description = mb_strimwidth(strip_tags($term->description), 0, 120, '...');
      }
    ?>
      <div class="item">
        <a href="<?php echo esc_url($term_link); ?>" style="background-image: url(<?php echo esc_url($image); ?>);">
          <span>
            <span class="num"></span>
            <span class="h"><?php echo esc_html($term->name); ?></span>
            <?php if ($this->settings['categories_show_description'] == 'on' && $description): ?>
              <span class="desc"><?php echo wp_kses_post($description); ?></span>
            <?php endif;?>
          </span>
        </a>
      </div>
    <?php }

    echo ob_get_clean();
  }

  public function banner_items() {
    $settings = $this->get_settings_for_display();

    if (!$settings['banner_items_list']) {
      return false;
    }

    // Fill $html var with data
    $item_attr = $animation = $image_animation_attr = "";

    foreach ($settings['banner_items_list'] as $index => $item):
      $tab_count = $index + 1;

      $tab_title_setting_key = $index;

      $this->add_render_attribute($tab_title_setting_key, 'class', [
        'item',
        'swiper-slide',
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

      $play_button_html = '';
      if (!empty($item['video_url']) && $item['play_in_lightbox'] == 'on') {
        $embed_params = $this->get_embed_params($item);
        $embed_options = $this->get_embed_options($item);

        $popup_array = [];
        $popup_array['video'] = [
          'html' => Embed::get_embed_html($item['video_url'], $embed_params, $embed_options),
          'w' => 1920,
          'h' => 1080
        ];

        wp_enqueue_script( 'background-video' );
        wp_enqueue_script( 'video' );

        $play_button_html = '<div class="play-button-block popup-gallery images"><a href="#" data-type="video" data-popup-json="'.esc_attr(json_encode($popup_array)).'" data-id="0"><i class="music-and-multimedia-play-button"></i></a></div>';
      }

      $decor_elements_html = '';
      if ($item['decor_elements'] == 'on') {
        $decor_elements_html = '<div class="banner-decor-elements"><div></div><div></div><div></div><div></div></div>';
      }

      if (!empty($item['css_animation'])) {
        $animation = ' ' . yprm_get_animation_css($item['css_animation']);
      }

      $this->add_render_attribute($tab_title_setting_key, 'class', $item['text_align']);

      if ($item['inner_shadow'] == 'on') {
        $this->add_render_attribute($tab_title_setting_key, 'class', 'with-shadow');
      }

      if (isset($item['text_color']) && $item['text_color'] != 'custom') {
        $this->add_render_attribute($tab_title_setting_key, 'class', $item['text_color']);
      }
    ?>
      <div <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>>
        <div class="bg-image" style="<?php echo esc_attr($item_attr); ?>"></div>
        <?php if (empty($play_button_html)): ?>
          <?php echo $this->inline_video_html($item); ?>
        <?php endif;?>
        <?php echo $decor_elements_html; ?>
        <div class="container">
          <div class="cell <?php echo esc_attr($item['text_vertical_align'] . $animation); ?>">
						<?php if ($item['heading_style'] == 'number'): ?>
							<div class="heading heading-with-num-type2">
								<div class="top">
									<div class="num"></div>
									<?php if ($item['sub_heading']): ?>
										<div class="sub-h"><?php echo wp_kses_post(nl2br($item['sub_heading'])); ?></div>
									<?php endif;?>
								</div>
								<<?php echo esc_attr($item['heading_size']); ?> class="h">
									<?php echo yprm_heading_filter(nl2br($item['heading'])); ?>
								</<?php echo esc_attr($item['heading_size']); ?>>
							</div>
						<?php elseif ($item['heading_style'] == 'decor-line'): ?>
							<div class="heading heading-decor">
								<?php if ($item['sub_heading']): ?>
									<div class="sub-heading"><?php echo wp_kses_post(nl2br($item['sub_heading'])); ?></div>
								<?php endif;?>
								<<?php echo esc_attr($item['heading_size']); ?> class="h">
									<?php echo yprm_heading_filter(nl2br($item['heading'])); ?>
								</<?php echo esc_attr($item['heading_size']); ?>>
							</div>
						<?php else: ?>
							<div class="heading">
								<?php if ($item['sub_heading']): ?>
									<div class="sub-heading"><?php echo wp_kses_post(nl2br($item['sub_heading'])); ?></div>
								<?php endif;?>
								<<?php echo esc_attr($item['heading_size']); ?> class="h">
									<?php echo yprm_heading_filter(nl2br($item['heading'])); ?>
								</<?php echo esc_attr($item['heading_size']); ?>>
							</div>
						<?php endif;?>
						<?php if ($item['text']): ?>
							<div class="text"><?php echo wp_kses_post(nl2br($item['text'])); ?></div>
					    <?php endif;?>
					    <?php if ($item['link_button'] == 'on' && !empty($item['link']['url']) && !empty($item['link_text'])): ?>
						    <a href="<?php echo esc_url($item['link']['url']); ?>" class="button-<?php echo esc_attr($item['link_style']); ?>">
						    	<?php if ($item['link_play'] == 'on'): ?>
							        <i class="multimedia-icon-play"></i>
							    <?php endif;?>
							    <span><?php echo $item['link_text']; ?></span>
							</a>
						<?php endif;?>
						<?php echo $play_button_html; ?>
					</div>
				</div>
			</div>
		<?php endforeach;
  }

  public function inline_video_html($item) {

    ob_start();
    $item['background_video_controls'] = $item['controls'] ? 'on' : 'off';
    $item['background_video_mute'] = $item['mute'] ? 'on' : 'off';
    $item['bg_overlay'] = 'on';
    echo yprm_build_bg_overlay($item, 'banner-item-' . uniqid());
    echo ob_get_clean();
  }

  public function get_embed_params($item) {

    $params = [];

    $params_dictionary = [];

    $service = $this->identify_service($item['video_url']);

    if ('youtube' === $service) {
      $params_dictionary = [
        'controls',
        'mute',
      ];
    } elseif ('vimeo' === $service) {
      $params_dictionary = [
        'mute' => 'muted',
        'vimeo_title' => 'title',
        'vimeo_portrait' => 'portrait',
        'vimeo_byline' => 'byline',
      ];

      $params['autopause'] = '0';
    }

    foreach ($params_dictionary as $key => $param_name) {
      $setting_name = $param_name;

      if (is_string($key)) {
        $setting_name = $key;
      }

      $setting_value = $item[$setting_name] ? '1' : '0';

      $params[$param_name] = $setting_value;
    }

    return $params;
  }

  private function get_embed_options($item) {

    $embed_options = [];

    $embed_options['lazy_load'] = '0';

    return $embed_options;
  }

  public function identify_service($url) {
    if (preg_match('%youtube|youtu\.be%i', $url)) {
      return 'youtube';
    } elseif (preg_match('%vimeo%i', $url)) {
      return 'vimeo';
    } elseif (preg_match('/^.*\.(mp4|mov)$/i', $url) || preg_match('/^.*\.(mp4|mov)$/i', $url)) {
      return 'mp4';
    }

    return null;
  }

  public function get_url_id($url) {
    $service = self::identify_service($url);
    if ($service == 'youtube') {
      return self::get_youtube_id($url);
    } elseif ($service == 'vimeo') {
      return self::get_vimeo_id($url);
    }
    return null;
  }

  public function get_all_categories($taxonomy, $param = 'All') {

    $args = array(
      'hide_empty' => true,
    );

    $terms = get_terms($taxonomy, $args);
    $result = array();

    if (!empty($terms) && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        $name = get_category_parents($term->term_id);
        $name = trim($name, '/');
        $result[$term->term_id] = 'ID [' . $term->term_id . '] ' . $name;
      }
    }

    return $result;
  }
}
