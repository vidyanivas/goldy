<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

class Elementor_Products_Widget extends Widget_Base {
	protected $settings;
	protected $paged;
	protected $produ;
	protected $displacement_url;
	protected $products_object;

	protected $item_col;

	protected $mobile_cols;

	protected $tablet_cols;

	protected $desktop_cols;

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		$this->displacement_url = YPRM_PLUGINS_HTTP.'/assets/imgs/pattern4.jpg';

		if (is_front_page()) {
			$this->paged = (get_query_var('page')) ? get_query_var('page') : 1;
		} else {
			$this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}

		wp_register_script( 'products-handler', YPRM_PLUGINS_HTTP . '/elementor/js/widgets/products.js', array( 'jquery', 'isotope' ), '', true );
	}

	public function get_name() {
		return 'yprm_products';
	}

	public function get_title() {
		return esc_html__( 'Products', 'pt-addons' );
	}

	public function get_icon() {
		return 'pt-el-icon-products';
	}

	public function get_categories() {
		return [ 'novo-elements' ];
	}

	public function get_script_depends() {
		if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [  'isotope', 'pt-load-posts', 'products-handler' ];
		}

		$scripts = [];
		$type = $this->get_settings_for_display('type');
		$navigation = $this->get_settings_for_display('loading_mode');

		$scripts[] = 'isotope';

		if ( $navigation == 'js_more' ) {
			$scripts[] = 'pt-load-posts';
		}

		$scripts[] = 'products-handler';
		return $scripts;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'layout_content_section',
			[
				'label' => __( 'Layout', 'pt-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'grid' => esc_html__( 'Grid', 'pt-addons' ),
					'masonry' => esc_html__( 'Masonry', 'pt-addons' ),
				],
				'default' => 'grid',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'cols',
			[
				'label' => esc_html__( 'Columns', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1'  => esc_html__( 'Col 1', 'pt-addons' ),
					'2' => esc_html__( 'Col 2', 'pt-addons' ),
					'3' => esc_html__( 'Col 3', 'pt-addons' ),
					'4' => esc_html__( 'Col 4', 'pt-addons' ),
				],
				'default' => '3',
				'frontend_available' => true,
				'condition' => [
					'type' => ['grid', 'masonry', 'carousel']
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sorting_settings',
			[
				'label' => esc_html__( 'Query', 'pt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sort_type',
			[
				'label' => esc_html__( 'Products', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'product'         => __( 'Latest Products', 'pt-addons' ),
					'sale'            => __( 'Sale', 'pt-addons' ),
					'top'             => __( 'Top rated products', 'pt-addons' ),
					'best_selling'    => __( 'Best selling', 'pt-addons' ),
					'featured'        => __( 'Featured', 'pt-addons' ),
					'by_id'           => _x( 'Manual Selection', 'Posts Query Control', 'pt-addons' ),
				],
				'default' => 'product',
			]
		);

		$this->add_control(
			'query_args',
			[
				'type' => Controls_Manager::TABS,
			]
		);

		$tabs_wrapper = 'query_args';
		$include_wrapper = 'query_include';
		$exclude_wrapper = 'query_exclude';

		$this->add_control(
			'query_include',
			[
				'type' => Controls_Manager::TAB,
				'label' => __( 'Include', 'pt-addons' ),
				'tabs_wrapper' => $tabs_wrapper,
				'condition' => [
					'sort_type!' => [
						'current_query',
						'by_id',
					],
				],
			]
		);

		$this->add_control(
			'include',
			[
				'label' => __( 'Include By', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'manual_selection' => __( 'Manual Selection', 'pt-addons' ),
					'terms' => __( 'Term', 'pt-addons' ),
				],
				'condition' => [
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
				'label_block' => true,
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $include_wrapper,
			]
		);

		$this->add_control(
			'posts_ids',
			[
				'label' => __( 'Search & Select', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_all_products_items(),
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'sort_type' => 'by_id',
				],
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $include_wrapper,
				'export' => false,
			]
		);

		$this->add_control(
			'include_ids',
			[
				'label' => __( 'Search & Select', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_all_products_items(),
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'include' => 'manual_selection',
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $include_wrapper,
				'export' => false,
			]
		);

		$this->add_control(
			'include_term_ids',
			[
				'label' => esc_html__( 'Category', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_all_products_category(),
				'condition' => [
					'include' => 'terms',
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $include_wrapper,
			]
		);

		$this->add_control(
			'query_exclude',
			[
				'type' => Controls_Manager::TAB,
				'label' => __( 'Exclude', 'pt-addons' ),
				'tabs_wrapper' => $tabs_wrapper,
				'condition' => [
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' => __( 'Exclude By', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'manual_selection' => __( 'Manual Selection', 'pt-addons' ),
					'terms' => __( 'Term', 'pt-addons' ),
				],
				'condition' => [
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
				'label_block' => true,
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $exclude_wrapper,
			]
		);

		$this->add_control(
			'exclude_ids',
			[
				'label' => __( 'Search & Select', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_all_products_items(),
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'exclude' => 'manual_selection',
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $exclude_wrapper,
				'export' => false,
			]
		);

		$this->add_control(
			'exclude_term_ids',
			[
				'label' => __( 'Term', 'pt-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_all_products_category(),
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'exclude' => 'terms',
					'sort_type!' => [
						'by_id',
						'current_query',
					],
				],
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $exclude_wrapper,
				'export' => false,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order by', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'       => __( 'Date', 'pt-addons' ),
					'title'      => __( 'Title', 'pt-addons' ),
					'price'      => __( 'Price', 'pt-addons' ),
					'popularity' => __( 'Popularity', 'pt-addons' ),
					'rating'     => __( 'Rating', 'pt-addons' ),
					'rand'       => __( 'Random', 'pt-addons' ),
					'menu_order' => __( 'Menu Order', 'pt-addons' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => __( 'Order', 'pt-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => [
					'asc'  => __( 'ASC', 'pt-addons' ),
					'desc' => __( 'DESC', 'pt-addons' ),
				]
			]
		);

		$this->add_control(
			'loading_mode',
			[
				'label'     => __( 'Pagination mode', 'pt-novo' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'disabled',
				'options'   => [
					'disabled'        => 'Disabled',
					'standard'        => 'Standard',
					'js_more'         => '"Load more" button',
				],
			]
		);

		// Load more additional settings.
		$this->add_control(
			'pagination_load_more_text',
			[
				'label'       => __( 'Button Text', 'pt-novo' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Load more', 'pt-novo' ),
				'placeholder' => '',
				'condition'   => [
					'loading_mode' => 'js_more'
				],
			]
		);

		$this->add_control(
			'dis_posts_total',
			[
				'label'       => __( 'Total Number Of Posts', 'pt-novo' ),
				'description' => __( 'Leave empty to display all posts.', 'pt-novo' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'condition'  => [
					'loading_mode'     => 'disabled'
				],
			]
		);

		// Standard pagination.
		$this->add_control(
			'st_posts_per_page',
			[
				'label'       => __( 'Posts Per Page', 'pt-novo' ),
				'description' => __(
					'Leave empty to use value from the WP Reading settings. Set "-1" to show all posts.',
					'pt-novo'
				),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'condition'   => [
					'loading_mode'     => 'standard',
				],
			]
		);

		$this->add_control(
			'jsm_posts_per_page',
			[
				'label'       => __( 'Posts Per Page', 'pt-novo' ),
				'description' => __( 'Leave empty to use value from the WP Reading settings.', 'pt-novo' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'condition'   => [
					'loading_mode'     => 'js_more'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'filter_buttons_section',
			[
				'label' => esc_html__( 'Filter buttons', 'pt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'filter_buttons',
			[
				'label' => esc_html__( 'Filter buttons', 'pt-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'pt-addons' ),
				'label_off' => esc_html__( 'No', 'pt-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'filter_buttons_style',
			[
				'label' => esc_html__( 'Filter buttons style', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style1'  => esc_html__( 'Style 1', 'pt-addons' ),
					'style2' => esc_html__( 'Style 2', 'pt-addons' ),
				],
				'default' => 'style1',
				'condition' =>[
					'filter_buttons' => 'yes'
				]
			]
		);

		$this->add_control(
			'filter_buttons_align',
			[
				'label' => esc_html__( 'Filter buttons align', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'tal'  => esc_html__( 'Left', 'pt-addons' ),
					'tac' => esc_html__( 'Center', 'pt-addons' ),
					'tar' => esc_html__( 'Right', 'pt-addons' ),
				],
				'default' => 'tal',
				'condition' =>[
					'filter_buttons' => 'yes'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->settings = $this->get_settings_for_display();

		$products_object = new \Pt_Product_Query_Class( $settings );
		$query = $products_object->create();

		$products_object = $this->products_object = $products_object->create();

		$this->set_cols_css();

		$this->add_render_attribute('block', 'class', [
			'product-block',
			'product-elementor-block',
		]);

		$this->add_render_attribute('items_block', 'class', [
			'products',
			// 'isotope',
			'load-wrap',
			'row',
		]);

		if(!$products_object->have_posts()) return false;

		ob_start(); ?>

		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<div class="woocommerce">
				<?php $this->get_filter_buttons_html() ?>
				<ul <?php echo $this->get_render_attribute_string('items_block') ?>>
					<?php $this->render_items( $products_object ); ?>
				</ul>
			</div>
			<?php $this->get_navigation_html( $this->settings['loading_mode'] ) ?>
		</div>

		<?php

        echo ob_get_clean();

		wp_reset_postdata();
	}

	protected function get_loadmore_array() {

		$settings = $this->settings;

		$settings['dis_posts_total']   = -1;
		$settings['st_posts_per_page'] = -1;
		$settings['jsm_posts_per_page']   = -1;

		$products_object = new \Pt_Product_Query_Class( $settings );

		$query = $products_object->create();

		if ( ! isset( $query->posts ) || empty( $query->posts ) ) {
			return $loadmore_array;
		}

		foreach ( $query->posts as $key => $post ) {
			$loadmore_array[ $key ] = [
				'id' => $post->ID
			];

			foreach ( wp_get_post_terms( $post->ID, 'product_cat' ) as $s_item ) {
				if ( ! $s_item ) {
					continue;
				}

				$loadmore_array[$key]['cat'][] = $s_item->slug;
			}
		}

		return $loadmore_array;
	}

	protected function normalize_terms( $terms ) {
		$array = [];

		foreach ( $terms as $term ) {
			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}

			$array[ $term->term_id ] = [
				'id'	=> $term->term_id,
				'slug'	=> $term->slug,
				'title'	=> $term->name
			];
		}

		return $array;
	}

	protected function get_selected_terms() {
		$categories_array = array();

		if ( $this->settings['loading_mode'] != 'load_more' && ! $this->settings[ 'include_term_ids' ] && ! empty( $this->settings['include'] ) && ! in_array( 'terms', $this->settings[ 'include' ], true ) ) {

			$array = $this->products_object->posts;
			if ( empty( $array ) ) {
				return $categories_array;
			}

			foreach( $array as $item ) {
				$categories = wp_get_post_terms( $item->ID, 'product_cat' );
				if ( ! $categories ) {
					continue;
				}

				$categories_array = $this->normalize_terms( $categories );

				if ( empty( $categories_array ) ) {
					continue;
				}
			}

		} elseif ( $this->settings['include_term_ids'] && in_array( 'terms', $this->settings[ 'include' ], true ) ) {

			$categories = get_terms('product_cat', array(
				'hide_empty' => true,
				'include' => $this->settings['include_term_ids'],
			));

			if ( empty( $categories ) ) {
				return $categories_array;
			}

			$categories_array = $this->normalize_terms( $categories );
		} else {
			$categories = get_terms('product_cat', array(
				'hide_empty' => true,
			));

			if ( empty( $categories ) ) {
				return $categories_array;
			}

			$categories_array = $this->normalize_terms( $categories );
		}

		return $categories_array;
	}

	protected function get_filter_buttons_html() {
		if ( $this->settings['filter_buttons'] != 'yes' ) return false;

		$this->add_render_attribute( 'filter_buttons_block' , 'class', [
			'filter-button-group',
			$this->settings['filter_buttons_style'],
			$this->settings['filter_buttons_align']
		]);

		$categories = $this->get_selected_terms();

		if ( empty( $categories ) || ! is_array( $categories ) ) {
			return false;
		}

		ob_start(); ?>
		<div <?php echo $this->get_render_attribute_string( 'filter_buttons_block' ); ?>>
			<div class="wrap">
				<button class="active" data-filter="*">
					<span><?php echo yprm_get_theme_setting('tr_all') ?></span>
				</button>
				<?php foreach( $categories as $category) { ?>
					<button data-filter=".product_cat-<?php echo esc_attr($category['slug']) ?>">
						<span><?php echo strip_tags($category['title']) ?></span>
					</button>
				<?php } ?>
			</div>
		</div>
		<?php echo ob_get_clean();
	}

	public function get_posts_per_page( $pagination_mode, $settings ) {
		$settings = wp_parse_args(
			$settings,
			[
				'dis_posts_total'   => -1,
				'st_posts_per_page' => -1,
				'jsm_posts_per_page'   => -1,
			]
		);

		$max_posts_per_page = 99999;
		switch ( $pagination_mode ) {
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
		if ( $posts_per_page === -1 || ! $posts_per_page ) {
			return $max_posts_per_page;
		}

		return $posts_per_page;
	}

	protected function display_load_more_button( $loadmore_array ) {
		?>
			<div class="load-button elementor-loadmore-button tac">
				<a class="button-style1 accent loadmore-button load_more" data-type="<?php echo esc_attr($this->settings['type']) ?>" data-array="<?php echo esc_attr($loadmore_array) ?>" data-count="<?php echo $this->get_posts_per_page( $this->settings['loading_mode'], $this->settings ) ?>" data-atts="<?php echo esc_attr(json_encode($this->settings)) ?>">
					<span><?php echo $this->get_settings_for_display( 'pagination_load_more_text' ); ?></span>
				</a>
			</div>
		<?php
	}

	protected function get_navigation_html( $loading_mode ) {
		$max_num_pages = $this->products_object->max_num_pages;
		$loadmore_array = array_slice($this->get_loadmore_array(), $this->get_posts_per_page( $this->settings['loading_mode'], $this->settings ) );
		$loadmore_array = json_encode($loadmore_array);
		if ( 'standard' === $loading_mode ) {
			if (function_exists( 'yprm_wp_corenavi' ) ) {
				echo yprm_wp_corenavi( $max_num_pages );
			} else {
				wp_link_pages();
			}

		} elseif ( 'js_more' === $loading_mode && $max_num_pages > 1 ) {
			$this->display_load_more_button( $loadmore_array );
		}
	}

	protected function set_cols_css() {
		switch ($this->settings['cols']) {
			case '1':
				$this->item_col = "col-12";
				$this->mobile_cols = "1";
				$this->tablet_cols = "1";
				$this->desktop_cols = "1";
				break;
			case '2':
				$this->item_col = "col-12 col-sm-6";
				$this->mobile_cols = "1";
				$this->tablet_cols = "2";
				$this->desktop_cols = "2";
				break;
			case '3':
				$this->item_col = "col-12 col-sm-6 col-md-4";
				$this->mobile_cols = "1";
				$this->tablet_cols = "2";
				$this->desktop_cols = "3";
				break;
			case '4':
				$this->item_col = "col-12 col-sm-4 col-md-4 col-lg-3";
				$this->mobile_cols = "1";
				$this->tablet_cols = "2";
				$this->desktop_cols = "4";
				break;

			default:
				$this->item_col = "";
				break;
		}
	}

	protected function render_items( $query ) {
		global $woocommerce_loop;
		$woocommerce_loop['columns'] = $this->settings['cols'];
		$woocommerce_loop['type'] = $this->settings['type'];

		while ( $query->have_posts() ) :
			$query->the_post();
			echo wc_get_template_part( 'content', 'product' );
		endwhile;

		woocommerce_reset_loop();
		wp_reset_postdata();
	}

	public function loadmore() {

		$array = $_POST['array'];
		$atts = $_POST['atts'];
		$type = $_POST['type'];
		$start_index = $_POST['start_index'];
		$ids = [];

		foreach( $array as $item ) {
			$ids[] = $item['id'];
		}

		$atts_array = array(
			'posts_per_page' => count( $ids ),
			'orderby' => $atts['orderby'],
			'order' => $atts['order'],
			'post_type' => 'product',
		);

		if ( is_array( $ids ) && ! empty( $ids ) ) {
			$atts_array['post__in'] = $ids;
		}

		if ( ! empty( $atts['include'] ) && ! empty( $atts['include_term_ids'] ) && in_array( 'terms', $atts['include'] ) ) {
			$atts_array['tax_query'] = [
				array(
					'taxonomy' => 'product_cat',
					'terms' => $atts['include_term_ids'],
					'field' => 'id'
				),
			];
		}

		$products = new \WP_Query( $atts_array );

		global $woocommerce_loop;
		$woocommerce_loop['columns'] = $atts['cols'];
		$woocommerce_loop['type'] = $atts['type'];

		if ( $products->have_posts() ) {
			while ($products->have_posts()): $products->the_post();
        global $woocommerce_loop;
        $woocommerce_loop['type'] = $type;
        
        wc_get_template_part( 'content', 'product' );
      endwhile;
		} else {
			echo array(
				'return' => 'error'
			);
		}

		woocommerce_reset_loop();
    wp_reset_postdata();

		wp_die();
	}

	public function get_all_products_category() {
		$taxonomy = 'product_cat';
		$args = array(
			'hide_empty' => true,
		);

		$terms = get_terms($taxonomy, $args);
		$result = array();

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $result;
		}

		foreach ( $terms as $term ) {
			if ( ! $term ) { continue; }

			if ( get_category_parents( $term->term_id ) ) {
				$name = get_category_parents($term->term_id);
			} else {
				$name = $term->name;
			}

			$name = trim($name, '/');

			$result[ $term->term_id ] = 'ID [' . $term->term_id . '] '. $name;
		}

		return $result;
	}

	protected function get_all_products_items($param = 'All') {
		$result = array();

		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		);

		$porfolio_array = new \WP_Query($args);

		if ( empty( $porfolio_array->posts ) ) {
			return $result;
		}

		foreach ( $porfolio_array->posts as $post ) {
			if ( ! $post ) { continue; }

			$result[ $post->ID ] = 'ID [' . $post->ID . '] '. $post->post_title;
		}

		return $result;
	}
}