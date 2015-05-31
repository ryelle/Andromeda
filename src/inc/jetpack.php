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
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'type'      => 'click',
		'render'    => 'andromeda_infinite_scroll_render',
		'footer'    => 'page',
	) );

	add_theme_support( 'featured-content', array(
		'filter'     => 'andromeda_get_featured_content',
		'max_posts'  => 1,
		'post_types' => array( 'post' ),
	) );
}
add_action( 'after_setup_theme', 'andromeda_jetpack_setup' );

/**
 * Only enable infinite scroll on archive pages.
 *
 * @param  bool   $supported  True IS is already supported
 * @param  array  $settings   Configuration for infinite scroll
 * @return bool   True if IS should be enabled
 */
function andromeda_remove_infinite_scroll( $supported, $settings ) {
	if ( ! is_archive() ) {
		$supported = false;
	}
	return $supported;
}
add_filter( 'infinite_scroll_archive_supported', 'andromeda_remove_infinite_scroll', 10, 2 );

/**
 * Custom render function for Infinite Scroll.
 */
function andromeda_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'partial/content', get_post_format() );
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
