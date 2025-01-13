<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly

class Elementor_Video_Banner_Widget extends \Elementor\Widget_Base {

  protected $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('video-banner-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/video-banner.js', array('jquery'), '', true);
  }

  public function get_name() {
    return 'yprm_video_banner';
  }

  public function get_title() {
    return esc_html__('Video Banner', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-video-banner';
  }

  public function get_keywords() {
    return ['banner', 'home', 'slider', 'novo'];
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['owl-carousel', 'video-banner-handler'];
  }

  public function get_style_depends() {
    return ['owl-carousel'];
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
        'range' => [
          'px' => [
            'max' => 1500,
          ],
        ],
        'selectors' => [
          "{{WRAPPER}} .banner.banner-items, {{WRAPPER}} .banner.banner-items .cell" => 'height: {{SIZE}}{{UNIT}};',
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
        'label' => __('Video url', 'novo'),
        'type' => Controls_Manager::TEXT,
        'admin_label' => true,
        "description" => esc_html__("Source YouTube, Vimeo and mp4 file", "pt-addons"),
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

    $this->start_controls_section(
      'banner_items_style_tab',
      [
        'label' => __('Banner Items', 'novo'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
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

    $this->add_control(
      'about_banner_heading',
      [
        'label' => __('Heading', 'novo'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'banner_items_typo',
        'selector' => '{{WRAPPER}} .banner-items .item .cell .h',
      ]
    );

    $this->add_control(
      'color_overlay',
      [
        'label' => __('Color overlay', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .banner-items .item-banner:after' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'text_color_hex',
      [
        'label' => __('color', 'novo'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .banner-items .item-banner' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
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

    $this->add_control(
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

    $this->add_control(
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

    $this->end_controls_section();
  }

  protected function set_class_wrapper() {
    $settings = $this->get_settings_for_display();
    $this->add_render_attribute('wrapper', [
      'class' => 'banner-area video-banner banner-' . uniqid(),
    ]);

    $this->add_render_attribute('banner-items', [
      'class' => 'banner banner-items banner-' . uniqid(),
      'id' => 'banner-' . uniqid(),
    ]);

    if (array_key_exists('height', $settings) && !empty($settings['height'])) {
      $this->add_render_attribute('banner-items', 'class', 'fixed-height');
  }

    if ($this->settings['external_indent'] == 'on') {
      $this->add_render_attribute('wrapper', 'class', 'external-indent');
    }

    if ($this->settings['arrows'] == 'on') {
      $this->add_render_attribute('banner-items', 'class', 'arrows-' . $this->settings['arrows_position']);
    }

    if ($this->settings['dots'] == 'on') {
      $this->add_render_attribute('banner-items', 'class', 'pagination-' . $this->settings['dots_position']);
    }

    $params_json = array(
      'loop' => $this->settings["infinite_loop"] == 'on' ? true : false,
      'speed' => intval($this->settings['speed']),
      'autoplay' => false,
      'arrows' => $this->settings["arrows"] == 'on' ? true : false,
      'dots' => $this->settings["dots"] == 'on' ? true : false,
      'dots_position' => $this->settings['dots_position'],
      'animation' => false
    );

    if ($this->settings["autoplay"] == 'on') {
      $autoplay_speed = isset($this->settings['autoplay_speed']['size']) 
          ? intval($this->settings['autoplay_speed']['size']) 
          : 5000; 
  
      $params_json['autoplay'] = [
          'delay' => $autoplay_speed,
          'disableOnInteraction' => false,
          'pauseOnMouseEnter' => isset($this->settings['pauseohover']) && $this->settings['pauseohover'] == 'on',
      ];
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

    if ($this->settings['dots'] == 'on' && $this->settings['dots_position'] == 'bottom') {
      $this->add_render_attribute('wrapper', 'class', 'with-circle-nav');
    }
  }

  protected function render() {
    $settings = $this->settings = $this->get_settings_for_display();

    $this->set_class_wrapper();

    ?>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>

				<?php if ($settings['social_buttons'] == 'on' && $settings['social_items_list']): ?>
					<div class="banner-social-buttons">
						<div class="links">
							<?php $fallback_defaults = [
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
									<?php if ($is_new || $migrated) {
                    \Elementor\Icons_Manager::render_icon($item['social_icon']);
                  } else { ?>
										<i class="<?php echo esc_attr($item['social']); ?>"></i>
									<?php } ?>
									<span><?php echo $social; ?></span>
								</a>
							<?php }?>
						</div>
					</div>
				<?php endif;?>
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

  protected function get_banner_items_wrapper($index, $key, $item) {

    $this->add_render_attribute($key, [
      'class' => ['swiper-slide item item-banner banner-item-' . uniqid()],
      'style' => yprm_get_image($item['image']['id'], 'bg', 'large'),
    ]);

    $this->add_render_attribute($key, 'class', $this->settings['text_align']);

    if ($this->settings['inner_shadow'] == 'on') {
      $this->add_render_attribute($key, 'class', 'with-shadow');
    }
  }

  public function banner_items() {

    if (!$this->settings['banner_items_list']) {
      return false;
    }

    foreach ($this->settings['banner_items_list'] as $index => $item):
      $tab_count = $index + 1;

      $tab_title_setting_key = $this->get_repeater_setting_key('banner-item-list', 'banner_items', $index);

      $this->get_banner_items_wrapper($index, $tab_title_setting_key, $item);

    ?>
      <div <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>>
        <div class="container">
          <div class="cell middle">
            <div class="content popup-gallery images">
              <div class="angle"><span></span><span></span><span></span><span></span></div>
              <?php echo $this->get_sub_heading($item, $index); ?>
              <?php echo $this->get_heading($item, $index); ?>
              <?php echo $this->get_video($item, $index); ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach;
  }

  public function get_sub_heading($item, $index) {
    if (!$item['sub_heading']) return; 
  ?>
    <div class="sub-h"><?php echo wp_kses_post(nl2br($item['sub_heading'])); ?></div>
  <?php }

  public function get_heading($item, $index) {
    if (!$item['heading']) return;
  ?>
    <div class="heading">
      <<?php echo $this->settings['heading_size']; ?> class="h"><?php echo wp_kses_post(nl2br($item['heading'])); ?></<?php echo $this->settings['heading_size']; ?>>
    </div>
  <?php }

  public function get_video($item, $index) {
    if (!$item['video_url']) return;

    $popup_array = [];
    $popup_array['video'] = [
      'html' => \VideoUrlParser::get_player($item['video_url']),
      'w' => 1920,
      'h' => 1080
    ];

    wp_enqueue_script('video-background', get_template_directory_uri() . '/js/jquery.background-video.js', array('jquery'));
    wp_enqueue_script('video', get_template_directory_uri() . '/js/video.js', array('jquery'));

  ?>
    <a href="#" data-type="video" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="0">
      <i class="music-and-multimedia-play-button"></i>
    </a>
  <?php }
}