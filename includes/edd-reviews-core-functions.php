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
function edd_set_rating_counts( $download_id, $counts ) {
	update_post_meta( $download_id, '_rating_counts', array_filter( array_map( 'absint', (array) $counts ) ) );
}

/**
 * Get rating counts.
 * @param  [type] $download_id [description]
 * @return [type]              [description]
 */
function edd_get_rating_counts( $download_id ) {
	return get_post_meta( $download_id, '_rating_counts',  true );
}

/**
 * Set average rating.
 *
 * @param float $average Download average rating.
 */
function edd_set_average_rating( $download_id, $average ) {
    update_post_meta( $download_id, '_average_rating', edd_format_amount( $average ) );
}

/**
 * Get average rating.
 * @param  [type] $download_id [description]
 * @return [type]              [description]
 */
function edd_get_average_rating( $download_id ) {
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
