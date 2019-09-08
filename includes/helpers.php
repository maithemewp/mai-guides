<?php

/**
 * Get guides list HTML.
 *
 * @since   0.1.0
 * @return  string
 */
function maiguides_get_table_of_contents() {

	// Get the main guide ID.
	$guide_id = maiguides_get_guide_id();

	// Bail if no guide ID.
	if ( ! $guide_id ) {
		return;
	}

	// Bail if user cannot view the guide.
	if ( ! maiguides_can_view( $guide_id ) ) {
		return;
	}

	// Get post IDs.
	$entry_ids = maiguides_get_guide_entry_ids();

	// Bail if no post IDs.
	if ( empty( $entry_ids ) || ! is_array( $entry_ids ) ) {
		return;
	}

	wp_enqueue_style( 'mai-guides' );

	$html  = '';
	$html .= '<div class="mai-guides-container">';
		$html .= '<ol class="mai-guides">';
			$html .= maiguides_get_guide_list_item( $guide_id, false, true );
			$i = 1;
			foreach ( $entry_ids as $entry_id ) {
				$html .= maiguides_get_guide_list_item( $entry_id, $i );
				$i++;
			}
		$html .= '</ol>';
	$html .= '</div>';

	return apply_filters( 'maiguides_table_of_contents', $html );
}

/**
 * Get the guide entry IDs.
 *
 * @since   0.3.0
 * @return  array|false
 */
function maiguides_get_guide_entry_ids() {

	// Get the main guide ID.
	$guide_id = maiguides_get_guide_id();

	// Bail if no ID.
	if ( ! $guide_id ) {
		return false;
	}

	// Return the entry IDs.
	return get_post_meta( $guide_id, 'guide_ids', true );
}

/**
 * Get the guide entry post types.
 * Use the filter here to allow other post types to be used as guide entries.
 *
 * @since   0.3.0
 * @return  array
 */
function maiguides_get_guide_id() {

	// Bail if not guide content.
	if ( ! maiguides_is_table_of_contents() ) {
		return false;
	}

	$guide_id = false;

	// Single guide.
	if ( is_singular( 'mai_guide' ) ) {
		$guide_id = get_the_ID();
	}
	// If single guide entry.
	else {
		$guide_ids = get_post_meta( get_the_ID(), 'guide_ids', true );
		if ( empty( $guide_ids ) || ! is_array( $guide_ids ) ) {
			return false;
		}
		$guide_id = $guide_ids[0];
	}

	return absint( $guide_id );
}

/**
 * Check if on a guide or guide entry post.
 *
 * @since   0.3.0
 * @return  bool
 */
function maiguides_is_table_of_contents() {

	// It's a main guide page!
	if ( is_singular( 'mai_guide' ) ) {
		return true;
	}

	// It's not a guide entry post type.
	if ( ! is_singular( maiguides_get_guide_entry_post_types() ) ) {
		return false;
	}

	// Get the value.
	$value = get_post_meta( get_the_ID(), 'guide_ids', true );

	// Bail if no value.
	if ( ! $value ) {
		return false;
	}

	// Yep!
	return true;
}

/**
 * Get the guide entry post types.
 * Use the filter here to allow other post types to be used as guide entries.
 *
 * @since   0.3.0
 * @return  array
 */
function maiguides_get_guide_entry_post_types() {
	$post_types = array( 'post' );
	return (array) apply_filters( 'maiguides_entry_post_types', $post_types );
}

/**
 * Get guide HTML.
 *
 * @since   0.1.0
 *
 * @param   int     $entry_id   The post ID to get the guide from.
 * @param   int     $count     The count/order the post is in.
 * @param   bool    $is_guide  Whether the guide is the main guide or a post.
 *
 * @return  string
 */
function maiguides_get_guide_list_item( $entry_id, $count = '', $is_guide = false ) {
	// Bail if user cannot view the entry.
	if ( ! maiguides_can_view( $entry_id ) ) {
		return '';
	}
	$icon    = '<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 70 90"><path d="M 5.9881889,0 C 2.6929133,0 0,2.6929134 0,5.988189 L 0,84.011811 C 0,87.307087 2.6929133,90 5.9881889,90 l 58.0039361,0 c 3.295276,0 6.023622,-2.692913 6.023622,-5.988189 l 0,-78.023622 C 70.015747,2.6929134 67.287401,0 63.992125,0 z m 0,4.003937 58.0039361,0 c 1.133858,0 2.019685,0.8503937 2.019685,1.984252 l 0,78.023622 c 0,1.133858 -0.885827,1.984252 -2.019685,1.984252 l -58.0039361,0 c -1.1338583,0 -1.9842519,-0.850394 -1.9842519,-1.984252 l 0,-78.023622 c 0,-1.1338583 0.8503936,-1.984252 1.9842519,-1.984252 z m 4.9960631,13.003937 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,1.984252 c -1.098426,0 -1.984252,0.885827 -1.984252,2.019685 0,1.098425 0.885826,1.984252 1.984252,1.984252 l 29.019684,0 c 1.098425,0 1.984252,-0.885827 1.984252,-1.984252 0,-1.133858 -0.885827,-2.019685 -1.984252,-2.019685 z m -17.007874,13.996063 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,2.019685 c -1.098426,0 -1.984252,0.885827 -1.984252,1.984252 0,1.098425 0.885826,2.019685 1.984252,2.019685 l 29.019684,0 c 1.098425,0 1.984252,-0.92126 1.984252,-2.019685 0,-1.098425 -0.885827,-1.984252 -1.984252,-1.984252 z m -17.007874,13.996063 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,1.984252 c -1.098426,0 -1.984252,0.92126 -1.984252,2.019685 0,1.098425 0.885826,1.984252 1.984252,1.984252 l 29.019684,0 c 1.098425,0 1.984252,-0.885827 1.984252,-1.984252 0,-1.098425 -0.885827,-2.019685 -1.984252,-2.019685 z m -17.007874,13.996063 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,2.019685 c -1.098426,0 -1.984252,0.885827 -1.984252,1.984252 0,1.133858 0.885826,2.019685 1.984252,2.019685 l 29.019684,0 c 1.098425,0 1.984252,-0.885827 1.984252,-2.019685 0,-1.098425 -0.885827,-1.984252 -1.984252,-1.984252 z" style="" fill="currentColor" fill-rule="nonzero" stroke="none"/></svg>';
	$icon    = apply_filters( 'maiguides_guide_icon', $icon );
	$guide   = $is_guide ? ' mai-guide-main' : '';
	$current = ( get_the_ID() === (int) $entry_id ) ? ' mai-current-guide' : '';
	$html    = sprintf( '<li class="mai-guide%s%s">', $guide, $current );
		$html .= sprintf( '<a class="mai-guide__link" href="%s">', get_permalink( $entry_id ) );
			$html .= '<span class="row gutter-xs middle-xs">';
				if ( $is_guide ) {
					$html .= $icon ? $icon : '';
				} else {
					$html .= sprintf( '<span class="mai-guide__count col col-xs-auto">%s</span>', $count );
					$html .= sprintf( '<span class="mai-guide__image col col-xs-auto">%s</span>', wp_get_attachment_image( get_post_thumbnail_id( $entry_id ), 'tiny', false, array( 'class' => 'mai-guide__img' ) ) );
				}
				$html .= sprintf( '<span class="mai-guide__title col col-xs"><h4 class="bottom-xs-none">%s</h4></span>', get_the_title( $entry_id ) );
			$html .= '</span>';
		$html .= '</a>';
	$html .= '</li>';
	return $html;
}

/**
 * Helper function for getting the script/style `.min` suffix for minified files.
 *
 * @since   0.1.0
 * @return  string
 */
function maiguides_get_suffix() {
	$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	return $debug ? '' : '.min';
}

/**
 * Get process content.
 *
 * @since   0.3.0
 * @return  string|HTML
 */
function maiguides_get_processed_content( $content ) {
	if ( function_exists( 'mai_get_processed_content' ) ) {
		return mai_get_processed_content( $content );
	} else {
		global $wp_embed;
		$content = $wp_embed->autoembed( $content );              // WP runs priority 8.
		$content = $wp_embed->run_shortcode( $content );          // WP runs priority 8.
		$content = wptexturize( $content );                       // WP runs priority 10.
		$content = wpautop( $content );                           // WP runs priority 10.
		$content = mai_content_filter_shortcodes( $content );     // after wpautop, before shortcodes are parsed.
		$content = shortcode_unautop( $content );                 // WP runs priority 10.
		$content = wp_make_content_images_responsive( $content ); // WP runs priority 10.
		$content = do_shortcode( $content );                      // WP runs priority 11.
		$content = convert_smilies( $content );                   // WP runs priority 20.
	}
	return $content;
}

/**
 * Check if a user can view a post by ID.
 *
 * @since   0.4.1
 * @param   int  The post ID to check.
 * @return  bool
 */
function maiguides_can_view( $post_id ) {
	// Get guide status.
	$status = get_post_status( $post_id );
	// If guide is not public.
	if ( 'publish' === $status ) {
		return true;
	}
	// If guide is private and current user can view private posts.
	if ( ( 'private' === $status ) && current_user_can( 'read_private_posts' ) ) {
		return true;
	}
	// Nope!
	return false;
}
