<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Image_Comparison_Slider_Widget extends Widget_Base {

  protected $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('image-comparison-slider', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/image-comparison-slider.js', array('jquery'), false, true);
  }

  public function get_name() {
    return 'yprm_image_comparison_slider';
  }

  public function get_title() {
    return esc_html__('Before/After Slider', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-before-after';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['image-comparison-slider'];
    }

    $scripts = ['image-comparison-slider'];
    return $scripts;
  }

  protected function register_controls() {

    $this->start_controls_section(
      'image_comparison_section_start', [
        'label' => esc_html__('Images', 'pt-addons'),
      ]
    );

    $this->start_controls_tabs('image_comparison_tab_images');

    $this->start_controls_tab(
      'image_comparison_tab_before_image',
      [
        'label' => esc_html__('Before', 'pt-addons'),
      ]
    );

    $this->add_control(
      'image_comparison_before_image',
      array(
        'label' => esc_html__('Before image', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
        'show_label' => false,
      )
    );

    $this->end_controls_tab();

    $this->start_controls_tab(
      'image_comparison_tab_after_image',
      [
        'label' => esc_html__('After', 'pt-addons'),
      ]
    );

    $this->add_control(
      'image_comparison_after_image',
      array(
        'label' => esc_html__('After Image', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
        'show_label' => false,
      )
    );

    $this->end_controls_tab();
    $this->end_controls_tabs();

    $this->add_group_control(
      Group_Control_Image_Size::get_type(),
      [
        'name' => 'thumbnail',
        'default' => 'full',
        'separator' => 'before',
        'exclude' => array('custom'),
      ]
    );

    $this->end_controls_section();

    /**
     * General Style Section
     */
    $this->start_controls_section(
      'image_comparison_general_style',
      array(
        'label' => esc_html__('Layout Style', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
        'show_label' => false,
      )
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      array(
        'name' => 'image_comparison_container_border',
        'label' => esc_html__('Border', 'pt-addons'),
        'placeholder' => '1px',
        'default' => '1px',
        'selector' => '{{WRAPPER}} .image-comparison-slider',
      )
    );

    $this->add_control(
      'image_comparison_border_radius',
      [
        'label' => esc_html__('Border Radius', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
          '{{WRAPPER}} .image-comparison-slider .line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => 'image_comparison_box_shadow',
        'selector' => '{{WRAPPER}} .image-comparison-slider .line',
      ]
    );

    $this->add_responsive_control(
      'image_comparison_container_padding',
      array(
        'label' => esc_html__('Padding', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => array('px', '%'),
        'selectors' => array(
          '{{WRAPPER}} .image-comparison-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );

    $this->add_responsive_control(
      'image_comparison_container_margin',
      array(
        'label' => esc_html__('Margin', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => array('px', '%'),
        'selectors' => array(
          '{{WRAPPER}} .image-comparison-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );

    $this->end_controls_section();

    /**
     * Style Tab: Handle
     */
    $this->start_controls_section(
      'image_comparison_section_handle_style',
      [
        'label' => esc_html__('Handle', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'image_comparison_handle_bg_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .image-comparison-slider .line:after' => 'background-color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'image_comparison_arrow_box_width',
      [
        'label' => esc_html__('Box Width', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 20,
            'max' => 100,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .image-comparison-slider .line:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'image_comparison_handle_bar_size',
      [
        'label' => esc_html__('Size', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 50,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .image-comparison-slider .line:after' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'image_comparison_handle_icon_color',
      [
        'label' => esc_html__('Icon Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .image-comparison-slider .line:after' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name' => 'image_comparison_handle_border',
        'label' => esc_html__('Border', 'pt-addons'),
        'placeholder' => '1px',
        'default' => '1px',
        'selector' => '{{WRAPPER}} .image-comparison-slider .line:after',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'image_comparison_handle_border_radius',
      [
        'label' => esc_html__('Border Radius', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
          '{{WRAPPER}} .image-comparison-slider .line:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();
    $id = 'image-comparison-slider-' . $this->get_id();

    $this->add_render_attribute(
      [
        'image_comparison' => [
          'id' => esc_attr($id),
          'class' => implode(' ', [
            'image-comparison-slider',
            'image-comparison-slider-' . esc_attr($this->get_id()),
          ]),
        ],
      ]
    );

    ob_start();?>

			<div <?php echo $this->get_render_attribute_string('image_comparison'); ?>>
				<div class="new">
		            <?php if ($settings['image_comparison_before_image']['url'] || $settings['image_comparison_before_image']['id']):
      $this->add_render_attribute('compare_before_image', 'src', $settings['image_comparison_before_image']['url']);
      $this->add_render_attribute('compare_before_image', 'alt', Control_Media::get_image_alt($settings['image_comparison_before_image']));
      $this->add_render_attribute('compare_before_image', 'title', Control_Media::get_image_title($settings['image_comparison_before_image']));
      echo Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image_comparison_before_image');
    endif;?>
		        </div>

		        <div class="resize">
		        	<div class="old">
	            		<?php if ($settings['image_comparison_after_image']['url'] || $settings['image_comparison_after_image']['id']):
      $this->add_render_attribute('compare_after_image', 'src', $settings['image_comparison_after_image']['url']);
      $this->add_render_attribute('compare_after_image', 'alt', Control_Media::get_image_alt($settings['image_comparison_after_image']));
      $this->add_render_attribute('compare_after_image', 'title', Control_Media::get_image_title($settings['image_comparison_after_image']));
      echo Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image_comparison_after_image');
    endif;?>
			        </div>
			    </div>

			    <div class="line"></div>
	        </div>
		<?php
echo ob_get_clean();
  }
}