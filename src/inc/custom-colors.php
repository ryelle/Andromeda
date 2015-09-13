<?php
/**
 * Custom background & highlight colors
 *
 * @package Andromeda
 */

/**
 * Add background and highlight colors to the Color section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function andromeda_colors_customize_register( $wp_customize ) {

	$wp_customize->remove_setting( 'background_color' );

	// Background Color
	$wp_customize->add_setting( 'background-color', array(
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize, 'background-color', array(
		'settings'    => 'background-color',
		'section'     => 'colors',
		'label'       => __( 'Background Color', 'andromeda' ),
	) ) );

	// Text Color
	$wp_customize->add_setting( 'text-color', array(
		'default'           => '#333333',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize, 'text-color', array(
		'settings'    => 'text-color',
		'section'     => 'colors',
		'label'       => __( 'Text Color', 'andromeda' ),
	) ) );

	// Highlight Color
	$wp_customize->add_setting( 'accent-color', array(
		'default'           => '#1abc9c',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize, 'accent-color', array(
		'settings'    => 'accent-color',
		'section'     => 'colors',
		'label'       => __( 'Highlight Color', 'andromeda' ),
	) ) );
}
add_action( 'customize_register', 'andromeda_colors_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function andromeda_customize_preview_js() {
	wp_enqueue_script( 'andromeda-customize', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview', 'underscore' ), ANDROMEDA_VERSION, true );
	wp_localize_script( 'andromeda-customize', 'andromedaColors', array( 'url' => home_url( '/andromeda-css/' ) ) );
}
add_action( 'customize_preview_init', 'andromeda_customize_preview_js' );


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

		$this->print_css( array(
			'background' => get_theme_mod( 'background-color', '#222' ),
			'text' => get_theme_mod( 'text-color', '#ddd' ),
			'accent' => get_theme_mod( 'accent-color', '#e74c3c' ),
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

		$colors = array(
			'background' => '#222',
			'text' => '#ddd',
			'accent' => '#e74c3c',
		);
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
			 * @param string  $sass   The Sass used to generate the CSS
			 * @param string  $color  The color picked from the image, used as
			 *                        the $base-color for all other color variables
			 */
			$sass = apply_filters( 'andromeda_color_scheme', $sass ); //, $color );
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
