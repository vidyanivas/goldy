<?php

// Element Description: PT Portfolio
#[AllowDynamicProperties]

class PT_Portfolio_Items extends WPBakeryShortCode {

  protected $settings;
  protected $wrap_id;
  protected $paged;
  protected $project;
  protected $displacement_url;

  // Element Init
  public function __construct() {
    add_action('init', array($this, 'pt_portfolio_mapping'));
    add_shortcode('pt_portfolio', array($this, 'pt_portfolio_html'));

    add_action('wp_ajax_loadmore_portfolio', array($this, 'loadmore'));
    add_action('wp_ajax_nopriv_loadmore_portfolio', array($this, 'loadmore'));

    $this->displacement_url = YPRM_PLUGINS_HTTP . '/assets/imgs/pattern4.jpg';
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

  public static function get_all_portfolio_items($param = 'All') {
    $result = array();

    $args = array(
      'post_type' => 'pt-portfolio',
      'post_status' => 'publish',
      'posts_per_page' => '10000',
    );

    $porfolio_array = new WP_Query($args);
    $result[0] = "";

    if (is_array($porfolio_array->posts) && !empty($porfolio_array->posts)) {
      foreach ($porfolio_array->posts as $item) {
        $result['ID [' . $item->ID . '] ' . $item->post_title] = $item->ID;
      }
    }

    return $result;
  }

  // Element Mapping
  public function pt_portfolio_mapping() {

    // Stop all if VC is not enabled
    if (!defined('WPB_VC_VERSION')) {
      return;
    }

    // Map the block with vc_map()
    vc_map(array(
      "name" => esc_html__("Portfolio", "novo"),
      "base" => "pt_portfolio",
      "show_settings_on_create" => true,
      "icon" => "shortcode-icon-portfolio",
      "is_container" => true,
      "category" => esc_html__("Novo Shortcodes", "novo"),
      "params" => array(
        yprm_vc_uniqid(),
        array(
          "type" => "number",
          "heading" => esc_html__("Count items", "novo"),
          "param_name" => "count_items",
          "value" => '9',
          "admin_label" => true,
        ),
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
            esc_html__("Col 6", "novo") => "6",
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
        array(
          "type" => "switch",
          "heading" => esc_html__("Filter buttons", "novo"),
          "param_name" => "filter_buttons",
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
          "type" => "dropdown",
          "heading" => esc_html__("Filter buttons align", "novo"),
          "param_name" => "filter_buttons_align",
          "value" => array(
            esc_html__('Left', 'novo') => 'tal',
            esc_html__('Center', 'novo') => 'tac',
            esc_html__('Right', 'novo') => 'tar',
          ),
          "dependency" => Array("element" => "type", "value" => array("grid", "masonry")),
        ),
        array(
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
        ),
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
          "heading" => esc_html__("Open Project in New Tab", "pt-addons"),
          "param_name" => "project_link_target",
          "value" => "off",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "pt-addons"),
              "off" => esc_html__("Off", "pt-addons"),
            ),
          ),
          "default_set" => false,
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
        array(
          "type" => "switch",
          "heading" => esc_html__("Categories", "novo"),
          "param_name" => "show_categories",
          "value" => "on",
          "options" => array(
            "on" => array(
              "on" => esc_html__("On", "novo"),
              "off" => esc_html__("Off", "novo"),
            ),
          ),
          "default_set" => true,
          "dependency" => Array("element" => "type", "value" => array("carousel", "carousel-type2")),
          "group" => esc_html__("Fields", "novo"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Order by", "novo"),
          "param_name" => "orderby",
          "value" => array(
            esc_html__('Default', 'novo') => 'post__in',
            esc_html__('Author', 'novo') => 'author',
            esc_html__('Category', 'novo') => 'category',
            esc_html__('Date', 'novo') => 'date',
            esc_html__('ID', 'novo') => 'ID',
            esc_html__('Title', 'novo') => 'title',
          ),
          "group" => esc_html__("Sorting", "novo"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Order", "novo"),
          "param_name" => "order",
          "value" => array(
            esc_html__('Ascending order', 'novo') => 'ASC',
            esc_html__('Descending order', 'novo') => 'DESC',
          ),
          "group" => esc_html__("Sorting", "novo"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Source", "novo"),
          "param_name" => "source",
          "value" => array(
            esc_html__("---", "novo") => "",
            esc_html__("Items", "novo") => "items",
            esc_html__("Categories", "novo") => "categories",
          ),
          "group" => esc_html__("Source", "novo"),
        ),
        array(
          "type" => "dropdown_multi",
          "heading" => esc_html__("Items", "novo"),
          "param_name" => "items",
          "dependency" => Array("element" => "source", "value" => array("items")),
          "value" => PT_Portfolio_Items::get_all_portfolio_items(),
          "group" => esc_html__("Source", "novo"),
        ),
        array(
          "type" => "dropdown_multi",
          "heading" => esc_html__("Category", "novo"),
          "param_name" => "categories",
          "dependency" => Array("element" => "source", "value" => array("categories")),
          "value" => PT_Portfolio_Items::get_all_portfolio_category(),
          "group" => esc_html__("Source", "novo"),
        ),
      ),
    ));
  }

  // Element HTML
  public function pt_portfolio_html($atts, $content = null) {

    // Params extraction
    extract(
      $this->settings = $atts = shortcode_atts(
        array(
          'uniqid' => uniqid(),
          'count_items' => '9',
          'type' => 'grid',
          'cols' => '3',
          'project_link_target' => 'off',
          'hover' => 'type_1',
          'hover_disable' => 'off',
          'popup_gallery' => 'on',
          'popup_mode' => 'on',
          'popup_mode_title' => 'off',
          'popup_mode_desc' => 'off',
          'project_link' => 'off',
          'filter_buttons' => 'on',
          'filter_buttons_align' => 'tal',
          'desc_size' => '45',
          'gap' => 'on',
          'thumb_size' => 'large',
          'navigation' => 'none',
          'show_heading' => 'on',
          'show_desc' => 'on',
          'show_categories' => 'on',
          'orderby' => 'post__in',
          'order' => 'ASC',
          'source' => '',
          'items' => '',
          'categories' => '',
          'source' => '',
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

    if (is_front_page()) {
      $this->paged = (get_query_var('page')) ? get_query_var('page') : 1;
    } else {
      $this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }

    $projects_array = $this->get_items_array()->posts;

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
				<?php $this->get_filter_buttons_html();?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<?php $this->render_items()?>
				</div>
				<?php echo $this->get_navigation_html() ?>
			<?php elseif ($this->settings['type'] == 'horizontal'): ?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<?php $this->render_items()?>
				</div>
			<?php elseif ($this->settings['type'] == 'flow'): ?>
				<div class="<?php echo implode(' ', $this->css_class['inner-wrapper']) ?>">
					<ul><?php $this->render_items()?></ul>
				</div>
			<?php elseif ($this->settings['type'] == 'carousel-type2'): ?>
				<?php $this->get_filter_buttons_html();?>
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

  protected function get_source_categories() {
    if ($this->settings['source'] == 'categories' && $this->settings['categories']) {
      return array(
        array(
          'taxonomy' => 'pt-portfolio-category',
          'field' => 'id',
          'terms' => explode(',', $this->settings['categories']),
        ),
      );
    }

    return array();
  }

  protected function get_source_items() {
    if ($this->settings['source'] == 'items' && $this->settings['items']) {
      return explode(',', $this->settings['items']);
    }

    return false;
  }

  protected function get_items_array() {
    $args = array(
      'post__in' => $this->get_source_items(),
      'posts_per_page' => $this->settings['count_items'],
      'paged' => $this->paged,
      'orderby' => $this->settings['orderby'],
      'order' => $this->settings['order'],
      'post_type' => 'pt-portfolio',
      'post_status' => 'publish',
      'tax_query' => $this->get_source_categories(),
    );

    return new \WP_Query($args);
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

  protected function get_categories_array() {
    $categories_array = array();

    if(
      ($this->settings['navigation'] != 'load_more' && $this->settings['navigation'] != 'load_more_on_scroll' && $this->settings['source'] != 'categories') ||
      ($this->settings['source'] == 'items' && $this->settings['items'])
    ) {
      $array = $this->get_items_array()->posts;

      if(count($array) > 0) {
        foreach($array as $item) {
          $categories = wp_get_post_terms($item->ID, 'pt-portfolio-category');

          if(count($categories) > 0) {
            foreach($categories as $category) {
              if(isset($categories_array[$category->term_id])) continue;

              $categories_array[$category->term_id] = array(
                'id' => $category->term_id,
                'slug' => $category->slug,
                'title' => $category->name
              );
            }
          }
        }
      }
    } elseif(
      $this->settings['source'] == 'categories' && 
      $this->settings['categories']
    ) {
      $categories = explode(',', $this->settings['categories']);
      if(is_array($categories) && count($categories) > 0) {
        foreach($categories as $category_id) {
          $category = get_term($category_id, 'pt-portfolio-category');
          
          if(isset($categories_array[$category->term_id])) continue;

          $categories_array[$category->term_id] = array(
            'id' => $category->term_id,
            'slug' => $category->slug,
            'title' => $category->name
          );
        }
      }
    } else {
      $categories = get_terms('pt-portfolio-category', array(
        'hide_empty' => true,
      ));

      if(count($categories) > 0) {
        foreach($categories as $category) {
          $categories_array[$category->term_id] = array(
            'id' => $category->term_id,
            'slug' => $category->slug,
            'title' => $category->name
          );
        }
      }
    }

    return $categories_array;
  }

  protected function get_filter_buttons_html() {

    if (in_array($this->settings['type'], ['flow', 'horizontal', 'carousel', 'shift', 'carousel-type2', 'scattered'])) {
      return;
    }

    if ($this->settings['filter_buttons'] != 'on') return false;

    $this->css_class['filter_buttons_block'][] = 'filter-buttons filter-button-group '.$this->settings['filter_buttons_align'];

    $categories_array = $this->get_categories_array();

    if (count($categories_array) < 2) {
      return false;
    }

    ob_start();?>
		<div class="<?php echo implode(' ', $this->css_class['filter_buttons_block']) ?>">
			<div class="wrap">
				<button class="button active" data-filter="*">
					<span><?php echo yprm_get_theme_setting('tr_all') ?></span>
				</button>
				<?php foreach ($categories_array as $category): ?>
					<button class="button" data-filter=".category-<?php echo esc_attr($category['id']) ?>">
						<span><?php echo strip_tags($category['title']) ?></span>
					</button>
				<?php endforeach;?>
			</div>
		</div>
		<?php echo ob_get_clean();
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

    $thumb_size = $this->settings['thumb_size'];

    $this->project = new \YPRM_Get_Project([
      'id' => $atts->ID,
      'index' => $index,
      'project_link_target' => $this->settings['project_link_target'] == 'on' ? '_blank' : '_self',
      'popup_mode' => $this->settings['popup_mode'],
      'thumb_size' => $thumb_size,
    ]);
  }

  protected function set_project_css($index, $post) {
    $block_setting_key = 'project-index-'.$index;

    switch ($this->settings['cols']) {
      case '1':
        $cols = "col-12";
        break;
      case '2':
        $cols = "col-12 col-sm-6 col-lg-6";
        break;
      case '3':
        $cols = "col-12 col-sm-4 col-lg-4";
        break;
      case '4':
        $cols = "col-12 col-sm-4 col-lg-3";
        break;
      case '6':
        $cols = "col-12 col-sm-4 col-lg-3 col-xl-2";
        break;
  
      default:
        $cols = "";
        break;
      }


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

    $this->css_class[$block_setting_key][] = $this->project->get_categories_css();

    if ($this->settings['popup_mode'] == 'on') {
      $this->css_class[$block_setting_key][] = 'popup-item';

      if ($this->project->has_video()) {
        $this->css_class[$block_setting_key][] = 'with-video';
      }

      if (function_exists('get_field') && get_field('project_image_position', $this->project->get_id())) {
        $this->css_class[$block_setting_key][] = 'image-' . get_field('project_image_position', $this->project->get_id());
      }
    }

    return $block_setting_key;
  }

  protected function render_items() {
    $projects_array = $this->get_items_array()->posts;

    foreach ($projects_array as $key => $project) {
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

  protected function project_title($num) {
    if ($this->settings['show_heading'] !== 'on') {
      return false;
    }

    if ($this->settings['hover'] == 'type_5'): ?>
			<h5><span><?php echo esc_html($num+1); ?></span><?php echo esc_html($this->project->get_title()); ?></h5>
		<?php else: ?>
			<h5><?php echo esc_html($this->project->get_title()); ?></h5>
		<?php endif;
  }

  protected function project_desc() {
    if ($this->settings['show_desc'] !== 'on') {
      return false;
    }

    echo wpautop($this->project->get_short_description($this->settings['desc_size']));
  }

  protected function render_project_flow($atts, $index) {
    
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start();?>
		<li class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>"><?php echo $this->project->get_image_html() ?></div>
			<div class="content">
				<?php echo $this->project_title($index); ?>
				<?php echo $this->project_desc(); ?>
			</div>
			<?php $this->project->get_link_html()?>
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
            <?php echo $this->project->get_image_html() ?>
            <?php echo $this->project_gallery() ?>
          </div>
				<?php } else { ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg() ?>"></div>
            <?php echo $this->project_gallery() ?>
					</div>
				<?php } if ($this->settings['popup_mode'] == 'on' && $this->settings['project_link'] == 'on' && !post_password_required($this->project->get_id())) { ?>
          <a href="<?php echo $this->project->get_permalink() ?>" class="permalink"><i class="basic-ui-icon-link"></i></a>
        <?php } ?>
				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
        <?php if(!$this->project_gallery()) { ?>
          <?php $this->project->get_link_html() ?>
        <?php } ?>
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
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<img width="<?php echo $this->project->get_image_array()[1]; ?>" height="<?php echo $this->project->get_image_array()[2]; ?>" src="<?php echo $this->project->get_image_array()[0]; ?>" alt="<?php echo $this->project->get_title(); ?>">
          </div>
				<?php else: ?>
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<div style="<?php echo $this->project->get_image_bg() ?>"></div>
					</div>
				<?php endif;?>

				<div class="content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php $this->project->get_link_html()?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function render_project_scattered($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ob_start()?>
			<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
				<div class="wrap">
					<div class="a-img" data-original="<?php echo esc_url($this->project->get_image_original()[0]); ?>">
						<?php echo $this->project->get_image_html(); ?>
					</div>
					<div class="content">
						<?php echo $this->project_title($index); ?>
						<?php echo $this->project_desc(); ?>
					</div>
					<?php $this->project->get_link_html() ?>
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
					<div style="<?php echo $this->project->get_image_bg(); ?>">
            <a <?php echo $this->project->get_link_atts() ?>></a>
					</div>
				</div>
				<div class="bottom-content">
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_cats(); ?>
				</div>
			</div>
		</article>
  <?php }

  protected function render_project_carousel_type2($atts, $index) {
    $block_setting_key = $this->set_project_css($index, $atts);

    ?>
		<article class="<?php echo implode(' ', $this->css_class[$block_setting_key]) ?>">
			<div class="wrap">
				<?php echo $this->project->get_image_html(); ?>
				<div class="content">
					<?php echo $this->project_cats(); ?>
					<?php echo $this->project_title($index); ?>
					<?php echo $this->project_desc(); ?>
				</div>
				<?php $this->project->get_link_html()?>
			</div>
		</article>
  <?php }

  protected function project_gallery() {
    if($this->settings['hover'] != 'gallery') return false;

    $gallery_array = [];

    if (is_array($this->project->get_gallery_array()) && count($this->project->get_gallery_array()) > 0) {
      foreach ($this->project->get_gallery_array() as $item) {
        $thumb_size = $this->settings['thumb_size'];
        if ($image = yprm_get_image($item, '', $thumb_size)) {
          $gallery_array[] = $image[0];
        }
      }
    }

    ob_start(); ?>

    <?php if(is_array($this->project->get_gallery_array()) && count($this->project->get_gallery_array()) > 0) { ?>
      <ul class="portfolio-item-gallery">
        <?php foreach ($this->project->get_gallery_array() as $key => $thumb) { 
          $full_img_array = yprm_get_image($thumb, 'array', 'full');

          $popup_array = [];
          
          $popup_array['image'] = [
            'url' => $full_img_array[0],
            'w' => $full_img_array[1],
            'h' => $full_img_array[2]
          ];
          $popup_array['post_id'] = $this->project->get_id();

          $popup_array['projectLink'] = get_permalink($this->project->get_id());
          
          if ($this->settings['type'] == 'grid') { ?>
            <li class="popup-item" style="<?php echo esc_attr(yprm_get_image($thumb, 'bg', 'large')) ?>"><a href="#" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="<?php echo esc_attr($key) ?>"></a></li>
          <?php } elseif ($this->settings['type'] == 'masonry') { ?>
            <li class="popup-item"><?php echo yprm_get_image($thumb, 'img', 'large') ?><a href="#" data-popup-json="<?php echo esc_attr(json_encode($popup_array)) ?>" data-id="<?php echo esc_attr($key) ?>"></a></li>
          <?php } ?>
        <?php } ?>
      </ul>
    <?php } else { ?>
      <a href="#" data-popup-json="<?php echo esc_attr($this->project->get_popup_link_atts()) ?>" data-id="0"></a>
    <?php }

    return ob_get_clean();
  }

  protected function project_cats() {
    $terms = wp_get_post_terms($this->project->get_id(), 'pt-portfolio-category');
    if (!$terms) {
      return false;
    }

    $array = [];

    foreach ($terms as $term) {
      if (!$term || is_wp_error($term)) {
        continue;
      }

      $array[] = $term->name;
    }

    if (empty($array)) {
      return false;
    }

    return '<div class="cat">' . yprm_implode($array, '', ', ') . '</div>';
  }

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
      \$portfolio_carousel_swiper = new Swiper(\$portfolio_carousel.find('.swiper').get(0), {
        slidesPerView: 'auto',
        spaceBetween: 30,
        breakpoints: {
          576: {
            autoHeight: true
          }
        },
        ".(($this->settings['mousewheel'] == 'on') ? 'mousewheel: {},' : '')."
      });
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

    wp_die();
  }

}

new PT_Portfolio_Items();