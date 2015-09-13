/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	var getCSS = _.debounce( function( to ){
		var data = {
			'background': wp.customize( 'background-color' ).get(),
			'text': wp.customize( 'text-color' ).get(),
			'accent': wp.customize( 'accent-color' ).get(),
			'no-cache': true,
		};
		$.when(
			$.get( andromedaColors.url, data )
		).then( function( css ) {
			if ( ! $( '#andromeda-css' ).length ) {
				$( 'head' ).append( '<style id="andromeda-css"><style>' );
			}

			$( '#andromeda-css' ).text( css );
		} );
	}, 500 );

	wp.customize( 'background-color', function( value ) {
		value.bind( getCSS );
	} );
	wp.customize( 'text-color', function( value ) {
		value.bind( getCSS );
	} );
	wp.customize( 'accent-color', function( value ) {
		value.bind( getCSS );
	} );

} )( jQuery );
