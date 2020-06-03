<?php
/**
 * Edd_Reviews Template Hooks
 *
 * Action/filter hooks used for Edd_Reviews functions/templates.
 *
 * @package Edd_Reviews/Templates
 * @version 0.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reviews
 *
 * @see edd_reviews_review_display_gravatar()
 * @see edd_reviews_review_display_rating()
 * @see edd_reviews_review_display_meta()
 * @see edd_reviews_review_display_comment_text()
 */
add_action( 'edd_reviews_review_before', 'edd_reviews_review_display_gravatar', 10 );
add_action( 'edd_reviews_review_before_comment_meta', 'edd_reviews_review_display_rating', 10 );
add_action( 'edd_reviews_review_meta', 'edd_reviews_review_display_meta', 10 );
add_action( 'edd_reviews_review_comment_text', 'edd_reviews_review_display_comment_text', 10 );
