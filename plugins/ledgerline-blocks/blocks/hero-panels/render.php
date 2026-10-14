<?php
/**
 * Hero panels. The page shows the finished state; view.js plays it from the start once on load.
 * Gross and net profit are worked out here, so the sheet always adds up.
 *
 * @var array $attributes Block attributes.
 * @package LedgerLineBlocks
 */

defined( 'ABSPATH' ) || exit;

$a      = $attributes;
$income = (float) ( $a['income'] ?? 0 );
$cogs   = (float) ( $a['cogs'] ?? 0 );
$opex   = (float) ( $a['opex'] ?? 0 );
$gross  = $income - $cogs;
$net    = $gross - $opex;
$items  = array_values( array_filter( array_map( 'trim', explode( "\n", (string) ( $a['checklist'] ?? '' ) ) ) ) );

$money = static function ( $n ) {
	return '<span class="llb-n" data-to="' . esc_attr( (string) round( $n ) ) . '">' . esc_html( number_format( round( $n ) ) ) . '</span>';
};
$chip  = static function ( $label ) {
	return '<span class="llb-chip">' . llb_tick() . esc_html( $label ) . '</span>';
};
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'llb-panels' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<figure class="llb-panel llb-receipts">
		<img src="<?php echo esc_url( llb_image( $a['receiptsImage'] ?? '' ) ); ?>"<?php echo llb_srcset( $a['receiptsImage'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> alt="<?php echo esc_attr( $a['receiptsAlt'] ?? '' ); ?>" width="1100" height="1473" fetchpriority="high">
		<?php echo $chip( $a['chip1'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<figcaption class="llb-count"># <?php echo esc_html( $a['month'] ?? '' ); ?> # <span class="llb-n" data-to="<?php echo (int) ( $a['receiptCount'] ?? 0 ); ?>"><?php echo (int) ( $a['receiptCount'] ?? 0 ); ?></span> items</figcaption>
	</figure>

	<figure class="llb-panel llb-close">
		<div class="llb-ck">
			<p class="llb-label"># <?php echo esc_html( $a['checklistTitle'] ?? '' ); ?> #</p>
			<ul>
				<?php foreach ( $items as $item ) : ?>
					<li><i aria-hidden="true"><svg viewBox="0 0 12 12" focusable="false"><path d="M2 6.3l2.6 2.6L10 3.4"/></svg></i><span><?php echo esc_html( $item ); ?></span><em><?php echo esc_html( $a['doneLabel'] ?? '' ); ?></em></li>
				<?php endforeach; ?>
			</ul>
			<p class="llb-foot"><span class="llb-label">Status</span><span class="llb-status ok" data-todo="<?php echo esc_attr( $a['statusTodo'] ?? '' ); ?>" data-done="<?php echo esc_attr( $a['statusDone'] ?? '' ); ?>"><?php echo esc_html( $a['statusDone'] ?? '' ); ?></span></p>
		</div>
		<?php echo $chip( $a['chip2'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</figure>

	<figure class="llb-panel llb-pl">
		<img src="<?php echo esc_url( llb_image( $a['calcImage'] ?? '' ) ); ?>"<?php echo llb_srcset( $a['calcImage'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> alt="" width="1100" height="1473">
		<div class="llb-sheet">
			<p class="llb-sheet-title"><?php echo esc_html( $a['plTitle'] ?? '' ); ?></p>
			<p class="llb-per"><?php echo esc_html( $a['period'] ?? '' ); ?></p>
			<p class="llb-label">Net profit</p>
			<p class="llb-net">$<?php echo $money( $net ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<dl>
				<dt>Income</dt><dd>$<?php echo $money( $income ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
				<dt>Cost of goods sold</dt><dd>$<?php echo $money( $cogs ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
				<dt>Gross profit</dt><dd>$<?php echo $money( $gross ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
				<dt>Operating expenses</dt><dd>$<?php echo $money( $opex ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
				<dt class="tot">Net profit</dt><dd class="tot">$<?php echo $money( $net ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
			</dl>
		</div>
		<?php echo $chip( $a['chip3'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</figure>
</div>
