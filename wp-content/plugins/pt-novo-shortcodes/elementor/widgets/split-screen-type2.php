<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Elementor_Split_Screen_Type2_Widget extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('split-screen-type2-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/split-screen-type2.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_split_screen_type2';
  }

  public function get_title() {
    return esc_html__('Split Screen Type 2', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-split-screen-type2';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['owl-carousel', 'touch-swipe', 'split-screen-type2-handler'];
    }

    return ['owl-carousel', 'touch-swipe', 'split-screen-type2-handler'];
  }

  public function get_style_depends() {
    return ['owl-carousel'];
  }

  public function get_keywords() {
    return ['split-screen-type2', 'tabs', 'toggle'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'section_title',
      [
        'label' => __('General', 'novo'),
      ]
    );

    $this->add_control(
      'image_on_popup',
      [
        'label' => esc_html__( 'Open Image on Popup', 'pt-addons' ),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__( 'Yes', 'pt-addons' ),
        'label_off' => esc_html__( 'No', 'pt-addons' ),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'dots',
      [
        'label' => esc_html__( 'Pagination', 'pt-addons' ),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__( 'Yes', 'pt-addons' ),
        'label_off' => esc_html__( 'No', 'pt-addons' ),
        'return_value' => 'on',
        'default' => 'on',
      ]
    );

    $this->add_control(
      'dots_color',
      [
        'label' => esc_html__('Pagination Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .split-screen' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'screen_title',
      [
        'label' => __('Screens', 'novo'),
      ]
    );

    $repeater = new Repeater();

    $repeater->add_control(
      'left_side_hr',
      [
        'label' => esc_html__( 'Side left', 'pt-addons' ),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_control(
      'left_color',
      [
        'label' => esc_html__( 'Color', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'black'  => esc_html__( 'Black', 'pt-addons' ),
          'white'  => esc_html__( 'White', 'pt-addons' ),
        ],
        'default' => 'black',
      ]
    );

    $repeater->add_control(
      'left_header_position',
      [
        'label' => esc_html__( 'Header position', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'top'  => esc_html__( 'on top', 'pt-addons' ),
          'bottom'  => esc_html__( 'on bottom', 'pt-addons' ),
        ],
        'default' => 'top',
      ]
    );

    $repeater->add_control(
      'left_heading_num',
      [
        'label' => esc_html__( 'Heading num', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'left_heading',
      [
        'label' => esc_html__( 'Heading', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'left_sub_heading_on_top',
      [
        'label' => esc_html__( 'Sub Heading on top', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'left_sub_heading_on_bottom',
      [
        'label' => esc_html__( 'Sub Heading on bottom', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'left_image',
      [
        'label' => esc_html__( 'Image', 'pt-addons' ),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'left_video_url',
      [
        'label' => esc_html__( 'Video url', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'description' => esc_html__( 'Supported YouTube & Vimeo', 'pt-addons' ),
        'label_block' => true,
      ]
    );

    $repeater->add_group_control(
      Group_Control_Link::get_type(),
      [
        'name' => 'left_link',
        'label' => esc_html__( 'Link', 'pt-addons' ),
      ]
    );

    $repeater->add_control(
      'left_background_color_hex',
      [
        'label' => esc_html__( 'Background color', 'pt-addons' ),
        'type' => Controls_Manager::COLOR,
        "description" => esc_html__("optional", "pt-addons"),
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}.item-left ' => 'background-color: {{VALUE}}',
        ],
      ]
    );

    $repeater->add_control(
      'left_bg_image',
      [
        'label' => esc_html__( 'Background image', 'pt-addons' ),
        'type' => Controls_Manager::MEDIA,
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}.item-left ' => 'background-image: url({{URL}})',
        ],
      ]
    );

    $repeater->add_control(
      'left_text_color_hex',
      [
        'label' => esc_html__( 'Select Text color', 'pt-addons' ),
        'type' => Controls_Manager::COLOR,
        "description" => esc_html__("optional", "pt-addons"),
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}.item-left ' => 'color: {{VALUE}}',
        ],
      ]
    );

    $repeater->add_control(
      'right_side_hr',
      [
        'label' => esc_html__( 'Side right', 'pt-addons' ),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $repeater->add_control(
      'right_color',
      [
        'label' => esc_html__( 'Color', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'black'  => esc_html__( 'Black', 'pt-addons' ),
          'white'  => esc_html__( 'White', 'pt-addons' ),
        ],
        'default' => 'black',
      ]
    );

    $repeater->add_control(
      'right_header_position',
      [
        'label' => esc_html__( 'Header position', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'top'  => esc_html__( 'on top', 'pt-addons' ),
          'bottom'  => esc_html__( 'on bottom', 'pt-addons' ),
        ],
        'default' => 'top',
      ]
    );

    $repeater->add_control(
      'right_heading_num',
      [
        'label' => esc_html__( 'Heading num', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'right_heading',
      [
        'label' => esc_html__( 'Heading', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'right_sub_heading_on_top',
      [
        'label' => esc_html__( 'Sub Heading on top', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'right_sub_heading_on_bottom',
      [
        'label' => esc_html__( 'Sub Heading on bottom', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $repeater->add_control(
      'right_image',
      [
        'label' => esc_html__( 'Image', 'pt-addons' ),
        'type' => Controls_Manager::MEDIA,
      ]
    );

    $repeater->add_control(
      'right_video_url',
      [
        'label' => esc_html__( 'Video url', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'description' => esc_html__( 'Supported YouTube & Vimeo', 'pt-addons' ),
        'label_block' => true,
      ]
    );

    $repeater->add_group_control(
      Group_Control_Link::get_type(),
      [
        'name' => 'right_link',
        'label' => esc_html__( 'Link', 'pt-addons' ),
      ]
    );

    $repeater->add_control(
      'right_background_color_hex',
      [
        'label' => esc_html__( 'Background color', 'pt-addons' ),
        'type' => Controls_Manager::COLOR,
        "description" => esc_html__("optional", "pt-addons"),
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}.item-right ' => 'background-color: {{VALUE}}',
        ],
      ]
    );

    $repeater->add_control(
      'right_bg_image',
      [
        'label' => esc_html__( 'Background image', 'pt-addons' ),
        'type' => Controls_Manager::MEDIA,
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}.item-right ' => 'background-image: url({{URL}})',
        ],
      ]
    );

    $repeater->add_control(
      'right_text_color_hex',
      [
        'label' => esc_html__( 'Select Text color', 'pt-addons' ),
        'type' => Controls_Manager::COLOR,
        "description" => esc_html__("optional", "pt-addons"),
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}.item-right ' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'screens',
      [
        'label' => __('Screens', 'novo'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
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
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();

    $this->add_render_attribute('wrapper', 'class', [
      'split-screen-type2',
    ]);

    if ($settings['dots'] != 'on') {
      $this->add_render_attribute('wrapper', 'class', 'pagination-off');
    }

    ?>
		<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
      <?php echo $this->get_social_buttons() ?>
      <div class="items">
        <?php foreach ($settings['screens'] as $index => $screen):
          $left_item_key = 'left-item-'.$screen['_id'];
          $right_item_key = 'right-item-'.$screen['_id'];
          $left_link_key = 'left-link-'.$screen['_id'];
          $right_link_key = 'right-link-'.$screen['_id'];
        
          $this->add_render_attribute($left_item_key, [
            'class' => [
              'item',
              'popup-gallery',
              'item-left',
              'col-12',
              'col-sm-6',
              'elementor-repeater-item-'.$screen['_id'],
              $screen['left_color'],
              'header-on-'.$screen['left_header_position'],
            ]
          ]);
      
          $this->add_render_attribute($right_item_key, [
            'class' => [
              'item',
              'popup-gallery',
              'item-right',
              'col-12',
              'col-sm-6',
              'elementor-repeater-item-'.$screen['_id'],
              $screen['right_color'],
              'header-on-'.$screen['right_header_position'],
            ]
          ]);

          if(isset($screen['left_link_url']['url'])) {
            $this->add_link_attributes($left_link_key, $screen['left_link_url']);
            $this->add_render_attribute($left_link_key, 'class', [
              'button-style1',
              'permalink'
            ]);
          }

          if(isset($screen['right_link_url']['url'])) {
            $this->add_link_attributes($right_link_key, $screen['right_link_url']);
            $this->add_render_attribute($right_link_key, 'class', [
              'button-style1',
              'permalink'
            ]);
          }

          $full_left_src = $full_right_src = '';
          if(isset($screen['left_image']['id'])) {
            $full_left_src = wp_get_attachment_image_src($screen['left_image']['id'], 'full');
          }
          if(isset($screen['right_image']['id'])) {
            $full_right_src = wp_get_attachment_image_src($screen['right_image']['id'], 'full');
          }
        ?>
        <div class="screen-item">
          <div class="row">
            <div <?php echo $this->get_render_attribute_string($left_item_key) ?>>
              <div class="heading">
                <div>
                  <?php if($screen['left_heading_num']) { ?>
                  <div class="num"><?php echo strip_tags($screen['left_heading_num']) ?></div>
                  <?php } ?>
                  <div class="text">
                    <?php if($screen['left_sub_heading_on_top']) { ?>
                    <div class="s"><?php echo strip_tags($screen['left_sub_heading_on_top']) ?></div>
                    <?php } if($screen['left_heading']) { ?>
                    <div class="h"><?php echo strip_tags($screen['left_heading']) ?></div>
                    <?php } if($screen['left_sub_heading_on_bottom']) { ?>
                    <div class="d"><?php echo strip_tags($screen['left_sub_heading_on_bottom']) ?></div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php if(is_array($full_left_src) && $full_left_src) { ?>
                <div class="image popup-item">
                  <?php if($settings['image_on_popup'] == 'on') { ?>
                    <?php if($video_url = \VideoUrlParser::get_url_embed($screen['left_video_url'])) { ?>
                      <a class="play-button web-application-icon-right-arrow-play-button" href="https://www.youtube.com/watch?v=oWjU2SqPOVA" data-type="video" data-size="960x640" data-video="<div class=&quot;wrapper&quot;><div class=&quot;video-wrapper&quot;><iframe class=&quot;pswp__video&quot; width=&quot;960&quot; height=&quot;640&quot; src=&quot;<?php echo esc_attr($video_url) ?>&quot; frameborder=&quot;0&quot; allowfullscreen></iframe></div></div>"></a>
                    <?php } else { ?>
                      <a class="popup-link popup-single" href="<?php echo esc_url($full_left_src[0]) ?>" data-size="<?php echo esc_attr($full_left_src[1].'x'.$full_left_src[2]) ?>" data-id="<?php echo $index ?>"></a>
                    <?php } ?>
                  <?php } ?>
                  <?php echo wp_get_attachment_image($screen['left_image']['id'], 'large') ?>
                </div>
              <?php } if(isset($screen['left_link_url']['url']) && $screen['left_link_url']['url']) { ?>
                <div class="button">
                  <a <?php echo $this->get_render_attribute_string($left_link_key) ?>><?php echo strip_tags($screen['left_link_label']) ?></a>
                </div>
              <?php } ?>
            </div>
            <div <?php echo $this->get_render_attribute_string($right_item_key) ?>>
              <div class="heading right">
                <div>
                  <?php if($screen['right_heading_num']) { ?>
                  <div class="num"><?php echo strip_tags($screen['right_heading_num']) ?></div>
                  <?php } ?>
                  <div class="text">
                    <?php if($screen['right_sub_heading_on_top']) { ?>
                    <div class="s"><?php echo strip_tags($screen['right_sub_heading_on_top']) ?></div>
                    <?php } if($screen['right_heading']) { ?>
                    <div class="h"><?php echo strip_tags($screen['right_heading']) ?></div>
                    <?php } if($screen['right_sub_heading_on_bottom']) { ?>
                    <div class="d"><?php echo strip_tags($screen['right_sub_heading_on_bottom']) ?></div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php if(is_array($full_right_src) && $full_right_src) { ?>
                <div class="image popup-item">
                  <?php if($settings['image_on_popup'] == 'on') { ?>
                    <?php if($video_url = \VideoUrlParser::get_url_embed($screen['right_video_url'])) { ?>
                      <a class="play-button web-application-icon-right-arrow-play-button" href="https://www.youtube.com/watch?v=oWjU2SqPOVA" data-type="video" data-size="960x640" data-video="<div class=&quot;wrapper&quot;><div class=&quot;video-wrapper&quot;><iframe class=&quot;pswp__video&quot; width=&quot;960&quot; height=&quot;640&quot; src=&quot;<?php echo esc_attr($video_url) ?>&quot; frameborder=&quot;0&quot; allowfullscreen></iframe></div></div>"></a>
                    <?php } else { ?>
                      <a class="popup-link popup-single" href="<?php echo esc_url($full_right_src[0]) ?>" data-size="<?php echo esc_attr($full_right_src[1].'x'.$full_right_src[2]) ?>" data-id="<?php echo $index ?>"></a>
                    <?php } ?>
                  <?php } ?>
                  <?php echo wp_get_attachment_image($screen['right_image']['id'], 'large') ?>
                </div>
              <?php } if(isset($screen['right_link_url']['url']) && $screen['right_link_url']['url']) { ?>
                <div class="button">
                  <a <?php echo $this->get_render_attribute_string($right_link_key) ?>><?php echo strip_tags($screen['right_link_label']) ?></a>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php if($settings['dots'] == 'on') { ?>
        <div class="pagination-dots"></div>
      <?php } ?>
		</div>
  <?php }

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
}
