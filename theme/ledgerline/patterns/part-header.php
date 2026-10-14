<?php
/**
 * Title: Part: header
 * Slug: ledgerline/part-header
 * Inserter: no
 *
 * Template part markup lives in a pattern so links use this site's own address.
 */

// Point the menu at the site's "Main menu" (created by the demo setup, editable in Appearance > Editor > Navigation).
$ll_menu = get_posts( array( 'post_type' => 'wp_navigation', 'title' => 'Main menu', 'numberposts' => 1, 'fields' => 'ids', 'post_status' => 'publish' ) );
$ll_ref  = $ll_menu ? '"ref":' . (int) $ll_menu[0] . ',' : '';
?>
<!-- wp:group {"anchor":"top","className":"site-header","layout":{"type":"default"}} -->
<div id="top" class="wp-block-group site-header"><!-- wp:group {"className":"head-in","layout":{"type":"default"}} -->
<div class="wp-block-group head-in"><!-- wp:group {"className":"brand","layout":{"type":"default"}} -->
<div class="wp-block-group brand"><!-- wp:paragraph {"className":"brand-word"} -->
<p class="brand-word"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">LEDGER LINE</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"brand-sub"} -->
<p class="brand-sub">Accounting for small business</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:navigation {<?php echo $ll_ref; // phpcs:ignore ?>"overlayMenu":"mobile","className":"nav","layout":{"type":"flex","justifyContent":"right"}} /-->

<!-- wp:buttons {"className":"header-cta-wrap"} -->
<div class="wp-block-buttons header-cta-wrap"><!-- wp:button {"className":"pill pill-line pill-sm"} -->
<div class="wp-block-button pill pill-line pill-sm"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#book' ) ); ?>">Book a call</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
