document.querySelectorAll( '.wftu-toc__toggle' ).forEach( function ( toggle ) {
	var list = document.getElementById( toggle.getAttribute( 'aria-controls' ) );
	if ( ! list ) {
		return;
	}

	toggle.addEventListener( 'click', function () {
		var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';
		toggle.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
		list.hidden = isOpen;

		var actionLabel = toggle.querySelector( '.wftu-toc__action' );
		actionLabel.innerHTML = isOpen
			? 'Show <span class="wftu-toc__icon">+</span>'
			: 'Hide <span class="wftu-toc__icon">&minus;</span>';
	} );
} );