<?php

namespace Elementor;

use YPRM_Get_Project;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Portfolio_Widget extends \Elementor\Widget_Base {
  protected $settings;
  protected $paged;
  protected $project;
  protected $displacement_url;

  protected $popup_atts;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    $this->displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern4.jpg';

    if (is_front_page()) {
      $this->paged = (get_query_var('page')) ? get_query_var('page') : 1;
    } else {
      $this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }

    wp_register_script('portfolio-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/portfolio.js', array('jquery', 'isotope', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_portfolio';
  }

  public function get_title() {
    return esc_html__('Portfolio', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-portfolio';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['portfolio-handler', 'swiper11', 'isotope', 'pt-scattered-portfolio', 'flipster', 'pt-load-posts', 'owl-carousel'];
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

    $scripts[] = 'portfolio-handler';
    return $scripts;
  }

  public function get_style_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
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
        'type' => \Elementor\Controls_Manager::SELECT,
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
        'type' => \Elementor\Controls_Manager::SELECT,
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'return_value' => 'yes',
        'default' => '',
        'condition' => [
          'hover' => 'gallery',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Image_Size::get_type(),
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::SLIDER,
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
          '{{WRAPPER}} .portfolio-item' => 'width: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .portfolio-type-horizontal .portfolio-item .a-img div' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_control(
      'gap',
      [
        'label' => __('GAP', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
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

    $this->add_control(
      'navigation',
      [
        'label' => __('Navigation', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
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
    );

    $this->add_control(
      'count_items',
      [
        'label' => __('Posts Per Page', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 9,
      ]
    );

    $this->add_control(
      'project_link',
      [
        'label' => __('Project Link', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => '',
        'condition' => [
          'popup_mode' => 'yes',
        ],
      ]
    );

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
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'project_link_target',
      [
        'label' => esc_html__( 'Open Project in New Tab', 'pt-addons' ),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__( 'Yes', 'pt-addons' ),
        'label_off' => esc_html__( 'No', 'pt-addons' ),
        'return_value' => 'yes',
        'default' => 'no',
        'condition' => [
          'popup_mode!' => 'yes',
        ]
      ]
    );

    $this->add_control(
      'popup_mode_title',
      [
        'label' => __('Popup Mode Title', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => '',
        'condition' => [
          'popup_mode' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'filter_settings_section', [
        'label' => __('Filter Settings', 'novo'),
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
      'filter_buttons',
      [
        'label' => __('Filter buttons', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'filter_buttons_align',
      [
        'label' => __('Filter buttons align', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'tal' => esc_html__('Left', 'novo'),
          'tac' => esc_html__('Center', 'novo'),
          'tar' => esc_html__('Right', 'novo'),
        ],
        'default' => 'tal'
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::NUMBER,
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
        'type' => \Elementor\Controls_Manager::SWITCHER,
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
        'type' => \Elementor\Controls_Manager::NUMBER,
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

    $this->add_control(
      'show_categories',
      [
        'label' => __('Categories', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
        'conditions' => [
          'terms' => [
            [
              'name' => 'type',
              'operator' => 'in',
              'value' => ['carousel', 'carousel-type2'],
            ],
          ],
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'portfolio_sorting_section', [
        'label' => __('Sorting', 'novo'),
      ]
    );

    $this->add_control(
      'orderby',
      [
        'label' => __('Order by', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'post__in' => esc_html__('Default', 'novo'),
          'author' => esc_html__('Author', 'novo'),
          'category' => esc_html__('Category', 'novo'),
          'date' => esc_html__('Date', 'novo'),
          'ID' => esc_html__('ID', 'novo'),
          'title' => esc_html__('Title', 'novo'),
        ],
        'default' => 'ID',
      ]
    );

    $this->add_control(
      'order',
      [
        'label' => __('Order', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'ASC' => esc_html__('Ascending order', 'novo'),
          'DESC' => esc_html__('Descending order', 'novo'),
        ],
        'default' => 'ASC',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'portfolio_source_section', [
        'label' => __('Source', 'novo'),
      ]
    );

    $this->add_control(
      'source',
      [
        'label' => __('Source', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          '' => esc_html__('---', 'novo'),
          'items' => esc_html__('Items', 'novo'),
          'categories' => esc_html__('Categories', 'novo'),
        ],
      ]
    );

    $this->add_control(
      'items',
      [
        'label' => __('Source', 'novo'),
        'type' => \Elementor\Selectize_Control::SELECTIZE,
        'options' => $this->get_all_portfolio_items(),
        'label_block' => true,
        'condition' => [
          'source' => 'items',
        ],
      ]
    );

    $this->add_control(
      'categories',
      [
        'label' => __('Category', 'novo'),
        'type' => \Elementor\Selectize_Control::SELECTIZE,
        'options' => $this->get_all_portfolio_category(),
        'label_block' => true,
        'condition' => [
          'source' => 'categories',
        ],
      ]
    );

    $this->end_controls_section();

    // Style Tab
    $this->start_controls_section(
      'general_style',
      [
        'label' => __('Navigation', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'type' => \Elementor\Controls_Manager::HEADING,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'show_heading' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
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
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'selector' => '{{WRAPPER}} .portfolio-type-carousel .portfolio-item .bottom-content h5, {{WRAPPER}} .portfolio-type-carousel .portfolio-item .bottom-content h5, .portfolio-item .content h5',
      ]
    );

    $this->add_control(
      'portofio_index_heading',
      [
        'label' => __('Number Index', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
        'type' => \Elementor\Controls_Manager::COLOR,
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
      \Elementor\Group_Control_Typography::get_type(),
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
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'show_desc' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'desc_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .portfolio-item .content p' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'desc_typography',
        'selector' => '{{WRAPPER}} .portfolio-item .content p',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'button_style_section',
      [
        'label' => __('Button', 'pt-addons'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'navigation' => 'load_more',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'button_typography',
        'selector' => '{{WRAPPER}} .load-button a.elementor-loadmore-button',
      ]
    );

    $this->add_control(
      'button_text_color',
      [
        'label' => __('Text Color', 'pt-addons'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_background_color',
      [
        'label' => __('Background Color', 'pt-addons'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'background: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'button_border',
        'selector' => '{{WRAPPER}} .load-button a.elementor-loadmore-button',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'button_border_radius',
      [
        'label' => __('Border Radius', 'pt-addons'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'button_text_padding',
      [
        'label' => __('Text Padding', 'pt-addons'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
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

    $projects_array = $this->get_items_array()->posts;

    if (empty($projects_array)) {
      return false;
    }

    $this->set_css_classes();

    ob_start(); ?>

		<div <?php echo $this->get_render_attribute_string('wrapper') ?>>
			<?php if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered' || $this->settings['type'] == 'carousel'): ?>
				<?php $this->get_filter_buttons_html();?>
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
				<?php $this->get_filter_buttons_html();?>
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

    if ($this->settings['popup_mode'] == 'yes' && $this->settings['hover'] != 'gallery') {
      $this->add_render_attribute('wrapper', 'class', 'popup-gallery');
    }

    if ($this->settings['hover'] == 'gallery' && $this->settings['popup_gallery'] == 'off') {
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
    
    if ($this->settings['hover_disable'] == 'yes') {
      $this->add_render_attribute('inner-wrapper', 'class', 'hover-disable');
    }

    if ($this->settings['gap'] != 'yes') {
      $this->add_render_attribute('inner-wrapper', 'class', 'gap-off');
    }

    if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered' || $this->settings['type'] == 'carousel') {
      $this->add_render_attribute('inner-wrapper', 'class', 'row');
    }
  }

  protected function get_source_categories() {
    if ($this->settings['source'] == 'categories' && $this->settings['categories']) {
      return array(
        array(
          'taxonomy' => 'pt-portfolio-category',
          'field' => 'id',
          'terms' => $this->settings['categories'],
        ),
      );
    }

    return array();
  }

  protected function get_source_items() {
    if ($this->settings['source'] == 'items' && $this->settings['items']) {
      return $this->settings['items'];
    }

    return false;
  }

  protected function get_items_array() {
    $args = array(
      'post__in' => $this->get_source_items(),
      'posts_per_page' => $this->settings['count_items'],
      'paged' => $this->paged,
      'orderby' => $this->settings['orderby'],
      'order' => $this->settings['order'],
      'post_type' => 'pt-portfolio',
      'post_status' => 'publish',
      'tax_query' => $this->get_source_categories(),
    );

    return new \WP_Query($args);
  }

  protected function get_loadmore_array() {
    $args = array(
      'post__in' => $this->get_source_items(),
      'posts_per_page' => -1,
      'paged' => $this->paged,
      'orderby' => $this->settings['orderby'],
      'order' => $this->settings['order'],
      'post_type' => 'pt-portfolio',
      'post_status' => 'publish',
      'tax_query' => $this->get_source_categories(),
    );

    $array = new \WP_Query($args);
    $loadmore_array = array();

    if (is_object($array) && count($array->posts) > 0) {
      foreach ($array->posts as $key => $item) {
        $loadmore_array[$key] = array(
          'id' => $item->ID,
        );

        foreach (wp_get_post_terms($item->ID, 'pt-portfolio-category') as $s_item) {
          $loadmore_array[$key]['cat'][] = $s_item->term_id;
        }
      }
    }

    return $loadmore_array;
  }

  protected function get_categories_array() {
    $categories_array = array();

    if(
      ($this->settings['navigation'] != 'load_more' && $this->settings['navigation'] != 'load_more_on_scroll') ||
      ($this->settings['source'] == 'items' && $this->settings['items'])
    ) {
      $array = $this->get_items_array()->posts;

      if(count($array) > 0) {
        foreach($array as $item) {
          $categories = wp_get_post_terms($item->ID, 'pt-portfolio-category');

          if(count($categories) > 0) {
            foreach($categories as $category) {
              if(isset($categories_array[$category->term_id])) continue;

              $categories_array[$category->term_id] = array(
                'id' => $category->term_id,
                'slug' => $category->slug,
                'title' => $category->name
              );
            }
          }
        }
      }
    } elseif(
      $this->settings['source'] == 'categories' && 
      $this->settings['categories']
    ) {
      $categories = $this->settings['categories'];

      if(count($categories) > 0) {
        foreach($categories as $category_id) {
          $category = get_term($category_id, 'pt-portfolio-category');
          
          if(isset($categories_array[$category->term_id])) continue;

          $categories_array[$category->term_id] = array(
            'id' => $category->term_id,
            'slug' => $category->slug,
            'title' => $category->name
          );
        }
      }
    } else {
      $categories = get_terms('pt-portfolio-category', array(
        'hide_empty' => true,
      ));

      if(count($categories) > 0) {
        foreach($categories as $category) {
          $categories_array[$category->term_id] = array(
            'id' => $category->term_id,
            'slug' => $category->slug,
            'title' => $category->name
          );
        }
      }
    }

    return $categories_array;
  }

  protected function get_filter_buttons_html() {

    if (in_array($this->settings['type'], ['flow', 'horizontal', 'carousel', 'shift', 'carousel-type2', 'scattered'])) {
      return;
    }

    if ($this->settings['filter_buttons'] != 'yes') {
      return false;
    }

    $this->add_render_attribute('filter_buttons_block', 'class', [
      'filter-buttons filter-button-group',
      $this->settings['filter_buttons_align'],
    ]);

    $categories_array = $this->get_categories_array();

    if (count($categories_array) < 2) {
      return false;
    }

    ob_start();?>
		<div <?php echo $this->get_render_attribute_string('filter_buttons_block'); ?>>
			<div class="wrap">
				<button class="button active" data-filter="*">
					<span><?php echo yprm_get_theme_setting('tr_all') ?></span>
				</button>
				<?php foreach ($categories_array as $index => $category): ?>
					<button class="button" data-filter=".category-<?php echo esc_attr($category['id']) ?>">
						<span><?php echo strip_tags($category['title']) ?></span>
					</button>
				<?php endforeach;?>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function get_navigation_html() {
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
				<a href="#" data-array="<?php echo esc_attr($loadmore_array); ?>" data-action="loadmore_elementor_portfolio" data-count="<?php echo $this->settings['count_items'] ?>" data-atts="<?php echo esc_attr(json_encode($this->settings)) ?>" class="button-style2 loadmore-button elementor-loadmore-button <?php echo esc_attr($this->settings['navigation']); ?>">
					<span><?php echo yprm_get_theme_setting('tr_load_more'); ?></span>
				</a>
			</div>
		<?php }
  }

  protected function set_project_object($atts, $index) {

    $thumb_size = $this->settings['thumb_size_size'];

    $this->project = new \YPRM_Get_Project([
      'id' => $atts->ID,
      'index' => $index,
      'project_link_target' => $this->settings['project_link_target'] == 'yes' ? '_blank' : '_self',
      'popup_mode' => $this->settings['popup_mode'],
      'thumb_size' => $thumb_size,
    ]);
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

    $this->add_render_attribute($block_setting_key, 'class', $this->project->get_categories_css());

    if ($this->settings['popup_mode'] == 'yes') {
      $this->add_render_attribute($block_setting_key, 'class', 'popup-item');

      if ($this->project->has_video()) {
        $this->add_render_attribute($block_setting_key, 'class', 'with-video');
      }

      if (function_exists('get_field') && get_field('project_image_position', $this->project->get_id())) {
        $this->add_render_attribute($block_setting_key, 'class', ' image-' . get_field('project_image_position', $this->project->get_id()));
      }
    }

    return $block_setting_key;
  }

  protected function render_items() {
    $projects_array = $this->get_items_array()->posts;

    foreach ($projects_array as $key => $project) {
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
			<h5><span><?php echo esc_html($num+1); ?></span><?php echo esc_html($this->project->get_title()); ?></h5>
		<?php else: ?>
			<h5><?php echo esc_html($this->project->get_title()); ?></h5>
		<?php endif;
  }

  protected function project_desc() {
    if ($this->settings['show_desc'] !== 'yes') {
      return false;
    }

    echo wpautop($this->project->get_short_description($this->settings['desc_size']));
  }

  protected function render_project_flow($atts, $index) {
    
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<li <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>"><?php echo $this->project->get_image_html() ?></div>
			<div class="content">
				<?php echo $this->project_title($index); ?>
				<?php echo $this->project_desc(); ?>
			</div>
			<?php $this->project->get_link_html()?>
		</li>
		<?php return ob_get_clean();
  }

  protected function render_project_grid($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
        <?php if ($this->settings['type'] == 'masonry') {?>
					<div class="a-img">
            <?php echo $this->project->get_image_html() ?>
            <?php echo $this->project_gallery() ?>
          </div>
				<?php } else { ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg() ?>"></div>
            <?php echo $this->project_gallery() ?>
					</div>
				<?php } if ($this->settings['popup_mode'] == 'yes' && $this->settings['project_link'] == 'yes' && !post_password_required($this->project->get_id())) { ?>
          <a href="<?php echo $this->project->get_permalink() ?>" class="permalink"><i class="basic-ui-icon-link"></i></a>
        <?php } ?>
				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php if(!$this->project_gallery()) { ?>
          <?php $this->project->get_link_html() ?>
        <?php } ?>
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
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<img width="<?php echo $this->project->get_image_array()[1]; ?>" height="<?php echo $this->project->get_image_array()[2]; ?>" src="<?php echo $this->project->get_image_array()[0]; ?>" alt="<?php echo $this->project->get_title(); ?>">
          </div>
				<?php else: ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg() ?>"></div>
					</div>
				<?php endif;?>

				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php $this->project->get_link_html()?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function render_project_scattered($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);
    $gallery_array = [];

    if (is_array($this->project->get_gallery_array()) && count($this->project->get_gallery_array()) > 0) {
      foreach ($this->project->get_gallery_array() as $item) {
        $thumb_size = $this->settings['thumb_size_size'];
        if ($image = yprm_get_image($item, '', $thumb_size)) {
          $gallery_array[] = $image[0];
        }
      }
    }

    ob_start()?>
			<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
				<div class="wrap">
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<?php echo $this->project->get_image_html(); ?>
					</div>
					<div class="content">
						<?php echo $this->project_title($index); ?>
						<?php echo $this->project_desc(); ?>
					</div>
					<?php $this->project->get_link_html() ?>
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
					<div style="<?php echo $this->project->get_image_bg(); ?>">
            <a <?php echo $this->project->get_link_atts() ?>>
              <?php if($this->project->has_video()) { ?>
                <i class="music-and-multimedia-play-button"></i>
              <?php } ?>
            </a>
					</div>
				</div>
				<div class="bottom-content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_cats(); ?>
				</div>
			</div>
		</article>
  <?php }

  protected function render_project_carousel_type2($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ?>
		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<?php echo $this->project->get_image_html(); ?>
        <?php if ($this->settings['popup_mode'] == 'yes' && $this->settings['project_link'] == 'yes' && !post_password_required($this->project->get_id())) { ?>
          <a href="<?php echo $this->project->get_permalink() ?>" class="permalink"><i class="basic-ui-icon-link"></i></a>
        <?php } ?>
				<div class="content">
					<?php echo $this->project_cats(); ?>
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php $this->project->get_link_html()?>
			</div>
		</article>
  <?php }

  protected function project_cats() {
    if ($this->settings['show_categories'] !== 'yes') {
      return false;
    }
    $terms = wp_get_post_terms($this->project->get_id(), 'pt-portfolio-category');
    if (!$terms) {
      return false;
    }

    $array = [];

    foreach ($terms as $term) {
      if (!$term || is_wp_error($term)) {
        continue;
      }

      $array[] = $term->name;
    }

    if (empty($array)) {
      return false;
    }

    return '<div class="cat">' . yprm_implode($array, '', ', ') . '</div>';
  }

  protected function project_gallery() {
    if($this->settings['hover'] != 'gallery') return false;

    $gallery_array = [];

    if (is_array($this->project->get_gallery_array()) && count($this->project->get_gallery_array()) > 0) {
      foreach ($this->project->get_gallery_array() as $item) {
        $thumb_size = $this->settings['thumb_size_size'];
        if ($image = yprm_get_image($item, '', $thumb_size)) {
          $gallery_array[] = $image[0];
        }
      }
    }

    ob_start(); ?>

    <?php if(is_array($this->project->get_gallery_array()) && count($this->project->get_gallery_array()) > 0) { ?>
      <ul class="portfolio-item-gallery popup-gallery">
        <?php foreach ($this->project->get_gallery_array() as $key => $thumb) { 
          $full_img_array = yprm_get_image($thumb, 'array', 'full');

          $popup_array = [];
          
          $popup_array['image'] = [
            'url' => $full_img_array[0],
            'w' => $full_img_array[1],
            'h' => $full_img_array[2]
          ];
          $popup_array['post_id'] = $this->project->get_id();

          $popup_array['projectLink'] = get_permalink($this->project->get_id());
          
          if ($this->settings['type'] == 'grid') { ?>
            <li class="popup-item" style="<?php echo esc_attr(yprm_get_image($thumb, 'bg', 'large')) ?>"><a href="#" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="<?php echo esc_attr($key) ?>"></a></li>
          <?php } elseif ($this->settings['type'] == 'masonry') { ?>
            <li class="popup-item"><?php echo yprm_get_image($thumb, 'img', 'large') ?><a href="#" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="<?php echo esc_attr($key) ?>"></a></li>
          <?php } ?>
        <?php } ?>
      </ul>
    <?php } else { ?>
      <a href="#" data-popup-json="<?php echo esc_attr($this->project->get_popup_link_atts()) ?>" data-id="0"></a>
    <?php }

    return ob_get_clean();
  }

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

    wp_die();
  }

  public function get_all_portfolio_category() {
    $taxonomy = 'pt-portfolio-category';
    $args = array(
      'hide_empty' => true,
    );

    $terms = get_terms($taxonomy, $args);
    $result = array();

    if (empty($terms) || is_wp_error($terms)) {
      return $result;
    }

    foreach ($terms as $term) {
      if (!$term) {continue;}

      if (get_category_parents($term->term_id)) {
        $name = get_category_parents($term->term_id);
      } else {
        $name = $term->name;
      }

      $name = trim($name, '/');

      $result[$term->term_id] = 'ID [' . $term->term_id . '] ' . $name;
    }

    return $result;
  }

  protected function get_all_portfolio_items($param = 'All') {
    $result = array();

    $args = array(
      'post_type' => 'pt-portfolio',
      'post_status' => 'publish',
      'posts_per_page' => -1,
    );

    $porfolio_array = new \WP_Query($args);

    if (empty($porfolio_array->posts)) {
      return $result;
    }

    foreach ($porfolio_array->posts as $post) {
      if (!$post) {continue;}

      $result[$post->ID] = 'ID [' . $post->ID . '] ' . $post->post_title;
    }

    return $result;
  }

}