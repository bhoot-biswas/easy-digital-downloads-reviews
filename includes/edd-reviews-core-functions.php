<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieves a template part
 *
 * @since 0.1.0
 *
 * Taken from bbPress
 *
 * @param string $slug
 * @param string $name Optional. Default null
 * @param bool   $args
 *
 * @return string
 *
 * @uses edd_locate_template()
 * @uses load_template()
 * @uses get_template_part()
 */
function edd_reviews_get_template( $slug, $name = null, $args = array() ) {
	$load_template = apply_filters( 'edd_allow_template_' . $slug . '_' . $name, true );
	if ( false === $load_template ) {
		return '';
	}

	// Setup possible parts
	$templates = array();
	if ( isset( $name ) ) {
		$templates[] = $slug . '-' . $name . '.php';
	}
	$templates[] = $slug . '.php';

	// Locate the part that is found
	$template = edd_locate_template( $templates, false, false );

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'edd_reviews_get_template', $template, $template_name, $args );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			wc_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'edd-reviews' ), '<code>' . $template . '</code>' ), '0.1.0' );
			return;
		}
		$template = $filter_template;
	}

	$action_args = array(
		'template_name' => $template_name,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			wc_doing_it_wrong(
				__FUNCTION__,
				__( 'action_args should not be overwritten when calling edd_reviews_get_template.', 'edd-reviews' ),
				'0.1.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args ); // @codingStandardsIgnoreLine
	}

	include $action_args['located'];
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
