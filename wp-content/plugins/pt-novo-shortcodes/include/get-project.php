<?php 

if (!defined('ABSPATH')) {
	exit;
}

class YPRM_Get_Project {
	private $atts;
	private $id;
	private $thumb_id;
	private $post_object;
	private $gallery_array;

	public function __construct($atts = array()) {
		$this->atts = shortcode_atts(array(
			'id' => 0,
			'index' => '',
			'thumb_size' => '',
			'popup_mode' => 'yes',
      'project_link_target' => '_self'
		), $atts);

		$this->id = (int) $this->atts['id'];
		$this->post_object = get_post($this->id);
		$this->thumb_id = (int) get_post_meta($this->id, '_thumbnail_id', true);
		$this->gallery_array = $this->yprm_get_gallery_images($this->id);
	}

	public function yprm_get_gallery_images( $post_id = 0 ) {
		if ( 0 === $post_id && null === ( $post_id = $this->id ) ) {
			return array();
		}

		return get_post_meta( $this->id, 'pt_gallery', true );
	}

	public function has_thumb() {
		return !empty($this->thumb_id);
	}

	public function has_gallery() {
		return !empty($this->gallery_array);
	}

	public function has_video() {
		return $this->get_video_player();
	}

	public function get_id() {
		return $this->id;
	}

	public function get_thumb_id() {
		return $this->thumb_id;
	}

	public function get_image_bg() {
		return yprm_get_image($this->thumb_id, 'bg', $this->atts['thumb_size']);
	}

	public function get_image_html() {
		return yprm_get_image($this->thumb_id, 'img', $this->atts['thumb_size']);
	}

	public function get_image_array() {
		return yprm_get_image($this->thumb_id, 'array', $this->atts['thumb_size']);
	}

	public function get_image_original() {
		return yprm_get_image($this->thumb_id, 'array', 'full');
	}

	public function get_gallery_array() {
		return $this->gallery_array;
	}

	public function get_title() {
		return $this->post_object->post_title;
	}

	public function get_date($format = '') {
		if(!$format) get_option( 'date_format' );

		return get_the_date($format, $this->post_object);
	}

	public function get_description() {
		return strip_tags(strip_shortcodes($this->post_object->post_content));
	}

	public function get_short_description($width = 140) {
		if (function_exists('get_field')) {
			$field_value = get_field('short_desc', $this->id);
	
			if ($field_value !== false && $field_value !== null) {
				$field_value = strip_tags(strip_shortcodes($field_value));
				$short_desc = $field_value;
			} else {
				$short_desc = $this->get_description();
			}
		} else {
			$short_desc = $this->get_description();
		}
	
		if (!$width) {
			$width = 140;
		}
	
		if ($short_desc && function_exists('mb_strimwidth')) {
			return mb_strimwidth($short_desc, 0, $width, '...');
		}
	
		return false;
	}

	public function get_categories_array() {
		$categories_array = array();

		if (is_array($cat_array = get_the_terms($this->id, 'pt-portfolio-category'))) {
			foreach($cat_array as $category_item) {
				$categories_array[] = array(
					'id' => (int) $category_item->term_id,
					'title' => $category_item->name,
					'css_class' => 'category-' . $category_item->term_id,
					'permalink' => get_term_link((int) $category_item->term_id)
				);
			}

			return is_array($categories_array) && count($categories_array) > 0 ? $categories_array : false;
		}

		return false;
	}

	public function get_categories_ids() {
		return array_column($this->get_categories_array(), 'id');
	}

	public function get_categories_css() {
		if ( ! $this->get_categories_array() ) return;

		return implode(' ', array_column( $this->get_categories_array(), 'css_class' ) );
	}

	public function get_categories_string($separator = ', ') {
		if(!$this->get_categories_array()) return;
		
		return implode($separator, array_column($this->get_categories_array(), 'title'));
	}

	public function get_video_player() {
		$video = false;

		if(class_exists('VideoUrlParser') && function_exists('get_field') && $video_url = get_field('video_url', $this->id)) {
      $video = VideoUrlParser::get_player($video_url);
    }

		return $video;
	}

	public function get_video_attr_value() {
		return '<div class="wrapper"><div class="video-wrapper">'.$this->get_video_player().'</div></div>';
	}
 
	public function get_permalink() {
		if(function_exists('get_field') && $external_link = get_field('external_link', $this->get_id())) {
			return esc_url($external_link);
		} else {
			return esc_url(get_permalink($this->id));
		}
	}

  public function get_popup_link_atts() {
    $popup_array = [];
    $original_image_array = $this->get_image_original();
    $likes = get_post_meta($this->get_id(), '_zilla_likes', true) ? get_post_meta($this->get_id(), '_zilla_likes', true) : 0;

    $popup_array['title'] = $this->get_title();
    $popup_array['desc'] = $this->get_short_description(yprm_get_theme_setting('popup_desc_size'));
    
    if($this->has_video()) {
      $popup_array['video'] = [
        'html' => $this->get_video_player(),
        'w' => 1920,
        'h' => 1080
      ];
    } else if($original_image_array) {
      $popup_array['image'] = [
        'url' => $original_image_array[0],
        'w' => $original_image_array[1],
        'h' => $original_image_array[2]
      ];
    }
    $popup_array['post_id'] = $this->get_id();
    $popup_array['likes'] = $likes;
    $popup_array['projectLink'] = $this->get_permalink();
    $popup_array['projectLinkTarget'] = $this->atts['project_link_target'];

    return json_encode($popup_array);
  }

	public function get_link_atts_array() {
		$atts = array();
  

		if($this->atts['popup_mode'] != 'yes' && $this->atts['popup_mode'] != 'on') {
			$atts['href'] = $this->get_permalink();
      $atts['target'] = $this->atts['project_link_target'];
		} else {
			$atts['href'] = '#';
      $atts['data-popup-json'] = $this->get_popup_link_atts();
			$atts['data-id'] = $this->atts['index'];
		}

		return $atts;
	}

	public function get_link_atts($additional_atts = array()) {
		$atts_array = array_merge($this->get_link_atts_array(), $additional_atts);
		$atts = '';

		foreach($atts_array as $key => $value) {
			$atts .= $key.'="'.esc_attr($value).'" ';
		}

		return $atts;
	}

	public function get_link_html() {
		echo '<a class="link" '.$this->get_link_atts().'></a>';
	}
}