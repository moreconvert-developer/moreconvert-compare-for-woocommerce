<?php
/**
 * Template for displaying the Checkbox Group Field
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
 * @var $dependencies                 string Dependencies
 * @var $desc                      string Description
 * @var $options                   array Array of all checkboxes
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<fieldset id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo ! empty( $class ) ? esc_attr( $class ) : ''; ?>" data-type="checkbox-group"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<?php if ( isset( $options ) && is_array( $options ) && ! empty( $options ) ) : ?>
		<?php foreach ( $options as $key => $val ) : ?>
			<label for="<?php echo esc_attr( $field_id . '_' . $key ); ?>">
				<input id="<?php echo esc_attr( $field_id . '_' . $key ); ?>" type="checkbox" name="<?php echo '' !== $name ? esc_attr( $name ) . '[]' : ''; ?>" value="<?php echo esc_attr( $key ); ?>"<?php echo ( is_array( $value ) && in_array( $key, $value, true ) ) ? 'checked="checked"' : ''; ?>>
				<span><?php echo esc_attr( $val ); ?></span>
			</label>
			<br>
		<?php endforeach; ?>
	<?php endif; ?>
</fieldset>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
