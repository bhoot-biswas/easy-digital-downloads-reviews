<?php
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 * @package WooCommerce/Classes/Products
 * @version 2.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Comments class.
 */
class EDD_Reviews_Comments {

	public function __construct() {
		add_filter( 'preprocess_comment', [ $this, 'check_comment_rating' ], 0 );
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
			wp_die( esc_html__( 'Please rate the product.', 'edd-reviews' ) );
			exit;
		}
		return $comment_data;
	}

}
