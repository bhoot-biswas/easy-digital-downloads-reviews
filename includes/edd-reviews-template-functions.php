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
