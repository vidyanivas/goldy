<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    5.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="categories">', '</div>' );

echo '<div class="product-top">';
  the_title( '<h1 class="product_title entry-title h4">', '</h1>' );
  if(shortcode_exists('ti_wishlists_addtowishlist')) {
    echo do_shortcode('[ti_wishlists_addtowishlist]');
  }
echo '</div>';