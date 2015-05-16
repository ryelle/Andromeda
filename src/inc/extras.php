<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
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
		$html = sprintf( '<div class="placeholder" style="height:%spx;width:%spx;">%s</div>', $_wp_additional_image_sizes[$size]['height'], $_wp_additional_image_sizes[$size]['width'], $icon );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'andromeda_thumbnail_placeholder', 10, 5 );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function andromeda_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'andromeda' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'andromeda_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function andromeda_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'andromeda_render_title' );
endif;
