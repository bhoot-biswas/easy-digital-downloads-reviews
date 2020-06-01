<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set rating counts. Read only.
 *
 * @param array $counts Download rating counts.
 */
function edd_set_rating_counts( $download, $counts ) {
	update_post_meta( $download->get_ID(), '_rating_counts', array_filter( array_map( 'absint', (array) $counts ) ) );
}

/**
 * Set average rating. Read only.
 *
 * @param float $average Download average rating.
 */
function edd_set_average_rating( $download, $average ) {
    update_post_meta( $download->get_ID(), '_average_rating', wc_format_decimal( $average ) );
}

/**
 * Set review count. Read only.
 *
 * @param int $count Download review count.
 */
function edd_set_review_count( $download, $count ) {
    update_post_meta( $download->get_ID(), '_review_count', absint( $count ) );
}
