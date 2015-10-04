/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function() {
	wp.customize.bind( 'ready', function() {
		wp.customize( 'text-color' ).validate = function( to ){
			var backgroundHex = wp.customize( 'background-color' ).get();

			var background = new Color( backgroundHex );
			var foreground = new Color( to );
			var contrast = foreground.getReadableContrastingColor( background );

			return contrast.toString();
		};
	} );
} )();
