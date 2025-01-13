<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Gallery_External_Link_Widget extends Widget_Base {
  protected $settings;
  protected $paged;
  protected $project;
  protected $displacement_url;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    $this->displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern4.jpg';

    if (is_front_page()) {
      $this->paged = (get_query_var('page')) ? get_query_var('page') : 1;
    } else {
      $this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }

    wp_register_script('gallery-external-link-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/gallery-external-link.js', array('jquery', 'isotope', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_gallery_external_link';
  }

  public function get_title() {
    return esc_html__('Gallery (External link)', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-gallery-external-link';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode()) {
      return ['gallery-external-link-handler', 'swiper11', 'isotope', 'pt-scattered-portfolio', 'flipster', 'pt-load-posts', 'owl-carousel'];
    }

    $scripts = [];
    $type = $this->get_settings_for_display('type');
    $navigation = $this->get_settings_for_display('navigation');

    if ($type === 'carousel-type2') {
      $scripts[] = 'swiper';
    } elseif ($type === 'flow') {
      $scripts[] = 'flipster';
    } elseif ($type == 'carousel' || $type == 'horizontal') {
      $scripts[] = 'owl-carousel';
    } elseif ($type == 'masonry') {
      $scripts[] = 'isotope';
    }

    if ($navigation == 'load_more' || $navigation == 'load_more_on_scroll') {
      $scripts[] = 'pt-load-posts';
    }

    $scripts[] = 'gallery-external-link-handler';
    return $scripts;
  }

  public function get_style_depends() {
    if (Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode()) {
      return ['swiper', 'flipster', 'owl-carousel'];
    }

    $styles = [];
    $type = $this->get_settings_for_display('type');

    if ($type === 'carousel-type2') {
      $styles[] = 'swiper';
    } elseif ($type === 'flow') {
      $styles[] = 'flipster';
    } elseif ($type == 'carousel' || $type == 'horizontal') {
      $styles[] = 'owl-carousel';
    }

    return $styles;
  }

  protected function register_controls() {

    $this->start_controls_section(
      'general_section', [
        'label' => __('Layout', 'novo'),
      ]
    );

    $this->add_control(
      'type',
      [
        'label' => __('Type', 'novo'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "grid" => esc_html__("Grid", "novo"),
          "masonry" => esc_html__("Masonry", "novo"),
          "flow" => esc_html__("Flow", "novo"),
          "horizontal" => esc_html__("Horizontal", "novo"),
          "carousel" => esc_html__("Carousel", "novo"),
          "carousel-type2" => esc_html__("Carousel Type 2", "novo"),
          "scattered" => esc_html__("Scattered", "novo"),
        ],
        'default' => 'grid',
      ]
    );

    $this->add_group_control(
      Group_Control_Cols::get_type(),
      [
        'name' => 'cols',
        'label' => esc_html__('Cols', 'pt-addons'),
        'frontend_available' => true,
        'fields_options' => [
          'xs' => [
            'default' => '1',
          ],
          'sm' => [
            'default' => '2',
          ],
          'md' => [
            'default' => '3',
          ],
        ],
        'condition' => [
          'type' => ['grid', 'masonry', 'scattered'],
        ],
      ]
    );

    $this->add_control(
      'hover',
      [
        'label' => __('Hover animation', 'novo'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "none" => esc_html__("Content always is shown", "novo"),
          "gallery" => esc_html__("Gallery", "novo"),
          "type_1" => esc_html__("Type 1", "novo"),
          "type_2" => esc_html__("Type 2", "novo"),
          "type_3" => esc_html__("Type 3", "novo"),
          "type_4" => esc_html__("Type 4", "novo"),
          "type_5" => esc_html__("Type 5", "novo"),
          "type_6" => esc_html__("Type 6", "novo"),
          "type_7" => esc_html__("Type 7", "novo"),
          "type_8" => esc_html__("Type 8", "novo"),
          "type_9" => esc_html__("Type 9", "novo"),
        ],
        'default' => 'type_1',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['grid', 'masonry', 'horizontal', 'flow'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'popup_gallery',
      [
        'label' => __('Popup Gallery', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'return_value' => 'yes',
        'default' => '',
        'condition' => [
          'hover' => 'gallery',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Image_Size::get_type(),
      [
        'name' => 'thumb_size',
        'label' => 'Images Quality',
        'fields_options' => [
          'size' => [
            'default' => 'large'
          ]
        ]
      ]
    );

    $this->add_control(
      'hover_disable',
      [
        'label' => __('Hover disable', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'return_value' => 'yes',
        'default' => '',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['grid', 'masonry', 'horizontal', 'flow'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'width',
      [
        'label' => __('Width', 'novo'),
        'type' => Controls_Manager::SLIDER,
        'default' => [
          'unit' => 'px',
          'size' => 600,
        ],
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 1000,
            'step' => 1,
          ],
        ],
        'condition' => [
          'type' => 'horizontal',
        ],
        'selectors' => [
          '{{WRAPPER}} .portfolio-type-horizontal' => 'height: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .portfolio-item.item .a-img img' => 'height: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .portfolio-item.item .a-img.disable-ratio' => 'width: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .swiper-slide .wrap img' => 'height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_control(
      'gap',
      [
        'label' => __('GAP', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['grid', 'masonry'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'save_aspect_ratio',
      [
        'label' => __('Save Aspect Ratio', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => '',
        'return_value' => 'yes',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['horizontal'],
            ],
          ],
        ],
      ]
    );

    /* $this->add_control(
      'navigation',
      [
        'label' => __('Navigation', 'novo'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "none" => esc_html__("None", "novo"),
          "load_more" => esc_html__("Load More", "novo"),
          "load_more_on_scroll" => esc_html__("Load More On Scroll", "novo"),
          "pagination" => esc_html__("Pagination", "novo"),
        ],
        'default' => 'none',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['grid', 'masonry', 'scattered'],
            ],
          ],
        ],
      ]
    ); */

    /* $this->add_control(
      'count_items',
      [
        'label' => __('Posts Per Page', 'novo'),
        'type' => Controls_Manager::NUMBER,
        'default' => 9,
      ]
    ); */

    $this->end_controls_section();

    $this->start_controls_section(
      'items_section', [
        'label' => __('Items', 'novo'),
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'image',
      [
        'label' => esc_html__( 'Image', 'pt-addons' ),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'title',
      [
        'label' => esc_html__( 'Title', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'desc',
      [
        'label' => esc_html__( 'Description', 'pt-addons' ),
        'type' => Controls_Manager::TEXTAREA,
      ]
    );

    $repeater->add_control(
      'link',
      [
        'label' => esc_html__( 'Link', 'pt-addons' ),
        'type' => Controls_Manager::URL,
        'dynamic' => [
          'active' => true,
          ],
      ]
    );

    $this->add_control('items', [
      'label' => __('Items', 'novo'),
      'type' => Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'title_field' => '{{{ title }}}',
    ]);

    $this->end_controls_section();

    $this->start_controls_section(
      'popup_settings_section', [
        'label' => __('Popup Settings', 'novo'),
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ["grid", "masonry", "flow", "horizontal", "scattered", "carousel",
                "carousel-type2"],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'popup_mode',
      [
        'label' => __('Popup Mode', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'popup_mode_title',
      [
        'label' => __('Popup Mode Title', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'condition' => [
          'popup_mode' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'popup_mode_desc',
      [
        'label' => __('Popup mode descripton', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => '',
        'condition' => [
          'popup_mode' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'project_link',
      [
        'label' => __('Project Link', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => '',
        'condition' => [
          'popup_mode' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'slider_settings_section', [
        'label' => __('Slider Settings', 'novo'),
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['carousel', 'horizontal', 'carousel-type2'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'carousel_nav',
      [
        'label' => __('Carousel navigation', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['horizontal', 'carousel', 'carousel-type2'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'infinite_loop',
      [
        'label' => __('Infinite loop', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['horizontal', 'carousel', 'carousel-type2'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'mousewheel',
      [
        'label' => __('Mousewheel', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['carousel-type2', 'horizontal'],
            ],
          ],
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
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['carousel', 'horizontal'],
            ],
          ],
        ],
        "description" => esc_html__("Speed at which next slide comes.", "novo"),
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => __('Autoplay Slides', 'novo'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['carousel', 'horizontal'],
            ],
          ],
        ],
        "description" => esc_html__("Speed at which next slide comes.", "novo"),
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
        'conditions' => [
          'relation' => 'and',
          'terms' => [
            [
              'name' => 'autoplay',
              'operator' => '=',
              'value' => 'yes',
            ],
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['carousel', 'horizontal'],
            ],
          ],
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'portfolio_fields_section', [
        'label' => __('Fields', 'novo'),
      ]
    );

    $this->add_control(
      'show_heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_desc',
      [
        'label' => __('Description', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'desc_size',
      [
        'label' => __('Desc Size', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'condition' => [
          'show_desc' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();
    
    $this->start_controls_section(
      'general_style',
      [
        'label' => __('Navigation', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'conditions' => [
          'relation' => 'and',
          'terms' => [
            [
              'name' => 'carousel_nav',
              'operator' => '=',
              'value' => 'yes',
            ],
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['flow', 'carousel', 'carousel-type2'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'arrow_color',
      [
        'label' => __('Arrow Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .flipster__button svg' => 'fill: {{VALUE}}',
          '{{WRAPPER}} .owl-nav .owl-prev, {{WRAPPER}} .owl-nav .owl-next' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'arrow_hover_color',
      [
        'label' => __('Arrow Hover Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .flipster__button:hover svg' => 'fill: {{VALUE}}',
          '{{WRAPPER}} .owl-nav .owl-prev:hover, {{WRAPPER}} .owl-nav .owl-next:hover' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'carousel_dots_heading',
      [
        'label' => __('Dots', 'novo'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'dots_color',
      [
        'label' => __('Dots color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .owl-dots' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'dots_hover_color',
      [
        'label' => __('Dots Hover color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .owl-dots:hover' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'dots_active_color',
      [
        'label' => __('Dots Active color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .owl-dots .owl-dot.active' => 'color: {{VALUE}} !important',
          '{{WRAPPER}} .owl-dots .owl-dot.active span' => 'background-color: {{VALUE}} !important',
        ],
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_title_style',
      [
        'label' => __('Title', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'show_heading' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .portfolio-item .content h5' => 'color: {{VALUE}};',
          '{{WRAPPER}} .portfolio-type-carousel .portfolio-item .bottom-content' => 'color: {{VALUE}};',
          '{{WRAPPER}} .portfolio_hover_type_5 .portfolio-item .content h5' => 'color: {{VALUE}};',
          '{{WRAPPER}} .portfolio_hover_none .portfolio-item .content h5:after' => 'background-color:{{VALUE}}',
          '{{WRAPPER}} .portfolio_hover_type_1 .portfolio-item .content h5:after' => 'background-color:{{VALUE}}',
          '{{WRAPPER}} .portfolio_hover_type_3 .portfolio-item .content h5:after' => 'background-color:{{VALUE}}',
          '{{WRAPPER}} .portfolio_hover_type_4 .portfolio-item .content h5:after' => 'background-color:{{VALUE}}',
          '{{WRAPPER}} .portfolio_hover_type_6 .portfolio-item .content h5:after' => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .portfolio_hover_type_2 .portfolio-item .content h5:after' => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .portfolio_hover_type_4 .portfolio-item .content h5:after' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'selector' => '{{WRAPPER}} .portfolio-type-carousel .portfolio-item .bottom-content h5, {{WRAPPER}} .portfolio-type-carousel .portfolio-item .bottom-content h5, .portfolio-item .content h5',
      ]
    );

    $this->add_control(
      'portofio_index_heading',
      [
        'label' => __('Number Index', 'novo'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'hover' => 'type_5',
        ],
      ]
    );

    $this->add_control(
      'index_title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .portfolio_hover_type_5 .portfolio-item .content h5 span' => 'color: {{VALUE}};',
        ],
        'condition' => [
          'hover' => 'type_5',
        ],
      ]
    );

    $this->add_control(
      'hover_bg_color',
      [
        'label' => __('Background Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .portfolio_hover_type_3 .portfolio-item .content, .portfolio_hover_type_6 .portfolio-item .content' => 'background-color: {{VALUE}};-webkit-box-shadow: 0 0 0 6px {{VALUE}};box-shadow: 0 0 0 6px {{VALUE}};',
        ],
        'conditions' => [
          'terms' => [
            [
              'name' => 'hover',
              'operator' => 'in',
              'value' => ['type_3', 'type_6'],
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'hover_border_color',
      [
        'label' => __('Background Border Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .portfolio_hover_type_3 .portfolio-item .content, .portfolio_hover_type_6 .portfolio-item .content' => 'border-color: {{VALUE}};',
        ],
        'conditions' => [
          'terms' => [
            [
              'name' => 'hover',
              'operator' => 'in',
              'value' => ['type_3', 'type_6'],
            ],
          ],
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'index_typography',
        'selector' => '{{WRAPPER}} .portfolio_hover_type_5 .portfolio-item .content h5 span',
        'condition' => [
          'hover' => 'type_5',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_desc_style',
      [
        'label' => __('Description', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'show_desc' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'desc_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .portfolio-item .content p' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'desc_typography',
        'selector' => '{{WRAPPER}} .portfolio-item .content p',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'button_style_section',
      [
        'label' => __('Button', 'marlon-elementor'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'navigation' => 'load_more',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'button_typography',
        'selector' => '{{WRAPPER}} .load-button a.elementor-loadmore-button',
      ]
    );

    $this->add_control(
      'button_text_color',
      [
        'label' => __('Text Color', 'marlon-elementor'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_background_color',
      [
        'label' => __('Background Color', 'marlon-elementor'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'background: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name' => 'button_border',
        'selector' => '{{WRAPPER}} .load-button a.elementor-loadmore-button',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'button_border_radius',
      [
        'label' => __('Border Radius', 'marlon-elementor'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'button_text_padding',
      [
        'label' => __('Text Padding', 'marlon-elementor'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', '%'],
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $this->settings = $this->get_settings_for_display();

    $projects_array = $this->settings['items'];

    if (count($projects_array) < 1) {
      return false;
    }

    $this->set_css_classes();

    ob_start();?>

		<div <?php echo $this->get_render_attribute_string('wrapper') ?>>
			<?php if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered' || $this->settings['type'] == 'carousel'): ?>
				<div <?php echo $this->get_render_attribute_string('inner-wrapper'); ?>>
					<?php $this->render_items()?>
				</div>
				<?php echo $this->get_navigation_html() ?>
			<?php elseif ($this->settings['type'] == 'horizontal'): ?>
				<div <?php echo $this->get_render_attribute_string('inner-wrapper'); ?>>
					<?php $this->render_items()?>
				</div>
			<?php elseif ($this->settings['type'] == 'flow'): ?>
				<div <?php echo $this->get_render_attribute_string('inner-wrapper'); ?>>
					<ul><?php $this->render_items()?></ul>
				</div>
			<?php elseif ($this->settings['type'] == 'carousel-type2'): ?>
				<div <?php echo $this->get_render_attribute_string('inner-wrapper'); ?>>
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php $this->render_items()?>
						</div>
					</div>
				</div>
			<?php endif;?>
		</div>
    <?php echo ob_get_clean();
    wp_reset_postdata();
  }

  protected function set_css_classes() {

    $this->popup_atts = [];

    $this->popup_atts['popupTitle'] = $this->settings['popup_mode_title'] == 'yes' ? true : false;
    $this->popup_atts['popupDesc'] = $this->settings['popup_mode_desc'] == 'yes' ? true : false;

    $this->add_render_attribute('wrapper', [
      'class' => 'portfolio-block elementor-block',
      'id' => 'portfolio-' . uniqid(),
      'data-portfolio-settings' => [
        wp_json_encode(array_filter([
          "loop" => ("yes" == $this->settings["infinite_loop"]) ? true : false,
          "autoplay" => ("yes" == $this->settings["autoplay"]) ? true : false,
          "arrows" => ("yes" == $this->settings["carousel_nav"]) ? true : false,
          "mousewheel" => ("yes" == $this->settings["mousewheel"]) ? true : false,
          "autoplay_speed" => $this->settings['autoplay_speed'],
          "speed" => $this->settings['speed'],
          'type' => $this->settings['type'],
        ])),
      ],
      'data-popup-settings' => json_encode($this->popup_atts)
    ]);

    if ($this->settings['hover'] == 'gallery' && $this->settings['popup_gallery'] == 'off') {
      $this->add_render_attribute('wrapper', 'class', 'popup-gallery');
    }

    if ($this->settings['hover_disable'] == 'yes') {
      $this->add_render_attribute('wrapper', 'class', 'hover-disable');
    }

    if ($this->settings['popup_mode'] == 'yes' && $this->settings['hover'] != 'gallery') {
      $this->add_render_attribute('wrapper', 'class', 'popup-gallery');
    }

    if ($this->settings['type'] !== 'carousel-type2') {
      $this->add_render_attribute('inner-wrapper', 'class', [
        'portfolio-items elementor-block',
      ]);
      $this->add_render_attribute('inner-wrapper', 'class', ['portfolio-type-' . $this->settings['type'], 'load-wrap']);
    } else {
      $this->add_render_attribute('inner-wrapper', 'class', [
        'portfolio-items elementor-block portfolio-type-carousel2',
      ]);
    }

    if ($this->settings['type'] == 'scattered') {
      $this->add_render_attribute('inner-wrapper', 'class', 'portfolio_hover_type_1');
    } elseif ($this->settings['hover']) {
      $this->add_render_attribute('inner-wrapper', 'class', 'portfolio_hover_' . $this->settings['hover']);
    }

    $this->add_render_attribute('inner-wrapper', 'id', 'portfolio-' . uniqid());
    

    if ($this->settings['gap'] != 'yes') {
      $this->add_render_attribute('inner-wrapper', 'class', 'gap-off');
    }

    if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered' || $this->settings['type'] == 'carousel') {
      $this->add_render_attribute('inner-wrapper', 'class', 'row');
    }
  }

  protected function get_navigation_html() {
    return false; 

    $max_num_pages = $this->get_items_array()->max_num_pages;
    $loadmore_array = array_slice($this->get_loadmore_array(), $this->settings['count_items']);
    $loadmore_array = json_encode($loadmore_array);

    if ($this->settings['navigation'] == "pagination") {
      if (function_exists('yprm_wp_corenavi')) {
        echo yprm_wp_corenavi($max_num_pages);
      } else {
        wp_link_pages();
      }
    }if ($loadmore_array && ($this->settings['navigation'] == "load_more" || $this->settings['navigation'] == 'load_more_on_scroll') && $max_num_pages > 1) { ?>
			<div class="load-button tac">
				<a href="#" data-array="<?php echo esc_attr($loadmore_array); ?>" data-count="<?php echo $this->settings['count_items'] ?>" data-atts="<?php echo esc_attr(json_encode($this->settings)) ?>" class="button-style2 elementor-loadmore-button <?php echo esc_attr($this->settings['navigation']); ?>">
					<span><?php echo yprm_get_theme_setting('tr_load_more'); ?></span>
				</a>
			</div>
		<?php }
  }

  protected function set_project_object($atts, $index) {
    // Check if 'image' and 'id' keys exist before accessing
    $image_id = isset($atts['image']['id']) ? $atts['image']['id'] : null;

    // Safely retrieve image data or set default values
    $image_original = $image_id ? yprm_get_image($image_id, 'array', 'full') : false;

    $this->project = (object) [
        'index' => $index,
        'title' => isset($atts['title']) ? $atts['title'] : '',
        'desc' => isset($atts['desc']) ? $atts['desc'] : '',
        'get_image_original' => $image_original ? $image_original : ['placeholder.jpg', 0, 0], // Fallback
        'get_image_html' => $image_id ? yprm_get_image($image_id, 'img', $this->settings['thumb_size_size']) : '',
        'get_image_bg' => $image_id ? yprm_get_image($image_id, 'bg', $this->settings['thumb_size_size']) : '',
        'get_image_array' => $image_id ? yprm_get_image($image_id, 'array', $this->settings['thumb_size_size']) : false,
    ];

    if ($this->settings['popup_mode'] != 'yes') {
        if (isset($atts['link']['url'])) {
            $this->project->get_link_html = '<a class="link" href="' . esc_url($atts['link']['url']) . '" target="' . esc_attr($atts['link']['is_external'] ? '_blank' : '_self') . '"></a>';
        }
    } else {
        $popup_array = [];
        $popup_array['title'] = $this->project->title;
        $popup_array['desc'] = $this->get_short_description(yprm_get_theme_setting('popup_desc_size'));
        if ($image_original) {
            $popup_array['image'] = [
                'url' => $image_original[0],
                'w' => $image_original[1],
                'h' => $image_original[2],
            ];
        } else {
            $popup_array['image'] = [
                'url' => 'placeholder.jpg',
                'w' => 0,
                'h' => 0,
            ]; // Fallback for missing image
        }

        if (isset($atts['link']['url'])) {
            $popup_array['projectLink'] = esc_url($atts['link']['url']);
        }

        $this->project->get_link_html = '<a class="link" href="#" data-popup-json="' . esc_attr(json_encode($popup_array)) . '" data-id="' . esc_attr($index) . '"></a>';
    }
}


  protected function get_short_description($count = 140) {
    if(!$count) $count = 140;

    if(function_exists('mb_strimwidth') && $this->project->desc) {
      return mb_strimwidth($this->project->desc, 0, (int) $count);
    }

    return false;
  }

  protected function set_project_css($index, $post) {
    $block_setting_key = $this->get_repeater_setting_key('project', 'items', $index);
    $cols = yprm_el_cols($this->settings);

    if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered') {
      $this->add_render_attribute($block_setting_key, 'class', $cols);
    }

    if ($this->settings['type'] !== 'carousel-type2') {
      $this->add_render_attribute($block_setting_key, 'class', [
        'portfolio-item',
      ]);
    } else {
      $this->add_render_attribute($block_setting_key, 'class', [
        'swiper-slide',
      ]);
    }

    if ($this->settings['type'] == 'horizontal' || $this->settings['type'] == 'carousel') {
      $this->add_render_attribute($block_setting_key, 'class', 'item');
    }
    $this->add_render_attribute($block_setting_key, 'class', 'portfolio-' . $this->settings['type'] . '-item');

    if ($this->settings['popup_mode'] == 'yes') {
      $this->add_render_attribute($block_setting_key, 'class', 'popup-item');
    }

    return $block_setting_key;
  }

  protected function render_items() {
    $items_array = $this->settings['items'];

    if(count($items_array) < 1) return false;

    foreach ($items_array as $key => $project) {
      $this->set_project_object($project, $key);

      if ($this->settings['type'] == 'scattered') {
        echo $this->render_project_scattered($project, $key);
      } else if ($this->settings['type'] == 'carousel') {
        echo $this->render_project_carousel($project, $key);
      } else if ($this->settings['type'] == 'flow') {
        echo $this->render_project_flow($project, $key);
      } else if ($this->settings['type'] == 'carousel-type2') {
        echo $this->render_project_carousel_type2($project, $key);
      } else if ($this->settings['type'] == 'horizontal') {
        echo $this->render_project_horizontal($project, $key);
      } else {
        echo $this->render_project_grid($project, $key);
      }
    }
  }

  protected function project_title($num) {
    if ($this->settings['show_heading'] !== 'yes') {
      return false;
    }

    if ($this->settings['hover'] == 'type_5'): ?>
			<h5><span><?php echo esc_html($num+1); ?></span><?php echo esc_html($this->project->title); ?></h5>
		<?php else: ?>
			<h5><?php echo esc_html($this->project->title); ?></h5>
		<?php endif;
  }

  protected function project_desc() {
    if ($this->settings['show_desc'] != 'yes') return;

    echo wpautop($this->get_short_description($this->settings['desc_size']));
  }

  protected function render_project_flow($atts, $index) {
    
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<li <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>"><?php echo $this->project->get_image_html ?></div>
			<div class="content">
				<?php echo $this->project_title($index); ?>
				<?php echo $this->project_desc(); ?>
			</div>
			<?php echo $this->project->get_link_html ?>
		</li>
		<?php return ob_get_clean();
  }

  protected function render_project_grid($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<?php if ($this->settings['type'] == 'masonry') {?>
					<div class="a-img"><?php echo $this->project->get_image_html ?></div>
				<?php } else {?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg ?>"></div>
					</div>
				<?php }?>
				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php echo $this->project->get_link_html ?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function render_project_horizontal($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<?php if ($this->settings['save_aspect_ratio'] == 'yes'): ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<img width="<?php echo $this->project->get_image_array[1]; ?>" height="<?php echo $this->project->get_image_array[2]; ?>" src="<?php echo $this->project->get_image_array[0]; ?>" alt="<?php echo $this->project->title; ?>">
          </div>
				<?php else: ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg ?>"></div>
					</div>
				<?php endif;?>

				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php echo $this->project->get_link_html ?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function render_project_scattered($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start()?>
			<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
				<div class="wrap">
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<?php echo $this->project->get_image_html; ?>
					</div>
					<div class="content">
						<?php echo $this->project_title($index); ?>
						<?php echo $this->project_desc(); ?>
					</div>
					<?php echo $this->project->get_link_html ?>
				</div>
			</article>
		<?php return ob_get_clean();
  }

  protected function render_project_carousel($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ?>
		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<div class="a-img">
					<div style="<?php echo $this->project->get_image_bg; ?>">
            <?php echo $this->project->get_link_html ?>
					</div>
				</div>
				<div class="bottom-content">
					<?php echo $this->project_title($index); ?>
				</div>
			</div>
		</article>
  <?php }

  protected function render_project_carousel_type2($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ?>
		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<?php echo $this->project->get_image_html; ?>
				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php echo $this->project->get_link_html ?>
			</div>
		</article>
  <?php }


  public function loadmore() {
    $this->settings = $_POST['atts'];
    $array = $_POST['array'];
    $start_index = $_POST['start_index'];

    if (is_array($array) && count($array) > 0) {
      foreach ($array as $item) {
        if (!isset($item['id']) || empty($item['id'])) {
          continue;
        }

        $project = get_post($item['id']);

        if (!$project) {
          continue;
        }

        $this->set_project_object($project, $start_index++);

        if ($this->settings['type'] == 'scattered') {
          echo $this->render_project_scattered($project, $start_index);
        } else if ($this->settings['type'] == 'carousel') {
          echo $this->render_project_carousel($project, $start_index);
        } else if ($this->settings['type'] == 'flow') {
          echo $this->render_project_flow($project, $start_index);
        } else if ($this->settings['type'] == 'carousel-type2') {
          echo $this->render_project_carousel_type2($project, $start_index);
        } else {
          echo $this->render_project_grid($project, $start_index);
        }
      }
    } else {
      echo array(
        'return' => 'error',
      );
    }
  }

}