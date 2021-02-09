<?php

/**
 * Get guides list HTML.
 *
 * @since   0.1.0
 * @return  string
 */
function maiguides_get_table_of_contents( $atts ) {

	// Atts.
	$atts = shortcode_atts( array(
		'guide_id'   => maiguides_get_guide_id(),
		'id'         => '',
		'class'      => '',
		'title'      => '',
		'title_wrap' => 'h3',
		'entry_wrap' => 'h4',
		'image_size' => 'tiny',
	), $atts, 'guide_toc' );

	// Sanitize.
	$atts = array(
		'guide_id'   => absint( $atts['guide_id'] ),
		'id'         => esc_attr( $atts['id'] ),
		'class'      => esc_attr( $atts['class'] ),
		'title'      => sanitize_text_field( $atts['title'] ),
		'title_wrap' => esc_attr( $atts['title_wrap'] ),
		'entry_wrap' => esc_attr( $atts['entry_wrap'] ),
		'image_size' => esc_attr( $atts['image_size'] ),
	);

	// Bail if no guide ID.
	if ( ! $atts['guide_id'] ) {
		return;
	}

	// Bail if user cannot view the guide.
	if ( ! maiguides_can_view( $atts['guide_id'] ) ) {
		return;
	}

	// Get post IDs.
	$entry_ids = maiguides_get_guide_entry_ids( $atts['guide_id'] );

	// Bail if no post IDs.
	if ( empty( $entry_ids ) || ! is_array( $entry_ids ) ) {
		return;
	}

	// Load styles.
	wp_enqueue_style( 'mai-guides' );

	// Add custom id/class.
	$id    = trim( $atts['id'] );
	$id    = ! empty( $atts['id'] ) ? sprintf( 'id="%s" ', $atts['id'] ) : '';
	$class = trim( $atts['class'] );
	$class = ! empty( $class ) ? sprintf( ' %s', $class ) : '';

	// Get it started.
	$html  = '';
	$html .= sprintf( '<div %sclass="mai-guides-container%s">', $id, $class );

		// Heading.
		$title = ! empty( $atts['title'] ) ? $atts['title']: get_the_title( $atts['guide_id'] );
		$icon  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" width="24" height="24"><path d="M88 56H40a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h48a16 16 0 0 0 16-16V72a16 16 0 0 0-16-16zm0 160H40a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h48a16 16 0 0 0 16-16v-48a16 16 0 0 0-16-16zm0 160H40a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h48a16 16 0 0 0 16-16v-48a16 16 0 0 0-16-16zm416 24H168a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h336a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-320H168a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h336a8 8 0 0 0 8-8V88a8 8 0 0 0-8-8zm0 160H168a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h336a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8z"/></svg>';
		$icon  = apply_filters( 'maiguides_guide_icon', $icon );
		$html  .= '<div class="mai-guide__heading">';
			$html .= $icon ? sprintf( '<span class="mai-guide__icon">%s</span>', $icon ) : '';
			$html .= sprintf( '<span class="mai-guide__title mai-guide__title-heading"><%s>%s</%s></span>', $atts['title_wrap'], $title, $atts['title_wrap'] );
		$html .= '</div>';

		// Start counter.
		$i = 1;

		// List.
		$html .= '<ol class="mai-guides">';
			$html .= maiguides_get_guide_list_item( $atts['guide_id'], $i, $atts );
			foreach ( $entry_ids as $entry_id ) {
				$i++;
				$html .= maiguides_get_guide_list_item( $entry_id, $i, $atts );
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
function maiguides_get_guide_entry_ids( $guide_id ) {

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
		$guide_id = reset( $guide_ids );
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
	static $post_types = null;
	if ( ! is_null( $post_types ) ) {
		return $post_types;
	}
	$post_types = array( 'post' );
	$post_types = apply_filters( 'maiguides_entry_post_types', $post_types );
	return (array) $post_types;
}

/**
 * Get guide HTML.
 *
 * @since   0.1.0
 *
 * @param   int    $entry_id   The post ID to get the guide from.
 * @param   int    $count      The count/order the post is in.
 * @param   array  $atts       The shortcode attributes.
 *
 * @return  string
 */
function maiguides_get_guide_list_item( $entry_id, $count = '', $atts ) {
	// Bail if user cannot view the entry.
	if ( ! maiguides_can_view( $entry_id ) ) {
		return '';
	}
	$current  = ( get_the_ID() === (int) $entry_id ) ? ' mai-current-guide' : '';
	$image_id = get_post_thumbnail_id( $entry_id );
	$html     = sprintf( '<li class="mai-guide%s">', $current );
		$html .= sprintf( '<a class="mai-guide__link" href="%s">', get_permalink( $entry_id ) );
			$html .= sprintf( '<span class="mai-guide__count">%s</span>', $count );
			$html .= $image_id ? sprintf( '<span class="mai-guide__image">%s</span>', wp_get_attachment_image( $image_id, $atts['image_size'], false, array( 'class' => 'mai-guide__img' ) ) ) : '';
			$html .= sprintf( '<span class="mai-guide__title"><%s>%s</%s></span>', $atts['title_wrap'], get_the_title( $entry_id ), $atts['title_wrap'] );
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
		/**
		 * Embed.
		 *
		 * @var WP_Embed $wp_embed Embed object.
		 */
		global $wp_embed;

		$content = $wp_embed->autoembed( $content );     // WP runs priority 8.
		$content = $wp_embed->run_shortcode( $content ); // WP runs priority 8.
		$content = do_blocks( $content );                // WP runs priority 9.
		$content = wptexturize( $content );              // WP runs priority 10.
		$content = wpautop( $content );                  // WP runs priority 10.
		$content = shortcode_unautop( $content );        // WP runs priority 10.
		$content = function_exists( 'wp_filter_content_tags' ) ? wp_filter_content_tags( $content ) : wp_make_content_images_responsive( $content ); // WP runs priority 10. WP 5.5 with fallback.
		$content = do_shortcode( $content );             // WP runs priority 11.
		$content = convert_smilies( $content );          // WP runs priority 20.
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
