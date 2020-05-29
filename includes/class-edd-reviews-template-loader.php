<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template loader class.
 */
class EDD_Reviews_Template_Loader {

	public function __construct() {
		add_filter( 'comments_template', array( $this, 'comments_template_loader' ) );
	}

	/**
	 * Load comments template.
	 *
	 * @param string $template template to load.
	 * @return string
	 */
	public static function comments_template_loader( $template ) {
		if ( get_post_type() !== 'download' ) {
			return $template;
		}

		$template_dir = edd_get_theme_template_dir_name();

		$check_dirs = array(
			trailingslashit( get_stylesheet_directory() ) . $template_dir,
			trailingslashit( get_template_directory() ) . $template_dir,
			trailingslashit( get_stylesheet_directory() ),
			trailingslashit( get_template_directory() ),
			trailingslashit( edd_reviews()->plugin_path() ) . 'templates/',
		);

		if ( EDD_REVIEWS_TEMPLATE_DEBUG_MODE ) {
			$check_dirs = array( array_pop( $check_dirs ) );
		}

		foreach ( $check_dirs as $dir ) {
			if ( file_exists( trailingslashit( $dir ) . 'single-download-reviews.php' ) ) {
				return trailingslashit( $dir ) . 'single-download-reviews.php';
			}
		}
	}

}
