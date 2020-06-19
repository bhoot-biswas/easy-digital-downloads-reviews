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
		// Rating posts.
		add_filter( 'comments_open', array( __CLASS__, 'comments_open' ), 10, 2 );
		add_filter( 'preprocess_comment', array( __CLASS__, 'check_comment_rating' ), 0 );
		add_action( 'comment_post', array( __CLASS__, 'add_comment_rating' ), 1 );
		add_action( 'comment_moderation_recipients', array( __CLASS__, 'comment_moderation_recipients' ), 10, 2 );

		// Support avatars for `review` comment type.
		add_filter( 'get_avatar_comment_types', array( __CLASS__, 'add_avatar_for_review_comment_type' ) );

		// Review of verified purchase.
		add_action( 'comment_post', array( __CLASS__, 'add_comment_purchase_verification' ) );

		// Set comment type.
		add_action( 'preprocess_comment', array( __CLASS__, 'update_comment_type' ), 1 );

		// Update comment status.
		add_action( 'wp_insert_post', array( __CLASS__, 'update_comment_status' ), 10, 3 );
	}

	/**
	 * See if comments are open.
	 *
	 * @since  0.1.0
	 * @param  bool $open    Whether the current post is open for comments.
	 * @param  int  $post_id Post ID.
	 * @return bool
	 */
	public static function comments_open( $open, $post_id ) {
		if ( 'download' === get_post_type( $post_id ) && ! post_type_supports( 'download', 'comments' ) ) {
			$open = false;
		}
		return $open;
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
			wp_die( esc_html__( 'Please rate the download.', 'easy-digital-downloads-reviews' ) );
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
	 * Modify recipient of review email.
	 *
	 * @param array $emails     Emails.
	 * @param int   $comment_id Comment ID.
	 * @return array
	 */
	public static function comment_moderation_recipients( $emails, $comment_id ) {
		$comment = get_comment( $comment_id );

		if ( $comment && 'download' === get_post_type( $comment->comment_post_ID ) ) {
			$emails = array( get_option( 'admin_email' ) );
		}

		return $emails;
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
		edd_reviews_set_download_review_count( $download->get_ID(), self::get_review_count_for_download( $download ) );
		edd_reviews_set_download_rating_counts( $download->get_ID(), self::get_rating_counts_for_download( $download ) );
		edd_reviews_set_download_average_rating( $download->get_ID(), self::get_average_rating_for_download( $download ) );
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

		$count = edd_reviews_get_rating_count( $download->get_ID() );

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

	/**
	 * Make sure WP displays avatars for comments with the `review` type.
	 *
	 * @since  0.1.0
	 * @param  array $comment_types Comment types.
	 * @return array
	 */
	public static function add_avatar_for_review_comment_type( $comment_types ) {
		return array_merge( $comment_types, array( 'review' ) );
	}

	/**
	 * Determine if a review is from a verified owner at submission.
	 *
	 * @param int $comment_id Comment ID.
	 * @return bool
	 */
	public static function add_comment_purchase_verification( $comment_id ) {
		$comment  = get_comment( $comment_id );
		$verified = false;
		if ( 'download' === get_post_type( $comment->comment_post_ID ) ) {
			$verified = edd_has_user_purchased( $comment->user_id, $comment->comment_post_ID );
			add_comment_meta( $comment_id, 'verified', (int) $verified, true );
		}
		return $verified;
	}

	/**
	 * Update comment type of download reviews.
	 *
	 * @since 0.1.0
	 * @param array $comment_data Comment data.
	 * @return array
	 */
	public static function update_comment_type( $comment_data ) {
		if ( ! is_admin() && isset( $_POST['comment_post_ID'], $comment_data['comment_type'] ) && '' === $comment_data['comment_type'] && 'download' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) { // WPCS: input var ok, CSRF ok.
			$comment_data['comment_type'] = 'review';
		}

		return $comment_data;
	}

	/**
	 * [update_comment_status description]
	 * @param  [type] $post_id [description]
	 * @param  [type] $post    [description]
	 * @param  [type] $update  [description]
	 * @return [type]          [description]
	 */
	public static function update_comment_status( $post_id, $post, $update ) {
		global $wpdb;

		$comment_status = edd_reviews_reviews_enabled() ? 'open' : 'closed';
		if ( $comment_status === $post->comment_status ) {
			return;
		}

		$wpdb->update( $wpdb->posts, array( 'comment_status' => $comment_status ), array( 'ID' => $post_id ) );
	}

}
