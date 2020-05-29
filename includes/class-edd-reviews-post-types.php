<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Reviews_Post_Types {

	public function __construct() {
		add_filter( 'edd_download_supports', [ $this, 'edd_download_supports' ] );
	}

	public function edd_download_supports( $supports ) {
		$supports[] = 'comments';

		return $supports;
	}

}
