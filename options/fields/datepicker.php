<?php
/**
 * Template for displaying the Datepicker Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.3.0
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

wp_enqueue_script( 'mct-daterangepicker' );
wp_enqueue_style( 'mct-daterangepicker' );
?>

<input autocomplete="off" type="text" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="regular-text mct-datepicker <?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $value ); ?>" data-value="<?php echo esc_attr( $value ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>
/>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
