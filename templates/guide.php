<?php

/**
 * Template Name: Guide
 * Template Post Type: post
 */

/**
 * Force content-sidebar layout setting.
 * Sidebar is swapped out in /includes/guides.php.
 */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );

add_action( 'genesis_sidebar', function() {

	// $meta = get_post_meta( get_the_ID() );
	// vd( $meta );

	// $guides = get_post_meta( get_the_ID(), 'guides', true );

	// vd( $guides );

});

genesis();
