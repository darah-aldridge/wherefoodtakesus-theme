document.querySelectorAll( '.schema-faq-question' ).forEach( function ( question ) {
	var section = question.closest( '.schema-faq-section' );
	if ( ! section ) {
		return;
	}

	question.setAttribute( 'role', 'button' );
	question.setAttribute( 'tabindex', '0' );
	question.setAttribute( 'aria-expanded', 'false' );

	function toggle() {
		var isOpen = section.classList.toggle( 'is-open' );
		question.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
	}

	question.addEventListener( 'click', toggle );
	question.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Enter' || e.key === ' ' ) {
			e.preventDefault();
			toggle();
		}
	} );
} );