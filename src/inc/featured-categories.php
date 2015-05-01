<?php
/**
 * Functionality for the Featured Categories displayed on the homepage
 *
 * @package Andromeda
 */

/**
 * Pull featured categories out of theme mods, filter down to just valid categories.
 *
 * @return  array  List of 0-3 category IDs
 */
function andromeda_get_featured_categories(){
	$categories = array();
	$categories[] = get_theme_mod( 'first-home-category', false );
	$categories[] = get_theme_mod( 'second-home-category', false );
	$categories[] = get_theme_mod( 'third-home-category', false );
	$categories = array_filter( $categories );

	return $categories;
}

/**
 * Classes for the featured category section
 */
function andromeda_featured_cats_classes( $query, $class = '' ) {
	$classes = array( 'featured-category' );
	if ( $query->post_count >= 5 ) { // 5 & 6
		$classes[] = 'six-up';
	} elseif ( $query->post_count == 4 ) {
		$classes[] = 'four-up';
	} elseif ( $query->post_count == 3 ) {
		$classes[] = 'three-up';
	} elseif ( $query->post_count <= 2 ) { // 2 & 1
		$classes[] = 'two-up';
	}
	$classes[] = $class;
	echo join( ' ', $classes );
}

/**
 * Grab the title for a given term + taxonomy
 *
 * @param  int  Term ID
 * @param  string  Taxonomy name
 * @param  bool  Print out the term name, Default: true
 */
function andromeda_single_term_title( $term, $taxonomy, $display = true ){
	$term = get_term_by( 'id', $term, $taxonomy );
	if ( ! $term ) {
		return false;
	}

	if ( 'category' == $taxonomy ) {
		$term_name = apply_filters( 'single_cat_title', $term->name );
	} elseif ( 'post_tag' == $taxonomy ) {
		$term_name = apply_filters( 'single_tag_title', $term->name );
	} else {
		$term_name = apply_filters( 'single_term_title', $term->name );
	}
	if ( $display ) {
		echo $term_name;
	} else {
		return $term_name;
	}
}
