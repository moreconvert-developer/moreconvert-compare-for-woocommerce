<?php
/**
 * Template for displaying the copy text Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.0.0
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     string Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies                 string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d-inline-flex f-center gap-5 mct-copy-wrapper">
<input type="text" readonly id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="regular-text mct-copy-text <?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $value ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>
/>
<button class="btn-secondary small-btn mct-copy-btn"><?php esc_attr_e( 'Copy', 'moreconvert-compare-for-woocommerce' ); ?></button>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
