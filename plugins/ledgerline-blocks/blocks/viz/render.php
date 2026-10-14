<?php
/**
 * Service graphic. One of three small drawings, chosen in the sidebar. Each plays once when it
 * scrolls into view (view.js). Without JavaScript it shows the finished drawing.
 *
 * @var array $attributes Block attributes.
 * @package LedgerLineBlocks
 */

defined( 'ABSPATH' ) || exit;

$a     = $attributes;
$type  = in_array( $a['type'] ?? '', array( 'transactions', 'dates', 'flow' ), true ) ? $a['type'] : 'transactions';
$lines = static function ( $text ) {
	return array_values( array_filter( array_map( 'trim', explode( "\n", (string) $text ) ) ) );
};
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'llb-viz llb-viz-' . $type ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
<?php if ( 'transactions' === $type ) : ?>
	<ul class="llb-tx" aria-label="Transactions sorted into categories">
		<?php
		foreach ( $lines( $a['transactions'] ?? '' ) as $row ) :
			$cells = array_map( 'trim', explode( '|', $row ) ) + array( '', '', '' );
			?>
			<li><span class="llb-who"><?php echo esc_html( $cells[0] ); ?></span><span class="llb-amt"><?php echo esc_html( $cells[1] ); ?></span><b><?php echo esc_html( $cells[2] ); ?></b></li>
		<?php endforeach; ?>
	</ul>
<?php elseif ( 'dates' === $type ) : ?>
	<ul class="llb-dates" aria-label="<?php echo esc_attr( $a['datesLabel'] ?? '' ); ?>">
		<?php
		foreach ( $lines( $a['dates'] ?? '' ) as $date ) :
			$parts = preg_split( '/\s+/', $date, 2 ) + array( '', '' );
			?>
			<li><b><?php echo esc_html( $parts[0] ); ?></b><span><?php echo esc_html( $parts[1] ); ?></span></li>
		<?php endforeach; ?>
	</ul>
	<p class="llb-viz-label"># <?php echo esc_html( $a['datesLabel'] ?? '' ); ?> #</p>
<?php else : ?>
	<svg viewBox="0 0 300 110" preserveAspectRatio="none" aria-hidden="true" focusable="false">
		<path class="area" d="M0 88 C30 84 45 70 70 74 S115 92 140 66 S190 48 215 52 S265 26 300 14 L300 110 L0 110Z"/>
		<path class="line" d="M0 88 C30 84 45 70 70 74 S115 92 140 66 S190 48 215 52 S265 26 300 14"/>
		<circle cx="300" cy="14" r="5"/>
	</svg>
	<p class="llb-viz-label"># <?php echo esc_html( $a['flowLabel'] ?? '' ); ?> #</p>
<?php endif; ?>
</div>
