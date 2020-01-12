<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
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

<div id="comments" class="comments-area content-block">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				if (get_comments_number()==1) {
					printf( smt_translate( 'formoneresponse' ), '<em>' . get_the_title() . '</em>' );
				} else {
					printf( smt_translate( 'formmultiresponse' ), '<em>' . get_the_title() . '</em>', get_comments_number() );
				}
			?>
		</h3>



		<ul class="comment-list">
			<?php
				wp_list_comments( array(
					'callback' => 'smt_comment',
					'short_ping'  => true,
					'avatar_size' => 56,
				) );
			?>
		</ul><!-- .comment-list -->


	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php smt_translate( 'disabledcomments' ); ?></p>
	<?php endif; ?>
	
	<?php $commenter = wp_get_current_commenter(); ?>
	<?php $req = get_option( 'require_name_email' ); ?>
	
	<?php comment_form( array (
		'comment_field' => '<div class="smt-field">
			<span>' . smt_translate( 'comment' ) . '</span>
			<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . smt_translate( 'comment' ) . '"></textarea>
			</div>',
			
		'fields' => array(
		
			'author' => '<div class="smt-field">
				<span>' . smt_translate( 'name' ) . ( $req ? '<abbr class="required" title="required">*</abbr>' : '' ) . '</span>
				<input type="text" class="input-text" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) .'" />
			</div>',

		  'email' => '<div class="smt-field">
				<span>' . smt_translate( 'mail' ) . ( $req ? '<abbr class="required" title="required">*</abbr>' : '' ) . '</span>
				<input type="text" class="input-text" name="email" id="email" value="' . esc_attr(  $commenter['comment_author_email'] )  .'" />
			</div>',

		  'url' => '<div class="smt-field">
				<span>' . smt_translate( 'website' ) . ( $req ? '<abbr class="required" title="required">*</abbr>' : '' ) . '</span>
				<input type="text" class="input-text" name="url" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) .'" />
			</div>',
		),
		
		'comment_notes_before' => '<p class="comment-notes">' . smt_translate( 'comment_notes_before' ) . ( $req && isset($required_text) ? $required_text : '' ) . '</p>',
		
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( smt_translate( 'loggedinas' ).' <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">'.smt_translate( 'logout' ).'?</a>', admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ) ) ) ) . '</p>',
		
	) ); ?>

</div><!-- .comments-area -->
