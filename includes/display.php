<?php

/**
 * Do the before guide content.
 *
 * @since   0.3.0
 * @return  void
 */
add_action( 'maiguides_before_content', 'maiguides_do_before_content' );
function maiguides_do_before_content() {

	// Get the main guide ID.
	$guide_id = maiguides_get_guide_id();

	// Bail if no guide ID.
	if ( ! $guide_id ) {
		return;
	}

	// Get the content.
	$content = trim( get_post_meta( $guide_id, 'guide_before_content', true ) );

	// Bail if no content.
	if ( ! $content ) {
		return;
	}

	// Send it!
	echo maiguides_get_processed_content( $content );
}

/**
 * Do the after guide content.
 *
 * @since   0.3.0
 * @return  void
 */
add_action( 'maiguides_after_content', 'maiguides_do_after_content' );
function maiguides_do_after_content() {

	// Get the main guide ID.
	$guide_id = maiguides_get_guide_id();

	// Bail if no guide ID.
	if ( ! $guide_id ) {
		return;
	}

	// Get the content.
	$content = trim( get_post_meta( $guide_id, 'guide_after_content', true ) );

	// Bail if no content.
	if ( ! $content ) {
		return;
	}

	// Send it!
	echo maiguides_get_processed_content( $content );
}

/**
 * Add hook before guide and guide entry content.
 *
 * @since   0.3.0
 * @return  void
 */
add_action( 'genesis_entry_content', 'maiguides_do_before_guide_content', 8 );
function maiguides_do_before_guide_content() {

	// Bail if no guide ID.
	if ( ! maiguides_get_guide_id() ) {
		return;
	}

	// Run hook!
	do_action( 'maiguides_before_content' );
}

/**
 * Add hook after guide and guide entry content.
 *
 * @since   0.3.0
 * @return  void
 */
add_action( 'genesis_entry_content', 'maiguides_do_after_guide_content', 12 );
function maiguides_do_after_guide_content() {

	// Bail if no guide ID.
	if ( ! maiguides_get_guide_id() ) {
		return;
	}

	// Run hook!
	do_action( 'maiguides_after_content' );
}

/**
 * Register CSS files.
 *
 * @since   0.1.0
 * @return  void
 */
add_action( 'wp_enqueue_scripts', 'maiguides_register_styles' );
function maiguides_register_styles() {
	$suffix = maiguides_get_suffix();
	wp_register_style( 'mai-guides', MAI_GUIDES_PLUGIN_URL . "assets/css/mai-guides{$suffix}.css", array(), MAI_GUIDES_VERSION );
}

/**
 * Register [guides] shortcode.
 *
 * @since   0.1.0
 * @return  string
 */
add_shortcode( 'guide_toc', 'maiguides_get_guide_toc_shortcode' );
function maiguides_get_guide_toc_shortcode( $atts ) {
	return maiguides_get_table_of_contents();
}