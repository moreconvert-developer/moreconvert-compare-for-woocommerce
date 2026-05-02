<?php
/**
 * Template for displaying the Select Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.2.1
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $label                     string Field label
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     string Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies                 string Dependencies
 * @var $desc                      string Description
 * @var $options                   array Array of all select options
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_attr( $label ); ?></label>
<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>"
		class="select-icon <?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<?php if ( is_array( $options ) && ! empty( $options ) ) : ?>
		<?php foreach ( $options as $key => $val ) : ?>
			<?php if ( is_array( $val ) && isset( $val['options'] ) ) : ?>
				<optgroup label="<?php echo esc_attr( $val['label'] ); ?>">
					<?php foreach ( $val['options'] as $option_key => $option ) : ?>
						<?php if ( is_array( $option ) ) : ?>
							<option data-image='<?php echo wp_kses_post( $option['image'] ); ?>'
									value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option['title'] ); ?></option>
						<?php else : ?>
							<option
									value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option ); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</optgroup>
			<?php else : ?>
				<?php if ( is_array( $val ) ) : ?>
					<option data-image='<?php echo wp_kses_post( $val['image'] ); ?>'
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $value ); ?>><?php echo esc_html( $val['title'] ); ?></option>
				<?php else : ?>
					<option
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $value ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endif; ?>

			<?php endif; ?>

		<?php endforeach; ?>
	<?php endif; ?>
</select>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
