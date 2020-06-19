<?php
/**
 * EDD_Reviews Admin
 *
 * @class    EDD_Reviews_Admin
 * @package  EDD_Reviews/Admin
 * @version  0.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EDD_Reviews_Admin class.
 */
class EDD_Reviews_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once dirname( __FILE__ ) . '/class-edd-reviews-admin-meta-boxes.php';
		include_once dirname( __FILE__ ) . '/class-edd-reviews-admin-settings.php';

		new EDD_Reviews_Admin_Meta_Boxes();
		new EDD_Reviews_Admin_Settings();
	}
}
