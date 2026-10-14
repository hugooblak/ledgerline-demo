<?php
/**
 * Plugin Name: Ledger Line Demo Notes
 * Description: Portfolio helper. Shows a "this is a demo" bar and, when switched on, a note above each section explaining the design decision behind it. Delete this plugin on a real site.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Author: Hugo Oblak
 * License: GPL-2.0-or-later
 *
 * The notes are not stored in page content, so editors never see them and they vanish with the plugin.
 *
 * @package LedgerLineDemoNotes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Notes on or off. ?notes=1 turns them on (and remembers it in a cookie while you browse), ?notes=0 turns them off.
 */
function npd_notes_on() {
	static $on = null;
	if ( null === $on ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['notes'] ) ) {
			$on = '1' === $_GET['notes'];
		} else {
			$on = isset( $_COOKIE['npd_notes'] ) && '1' === $_COOKIE['npd_notes'];
		}
		// phpcs:enable
	}
	return $on;
}

add_action(
	'send_headers',
	function () {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['notes'] ) && ! headers_sent() ) {
			setcookie( 'npd_notes', npd_notes_on() ? '1' : '0', 0, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
		}
	}
);

/**
 * The notes, keyed by the class or block name they sit above.
 *
 * @return array
 */
function npd_notes() {
	return array(
		'hero'     => array(
			'The service in one look',
			'The headline is the whole offer in three short lines, and the three panels show it happening: receipts in, the month closed, clean books out. They play once on load and then rest. The checklist and the profit and loss figures are edited in the block sidebar; net profit is worked out from them, so it always adds up.',
		),
		'how'      => array(
			'Three steps, nothing to learn',
			'A line runs from step one to step three as you scroll and each number fills when the line reaches it. Owners who are behind on their books need to see that starting is easy before anything else.',
		),
		'services' => array(
			'Every service shows its work',
			'Each card has a small graphic that explains the service without words: transactions getting their categories, the four US estimated tax dates, a cash-flow line. They play once when they scroll into view.',
		),
		'numbers'  => array(
			'Numbers about the service, not invented proof',
			'A fictional firm has no clients to count, so the band shows facts about how the service works. On a real site these become the firm\'s own figures.',
		),
		'faq-sec'  => array(
			'A person behind the books, and the questions people ask first',
			'The founder note puts a name to the firm; the signature writes itself in. The FAQ uses WordPress\'s own Details block, so it works with a keyboard and without JavaScript.',
		),
		'book-sec' => array(
			'One short form, demo only',
			'Three fields, because a first call needs no more. In this demo nothing is sent and the form says so; sending it ticks the three chips on the card. On a real site the block connects to a form plugin or the firm\'s calendar.',
		),
	);
}

/**
 * Put a note in front of each matching section when notes are on.
 */
add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( is_admin() || ! npd_notes_on() ) {
			return $html;
		}
		$classes = preg_split( '/\s+/', (string) ( $block['attrs']['className'] ?? '' ) );
		foreach ( npd_notes() as $class => $note ) {
			if ( in_array( $class, $classes, true ) && 'core/group' === $block['blockName'] ) {
				return sprintf(
					'<aside class="npd-note" aria-label="%s"><p class="npd-note-title"><span>Design note</span> %s</p><p>%s</p></aside>',
					esc_attr( 'Design note: ' . $note[0] ),
					esc_html( $note[0] ),
					esc_html( $note[1] )
				) . $html;
			}
		}
		return $html;
	},
	10,
	2
);

/**
 * Demo bar at the very top of every page.
 *
 * It sits above the header, which would otherwise put its link ahead of WordPress's skip link in the
 * tab order. So we print our own skip link first and hide core's.
 */
add_action(
	'wp_body_open',
	function () {
		printf(
			'<a class="npd-skip skip-link screen-reader-text" href="#wp--skip-link--target">%s</a>',
			esc_html__( 'Skip to content', 'ledgerline-demo-notes' )
		);
	},
	0
);

add_action(
	'wp_body_open',
	function () {
		$toggle = add_query_arg( 'notes', npd_notes_on() ? '0' : '1' );
		?>
		<div class="npd-bar" role="region" aria-label="Demo notice">
			<p><strong>Portfolio demo.</strong> Ledger Line is a fictional accounting firm. The name, founder, figures and photographs are sample content, and nothing here is sent.
				<a href="<?php echo esc_url( $toggle ); ?>"><?php echo npd_notes_on() ? 'Hide the design notes' : 'Show the design notes behind each section'; ?></a>
			</p>
		</div>
		<?php
	},
	1
);

add_action(
	'wp_head',
	function () {
		?>
		<style id="npd-css">
			.npd-bar ~ a.skip-link:not(.npd-skip){display:none !important}
			.npd-bar{background:#000;color:#EEE9E2;font-size:.8125rem;line-height:1.5;padding:.55rem 1rem;text-align:center;position:relative;z-index:50}
			.npd-bar p{margin:0}.npd-bar a{color:#D9BE9B;font-weight:500;margin-left:.35rem}
			.npd-note{box-sizing:border-box;margin:0;padding:.9rem clamp(1rem,7.9%,10rem);background:#FFF8DB;border-top:2px dashed #C99A06;border-bottom:2px dashed #C99A06;color:#3D2E00;font-size:.9375rem;line-height:1.55}
			.npd-note p{margin:0;max-width:90ch}.npd-note-title{font-weight:700;margin-bottom:.25rem !important}
			.npd-note-title span{display:inline-block;background:#3D2E00;color:#FFF8DB;font-size:.6875rem;letter-spacing:.06em;text-transform:uppercase;padding:.1rem .4rem;margin-right:.35rem;vertical-align:.1em}
		</style>
		<?php
	}
);

/**
 * A short guide on the Dashboard for anyone reviewing the demo in WordPress Playground.
 */
add_action(
	'wp_dashboard_setup',
	function () {
		wp_add_dashboard_widget(
			'npd_guide',
			'Ledger Line demo: where to look',
			function () {
				$items = array(
					array( home_url( '/?notes=1' ), 'Home page with the design notes switched on' ),
					array( home_url( '/#book' ), 'Book a call form (demo only, sends nothing)' ),
					array( admin_url( 'post.php?post=' . get_option( 'page_on_front' ) . '&action=edit' ), 'Edit the home page: normal blocks; checklist, figures and form edited in the block sidebar' ),
					array( admin_url( 'site-editor.php' ), 'Site editor: styles, header, footer, templates' ),
				);
				echo '<ol>';
				foreach ( array_filter( $items ) as $item ) {
					printf( '<li><a href="%s">%s</a></li>', esc_url( $item[0] ), esc_html( $item[1] ) );
				}
				echo '</ol>';
			}
		);
	}
);
