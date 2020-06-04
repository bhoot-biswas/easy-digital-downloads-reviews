<?php
/**
 * Edd_Reviews Customer Functions
 *
 * Functions for customers.
 *
 * @package Edd_Reviews/Functions
 * @version 0.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get review verification status.
 *
 * @param  int $comment_id Comment ID.
 * @return bool
 */
function edd_reviews_review_is_from_verified_owner( $comment_id ) {
	$verified = get_comment_meta( $comment_id, 'verified', true );
	return '' === $verified ? EDD_Reviews_Comments::add_comment_purchase_verification( $comment_id ) : (bool) $verified;
}
