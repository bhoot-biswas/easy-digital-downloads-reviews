<?php
/**
 * Plugin Name:     Easy Digital Downloads Reviews
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     easy-digital-downloads-reviews
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Easy_Digital_Downloads_Reviews
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EDD_REVIEWS_PLUGIN_FILE' ) ) {
	define( 'EDD_REVIEWS_PLUGIN_FILE', __FILE__ );
}

// Include the main EDD_Reviews class.
if ( ! class_exists( 'EDD_Reviews', false ) ) {
	include_once dirname( EDD_REVIEWS_PLUGIN_FILE ) . '/includes/class-edd-reviews.php';
}

/**
 * The main function responsible for returning the one true EDD_Reviews
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $edd_reviews = edd_reviews(); ?>
 *
 * @since  0.1.0
 *
 * @return object The one true EDD_Reviews Instance
 */
function edd_reviews() {
	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {

		if ( ! class_exists( 'EDD_Extension_Activation' ) ) {
			require_once 'includes/class-edd-extension-activation.php';
		}

		$activation = new EDD_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

	} else {
		return EDD_Reviews::instance();
	}
}

add_action( 'plugins_loaded', 'edd_reviews', 100 );
