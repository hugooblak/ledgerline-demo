/**
 * Service graphics: each plays once when it scrolls into view.
 * Dates light up one after another; the cash-flow line draws itself.
 */
( () => {
	const all = document.querySelectorAll( '.llb-viz' );
	if ( ! all.length ) {
		return;
	}
	const reduce = matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	const play = ( el ) => {
		el.classList.add( 'in' );
		if ( el.classList.contains( 'llb-viz-dates' ) ) {
			const days = [ ...el.querySelectorAll( 'li' ) ];
			days.forEach( ( d, i ) => setTimeout( () => {
				days.forEach( ( x ) => x.classList.remove( 'on' ) );
				d.classList.add( 'on' );
			}, reduce ? 0 : 500 + i * 650 ) );
		}
	};

	// The line starts undrawn only once we know it will be drawn.
	all.forEach( ( el ) => {
		const line = el.querySelector( '.line' );
		if ( line && ! reduce ) {
			const len = line.getTotalLength();
			line.style.strokeDasharray = len;
			line.style.strokeDashoffset = len;
			el._draw = () => {
				line.getBoundingClientRect();
				line.style.transition = 'stroke-dashoffset 1.4s cubic-bezier(.2,.7,.2,1)';
				line.style.strokeDashoffset = 0;
			};
		}
	} );

	const io = new IntersectionObserver( ( entries ) => entries.forEach( ( e ) => {
		if ( e.isIntersecting ) {
			play( e.target );
			e.target._draw?.();
			io.unobserve( e.target );
		}
	} ), { threshold: 0.35 } );
	all.forEach( ( el ) => io.observe( el ) );
} )();
