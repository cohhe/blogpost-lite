<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to vh_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage BlogPost
 */

?>
<div id="comments">
	<?php if ( post_password_required() ) { ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'vh' ); ?></p>
	</div><!-- end of comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		}
	?>

	<?php if ( have_comments() ) : ?>
			<?php

				printf( _n( '<span class="post_comment_title">Comment</span>', '<span class="post_comment_title">Comments</span>', get_comments_number(),'vh' ), number_format_i18n( get_comments_number() ) );

			?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<div id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'vh' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'vh' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'vh' ) ); ?></div>
		</div>
		<?php endif; // check for comment navigation ?>

		<ul class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use vh_comment() to format the comments.
				 * See vh_comment() for more.
				 */
				wp_list_comments( array( 'callback' => 'vh_comment' ) );
			?>
			<div class="clearfix"></div>
		</ul>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<div id="comment-nav-below">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'vh' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'vh' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'vh' ) ); ?></div>
		</div>
		<?php endif; // check for comment navigation ?>
	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'vh' ); ?></p>
	<?php endif; ?>

	<div class="content-form white-form">
		<?php
		if (empty($required_text)) {
			$required_text = '';
		}

		comment_form(
			array('comment_notes_after' => '',
					'logged_in_as' => '',
					'url' => '',
					'title_reply'      => __( 'Add comment', 'vh'),
					'comment_notes_before' => '',
					'label_submit'    => __( 'Add comment', 'vh'),
					'comment_field' =>  '<div class="comment-form-comment"><textarea id="comment" name="comment" placeholder="' . __('Your comment', 'vh') . '" cols="45" rows="8" aria-required="true">' . '</textarea><span class="comment-form-error">' . __('Enter your comment', 'vh') . '</span></div>'));
		?>
	</div>
</div><!-- end of comments -->
