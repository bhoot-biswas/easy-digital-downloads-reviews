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

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="reviews" class="reviews-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$_s_comment_count = get_comments_number();
			if ( '1' === $_s_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', '_s' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf( // WPCS: XSS OK.
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $_s_comment_count, 'comments title', '_s' ) ),
					number_format_i18n( $_s_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', '_s' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	$commenter    = wp_get_current_commenter();
	$comment_form = array(
		/* translators: %s is product title */
		'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
		/* translators: %s is product title */
		'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
		'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
		'title_reply_after'   => '</span>',
		'comment_notes_after' => '',
		'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
		'logged_in_as'        => '',
		'comment_field'       => '',
	);

	$name_email_required = (bool) get_option( 'require_name_email', 1 );
	$fields              = array(
		'author' => array(
			'label'    => __( 'Name', 'woocommerce' ),
			'type'     => 'text',
			'value'    => $commenter['comment_author'],
			'required' => $name_email_required,
		),
		'email'  => array(
			'label'    => __( 'Email', 'woocommerce' ),
			'type'     => 'email',
			'value'    => $commenter['comment_author_email'],
			'required' => $name_email_required,
		),
	);

	$comment_form['fields'] = array();

	foreach ( $fields as $key => $field ) {
		$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
		$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

		if ( $field['required'] ) {
			$field_html .= '&nbsp;<span class="required">*</span>';
		}

		$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

		$comment_form['fields'][ $key ] = $field_html;
	}

	$purchase_history = edd_get_option( 'purchase_history_page', 0 );
	if ( ! empty( $purchase_history ) ) {
		/* translators: %s opening and closing link tags respectively */
		$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( get_permalink( $purchase_history ) ) . '">', '</a>' ) . '</p>';
	}

	$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" required>
        <option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
        <option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
        <option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
        <option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
        <option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
        <option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
    </select></div>';

	$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

	comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
	?>

</div><!-- #comments -->
