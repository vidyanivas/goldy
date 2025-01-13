<?php

namespace Elementor;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Music_Album_Item_Widget extends Widget_Base {

  var $settings;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script('jplayer', plugins_url('pt-music-album') . '/include/js/jquery.jplayer.min.js');
    wp_register_script('scrollbar', plugins_url('pt-music-album') . '/include/js/jquery.scrollbar.min.js');

    wp_register_script('music-album-item-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/music-album-item.js', array('jquery'), '', true);
  }

  public function get_name() {
    return 'yprm_music_album_item';
  }

  public function get_title() {
    return esc_html__('Music Album Item', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-music-album-item';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    return ['jplayer', 'scrollbar', 'music-album-item-handler'];
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
      'item',
      [
        'label' => __('Item', 'novo'),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'multiple' => false,
        'options' => novo_get_post_items('pt-music-album'),
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'customizing',
      [
        'label' => esc_html__('First Album', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'album_name',
      [
        'label' => esc_html__('Album Name', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-name' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'album_typo',
        'selector' => '{{WRAPPER}} .album-name',
      ]
    );

    $this->add_control(
      'album_heading',
      [
        'label' => __('Track', 'plugin-name'),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'track_name',
      [
        'label' => esc_html__('Track Name', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .track-name' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'track_typo',
        'selector' => '{{WRAPPER}} .track-name',
      ]
    );

    $this->add_control(
      'heading_background_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .jp-playlist .jp-playlist-current' => 'background: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'heading_padding',
      [
        'label' => esc_html__('Margin', 'pt-addons'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'selectors' => [
          '{{WRAPPER}} .album-playlist .top .top-playbutton, {{WRAPPER}} .album-playlist .top-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'albumns_list',
      [
        'label' => esc_html__('Albums List', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'list_album_name',
      [
        'label' => esc_html__('Album Name', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .jp-playlist .jp-playlist-item' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'list_album_typo',
        'selector' => '{{WRAPPER}} .album-playlist .jp-playlist .jp-playlist-item',
      ]
    );

    $this->add_control(
      'list_background_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .album-playlist .jp-playlist li:nth-child(even) .jp-playlist-item' => 'background: {{VALUE}}',
        ],
      ]
    );

    $this->end_controls_section();
  }

  public static function album_array($id = null) {
    if (empty($id)) {
      global $post;
      $id = $post->ID;
    }
    $array = array(
      'items' => get_post_meta($id, 'pt_music_album_tracks_meta', true),
      'details' => get_post_meta($id, 'pt_music_album_details_meta', true),
    );
    return $array;
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    if ('' === $settings['item']) {
      return false;
    }

    $id = $settings['item'];

    $album_id = uniqid();

    $album_array = $this->album_array($id);
    $album_name = get_the_title($id);
    $js_items = '';

    $thumb = get_post_meta($id, '_thumbnail_id', true);

    if (empty($album_array['items']) || !is_array($album_array['items'])) {
      return false;
    }

    $js_items = [];

    foreach ($album_array['items'] as $key => $track) {
      if (!isset($track['duration']) || !$track['duration']) {
        $mp3file = new \Mp3Data($track['track_url']);
        $duration = $mp3file->mp3data['duration'];
      } else {
        $duration = $track['duration'];
      }

      $js_items[] = [
        'title' => $track['name'],
        'mp3' => $track['track_url'],
        'duration' => $duration,
      ];
    }

    $this->add_render_attribute(
      [
        'wrapper' => [
          'data-player-items' => json_encode($js_items),
        ],
      ]
    );

    $this->add_render_attribute('wrapper', 'class', 'jp_container_album');
    $this->add_render_attribute('wrapper', 'id', esc_attr($this->get_id()));

    ?>
        <div <?php echo $this->get_render_attribute_string('wrapper') ?>>
	        <div id="jquery_jplayer_<?php echo esc_attr($this->get_id()); ?>" class="jp-jplayer"></div>
			<div id="jp_container_<?php echo esc_attr($this->get_id()); ?>" class="jp-audio album-area row" role="application">
				<?php if (!empty($thumb)) {?>
					<div class="col-xs-12 col-sm-6">
				<?php } else {?>
					<div class="col-xs-12">
				<?php }?>
					<div class="album-playlist">
						<div class="top">
							<div class="top-playbutton jp-play">
								<?php if (!empty($thumb)) {?>
									<div class="pb-bg" style="background-image: url(<?php echo wp_get_attachment_image_src($thumb, 'medium')[0] ?>)"></div>
								<?php } else {?>
									<div class="pb-bg"></div>
								<?php }?>
								<i class="music-and-multimedia-play-button"></i>
							</div>
							<div class="top-text">
								<div class="album-name"><?php echo esc_html($album_name); ?></div>
								<div class="track-name"></div>
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
						<div class="jp-playlist">
							<ul>
								<li></li>
							</ul>
						</div>
					</div>
				</div>
				<?php if (!empty($thumb)) {?>
					<div class="col-xs-12 col-sm-6">
						<div class="album-cover" style="background-image: url(<?php echo wp_get_attachment_image_src($thumb, 'large')[0] ?>)"></div>
					</div>
				<?php }?>
				<div class="col-xs-12">
					<div class="jp-no-solution">
						<?php echo __('<span>Update Required</span>To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.', 'pt-music-album') ?>
					</div>
				</div>
			</div>
		</div>
        <?php

    // ob_start();
    // echo do_shortcode('[pt_music_album_shortcode id="'. $settings['item'] .'"]');

    // echo ob_get_clean();
  }
}