<?php
/**
 * EDD_Reviews Settings
 *
 * Register settings.
 *
 * @package EDD_Reviews/Admin/Settings
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EDD_Reviews_Admin_Settings.
 */
class EDD_Reviews_Admin_Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'edd_settings_extensions', array( $this, 'register_settings' ), 10 );
	}

	/**
	 * [register_settings description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function register_settings( $settings ) {
		$settings['reviews'] = array(
			'enable_reviews' => array(
				'id'   => 'enable_reviews',
				'name' => __( 'Allow User Keys', 'easy-digital-downloads' ),
				'desc' => __( 'Check this box to allow all users to generate API keys. Users with the \'manage_shop_settings\' capability are always allowed to generate keys.', 'easy-digital-downloads' ),
				'type' => 'checkbox',
			),
		);

		return $settings;
	}

}

new EDD_Reviews_Admin_Settings();
