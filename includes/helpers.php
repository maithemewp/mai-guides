<?php

function maiguides_get_guides() {

	// Bail if not a post or a guide.
	if ( ! is_singular( 'post', 'mai_guide' ) ) {
		return;
	}

	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$guides = get_post_meta( get_the_ID(), 'guide_ids', true );

	if ( empty( $guides ) || ! is_array( $guides ) ) {
		return;
	}

	wp_enqueue_style( 'mai-guides' );

	$guide_id = $guides[0];

	$posts = get_post_meta( $guide_id, 'guide_ids', true );

	if ( empty( $posts ) || ! is_array( $posts ) ) {
		return;
	}

	$html  = '';
	$html .= '<div class="mai-guides-container">';
		$html .= '<ul class="mai-guides">';
			$html .= maiguides_get_guide( $guide_id, false, true );
			$i = 1;
			foreach ( $posts as $post_id ) {
				$html .= maiguides_get_guide( $post_id, $i );
				$i++;
			}
		$html .= '</ul>';
	$html .= '</div>';

	return $html;
}

function maiguides_get_guide( $post_id, $count = '', $is_main = false ) {
	$guide   = $is_main ? ' mai-guide-main' : '';
	$current = ( get_the_ID() === (int) $post_id ) ? ' mai-current-guide' : '';
	$html    = sprintf( '<li class="mai-guide%s%s">', $guide, $current );
		$html .= sprintf( '<a class="mai-guide__link row gutter-xs middle-xs" href="%s">', get_permalink( $post_id ) );
			if ( ! $is_main ) {
				$html .= sprintf( '<span class="mai-guide__count col col-xs-auto">%s</span>', $count );
				$html .= sprintf( '<span class="mai-guide__image col col-xs-auto">%s</span>', wp_get_attachment_image( get_post_thumbnail_id( $post_id ), 'tiny', false, array( 'class' => 'mai-guide__img' ) ) );
			}
			$html .= sprintf( '<span class="mai-guide__title col col-xs">%s</span>', get_the_title( $post_id ) );
		$html .= '</a>';
	$html .= '</li>';
	return $html;
}
