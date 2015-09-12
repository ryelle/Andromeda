<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Andromeda
 */

/**
 * Display an excerpt of a given length.
 * REQUIRES PHP 5.3+ :(
 */
function andromeda_excerpt( $length = 35 ) {
	$text = get_the_content('');
	$text = strip_shortcodes( $text );

	/** This filter is documented in wp-includes/post-template.php */
	$text = apply_filters( 'the_content', $text );
	$text = str_replace(']]>', ']]&gt;', $text);

	$text = wp_trim_words( $text, $length );

	echo apply_filters( 'the_excerpt', $text );

	printf( '<p class="read-more"><a href="%s" rel="bookmark">%s</a></p>',
		esc_url( get_permalink() ),
		sprintf(
			__( 'Continue reading %s', 'andromeda' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		)
	);
}

if ( ! function_exists( 'andromeda_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function andromeda_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		_x( '%s', 'post date', 'andromeda' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		'<span class="author vcard"><a class="url fn n" href="%s">%s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="sep"> | </span><span class="byline">' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'andromeda_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function andromeda_entry_footer() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		_x( 'Posted %s', 'post date', 'andromeda' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		'by <span class="author vcard"><a class="url fn n" href="%s">%s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);

	// Hide category and tag text for pages.
	$categories_html = '';
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'andromeda' ) );
		if ( $categories_list && andromeda_categorized_blog() ) {
			$categories_html = sprintf( '<span class="cat-links">' . __( 'in %1$s', 'andromeda' ) . '</span>', $categories_list );
		}
	}

	printf( '<p><span class="posted-on">%s</span> <span class="byline">%s</span> %s</p>',
		$posted_on,
		$byline,
		$categories_html
	);

	echo '<p>';
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'andromeda' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'andromeda' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'andromeda' ), __( '1 Comment', 'andromeda' ), __( '% Comments', 'andromeda' ) );
		echo '</span>';
	}

	edit_post_link( ' ' . __( 'Edit', 'andromeda' ), '<span class="edit-link">', '</span>' );
	echo '</p>';
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function andromeda_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'andromeda_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'andromeda_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so andromeda_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so andromeda_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in andromeda_categorized_blog.
 */
function andromeda_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'andromeda_categories' );
}
add_action( 'edit_category', 'andromeda_category_transient_flusher' );
add_action( 'save_post',     'andromeda_category_transient_flusher' );
