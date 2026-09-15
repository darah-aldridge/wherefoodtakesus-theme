var trigger = document.querySelector( '.wftu-search-trigger' );
var overlay = document.getElementById( 'wftu-search-overlay' );

if ( trigger && overlay ) {
	var closeBtn = overlay.querySelector( '.wftu-search-overlay__close' );
	var input = overlay.querySelector( 'input[name="s"]' );

	trigger.setAttribute( 'role', 'button' );
	trigger.setAttribute( 'tabindex', '0' );
	trigger.setAttribute( 'aria-expanded', 'false' );
	trigger.setAttribute( 'aria-controls', 'wftu-search-overlay' );

	function openOverlay() {
		overlay.hidden = false;
		requestAnimationFrame( function () {
			overlay.classList.add( 'is-open' );
		} );
		trigger.setAttribute( 'aria-expanded', 'true' );
		input.focus();
	}

	function closeOverlay() {
		overlay.classList.remove( 'is-open' );
		trigger.setAttribute( 'aria-expanded', 'false' );
		setTimeout( function () {
			overlay.hidden = true;
		}, 200 );
	}

	trigger.addEventListener( 'click', openOverlay );

	trigger.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Enter' || e.key === ' ' ) {
			e.preventDefault();
			openOverlay();
		}
	} );

	closeBtn.addEventListener( 'click', closeOverlay );

	overlay.addEventListener( 'click', function ( e ) {
		if ( e.target === overlay ) {
			closeOverlay();
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && overlay.classList.contains( 'is-open' ) ) {
			closeOverlay();
		}
	} );
}