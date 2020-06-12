<?php
/**
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * This template can be overridden by copying it to yourtheme/templates/single-download/review-meta.php.
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
	exit; // Exit if accessed directly.
}

global $comment;

if ( '0' === $comment->comment_approved ) :
	?>
	<p class="meta">
		<em class="edd-reviews-review__awaiting-approval">
			<?php esc_html_e( 'Your review is awaiting approval', 'easy-digital-downloads-reviews' ); ?>
		</em>
	</p>
<?php else : ?>
	<p class="meta">
		<strong class="edd-reviews-review__author"><?php comment_author(); ?> </strong>

		<?php
		$verified = edd_reviews_review_is_from_verified_owner( $comment->comment_ID );
		if ( edd_get_option( 'review_rating_verification_label', false ) && $verified ) {
			echo '<em class="edd-reviews-review__verified verified">(' . esc_attr__( 'verified owner', 'easy-digital-downloads-reviews' ) . ')</em> ';
		}
		?>

		<span class="edd-reviews-review__dash">&ndash;</span> <time class="edd-reviews-review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>"><?php echo esc_html( get_comment_date( get_option( 'date_format' ) ) ); ?></time>
	</p>
<?php endif; ?>
