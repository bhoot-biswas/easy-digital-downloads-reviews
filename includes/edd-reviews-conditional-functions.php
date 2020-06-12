<?php
/**
 * Check if reviews are enabled.
 *
 * @since 0.1.0
 * @return bool
 */
function edd_reviews_reviews_enabled() {
	$ret = edd_get_option( 'enable_reviews', false );
	return (bool) apply_filters( 'edd_is_enable_reviews', $ret );
}

/**
 * Check if reviews ratings are enabled.
 *
 * @since 0.1.0
 * @return bool
 */
function edd_reviews_review_ratings_enabled() {
	$ret = edd_get_option( 'enable_review_rating', false );
	return (bool) apply_filters( 'edd_is_enable_review_rating', edd_reviews_reviews_enabled() && $ret );
}

/**
 * Check if review ratings are required.
 *
 * @since 0.1.0
 * @return bool
 */
function edd_reviews_review_ratings_required() {
	$ret = edd_get_option( 'review_rating_required', false );
	return (bool) apply_filters( 'edd_is_review_rating_required', $ret );
}
