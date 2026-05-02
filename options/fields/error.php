<?php
/**
 * Template for displaying the Error Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.2.4
 * @since 1.2.4
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
<div id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<?php echo esc_attr( $value ); ?>
	<?php if ( isset( $desc ) ) : ?>
		<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
	<?php endif; ?>
</div>
