<?php
/**
 * Twenty Twenty support.
 *
 * @since   0.1.0
 * @package EDD_Reviews/Classes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EDD_Reviews_Twenty_Twenty class.
 */
class EDD_Reviews_Twenty_Twenty {

	/**
	 * Theme init.
	 */
	public static function init() {
		// Enqueue theme compatibility styles.
		add_filter( 'edd_reviews_enqueue_styles', array( __CLASS__, 'enqueue_styles' ) );
	}

	/**
	 * Enqueue CSS for this theme.
	 *
	 * @param  array $styles Array of registered styles.
	 * @return array
	 */
	public static function enqueue_styles( $styles ) {
		$styles['edd-reviews-twentytwenty'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', edd_reviews()->plugin_url() ) . '/build/twentytwenty.css',
			'deps'    => '',
			'version' => EDD_REVIEWS_VERSION,
			'media'   => 'all',
			'has_rtl' => true,
		);

		return apply_filters( 'edd_reviews_twenty_twenty_styles', $styles );
	}

}

EDD_Reviews_Twenty_Twenty::init();
