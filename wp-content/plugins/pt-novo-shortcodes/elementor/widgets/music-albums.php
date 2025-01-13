<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

#[AllowDynamicProperties]

class Elementor_Music_Albums_Widget extends Widget_Base {
	protected $paged;

	protected $posts_array;

	protected $wrap_id;

	protected $post;


	var $settings;

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		if (is_front_page()) {
			$this->paged = (get_query_var('page')) ? get_query_var('page') : 1;
		} else {
			$this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
	}

	public function get_name() {
		return 'yprm_music_albums';
	}

	public function get_title() {
		return esc_html__( 'Music Albums', 'pt-addons' );
	}

	public function get_icon() {
		return 'pt-el-icon-music-albums';
	}

	public function get_categories() {
		return [ 'novo-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'pt-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Cols::get_type(),
			[
				'name' => 'cols',
				'label' => esc_html__( 'Cols', 'pt-addons' ),
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
				'label' => esc_html__( 'Source', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'posts'         => __( 'Latest Posts', 'pt-addons' ),
					'by_id'           => _x( 'Manual Selection', 'Posts Query Control', 'pt-addons' ),
				],
				'default' => 'posts',
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
				'options' => \novo_get_post_items('pt-music-album'),
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
				'options' => \novo_get_post_items('pt-music-album'),
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'include' => 'manual_selection',
					'sort_type!' => [
						'by_id',
					],
				],
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab' => $include_wrapper,
				'export' => false,
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
				],
				'condition' => [
					'sort_type!' => [
						'by_id',
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
				'options' => \novo_get_post_items('pt-music-album'),
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'exclude' => 'manual_selection',
					'sort_type!' => [
						'by_id',
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
				'options' => [
					'post__in'  => esc_html__( 'Default', 'pt-addons' ),
					'author'  => esc_html__( 'Author', 'pt-addons' ),
					'date'  => esc_html__( 'Date', 'pt-addons' ),
					'ID'  => esc_html__( 'ID', 'pt-addons' ),
					'title'  => esc_html__( 'Title', 'pt-addons' ),
				],
				'default' => 'ID',
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'pt-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'DESC'  => esc_html__( 'Descending order', 'pt-addons' ),
					'ASC'  => esc_html__( 'Ascending order', 'pt-addons' ),
				],
				'default' => 'DESC',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'pagination',
			[
				'label' => __( 'Pagination', 'pt-novo' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
			'customizing',
			[
				'label' => esc_html__( 'Customizing', 'pt-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumb',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_responsive_control(
			'image_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio (%)', 'pt-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .blog-item .img a' => 'padding-bottom: {{SIZE}}%;',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function set_css_classes() {
		$settings = $this->settings;
		$posts = $this->posts_array;

		$this->add_render_attribute('block', 'class', [
			'portfolio-items',
			'elementor-block',
			'music-albums',
			'row',
			$this->wrap_id
		]);
	}

	protected function render() {
		$this->settings = $settings = $this->get_settings_for_display();
		$posts_array = $this->posts_array = $this->get_items_array();

		$this->wrap_id = 'portfolio-'. uniqid();

		$this->set_css_classes();

		if ( ! $posts_array->have_posts() ) return false;

		ob_start(); ?>

		<div <?php echo $this->get_render_attribute_string('block') ?>>
			<?php $this->render_items( $posts_array ) ?>
		</div>
    <?php $this->get_navigation_html( $this->settings['loading_mode'] ); ?>

		<?php

        echo ob_get_clean();
		wp_reset_postdata();
	}

	protected function get_source_categories() {
		if($this->settings['source'] == 'categories' && $this->settings['categories']) {
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
		if($this->settings['source'] == 'items' && $this->settings['items']) {
			return $this->settings['items'];
		}

		return false;
	}

	protected function get_items_array() {

		$query_args = array(
			'paged' => $this->paged,
			'orderby' => $this->settings['orderby'],
			'order' => $this->settings['order'],
			'post_type' => 'pt-music-album',
			'post_status' => 'publish',
		);

		$query_args['posts_per_page'] = (int) $this->get_posts_per_page( $this->settings[ 'loading_mode' ], $this->settings );

		// IDs.
		$this->set_ids_query_args( $query_args );

		$this->set_include_query_args( $query_args );

		$this->set_exclude_query_args( $query_args );

		return new \WP_Query($query_args);
	}

	protected function set_ids_query_args( &$query_args ) {

		if ( $this->settings['sort_type'] == 'by_id' && $this->settings['posts_ids'] ) {
			$query_args['post__in'] = $this->settings['posts_ids'];
		}
	}

	protected function set_include_query_args( &$query_args ) {

		$query_type = $this->settings[ 'sort_type'];

		if ( in_array( $query_type, [ 'by_id' ], true ) ) {
			return;
		}

		if ( empty( $this->settings[ 'include' ] ) || empty( $this->settings[ 'include_ids' ] ) ) {
			return;
		}

		$post__in = [];

		$post__in = array_merge( $post__in, $this->settings[ 'include_ids' ] );

		$query_args['post__in'] = empty( $query_args['post__in'] ) ? $post__in : array_merge( $query_args['post__in'], $post__in );
	}

	protected function set_exclude_query_args( &$query_args ) {
		if ( empty( $this->settings[ 'exclude' ] ) ) {
			return;
		}

		$post__not_in = [];

		if ( in_array( 'manual_selection', $this->settings[ 'exclude' ] ) && ! empty( $this->settings[ 'exclude_ids' ] ) ) {
			$post__not_in = array_merge( $post__not_in, $this->settings[ 'exclude_ids' ] );
		}

		$query_args['post__not_in'] = empty( $query_args['post__not_in'] ) ? $post__not_in : array_merge( $query_args['post__not_in'], $post__not_in );
	}

	protected function get_loadmore_array() {

		$settings = $this->settings;

		$query_args = array(
			'paged' => $this->paged,
			'orderby' => $this->settings['orderby'],
			'order' => $this->settings['order'],
			'post_type' => 'pt-music-album',
			'post_status' => 'publish',
		);

		$query_args['posts_per_page'] = -1;

		// IDs.
		$this->set_ids_query_args( $query_args );

		$this->set_include_query_args( $query_args );

		// Exclude.
		$this->set_exclude_query_args( $query_args );

		$query = new \WP_Query($query_args);

		if ( ! isset( $query->posts ) || empty( $query->posts ) ) {
			return [];
		}

		foreach ( $query->posts as $key => $post ) {
			$loadmore_array[ $key ] = [
				'id' => $post->ID
			];
		}

		return $loadmore_array;
	}

	protected function set_post_object($post_id, $index) {
		$this->post = new \YPRM_Get_Post([
			'id' => $post_id,
			'thumb_size' => $this->settings['thumb_size']
		]);
	}

	protected function set_post_css($index) {
		$settings = $this->settings;
		$block_setting_key = $this->get_repeater_setting_key( 'post', 'items', $index );
		$cols = yprm_el_cols($settings);
		
		$this->add_render_attribute($block_setting_key, 'class', [
			'portfolio-item',
			$cols
		]);

		if ( $this->post->has_thumb() ) {
			$this->add_render_attribute($block_setting_key, 'class', 'with-image' );
		}

		return $block_setting_key;
	}

	protected function render_items( $query ) {

		$key = 0;
		while ( $query->have_posts() ) :
			$query->the_post();
			$key++;
			echo $this->render_post( get_the_ID(), $key );
		endwhile;

		woocommerce_reset_loop();
		wp_reset_postdata();
	}

	protected function render_post($post_id, $index) {
		$this->set_post_object($post_id, $index);
		$block_setting_key = $this->set_post_css($index);

		ob_start(); ?>

		<article <?php echo $this->get_render_attribute_string($block_setting_key) ?>>
			<div class="wrap">
				<?php $this->get_post_featured_image() ?>
			</div>
		</article>
		<?php return ob_get_clean();
	}

	protected function get_post_featured_image() {
		if(!$this->post->has_thumb() ) return;

		ob_start(); ?>
			<div data-original="<?php echo esc_url( $this->post->get_image_original()[0] ); ?>" class="a-img">
				<?php echo $this->post->get_image_html(); ?>
			</div>
			<a href="<?php echo $this->post->get_permalink(); ?>"><span></span></a>
		<?php echo ob_get_clean();
	}

	public function loadmore() {
		$this->settings = $atts = $_POST['atts'];
		$array = $_POST['array'];
		$start_index = $_POST['start_index'];
		$ids = [];

		if ( is_array( $array ) && count( $array ) > 0 ) {
			foreach( $array as $item ) {
				if ( ! isset( $item['id'] ) || empty( $item['id'] ) ) {
					continue;
				}
				
				$post = get_post( $item['id'] );

				if ( ! $post ) {
					continue;
				}

				echo $this->render_post( $post->ID, $start_index++ );
			}
		} else {
			echo array(
				'return' => 'error'
			);
		}
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

	protected function display_load_more_button( $loadmore_array, $max_num_pages ) {
		?>

			<div class="load-button tac">
				<a href="#" data-wrap=".<?php echo esc_attr( $this->wrap_id ); ?>" data-max="<?php echo esc_attr( $max_num_pages ); ?>" data-start-page="<?php echo $this->paged; ?>" data-next-link="<?php echo esc_url( next_posts($max_num_pages, false ) ); ?>" class="button-style1 gray">
					<span><?php echo $this->get_settings_for_display( 'pagination_load_more_text' ); ?></span>
				</a>
			</div>
		<?php
	}

	protected function get_navigation_html( $loading_mode ) {
		$max_num_pages = $this->posts_array->max_num_pages;
		$loadmore_array = array_slice($this->get_loadmore_array(), $this->get_posts_per_page( $this->settings['loading_mode'], $this->settings ) );
		$loadmore_array = json_encode($loadmore_array);

		if ( 'standard' === $loading_mode ) {
			if (function_exists( 'yprm_wp_corenavi' ) ) {
				echo yprm_wp_corenavi( $max_num_pages );
			} else {
				wp_link_pages();
			}

		} elseif ( 'js_more' === $loading_mode && $max_num_pages > 1 ) {
			$this->display_load_more_button( $loadmore_array, $max_num_pages );
		}
	}
}