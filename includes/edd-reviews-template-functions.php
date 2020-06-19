<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'edd_reviews_append_comments' ) ) {
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
}


if ( ! function_exists( 'edd_reviews_comments' ) ) {
	/**
	 * Output the Review comments template.
	 *
	 * @param WP_Comment $comment Comment object.
	 * @param array      $args Arguments.
	 * @param int        $depth Depth.
	 */
	function edd_reviews_comments( $comment, $args, $depth ) {
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

if ( ! function_exists( 'edd_reviews_review_display_meta' ) ) {
	/**
	 * Display the review authors meta (name, verified owner, review date)
	 *
	 * @return void
	 */
	function edd_reviews_review_display_meta() {
		edd_get_template_part( 'single-download/review-meta' );
	}
}

if ( ! function_exists( 'edd_reviews_review_display_comment_text' ) ) {
	/**
	 * Display the review content.
	 */
	function edd_reviews_review_display_comment_text() {
		echo '<div class="description">';
		comment_text();
		echo '</div>';
	}
}

if ( ! function_exists( 'edd_reviews_get_rating_html' ) ) {
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
			$label = sprintf( __( 'Rated %s out of 5', 'easy-digital-downloads-reviews' ), $rating );
			$html  = '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">' . edd_reviews_get_star_rating_html( $rating, $count ) . '</div>';
		}

		return apply_filters( 'edd_reviews_download_get_rating_html', $html, $rating, $count );
	}
}

if ( ! function_exists( 'edd_reviews_get_star_rating_html' ) ) {
	/**
	 * Get HTML for star rating.
	 *
	 * @since  0.1.0
	 * @param  float $rating Rating being shown.
	 * @param  int   $count  Total number of ratings.
	 * @return string
	 */
	function edd_reviews_get_star_rating_html( $rating, $count = 0 ) {
		$html = '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';

		if ( 0 < $count ) {
			/* translators: 1: rating 2: rating count */
			$html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'easy-digital-downloads-reviews' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>', '<span class="rating">' . esc_html( $count ) . '</span>' );
		} else {
			/* translators: %s: rating */
			$html .= sprintf( esc_html__( 'Rated %s out of 5', 'easy-digital-downloads-reviews' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>' );
		}

		$html .= '</span>';

		return apply_filters( 'edd_reviews_get_star_rating_html', $html, $rating, $count );
	}
}
