<?php
/**
 * Template for displaying the Box Shadow Field (Elementor Style)
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
	'horizontal' => 0,
	'vertical'   => 0,
	'blur'       => 0,
	'spread'     => 0,
	'color'      => 'rgba(0,0,0,0.1)',
	'position'   => 'outline',
);
?>

<div class="mct-box-shadow-container <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $field_id ); ?>_wrapper" <?php echo wp_kses_post( $dependencies ); ?>>
	<div class="mct-box-shadow-grid d-flex f-column gap-10" >
		<div class="d-flex space-between f-center">
			<label ><?php esc_html_e( 'Color', 'moreconvert-compare-for-woocommerce' ); ?></label>
			<input autocomplete="off" type="text" data-alpha-enabled="true"  class="mct-color-picker" name="<?php echo esc_attr( $name . '[color]' ); ?>" value="<?php echo esc_attr( $val['color'] ?? 'rgba(0,0,0,0.1)' ); ?>" />
		</div>
		<div>
			<label ><?php esc_html_e( 'Horizontal', 'moreconvert-compare-for-woocommerce' ); ?></label>
			<div class="mct-slider-unit-fields d-inline-flex f-center gap-10">
				<div class="mct-slider-range-wrapper" >
					<input
							type="range"
							class="mct-slider-range-control"
							min="-100"
							max="100"
							step="1"
							value="<?php echo esc_attr( $val['horizontal'] ?? 0 ); ?>"
					/>
				</div>

				<div class="mct-slider-numeric-wrapper">
					<input
							type="number"
							class="mct-slider-numeric-input regular-text"
							name="<?php echo esc_attr( $name . '[horizontal]' ); ?>"
							value="<?php echo esc_attr( $val['horizontal'] ?? 0 ); ?>"
							min="-100"
							max="100"
							step="1"
							autocomplete="off"
							placeholder="-"
						<?php echo wp_kses_post( $custom_attributes ); ?>
					/>
				</div>
			</div>
		</div>
		<div>
			<label ><?php esc_html_e( 'Vertical', 'moreconvert-compare-for-woocommerce' ); ?></label>
			<div class="mct-slider-unit-fields d-inline-flex f-center gap-10">
				<div class="mct-slider-range-wrapper" >
					<input
							type="range"
							class="mct-slider-range-control"
							min="-100"
							max="100"
							step="1"
							value="<?php echo esc_attr( $val['vertical'] ?? 0 ); ?>"
					/>
				</div>

				<div class="mct-slider-numeric-wrapper">
					<input
							type="number"
							class="mct-slider-numeric-input regular-text"
							name="<?php echo esc_attr( $name . '[vertical]' ); ?>"
							value="<?php echo esc_attr( $val['vertical'] ?? 0 ); ?>"
							min="-100"
							max="100"
							step="1"
							autocomplete="off"
							placeholder="-"
						<?php echo wp_kses_post( $custom_attributes ); ?>
					/>
				</div>
			</div>
		</div>
		<div>
			<label ><?php esc_html_e( 'Blur', 'moreconvert-compare-for-woocommerce' ); ?></label>
			<div class="mct-slider-unit-fields d-inline-flex f-center gap-10">
				<div class="mct-slider-range-wrapper" >
					<input
							type="range"
							class="mct-slider-range-control"
							min="0"
							max="100"
							step="1"
							value="<?php echo esc_attr( $val['blur'] ?? 0 ); ?>"
					/>
				</div>

				<div class="mct-slider-numeric-wrapper">
					<input
							type="number"
							class="mct-slider-numeric-input regular-text"
							name="<?php echo esc_attr( $name . '[blur]' ); ?>"
							value="<?php echo esc_attr( $val['blur'] ?? 0 ); ?>"
							min="0"
							max="100"
							step="1"
							autocomplete="off"
							placeholder="-"
						<?php echo wp_kses_post( $custom_attributes ); ?>
					/>
				</div>
			</div>
		</div>
		<div>
			<label ><?php esc_html_e( 'Spread', 'moreconvert-compare-for-woocommerce' ); ?></label>
			<div class="mct-slider-unit-fields d-inline-flex f-center gap-10">
				<div class="mct-slider-range-wrapper" >
					<input
							type="range"
							class="mct-slider-range-control"
							min="-100"
							max="100"
							step="1"
							value="<?php echo esc_attr( $val['spread'] ?? 0 ); ?>"
					/>
				</div>

				<div class="mct-slider-numeric-wrapper">
					<input
							type="number"
							class="mct-slider-numeric-input regular-text"
							name="<?php echo esc_attr( $name . '[spread]' ); ?>"
							value="<?php echo esc_attr( $val['spread'] ?? 0 ); ?>"
							min="-100"
							max="100"
							step="1"
							autocomplete="off"
							placeholder="-"
						<?php echo wp_kses_post( $custom_attributes ); ?>
					/>
				</div>
			</div>
		</div>

		<div class="d-flex space-between f-center">
			<label ><?php esc_html_e( 'Position', 'moreconvert-compare-for-woocommerce' ); ?></label>
			<select name="<?php echo esc_attr( $name . '[position]' ); ?>" >
				<option value="outline" <?php selected( $val['position'] ?? 'outline', 'outline' ); ?>><?php esc_html_e( 'Outline', 'moreconvert-compare-for-woocommerce' ); ?></option>
				<option value="inset" <?php selected( $val['position'] ?? 'outline', 'inset' ); ?>><?php esc_html_e( 'Inset', 'moreconvert-compare-for-woocommerce' ); ?></option>
			</select>
		</div>

	</div>
</div>
