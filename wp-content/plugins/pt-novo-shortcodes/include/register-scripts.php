<?php

/* Scripts */
wp_deregister_script('wpb_composer_front_js');
wp_register_script('wpb_composer_front_js', plugins_url('pt-novo-shortcodes') . '/assets/js/js_composer_front.min.js', array('jquery'), '1.0.0', true);

wp_register_script('pt-admin-script', plugins_url('pt-novo-shortcodes') . '/assets/js/admin.js', array('jquery'), '1.0.0', true);
wp_register_script('pt-scripts', plugins_url('pt-novo-shortcodes') . '/assets/js/pt-scripts.js', array('jquery'), '1.0.0', true);

wp_register_script('isotope', plugins_url('pt-novo-shortcodes') . '/assets/js/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
wp_register_script('background-video', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.background-video.js', array('jquery'), null, true);
wp_register_script('countdown', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.countdown.js', array('jquery'), '1.0', true);
wp_register_script('flipster', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.flipster.min.js', array('jquery'), null, true);
wp_register_script('justified-gallery', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.justifiedGallery.js', array('jquery'), '', true);
wp_register_script('scrollbar', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.scrollbar.min.js', array('jquery'), '0.2.10', true);
wp_register_script('owl-carousel', plugins_url('pt-novo-shortcodes') . '/assets/js/owl.carousel.min.js', array('jquery'), '2.3.4', true);
wp_register_script('owl-linked', plugins_url('pt-novo-shortcodes') . '/assets/js/owl.linked.js', array('owl-carousel'), '1.0.0', true);
wp_register_script('video', plugins_url('pt-novo-shortcodes') . '/assets/js/video.js', array('jquery'), '7.3.0', true);
wp_register_script('circle-progress', plugins_url('pt-novo-shortcodes') . '/assets/js/circle-progress.min.js', array('jquery'), '1.2.2', true);
wp_register_script('swiper11', plugins_url('pt-novo-shortcodes') . '/assets/js/swiper-bundle.min.js', array('jquery', 'pt-scripts'), '11.1.15', true);
wp_register_script('parallax', plugins_url('pt-novo-shortcodes') . '/assets/js/parallax.min.js', array('jquery'), null, true);
wp_register_script('touch-swipe', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.touchSwipe.min.js', array('jquery'), '1.6.18', true);
wp_register_script('textfill', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.textfill.min.js', array('jquery'), '0.6.2', true);
wp_register_script('sticky-kit', plugins_url('pt-novo-shortcodes') . '/assets/js/jquery.sticky-kit.min.js', array('jquery'), '1.1.2', true);
wp_register_script('typed', plugins_url('pt-novo-shortcodes') . '/assets/js/typed.min.js', array('jquery'), '2.0.9', true);
wp_register_script('gsap', plugins_url('pt-novo-shortcodes') . '/assets/js/gsap.min.js', null, '3.12.5', true);
wp_register_script('draggable', plugins_url('pt-novo-shortcodes') . '/assets/js/Draggable.min.js', array('gsap'), '3.12.5', true);

wp_register_script('pt-load-posts', plugins_url('pt-novo-shortcodes') . '/assets/js/load-posts.js', array('jquery'), '0.6.2', true);
wp_register_script('pt-accordion', plugins_url('pt-novo-shortcodes') . '/assets/js/pt-accordion.js', array('jquery'), '1.0.0', true);
wp_register_script('pt-split-screen', plugins_url('pt-novo-shortcodes') . '/assets/js/pt-split-screen.js', array('jquery'), '1.0.0', true);
wp_register_script('pt-tabs', plugins_url('pt-novo-shortcodes') . '/assets/js/pt-tabs.js', array('jquery'), '1.0.0', true);
wp_register_script('pt-youtube-video', plugins_url('pt-novo-shortcodes') . '/assets/js/youtube-video.js', array('jquery'), '2.0.9', true);
wp_register_script('pixi', 'https://cdnjs.cloudflare.com/ajax/libs/pixi.js/5.1.3/pixi.min.js', null, '5.1.3', true);
wp_register_script('three.js', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r119/three.min.js', null, '5.1.3', true);
wp_register_script('pt-liquidSlider', plugins_url('pt-novo-shortcodes') . '/assets/js/liquidSlider.js', array('three.js', 'gsap', 'swiper'), '1.0.0', true);

if(function_exists('yprm_get_theme_setting') && !empty(yprm_get_theme_setting('google_maps_api_key'))) {

	$args = [
        'key' => yprm_get_theme_setting('google_maps_api_key'),
        'v' => 3,
        'callback' => 'Function.prototype',
        'loading' => 'async'
    ];

    wp_enqueue_script( 'pt-googleapis', sprintf( 'https://maps.googleapis.com/maps/api/js?%s', http_build_query( $args ) ), ['jquery'], '1.0.0', true);
}

/* Styles */
wp_register_style('pt-admin-style', plugins_url('pt-novo-shortcodes') . '/assets/css/admin.css', false, '1.0.0');
wp_register_style('pt-addons', plugins_url('pt-novo-shortcodes') . '/assets/css/pt-addons.css');
wp_register_style('pt-inline', plugins_url('pt-novo-shortcodes') . '/assets/css/pt-inline.css');
wp_register_style('pt-custom', plugins_url('pt-novo-shortcodes') . '/assets/css/custom.css', false);
wp_register_style('flipster', plugins_url('pt-novo-shortcodes') . '/assets/css/jquery.flipster.css', false, '1.1.2');
wp_register_style('justified-gallery', plugins_url('pt-novo-shortcodes') . '/assets/css/justifiedGallery.min.css', false, '3.6.3');
wp_register_style('owl-carousel', plugins_url('pt-novo-shortcodes') . '/assets/css/owl.carousel.css', false, '2.3.4');
wp_register_style('swiper', plugins_url('pt-novo-shortcodes') . '/assets/css/swiper.css' );