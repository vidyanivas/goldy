<?php

/*
Plugin Name: One Click Demo Import
Plugin URI: https://wordpress.org/plugins/one-click-demo-import/
Description: Import your content, widgets and theme settings with one click. Theme authors! Enable simple demo import for your theme demo data.
Version: 2.5.2
Author: ProteusThemes
Author URI: https://www.proteusthemes.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl.html
*/

defined('ABSPATH') or die('No script kiddies please!');

class YPRM_Demo_Plugin {

	public function __construct() {

		if (version_compare(phpversion(), '5.3.2', '<')) {
			add_action('admin_notices', array($this, 'old_php_admin_error_notice'));
		} else {
			$this->set_plugin_constants();

			require_once PT_OCDI_PATH. '/vendor/autoload.php';
			
			$pt_one_click_demo_import = OCDI\OneClickDemoImport::get_instance();

			if (defined('WP_CLI') && WP_CLI) {
				WP_CLI::add_command('ocdi list', array('OCDI\WPCLICommands', 'list_predefined'));
				WP_CLI::add_command('ocdi import', array('OCDI\WPCLICommands', 'import'));
			}
		}
	}

	public function old_php_admin_error_notice() {
		$message = sprintf(esc_html__('The %2$sOne Click Demo Import%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'ocdi'), phpversion(), '<strong>', '</strong>', '<br>');

		printf('<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post($message));
	}
	
	private function set_plugin_constants() {

		if (!defined('PT_OCDI_PATH')) {
			define('PT_OCDI_PATH', plugin_dir_path(__FILE__));
		}
		if (!defined('PT_OCDI_URL')) {
			define('PT_OCDI_URL', plugin_dir_url(__FILE__));
		}

		add_action('admin_init', array($this, 'set_plugin_version_constant'));
	}

	public function set_plugin_version_constant() {
		if (!defined('PT_OCDI_VERSION')) {
			$plugin_data = get_plugin_data(__FILE__);
			define('PT_OCDI_VERSION', $plugin_data['Version']);
		}
	}
}

$ocdi_plugin = new YPRM_Demo_Plugin();

function ocdi_get_pages_array() {
	return array(
    'full-dark' => array(
      'title' => 'Full Demo (Dark version)',
      'url' => 'one-page/',
      'has_elementor' => true,
      'category' => array('Full'),
    ),
    'full-white' => array(
      'title' => 'Full Demo (White version)',
      'url' => 'one-page-white/',
      'has_elementor' => true,
      'category' => array('Full'),
    ),
    'home' => array(
      'title' => 'One Page',
      'url' => 'one-page/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-agency' => array(
      'title' => 'Agency',
      'url' => 'home-agency/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-agency-white' => array(
      'title' => 'Agency White',
      'url' => 'home-agency-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-business' => array(
      'title' => 'Business',
      'url' => 'home-business/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-business-white' => array(
      'title' => 'Business White',
      'url' => 'home-business-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-categories' => array(
      'title' => 'Home Categories',
      'url' => 'home-categories/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-categories-white' => array(
      'title' => 'Home Categories White',
      'url' => 'home-categories-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-freelancer' => array(
      'title' => 'Freelancer',
      'url' => 'home-freelancer/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-freelancer-white' => array(
      'title' => 'Freelancer White',
      'url' => 'home-freelancer-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-photographer' => array(
      'title' => 'Photographer',
      'url' => 'home-photographer/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-photographer-white' => array(
      'title' => 'Photographer White',
      'url' => 'home-photographer-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-portfolio' => array(
      'title' => 'Portfolio',
      'url' => 'home-portfolio/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-portfolio-white' => array(
      'title' => 'Portfolio White',
      'url' => 'home-portfolio-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-shop' => array(
      'title' => 'Shop',
      'url' => 'home-shop/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-shop-white' => array(
      'title' => 'Shop White',
      'url' => 'home-shop-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'home-showcase-wave' => array(
      'title' => 'Wave',
      'url' => 'home-showcase-wave/',
      'has_elementor' => false,
      'category' => array('Homepage'),
    ),
    'home-showcase-zoomin' => array(
      'title' => 'Zoom In',
      'url' => 'home-showcase-zoomin/',
      'has_elementor' => false,
      'category' => array('Homepage'),
    ),
    'home-showcase-zoomout' => array(
      'title' => 'Zoom Out',
      'url' => 'home-showcase-zoomout/',
      'has_elementor' => false,
      'category' => array('Homepage'),
    ),
    'home-white' => array(
      'title' => 'Home White',
      'url' => 'home-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'liquid-centered-slider' => array(
      'title' => 'Liquid Centered Slider',
      'url' => 'liquid-centered-slider/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'liquid-slider' => array(
      'title' => 'Liquid Slider',
      'url' => 'liquid-slider/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'liquid-slider-with-description' => array(
      'title' => 'Liquid Slider with Description',
      'url' => 'liquid-slider-with-description/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'liquid-slider-with-video' => array(
      'title' => 'Liquid Slider with Video',
      'url' => 'liquid-slider-with-video/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'musician' => array(
      'title' => 'Musician',
      'url' => 'musician/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'musician-white' => array(
      'title' => 'Musician White',
      'url' => 'musician-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'parallax-slider' => array(
      'title' => 'Parallax Slider',
      'url' => 'parallax-slider/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'showcase-with-thumbnails' => array(
      'title' => 'Showcase with Thumbnails',
      'url' => 'showcase-with-thumbnails/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'split-screen' => array(
      'title' => 'Split Screen',
      'url' => 'split-screen/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'split-screen-2' => array(
      'title' => 'Split Screen 2',
      'url' => 'split-screen-2/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'split-screen-white' => array(
      'title' => 'Split Screen White',
      'url' => 'split-screen-white/',
      'has_elementor' => true,
      'category' => array('Homepage', 'Portfolio'),
    ),
    'videographer' => array(
      'title' => 'Videographer',
      'url' => 'videographer/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
    'videographer-white' => array(
      'title' => 'Videographer White',
      'url' => 'videographer-white/',
      'has_elementor' => true,
      'category' => array('Homepage'),
    ),
		'about-me' => array(
      'title' => 'About me',
      'url' => 'about-me/',
      'has_elementor' => true,
      'category' => array('Pages'),
    ),
    'about-me-white' => array(
      'title' => 'About me White',
      'url' => 'about-me-white/',
      'has_elementor' => true,
      'category' => array('Pages'),
    ),
    'booking' => array(
      'title' => 'Booking',
      'url' => 'booking/',
      'has_elementor' => true,
      'category' => array('Pages'),
    ),
    'services' => array(
      'title' => 'Services',
      'url' => 'services/',
      'has_elementor' => true,
      'category' => array('Pages'),
    ),
    'services-white' => array(
      'title' => 'Services White',
      'url' => 'services-white/',
      'has_elementor' => true,
      'category' => array('Pages'),
    ),
    'blog-grid' => array(
      'title' => 'Blog Grid',
      'url' => 'blog-grid/',
      'has_elementor' => true,
      'category' => array('Blog'),
    ),
    'blog-horizontal' => array(
      'title' => 'Blog Horizontal',
      'url' => 'blog-horizontal/',
      'has_elementor' => true,
      'category' => array('Blog'),
    ),
    'blog-horizontal-with-sidebar' => array(
      'title' => 'Blog Horizontal with Sidebar',
      'url' => 'blog-horizontal-with-sidebar/',
      'has_elementor' => true,
      'category' => array('Blog'),
    ),
    'blog-masonry' => array(
      'title' => 'Blog Masonry',
      'url' => 'blog-masonry/',
      'has_elementor' => true,
      'category' => array('Blog'),
    ),
    'blog-with-sidebar' => array(
      'title' => 'Blog with Sidebar',
      'url' => 'blog-with-sidebar/',
      'has_elementor' => true,
      'category' => array('Blog'),
    ),
    'before-after-slider' => array(
      'title' => 'Before/After Slider',
      'url' => 'before-after-slider/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'gallery-external-link' => array(
      'title' => 'Gallery (External link)',
      'url' => 'gallery-external-link/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'carousel-type-1' => array(
      'title' => 'Carousel Type 1',
      'url' => 'gallery/carousel-type-1/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'carousel-type-2' => array(
      'title' => 'Carousel Type 2',
      'url' => 'gallery/carousel-type-2/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'projects-flow' => array(
      'title' => 'Flow',
      'url' => 'gallery/flow/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'projects-grid' => array(
      'title' => 'Grid',
      'url' => 'gallery/grid/col-3/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'projects-masonry' => array(
      'title' => 'Masonry',
      'url' => 'gallery/masonry/col-3/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'projects-horizontal' => array(
      'title' => 'Horizontal',
      'url' => 'gallery/horizontal/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'projects-scattered' => array(
      'title' => 'Scattered',
      'url' => 'gallery/scattered/',
      'has_elementor' => true,
      'category' => array('Portfolio'),
    ),
    'left-logo' => array(
      'title' => 'Left Logo',
      'url' => 'yprm_header_builder/left-logo/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'left-logo-white' => array(
      'title' => 'Left Logo White',
      'url' => 'yprm_header_builder/left-logo-white/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'center-logo' => array(
      'title' => 'Center Logo',
      'url' => 'yprm_header_builder/center-logo/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'center-logo-white' => array(
      'title' => 'Center Logo White',
      'url' => 'yprm_header_builder/center-logo-white/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'hidden-navigation' => array(
      'title' => 'Hidden Navigation',
      'url' => 'yprm_header_builder/hidden-navigation/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'hidden-navigation-white' => array(
      'title' => 'Hidden Navigation White',
      'url' => 'yprm_header_builder/hidden-navigation-white/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'fullscreen-navigation' => array(
      'title' => 'Fullscreen Navigation',
      'url' => 'yprm_header_builder/fullscreen-navigation/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'fullscreen-navigation-white' => array(
      'title' => 'Fullscreen Navigation White',
      'url' => 'yprm_header_builder/fullscreen-navigation-white/',
      'has_elementor' => false,
      'category' => array('Headers'),
    ),
    'footer-minified-wp-backery' => array(
      'title' => 'Footer Minified WP Backery',
      'url' => 'yprm_footer_builder/footer-minified-wp-backery/',
      'has_elementor' => false,
      'category' => array('Footers'),
    ),
    'footer-simple-wp-backery' => array(
      'title' => 'Footer Simple WP Backery',
      'url' => 'yprm_footer_builder/footer-simple-wp-backery/',
      'has_elementor' => false,
      'category' => array('Footers'),
    ),
    'footer-minified-elementor' => array(
      'title' => 'Footer Minified Elementor',
      'url' => 'yprm_footer_builder/footer-minified-elementor/',
      'has_elementor' => false,
      'category' => array('Footers'),
    ),
    'footer-simple-elementor' => array(
      'title' => 'Footer Simple Elementor',
      'url' => 'yprm_footer_builder/footer-simple-elementor/',
      'has_elementor' => false,
      'category' => array('Footers'),
    ),
    'contacts' => array(
      'title' => 'Contacts',
      'url' => 'contacts/',
      'has_elementor' => true,
      'category' => array('Contacts'),
    ),
    'contacts-2' => array(
      'title' => 'Contacts 2',
      'url' => 'contacts-2/',
      'has_elementor' => true,
      'category' => array('Contacts'),
    ),
    'blog-samples' => array(
			'title' => esc_html__('Blog Samples', 'pt-addons'),
			'category' => array('Content Samples'),
		),
		'project-samples' => array(
			'title' => esc_html__('Project Samples', 'pt-addons'),
			'category' => array('Content Samples'),
		),
		'product-samples' => array(
			'title' => esc_html__('Product Samples', 'pt-addons'),
			'category' => array('Content Samples'),
		),
		'album-samples' => array(
			'title' => esc_html__('Music Album Samples', 'pt-addons'),
			'category' => array('Content Samples'),
		),
		'cf-contact-form' => array(
			'title' => esc_html__('Contact Form', 'pt-addons'),
			'category' => array('Contact Forms'),
		),
		'cf-drop-me-a-line' => array(
			'title' => esc_html__('Drop Me a Line', 'pt-addons'),
			'category' => array('Contact Forms'),
		),
		'cf-subscribe' => array(
			'title' => esc_html__('Subscribe Form', 'pt-addons'),
			'category' => array('Contact Forms'),
		),
	);
}

function ocdi_get_contact_form($key) {
	$contact_form = '';
	
	

	return $contact_form;
}

function ocdi_get_header($key) {
	$header = 'left-logo';

	if(
    $key == 'fullscreen-menu'
  ) {
    $header = 'fullscreen-navigation';
  }
  if(
    $key == 'hidden-menu'
  ) {
    $header = 'hidden-navigation';
  }
  
  if(
    $key == 'home-photographer-elementor' ||
    $key == 'home-photographer'
  ) {
    $header = 'center-logo';
  }
  
  if(
    $key == 'home-photographer-white-elementor' ||
    $key == 'home-photographer-white'
  ) {
    $header = 'center-logo-white';
  }

  if(
    $key == 'home-portfolio' ||
    $key == 'home-portfolio-white' ||
    $key == 'home-portfolio-white-elementor' ||
    $key == 'home-portfolio-elementor' ||
    $key == 'side-menu'
  ) {
    //$header = 'side';
  }

	return $header;
}

function ocdi_get_footer($key) {
  // footer-minified-elementor
  //footer-minified-wp-backery
  //footer-simple-elementor
  //footer-simple-wp-backery

	/* if (
		$key == 'about-me' ||
		$key == 'app-mobile-saas' ||
		$key == 'contact-advanced-form' ||
		$key == 'creative-bureau' ||
		$key == 'creativity' ||
		$key == 'creators' ||
		$key == 'digital-studio' ||
		$key == 'marketing-corporate' ||
		$key == 'photo-videography' ||
		$key == 'services'
	) {
		return 'footer-simple-elementor';
	} */

	return 'footer-simple';
}

function ocdi_has_posts($key) {
	if(
		$key == 'home-freelancer' ||
		$key == 'home-freelancer-white' ||
		$key == 'about-me' ||
		$key == 'about-me-white'
	) {
		return true;
	} else {
		return false;
	}
}

function ocdi_has_projects($key) {
	if(
		$key == 'home-photographer' ||
		$key == 'home-photographer-white' ||
		$key == 'home-business' ||
		$key == 'home-business-white' ||
		$key == 'home-portfolio' ||
		$key == 'home-portfolio-white' ||
		$key == 'home-agency' ||
		$key == 'home-agency-white' ||
		$key == 'home-freelancer' ||
		$key == 'home-freelancer-white' ||
		$key == 'videographer' ||
		$key == 'videographer-white'
	) {
		return true;
	} else {
		return false;
	}
}

function ocdi_has_products($key) {
	if(
		$key == 'home-shop' ||
		$key == 'home-shop-white'
	) {
		return true;
	} else {
		return false;
	}
}

function ocdi_has_albums($key) {
	if(
		$key == 'musician' ||
		$key == 'musician-white'
	) {
		return true;
	} else {
		return false;
	}
}

function ocdi_has_widgets($key) {
	if(
		$key == 'blog-horizontal-with-sidebar' ||
		$key == 'blog-grid-with-sidebar' ||
		$key == 'blog-masonry-with-sidebar' ||
		$key == 'full-white' ||
		$key == 'full-dark'
	) {
		return true;
	} else {
		return false;
	}
}

function ocdi_import_files() {
	$pages_array = ocdi_get_pages_array();

	$result_array = array();

	foreach($pages_array as $key => $page) {
		$preview_url = 'https://promo-theme.com/novo/'.$key;
		$import_redux = null;
		$has_posts = ocdi_has_posts($key);
		$has_projects = ocdi_has_projects($key);
		$has_products = ocdi_has_products($key);
		$has_albums = ocdi_has_albums($key);
		$has_widgets = ocdi_has_widgets($key);
		$has_contact_forms = false;
		$has_header = true;
		$has_footer = true;
		$has_media_files = true;
		$contact_form = ocdi_get_contact_form($key);
		$header = ocdi_get_header($key);
		$footer = ocdi_get_footer($key);

		if(isset($page['url'])) {
			if($page['url']) {
				$preview_url = $page['url'];
			} else {
				$preview_url = false;
			}
		}

		$import_redux = array(
			array(
				'file_url' => 'https://updates.promo-theme.com/demo-contents/novo/new_pages/redux.json',
				'option_name' => 'novo_theme',
			),
		);

    if($key == 'full-white') {
      $import_redux = array(
        array(
          'file_url' => 'https://updates.promo-theme.com/demo-contents/novo/new_pages/redux-white.json',
          'option_name' => 'novo_theme',
        ),
      );
    }

		$import_widget_file_url = 'https://updates.promo-theme.com/demo-contents/novo/new_pages/widgets.json';

		if(!$has_widgets) {
			$import_widget_file_url = '';
		}

		if(
			strpos($key, 'header') !== false ||
			strpos($key, 'footer') !== false ||
			strpos($key, 'contact-form') !== false ||
			strpos($key, 'samples') !== false ||
			strpos($key, 'navigation') !== false ||
			strpos($key, 'cf-') !== false
		) {
			$preview_url = false;
		}

		if(isset($page['url']) && $page['url']) {
			$preview_url = 'https://promo-theme.com/novo/'.$page['url'];
		}

		if($contact_form) {
			$has_contact_forms = true;
		}

		if(
			$page['category'][0] == 'Headers' ||
			$page['category'][0] == 'Footers' ||
			$page['category'][0] == 'Contact Forms' ||
			$page['category'][0] == 'Content Samples'
		) {
			$has_header = $has_footer = $has_media_files = $has_contact_forms = false;
			$header = $footer = $contact_form = '';
		} else if($page['category'][0] == 'Full') {
			$has_header = $has_footer = $has_media_files = $has_contact_forms = false;
			$header = $footer = $contact_form = '';
		}
		

		array_push($result_array, array(
			'import_file_name' => $page['title'],
      'slug' => $key,
			'categories' => $page['category'],
			'has_elementor' => isset($page['has_elementor']) ? $page['has_elementor'] : false,
			'import_file_url' => array(
				'content' => 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$key.'/xml.xml',
				'elementor_content' => 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$key.'/elementor-xml.xml',
				'header' => $header ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$header.'/xml.xml' : '',
				'media_files' => 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$key.'/media.xml',
				'projects' => $has_projects ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/project-samples/xml.xml' : '',
				'posts' => $has_posts ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/blog-samples/xml.xml' : '',
				'albums' => $has_albums ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/album-samples/xml.xml' : '',
				'products' => $has_products ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/product-samples/xml.xml' : '',
				'contact_forms' => $contact_form ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$contact_form.'/xml.xml' : '',
        'footer' => $footer ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$footer.'-wp-backery/xml.xml' : '',
        'footer-elementor' => $footer ? 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$footer.'-elementor/xml.xml' : '',
			),
			'import_preview_image_url' => 'https://updates.promo-theme.com/demo-contents/novo/new_pages/'.$key.'/screenshot.png',
			'preview_url' => $preview_url,
			'import_widget_file_url' => $import_widget_file_url,
			'import_redux' => $import_redux,
			'posts' => $has_posts,
			'projects' => $has_projects,
			'products' => $has_products,
			'albums' => $has_albums,
			'widgets' => $has_widgets,
			'contact_forms' => $has_contact_forms,
			'header' => $has_header,
			'footer' => $has_footer,
			'media_files' => $has_media_files
		));
	}

	return $result_array;
}

if(get_option('enable_full_version')) {
	add_filter('ocdi/import_files', 'ocdi_import_files');
}

function pt_ocdi_after_import_setup($atts) {

  if(function_exists('vc_path_dir')) {
    require_once vc_path_dir('SETTINGS_DIR', 'class-vc-roles.php');
    if(class_exists('Vc_Roles')) {
      $vc_roles = new Vc_Roles();
      $vc_roles->save(array(
        'administrator' => array(
          'post_types' => array(
            '_state' => 'custom',
            'post' => 1,
            'page' => 1,
            'yprm_footer_builder' => 1,
            'pt-portfolio' => 1
          )
        )
      ));
    }
  }

  
  if ( did_action( 'elementor/loaded' ) ) {   
    // Deactivate Inline Font Icons
    update_option( 'elementor_experiment-e_font_icon_svg', 'inactive' );
}


// Custom Post Type Support Logic
$cpt_support = get_option('elementor_cpt_support');

if (!$cpt_support) {
    // Initialize with required custom post types
    $cpt_support = ['page', 'post', 'yprm_footer_builder', 'pt-portfolio'];
    update_option('elementor_cpt_support', $cpt_support);
} else {
    // Add custom post types if they don't exist
    if (!in_array('yprm_footer_builder', $cpt_support)) {
        $cpt_support[] = 'yprm_footer_builder';
    }
    if (!in_array('pt-portfolio', $cpt_support)) {
        $cpt_support[] = 'pt-portfolio';
    }
    update_option('elementor_cpt_support', $cpt_support);
}

    
  if($atts['import_file_name'] != 'Full White' && $atts['import_file_name'] != 'Full Dark') return false;

  $navigation = get_term_by( 'name', 'Navigation', 'nav_menu' );

  set_theme_mod( 'nav_menu_locations', array(
    'navigation' => $navigation->term_id
  ));

  $front_page_id = get_page_by_title( 'One Page' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
}
add_action( 'ocdi/after_import', 'pt_ocdi_after_import_setup' );