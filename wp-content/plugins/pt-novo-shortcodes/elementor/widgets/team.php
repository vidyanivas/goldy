<?php

namespace Elementor;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Widget_Base;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

class Elementor_Team_Widget extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('team-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/team.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_team';
  }

  public function get_title() {
    return esc_html__('Team ', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-team';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['team-handler'];
  }

  public function get_keywords() {
    return ['team', 'members', 'pt', 'novo'];
  }

  protected function register_controls() {

    $this->start_controls_section('section_general_settings', [
      'label' => __('General Settings', 'novo'),
    ]);

    $this->add_control('layout', [
      'type' => Controls_Manager::SELECT,
      'label' => __('Choose Layout', 'novo'),
      'default' => 'grid',
      'options' => [
        'grid' => __('Grid', 'novo'),
        'carousel' => __('Carousel', 'novo'),
      ],
    ]);

    $this->add_group_control(Group_Control_Image_Size::get_type(), [
      'name' => 'thumbnail_size',
      'label' => __('Team Member Image Size', 'novo'),
      'default' => 'full',
      'exclude' => ['custom'],
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_carousel_settings', [
      'label' => __('Carousel Settings', 'novo'),
      'condition' => [
        'layout' => ['carousel'],
      ],
    ]);

    $this->add_control('arrows', [
      'type' => Controls_Manager::SWITCHER,
      'label_off' => __('No', 'novo'),
      'label_on' => __('Yes', 'novo'),
      'return_value' => 'yes',
      'default' => 'yes',
      'label' => __('Prev/Next Arrows?', 'novo'),
    ]);

    $this->add_control('dots', [
      'type' => Controls_Manager::SWITCHER,
      'label_off' => __('No', 'novo'),
      'label_on' => __('Yes', 'novo'),
      'return_value' => 'yes',
      'default' => 'yes',
      'label' => __('Show dot indicators for navigation?', 'novo'),
    ]);
    $this->add_control('pause_on_hover', [
      'type' => Controls_Manager::SWITCHER,
      'label_off' => __('No', 'novo'),
      'label_on' => __('Yes', 'novo'),
      'return_value' => 'yes',
      'default' => 'yes',
      'label' => __('Pause on Hover?', 'novo'),
    ]);
    $this->add_control('loop', [
      'type' => Controls_Manager::SWITCHER,
      'label_off' => __('No', 'novo'),
      'label_on' => __('Yes', 'novo'),
      'return_value' => 'yes',
      'default' => 'yes',
      "description" => __("Should the animation loop?", "livemesh-el-addons"),
      "label" => __("Loop", "livemesh-el-addons"),
    ]);
    $this->add_control('autoplay', [
      'type' => Controls_Manager::SWITCHER,
      'label_off' => __('No', 'novo'),
      'label_on' => __('Yes', 'novo'),
      'return_value' => 'yes',
      'default' => 'no',
      'label' => __('Autoplay?', 'novo'),
      'description' => __('Should the slider autoplay as in a slideshow.', 'novo'),
    ]);
    $this->add_control('autoplay_speed', [
      'label' => __('Autoplay speed in ms', 'novo'),
      'type' => Controls_Manager::NUMBER,
      'default' => 3000,
    ]);
    $this->add_control('animation_speed', [
      'label' => __('Autoplay animation speed in ms', 'novo'),
      'type' => Controls_Manager::NUMBER,
      'default' => 300,
    ]);
    $this->end_controls_section();

    $this->start_controls_section('section_responsive', [
      'label' => __('Responsive Options', 'novo'),
      'condition' => [
        'layout' => ['carousel'],
      ],
    ]);

    $this->add_control('display_columns', [
      'label' => __('Desktop Columns per row', 'novo'),
      'type' => Controls_Manager::NUMBER,
      'min' => 1,
      'max' => 4,
      'step' => 1,
      'default' => 3,
    ]);

    $this->add_control('tablet_display_columns', [
      'label' => __('tablet Columns per row', 'novo'),
      'type' => Controls_Manager::NUMBER,
      'min' => 1,
      'max' => 4,
      'step' => 1,
      'default' => 2,
    ]);

    $this->add_control('mobile_display_columns', [
      'label' => __('Mobile Columns per row', 'novo'),
      'type' => Controls_Manager::NUMBER,
      'min' => 1,
      'max' => 4,
      'step' => 1,
      'default' => 1,
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_grid_settings', [
      'label' => __('Grid Settings', 'novo'),
      'condition' => [
        'layout' => ['grid'],
      ],
    ]);

    $this->add_responsive_control('per_line', [
      'label' => __('Columns per row', 'novo'),
      'type' => Controls_Manager::SELECT,
      'default' => '3',
      'tablet_default' => '2',
      'mobile_default' => '1',
      'options' => [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
      ],
      'frontend_available' => true,
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_team', [
      'label' => __('Team', 'novo'),
    ]);

    $repeater = new Repeater();

    $repeater->add_control('name', [
      'label' => __('Name', 'novo'),
      'type' => Controls_Manager::TEXT,
      'label_block' => true,
    ]);
    $repeater->add_control('post', [
      'label' => __('Post', 'novo'),
      'type' => Controls_Manager::TEXT,
      'label_block' => true,
      'label_block' => true,
    ]);

    $repeater->add_control('avatar', [
      'label' => __('Avatar', 'novo'),
      'type' => Controls_Manager::MEDIA,
      'default' => [
        'url' => Utils::get_placeholder_image_src(),
      ],
      'label_block' => true,
    ]);
    $repeater->add_control('social_link1', [
      'label' => __('Social Link 1', 'novo'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before',
    ]);
    $repeater->add_control(
      'social_icon_1',
      [
        'label' => esc_html__( 'Icon', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "" => esc_html__('---', 'novo'),
          "fa fa-adn,App.net" => esc_html__('App.net', 'novo'),
          "fa fa-bitbucket,Bitbucket" => esc_html__('Bitbucket', 'novo'),
          "fa fa-dropbox,Dropbox" => esc_html__('Dropbox', 'novo'),
          "fa fa-facebook,Facebook" => esc_html__('Facebook', 'novo'),
          "fa fa-flickr,Flickr" => esc_html__('Flickr', 'novo'),
          "fa fa-foursquare,Foursquare" => esc_html__('Foursquare', 'novo'),
          "fa fa-github,GitHub" => esc_html__('GitHub', 'novo'),
          "fa fa-google,Google" => esc_html__('Google', 'novo'),
          "fa fa-instagram,Instagram" => esc_html__('Instagram', 'novo'),
          "fa fa-linkedin,LinkedIn" => esc_html__('LinkedIn', 'novo'),
          "fa fa-windows,Windows" => esc_html__('Windows', 'novo'),
          "fa fa-odnoklassniki,Odnoklassniki" => esc_html__('Odnoklassniki', 'novo'),
          "fa fa-openid,OpenID" => esc_html__('OpenID', 'novo'),
          "fa fa-pinterest,Pinterest" => esc_html__('Pinterest', 'novo'),
          "fa fa-reddit,Reddit" => esc_html__('Reddit', 'novo'),
          "fa fa-soundcloud,SoundCloud" => esc_html__('SoundCloud', 'novo'),
          "fa fa-tumblr,Tumblr" => esc_html__('Tumblr', 'novo'),
          "fa fa-twitter,Twitter" => esc_html__('Twitter', 'novo'),
          "fa fa-vimeo-square,Vimeo" => esc_html__('Vimeo', 'novo'),
          "fa fa-vk,VK" => esc_html__('VK', 'novo'),
          "fa fa-yahoo,Yahoo" => esc_html__('Yahoo', 'novo'),
        ],
        'default' => '',
      ]
    );
    $repeater->add_control(
      'social_link_1',
      [
        'label' => esc_html__( 'Link', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true
      ]
    );
    $repeater->add_control('social_link2', [
      'label' => __('Social Link 2', 'novo'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before',
    ]);
    $repeater->add_control(
      'social_icon_2',
      [
        'label' => esc_html__( 'Icon', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "" => esc_html__('---', 'novo'),
          "fa fa-adn,App.net" => esc_html__('App.net', 'novo'),
          "fa fa-bitbucket,Bitbucket" => esc_html__('Bitbucket', 'novo'),
          "fa fa-dropbox,Dropbox" => esc_html__('Dropbox', 'novo'),
          "fa fa-facebook,Facebook" => esc_html__('Facebook', 'novo'),
          "fa fa-flickr,Flickr" => esc_html__('Flickr', 'novo'),
          "fa fa-foursquare,Foursquare" => esc_html__('Foursquare', 'novo'),
          "fa fa-github,GitHub" => esc_html__('GitHub', 'novo'),
          "fa fa-google,Google" => esc_html__('Google', 'novo'),
          "fa fa-instagram,Instagram" => esc_html__('Instagram', 'novo'),
          "fa fa-linkedin,LinkedIn" => esc_html__('LinkedIn', 'novo'),
          "fa fa-windows,Windows" => esc_html__('Windows', 'novo'),
          "fa fa-odnoklassniki,Odnoklassniki" => esc_html__('Odnoklassniki', 'novo'),
          "fa fa-openid,OpenID" => esc_html__('OpenID', 'novo'),
          "fa fa-pinterest,Pinterest" => esc_html__('Pinterest', 'novo'),
          "fa fa-reddit,Reddit" => esc_html__('Reddit', 'novo'),
          "fa fa-soundcloud,SoundCloud" => esc_html__('SoundCloud', 'novo'),
          "fa fa-tumblr,Tumblr" => esc_html__('Tumblr', 'novo'),
          "fa fa-twitter,Twitter" => esc_html__('Twitter', 'novo'),
          "fa fa-vimeo-square,Vimeo" => esc_html__('Vimeo', 'novo'),
          "fa fa-vk,VK" => esc_html__('VK', 'novo'),
          "fa fa-yahoo,Yahoo" => esc_html__('Yahoo', 'novo'),
        ],
        'default' => '',
      ]
    );
    $repeater->add_control(
      'social_link_2',
      [
        'label' => esc_html__( 'Link', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true
      ]
    );
    $repeater->add_control('social_link3', [
      'label' => __('Social Link 3', 'novo'),
      'type' => Controls_Manager::HEADING,
      'separator' => 'before',
    ]);
    $repeater->add_control(
      'social_icon_3',
      [
        'label' => esc_html__( 'Icon', 'pt-addons' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "" => esc_html__('---', 'novo'),
          "fa fa-adn,App.net" => esc_html__('App.net', 'novo'),
          "fa fa-bitbucket,Bitbucket" => esc_html__('Bitbucket', 'novo'),
          "fa fa-dropbox,Dropbox" => esc_html__('Dropbox', 'novo'),
          "fa fa-facebook,Facebook" => esc_html__('Facebook', 'novo'),
          "fa fa-flickr,Flickr" => esc_html__('Flickr', 'novo'),
          "fa fa-foursquare,Foursquare" => esc_html__('Foursquare', 'novo'),
          "fa fa-github,GitHub" => esc_html__('GitHub', 'novo'),
          "fa fa-google,Google" => esc_html__('Google', 'novo'),
          "fa fa-instagram,Instagram" => esc_html__('Instagram', 'novo'),
          "fa fa-linkedin,LinkedIn" => esc_html__('LinkedIn', 'novo'),
          "fa fa-windows,Windows" => esc_html__('Windows', 'novo'),
          "fa fa-odnoklassniki,Odnoklassniki" => esc_html__('Odnoklassniki', 'novo'),
          "fa fa-openid,OpenID" => esc_html__('OpenID', 'novo'),
          "fa fa-pinterest,Pinterest" => esc_html__('Pinterest', 'novo'),
          "fa fa-reddit,Reddit" => esc_html__('Reddit', 'novo'),
          "fa fa-soundcloud,SoundCloud" => esc_html__('SoundCloud', 'novo'),
          "fa fa-tumblr,Tumblr" => esc_html__('Tumblr', 'novo'),
          "fa fa-twitter,Twitter" => esc_html__('Twitter', 'novo'),
          "fa fa-vimeo-square,Vimeo" => esc_html__('Vimeo', 'novo'),
          "fa fa-vk,VK" => esc_html__('VK', 'novo'),
          "fa fa-yahoo,Yahoo" => esc_html__('Yahoo', 'novo'),
        ],
        'default' => '',
      ]
    );
    $repeater->add_control(
      'social_link_3',
      [
        'label' => esc_html__( 'Link', 'pt-addons' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => true
      ]
    );
    

    $this->add_control('team_members', [
      'label' => __('Team Members', 'novo'),
      'type' => Controls_Manager::REPEATER,
      'separator' => 'before',
      'fields' => $repeater->get_controls(),
      'title_field' => '{{{ name }}}',
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_team_member_title', [
      'label' => __('Member Title', 'novo'),
      'tab' => Controls_Manager::TAB_STYLE,
    ]);
    $this->add_control('title_color', [
      'label' => __('Color', 'novo'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .team-item .name' => 'color: {{VALUE}};',
      ],
    ]);
    $this->add_control('title_hover_color', [
      'label' => __('Hover Color for Link', 'novo'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .team-item .name-link:hover .lae-title' => 'color: {{VALUE}};',
      ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'title_typography',
      'selector' => '{{WRAPPER}} .team-item .name',
    ]);
    $this->end_controls_section();

    $this->start_controls_section('section_team_post', [
      'label' => __('Member Position', 'novo'),
      'tab' => Controls_Manager::TAB_STYLE,
    ]);
    $this->add_control('position_color', [
      'label' => __('Color', 'novo'),
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} .team-item .post' => 'color: {{VALUE}};',
      ],
    ]);

    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name' => 'position_typography',
      'selector' => '{{WRAPPER}} .team-item .post',
    ]);

    $this->end_controls_section();

    $this->start_controls_section('section_social_icon_styling', [
      'label' => __('Social Icons', 'novo'),
      'tab' => Controls_Manager::TAB_STYLE,
    ]);

    $this->add_control('social_icon_size', [
      'label' => __('Icon size in pixels', 'novo'),
      'type' => Controls_Manager::SLIDER,
      'size_units' => ['px'],
      'range' => [
        'px' => [
          'min' => 5,
          'max' => 20,
        ],
      ],
      'selectors' => [
        '{{WRAPPER}} .team-social-buttons a i' => 'font-size: {{SIZE}}{{UNIT}};',
      ],
    ]);

    $this->add_control('social_icon_spacing', [
      'label' => __('Spacing', 'novo'),
      'description' => __('Space between icons.', 'novo'),
      'type' => Controls_Manager::DIMENSIONS,
      'size_units' => ['px'],
      'selectors' => [
        '{{WRAPPER}} .team-social-buttons a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ],
    ]);

    $this->add_control('social_icon_color', [
      'label' => __('Icon Color', 'novo'),
      'type' => Controls_Manager::COLOR,
      'default' => '',
      'selectors' => [
        '{{WRAPPER}} .team-social-buttons a i' => 'color: {{VALUE}};',
      ],
    ]);
    $this->add_control('social_icon_hover_color', [
      'label' => __('Icon Hover Color', 'novo'),
      'type' => Controls_Manager::COLOR,
      'default' => '',
      'selectors' => [
        '{{WRAPPER}} .team-social-buttons a i:hover' => 'color: {{VALUE}};',
      ],
    ]);

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if ('' === $settings['team_members']) {
      return;
    }

    $this->add_render_attribute('wrapper', 'class', 'team-items row');
    $this->add_render_attribute('wrapper-inner', 'class', 'team-item item');

    if ($settings['layout'] == 'carousel') {
      $this->add_render_attribute('wrapper', 'class', 'team-carousel');
    } else {
      $per_line = $settings['per_line'] ?: 3;
      $per_line_tablet = $settings['per_line_tablet'] ?: 2;
      $per_line_mobile = $settings['per_line_mobile'] ?: 3;

      $this->add_render_attribute('wrapper-inner', 'class', 'col-xs-' . (12 / $per_line_mobile) . ' col-sm-' . (12 / $per_line_tablet) . ' col-md-' . (12 / $per_line));
    }

    $this->add_render_attribute(
      [
        'wrapper' => [
          'data-banner-settings' => [
            wp_json_encode(array_filter([
              "loop" => ("yes" == $settings["loop"]) ? true : false,
              "autoplay" => ("yes" == $settings["autoplay"]) ? true : false,
              "arrows" => ("yes" == $settings["arrows"]) ? true : false,
              "dots" => ("yes" == $settings["dots"]) ? true : false,
              "pause_on_hover" => ("yes" == $settings["pause_on_hover"]) ? true : false,
              "autoplay_speed" => $settings['autoplay_speed'],
              "speed" => $settings['animation_speed'],
              'desktop_columns' => $settings['display_columns'] ?: 3,
              'tablet_columns' => $settings['tablet_display_columns'] ?: 2,
              'mobile_columns' => $settings['mobile_display_columns'] ?: 1,
            ])),
          ],
        ],
      ]
    );
  ?>
    <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
      <?php foreach ($settings['team_members'] as $member): ?>
        <div <?php echo $this->get_render_attribute_string('wrapper-inner'); ?>>
          <div class="wrap">
            <?php if (isset($member['avatar']['url'])):
              $image_url = wp_get_attachment_image_url($member['avatar']['id'], $settings['thumbnail_size_size']);
            ?>
            <div class="image" style="background-image: url(<?php echo esc_url($image_url); ?> );">
              <div><?php echo $this->social_buttons($member); ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($member['name'])): ?>
              <div class="name"><?php echo esc_html($member['name']); ?></div>
            <?php endif;?>
            <?php if (!empty($member['post'])): ?>
              <div class="post"><?php echo esc_html($member['post']); ?></div>
            <?php endif;?>
          </div>
        </div>
      <?php endforeach;?>
    </div>
  <?php }

  protected function social_buttons($member) {
    $social_buttons_html = '';

    if(!empty($member['social_icon_1']) && !empty($member['social_link_1'])) {
      $icon = explode(',', $member['social_icon_1']);
      $social_buttons_html .= '<a href="' . esc_url($member['social_link_1']) . '" target="_blank"><i class="' . esc_attr($icon[0]) . '"></i></a>';
    }
    if(!empty($member['social_icon_2']) && !empty($member['social_link_2'])) {
      $icon = explode(',', $member['social_icon_2']);
      $social_buttons_html .= '<a href="' . esc_url($member['social_link_2']) . '" target="_blank"><i class="' . esc_attr($icon[0]) . '"></i></a>';
    }
    if(!empty($member['social_icon_3']) && !empty($member['social_link_3'])) {
      $icon = explode(',', $member['social_icon_3']);
      $social_buttons_html .= '<a href="' . esc_url($member['social_link_3']) . '" target="_blank"><i class="' . esc_attr($icon[0]) . '"></i></a>';
    }

    if($social_buttons_html) {
      echo '<div class="team-social-buttons">';
        echo $social_buttons_html;
      echo '</div>';
    }
  }
}