<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Reviews_Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_style(
			'edd-reviews',
			edd_reviews()->plugin_url() . '/build/index.css',
			array(),
			filemtime( edd_reviews()->plugin_path() . '/build/index.css' )
		);
	}

}
