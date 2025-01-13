<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Brands_Widget extends Widget_Base {

  protected $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('brands-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/brands.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_brands';
  }

  public function get_title() {
    return esc_html__('Logos', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-logos';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_style_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['swiper'];
    }

    return ['swiper'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['swiper11', 'brands-handler'];
    }

    return ['swiper11', 'brands-handler'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Content', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_group_control(
      Group_Control_Cols::get_type(),
      [
        'name' => 'cols',
        'label' => esc_html__('Cols', 'pt-addons'),
        'fields_options' => [
          'xs' => [
            'max' => 5,
          ],
          'sm' => [
            'max' => 5,
          ],
          'md' => [
            'max' => 5,
          ],
          'lg' => [
            'max' => 5,
          ],
          'xl' => [
            'max' => 5,
          ],
        ],
      ]
    );

    $this->add_control(
      'carousel',
      [
        'label' => esc_html__('Carousel', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pt-addons'),
        'label_off' => esc_html__('No', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'no',
        'frontend_available' => true,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'slider_settings_section', [
        'label' => __('Slider Settings', 'novo'),
        'condition' => [
          'carousel' => 'yes',
        ],
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
      'brands_section',
      [
        'label' => esc_html__('Brands', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'background_image',
      [
        'label' => esc_html__('Background Image', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'background_image_hover',
      [
        'label' => esc_html__('Background Image On Hover', 'pt-addons'),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'link',
      [
        'label' => esc_html__('Link', 'pt-addons'),
        'type' => Controls_Manager::URL,
        'dynamic' => [
          'active' => true,
          ],
      ]
    );

    $this->add_control(
      'items',
      [
        'label' => esc_html__('Brands', 'pt-addons'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'title_field' => '<img src="{{{ background_image.url }}}" style="max-width: 75px;">',
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();

    if (!is_array($settings['items']) || count($settings['items']) == 0) {
      return false;
    }

    $this->add_render_attribute('block', 'class', [
      'brand-block',
      $settings['carousel'] == 'yes' ? '' : 'carousel-off',
    ]);

    $this->add_render_attribute(
      [
        'block' => [
          'data-portfolio-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("yes" == $this->settings["infinite_loop"]) ? 'yes' : false,
              "autoplay" => ("yes" == $this->settings["autoplay"]) ? 'yes' : false,
              "carousel" => ("yes" == $this->settings["carousel"]) ? 'yes' : false,
              "autoplay_speed" => $this->settings['autoplay_speed'],
              'cols_xs' => $this->settings['cols_xs'],
              'cols_sm' => $this->settings['cols_sm'],
              'cols_md' => $this->settings['cols_md'],
              'cols_lg' => $this->settings['cols_lg'],
              'cols_xl' => $this->settings['cols_xl'],
            ])),
          ],
        ],
      ]
    );

    ob_start();?>
		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<?php if ($settings['carousel'] == 'yes') {?>
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php echo $this->get_brands($settings['items']); ?>
					</div>
				</div>
			<?php } else {?>
				<div class="row">
					<?php echo $this->get_brands($settings['items']); ?>
				</div>
			<?php }?>
		</div>

		<?php echo ob_get_clean();
  }

  public function get_brands($items) {
    foreach ($items as $index => $item) {
      echo $this->get_brand_item($item, $index);
    }
  }

  public function get_brand_item($atts, $index = 0) {
    $settings = $this->get_settings_for_display();

    $item_setting_key = $this->get_repeater_setting_key('block', 'items', $index);
    $link_setting_key = $this->get_repeater_setting_key('link', 'items', $index);

    $has_link = isset($atts['link']['url']) && $atts['link']['url'];
    $img_html_hover = '';

    if (isset($atts['background_image']['id']) && $img = yprm_get_image($atts['background_image']['id'], 'img')) {
      $img_html = $img;
    } else {
      return false;
    }

    if (isset($atts['background_image_hover']['id']) && $img = yprm_get_image($atts['background_image_hover']['id'], 'img')) {
      $img_html_hover = $img;
    }

    $this->add_render_attribute($item_setting_key, 'class', [
      'brand-item',
      $settings['carousel'] == 'yes' ? 'swiper-slide' : yprm_el_cols($settings),
      empty($img_html_hover) ? 'without-hover' : '',
    ]);

    $this->add_link_attributes($link_setting_key, $atts['link'], true);

    ob_start(); ?>
			<div <?php echo $this->get_render_attribute_string($item_setting_key) ?>>
				<div class="content">
					<?php if ($has_link) {?>
						<a <?php echo $this->get_render_attribute_string($link_setting_key) ?>>
					<?php }?>
						<?php echo wp_kses($img_html, 'post') ?>
						<?php if (!empty($img_html_hover)) {
      echo wp_kses($img_html_hover, 'post');
    }?>
					<?php if ($has_link) {?>
						</a>
					<?php }?>
				</div>
			</div>
		<?php return ob_get_clean();
  }

}