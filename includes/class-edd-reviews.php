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
		_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'edd-reviews' ), '0.1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 0.1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'edd-reviews' ), '0.1.0' );
	}

	/**
	 * Define plugin Constants.
	 */
	private function define_constants() {
		$this->define( 'EDD_REVIEWS_ABSPATH', dirname( EDD_REVIEWS_PLUGIN_FILE ) . '/' );
		$this->define( 'EDD_REVIEWS_PLUGIN_BASENAME', plugin_basename( EDD_REVIEWS_PLUGIN_FILE ) );
		$this->define( 'EDD_REVIEWS_TEMPLATE_DEBUG_MODE', false );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-core-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-conditional-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/edd-reviews-template-functions.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-post-types.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-comments.php';
		include_once EDD_REVIEWS_ABSPATH . 'includes/class-edd-reviews-template-loader.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 0.1.0
	 */
	private function init_hooks() {
		new EDD_Reviews_Post_Types();
		new EDD_Reviews_Template_Loader();
		new EDD_Reviews_Comments();
		add_action( 'edd_after_download_content', 'edd_reviews_append_comments' );
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
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( EDD_REVIEWS_PLUGIN_FILE ) );
	}

}
