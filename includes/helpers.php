<?php

/**
 * Get guides list HTML.
 *
 * @since   0.1.0
 * @return  string
 */
function maiguides_get_guide_content() {

	// Bail if not a post or a guide.
	if ( ! is_singular( array( 'post', 'mai_guide' ) ) ) {
		return;
	}

	// If single post.
	if ( is_singular( 'post' ) ) {
		$guide_ids = get_post_meta( get_the_ID(), 'guide_ids', true );
		if ( empty( $guide_ids ) || ! is_array( $guide_ids ) ) {
			return;
		}
		$guide_id = $guide_ids[0];
	}
	// Single guide.
	elseif ( is_singular( 'mai_guide' ) ) {
		$guide_id = get_the_ID();
	}

	// Bail if no guide ID.
	if ( ! $guide_id ) {
		return;
	}

	// Get post IDs.
	$post_ids = get_post_meta( $guide_id, 'guide_ids', true );

	// Bail if no post IDs.
	if ( empty( $post_ids ) || ! is_array( $post_ids ) ) {
		return;
	}

	wp_enqueue_style( 'mai-guides' );

	$html  = '';
	$html .= '<div class="mai-guides-container">';
		$html .= '<ol class="mai-guides">';
			$html .= maiguides_get_guide_item( $guide_id, false, true );
			$i = 1;
			foreach ( $post_ids as $post_id ) {
				$html .= maiguides_get_guide_item( $post_id, $i );
				$i++;
			}
		$html .= '</ol>';
	$html .= '</div>';

	return apply_filters( 'maiguides_guide_content', $html );
}

/**
 * Get guide HTML.
 *
 * @since   0.1.0
 *
 * @param   int     $post_id   The post ID to get the guide from.
 * @param   int     $count     The count/order the post is in.
 * @param   bool    $is_guide  Whether the guide is the main guide or a post.
 *
 * @return  string
 */
function maiguides_get_guide_item( $post_id, $count = '', $is_guide = false ) {
	$icon    = '<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 70 90"><path d="M 5.9881889,0 C 2.6929133,0 0,2.6929134 0,5.988189 L 0,84.011811 C 0,87.307087 2.6929133,90 5.9881889,90 l 58.0039361,0 c 3.295276,0 6.023622,-2.692913 6.023622,-5.988189 l 0,-78.023622 C 70.015747,2.6929134 67.287401,0 63.992125,0 z m 0,4.003937 58.0039361,0 c 1.133858,0 2.019685,0.8503937 2.019685,1.984252 l 0,78.023622 c 0,1.133858 -0.885827,1.984252 -2.019685,1.984252 l -58.0039361,0 c -1.1338583,0 -1.9842519,-0.850394 -1.9842519,-1.984252 l 0,-78.023622 c 0,-1.1338583 0.8503936,-1.984252 1.9842519,-1.984252 z m 4.9960631,13.003937 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,1.984252 c -1.098426,0 -1.984252,0.885827 -1.984252,2.019685 0,1.098425 0.885826,1.984252 1.984252,1.984252 l 29.019684,0 c 1.098425,0 1.984252,-0.885827 1.984252,-1.984252 0,-1.133858 -0.885827,-2.019685 -1.984252,-2.019685 z m -17.007874,13.996063 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,2.019685 c -1.098426,0 -1.984252,0.885827 -1.984252,1.984252 0,1.098425 0.885826,2.019685 1.984252,2.019685 l 29.019684,0 c 1.098425,0 1.984252,-0.92126 1.984252,-2.019685 0,-1.098425 -0.885827,-1.984252 -1.984252,-1.984252 z m -17.007874,13.996063 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,1.984252 c -1.098426,0 -1.984252,0.92126 -1.984252,2.019685 0,1.098425 0.885826,1.984252 1.984252,1.984252 l 29.019684,0 c 1.098425,0 1.984252,-0.885827 1.984252,-1.984252 0,-1.098425 -0.885827,-2.019685 -1.984252,-2.019685 z m -17.007874,13.996063 0,8.007874 8.007874,0 0,-8.007874 z m 17.007874,2.019685 c -1.098426,0 -1.984252,0.885827 -1.984252,1.984252 0,1.133858 0.885826,2.019685 1.984252,2.019685 l 29.019684,0 c 1.098425,0 1.984252,-0.885827 1.984252,-2.019685 0,-1.098425 -0.885827,-1.984252 -1.984252,-1.984252 z" style="" fill="currentColor" fill-rule="nonzero" stroke="none"/></svg>';
	$icon    = apply_filters( 'maiguides_guide_icon', $icon );
	$guide   = $is_guide ? ' mai-guide-main' : '';
	$current = ( get_the_ID() === (int) $post_id ) ? ' mai-current-guide' : '';
	$html    = sprintf( '<li class="mai-guide%s%s">', $guide, $current );
		$html .= sprintf( '<a class="mai-guide__link" href="%s">', get_permalink( $post_id ) );
			$html .= '<span class="row gutter-xs middle-xs">';
				if ( $is_guide ) {
					$html .= $icon ? $icon : '';
				} else {
					$html .= sprintf( '<span class="mai-guide__count col col-xs-auto">%s</span>', $count );
					$html .= sprintf( '<span class="mai-guide__image col col-xs-auto">%s</span>', wp_get_attachment_image( get_post_thumbnail_id( $post_id ), 'tiny', false, array( 'class' => 'mai-guide__img' ) ) );
				}
				$html .= sprintf( '<span class="mai-guide__title col col-xs"><h4 class="bottom-xs-none">%s</h4></span>', get_the_title( $post_id ) );
			$html .= '</span>';
		$html .= '</a>';
	$html .= '</li>';
	return $html;
}
