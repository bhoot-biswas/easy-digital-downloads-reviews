<?php
/**
 * EDD_Reviews Meta Boxes
 *
 * Sets up the write panels used by downloads and payments (custom post types).
 *
 * @package EDD_Reviews/Admin/Meta Boxes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EDD_Reviews_Admin_Meta_Boxes.
 */
class EDD_Reviews_Admin_Meta_Boxes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'rename_meta_boxes' ), 20 );
	}

	/**
	 * Remove bloat.
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'commentsdiv', 'download', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'download', 'side' );
		remove_meta_box( 'commentstatusdiv', 'download', 'normal' );
	}

	/**
	 * Rename core meta boxes.
	 */
	public function rename_meta_boxes() {
		global $post;

		// Comments/Reviews.
		if ( isset( $post ) && ( 'publish' === $post->post_status || 'private' === $post->post_status ) && post_type_supports( 'download', 'comments' ) ) {
			remove_meta_box( 'commentsdiv', 'product', 'normal' );
			add_meta_box( 'commentsdiv', __( 'Reviews', 'easy-digital-downloads-reviews' ), 'post_comment_meta_box', 'download', 'normal' );
		}
	}
}
