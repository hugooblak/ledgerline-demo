/**
 * Book-a-call form, demo only. Checks the three fields with plain-English messages, then shows the
 * thank-you and ticks the three chips on the card. Nothing is sent anywhere.
 */
( () => {
	const form = document.querySelector( '.llb-form' );
	if ( ! form ) {
		return;
	}
	const wrap = form.closest( '.llb-book' );
	const thanks = wrap.querySelector( '.llb-thanks' );

	const check = ( field ) => {
		const err = document.getElementById( field.getAttribute( 'aria-describedby' ) );
		let msg = '';
		if ( ! field.value.trim() ) {
			msg = form.dataset.error;
		} else if ( 'email' === field.type && ! field.checkValidity() ) {
			msg = form.dataset.emailError;
		}
		err.textContent = msg;
		field.setAttribute( 'aria-invalid', msg ? 'true' : 'false' );
		return ! msg;
	};

	form.querySelectorAll( 'input, select' ).forEach( ( f ) => f.addEventListener( 'change', () => check( f ) ) );

	form.addEventListener( 'submit', ( e ) => {
		e.preventDefault();
		const fields = [ ...form.querySelectorAll( 'input, select' ) ];
		const bad = fields.filter( ( f ) => ! check( f ) );
		if ( bad.length ) {
			bad[ 0 ].focus();
			return;
		}
		form.hidden = true;
		thanks.hidden = false;
		thanks.focus();
		wrap.closest( '.book' )?.classList.add( 'sent' );
	} );
} )();
