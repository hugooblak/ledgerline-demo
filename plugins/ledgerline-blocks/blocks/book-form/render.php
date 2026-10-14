<?php
/**
 * Book-a-call form. Demo only: view.js checks the fields, shows the thank-you and sends nothing.
 *
 * @var array $attributes Block attributes.
 * @package LedgerLineBlocks
 */

defined( 'ABSPATH' ) || exit;

$a     = $attributes;
$types = array_values( array_filter( array_map( 'trim', explode( "\n", (string) ( $a['types'] ?? '' ) ) ) ) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'llb-book' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<form class="llb-form" novalidate data-error="<?php echo esc_attr( $a['errorText'] ?? '' ); ?>" data-email-error="<?php echo esc_attr( $a['emailError'] ?? '' ); ?>">
		<p class="llb-field">
			<label for="llb-name"><?php echo esc_html( $a['nameLabel'] ?? '' ); ?></label>
			<input id="llb-name" name="name" autocomplete="name" required aria-describedby="llb-name-err">
			<span class="llb-err" id="llb-name-err" aria-live="polite"></span>
		</p>
		<p class="llb-field">
			<label for="llb-email"><?php echo esc_html( $a['emailLabel'] ?? '' ); ?></label>
			<input id="llb-email" name="email" type="email" autocomplete="email" required aria-describedby="llb-email-err">
			<span class="llb-err" id="llb-email-err" aria-live="polite"></span>
		</p>
		<p class="llb-field">
			<label for="llb-type"><?php echo esc_html( $a['typeLabel'] ?? '' ); ?></label>
			<select id="llb-type" name="type" required aria-describedby="llb-type-err">
				<option value=""><?php echo esc_html( $a['choose'] ?? '' ); ?></option>
				<?php foreach ( $types as $t ) : ?>
					<option><?php echo esc_html( $t ); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="llb-err" id="llb-type-err" aria-live="polite"></span>
		</p>
		<button type="submit" class="llb-go"><?php echo esc_html( $a['button'] ?? '' ); ?></button>
		<p class="llb-note"><?php echo esc_html( $a['note'] ?? '' ); ?></p>
	</form>
	<div class="llb-thanks" role="status" tabindex="-1" hidden>
		<p class="llb-thanks-title"><?php echo esc_html( $a['thanksTitle'] ?? '' ); ?></p>
		<p><?php echo esc_html( $a['thanksText'] ?? '' ); ?></p>
	</div>
</div>
