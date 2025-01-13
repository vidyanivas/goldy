<?php

namespace Elementor;

use YPRM_Get_Post;

if (!defined('ABSPATH')) {
  exit;
}

class Elementor_Blog_Widget extends Widget_Base {
  protected $settings;
  protected $paged;
  protected $post;
  protected $posts_array;

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    if (is_front_page()) {
      $this->paged = (get_query_var('page')) ? get_query_var('page') : 1;
    } else {
      $this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }

    wp_register_script('blog-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/blog.js', array('jquery', 'elementor-frontend'), '', true);
  }

  public function get_name() {
    return 'yprm_blog';
  }

  public function get_title() {
    return esc_html__('Blog', 'pt-addons');
  }

  public function get_icon() {
    return 'pt-el-icon-blog';
  }

  public function get_categories() {
    return ['novo-elements'];
  }

  public function get_script_depends() {
    if (Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode()) {
      return ['blog-handler'];
    }

    return ['blog-handler'];
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
      'type',
      [
        'label' => esc_html__('Type', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'grid' => esc_html__('Grid', 'pt-addons'),
          'masonry' => esc_html__('Masonry', 'pt-addons'),
          'horizontal' => esc_html__('Horizontal', 'pt-addons'),
        ],
        'default' => 'grid',
        'frontend_available' => true,
      ]
    );

    $this->add_group_control(
      Group_Control_Cols::get_type(),
      [
        'name' => 'cols',
        'label' => esc_html__('Cols', 'pt-addons'),
        'condition' => [
          'type!' => 'horizontal',
        ],
      ]
    );

    $this->add_control(
      'count_items',
      [
        'label' => __('Posts Per Page', 'novo'),
        'type' => Controls_Manager::NUMBER,
        'default' => 9,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'sorting_settings',
      [
        'label' => esc_html__('Source', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'source',
      [
        'label' => __('Source', 'novo'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          '' => esc_html__('---', 'novo'),
          'items' => esc_html__('Items', 'novo'),
          'categories' => esc_html__('Categories', 'novo'),
        ],
      ]
    );

    $this->add_control(
      'items',
      [
        'label' => __('Source', 'novo'),
        'type' => \Elementor\Selectize_Control::SELECTIZE,
        'options' => array_flip(pt_get_all_blog_items()),
        'multiple' => true,
        'label_block' => true,
        'condition' => [
          'source' => 'items',
        ],
      ]
    );

    $this->add_control(
      'categories',
      [
        'label' => __('Category', 'novo'),
        'type' => \Elementor\Selectize_Control::SELECTIZE,
        'options' => array_flip(pt_get_all_blog_category()),
        'multiple' => true,
        'label_block' => true,
        'condition' => [
          'source' => 'categories',
        ],
      ]
    );

    $this->add_control(
      'orderby',
      [
        'label' => esc_html__('Order by', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'post__in' => esc_html__('Default', 'pt-addons'),
          'author' => esc_html__('Author', 'pt-addons'),
          'date' => esc_html__('Date', 'pt-addons'),
          'ID' => esc_html__('ID', 'pt-addons'),
          'title' => esc_html__('Title', 'pt-addons'),
        ],
        'default' => 'ID',
      ]
    );

    $this->add_control(
      'order',
      [
        'label' => esc_html__('Order', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'DESC' => esc_html__('Descending order', 'pt-addons'),
          'ASC' => esc_html__('Ascending order', 'pt-addons'),
        ],
        'default' => 'DESC',
      ]
    );

    $this->add_control(
      'navigation',
      [
        'label' => __('Navigation', 'novo'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          "none" => esc_html__("None", "novo"),
          "load_more" => esc_html__("Load More", "novo"),
          "pagination" => esc_html__("Pagination", "novo"),
        ],
        'default' => 'none',
      ]
    );

    // Load more additional settings.
    $this->add_control(
      'pagination_load_more_text',
      [
        'label' => __('Button Text', 'pt-novo'),
        'type' => Controls_Manager::TEXT,
        'default' => __('Load more', 'pt-novo'),
        'placeholder' => '',
        'condition' => [
          'navigation' => 'load_more',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'layout_settings_section',
      [
        'label' => esc_html__('Layout Settings', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'show_featured_image',
      [
        'label' => __('Show Image', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_title',
      [
        'label' => __('Show Title', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'title_tag',
      [
        'label' => __('Title Tag', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'default' => 'h5',
        'options' => [
          'h1' => __('H1', 'pt-addons'),
          'h2' => __('H2', 'pt-addons'),
          'h3' => __('H3', 'pt-addons'),
          'h4' => __('H4', 'pt-addons'),
          'h5' => __('H5', 'pt-addons'),
          'h6' => __('H6', 'pt-addons'),
          'span' => __('Span', 'pt-addons'),
          'p' => __('P', 'pt-addons'),
          'div' => __('Div', 'pt-addons'),
        ],
        'condition' => [
          'show_title' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'show_excerpt',
      [
        'label' => __('Show excerpt', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_read_more',
      [
        'label' => __('Show Read More', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'read_more_text',
      [
        'label' => esc_html__('Label Text', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'dynamic' => ['active' => true],
        'label_block' => false,
        'default' => esc_html__('Read More', 'pt-addons'),
        'condition' => [
          'show_read_more' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'show_author',
      [
        'label' => __('Show Author Name', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_date',
      [
        'label' => __('Show Date', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_comments',
      [
        'label' => __('Show Comments', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_likes',
      [
        'label' => __('Show Likes', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => __('Show', 'pt-addons'),
        'label_off' => __('Hide', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'filter_buttons_section',
      [
        'label' => esc_html__('Filter buttons', 'pt-addons'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'filter_buttons',
      [
        'label' => esc_html__('Filter buttons', 'pt-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pt-addons'),
        'label_off' => esc_html__('No', 'pt-addons'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'filter_buttons_align',
      [
        'label' => esc_html__('Filter buttons align', 'pt-addons'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'tal' => esc_html__('Left', 'pt-addons'),
          'tac' => esc_html__('Center', 'pt-addons'),
          'tar' => esc_html__('Right', 'pt-addons'),
        ],
        'default' => 'tal',
        'condition' => [
          'filter_buttons' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'customizing',
      [
        'label' => esc_html__('Customizing', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      Group_Control_Image_Size::get_type(),
      [
        'name' => 'thumb',
        'label' => 'Images Quality',
        'exclude' => ['custom'],
        'include' => [],
        'default' => 'large',
        'condition' => [
          'show_featured_image' => 'yes',
        ],
      ]
    );

    $this->add_responsive_control(
      'image_aspect_ratio',
      [
        'label' => esc_html__('Aspect Ratio (%)', 'pt-addons'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 10,
            'max' => 300,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .blog-item .img a' => 'padding-bottom: {{SIZE}}%;',
        ],
        'condition' => [
          'type' => ['grid'],
          'show_featured_image' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'date_format',
      [
        'label' => esc_html__('Date Format', 'pt-addons'),
        'type' => Controls_Manager::TEXT,
        'description' => wp_kses_post(__("(optional). <a href=\"https://www.php.net/manual/function.date.php\" target=\"_blank\">Look date formats</a>", "pt-addons")),
        'condition' => [
          'show_date' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'color_customizing',
      [
        'label' => esc_html__('Color Customizing', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'author_block_background_color',
      [
        'label' => esc_html__('Author Block Background', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .author-info-block' => 'background-color: {{VALUE}}',
        ],
        'condition' => [
          'type!' => 'horizontal',
          'show_author' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'author_block_color',
      [
        'label' => esc_html__('Author Block', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .author-info-block' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'show_author' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'background_color',
      [
        'label' => esc_html__('Background Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .wrap' => 'background-color: {{VALUE}} !important;',
        ],
        'condition' => [
          'type!' => ['classic'],
        ],
      ]
    );

    $this->add_control(
      'text_color',
      [
        'label' => esc_html__('Text Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .wrap' => 'color: {{VALUE}} !important;',
        ],
      ]
    );

    $this->add_control(
      'categories_color',
      [
        'label' => esc_html__('Categories Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .categories' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'show_categories' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'heading_color',
      [
        'label' => esc_html__('Heading Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .title:not(:hover)' => 'color: {{VALUE}} !important',
        ],
        'condition' => [
          'show_title' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'heading_hover_color',
      [
        'label' => esc_html__('Heading Color on Hover', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .title:hover' => 'color: {{VALUE}} !important',
        ],
        'condition' => [
          'show_title' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'short_desc_color',
      [
        'label' => esc_html__('Short Desc Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .desc' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'show_excerpt' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'readmore_button_color',
      [
        'label' => esc_html__('Read More Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .button a:not(:hover)' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'show_read_more' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'readmore_button_hover_color',
      [
        'label' => esc_html__('Read More Hover Color', 'pt-addons'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .blog-item .button a:hover' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'show_read_more' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'typography_customizing',
      [
        'label' => esc_html__('Typography Customizing', 'pt-addons'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'categories',
        'label' => esc_html__('Categories', 'pt-addons'),
        'selector' => '{{WRAPPER}} .blog-item .categories',
        'condition' => [
          'show_categories' => 'yes',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'title',
        'label' => esc_html__('Title', 'pt-addons'),
        'selector' => '{{WRAPPER}} .blog-item .title',
        'condition' => [
          'show_title' => 'yes',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'desc',
        'label' => esc_html__('Desc', 'pt-addons'),
        'selector' => '{{WRAPPER}} .blog-item .content p',
        'condition' => [
          'show_excerpt' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function set_css_classes() {
    $settings = $this->settings;
    $posts = $this->posts_array;

    $this->add_render_attribute('block', 'class', 'blog-block elementor-block');

    if ($settings['type'] == 'horizontal') {
      $this->add_render_attribute('items_block', 'class', 'boxed-off');
    }

    if ($settings['type'] != 'grid' || $settings['filter_buttons'] == 'yes') {
      $this->add_render_attribute('items_block', 'class', 'isotope');
    }

    $this->add_render_attribute('items_block', 'class', 'row');

    $this->add_render_attribute('items_block', 'class', 'blog-type-' . $settings['type']);

    $this->add_render_attribute('items_block', 'class', 'load-wrap blog-items');
  }

  protected function render() {
    $this->settings = $settings = $this->get_settings_for_display();
    $posts_array = $this->posts_array = $this->get_items_array();

    $this->set_css_classes();

    if (!$posts_array->have_posts()) {
      return false;
    }

    ob_start();?>

		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<?php $this->get_filter_buttons_html()?>
			<div <?php echo $this->get_render_attribute_string('items_block') ?>>
        <div class="grid-sizer col-1"></div>
				<?php $this->render_items($posts_array)?>
			</div>
			<?php $this->get_navigation_html();?>
		</div>

    <?php echo ob_get_clean();
    wp_reset_postdata();
  }

  protected function get_source_categories() {
    if ($this->settings['source'] == 'categories' && $this->settings['categories']) {
      return array(
        array(
          'taxonomy' => 'category',
          'field' => 'id',
          'terms' => $this->settings['categories'],
        ),
      );
    }

    return array();
  }

  protected function get_source_items() {
    if ($this->settings['source'] == 'items' && $this->settings['items']) {
      return $this->settings['items'];
    }

    return false;
  }

  protected function get_categories_array() {
    $categories_array = array();

    if(
      ($this->settings['navigation'] != 'load_more' || $this->get_source_items()) &&
      ($this->settings['source'] != 'categories' || !$this->settings['categories'])
    ) {
      $array = $this->get_items_array()->posts;

      if(count($array) > 0) {
        foreach($array as $item) {
          $categories = wp_get_post_terms($item->ID, 'category');

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
      $categories = $this->settings['categories'];

      if(count($categories) > 0) {
        foreach($categories as $category) {
          $category = get_term($category, 'category');
          
          if(isset($categories_array[$category->term_id])) continue;

          $categories_array[$category->term_id] = array(
            'id' => $category->term_id,
            'slug' => $category->slug,
            'title' => $category->name
          );
        }
      }
    } else {
      $categories = get_terms('category', array(
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

  protected function get_items_array() {

    $query_args = array(
      'post__in' => $this->get_source_items(),
      'posts_per_page' => $this->settings['count_items'],
      'paged' => $this->paged,
      'orderby' => $this->settings['orderby'],
      'order' => $this->settings['order'],
      'post_type' => 'post',
      'post_status' => 'publish',
      'tax_query' => $this->get_source_categories(),
    );

    return new \WP_Query($query_args);
  }

  protected function get_loadmore_array() {
    $args = array(
      'post__in' => $this->get_source_items(),
      'posts_per_page' => -1,
      'paged' => $this->paged,
      'orderby' => $this->settings['orderby'],
      'order' => $this->settings['order'],
      'post_type' => 'post',
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

        foreach (wp_get_post_terms($item->ID, 'category') as $s_item) {
          $loadmore_array[$key]['cat'][] = $s_item->term_id;
        }
      }
    }

    return $loadmore_array;
  }

  protected function get_filter_buttons_html() {
    if ($this->settings['filter_buttons'] != 'yes') {
      return false;
    }

    $this->add_render_attribute('filter_buttons_block', 'class', [
      'filter-button-group',
      $this->settings['filter_buttons_align'],
    ]);

    $categories = $this->get_categories_array();

    if (empty($categories) || !is_array($categories) || count($categories) < 2) {
      return false;
    }

    ob_start();?>
		<div <?php echo $this->get_render_attribute_string('filter_buttons_block'); ?>>
			<div class="wrap">
				<button class="active" data-filter="*">
					<span><?php echo yprm_get_theme_setting('tr_all') ?></span>
				</button>
				<?php foreach ($categories as $category) {?>
					<button data-filter=".category-<?php echo esc_attr($category['id']) ?>">
						<span><?php echo strip_tags($category['title']) ?></span>
					</button>
				<?php }?>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function set_post_object($post_id, $index) {
    $this->post = new \YPRM_Get_Post([
      'id' => $post_id,
      'thumb_size' => $this->settings['thumb_size'],
    ]);
  }

  protected function set_post_css($index) {
    $settings = $this->settings;
    $block_setting_key = $this->get_repeater_setting_key('post', 'items', $index);
    $cols = yprm_el_cols($settings);

    if ($settings['type'] == 'horizontal') {
      $cols = 'col-12';
    }

    $this->add_render_attribute($block_setting_key, 'class', [
      'blog-item',
      'istp-item',
      $cols,
    ]);

    if ($this->post->has_thumb()) {
      $this->add_render_attribute($block_setting_key, 'class', 'with-image');
    }

    if($settings['show_author'] == 'yes') {
      $this->add_render_attribute($block_setting_key, 'class', 'with-author');
    }

    $this->add_render_attribute($block_setting_key, 'class', $this->post->get_categories_css());

    return $block_setting_key;
  }

  protected function render_items($query) {

    $key = 0;
    while ($query->have_posts()):
      $query->the_post();
      $key++;
      echo $this->render_post(get_the_ID(), $key);
    endwhile;

    wp_reset_postdata();
  }

  protected function render_post($post_id, $index) {
    $this->set_post_object($post_id, $index);
    $block_setting_key = $this->set_post_css($index);

    ob_start(); ?>

		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<?php $this->get_post_featured_image()?>
				<div class="content">
					<?php if (post_password_required($post_id)): ?>
				    	<div class="locked"><i class="fa fa-lock"></i></div>
				    <?php endif;?>
					<?php $this->get_post_title()?>
					<?php if ($this->settings['show_author'] != 'yes'): ?>
						<?php $this->get_post_date()?>
					<?php endif;?>
					<?php $this->get_post_short_desc()?>
					<?php $this->get_post_readmore()?>
				</div>
        <div class="clear"></div>
        <?php $this->get_post_comments()?>
			</div>
		</article>
		<?php return ob_get_clean();
  }

  protected function get_post_featured_image() {
    if (!$this->post->has_thumb() || $this->settings['show_featured_image'] != 'yes') {
      return;
    }

    ob_start();?>
		<?php if ($this->settings['type'] == 'masonry') {?>
			<div class="img">
				<?php $this->get_post_author_block()?>
				<a href="<?php echo $this->post->get_permalink(); ?>" data-original="<?php echo esc_url($this->post->get_image_original()[0]); ?>">
					<?php echo $this->post->get_image_html(); ?>
				</a>
			</div>
		<?php } else {?>
			<div class="img" data-original="<?php echo esc_url($this->post->get_image_original()[0]); ?>">
				<?php $this->get_post_author_block()?>
				<a href="<?php echo $this->post->get_permalink(); ?>" style="background-image: url(<?php echo esc_url($this->post->get_image_array()[0]) ?>)"></a>
			</div>
		<?php }?>
		<?php echo ob_get_clean();
  }

  protected function get_post_title() {
    if ($this->settings['show_title'] != 'yes' || !$this->post->get_title()) {
      return;
    }

    ob_start(); ?>
		<<?php echo esc_attr($this->settings['title_tag']); ?>>
			<a class="title" href="<?php echo $this->post->get_permalink() ?>"><?php echo strip_tags($this->post->get_title()) ?></a>
		</<?php echo esc_attr($this->settings['title_tag']); ?>>
		<?php echo ob_get_clean();
  }

  protected function get_post_author_block() {
    if ($this->settings['show_author'] != 'yes') {
      return;
    }

    ob_start(); ?>
		<div class="author-info-block">
			<div class="author-info-avatar" style="background-image: url(<?php echo $this->post->get_avatar_url() ?>)"></div>
			<div class="author-info-content">
				<div class="name"><?php echo $this->post->get_author() ?></div>
				<?php $this->get_post_date()?>
			</div>
		</div>
		<?php echo ob_get_clean();
  }

  protected function get_post_categories() {
    if ($this->settings['show_categories'] != 'yes' || !$this->post->get_categories_string()) {
      return;
    }

    ob_start(); ?>
		<div class="categories"><?php echo $this->post->get_categories_string(); ?></div>
		<?php echo ob_get_clean();
  }

  protected function get_post_date() {
    if ($this->settings['show_date'] != 'yes') {
      return;
    }

    ob_start(); ?>
		<div class="date"><?php echo strip_tags($this->post->get_date($this->settings['date_format'])); ?></div>
		<?php echo ob_get_clean();
  }

  protected function get_post_short_desc() {
    if ($this->settings['show_excerpt'] != 'yes' || !$this->post->get_short_description()) {
      return;
    }

    ob_start(); ?>
		<div class="desc"><?php echo wpautop($this->post->get_short_description()); ?></div>
		<?php echo ob_get_clean();
  }

  protected function get_post_readmore() {
    if ($this->settings['show_read_more'] != 'yes') {
      return;
    }

    ob_start(); ?>
		<a href="<?php echo $this->post->get_permalink() ?>" class="button-style2">
			<?php echo esc_html($this->settings['read_more_text']); ?>
		</a>
		<?php echo ob_get_clean();
  }

  protected function get_post_comments() {
    if(
      ($this->settings['show_likes'] != 'yes' || !function_exists('zilla_likes')) &&
      $this->settings['show_comments'] != 'yes'
    ) return false;

    ob_start(); ?>
		<div class="bottom">
			<?php if ($this->settings['show_likes'] == 'yes' && function_exists('zilla_likes')): ?>
				<div class="col">
					<?php echo zilla_likes($this->post->get_id()); ?>
				</div>
			<?php endif;?>
			<?php if ($this->settings['show_comments'] == 'yes'): ?>
				<div class="col">
					<i class="multimedia-icon-speech-bubble-1"></i>
					<a href="<?php echo $this->post->get_permalink() ?>#comments"><?php echo get_comments_number_text(false, false, false, $this->post->get_id()); ?></a>
				</div>
			<?php endif;?>
		</div>
		<?php echo ob_get_clean();
  }

  public function loadmore() {
    $this->settings = $_POST['atts'];
		$array = $_POST['array'];
		$start_index = $_POST['start_index'];  

		if(is_array($array) && count($array) > 0) {
			foreach($array as $post) {
				echo $this->render_post($post['id'], $start_index++);
			}
		} else {
			echo array(
				'return' => 'error'
			);
		}
  }

  public function get_posts_per_page($pagination_mode, $settings) {
    $settings = wp_parse_args(
      $settings,
      [
        'dis_posts_total' => -1,
        'st_posts_per_page' => -1,
        'jsm_posts_per_page' => -1,
      ]
    );

    $max_posts_per_page = 99999;
    switch ($pagination_mode) {
    case 'disabled':
      $posts_per_page = $settings['dis_posts_total'];
      break;
    case 'standard':
      $posts_per_page = $settings['st_posts_per_page'];
      break;
    case 'js_more':
      $posts_per_page = $settings['jsm_posts_per_page'];
      break;
    default:
      return $max_posts_per_page;
    }

    $posts_per_page = (int) $posts_per_page;
    if ($posts_per_page === -1 || !$posts_per_page) {
      return $max_posts_per_page;
    }

    return $posts_per_page;
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
    }if ($loadmore_array && ($this->settings['navigation'] == "load_more" || $this->settings['navigation'] == 'load_more_on_scroll') && $max_num_pages > 1) { ?>
			<div class="load-button tac">
				<a href="#" data-array="<?php echo esc_attr($loadmore_array); ?>" data-action="loadmore_elementor_blog" data-count="<?php echo $this->settings['count_items'] ?>" data-atts="<?php echo esc_attr(json_encode($this->settings)) ?>" class="button-style2 loadmore-button <?php echo esc_attr($this->settings['navigation']); ?>">
					<span><?php echo $this->settings['pagination_load_more_text']; ?></span>
				</a>
			</div>
		<?php }
  }
}