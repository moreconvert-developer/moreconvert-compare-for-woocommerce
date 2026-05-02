<?php
/**
 * Template for displaying the Html Field
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
 * @var $html                      string Field value
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

<?php if ( isset( $html ) ) : ?>
	<div id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="<?php echo esc_attr( $class ); ?>"
		<?php echo wp_kses_post( $dependencies ); ?>
		<?php echo wp_kses_post( $custom_attributes ); ?>
		<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?> >
		<?php echo wp_kses_post( $html ); ?>
	</div>
	<?php
endif;
