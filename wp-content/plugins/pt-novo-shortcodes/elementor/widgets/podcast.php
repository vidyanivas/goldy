<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Podcast_Widget extends Widget_Base {

  var $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('jplayer', plugins_url('pt-music-album') . '/include/js/jquery.jplayer.min.js');

    wp_register_script('podcast-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/podcast.js', array('jquery', 'jquery-isotope'), '', true);
  }

  public function get_name() {
    return 'yprm_podcast';
  }

  public function get_title() {
    return esc_html__('Podcast', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-podcast';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
      return ['jplayer', 'podcast-handler'];
    }

    return ['jplayer', 'podcast-handler'];
  }

  protected function register_controls() {

    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Content', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'track_url',
      [
        'label' => __('Track url', 'pt-novo'),
        'type' => Controls_Manager::TEXT,
        'placeholder' => 'http://example.com/podcast/red.mp3',
        'label_block' => true,
      ]
    );

    $this->add_control(
      'cover_image_url',
      [
        'label' => __('Cover Image', 'pt-novo'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $this->add_control(
      'sub_heading',
      [
        'label' => __('Sub Heading', 'pt-novo'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $this->add_control(
      'heading',
      [
        'label' => __('Heading', 'pt-novo'),
        'type' => Controls_Manager::TEXT,
        'label_block' => true,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'customizing',
      [
        'label' => esc_html__('Heading', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'album_name',
      [
        'label' => esc_html__('Heading', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .top-text .track-name' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'album_typo',
        'selector' => '{{WRAPPER}} .album-playlist .top-text .track-name',
      ]
    );

    $this->add_control(
      'heading_background_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .top-text .track-name' => 'background: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'sub_heading_style',
      [
        'label' => esc_html__('Heading', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'list_album_name',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .top-text .album-name' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'list_album_typo',
        'selector' => '{{WRAPPER}} .album-playlist .top-text .album-name',
      ]
    );

    $this->add_control(
      'list_background_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .top-text .album-name' => 'background: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'player_controls',
      [
        'label' => esc_html__('Controls', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'controls_color',
      [
        'label' => esc_html__('Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .track-buttons button, {{WRAPPER}} .album-playlist .bottom .time, {{WRAPPER}} .album-playlist .top i, {{WRAPPER}} .album-playlist .jp-mute' => 'color: {{VALUE}}',
          '{{WRAPPER}} .album-playlist .jp-volume-bar' => 'background: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if ('' === $settings['track_url']) {
      return false;
    }

    $this->add_render_attribute(
      [
        'wrapper' => [
          'data-title' => $settings['heading'],
          'data-track-url' => $settings['track_url'],
        ],
      ]
    );

    $this->add_render_attribute('wrapper', 'class', 'jp_container_podcast');
    $this->add_render_attribute('wrapper', 'id', esc_attr($this->get_id()));

    $cover_image_url = wp_get_attachment_image_src($settings['cover_image_url']['id'], 'full')[0] ?: '';
    ?>
        <div <?php echo $this->get_render_attribute_string('wrapper') ?>>
            <div id="jquery_jplayer_<?php echo esc_attr($this->get_id()); ?>" class="jp-jplayer"></div>
            <div id="jp_container_<?php echo esc_attr($this->get_id()); ?>" class="jp-audio album-area" role="application">
                <div class="album-playlist">
                    <div class="top">
                        <?php if (!empty($cover_image_url)) {?>
                            <div class="bg" style="background-image: url(<?php echo $cover_image_url ?>)"></div>
                        <?php }?>
                        <div class="top-playbutton jp-play">
                            <?php if (!empty($cover_image_url)) {?>
                                <div class="pb-bg" style="background-image: url(<?php echo $cover_image_url ?>)"></div>
                            <?php } else {?>
                                <div class="pb-bg"></div>
                            <?php }?>
                            <i class="music-and-multimedia-play-button"></i>
                        </div>
                        <div class="top-text">
                            <?php if (!empty($settings['sub_heading'])) {?>
                                <div class="album-name"><?php echo wp_kses_post($settings['sub_heading']); ?></div>
                            <?php }if (!empty($settings['heading'])) {?>
                                <div class="track-name"><?php echo wp_kses_post($settings['heading']); ?></div>
                            <?php }?>
                            <div class="track-buttons">
                                <button class="jp-previous music-and-multimedia-backward-button"></button>
                                <button class="jp-play music-and-multimedia-play-button"></button>
                                <button class="jp-next music-and-multimedia-fast-forward-button"></button>
                            </div>
                            <div class="bottom">
                                <div class="time">
                                    <div class="jp-current-time"></div>
                                    <div class="separate">/</div>
                                    <div class="jp-duration"></div>
                                </div>
                                <div class="volume">
                                    <button class="jp-mute music-and-multimedia-speaker-with-waves"></button>
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jp-progress">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="jp-no-solution">
                        <?php echo __('<span>Update Required</span>To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.', 'pt-music-album') ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
}
}