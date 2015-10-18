/* global jQuery, console */
/**
 * overlap.js
 *
 * Check if the social and primary navigation are overlapping, and if so, add a class.
 * Should also be throttled on resize.
 */
( function( $ ) {
	'use strict';

	var detectOverlap = function(){

		var primary = document.getElementById( 'primary-menu' );
		var social = document.getElementById( 'social-menu' );

		// Make sure we have both menus. Unnecessary if not.
		if ( 'undefined' === typeof primary || 'undefined' === typeof social || null === primary || null === social ) {
			return;
		}

		var w = $( social ).width();
		var offset = $( primary ).find('li').first().position().left;

		if ( offset < w ) {
			$( primary ).closest( 'nav' ).addClass( 'overlapped' );
		} else {
			$( primary ).closest( 'nav' ).removeClass( 'overlapped' );
		}
	};

	detectOverlap();
	$( window ).smartresize( detectOverlap );

} )( jQuery );
