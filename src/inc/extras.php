<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * @package Andromeda
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function andromeda_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_singular() ) {
		$classes[] = 'singular';
	}

	if ( is_home() && ! andromeda_has_featured_posts() ) {
		$classes[] = 'no-featured-posts';
	}

	return $classes;
}
add_filter( 'body_class', 'andromeda_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function andromeda_post_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( ! has_post_thumbnail() ) {
		$classes[] = 'no-image';
	}

	return $classes;
}
add_filter( 'post_class', 'andromeda_post_classes' );

/**
 * Adds custom classes to the array of nav item classes.
 *
 * @param array $classes Classes for the current nav item.
 * @return array
 */
function andromeda_nav_menu_classes( $classes, $item, $args, $depth ) {
	$classes[] = "depth-$depth";
	return $classes;
}
add_filter( 'nav_menu_css_class', 'andromeda_nav_menu_classes', 10, 4 );

/**
 * Set the number of posts displayed on the homepage. Set in the customizer.
 */
function andromeda_pre_get_posts( $query ){
	if ( ! $query->is_main_query() ) {
		return;
	}
	if ( is_home() && ! is_paged() ){
		$per_page = get_theme_mod( 'home-per-page', 3 );
		$query->set( 'posts_per_page', $per_page );
	}
}
add_filter( 'pre_get_posts', 'andromeda_pre_get_posts' );

/**
 * Make sure to use the paged.php template for older pages on home.
 */
function andromeda_home_template( $template ){
	if ( is_paged() ){
		$template = locate_template( array( 'paged' ) );
	}
	return $template;
}
add_filter( 'home_template', 'andromeda_home_template' );

/**
 * Return an empty div placeholder if there is no featured image.
 */
function andromeda_thumbnail_placeholder( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	global $_wp_additional_image_sizes;
	if ( '' == $html && isset( $_wp_additional_image_sizes[$size] ) ) {
		switch ( get_post_format() ) {
			case 'image':
				$icon = '<i class="fa fa-camera fa-3x"></i>';
				break;
			case 'gallery':
				$icon = '<i class="fa fa-image fa-3x"></i>';
				break;
			case 'audio':
				$icon = '<i class="fa fa-music fa-3x"></i>';
				break;
			case 'video':
				$icon = '<i class="fa fa-video-camera fa-3x"></i>';
				break;
			case 'quote':
				$icon = '<i class="fa fa-quote-left fa-3x"></i>';
				break;
			case 'link':
				$icon = '<i class="fa fa-link fa-3x"></i>';
				break;
			case 'chat':
				$icon = '<i class="fa fa-comment fa-3x"></i>';
				break;
			default:
				$icon = '<i class="fa fa-pencil fa-3x"></i>';
		}
		$html = sprintf( '<div style="width:%spx;"><span class="placeholder">%s</span></div>', $_wp_additional_image_sizes[$size]['width'], $icon );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'andromeda_thumbnail_placeholder', 10, 5 );
