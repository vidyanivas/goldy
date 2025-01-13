<?php

// Element Description: PT Gallery (external link)

class PT_Gallery_External_Link extends WPBakeryShortCode {

  public static $g_array = array(
    'index' => -1,
    'paged' => 1,
    'count' => 0,
    'col' => 0,
  );

  // Element Init
  public function __construct() {
    add_action('init', array($this, 'pt_gallery_external_link_mapping'));
    add_shortcode('pt_gallery_external_link', array($this, 'pt_gallery_external_link_html'));
    add_shortcode('pt_gallery_external_link_item', array($this, 'pt_gallery_external_link_item_html'));
    
    //add_action('wp_ajax_loadmore_portfolio', array($this, 'loadmore'));
    //add_action('wp_ajax_nopriv_loadmore_portfolio', array($this, 'loadmore'));
  }

  public static function get_all_portfolio_category() {
    $taxonomy = 'pt-portfolio-category';
    $args = array(
      'hide_empty' => true,
    );

    $terms = get_terms($taxonomy, $args);
    $result = array();
    $result[0] = "";

    if (!empty($terms) && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        if (get_category_parents($term->term_id)) {
          $name = get_category_parents($term->term_id);
        } else {
          $name = $term->name;
        }
        $name = trim($name, '/');
        $result['ID [' . $term->term_id . '] ' . $name] = $term->term_id;
      }
    }

    return $result;
  }

  // Element Mapping
  public function pt_gallery_external_link_mapping() {

    // Stop all if VC is not enabled
    if (!defined('WPB_VC_VERSION')) {
      return;
    }

    // Map the block with vc_map()
    vc_map(array(
      "name" => esc_html__("Gallery (external link)", "novo"),
      "base" => "pt_gallery_external_link",
      "as_parent" => array('only' => 'pt_gallery_external_link_item'),
      "show_settings_on_create" => true,
      "icon" => "shortcode-icon-gallery",
      "is_container" => true,
      "category" => esc_html__("Novo Shortcodes", "novo"),
      "params" => array(
        yprm_vc_uniqid(),
        /* array(
          "type" => "number",
          "heading" => esc_html__("Count items", "novo"),
          "param_name" => "count_items",
          "value" => '9',
          "admin_label" => true,
        ), */
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Type", "novo"),
          "param_name" => "type",
          "admin_label" => true,
          "value" => array(
            esc_html__("Grid", "novo") => "grid",
            esc_html__("Masonry", "novo") => "masonry",
            esc_html__("Flow", "novo") => "flow",
            esc_html__("Horizontal", "novo") => "horizontal",
            esc_html__("Carousel", "novo") => "carousel",
            esc_html__("Carousel Type 2", "novo") => "carousel-type2",
            esc_html__("Scattered", "novo") => "scattered",
          ),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Colums", "novo"),
          "param_name" => "cols",
          "admin_label" => true,
          "value" => array(
            esc_html__("Col 1", "novo") => "1",
            esc_html__("Col 2", "novo") => "2",
            esc_html__("Col 3", "novo") => "3",
            esc_html__("Col 4", "novo") => "4",
          ),
          "std" => '3',
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry")),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Hover animation", "novo"),
          "param_name" => "hover",
          "admin_label" => true,
          "value" => array(
            esc_html__("Content always is shown", "novo") => "none",
            esc_html__("Gallery", "novo") => "gallery",
            esc_html__("Type 1", "novo") => "type_1",
            esc_html__("Type 2", "novo") => "type_2",
            esc_html__("Type 3", "novo") => "type_3",
            esc_html__("Type 4", "novo") => "type_4",
            esc_html__("Type 5", "novo") => "type_5",
            esc_html__("Type 6", "novo") => "type_6",
            esc_html__("Type 7", "novo") => "type_7",
            esc_html__("Type 8", "novo") => "type_8",
            esc_html__("Type 9", "novo") => "type_9",
          ),
          "std" => 'type_1',
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry", "flow", "horizontal")),
          "description" => esc_html__("Type \"Gallery\" only for Grid and Masonry", "pt-addons"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Popup Gallery", "pt-addons"),
          "param_name" => "popup_gallery",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "pt-addons"),
              "off" => esc_html__("Off", "pt-addons"),
            ),
          ),
          "default_set" => false,
          "dependency" => Array("element" => "hover", "value" => "gallery" ),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Image Quality", "pt-addons"),
          "param_name" => "thumb_size",
          "description" => esc_html__("Enter image size (Example: \"thumbnail\", \"medium\", \"large\", \"full\" or other sizes defined by theme).", "pt-addons"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Hover disable", "pt-addons"),
          "param_name" => "hover_disable",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "pt-addons"),
              "off" => esc_html__("Off", "pt-addons"),
            ),
          ),
          "default_set" => false,
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry", "flow", "horizontal")),
        ),
        /* array(
          "type" => "dropdown",
          "heading" => esc_html__("Navigation", "novo"),
          "param_name" => "navigation",
          "value" => array(
            esc_html__("None", "novo") => "none",
            esc_html__("Load More", "novo") => "load_more",
            esc_html__("Load More On Scroll", "novo") => "load_more_on_scroll",
            esc_html__("Pagination", "novo") => "pagination",
          ),
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry", "scattered")),
        ), */
        array(
          "type" => "textfield",
          "heading" => esc_html__("Width", "novo"),
          "param_name" => "width",
          "value" => "600",
          "dependency" => Array("element" => "type", "value" => array("horizontal")),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Gap", "novo"),
          "param_name" => "gap",
          "value" => "on",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry")),
        ),
        array(
          "type" => "number",
          "heading" => esc_html__("Desc Size", "pt-addons"),
          "param_name" => "desc_size",
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Save Aspect Ratio", "novo"),
          "param_name" => "save_aspect_ratio",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => false,
          "dependency" => Array("element" => "type", "value" => array("horizontal")),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Carousel navigation", "novo"),
          "param_name" => "carousel_nav",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "dependency" => Array("element" => "type", "value" => array("horizontal", "carousel")),
          "group" => esc_html__("Slider Settings", "novo"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Infinite loop", "novo"),
          "param_name" => "infinite_loop",
          "value" => "on",
          "options" => array(
            "on" => array(
              "label" => esc_html__("Restart the slider automatically as it passes the last slide.", "novo"),
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "dependency" => Array("element" => "type", "value" => array("horizontal", "carousel")),
          "group" => esc_html__("Slider Settings", "novo"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Mousewheel", "novo"),
          "param_name" => "mousewheel",
          "value" => "on",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "dependency" => Array("element" => "type", "value" => array("carousel-type2", "horizontal")),
          "group" => esc_html__("Slider Settings", "novo"),
        ),
        array(
          "type" => "number",
          "heading" => esc_html__("Transition speed", "novo"),
          "param_name" => "speed",
          "value" => "300",
          "min" => "100",
          "max" => "10000",
          "step" => "100",
          "suffix" => "ms",
          "description" => esc_html__("Speed at which next slide comes.", "novo"),
          "dependency" => Array("element" => "type", "value" => array("horizontal", "carousel")),
          "group" => esc_html__("Slider Settings", "novo"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Autoplay Slides", "novo"),
          "param_name" => "autoplay",
          "value" => "on",
          "options" => array(
            "on" => array(
              "label" => esc_html__("Enable Autoplay", "novo"),
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "dependency" => Array("element" => "type", "value" => array("horizontal", "carousel")),
          "group" => esc_html__("Slider Settings", "novo"),
        ),
        array(
          "type" => "number",
          "heading" => esc_html__("Autoplay Speed", "novo"),
          "param_name" => "autoplay_speed",
          "value" => "5000",
          "min" => "100",
          "max" => "10000",
          "step" => "10",
          "suffix" => "ms",
          "dependency" => Array("element" => "autoplay", "value" => array("on")),
          "group" => esc_html__("Slider Settings", "novo"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Popup mode", "novo"),
          "param_name" => "popup_mode",
          "value" => "on",
          "admin_label" => true,
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry", "flow", "horizontal", "scattered", "carousel",
          "carousel-type2")),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Popup mode title", "pt-addons"),
          "param_name" => "popup_mode_title",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "pt-addons"),
              "off" => esc_html__("Off", "pt-addons"),
            ),
          ),
          "default_set" => false,
          "dependency" => Array("element" => "popup_mode", "value" => "on" ),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Popup mode descripton", "pt-addons"),
          "param_name" => "popup_mode_desc",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "pt-addons"),
              "off" => esc_html__("Off", "pt-addons"),
            ),
          ),
          "default_set" => false,
          "dependency" => Array("element" => "popup_mode", "value" => "on" ),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Project link", "novo"),
          "param_name" => "project_link",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "dependency" => Array("element" => "popup_mode", "value" => array("on")),
          "default_set" => false,
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Heading", "novo"),
          "param_name" => "show_heading",
          "value" => "on",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "group" => esc_html__("Fields", "novo"),
        ),
        array(
          "type" => "switch",
          "heading" => esc_html__("Description", "novo"),
          "param_name" => "show_desc",
          "value" => "on",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "group" => esc_html__("Fields", "novo"),
        ),
      ),
      "js_view" => 'VcColumnView',
    ));

    vc_map(array(
      "name" => esc_html__("Gallery item", "novo"),
      "base" => "pt_gallery_external_link_item",
      "content_element" => true,
      "show_settings_on_create" => true,
      "icon" => "shortcode-icon-gallery",
      "as_child" => array('only' => 'pt_gallery_external_link'),
      "params" => array(
        yprm_vc_uniqid(),
        array(
          "type" => "attach_image",
          "heading" => esc_html__("Image", "pt-addons"),
          "param_name" => "image",
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Title", "pt-addons"),
          "param_name" => "title",
          "admin_label" => true,
        ),
        array(
          "type" => "textarea",
          "heading" => esc_html__("Description", "pt-addons"),
          "param_name" => "desc",
        ),
        array(
          "type" => "vc_link",
          "heading" => esc_html__("Link", "pt-addons"),
          "param_name" => "link",
        ),
      ),
    ));
  }

  // Element HTML
  public function pt_gallery_external_link_html($atts, $content = null) {

    // Params extraction
    extract(
      $this->settings = $atts = shortcode_atts(
          array(
            'uniqid' => uniqid(),
            //'count_items' => '9',
            'type' => 'grid',
            'cols' => '3',
            'hover' => 'type_1',
            'hover_disable' => 'off',
            'popup_gallery' => 'on',
            'popup_mode' => 'on',
            'popup_mode_title' => 'off',
            'popup_mode_desc' => 'off',
            'project_link' => 'off',
            //'filter_buttons' => 'on',
            //'filter_buttons_align' => 'tal',
            'desc_size' => '45',
            'gap' => 'on',
            'thumb_size' => 'large',
            //'navigation' => 'none',
            'show_heading' => 'on',
            'show_desc' => 'on',
            'show_categories' => 'on',
            'orderby' => 'post__in',
            'order' => 'ASC',
            //'source' => '',
            //'items' => '',
            //'categories' => '',
            'save_aspect_ratio' => 'off',
            'carousel_nav' => 'off',
            'infinite_loop' => 'on',
            'mousewheel' => 'on',
            'speed' => '300',
            'autoplay' => 'on',
            'autoplay_speed' => '5000',
            'width' => '600',
          ),
          $atts
        )
    );

    $this->content = $content;

    $projects_array = $this->get_items_array();

    if (empty($projects_array)) {
      return false;
    }

    $this->set_css_classes();

    if(
      $type == 'flow' ||
      $type == 'horizontal' ||
      $type == 'carousel' ||
      $type == 'carousel-type2'
    ) {
      if ($infinite_loop == 'on') {
        $this->settings['infinite_loop'] = 'true';
      } else {
        $this->settings['infinite_loop'] = 'false';
      }
      if ($autoplay == 'on') {
        $this->settings['autoplay'] = 'true';
      } else {
        $this->settings['autoplay'] = 'false';
      }
      if ($carousel_nav == 'on') {
        $this->settings['arrows'] = 'true';
      } else {
        $this->settings['arrows'] = 'false';
      }
    }

    if ($type == 'flow') {
      $this->js_flow();
    } elseif ($type == 'horizontal') {
      $this->js_horizontal();
    } elseif ($type == 'carousel') { 
      $this->js_carousel();
    } elseif ($type == 'carousel-type2') {
      $this->js_carousel_type2();
    }

    ob_start(); ?>

		<div class="<?php echo implode(' ', $this->css_class['wrapper']) ?>" data-popup-settings="<?php echo esc_attr(json_encode($this->popup_atts)) ?>">
			<?php if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered' || $this->settings['type'] == 'carousel'): ?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<?php $this->render_items()?>
				</div>
				<?php //echo $this->get_navigation_html() ?>
			<?php elseif ($this->settings['type'] == 'horizontal'): ?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<?php $this->render_items()?>
				</div>
			<?php elseif ($this->settings['type'] == 'flow'): ?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<ul><?php $this->render_items()?></ul>
				</div>
			<?php elseif ($this->settings['type'] == 'carousel-type2'): ?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php $this->render_items()?>
						</div>
					</div>
				</div>
			<?php endif;?>
		</div>
    <?php return ob_get_clean();
    wp_reset_postdata();
  }

  public function pt_gallery_external_link_item_html($atts, $content = null) {
    $items_array = $this->get_items_array();

    if(count($items_array) < 1) return false;

    foreach ($items_array as $key => $project) {
      $this->set_project_object($project, $key);

      if ($this->settings['type'] == 'scattered') {
        echo $this->render_project_scattered($project, $key);
      } else if ($this->settings['type'] == 'carousel') {
        echo $this->render_project_carousel($project, $key);
      } else if ($this->settings['type'] == 'flow') {
        echo $this->render_project_flow($project, $key);
      } else if ($this->settings['type'] == 'carousel-type2') {
        echo $this->render_project_carousel_type2($project, $key);
      } else if ($this->settings['type'] == 'horizontal') {
        echo $this->render_project_horizontal($project, $key);
      } else {
        echo $this->render_project_grid($project, $key);
      }
    }
  }
  
  protected function set_css_classes() {

    $this->wrap_id = 'portfolio-block-'.$this->settings['uniqid'];

    $this->css_class['wrapper'] = [];
    $this->css_class['inner-wrapper'] = [];
    $this->popup_atts = [];

    $this->popup_atts['popupTitle'] = $this->settings['popup_mode_title'] == 'on' ? true : false;
    $this->popup_atts['popupDesc'] = $this->settings['popup_mode_desc'] == 'on' ? true : false;

    $this->css_class['wrapper'][] = 'portfolio-block '.$this->wrap_id;

    if ($this->settings['popup_mode'] == 'on' && $this->settings['hover'] != 'gallery') {
      $this->css_class['wrapper'][] = 'popup-gallery';
    }

    if ($this->settings['hover'] == 'gallery' && $this->settings['popup_gallery'] == 'off') {
      $this->css_class['wrapper'][] = 'popup-gallery';
    }

    if ($this->settings['type'] !== 'carousel-type2') {
      $this->css_class['inner-wrapper'][] = 'portfolio-items';
      $this->css_class['inner-wrapper'][] = 'portfolio-type-' . $this->settings['type'] . ' load-wrap';
    } else {
      $this->css_class['inner-wrapper'][] = 'portfolio-items portfolio-type-carousel2';
    }

    if ($this->settings['type'] == 'scattered') {
      $this->css_class['inner-wrapper'][] = 'portfolio_hover_type_1';
    } elseif ($this->settings['hover']) {
      $this->css_class['inner-wrapper'][] = 'portfolio_hover_' . $this->settings['hover'];
    }

    if ($this->settings['hover_disable'] == 'on') {
      $this->css_class['inner-wrapper'][] = 'hover-disable';
    }

    if ($this->settings['gap'] != 'on') {
      $this->css_class['inner-wrapper'][] = 'gap-off';
    }

    if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered' || $this->settings['type'] == 'carousel') {
      $this->css_class['inner-wrapper'][] = 'row';
    }
  }

  protected function get_items_array() {
    $items = [];
    yprm_the_shortcodes($items, $this->content);

    return $items;
  }

  protected function get_loadmore_array() {
    $args = array(
      'post__in' => $this->get_source_items(),
      'posts_per_page' => -1,
      'paged' => $this->paged,
      'orderby' => $this->settings['orderby'],
      'order' => $this->settings['order'],
      'post_type' => 'pt-portfolio',
      'post_status' => 'publish',
      'tax_query' => $this->get_source_categories(),
    );

    $array = new \WP_Query($args);
    $loadmore_array = array();

    if (is_object($array) && count($array->posts) > 0) {
      foreach ($array->posts as $key => $item) {
        $loadmore_array[$key] = array(
          'id' => $item->ID,
        );

        foreach (wp_get_post_terms($item->ID, 'pt-portfolio-category') as $s_item) {
          $loadmore_array[$key]['cat'][] = $s_item->term_id;
        }
      }
    }

    return $loadmore_array;
  }

  protected function get_navigation_html() {
    $max_num_pages = $this->get_items_array()->max_num_pages;
    $loadmore_array = array_slice($this->get_loadmore_array(), $this->settings['count_items']);
    $loadmore_array = json_encode($loadmore_array);

    if ($this->settings['navigation'] == "pagination") {
      if (function_exists('yprm_wp_corenavi')) {
        echo yprm_wp_corenavi($max_num_pages);
      } else {
        wp_link_pages();
      }
    }
    if ($loadmore_array && ($this->settings['navigation'] == "load_more" || $this->settings['navigation'] == 'load_more_on_scroll') && $max_num_pages > 1) { ?>
			<div class="load-button tac">
				<a href="#" data-array="<?php echo esc_attr($loadmore_array); ?>" data-count="<?php echo $this->settings['count_items'] ?>" data-atts="<?php echo esc_attr(json_encode($this->settings)) ?>" class="button-style2 loadmore-button <?php echo esc_attr($this->settings['navigation']); ?>">
					<span><?php echo yprm_get_theme_setting('tr_load_more'); ?></span>
				</a>
			</div>
		<?php }
  }

  protected function set_project_object($atts, $index) {
    $this->project = (object) [
      'index' => $index,
      'title' => $atts['title'],
      'desc' => $atts['desc'],
      'get_image_original' => yprm_get_image($atts['image'], 'array', 'full'),
      'get_image_html' => yprm_get_image($atts['image'], 'img', $this->settings['thumb_size']),
      'get_image_bg' => yprm_get_image($atts['image'], 'bg', $this->settings['thumb_size']),
      'get_image_array' => yprm_get_image($atts['image'], 'array', $this->settings['thumb_size']),
    ];

    $link = yprm_vc_link($atts['link'], '_self');
    
    if($this->settings['popup_mode'] != 'on') {
      if($link) {
        $this->project->get_link_html = '<a class="link" href="'.esc_url($link['url']).'" target="'.esc_attr($link['target']).'"></a>';
      }
		} else {
      $popup_array = [];
      $popup_array['title'] = $this->project->title;
      $popup_array['desc'] = $this->get_short_description(yprm_get_theme_setting('popup_desc_size'));
      $popup_array['image'] = [
        'url' => $this->project->get_image_original[0],
        'w' => $this->project->get_image_original[1],
        'h' => $this->project->get_image_original[2]
      ];
      if($link) {
        $popup_array['projectLink'] = esc_url($link['url']);
      }

      $this->project->get_link_html = '<a class="link" href="#" data-popup-json="'.esc_attr(json_encode($popup_array)).'" data-id="'.esc_attr($index).'"></a>';
		}
  }

  protected function get_short_description($count = 140) {
    if(!$count) $count = 140;

    if(function_exists('mb_strimwidth') && $this->project->desc) {
      return mb_strimwidth($this->project->desc, 0, (int) $count);
    }

    return false;
  }

  protected function set_project_css($index, $post) {
    $block_setting_key = 'project-index-'.$index;

    switch ($this->settings['cols']) {
      case '1':
        $cols = "col-12";
        break;
      case '2':
        $cols = "col-12 col-sm-6 col-md-6";
        break;
      case '3':
        $cols = "col-12 col-sm-4 col-md-4";
        break;
      case '4':
        $cols = "col-12 col-sm-4 col-md-3";
        break;
  
      default:
        $cols = "";
        break;
      }

    $this->css_class[$block_setting_key] = [];

    if ($this->settings['type'] !== 'carousel-type2') {
      $this->css_class[$block_setting_key][] = 'portfolio-item';
    } else {
      $this->css_class[$block_setting_key][] = 'swiper-slide';
    }

    if ($this->settings['type'] == 'grid' || $this->settings['type'] == 'masonry' || $this->settings['type'] == 'scattered') {
      $this->css_class[$block_setting_key][] = $cols;

      if($this->settings['hover'] == 'gallery') {
        $this->css_class[$block_setting_key][] = 'popup-gallery';
      }
    }

    if ($this->settings['type'] == 'horizontal' || $this->settings['type'] == 'carousel') {
      $this->css_class[$block_setting_key][] = 'item';
    }
    $this->css_class[$block_setting_key][] = 'portfolio-' . $this->settings['type'] . '-item';

    if ($this->settings['popup_mode'] == 'on') {
      $this->css_class[$block_setting_key][] = 'popup-item';
    }

    return $block_setting_key;
  }

  protected function render_items() {
    $projects_array = $this->get_items_array();
    $index = 0;

    foreach ($projects_array as $project) {
      $this->set_project_object($project, $index);

      if ($this->settings['type'] == 'scattered') {
        echo $this->render_project_scattered($project, $index);
      } else if ($this->settings['type'] == 'carousel') {
        echo $this->render_project_carousel($project, $index);
      } else if ($this->settings['type'] == 'flow') {
        echo $this->render_project_flow($project, $index);
      } else if ($this->settings['type'] == 'carousel-type2') {
        echo $this->render_project_carousel_type2($project, $index);
      } else if ($this->settings['type'] == 'horizontal') {
        echo $this->render_project_horizontal($project, $index);
      } else {
        echo $this->render_project_grid($project, $index);
      }

      $index++;
    }
  }

  protected function project_title($num) {
    if ($this->settings['show_heading'] !== 'on') {
      return false;
    }

    if ($this->settings['hover'] == 'type_5'): ?>
			<h5><span><?php echo esc_html($num+1); ?></span><?php echo esc_html($this->project->title); ?></h5>
		<?php else: ?>
			<h5><?php echo esc_html($this->project->title); ?></h5>
		<?php endif;
  }

  protected function project_desc() {
    if ($this->settings['show_desc'] !== 'on') {
      return false;
    }

    echo wpautop($this->get_short_description($this->settings['desc_size']));
  }

  protected function render_project_flow($atts, $index) {
    
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<li class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>"><?php echo $this->project->get_image_html ?></div>
			<div class="content">
				<?php echo $this->project_title($index); ?>
				<?php echo $this->project_desc(); ?>
			</div>
			<?php echo $this->project->get_link_html ?>
		</li>
		<?php return ob_get_clean();
  }

  protected function render_project_grid($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start(); ?>
		<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="wrap">
				<?php if ($this->settings['type'] == 'masonry') {?>
					<div class="a-img">
            <?php echo $this->project->get_image_html ?>
          </div>
				<?php } else {?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg ?>"></div>
					</div>
				<?php }?>
				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
        <?php echo $this->project->get_link_html  ?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function render_project_horizontal($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="wrap">
				<?php if ($this->settings['save_aspect_ratio'] == 'on'): ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<img width="<?php echo $this->project->get_image_array[1]; ?>" height="<?php echo $this->project->get_image_array[2]; ?>" src="<?php echo $this->project->get_image_array[0]; ?>" alt="<?php echo $this->project->title; ?>">
          </div>
				<?php else: ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg ?>"></div>
					</div>
				<?php endif;?>

				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php echo $this->project->get_link_html ?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function render_project_scattered($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start()?>
			<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
				<div class="wrap">
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original[0]); ?>">
						<?php echo $this->project->get_image_html; ?>
					</div>
					<div class="content">
						<?php echo $this->project_title($index); ?>
						<?php echo $this->project_desc(); ?>
					</div>
					<?php echo $this->project->get_link_html  ?>
				</div>
			</article>
		<?php return ob_get_clean();
  }

  protected function render_project_carousel($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ?>
		<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="wrap">
				<div class="a-img">
					<div style="<?php echo $this->project->get_image_bg; ?>">
            <?php echo $this->project->get_link_html ?>
					</div>
				</div>
				<div class="bottom-content">
					<?php echo $this->project_title($index); ?>
				</div>
			</div>
		</article>
  <?php }

  protected function render_project_carousel_type2($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ?>
		<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="wrap">
				<?php echo $this->project->get_image_html; ?>
				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php echo $this->project->get_link_html ?>
			</div>
		</article>
  <?php }

  public function js_flow() {
    wp_enqueue_style('flipster', get_template_directory_uri() . '/css/jquery.flipster.css');
    wp_enqueue_script('flipster', get_template_directory_uri() . '/js/jquery.flipster.min.js', array('jquery'), '', true);
    wp_enqueue_script('novo-script', get_template_directory_uri() . '/js/scripts.js');
    wp_add_inline_script('novo-script', "jQuery(document).ready(function(jQuery) {
      jQuery('." . $this->wrap_id . "').flipster({
        style: 'carousel',
        loop: true,
        start: 2,
        spacing: -0.5,
        nav: false,
        buttons: true,
      });
    });");
  }
   
  public function js_horizontal() {
    wp_enqueue_style('owl-carousel');
    wp_enqueue_script('owl-carousel');
    wp_enqueue_script('novo-script', get_template_directory_uri() . '/js/scripts.js');
    wp_add_inline_script('novo-script', "jQuery(document).ready(function(jQuery) {
      jQuery('." . $this->wrap_id . " .portfolio-items').each(function(){
        var head_slider = jQuery(this);
        if(head_slider.find('.item').length > 1){
          head_slider.imagesLoaded( function() {
            head_slider.addClass('owl-carousel').owlCarousel({
              loop:true,
              items:1,
              center: true,
              autoWidth: true,
              nav: " . esc_js($this->settings['arrows']) . ",
              dots: false,
              autoplay: " . esc_js($this->settings['autoplay']) . ",
              autoplayTimeout: " . esc_js($this->settings['autoplay_speed']) . ",
              autoplayHoverPause: true,
              smartSpeed: " . esc_js($this->settings['speed']) . ",
              navClass: ['owl-prev basic-ui-icon-left-arrow','owl-next basic-ui-icon-right-arrow'],
              navText: false,
              margin: 30,
              responsive:{
                0:{
                  nav: false,
                },
                768:{
                  nav: " . esc_js($this->settings['arrows']) . ",
                },
              },
            });
            ".(($this->settings['mousewheel'] == 'on') ? 'head_slider.on(\'mousewheel\', \'.owl-stage\', function (e) {
              var delta = e.originalEvent.deltaY;

              if (delta>0) {
                  head_slider.trigger(\'next.owl\');
              } else {
                  head_slider.trigger(\'prev.owl\');
              }
              e.preventDefault();
            });' : '')."
          });
        }
      });
    });");
  }
   
  public function js_carousel() {
    wp_enqueue_style('owl-carousel');
    wp_enqueue_script('owl-carousel');
    wp_enqueue_script('novo-script', get_template_directory_uri() . '/js/scripts.js');
    wp_add_inline_script('novo-script', "jQuery(document).ready(function(jQuery) {
      jQuery('." . $this->wrap_id . " .portfolio-items').each(function(){
        var head_slider = jQuery(this);
        if(head_slider.find('.item').length > 1){
          head_slider.imagesLoaded( function() {
            head_slider.addClass('owl-carousel').owlCarousel({
              loop:true,
              items:1,
              center: true,
              autoWidth: true,
              nav: false,
              dots: " . esc_js($this->settings['arrows']) . ",
              autoplay: " . esc_js($this->settings['autoplay']) . ",
              autoplayTimeout: " . esc_js($this->settings['autoplay_speed']) . ",
              autoplayHoverPause: true,
              smartSpeed: " . esc_js($this->settings['speed']) . ",
              navText: false,
              margin: 30,
              responsive:{
                0:{
                  nav: false,
                },
                768:{
                  nav: " . esc_js($this->settings['arrows']) . ",
                },
              },
            });
          });
        }
      });
    });");
  }
   
  public function js_carousel_type2() {
    wp_enqueue_script('novo-script', get_template_directory_uri() . '/js/scripts.js');
    wp_add_inline_script('novo-script', "jQuery(document).ready(function(jQuery) {
      var \$portfolio_carousel = jQuery('.$this->wrap_id'),
          \$swiper_element = \$portfolio_carousel.find('.swiper').get(0); 
  
      if (\$swiper_element) { 
          var \$portfolio_carousel_swiper = new Swiper(\$swiper_element, {
              slidesPerView: 'auto',
              spaceBetween: 30,
              breakpoints: {
                  576: {
                      autoHeight: true
                  }
              },
              ".(($this->settings['mousewheel'] == 'on') ? 'mousewheel: {},' : '')."
          });
      } else {
          console.error('Swiper element not found for $this->wrap_id');
      }
  });");
  

    wp_enqueue_script('swiper');
    wp_enqueue_style('swiper');
  }
   

  public function loadmore() {
    $this->settings = $_POST['atts'];
    $array = $_POST['array'];
    $start_index = $_POST['start_index'];

    if (is_array($array) && count($array) > 0) {
      foreach ($array as $item) {
        if (!isset($item['id']) || empty($item['id'])) {
          continue;
        }

        $project = get_post($item['id']);

        if (!$project) {
          continue;
        }

        $this->set_project_object($project, $start_index++);

        if ($this->settings['type'] == 'scattered') {
          echo $this->render_project_scattered($project, $start_index);
        } else if ($this->settings['type'] == 'carousel') {
          echo $this->render_project_carousel($project, $start_index);
        } else if ($this->settings['type'] == 'flow') {
          echo $this->render_project_flow($project, $start_index);
        } else if ($this->settings['type'] == 'carousel-type2') {
          echo $this->render_project_carousel_type2($project, $start_index);
        } else {
          echo $this->render_project_grid($project, $start_index);
        }
      }
    } else {
      echo array(
        'return' => 'error',
      );
    }
  }

}

new PT_Gallery_External_Link();
if (class_exists('WPBakeryShortCodesContainer')) {
  class WPBakeryShortCode_PT_Gallery_External_Link extends WPBakeryShortCodesContainer {
  }
}
if (class_exists('WPBakeryShortCode')) {
  class WPBakeryShortCode_PT_Gallery_External_Link_Item extends WPBakeryShortCode {
  }
}
