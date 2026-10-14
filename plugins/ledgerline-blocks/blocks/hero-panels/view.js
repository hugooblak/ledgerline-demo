/**
 * Hero panels: play the month once on load. Receipts settle, the checklist ticks line by line,
 * the profit and loss counts up, and each panel gets its chip.
 * People who ask for reduced motion get the finished state straight away.
 */
( () => {
	const root = document.querySelector( '.llb-panels' );
	if ( ! root ) {
		return;
	}
	const reduce = matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const [ p1, p2, p3 ] = root.querySelectorAll( '.llb-panel' );
	const status = root.querySelector( '.llb-status' );
	const wait = ( ms ) => new Promise( ( r ) => setTimeout( r, ms ) );
	const fmt = ( n ) => Math.round( n ).toLocaleString( 'en-US' );

	const count = ( panel, dur ) => panel.querySelectorAll( '.llb-n' ).forEach( ( el ) => {
		const to = +el.dataset.to;
		const t0 = performance.now();
		const step = ( t ) => {
			const k = Math.min( 1, ( t - t0 ) / dur );
			el.textContent = fmt( to * ( 1 - Math.pow( 1 - k, 3 ) ) );
			if ( k < 1 ) {
				requestAnimationFrame( step );
			}
		};
		requestAnimationFrame( step );
	} );

	const finish = () => {
		[ p1, p2, p3 ].forEach( ( p ) => p.classList.add( 'go', 'done' ) );
		root.querySelectorAll( 'li' ).forEach( ( li ) => li.classList.add( 'on' ) );
		status.textContent = status.dataset.done;
		status.classList.add( 'ok' );
	};

	if ( reduce ) {
		finish();
		return;
	}

	( async () => {
		status.textContent = status.dataset.todo;
		status.classList.remove( 'ok' );
		p3.querySelectorAll( '.llb-n' ).forEach( ( el ) => ( el.textContent = '0' ) );
		await wait( 150 );
		p1.classList.add( 'go' );
		count( p1, 1300 );
		await wait( 1100 );
		p1.classList.add( 'done' );
		await wait( 250 );
		for ( const li of p2.querySelectorAll( 'li' ) ) {
			li.classList.add( 'on' );
			await wait( 260 );
		}
		status.textContent = status.dataset.done;
		status.classList.add( 'ok' );
		await wait( 200 );
		p2.classList.add( 'done' );
		await wait( 250 );
		p3.classList.add( 'go' );
		count( p3, 1400 );
		await wait( 1300 );
		p3.classList.add( 'done' );
	} )();
} )();
