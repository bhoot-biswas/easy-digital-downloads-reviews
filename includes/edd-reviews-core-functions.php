<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set rating counts.
 *
 * @param array $counts Download rating counts.
 */
function edd_set_download_rating_counts( $download_id, $counts ) {
	update_post_meta( $download_id, '_rating_counts', array_filter( array_map( 'absint', (array) $counts ) ) );
}

/**
 * Get rating counts.
 * @param  [type] $download_id [description]
 * @return [type]              [description]
 */
function edd_get_download_rating_counts( $download_id ) {
	return get_post_meta( $download_id, '_rating_counts', true );
}

/**
 * Get the total amount (COUNT) of ratings, or just the count for one rating e.g. number of 5 star ratings.
 *
 * @param  int $value Optional. Rating value to get the count for. By default returns the count of all rating values.
 * @return int
 */
function edd_get_rating_count( $download_id, $value = null ) {
	$counts = edd_get_download_rating_counts( $download_id );

	if ( is_null( $value ) ) {
		return array_sum( $counts );
	} elseif ( isset( $counts[ $value ] ) ) {
		return absint( $counts[ $value ] );
	} else {
		return 0;
	}
}

/**
 * Set average rating.
 *
 * @param float $average Download average rating.
 */
function edd_set_download_average_rating( $download_id, $average ) {
	update_post_meta( $download_id, '_average_rating', edd_format_amount( $average ) );
}

/**
 * Get average rating.
 * @param  [type] $download_id [description]
 * @return [type]              [description]
 */
function edd_get_download_average_rating( $download_id ) {
	return get_post_meta( $download_id, '_average_rating', true );
}

/**
 * Set review count.
 *
 * @param int $count Download review count.
 */
function edd_set_download_review_count( $download_id, $count ) {
	update_post_meta( $download_id, '_review_count', absint( $count ) );
}

/**
 * Get review count.
 * @param  [type] $download_id [description]
 * @return [type]              [description]
 */
function edd_get_download_review_count( $download_id ) {
	return get_post_meta( $download_id, '_review_count', true );
}
