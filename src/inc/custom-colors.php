<?php
/**
 * Custom background & highlight colors
 *
 * @package Andromeda
 */

/**
 * Return the default theme colors, with optional filter for theme authors to customize.
 *
 * @return array Associative array of default colors for theme.
 */
function andromeda_get_default_colors() {
	$defaults = array(
		'background' => '#ffffff',
		'text' => '#333333',
		'accent' => '#1abc9c',
	);

	/**
	 * Filter the default color variables, to adjust or set up new defaults.
	 *
	 * @param array  $defaults  The array of default color hex codes
	 */
	return apply_filters( 'andromeda_colors', $defaults );
}

/**
 * Add background and highlight colors to the Color section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function andromeda_colors_customize_register( $wp_customize ) {
	$colors = andromeda_get_default_colors();

	$wp_customize->remove_setting( 'background_color' );

	// Background Color
	$wp_customize->add_setting( 'background-color', array(
		'default'           => $colors['background'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background-color', array(
		'settings'    => 'background-color',
		'section'     => 'colors',
		'label'       => __( 'Background Color', 'andromeda' ),
	) ) );

	// Text Color
	$wp_customize->add_setting( 'text-color', array(
		'default'           => $colors['text'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text-color', array(
		'settings'    => 'text-color',
		'section'     => 'colors',
		'label'       => __( 'Text Color', 'andromeda' ),
	) ) );

	// Highlight Color
	$wp_customize->add_setting( 'accent-color', array(
		'default'           => $colors['accent'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent-color', array(
		'settings'    => 'accent-color',
		'section'     => 'colors',
		'label'       => __( 'Highlight Color', 'andromeda' ),
	) ) );
}
add_action( 'customize_register', 'andromeda_colors_customize_register' );

/**
 * Load our JS file in the preview frame of the customizer. This controls the "live preview" for this theme.
 */
function andromeda_customize_preview_js() {
	wp_enqueue_script( 'andromeda-customize', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview', 'underscore' ), ANDROMEDA_VERSION, true );
	wp_localize_script( 'andromeda-customize', 'andromedaColors', array( 'url' => home_url( '/andromeda-css/' ) ) );
}
add_action( 'customize_preview_init', 'andromeda_customize_preview_js' );

/**
 * Load our JS file in the controls frame of the customizer. This sets up validation functions.
 */
function andromeda_customize_validate_js() {
	wp_enqueue_script( 'andromeda-validate', get_template_directory_uri() . '/js/customizer-validate.js', array(), ANDROMEDA_VERSION, true );
}
add_action( 'customize_controls_print_footer_scripts', 'andromeda_customize_validate_js', 0, 99 );

/**
 * Container class for the color functionality
 */
class Andromeda_Colors {

	private $cache_prefix = 'andromeda_css_';

	function __construct() {
		add_action( 'wp_head', array( $this, 'wp_head' ) );

		add_action( 'init',              array( $this, 'rewrites' ) );
		add_action( 'template_redirect', array( $this, 'ajax_css' ) );

		if ( defined( 'ANDROMEDA_VERSION' ) ) {
			$this->cache_prefix .= ANDROMEDA_VERSION . '_';
		}
	}

	/**
	 * Register the rewrites for the AJAX CSS endpoint
	 */
	public function rewrites(){
		if ( ! class_exists( 'Jetpack' ) ) {
			return;
		}

		add_rewrite_tag( '%andromeda%', '([^&]+)' );
		add_rewrite_rule( '^andromeda-css/?', 'index.php?andromeda=true', 'top' );
	}

	public function wp_head() {
		if ( ! class_exists( 'Jetpack' ) ) {
			return;
		}

		$defaults = andromeda_get_default_colors();

		$this->print_css( array(
			'background' => get_theme_mod( 'background-color', $defaults['background'] ),
			'text' => get_theme_mod( 'text-color', $defaults['text'] ),
			'accent' => get_theme_mod( 'accent-color', $defaults['accent'] ),
		) );
	}

	public function print_css( $colors ){
		$css = false;

		if ( $colors ) {
			$css = $this->generate_css( $colors );
		}

		if ( $css ) {
			printf( '<style id="andromeda-css">%s</style>', $css );
		}
	}

	/**
	 * Build & send the compiled CSS based on query parameters.
	 *
	 * GET expects `background`, `text`, `accent`, and will use default colors
	 * if any are not passed through.
	 * @return void  Sends CSS and dies.
	 */
	public function ajax_css(){
		if ( ! get_query_var( 'andromeda' ) ){
			return;
		}

		$cache = true;
		if ( isset( $_GET['no-cache'] ) ){
			$cache = false;
		}

		$colors = andromeda_get_default_colors();

		// Override the defaults with our custom colors
		foreach ( $colors as $key => $default ) {
			if ( isset( $_GET[ $key ] ) ) {
				$color = str_replace( htmlentities('#'), '', $_GET[ $key ] );
				$valid_color = preg_match( '/^[0-9a-f]{3}([0-9a-f]{3})?$/i', $color );
				if ( $valid_color ) {
					$colors[ $key ] = '#' . $color;
				}
			}
		}
		$css = $this->generate_css( $colors, $cache );

		header( 'Content-type: text/css; charset: UTF-8' );
		echo $css;
		die();
	}

	public function generate_css( $colors, $cache = true ) {
		if ( ! class_exists( 'Jetpack_Custom_CSS' ) ) {
			require Jetpack::get_module_path( 'custom-css' );
		}

		// Let's not generate lots of cache entries when customizing
		if ( is_customize_preview() ) {
			$cache = false;
		}

		$base_scss = file_get_contents( __DIR__ . '/_colors.scss' );

		ksort( $colors );
		$colors_hash = md5( json_encode( $colors ) );

		$key = $this->cache_prefix . $colors_hash;
		$css = ( $cache ) ? get_transient( $key ) : false;
		if ( ! $css ){
			$vars  = '$color__background-body: ' . $colors['background'] . '; ';
			$vars .= '$color__text-main: ' . $colors['text'] . '; ';
			$vars .= '$color__link-accent: ' . $colors['accent'] . '; ';
			$sass  = $vars . $base_scss;

			/**
			 * Filter the sass used for dynamic color CSS generation
			 *
			 * @param string  $sass  The Sass used to generate the CSS.
			 */
			$sass = apply_filters( 'andromeda_color_scheme', $sass );
			$css = Jetpack_Custom_CSS::minify( $sass, 'sass' );

			if ( $cache && $css ) {
				set_transient( $key, $css, WEEK_IN_SECONDS );
			}
		}

		return $css;
	}

}

global $andromeda_colors;
$andromeda_colors = new Andromeda_Colors();
