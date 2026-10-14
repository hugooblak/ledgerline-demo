/**
 * Ledger Line theme script: header, the how-it-works line, count-ups, the word-by-word Why
 * paragraph and the founder's signature. The hero panels, service graphics and booking form bring
 * their own small scripts (Ledger Line Blocks plugin), so each only loads where its block is used.
 */
( () => {
	document.documentElement.classList.add( 'js' );
	const reduce = matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const fmt = ( n ) => Math.round( n ).toLocaleString( 'en-US' );

	// Header: sits under the admin bar and the demo bar, and gets its dashed rule once you scroll.
	const header = document.querySelector( '.site-header' );
	if ( header ) {
		const bars = [ document.getElementById( 'wpadminbar' ), document.querySelector( '.npd-bar' ) ].filter( Boolean );
		const place = () => {
			const top = Math.max( 0, ...bars.map( ( b ) => b.getBoundingClientRect().bottom ) );
			header.style.setProperty( '--ll-top', ( document.getElementById( 'wpadminbar' ) ? document.getElementById( 'wpadminbar' ).offsetHeight : 0 ) + 'px' );
			header.style.setProperty( '--ll-head-bottom', Math.max( top, 0 ) + header.offsetHeight + 'px' );
			header.classList.toggle( 'scrolled', scrollY > 40 );
		};
		addEventListener( 'scroll', place, { passive: true } );
		addEventListener( 'resize', place );
		place();
		// Close the phone menu after a link in it is used.
		header.addEventListener( 'click', ( e ) => {
			if ( e.target.closest( '.is-menu-open .wp-block-navigation-item__content' ) ) {
				header.querySelector( '.wp-block-navigation__responsive-container-close' )?.click();
			}
		} );
	}

	// Count-ups: the numbers in the band count from 0 the first time they are seen.
	const countUp = ( el ) => {
		const to = parseFloat( el.textContent.replace( /[^0-9.]/g, '' ) );
		if ( Number.isNaN( to ) || reduce ) {
			return;
		}
		const t0 = performance.now();
		const step = ( t ) => {
			const k = Math.min( 1, ( t - t0 ) / 1200 );
			el.textContent = fmt( to * ( 1 - Math.pow( 1 - k, 3 ) ) );
			if ( k < 1 ) {
				requestAnimationFrame( step );
			}
		};
		requestAnimationFrame( step );
	};

	const io = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( e ) => {
			if ( ! e.isIntersecting ) {
				return;
			}
			e.target.classList.add( 'in' );
			// The signature is clipped until it is drawn, so its own box never "intersects": watch its note instead.
			e.target.querySelector( '.sig' )?.classList.add( 'in' );
			if ( e.target.classList.contains( 'band' ) ) {
				e.target.querySelectorAll( '.stat' ).forEach( countUp );
			}
			io.unobserve( e.target );
		} );
	}, { threshold: 0.35 } );
	document.querySelectorAll( '.band, .note' ).forEach( ( el ) => io.observe( el ) );

	// How it works: the line fills with scroll and each number fills when the line reaches it.
	const steps = document.querySelector( '.steps' );
	const stepEls = steps ? [ ...steps.querySelectorAll( '.step' ) ] : [];

	// Why: split into words. Words go from faint to ink while the paragraph is on screen.
	const man = document.querySelector( '.manifesto' );
	let words = [];
	if ( man && ! reduce ) {
		man.innerHTML = man.textContent.trim().split( /\s+/ ).map( ( w ) => '<span class="w">' + w.replace( /[&<>]/g, ( c ) => ( { '&': '&amp;', '<': '&lt;', '>': '&gt;' }[ c ] ) ) + '</span>' ).join( ' ' );
		words = [ ...man.querySelectorAll( '.w' ) ];
	}

	let ticking = false;
	const update = () => {
		ticking = false;
		const vh = innerHeight;
		if ( steps ) {
			const r = steps.getBoundingClientRect();
			const k = reduce ? 1 : Math.max( 0, Math.min( 1, ( vh * 0.85 - r.top ) / ( r.height + vh * 0.3 ) ) );
			steps.style.setProperty( '--p', k.toFixed( 3 ) );
			stepEls.forEach( ( s, i ) => s.classList.toggle( 'lit', reduce || k >= i / stepEls.length + 0.02 ) );
		}
		if ( words.length ) {
			const m = man.getBoundingClientRect();
			const onScreen = m.top < vh && m.bottom > 0;
			man.classList.toggle( 'live', onScreen );
			const k = Math.max( 0, Math.min( 1, ( vh * 0.85 - m.top ) / ( m.height + vh * 0.35 ) ) );
			const n = Math.round( k * words.length );
			words.forEach( ( w, i ) => w.classList.toggle( 'on', i < n ) );
		}
	};
	addEventListener( 'scroll', () => {
		if ( ! ticking ) {
			ticking = true;
			requestAnimationFrame( update );
		}
	}, { passive: true } );
	addEventListener( 'resize', update );
	update();
} )();
