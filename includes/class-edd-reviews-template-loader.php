<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template loader class.
 */
class EDD_Reviews_Template_Loader {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'edd_template_paths', array( __CLASS__, 'template_paths' ) );
		add_filter( 'comments_template', array( __CLASS__, 'comments_template_loader' ) );
	}

	/**
	 * [template_paths description]
	 * @param  [type] $file_paths [description]
	 * @return [type]             [description]
	 */
	public static function template_paths( $file_paths ) {
		$file_paths[50] = trailingslashit( edd_reviews()->plugin_path() ) . 'templates/';
		return $file_paths;
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

		// try locating this template file by looping through the template paths
		foreach ( edd_get_theme_template_paths() as $template_path ) {
			if ( file_exists( trailingslashit( $template_path ) . 'single-download-reviews.php' ) ) {
				return trailingslashit( $template_path ) . 'single-download-reviews.php';
			}
		}
	}

}
