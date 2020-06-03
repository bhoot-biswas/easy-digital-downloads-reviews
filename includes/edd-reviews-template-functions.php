<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Append comments.
 *
 * Automatically appends comments to download content, if enabled.
 *
 * @since 0.1.0
 * @param int $download_id Download ID
 * @return void
 */

function edd_reviews_append_comments( $download_id ) {
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}

if ( ! function_exists( 'edd_comments' ) ) {

	/**
	 * Output the Review comments template.
	 *
	 * @param WP_Comment $comment Comment object.
	 * @param array      $args Arguments.
	 * @param int        $depth Depth.
	 */
	function edd_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; // WPCS: override ok.
		edd_get_template_part( 'single-download/review' );
	}
}
