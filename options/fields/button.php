<?php
/**
 * Template for displaying the button Field
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
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 * @var $default                   string
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<button type="submit" class="<?php echo ! empty( $class ) ? esc_attr( $class ) : ''; ?>"
		id="<?php echo esc_attr( $field_id ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>

	<?php echo esc_attr( $default ); ?>

</button>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
