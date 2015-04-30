<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Andromeda
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function andromeda_jetpack_setup() {
	// add_theme_support( 'infinite-scroll', array(
	// 	'container' => 'main',
	// 	'render'    => 'andromeda_infinite_scroll_render',
	// 	'footer'    => 'page',
	// ) );

	add_theme_support( 'featured-content', array(
		'filter'     => 'andromeda_get_featured_content',
		'max_posts'  => 1,
		'post_types' => array( 'post' ),
	) );
}
add_action( 'after_setup_theme', 'andromeda_jetpack_setup' );

function andromeda_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function andromeda__infinite_scroll_render

/**
 * Pull featured content from Jetpack
 */
function andromeda_get_featured_posts(){
	return apply_filters( 'andromeda_get_featured_content', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @return bool Whether there are featured posts.
 */
function andromeda_has_featured_posts() {
	return ! is_paged() && (bool) andromeda_get_featured_posts();
}
