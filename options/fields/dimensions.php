<?php
/**
 * Template for displaying the Dimensions Field (Elementor Style)
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name base (e.g., option_name[field_id])
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
	'top'       => '',
	'right'     => '',
	'bottom'    => '',
	'left'      => '',
	'unit'      => 'px',
	'is_linked' => '1',
);

$units     = $field['units'] ?? array( 'px', 'em', 'rem', '%' );
$is_linked = ( isset( $val['is_linked'] ) && '1' === (string) $val['is_linked'] );
?>

<div class="mct-dimensions-container <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $field_id ); ?>_wrapper" <?php echo wp_kses_post( $dependencies ); ?>>
	<div class="mct-dimensions-fields-wrapper d-inline-flex ">

		<?php
		$directions = array(
			'top'    => __( 'Top', 'moreconvert-compare-for-woocommerce' ),
			'right'  => __( 'Right', 'moreconvert-compare-for-woocommerce' ),
			'bottom' => __( 'Bottom', 'moreconvert-compare-for-woocommerce' ),
			'left'   => __( 'Left', 'moreconvert-compare-for-woocommerce' ),
		);
		foreach ( $directions as $dir => $label ) :
			$input_value = isset( $val[ $dir ] ) ? $val[ $dir ] : '';
			?>
			<div class="mct-dimension-input-item text-center">
				<input
					type="number"
					class="mct-dimension-field mct-dimension-<?php echo esc_attr( $dir ); ?> regular-text"
					name="<?php echo esc_attr( $name . '[' . $dir . ']' ); ?>"
					value="<?php echo esc_attr( $input_value ); ?>"
					autocomplete="off"
					placeholder="-"
					<?php echo wp_kses_post( $custom_attributes ); ?>
				/>
				<span class="mct-dimension-label" style="display: block; font-size: 11px; color: #777; margin-top: 3px;"><?php echo esc_html( $label ); ?></span>
			</div>
		<?php endforeach; ?>

		<div class="mct-dimension-link-action">
			<button type="button" class="mct-dimension-link-btn button f-center d-inline-flex <?php echo $is_linked ? 'is-linked' : ''; ?>" >
				<span class="dashicons dashicons-<?php echo $is_linked ? 'admin-links' : 'editor-unlink'; ?>"></span>
			</button>
			<input
				type="hidden"
				class="mct-dimension-linked-status"
				name="<?php echo esc_attr( $name . '[is_linked]' ); ?>"
				value="<?php echo $is_linked ? '1' : '0'; ?>"
			/>
		</div>

		<div class="mct-dimension-unit-selector">
			<select name="<?php echo esc_attr( $name . '[unit]' ); ?>" class="mct-dimension-unit-select">
				<?php foreach ( $units as $unit ) : ?>
					<option value="<?php echo esc_attr( $unit ); ?>" <?php selected( $val['unit'] ?? 'px', $unit ); ?>><?php echo esc_html( $unit ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

	</div>
</div>
