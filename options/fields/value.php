<?php
/**
 * Template for displaying the Value Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.1.0
 * @since 1.1.0
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     string Field value
 * @var $value_format              string Field Value format
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies                 string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( isset( $value ) ) : ?>
	<div id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="<?php echo esc_attr( $class ); ?>"
		<?php echo wp_kses_post( $dependencies ); ?>
		<?php echo wp_kses_post( $custom_attributes ); ?>
		<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?> >
		<?php echo isset( $value_format ) && '' !== $value ? wp_kses_post( sprintf( $value_format, $value ) ) : wp_kses_post( $value ); ?>
	</div>
	<?php
endif;
