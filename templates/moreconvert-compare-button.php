<?php
/**
 * Compare Button Template
 *
 * Displays the Compare button to open the popup.
 *
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 *
 * @var $product_id int Product ID
 * @var $button_class string CSS class for the button
 * @var $icon string Icon HTML or class
 * @var $is_svg_icon bool Whether icon is SVG
 * @var $button_text string Button text
 * @var $product_data array product data
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="moreconvert-compare-button-wrapper">
	<button type="button" class="<?php echo esc_attr( $button_class ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-product="<?php echo esc_attr( wp_json_encode( $product_data ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" aria-label="<?php echo esc_attr( $button_text ); ?>">
		<?php if ( $is_svg_icon && ! empty( $icon ) ) : ?>
			<span class="moreconvert-compare-icon">
				<?php echo strpos( $icon, '<svg' ) !== false ? '<i class="moreconvert-compare-svg">' . moreconvert_compare_sanitize_svg( $icon ) . '</i>' : wp_kses_post( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		<?php endif; ?>
		<?php if ( ! empty( $button_text ) ) : ?>
			<span class="moreconvert-compare-text"><?php echo esc_html( $button_text ); ?></span>
		<?php endif; ?>
	</button>
</div>
