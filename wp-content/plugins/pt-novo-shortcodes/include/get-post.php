<?php 

if (!defined('ABSPATH')) {
	exit;
}

class YPRM_Get_Post {
	private $atts;
	private $id;
	private $thumb_id;
	private $post_object;

	public function __construct($atts = array()) {
		$this->atts = shortcode_atts(array(
			'id' => 0,
			'thumb_size' => '',
		), $atts);

		$this->id = (int) $this->atts['id'];
		$this->post_object = get_post($this->atts['id']);
		$this->thumb_id = (int) get_post_meta($this->atts['id'], '_thumbnail_id', true);
	}

	public function has_thumb() {
		return !empty($this->thumb_id);
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

	public function get_short_description($width = 130) {
		if (function_exists('get_field')) {
			$field_value = get_field('short_desc', $this->id);
	
			if ($field_value !== false) {
				// Check if $field_value is a non-null string before using strip_tags
				$field_value = is_string($field_value) ? strip_tags(strip_shortcodes($field_value)) : '';
				$short_desc = $field_value;
			} else {
				$short_desc = $this->get_description();
			}
	
			if ($short_desc && function_exists('mb_strimwidth')) {
				return mb_strimwidth($short_desc, 0, $width, '...');
			}
		}
	
		return false;
	}
	

	public function get_categories_array() {
		$categories_array = array();

		if (is_array($cat_array = get_the_terms($this->id, 'category'))) {
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
		if(!$this->get_categories_array()) return;

		return implode(' ', array_column($this->get_categories_array(), 'css_class'));
	}

	public function get_categories_string($separator = ', ') {
		if(!$this->get_categories_array()) return;
		
		return implode($separator, array_column($this->get_categories_array(), 'title'));
	}
 
	public function get_permalink() {
		return esc_url(get_permalink($this->id));
	}

	public function get_author() {
		return get_the_author_meta('display_name', $this->post_object->post_author);
	}

	public function get_avatar_url() {
		return get_avatar_url($this->post_object->post_author);
	}
}