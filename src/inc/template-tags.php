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
