<?php
/**
 * Title: Part: footer
 * Slug: ledgerline/part-footer
 * Inserter: no
 *
 * Template part markup lives in a pattern so links use this site's own address.
 */

$ll_home = static function ( $hash = '' ) {
	return esc_url( home_url( '/' . $hash ) );
};
?>
<!-- wp:group {"className":"footer","layout":{"type":"default"}} -->
<div class="wp-block-group footer"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:separator {"className":"dash"} -->
<hr class="wp-block-separator has-alpha-channel-opacity dash"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"foot-row","layout":{"type":"default"}} -->
<div class="wp-block-group foot-row"><!-- wp:group {"className":"brand","layout":{"type":"default"}} -->
<div class="wp-block-group brand"><!-- wp:paragraph {"className":"brand-word"} -->
<p class="brand-word"><a href="<?php echo $ll_home(); // phpcs:ignore ?>">LEDGER LINE</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"brand-sub"} -->
<p class="brand-sub">Accounting for small business</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"foot-links label"} -->
<p class="foot-links label"><a href="<?php echo $ll_home( '#how' ); // phpcs:ignore ?>">How it works</a> <a href="<?php echo $ll_home( '#services' ); // phpcs:ignore ?>">Services</a> <a href="<?php echo $ll_home( '#faq' ); // phpcs:ignore ?>">FAQ</a> <a href="<?php echo $ll_home( '#book' ); // phpcs:ignore ?>">Book a call</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"fine"} -->
<p class="fine">© 2026 Ledger Line. Ledger Line is a fictional firm. Concept design by Sunside.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
