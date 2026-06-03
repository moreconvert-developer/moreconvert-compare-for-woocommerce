<?php
/**
 * Compare Variation Template
 *
 * Displays the full-screen compare popup with search and product suggestions.
 *
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 *
 * @var $attributes array
 * @var $available_variations array
 * @var $product object
 * @var $clear_text string
 * @var $outofstock_message string
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
$variations_attr = is_string( $variations_attr ) ? $variations_attr : '';
?>

	<form class="variations_form cart" action="" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo esc_attr( $variations_attr ); ?>">
		<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
			<p class="stock out-of-stock"><?php echo esc_html( $outofstock_message ); ?></p>
		<?php else : ?>
			<table class="moreconvert-compare-variation-table variations" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
				<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<th class="label"><label for="<?php echo esc_attr( esc_attr( $attribute_name . '_' . $product->get_id() ) ); ?>"><?php echo esc_html( wc_attribute_label( $attribute_name ) ); ?></label></th>
						<td class="value">
							<?php
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
									'id'        => esc_attr( $attribute_name . '_' . $product->get_id() ),
									'name'      => esc_attr( $attribute_name . '_' . $product->get_id() ),
								)
							);
							?>
						</td>
					</tr>
					<?php
					if ( end( $attribute_keys ) === $attribute_name ) {
						echo wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<tr><td colspan="2" class="variation-wrapper reset"><a class=" moreconvert-compare-reset-variations" href="#">' . esc_attr( $clear_text ) . '</a></td></tr>' ) ); // phpcs:ignore  WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
					}
					?>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</form>
<?php
