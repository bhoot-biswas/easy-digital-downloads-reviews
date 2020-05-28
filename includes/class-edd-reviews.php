<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main EDD_Reviews Class.
 *
 * Tap tap tap... Is this thing on?
 *
 * @since 0.1.0
 */
final class EDD_Reviews {

	/**
	 * Main EDD_Reviews Instance.
	 *
	 * EDD_Reviews is great.
	 * Please load it only one time.
	 * For this, we thank you.
	 *
	 * Insures that only one instance of EDD_Reviews exists in memory at any
	 * one time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1.0
	 *
	 * @static object $instance
	 * @see edd_reviews()
	 *
	 * @return EDD_Reviews|null The one true EDD_Reviews.
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication.
		static $instance = null;

		// Only run these methods if they haven't been run previously.
		if ( null === $instance ) {
			$instance = new EDD_Reviews;
			$instance->constants();
			$instance->setup_globals();
			$instance->load_textdomain();
			$instance->includes();
			$instance->setup_actions();
		}

		// Always return the instance.
		return $instance;

		// The last metroid is in captivity. The galaxy is at peace.
	}

	/**
	 * Bootstrap constants.
	 *
	 * @since 0.1.0
	 *
	 */
	private function constants() {

		// Path and URL.
		if ( ! defined( 'EDD_REVIEWS_PLUGIN_DIR' ) ) {
			define( 'EDD_REVIEWS_PLUGIN_DIR', plugin_dir_path( EDD_REVIEWS_PLUGIN_FILE ) );
		}

		if ( ! defined( 'EDD_REVIEWS_PLUGIN_URL' ) ) {
			define( 'EDD_REVIEWS_PLUGIN_URL', plugin_dir_url( EDD_REVIEWS_PLUGIN_FILE ) );
		}

	}

	/**
	 * Component global variables.
	 *
	 * @since 0.1.0
	 *
	 */
	private function setup_globals() {
		/** Versions **********************************************************/

		$this->version = '0.1.0';

		/** Paths**************************************************************/

		// BuddyPress root directory.
		$this->file       = constant( 'EDD_REVIEWS_PLUGIN_FILE' );
		$this->basename   = plugin_basename( EDD_REVIEWS_PLUGIN_FILE );
		$this->plugin_dir = trailingslashit( constant( 'EDD_REVIEWS_PLUGIN_DIR' ) );
		$this->plugin_url = trailingslashit( constant( 'EDD_REVIEWS_PLUGIN_URL' ) );

		// Languages.
		$this->lang_dir = $this->plugin_dir . 'languages';
	}

	/**
	 * Loads the plugin language files.
	 *
	 * @since  0.1.0
	 *
	 */
	public function load_textdomain() {
		// Traditional WordPress plugin locale filter.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'edd-reviews' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'edd-reviews', $locale );

		// Setup paths to current locale file.
		$mofile_local  = $this->lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/edd-reviews/' . $mofile;

		// Look in global /wp-content/languages/edd-reviews/ folder.
		if ( file_exists( $mofile_global ) ) {
			load_textdomain( 'edd-reviews', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/edd-reviews/languages/ folder.
			load_textdomain( 'edd-reviews', $mofile_local );
		} else { // Load the default language files.
			load_plugin_textdomain( 'edd-reviews', false, $this->lang_dir );
		}
	}

	/**
	 * Include required files.
	 *
	 * @since 0.1.0
	 *
	 */
	private function includes() {
		require( $this->plugin_dir . 'includes/core-functions.php' );
	}

	/**
	 * Set up the default hooks and actions.
	 *
	 * @since 0.1.0
	 *
	 */
	private function setup_actions() {
	}

}
