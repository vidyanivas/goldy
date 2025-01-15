<?php
// Enqueue parent theme styles
function twentytwentyone_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
}
add_action('wp_enqueue_scripts', 'twentytwentyone_child_enqueue_styles');
// Include the WP Bootstrap Navwalker class
//require_once get_stylesheet_directory() . '/inc/class-wp-bootstrap-navwalker.php';

function register_primary_menu() {
	register_nav_menu( 'primary', __( 'Primary Menu' ) );
}
add_action( 'after_setup_theme', 'register_primary_menu' );

function theme_enqueue_styles() {
	wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
	wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


