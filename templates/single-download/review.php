<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/templates/single-download/review.php.
 *
 * HOWEVER, on occasion Edd_Reviews will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Edd_Reviews/Templates
 * @version 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container">

		<?php
		/**
		 * The edd_reviews_review_before hook
		 *
		 * @hooked edd_reviews_review_display_gravatar - 10
		 */
		do_action( 'edd_reviews_review_before', get_comment() );
		?>

		<div class="comment-text">

			<?php
			/**
			 * The edd_reviews_review_before_comment_meta hook.
			 *
			 * @hooked edd_reviews_review_display_rating - 10
			 */
			do_action( 'edd_reviews_review_before_comment_meta', get_comment() );

			/**
			 * The edd_reviews_review_meta hook.
			 *
			 * @hooked edd_reviews_review_display_meta - 10
			 */
			do_action( 'edd_reviews_review_meta', get_comment() );

			do_action( 'edd_reviews_review_before_comment_text', get_comment() );

			/**
			 * The edd_reviews_review_comment_text hook
			 *
			 * @hooked edd_reviews_review_display_comment_text - 10
			 */
			do_action( 'edd_reviews_review_comment_text', get_comment() );

			do_action( 'edd_reviews_review_after_comment_text', get_comment() );
			?>

		</div>
	</div>
