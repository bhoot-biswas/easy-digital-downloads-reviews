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
		add_filter( 'edd_settings_sections_extensions', array( $this, 'register_settings_sections' ), 10 );
		add_filter( 'edd_settings_extensions', array( $this, 'register_settings' ), 10 );
	}

	/**
	 * [register_settings_sections description]
	 * @param  [type] $sections [description]
	 * @return [type]           [description]
	 */
	public function register_settings_sections( $sections ) {
		$sections['reviews'] = __( 'Reviews', 'easy-digital-downloads' );
		return $sections;
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
				'name' => __( 'Enable download reviews', 'easy-digital-downloads' ),
				'desc' => __( 'Check this box to allow customers to review the products offered on your website.', 'easy-digital-downloads' ),
				'type' => 'checkbox',
			),
		);

		return $settings;
	}

}

new EDD_Reviews_Admin_Settings();
