<?php
/**
 * The template to display the reviewers star rating in reviews
 *
 * This template can be overridden by copying it to yourtheme/templates/review-rating.php.
 *
 * HOWEVER, on occasion Edd_Reviews will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Edd_Reviews/Templates
 * @version 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $comment;
$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

if ( $rating && edd_reviews_review_ratings_enabled() ) {
	echo edd_reviews_get_rating_html( $rating ); // WPCS: XSS ok.
}
