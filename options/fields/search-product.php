<?php
/**
 * Template for displaying the search product Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.6
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     array Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'WC' ) ) {
	?>
	<p><?php esc_attr_e( 'Search Product is enabled but not effective. It requires WooCommerce to work.', 'moreconvert-compare-for-woocommerce' ); ?></p>
	<?php
	return;
}

wp_enqueue_style( 'select2' );

wp_enqueue_script( 'selectWoo' );

wp_enqueue_script( 'wc-enhanced-select' );
?>
<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php esc_attr_e( 'Search for a product&hellip;', 'moreconvert-compare-for-woocommerce' ); ?></label>
<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo '' !== $name ? esc_attr( $name ) . '[]' : ''; ?>"
		class="wc-product-search <?php echo esc_attr( $class ); ?>" multiple="multiple" style="width: 50%;"
		data-minimum_input_length="3"
		data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'moreconvert-compare-for-woocommerce' ); ?>"
		data-action2="woocommerce_json_search_products_and_variations"
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<?php
	if ( is_array( $value ) && ! empty( $value ) ) {
		foreach ( $value as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( is_object( $product ) ) {
				echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
			}
		}
	}
	?>
</select>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
