<?php
/**
 * Andromeda functions and definitions
 *
 * @package Andromeda
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 920; /* pixels */
}

if ( ! function_exists( 'andromeda_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function andromeda_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Andromeda, use a find and replace
	 * to change 'andromeda' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'andromeda', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 350, 265, true );
	add_image_size( 'full-width', 920, 9999 );
	add_image_size( 'feature', 640, 480 );
	add_image_size( 'small', 200, 150, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'andromeda' ),
		'social' => __( 'Social Menu', 'andromeda' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'andromeda_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // andromeda_setup
add_action( 'after_setup_theme', 'andromeda_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function andromeda_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'andromeda' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'andromeda' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'andromeda_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function andromeda_scripts() {
	wp_enqueue_style( 'andromeda-style', get_stylesheet_uri() );

	wp_enqueue_script( 'andromeda-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'andromeda-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'andromeda_scripts' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of PT Serif, and Prata by default is
 * localized. For languages that use characters not supported by either
 * font, the font can be disabled.
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function andromeda_google_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by PT Serif, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$pt_serif = _x( 'on', 'PT Serif font: on or off', 'andromeda' );

	/* Translators: If there are characters in your language that are not
	 * supported by Prata, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$prata = _x( 'on', 'Prata font: on or off', 'andromeda' );

	if ( 'off' !== $pt_serif || 'off' !== $prata ) {
		$font_families = array();

		if ( 'off' !== $pt_serif ) {
			$font_families[] = 'PT+Serif:400'; //Only comes in 400
		}

		if ( 'off' !== $prata ) {
			$font_families[] = 'Prata:400';
		}

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => implode( '|', $font_families ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Loads our special font CSS file.
 *
 * To disable in a child theme, use remove_action
 * function mytheme_dequeue_fonts() {
 *     remove_action( 'wp_enqueue_scripts', 'andromeda_fonts' );
 * }
 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
 *
 * @return void
 */
function andromeda_fonts() {
	$fonts_url = andromeda_google_fonts_url();
	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'andromeda-serifs', esc_url_raw( $fonts_url ), array(), null );
	}

	/* Translators: If there are characters in your language that are not
	 * supported by Aileron, translate this to 'off'. Do not translate into
	 * your own language.
	 */
	$aileron = _x( 'on', 'Aileron font: on or off', 'andromeda' );

	// if ( 'off' !== $aileron ) {
	// 	$fonts_url = get_template_directory_uri() . '/font/aileron.css';
	// 	wp_enqueue_style( 'andromeda-sans', esc_url_raw( $fonts_url ), array(), null );
	// }
}
add_action( 'wp_enqueue_scripts', 'andromeda_fonts' );

/**
 * Enqueue Google fonts style to admin screens for TinyMCE typography dropdown.
 */
function andromeda_admin_fonts( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post-new.php', 'post.php' ) ) ) {
		return;
	}

	andromeda_fonts();

}
add_action( 'admin_enqueue_scripts', 'andromeda_admin_fonts' );

/**
 * Load Featured Categories feature.
 */
require get_template_directory() . '/inc/featured-categories.php';

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
