<?php
/**
 * Template for displaying the Checkbox Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.0.0
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
<fieldset class="">
	<label for="<?php echo esc_attr( $field_id ); ?>">
		<input type="checkbox" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" class="<?php echo ! empty( $class ) ? esc_attr( $class ) : ''; ?>" data-type="checkbox"
			<?php echo wp_kses_post( $dependencies ); ?>
			<?php checked( true, ( $value ) ); ?>
			<?php echo wp_kses_post( $custom_attributes ); ?>
			<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>/>
		<?php if ( isset( $desc ) ) : ?>
			<span class='description'><?php echo wp_kses_post( $desc ); ?></span>
		<?php endif; ?>
	</label>
</fieldset>
