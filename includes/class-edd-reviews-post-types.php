<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Reviews_Post_Types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'edd_download_supports', array( __CLASS__, 'edd_download_supports' ) );
	}

	/**
	 * Add post type supports.
	 * @param  [type] $supports [description]
	 * @return [type]           [description]
	 */
	public static function edd_download_supports( $supports ) {
		if ( edd_get_option( 'enable_reviews', false ) ) {
			$supports[] = 'comments';
		}

		return $supports;
	}

}
