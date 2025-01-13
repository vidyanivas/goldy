<?php
/**
 * PT Novo Shortcodes - Addons for Novo Theme.
 *
 * @encoding     UTF-8
 * @version      4.0.21
 * @copyright    Copyright (C) 2011 - 2022 Promo Theme (https://promo-theme.com/). All rights reserved.
 * @license      Envato Standard Licenses (https://themeforest.net/licenses/standard)
 * @author       Promo Theme
 * @support      support@promo-theme.com
 **/

/** @noinspection AutoloadingIssuesInspection */

namespace Elementor; // TODO: Breaks of PSR standards. Migrate to "PromoTheme/Novo" namespace.

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}


class PT_Elementor {

	public function __construct() {

        /** Exit if Elementor not active. */
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) { return; }

		/** Include all widgets and control classes. */
		add_action( 'elementor/init', [ $this, 'includes' ] );

		require_once __DIR__ . '/functions.php'; // TODO: Abracadabra, refactor this!

        /** Register custom widget categories. */
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );

		/** Register custom controls to use in widgets. */
		add_action( 'elementor/controls/register', [ $this, 'init_controls' ] );

        /** Add custom scripts and styles to Elementor editor. */
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'after_enqueue_scripts' ] );

		/** Register custom Elementor widgets. */
		add_action( 'elementor/widgets/register', [ $this, 'widgets_registered' ], 1000 );

        /**
         * Enqueue all Elementor frontend styles.
         *
         * TODO: I'm not sure, Do we really need this? I suggest deleting, but more testing is needed.
         **/
		add_action( 'wp_enqueue_scripts', static function () {

			if ( class_exists( Frontend::class ) ) {
				Plugin::instance()->frontend->enqueue_styles();
			}
		} );

		/** Ajax handler for Portfolio Widget. */
		add_action( 'wp_ajax_loadmore_elementor_portfolio', [ $this, 'portfolio_loadmore' ] );
		add_action( 'wp_ajax_nopriv_loadmore_elementor_portfolio', [ $this, 'portfolio_loadmore' ] );

		/** Ajax handler for Blog Widget. */
		add_action( 'wp_ajax_loadmore_elementor_blog', [ $this, 'blog_loadmore' ] );
		add_action( 'wp_ajax_nopriv_loadmore_elementor_blog', [ $this, 'blog_loadmore' ] );

		/** Ajax handler for Products Widget. */
		add_action( 'wp_ajax_loadmore_elementor_products', [ $this, 'products_loadmore' ] );
		add_action( 'wp_ajax_nopriv_loadmore_elementor_products', [ $this, 'products_loadmore' ] );

	}

	/**
	 * Include all widgets and control classes.
	 *
	 * TODO: Refactor to use Autoloading Classes 'spl_autoload_register'
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function includes() {

		/** Include query helpers. */
		require_once __DIR__ . '/classes/shortcodes/product-query-interface.php';
		require_once __DIR__ . '/classes/shortcodes/products-query.php';

		/** Include custom Widgets. */
		$widget_dir   = __DIR__ . '/widgets';
		foreach ( glob( $widget_dir . '/*.php', GLOB_NOSORT ) as $widget ) {
			include_once $widget;
		}

		/** Include custom Controls. */
		require_once __DIR__ . '/controls/selectize.php';
		require_once __DIR__ . '/controls/gradient.php';
		require_once __DIR__ . '/controls/groups/link.php';
		require_once __DIR__ . '/controls/groups/cols.php';
		require_once __DIR__ . '/controls/groups/swiper.php';
		require_once __DIR__ . '/controls/groups/background_overlay.php';
		require_once __DIR__ . '/controls/groups/background_video.php';

	}

    /**
     * Register custom controls to use in widgets.
     *
     * @since 4.0.21
     * @access public
     *
     * @return void
     * @noinspection UnusedFunctionResultInspection
     **/
	public function init_controls() {

		/** Include all widgets and control classes. */
		$this->includes();

		/** Get controls manager to registering and initializing controls. */
		$controls_manager = Plugin::$instance->controls_manager;

		/** Register Gradient Control. */
		$controls_manager->register( new Gradient_Control(), Gradient_Control::GRADIENT );

		/** Register Selectize Control. */
		$controls_manager->register( new Selectize_Control(), Selectize_Control::SELECTIZE );

        /** Add custom Control Groups. */
		/** @noinspection UnusedFunctionResultInspection */
		$controls_manager->add_group_control( 'link', new Group_Control_Link() );
		$controls_manager->add_group_control( 'cols', new Group_Control_Cols() );
		$controls_manager->add_group_control( 'swiper', new Group_Control_Swiper() );
		$controls_manager->add_group_control( 'background_overlay', new Group_Control_Background_Overlay() );
		$controls_manager->add_group_control( 'background_video', new Group_Control_Background_Video() );

	}

	/**
	 * Register custom widget categories.
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function register_categories( $elements_manager ) {

		$elements_manager->add_category(
			'novo-elements',
			[
				'title' => esc_html__( 'Novo Elements', 'novo' ),
			]
		);

	}

	/**
	 * Register custom Elementor widgets.
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function widgets_registered() {

		/** Register widgets. */
        foreach( $this->get_widget_classes() as $class ) {
	        Plugin::instance()->widgets_manager->register( new $class() );
        }

	}

	/**
	 * Return class names for custom widgets.
	 *
	 * @since 4.0.21
	 * @access private
	 *
	 * @return array
	 **/
    private function get_widget_classes() {

	    /** @noinspection ClassConstantCanBeUsedInspection */
	    return [
	        '\Elementor\Elementor_Accordion_Widget',
	        '\Elementor\Elementor_Banner_Liquid_Slider_Widget',
	        '\Elementor\Elementor_Banner_Vertical_Widget',
	        '\Elementor\Elementor_Banner_Widget',
	        '\Elementor\Elementor_Blog_Widget',
	        '\Elementor\Elementor_Brands_Widget',
	        '\Elementor\Elementor_Button_Widget',
	        '\Elementor\Elementor_Categories_Slider_Widget',
	        '\Elementor\Elementor_Categories_Widget',
	        '\Elementor\Elementor_Decor_Elements_Widget',
	        '\Elementor\Elementor_Gallery_External_Link_Widget',
	        '\Elementor\Elementor_Gallery_Widget',
	        '\Elementor\Elementor_Google_MAP_Widget',
	        '\Elementor\Elementor_Heading_Block_Widget',
	        '\Elementor\Elementor_Icon_Box_Widget',
	        '\Elementor\Elementor_Image_Comparison_Slider_Widget',
	        '\Elementor\Elementor_Music_Album_Item_Widget',
	        '\Elementor\Elementor_Music_Albums_Widget',
	        '\Elementor\Elementor_Num_Box_Widget',
	        '\Elementor\Elementor_Photo_Carousel_Widget',
	        '\Elementor\Elementor_Podcast_Widget',
	        '\Elementor\Elementor_Portfolio_Widget',
	        '\Elementor\Elementor_Price_List_Type2_Widget',
	        '\Elementor\Elementor_Price_List_Widget',
	        '\Elementor\Elementor_Products_Banner_Widget',
	        '\Elementor\Elementor_Products_Widget',
	        '\Elementor\Elementor_Side_Image_Widget',
	        '\Elementor\Elementor_Skills_Widget',
	        '\Elementor\Elementor_Split_Screen_Type2_Widget',
	        '\Elementor\Elementor_Split_Screen_Widget',
	        '\Elementor\Elementor_Tabs_Widget',
	        '\Elementor\Elementor_Team_Widget',
	        '\Elementor\Elementor_Testimonials_Widget',
	        '\Elementor\Elementor_Video_Banner_Widget',
	        '\Elementor\Elementor_Video_Widget',
        ];

    }

	/**
	 * Add custom scripts and styles to Elementor editor.
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function after_enqueue_scripts() {

        /** Url to assets folder. */
        $assets_url = plugins_url( 'pt-novo-shortcodes' ) . '/assets';

        /** Enqueue style.css. */
		wp_enqueue_style( 'pt-el-icons', $assets_url . '/css/pt-el-icons/style.css' );

		/** Enqueue admin.js. */
		wp_enqueue_script( 'pt-admin', $assets_url .'/js/admin.js', [ 'jquery' ], '4.0.21', true );

	}

	/**
	 * Ajax handler for Portfolio Widget.
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function portfolio_loadmore() {

		/** Instantiate Portfolio Widget. */
		$portfolio = new Elementor_Portfolio_Widget();

		/** Show next portion of content. */
		$portfolio->loadmore();

		/** Terminate immediately and return a proper ajax response. */
		wp_die();

	}

	/**
	 * Ajax handler for Blog Widget.
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function blog_loadmore() {

		/** Instantiate Blog Widget. */
		$blog = new Elementor_Blog_Widget();

		/** Show next portion of content. */
		$blog->loadmore();

		/** Terminate immediately and return a proper ajax response. */
		wp_die();

	}

	/**
	 * Ajax handler for Products Widget.
	 *
	 * @since 4.0.21
	 * @access public
	 *
	 * @return void
	 **/
	public function products_loadmore() {

        /** Instantiate Products Widget. */
		$products = new Elementor_Products_Widget();

        /** Show next portion of content. */
		$products->loadmore();

        /** Terminate immediately and return a proper ajax response. */
		wp_die();

	}

} // End class PT_Elementor.

/** Instantiate class after including file on 'plugins_loaded' hook. */
new PT_Elementor();
