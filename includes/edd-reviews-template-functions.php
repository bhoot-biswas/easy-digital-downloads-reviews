<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Append comments.
 *
 * Automatically appends comments to download content, if enabled.
 *
 * @since 0.1.0
 * @param int $download_id Download ID
 * @return void
 */

function edd_reviews_append_comments( $download_id ) {
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}

if ( ! function_exists( 'edd_comments' ) ) {

	/**
	 * Output the Review comments template.
	 *
	 * @param WP_Comment $comment Comment object.
	 * @param array      $args Arguments.
	 * @param int        $depth Depth.
	 */
	function edd_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; // WPCS: override ok.
		edd_get_template_part( 'single-download/review' );
	}
}

if ( ! function_exists( 'edd_reviews_review_display_gravatar' ) ) {
	/**
	 * Display the review authors gravatar
	 *
	 * @param array $comment WP_Comment.
	 * @return void
	 */
	function edd_reviews_review_display_gravatar( $comment ) {
		echo get_avatar( $comment, apply_filters( 'edd_reviews_review_gravatar_size', '60' ), '' );
	}
}

if ( ! function_exists( 'edd_reviews_review_display_rating' ) ) {
	/**
	 * Display the reviewers star rating
	 *
	 * @return void
	 */
	function edd_reviews_review_display_rating() {
		if ( post_type_supports( 'download', 'comments' ) ) {
			edd_get_template_part( 'single-download/review-rating' );
		}
	}
}

/**
 * Get HTML for ratings.
 *
 * @since  0.1.0
 * @param  float $rating Rating being shown.
 * @param  int   $count  Total number of ratings.
 * @return string
 */
function edd_reviews_get_rating_html( $rating, $count = 0 ) {
	$html = '';

	if ( 0 < $rating ) {
		/* translators: %s: rating */
		$label = sprintf( __( 'Rated %s out of 5', 'edd-reviews' ), $rating );
		$html  = '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">' . wc_get_star_rating_html( $rating, $count ) . '</div>';
	}

	return apply_filters( 'edd_reviews_download_get_rating_html', $html, $rating, $count );
}
