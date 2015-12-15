/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 * Things like site title, description, and background color changes.
 */

(function($) {
	wp.customize( 'vh_color_scheme', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).removeClass( 'yellow-color-scheme green-color-scheme red-color-scheme gray-color-scheme' ).addClass(to);
		});
	} );

	// Hook into background color change and adjust body class value as needed.
	wp.customize('background_color', function( value ) {
		value.bind( function( to ) {
			if ( '#f9f7f8' == to ) {
				jQuery( 'body' ).addClass( 'custom-background-white' );
			} else if ( '' == to ) {
				jQuery( 'body' ).addClass( 'custom-background-empty' );
			} else {
				jQuery( 'body' ).removeClass( 'custom-background-empty custom-background-white' );
			}
		});
	});
})(jQuery);