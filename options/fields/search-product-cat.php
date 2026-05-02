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
 * @var $label                     string Field label
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
	<p><?php esc_attr_e( 'Search Product Categories is enabled but not effective. It requires WooCommerce to work.', 'moreconvert-compare-for-woocommerce' ); ?></p>
	<?php
	return;
}

wp_enqueue_style( 'select2' );

wp_enqueue_script( 'selectWoo' );

wp_enqueue_script( 'wc-enhanced-select' );

?>
<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_attr( $label ); ?></label>
<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo '' !== $name ? esc_attr( $name ) . '[]' : ''; ?>" multiple="multiple" class="wc-category-search <?php echo esc_attr( $class ); ?>" data-allow_clear="true" data-minimum_input_length="3"
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
		<?php
		if ( is_array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $current_category_slug ) {
				$current_category = get_term_by( 'slug', $current_category_slug, 'product_cat' );
				?>
				<option value="<?php echo esc_attr( $current_category_slug ); ?>" selected="selected"><?php echo esc_html( htmlspecialchars( wp_kses_post( $current_category->name ) ) ); ?></option>
				<?php
			}
		}
		?>
</select>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
