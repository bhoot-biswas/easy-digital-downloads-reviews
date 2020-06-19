<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EDD_Reviews
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="reviews" class="edd-reviews">
	<div id="comments" class="comments-area">
		<?php
		// You can start editing here -- including this comment!
		if ( have_comments() ) :
			?>
			<h2 class="comments-title">
				<?php
				$edd_reviews_comment_count = get_comments_number();
				if ( '1' === $edd_reviews_comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html__( 'One review for &ldquo;%1$s&rdquo;', 'easy-digital-downloads-reviews' ),
						'<span>' . get_the_title() . '</span>'
					);
				} else {
					printf( // WPCS: XSS OK.
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s review for &ldquo;%2$s&rdquo;', '%1$s reviews for &ldquo;%2$s&rdquo;', $edd_reviews_comment_count, 'comments title', 'easy-digital-downloads-reviews' ) ),
						number_format_i18n( $edd_reviews_comment_count ),
						'<span>' . get_the_title() . '</span>'
					);
				}
				?>
			</h2><!-- .comments-title -->

			<?php the_comments_navigation(); ?>

			<ol class="comment-list">
				<?php wp_list_comments( apply_filters( 'edd_download_review_list_args', array( 'callback' => 'edd_reviews_comments' ) ) ); ?>
			</ol><!-- .comment-list -->

			<?php
			the_comments_navigation();

			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() ) :
				?>
				<p class="no-comments"><?php esc_html_e( 'Reviews are closed.', 'easy-digital-downloads-reviews' ); ?></p>
				<?php
			endif;

		endif; // Check for have_comments().
		?>

		<?php if ( false === edd_get_option( 'review_rating_verification_required', false ) || edd_has_user_purchased( get_current_user_id(), get_queried_object_id() ) ) : ?>
			<div id="review_form_wrapper">
				<div id="review_form">
					<?php
					$commenter     = wp_get_current_commenter();
					$comments_args = array(
						'fields'         => array(),
						'comment_field'  => '',
						'logged_in_as'   => '',
						/* translators: %s is product title */
						'title_reply'    => have_comments() ? esc_html__( 'Write a Review', 'easy-digital-downloads-reviews' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'easy-digital-downloads-reviews' ), get_the_title() ),
						/* translators: %s is product title */
						'title_reply_to' => esc_html__( 'Leave a Reply to %s', 'easy-digital-downloads-reviews' ),
						'label_submit'   => esc_html__( 'Post Review', 'easy-digital-downloads-reviews' ),
					);

					$name_email_required = (bool) get_option( 'require_name_email', 1 );
					$fields              = array(
						'author' => array(
							'label'    => __( 'Name', 'easy-digital-downloads-reviews' ),
							'type'     => 'text',
							'value'    => $commenter['comment_author'],
							'required' => $name_email_required,
						),
						'email'  => array(
							'label'    => __( 'Email', 'easy-digital-downloads-reviews' ),
							'type'     => 'email',
							'value'    => $commenter['comment_author_email'],
							'required' => $name_email_required,
						),
					);

					foreach ( $fields as $key => $field ) {
						$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
						$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

						if ( $field['required'] ) {
							$field_html .= '&nbsp;<span class="required">*</span>';
						}

						$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

						$comments_args['fields'][ $key ] = $field_html;
					}

					$purchase_history = edd_get_option( 'purchase_history_page', 0 );
					if ( ! empty( $purchase_history ) ) {
						/* translators: %s opening and closing link tags respectively */
						$comments_args['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'easy-digital-downloads-reviews' ), '<a href="' . esc_url( get_permalink( $purchase_history ) ) . '">', '</a>' ) . '</p>';
					}

					if ( edd_reviews_review_ratings_enabled() ) {
						$comments_args['comment_field'] = '<p class="comment-form-rating">
							<label for="rating">' . esc_html__( 'Your rating', 'easy-digital-downloads-reviews' ) . '</label>
							<select name="rating" id="rating" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'easy-digital-downloads-reviews' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'easy-digital-downloads-reviews' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'easy-digital-downloads-reviews' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'easy-digital-downloads-reviews' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'easy-digital-downloads-reviews' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'easy-digital-downloads-reviews' ) . '</option>
							</select>
						</p>';
					}

					$comments_args['comment_field'] .= '<p class="comment-form-comment">
						<label for="comment">' . esc_html__( 'Your review', 'easy-digital-downloads-reviews' ) . '&nbsp;<span class="required">*</span></label>
						<textarea id="comment" name="comment" cols="45" rows="8" required></textarea>
					</p>';

					comment_form( apply_filters( 'edd_reviews_product_review_comment_form_args', $comments_args ) );
					?>
				</div>
			</div>
		<?php else : ?>
			<p class="edd-reviews-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'easy-digital-downloads-reviews' ); ?></p>
		<?php endif; ?>
	</div><!-- #comments -->
</div>
