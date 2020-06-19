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
	 * EDD_Reviews version.
	 *
	 * @var string
	 */
	public $version = '0.1.0';

	/**
	 * The single instance of the class.
	 *
	 * @var EDD_Reviews
	 * @since 0.1.0
	 */
	protected static $_instance = null;

	/**
	 * Main EDD_Reviews Instance.
	 *
	 * Ensures only one instance of EDD_Reviews is loaded or can be loaded.
	 *
	 * @since 0.1.0
	 * @static
	 * @see edd_reviews()
	 * @return EDD_Reviews - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Is not allowed to call from outside to prevent from creating multiple instances,
	 * to use the singleton, you have to obtain the instance from EDD_Reviews::instance() instead
	 */
	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 0.1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'easy-digital-downloads-reviews' ), '0.1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 0.1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'easy-digital-downloads-reviews' ), '0.1.0' );
	}

	/**
	 * Define plugin Constants.
	 */
	private function define_constants() {
		$this->define( 'EDD_REVIEWS_ABSPATH', dirname( EDD_REVIEWS_PLUGIN_FILE ) . '/' );
		$this->define( 'EDD_REVIEWS_PLUGIN_BASENAME', plugin_basename( EDD_REVIEWS_PLUGIN_FILE ) );
		$this->define( 'EDD_REVIEWS_VERSION', $this->version );
		$this->define( 'EDD_REVIEWS_TEMPLATE_DEBUG_MODE', false );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Returns true if the request is a non-legacy REST API request.
	 *
	 * Legacy REST requests should still run some extra code for backwards compatibility.
	 *
	 * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
	 *
	 * @return bool
	 */
	public function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		return apply_filters( 'edd_reviews_is_rest_api_request', $is_rest_api_request );
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! $this->is_rest_api_request();
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		// Functions.
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-core-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-conditional-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-template-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-user-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-template-hooks.php';

		// Classes.
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-post-types.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-comments.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-template-loader.php';

		if ( $this->is_request( 'admin' ) ) {
			include_once EDD_REVIEWS_ABSPATH . 'includes/admin/class-edd-reviews-admin.php';
			new EDD_Reviews_Admin();
		}

		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
			$this->frontend_hooks();
		}

		$this->theme_support_includes();
	}

	/**
	 * Include classes for theme support.
	 *
	 * @since 0.1.0
	 */
	private function theme_support_includes() {
		if ( edd_reviews_is_wp_default_theme_active() ) {
			switch ( get_template() ) {
				case 'twentytwenty':
					include_once EDD_REVIEWS_ABSPATH . 'includes/theme-support/class-edd-reviews-twenty-twenty.php';
					break;
			}
		}
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-frontend-scripts.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 0.1.0
	 */
	public function frontend_hooks() {
		EDD_Reviews_Frontend_Scripts::init();
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 0.1.0
	 */
	private function init_hooks() {
		EDD_Reviews_Post_Types::init();
		EDD_Reviews_Template_Loader::init();
		EDD_Reviews_Comments::init();
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', EDD_REVIEWS_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( EDD_REVIEWS_PLUGIN_FILE ) );
	}

}
