<?php
/**
 * Andromeda Theme Customizer
 *
 * @package Andromeda
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function andromeda_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_section( 'andromeda_settings' , array(
		'title'      => __( 'Front Page Setup', 'andromeda' ),
		'priority'   => 30,
		'description' => sprintf( __( 'These settings only affect your home page, %s', 'andromeda' ), esc_url_raw( home_url() ) ),
	) );

	// home-per-page: number
	$wp_customize->add_setting( 'home-per-page', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'home-per-page', array(
		'label'       => __( 'Number of recent posts', 'andromeda' ),
		'section'     => 'andromeda_settings',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 10 ),
		'description' => __( 'We recommend 3.', 'andromeda' ),
	) );

	// first-home-category: cat dropdown
	$wp_customize->add_setting( 'first-home-category', array(
		'default'           => false,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new Andromeda_Category_Customize_Control( $wp_customize, 'first-home-category', array(
		'label'       => __( 'First featured category', 'andromeda' ),
		'section'     => 'andromeda_settings',
	) ) );

	// second-home-category: cat dropdown
	$wp_customize->add_setting( 'second-home-category', array(
		'default'           => false,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new Andromeda_Category_Customize_Control( $wp_customize, 'second-home-category', array(
		'label'       => __( 'Second featured category', 'andromeda' ),
		'section'     => 'andromeda_settings',
	) ) );

	// third-home-category: cat dropdown
	$wp_customize->add_setting( 'third-home-category', array(
		'default'           => false,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new Andromeda_Category_Customize_Control( $wp_customize, 'third-home-category', array(
		'label'       => __( 'Third featured category', 'andromeda' ),
		'section'     => 'andromeda_settings',
	) ) );

	// copyright: text
	$wp_customize->add_setting( 'copyright', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'copyright', array(
		'label'       => __( 'Copyright', 'andromeda' ),
		'section'     => 'andromeda_settings',
		'type'        => 'text',
		'description' => __( 'Appears in the footer.', 'andromeda' ),
	) );
}
add_action( 'customize_register', 'andromeda_customize_register' );

if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Custom control for the category dropdown
	 */
	class Andromeda_Category_Customize_Control extends WP_Customize_Control {
		/**
		 * Render the control's content. Copied from WP_Customize_Control
		 *
		 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
		 *
		 * Supports basic input types `text`, `checkbox`, `textarea`, `radio`, and `select`.
		 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
		 */
		protected function render_content() {
			$dropdown = wp_dropdown_categories( array(
				'name'              => '_customize-dropdown-category-' . $this->id,
				'echo'              => 0,
				'show_option_none'  => __( '&mdash; Select &mdash;', 'andromeda' ),
				'option_none_value' => '0',
				'selected'          => $this->value(),
			) );

			// Hackily add in the data link parameter.
			$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

			printf(
				'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
				$this->label,
				$dropdown
			);
		}
	}
}

