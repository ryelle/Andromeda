<?php
/**
 * Comment callbacks
 *
 * @package Andromeda
 */

/**
 * Comment display callback, determine which display function should be used based on comment type.
 * Pingbacks/trackbacks have a short display, while comments get full meta and author gravatar.
 *
 * @param object $comment The comment object.
 * @param array  $args    An array of arguments.
 * @param int    $depth   Depth of comment.
 */
function andromeda_handle_comment( $comment, $args, $depth ){
	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) {
		andromeda_ping( $comment, $args, $depth );
	} else {
		andromeda_comment( $comment, $args, $depth );
	}
}

/**
 * Display a comment.
 *
 * @param object $comment The comment object.
 * @param array  $args    An array of arguments.
 * @param int    $depth   Depth of comment.
 */
function andromeda_comment( $comment, $args, $depth ){ ?>

<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
	<footer class="comment-meta">
		<div class="comment-avatar vcard">
			<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div><!-- .comment-author -->

		<div class="comment-metadata">
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
				<time datetime="<?php comment_time( 'c' ); ?>">
					<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'andromeda' ), get_comment_date(), get_comment_time() ); ?>
				</time>
			</a>
			<?php edit_comment_link( ' &bull; ' . __( 'Edit', 'andromeda' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .comment-metadata -->

		<?php printf( '<p class="comment-author"><b class="fn">%s</b></p>', get_comment_author_link() ); ?>
	</footer><!-- .comment-meta -->

	<div class="comment-content">
		<?php comment_text(); ?>

		<footer class="comment-meta">
		<?php if ( '0' == $comment->comment_approved ) : ?>
			<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'andromeda' ); ?></p>
		<?php endif; ?>
		</footer><!-- .comment-meta -->

	</div><!-- .comment-content -->

	<?php comment_reply_link( array_merge( $args, array(
		'add_below' => 'div-comment',
		'depth'     => $depth,
		'max_depth' => $args['max_depth'],
		'before'    => '<div class="reply">',
		'after'     => '</div>'
	) ) ); ?>

</article><!-- .comment-body -->
<?php
}

/**
 * Display a pingback/trackback. Only display the pinging post.
 *
 * @param object $comment The comment object.
 * @param array  $args    An array of arguments.
 * @param int    $depth   Depth of comment.
 */
function andromeda_ping( $comment, $args, $depth ){ ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
<article class="comment-body">
	<footer class="comment-meta">
		<div class="comment-avatar vcard">
			<i class="fa fa-bell"></i>
		</div><!-- .comment-author -->
		<div class="comment-metadata">
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
				<time datetime="<?php comment_time( 'c' ); ?>">
					<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'andromeda' ), get_comment_date(), get_comment_time() ); ?>
				</time>
			</a>
			<?php edit_comment_link( __( 'Edit', 'andromeda' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .comment-metadata -->

		<?php printf( '<p class="comment-author"><b class="fn">%s</b></p>', get_comment_author_link() ); ?>
	</footer><!-- .comment-meta -->
</article><!-- .comment-body -->
<?php
}