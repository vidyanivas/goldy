<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Banner_Liquid_Slider_Widget extends Widget_Base {

  protected $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('banner-liquid-slider-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/banner-liquid-slider.js', array('jquery'), false, true);
  }

  public function get_name() {
    return 'yprm_banner_liquid_slider';
  }

  public function get_title() {
    return esc_html__('Banner Liquid Slider', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-banner-liquid-slider';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_style_depends(): array {
    return [ 'e-swiper' ];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['pt-liquidSlider', 'gsap', 'swiper11', 'pt-youtube-video', 'banner-liquid-slider-handler'];
    }

    $scripts = ['pt-liquidSlider', 'gsap', 'swiper11', 'pt-youtube-video', 'banner-liquid-slider-handler'];
    return $scripts;
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
      'effect_type',
      [
        'label' => esc_html__('Effect Type', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "default" => esc_html__("Default", "pt-addons"),
          "style1" => esc_html__("Style 1", "pt-addons"),
          "style2" => esc_html__("Style 2", "pt-addons"),
        ],
        'default' => 'default',
      ]
    );

    $this->add_control(
      'text_vertical_align',
      [
        'label' => esc_html__('Vertical Align', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'top' => esc_html__('Top', 'pt-addons'),
          'middle' => esc_html__('Middle', 'pt-addons'),
          'bottom' => esc_html__('Bottom', 'pt-addons'),
        ],
        'default' => 'bottom',
      ]
    );

    $this->add_control(
      'text_align',
      [
        'label' => esc_html__('Content Align', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'left' => esc_html__('Left', 'pt-addons'),
          'center' => esc_html__('Center', 'pt-addons'),
          'right' => esc_html__('Right', 'pt-addons'),
        ],
        'default' => 'left',
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
      'image',
      [
        'label' => esc_html__('Image', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'sub_heading',
      [
        'label' => esc_html__('Sub Heading', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'heading',
      [
        'label' => esc_html__('Heading', 'pt-addons'),
        'type' => Controls_Manager::TEXTAREA,
        'description' => wp_kses(__("Wrap the text in { } if you want the text to be another color.<br>Example: Minds your work {level}", "pt-addons"), 'post'),
      ]
    );

    $repeater->add_control(
      'text',
      [
        'label' => esc_html__('Text', 'pt-addons'),
        'type' => Controls_Manager::TEXTAREA,
      ]
    );

    $repeater->add_group_control(
      Group_Control_Link::get_type(),
      [
        'name' => 'link',
        'label' => esc_html__( 'Link', 'pt-addons' ),
      ]
    );

    $repeater->add_control(
      'video_url',
      [
        'label' => esc_html__('Video In Popup', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true
      ]
    );

    $this->add_control(
      'slides',
      [
        'label' => esc_html__('Slides ', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '{{{ heading }}}',
      ]
    );

    $this->end_controls_section();

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

    /* $this->add_control(
      'speed',
      [
        'label' => __('Transition speed', 'novo'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 300,
        "min" => 100,
        "max" => 10000,
        "step" => 100,
      ]
    ); */

    $this->add_control(
      'autoplay',
      [
        'label' => __('Autoplay Slides', 'novo'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes',
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

    // Style Tab
    $this->start_controls_section(
      'general_style',
      [
        'label' => __('Navigation', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'carousel_nav' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'arrow_color',
      [
        'label' => __('Arrow Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
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
      ]
    );

    $this->add_control(
      'heading_size',
      [
        'label' => __('Heading Size', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          "h1" => esc_html__("H1", "novo"),
          "h2" => esc_html__("H2", "novo"),
          "h3" => esc_html__("H3", "novo"),
          "h4" => esc_html__("H4", "novo"),
          "h5" => esc_html__("H5", "novo"),
          "h6" => esc_html__("H6", "novo"),
        ],
        'default' => 'h3',
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __('Text Color', 'novo'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .content-slider-container .h' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'selector' => '{{WRAPPER}} .content-slider-container .h',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_desc_style',
      [
        'label' => __('Description', 'novo'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'text' => 'yes',
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
        'label' => __('Button', 'marlon-elementor'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
        'label' => __('Text Color', 'marlon-elementor'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .load-button a.elementor-loadmore-button' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_background_color',
      [
        'label' => __('Background Color', 'marlon-elementor'),
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
        'label' => __('Border Radius', 'marlon-elementor'),
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
        'label' => __('Text Padding', 'marlon-elementor'),
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
    $this->settings = $settings = $this->get_settings_for_display();

    if (!is_array($settings['slides']) || count($settings['slides']) == 0) {
      return false;
    }

    $this->set_images_array();

    $this->add_render_attribute('wrapper', 'class', [
      'liquiq-banner',
    ]);

    $this->add_render_attribute(
      [
        'wrapper' => [
          'data-banner-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("yes" == $this->settings["infinite_loop"]) ? 'on' : 'off',
              "autoplay" => ("yes" == $this->settings["autoplay"]) ? 'on' : 'off',
              "arrows" => ("yes" == $this->settings["carousel_nav"]) ? 'on' : 'off',
              "mousewheel" => ("yes" == $this->settings["mousewheel"]) ? 'on' : 'off',
              "autoplaySpeed" => $this->settings['autoplay_speed'] ? $this->settings['autoplay_speed'] : 5000,
              //"speed" => $this->settings['speed'] ? $this->settings['speed'] : 300,
            ])),
          ],
        ],
      ]
    );

    if ($settings['effect_type'] == 'default') {
      $displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern.jpg';
    } else if ($settings['effect_type'] == 'style1') {
      $displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern2.jpg';
    } else if ($settings['effect_type'] == 'style2') {
      $displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern3.png';
    }

    $this->add_render_attribute('slider_block', [
      'class' => [
        'content-slider-container',
      ],
    ]);

    ob_start();?>

		<div <?php echo $this->get_render_attribute_string('wrapper') ?> data-displacement="<?php echo esc_url($displacement_url) ?>">
			<?php $this->get_navigation_arrows()?>
			<?php $this->get_slider()?>
			<div class="images-slider-container">
				<div class="images-slider-wrapper"></div>
			</div>
			<div <?php echo $this->get_render_attribute_string('slider_block') ?>>
				<?php $this->render_slides();?>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function render_slides() {
    foreach ($this->settings['slides'] as $key => $slide) {
      $this->get_slide_content($slide, $key);
    }
  }

  protected function get_slide_content($atts, $index) {
    $item_block_setting_key = $this->get_repeater_setting_key('item_block', 'slides', $index);

    $this->add_render_attribute($item_block_setting_key, 'class', [
      'content-slide',
      'banner-item container',
      'elementor-repeater-item-' . $atts['_id'],
      'horizontal-type-'.$this->settings['text_align'],
      'vertical-type-'.$this->settings['text_vertical_align'],
    ]);

    if ( $index == 0 ) {
      $this->add_render_attribute($item_block_setting_key, 'class', [
        'slide-active'
      ] );
    }

    ob_start(); ?>
		<div <?php echo $this->get_render_attribute_string($item_block_setting_key) ?>>
			<div class="content-wrap">
				<?php $this->get_video_block($atts)?>
				<div class="content">
					<div class="h-block">
						<?php if ($atts['sub_heading']): ?>
							<?php $this->get_sub_heading_block($atts)?>
						<?php endif; ?>
            <?php if ($atts['heading']): ?>
							<?php $this->get_heading_block($atts)?>
						<?php endif;?>
					</div>
					<?php $this->get_text($atts)?>
					<?php $this->get_links($atts)?>
				</div>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function get_video_block($item) {
    if (!$item['video_url']) return;

    $popup_array = [];
    $popup_array['video'] = [
      'html' => \VideoUrlParser::get_player($item['video_url']),
      'w' => 1920,
      'h' => 1080
    ];

    wp_enqueue_script( 'background-video' );
    wp_enqueue_script( 'video' );

  ?>
    <div class="play-button-block popup-gallery images">
      <a href="#" data-type="video" class="play-button" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="0">
        <i class="music-and-multimedia-play-button"></i>
      </a>
    </div>
  <?php }

  protected function get_sub_heading_block($atts) {
    ?>
			<div class="sub-h"><?php echo wp_kses($atts['sub_heading'], 'post') ?></div>
		<?php
}

  protected function get_heading_block($atts) {
    ?>
			<<?php echo $this->settings['heading_size'] ?> class="h"><?php echo yprm_heading_filter(nl2br($atts['heading'])); ?></<?php echo $this->settings['heading_size'] ?>>
		<?php
}

  protected function get_text($atts) {
    if (!$atts['text']) {
      return false;
    }

    ob_start()?>
			<div class="clear"></div>
			<div class="text"><?php echo wp_kses_post(nl2br($atts['text'])) ?></div>
		<?php echo ob_get_clean();
  }

  protected function get_links($atts) {
    if(!isset($atts['link_url']['url']) || !$atts['link_url']['url']) return false;

    $link = yprm_el_link($atts);

    if(!$link) return false;

    ob_start() ?>
			<div class="button-block">
        <a href="<?php echo esc_url($link['url']) ?>" class="button-style2" target="<?php echo esc_attr($link['target']) ?>"><?php echo strip_tags($link['title']) ?></a>
      </div>
		<?php echo ob_get_clean();
  }

  protected function get_navigation_arrows() {
    if ($this->settings['carousel_nav'] != 'yes') {
      return;
    }

    ob_start(); ?>

		<div class="nav-arrows">
			<div class="prev">
				<svg width="28" height="28" viewBox="0 0 28 28" fill="#C48F56" xmlns="http://www.w3.org/2000/svg">
					<path d="M1.0941 15.0937L24.2574 15.0937L20.4203 18.9122C19.9922 19.3383 19.9905 20.0308 20.4167 20.459C20.8428 20.8872 21.5354 20.8888 21.9635 20.4627L27.6786 14.7752C27.6789 14.7749 27.6792 14.7745 27.6795 14.7742C28.1066 14.3481 28.108 13.6533 27.6797 13.2258C27.6793 13.2254 27.679 13.2251 27.6787 13.2247L21.9636 7.53724C21.5355 7.11122 20.843 7.1127 20.4168 7.54095C19.9907 7.9691 19.9923 8.66161 20.4204 9.08773L24.2574 12.9062L1.0941 12.9062C0.490021 12.9062 0.000349045 13.3959 0.000349045 14C0.000349045 14.6041 0.490021 15.0937 1.0941 15.0937Z"/>
				</svg>
			</div>
			<div class="next">
				<svg width="28" height="28" viewBox="0 0 28 28" fill="#C48F56" xmlns="http://www.w3.org/2000/svg">
					<path d="M1.0941 15.0937L24.2574 15.0937L20.4203 18.9122C19.9922 19.3383 19.9905 20.0308 20.4167 20.459C20.8428 20.8872 21.5354 20.8888 21.9635 20.4627L27.6786 14.7752C27.6789 14.7749 27.6792 14.7745 27.6795 14.7742C28.1066 14.3481 28.108 13.6533 27.6797 13.2258C27.6793 13.2254 27.679 13.2251 27.6787 13.2247L21.9636 7.53724C21.5355 7.11122 20.843 7.1127 20.4168 7.54095C19.9907 7.9691 19.9923 8.66161 20.4204 9.08773L24.2574 12.9062L1.0941 12.9062C0.490021 12.9062 0.000349045 13.3959 0.000349045 14C0.000349045 14.6041 0.490021 15.0937 1.0941 15.0937Z"/>
				</svg>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function get_slider() {

    ob_start(); ?>
		<div class="slider swiper">
			<div class="swiper-wrapper">
				<?php foreach ($this->images_tag as $image) {?>
					<div class="swiper-slide">
						<?php echo $image; ?>
					</div>
				<?php }?>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function set_images_array() {
    $this->images_array = [];
    $this->images_bg_array = [];
    $this->images_tag = [];

    foreach ($this->settings['slides'] as $slide) {
      if (!isset($slide['image']['id']) || !$slide['image']['id']) {
        continue;
      }

      $this->images_array[] = yprm_get_image($slide['image']['id'])[0];
      $this->images_bg_array[] = yprm_get_image($slide['image']['id'], 'bg');
      $this->images_tag[] = yprm_get_image($slide['image']['id'], 'img');
    }
  }

}