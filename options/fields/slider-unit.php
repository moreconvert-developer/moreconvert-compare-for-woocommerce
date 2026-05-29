<?php
/**
 * Template for displaying the Slider Unit Field (Elementor Style)
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.6
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name base
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     array  Field value array
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
 * @var $field                     array  Array of all field attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$val = is_array( $value ) ? $value : array(
	'size' => '',
	'unit' => 'px',
);

$units = $field['units'] ?? array();
$min   = $field['min'] ?? '0';
$max   = $field['max'] ?? '200';
$step  = $field['step'] ?? '1';
?>

<div class="mct-slider-unit-container <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $field_id ); ?>_wrapper" <?php echo wp_kses_post( $dependencies ); ?>>
	<div class="mct-slider-unit-fields <?php echo ( ! empty( $units ) ) ? 'mct-have-unit' : ''; ?> d-inline-flex f-center gap-10">

		<div class="mct-slider-range-wrapper" >
			<input
				type="range"
				class="mct-slider-range-control"
				min="<?php echo esc_attr( $min ); ?>"
				max="<?php echo esc_attr( $max ); ?>"
				step="<?php echo esc_attr( $step ); ?>"
				value="<?php echo esc_attr( ! empty( $val['size'] ) ? $val['size'] : $min ); ?>"
			/>
		</div>

		<div class="mct-slider-numeric-wrapper">
			<input
				type="number"
				class="mct-slider-numeric-input regular-text"
				name="<?php echo esc_attr( $name . '[size]' ); ?>"
				id="<?php echo esc_attr( $field_id ); ?>"
				value="<?php echo esc_attr( $val['size'] ); ?>"
				min="<?php echo esc_attr( $min ); ?>"
				max="<?php echo esc_attr( $max ); ?>"
				step="<?php echo esc_attr( $step ); ?>"
				autocomplete="off"
				placeholder="-"
				<?php echo wp_kses_post( $custom_attributes ); ?>
			/>
		</div>
		<?php if ( ! empty( $units ) ) : ?>
			<div class="mct-slider-unit-wrapper">
				<select name="<?php echo esc_attr( $name . '[unit]' ); ?>" class="mct-slider-unit-select">
					<?php foreach ( $units as $unit ) : ?>
						<option value="<?php echo esc_attr( $unit ); ?>" <?php selected( $val['unit'] ?? 'px', $unit ); ?>><?php echo esc_html( $unit ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
	</div>
</div>
