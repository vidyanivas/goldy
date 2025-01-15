<?php
/**
 * Template Name: Full Page Template
 *
 * @package Twenty Twenty-One
 */

get_header(); ?>

<style>
    /* Full width container */
    .content-area {
        width: 100%;
        max-width: none;
        margin: 0;
    }

    .site-main {
        padding: 0;
    }

    /* Remove any side padding or margins */
    .site {
        margin: 0;
        padding: 0;
    }
	section.wpb-content-wrapper {
    min-width: 100% !important;
}
h3.entry-title {
    font-size: 56px !important;
}
</style>
        <div class="entry-content">
		<?php
		the_title( '<h3 class="entry-title">', '</h3>' );
		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
	</div><!-- .entry-content -->



<?php get_footer(); ?>
