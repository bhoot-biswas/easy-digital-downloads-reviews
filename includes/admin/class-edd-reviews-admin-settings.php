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
		$sections['reviews'] = __( 'Reviews', 'edd-reviews' );
		return $sections;
	}

	/**
	 * [register_settings description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function register_settings( $settings ) {
		$settings['reviews'] = array(
			'download_reviews'                    => array(
				'id'   => 'download_reviews',
				'name' => '<strong>' . __( 'Download Reviews', 'easy-digital-downloads' ) . '</strong>',
				'type' => 'header',
			),
			'enable_reviews'                      => array(
				'id'   => 'enable_reviews',
				'name' => __( 'Enable Reviews', 'edd-reviews' ),
				'desc' => __( 'Enable download reviews.', 'edd-reviews' ),
				'type' => 'checkbox',
			),
			'review_rating_verification_required' => array(
				'id'   => 'review_rating_verification_required',
				'name' => __( 'Enable Verification', 'edd-reviews' ),
				'desc' => __( 'Reviews can only be left by "verified owners".', 'edd-reviews' ),
				'type' => 'checkbox',
			),
			'review_rating_verification_label'    => array(
				'id'   => 'review_rating_verification_label',
				'name' => __( 'Verification Label', 'edd-reviews' ),
				'desc' => __( 'Show "verified owner" label on customer reviews.', 'edd-reviews' ),
				'type' => 'checkbox',
			),
			'download_ratings'                    => array(
				'id'   => 'download_ratings',
				'name' => '<strong>' . __( 'Download Ratings', 'easy-digital-downloads' ) . '</strong>',
				'type' => 'header',
			),
			'enable_review_rating'                => array(
				'id'   => 'enable_review_rating',
				'name' => __( 'Enable Ratings', 'edd-reviews' ),
				'desc' => __( 'Enable star ratings on reviews.', 'edd-reviews' ),
				'type' => 'checkbox',
			),
			'review_rating_required'              => array(
				'id'   => 'review_rating_required',
				'name' => __( 'Ratings Required?', 'edd-reviews' ),
				'desc' => __( 'Star ratings should be required, not optional.', 'edd-reviews' ),
				'type' => 'checkbox',
			),
		);

		return $settings;
	}

}

new EDD_Reviews_Admin_Settings();
