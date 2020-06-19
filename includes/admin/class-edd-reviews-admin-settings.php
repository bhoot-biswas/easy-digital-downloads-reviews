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
		$sections['reviews'] = __( 'Reviews', 'easy-digital-downloads-reviews' );
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
				'name' => '<strong>' . __( 'Download Reviews', 'easy-digital-downloads-reviews' ) . '</strong>',
				'type' => 'header',
			),
			'enable_reviews'                      => array(
				'id'   => 'enable_reviews',
				'name' => __( 'Enable Reviews', 'easy-digital-downloads-reviews' ),
				'desc' => __( 'Enable download reviews.', 'easy-digital-downloads-reviews' ),
				'type' => 'checkbox',
			),
			'review_rating_verification_required' => array(
				'id'   => 'review_rating_verification_required',
				'name' => __( 'Enable Verification', 'easy-digital-downloads-reviews' ),
				'desc' => __( 'Reviews can only be left by "verified owners".', 'easy-digital-downloads-reviews' ),
				'type' => 'checkbox',
			),
			'review_rating_verification_label'    => array(
				'id'   => 'review_rating_verification_label',
				'name' => __( 'Verification Label', 'easy-digital-downloads-reviews' ),
				'desc' => __( 'Show "verified owner" label on customer reviews.', 'easy-digital-downloads-reviews' ),
				'type' => 'checkbox',
			),
			'download_ratings'                    => array(
				'id'   => 'download_ratings',
				'name' => '<strong>' . __( 'Download Ratings', 'easy-digital-downloads-reviews' ) . '</strong>',
				'type' => 'header',
			),
			'enable_review_rating'                => array(
				'id'   => 'enable_review_rating',
				'name' => __( 'Enable Ratings', 'easy-digital-downloads-reviews' ),
				'desc' => __( 'Enable star ratings on reviews.', 'easy-digital-downloads-reviews' ),
				'type' => 'checkbox',
			),
			'review_rating_required'              => array(
				'id'   => 'review_rating_required',
				'name' => __( 'Ratings Required?', 'easy-digital-downloads-reviews' ),
				'desc' => __( 'Star ratings should be required, not optional.', 'easy-digital-downloads-reviews' ),
				'type' => 'checkbox',
			),
		);

		return $settings;
	}

}
