<?php
/**
 * Plugin Name: Ledger Line Blocks
 * Description: The parts of the Ledger Line site that normal blocks cannot do: the three hero panels (receipts, month-end checklist, profit and loss), the small service graphics, and the booking form (demo only, sends nothing).
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: Hugo Oblak
 * License: GPL-2.0-or-later
 * Text Domain: ledgerline-blocks
 *
 * Why a plugin and not theme code: the checklist, the profit and loss figures and the form labels
 * are content the firm edits. They must survive a redesign.
 *
 * @package LedgerLineBlocks
 */

defined( 'ABSPATH' ) || exit;

define( 'LLB_URL', plugin_dir_url( __FILE__ ) );

add_action(
	'init',
	function () {
		foreach ( array( 'hero-panels', 'viz', 'book-form' ) as $block ) {
			register_block_type( __DIR__ . '/blocks/' . $block );
		}
	}
);

/**
 * The theme's photo for a panel, or a full image address if the editor pasted one.
 *
 * @param string $file File name in the active theme's assets/img folder, or a URL.
 * @return string
 */
function llb_image( $file ) {
	if ( preg_match( '#^https?://#', $file ) ) {
		return $file;
	}
	return get_theme_file_uri( 'assets/img/' . $file );
}

/**
 * srcset for a theme photo: phones get the 760px copy (name-760.webp) when the theme has one.
 *
 * @param string $file File name in the active theme's assets/img folder, or a URL.
 * @return string Attribute markup, or an empty string.
 */
function llb_srcset( $file ) {
	if ( preg_match( '#^https?://#', $file ) ) {
		return '';
	}
	$small = preg_replace( '/(\.\w+)$/', '-760$1', $file );
	if ( ! file_exists( get_theme_file_path( 'assets/img/' . $small ) ) ) {
		return '';
	}
	return sprintf(
		' srcset="%s 760w, %s 1100w" sizes="(max-width: 1000px) 100vw, 380px"',
		esc_url( get_theme_file_uri( 'assets/img/' . $small ) ),
		esc_url( get_theme_file_uri( 'assets/img/' . $file ) )
	);
}

/**
 * Small green tick used on the chips.
 *
 * @return string
 */
function llb_tick() {
	return '<svg viewBox="0 0 20 20" aria-hidden="true" focusable="false"><circle cx="10" cy="10" r="10" fill="#2F8A4C"/><path d="M5.5 10.3l3 3 6-6.3" stroke="#fff" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * One editor script for all three blocks: a sidebar with the fields, and a preview drawn
 * by the same PHP as the live site. Plain JavaScript, no build step.
 */
add_action(
	'enqueue_block_editor_assets',
	function () {
		wp_enqueue_script(
			'ledgerline-blocks-editor',
			LLB_URL . 'editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
			'1.0.0',
			true
		);
	}
);
