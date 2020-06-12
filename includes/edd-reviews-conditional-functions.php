<?php
/**
 * Check if reviews ratings are enabled.
 *
 * @since 0.1.0
 * @return bool
 */
function edd_reviews_download_ratings_enabled() {
	$ret = edd_get_option( 'enable_download_ratings', false );
	return (bool) apply_filters( 'edd_is_enable_download_ratings', $ret );
}

/**
 * Check if review ratings are required.
 *
 * @since 0.1.0
 * @return bool
 */
function edd_reviews_download_ratings_required() {
	$ret = edd_get_option( 'download_ratings_required', false );
	return (bool) apply_filters( 'edd_is_download_ratings_required', $ret );
}
