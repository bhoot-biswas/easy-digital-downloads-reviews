<?php
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 * @package EDD_Reviews
 * @version 0.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Comments class.
 */
class EDD_Reviews_Comments {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'preprocess_comment', array( __CLASS__, 'check_comment_rating' ), 0 );
		add_action( 'comment_post', array( __CLASS__, 'add_comment_rating' ), 1 );
	}

	/**
	 * Validate the comment ratings.
	 *
	 * @param  array $comment_data Comment data.
	 * @return array
	 */
	public static function check_comment_rating( $comment_data ) {
		// If posting a comment (not trackback etc) and not logged in.
		if ( ! is_admin() && isset( $_POST['comment_post_ID'], $_POST['rating'], $comment_data['comment_type'] ) && 'download' === get_post_type( absint( $_POST['comment_post_ID'] ) ) && empty( $_POST['rating'] ) && '' === $comment_data['comment_type'] && edd_reviews_review_ratings_enabled() && edd_reviews_review_ratings_required() ) { // WPCS: input var ok, CSRF ok.
			wp_die( esc_html__( 'Please rate the download.', 'edd-reviews' ) );
			exit;
		}
		return $comment_data;
	}

	/**
	 * Rating field for comments.
	 *
	 * @param int $comment_id Comment ID.
	 */
	public static function add_comment_rating( $comment_id ) {
		if ( isset( $_POST['rating'], $_POST['comment_post_ID'] ) && 'download' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) { // WPCS: input var ok, CSRF ok.
			if ( ! $_POST['rating'] || $_POST['rating'] > 5 || $_POST['rating'] < 0 ) { // WPCS: input var ok, CSRF ok, sanitization ok.
				return;
			}
			add_comment_meta( $comment_id, 'rating', intval( $_POST['rating'] ), true ); // WPCS: input var ok, CSRF ok.

			$post_id = isset( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0; // WPCS: input var ok, CSRF ok.
			if ( $post_id ) {
				self::clear_transients( $post_id );
			}
		}
	}

	/**
	 * Ensure download average rating and review count is kept up to date.
	 *
	 * @param int $post_id Post ID.
	 */
	public static function clear_transients( $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( 'download' !== $post_type ) {
			return;
		}

		$download = edd_get_download( $post_id );
		edd_set_download_review_count( $download->get_ID(), self::get_review_count_for_download( $download ) );
		edd_set_download_rating_counts( $download->get_ID(), self::get_rating_counts_for_download( $download ) );
		edd_set_download_average_rating( $download->get_ID(), self::get_average_rating_for_download( $download ) );
	}

	/**
	 * Get download review count for a download (not replies). Please note this is not cached.
	 *
	 * @since 0.1.0
	 * @param EDD_Download $download Download instance.
	 * @return int
	 */
	public static function get_review_count_for_download( &$download ) {
		global $wpdb;

		$count = $wpdb->get_var(
			$wpdb->prepare(
				"
			SELECT COUNT(*) FROM $wpdb->comments
			WHERE comment_parent = 0
			AND comment_post_ID = %d
			AND comment_approved = '1'
				",
				$download->get_ID()
			)
		);

		return $count;
	}

	/**
	 * Get download rating count for a download. Please note this is not cached.
	 *
	 * @since 0.1.0
	 * @param EDD_Download $download Download instance.
	 * @return int[]
	 */
	public static function get_rating_counts_for_download( &$download ) {
		global $wpdb;

		$counts     = array();
		$raw_counts = $wpdb->get_results(
			$wpdb->prepare(
				"
			SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
			AND meta_value > 0
			GROUP BY meta_value
				",
				$download->get_ID()
			)
		);

		foreach ( $raw_counts as $count ) {
			$counts[ $count->meta_value ] = absint( $count->meta_value_count ); // WPCS: slow query ok.
		}

		return $counts;
	}

	/**
	 * Get download rating for a download. Please note this is not cached.
	 *
	 * @since 0.1.0
	 * @param EDD_Download $download Download instance.
	 * @return float
	 */
	public static function get_average_rating_for_download( &$download ) {
		global $wpdb;

		$count = edd_get_rating_count( $download->get_ID() );

		if ( $count ) {
			$ratings = $wpdb->get_var(
				$wpdb->prepare(
					"
				SELECT SUM(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
					",
					$download->get_ID()
				)
			);
			$average = number_format( $ratings / $count, 2, '.', '' );
		} else {
			$average = 0;
		}

		return $average;
	}

}
