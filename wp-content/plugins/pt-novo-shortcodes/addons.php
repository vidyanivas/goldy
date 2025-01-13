<?php
/**
 * Plugin Name: PT Novo Shortcodes
 * Description: Addons for Novo Theme
 * Version:     4.3.1
 * Author:      Promo Theme
 * Author URI:  https://promo-theme.com/
 * Text Domain: pt-addons
 */

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('YPRM_Novo_Addons')) {
  class YPRM_Novo_Addons {

    function __construct() {
      add_action('init', array($this, 'i18n'));
      add_action( 'init', array($this, 'move_subcat_lis') );
      add_action('plugins_loaded', array($this, 'init'));

      $this->include_plugins();

      add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
      add_action('admin_enqueue_scripts', array($this, 'register_scripts'));

      add_action('admin_enqueue_scripts', array($this, 'enqueue_back'), 400);
      add_action('wp_enqueue_scripts', array($this, 'enqueue_front'), 2200);

      add_action('wp_head', array($this, 'addOGMetaTags'));
      add_action('admin_footer', array($this, 'fix_pixproof'), 100050);

      if(get_option('enable_full_version') && class_exists('WPBakeryShortCode')) {
        add_action('vc_before_init', array($this, 'load_params'));
        add_action('plugins_loaded', array($this, 'load_shortcodes'));
        add_action('vc_before_init', array($this, 'vc_templates'));
        add_action('vc_before_init', array($this, 'vc_edit'));
      } else {
        add_action('admin_menu', function() {
          remove_submenu_page( 'tools.php', 'fw-backups-demo-content' );
        }, 99099);
      }
      add_action('init', array($this, 'el_icons'));
      add_action('wp_ajax_load_project', array($this, 'load_project'));
      add_action('wp_ajax_nopriv_load_project', array($this, 'load_project'));

      define('YPRM_PLUGINS_URL', dirname(__FILE__));
      define('YPRM_PLUGINS_HTTP', plugins_url('pt-novo-shortcodes'));
    }

    public function i18n() {
      load_plugin_textdomain('pt-addons');
    }

    public function el_icons() {
      
      if(!class_exists('WPBakeryShortCode')) {
        include_once dirname(__FILE__) . '/params/iconpicker.php';
      }
    }

    public function init() {
      if (!class_exists('WPBakeryShortCode') && !defined('ELEMENTOR_VERSION')) {
        add_action('admin_notices', array($this, 'admin_notice'));
        return;
      }
      

      add_image_size( 'yprm-lazyloading-placeholder', '70', '70');

      include_once dirname(__FILE__) . '/include/hooks.php';
      include_once dirname(__FILE__) . '/include/typekit.php';
      include_once dirname(__FILE__) . '/include/zilla-likes.php';
      include_once dirname(__FILE__) . '/include/vc-snippets.php';
      include_once dirname(__FILE__) . '/include/video-parser.php';
      include_once dirname(__FILE__) . '/include/hooks.php';
      include_once dirname(__FILE__) . '/include/one-click-demo-import/one-click-demo-import.php';
      include_once dirname(__FILE__) . '/include/popup/popup.php';
      include_once dirname(__FILE__) . '/include/get-post.php';
      include_once dirname(__FILE__) . '/include/get-project.php';

      if ( ! get_option( 'enable_full_version' ) ) return;
      include_once dirname(__FILE__) . '/elementor/elementor.php';
      include_once dirname(__FILE__) . '/redux-extensions/loader.php';
      
      self::widgets();
    }

    public function include_plugins() {
			include_once dirname(__FILE__) . '/include/header-builder/header-builder.php';
			include_once dirname(__FILE__) . '/include/footer-builder/footer-builder.php';
		}

    public function admin_notice() {
      if (isset($_GET['activate'])) {
        unset($_GET['activate']);
      }

      $message = sprintf(
        esc_html__('"%1$s" requires "%2$s" or "%3$s" to be installed and activated.', 'pt-addons'),
        '<strong>' . esc_html__('PT Novo Addons', 'pt-addons') . '</strong>',
        '<strong>' . esc_html__('WPBakery Page Builder', 'pt-addons') . '</strong>',
        '<strong>' . esc_html__('Elementor Website Builder', 'pt-addons') . '</strong>'
      );

      printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function register_scripts() {
      include_once dirname(__FILE__) . '/include/register-scripts.php';
    }

    public function addOGMetaTags() {
      ?>
      <meta property="og:title" content="<?php echo the_title() ?>" />
      <?php if($image = get_the_post_thumbnail_url(null, 'large')) {
        ?>
          <meta property="og:image" content="<?php echo $image ?>" />
        <?php
      }
    }

    public function enqueue_front() {
      yprm_enqueue_fonts();

      wp_dequeue_script('wpb_composer_front_js');
      wp_enqueue_script('wpb_composer_front_js');

      wp_enqueue_script('imagesloaded');
      wp_enqueue_script('isotope');
      wp_enqueue_script('pt-scripts');
      wp_localize_script('pt-scripts', 'yprm_ajax',
        array(
          'url' => admin_url('admin-ajax.php'),
        )
      );

      wp_enqueue_style('vc_font_awesome_5_shims');
      wp_enqueue_style('pt-addons');
      wp_enqueue_style('novo-main-mobile', get_parent_theme_file_uri() . '/css/mobile.css');
    }

    public function enqueue_back() {
      wp_enqueue_style('gf-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
      wp_enqueue_script('pt-admin-script');
      wp_localize_script('pt-admin-script', 'yprm_ajax',
        array(
          'url' => admin_url('admin-ajax.php'),
        )
      );

      //wp_dequeue_style('font-awesome');
      wp_enqueue_style('fontawesome');
      wp_enqueue_style('pt-admin-style');
    }

    public function fix_pixproof() {
      wp_dequeue_script('cmb2-conditionals');
    }

    public function load_params() {
      $dir = dirname(__FILE__) . '/params';
      $files = glob($dir . '/*.php');
      foreach ($files as $file) {
        include_once $file;
      }
    }

    public function load_shortcodes() {
      $dir = dirname(__FILE__) . '/shortcodes';
      $files = glob($dir . '/*.php');
      foreach ($files as $file) {
        include_once $file;
      }
    }

    public function vc_templates() {
      vc_set_shortcodes_templates_dir(plugin_dir_path(__FILE__) . 'vc_templates');
    }

    public function vc_edit() {
      include_once dirname(__FILE__) . '/include/vc-edit.php';
    }

    public function load_project() {
      $item = get_post($_POST['id']);
    }

    public function widgets() {
      $dir = dirname(__FILE__) . '/widgets';
      $files = glob($dir . '/*.php');
      foreach ($files as $file) {
        include_once $file;
      }
    }

    public function move_subcat_lis() {
      remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
      add_action( 'woocommerce_before_shop_loop', array( $this, 'yprm_product_loop_start' ), 40 );
      add_action( 'woocommerce_before_shop_loop', array( $this, 'yprm_maybe_show_product_subcategories' ), 50 );
      add_action( 'woocommerce_before_shop_loop', array( $this, 'yprm_product_loop_end' ), 60 );
    }

    public function yprm_product_loop_start() {
      $subcategories = woocommerce_maybe_show_product_subcategories();
      if ( $subcategories ) {
        echo '<div class="clear"></div><div class="woocommerce-filter-button filter-button-group">';
      }
    }

    public function yprm_maybe_show_product_subcategories() {
      echo woocommerce_maybe_show_product_subcategories();
    }

    public function yprm_product_loop_end() {
      $subcategories = woocommerce_maybe_show_product_subcategories();
      if ( $subcategories ) {
        echo '</div>';
      }
    }
  }

  new YPRM_Novo_Addons();
}
