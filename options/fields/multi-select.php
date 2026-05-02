<?php
/**
 * Template for displaying the MultiSelect Field
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
 * @var $options                   array Array of all select options
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$value = is_array( $value ) ? $value : array( $value );
?>
<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_attr( $label ); ?></label>
<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo '' !== $name ? esc_attr( $name ) . '[]' : ''; ?>"
		class="<?php echo esc_attr( $class ); ?>" multiple="multiple" autocomplete="off"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<?php if ( is_array( $options ) && ! empty( $options ) ) : ?>
		<?php foreach ( $options as $key => $val ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php echo is_array( $value ) && in_array( (string) $key, $value, true ) ? ' selected="selected"' : ''; ?>><?php echo esc_attr( $val ); ?></option>
		<?php endforeach; ?>
	<?php endif; ?>
</select>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
