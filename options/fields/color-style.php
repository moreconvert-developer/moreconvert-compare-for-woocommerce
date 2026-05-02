<?php
/**
 * Template for displaying the Color style Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.3.2
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
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 * @var $disable_border            bool  disable/enable border
 * @var $disable_background        bool  disable/enable background color
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<table class="mct-border-table mct-responsive-table">
		<thead>
		<tr>
			<th><?php esc_attr_e( 'Text color', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th><?php esc_attr_e( 'Text hover color', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<?php if ( ! isset( $disable_background ) || ! $disable_background ) : ?>

				<th><?php esc_attr_e( 'Background', 'moreconvert-compare-for-woocommerce' ); ?></th>
				<th><?php esc_attr_e( 'Background hover', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<?php endif; ?>
			<?php if ( ! isset( $disable_border ) || ! $disable_border ) : ?>
				<th><?php esc_attr_e( 'Border', 'moreconvert-compare-for-woocommerce' ); ?></th>
				<th><?php esc_attr_e( 'Border hover', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-title="<?php esc_attr_e( 'Text color', 'moreconvert-compare-for-woocommerce' ); ?>">
				<input class="mct-color-picker" data-alpha-enabled="true" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[color]" value="<?php echo isset( $value['color'] ) ? esc_attr( $value['color'] ) : ''; ?>"/>
			</td>
			<td data-title="<?php esc_attr_e( 'Text hover color', 'moreconvert-compare-for-woocommerce' ); ?>">
				<input class="mct-color-picker" data-alpha-enabled="true" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[color-hover]" value="<?php echo isset( $value['color-hover'] ) ? esc_attr( $value['color-hover'] ) : ''; ?>"/>
			</td>
			<?php if ( ! isset( $disable_background ) || ! $disable_background ) : ?>
				<td data-title="<?php esc_attr_e( 'Background', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[background]" value="<?php echo isset( $value['background'] ) ? esc_attr( $value['background'] ) : ''; ?>"/>
				</td>
				<td data-title="<?php esc_attr_e( 'Background Hover', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[background-hover]" value="<?php echo isset( $value['background-hover'] ) ? esc_attr( $value['background-hover'] ) : ''; ?>"/>
				</td>
			<?php endif; ?>
			<?php if ( ! isset( $disable_border ) || ! $disable_border ) : ?>
				<td data-title="<?php esc_attr_e( 'Border', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[border]" value="<?php echo isset( $value['border'] ) ? esc_attr( $value['border'] ) : ''; ?>"/>
				</td>
				<td data-title="<?php esc_attr_e( 'Border hover', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[border-hover]" value="<?php echo isset( $value['border-hover'] ) ? esc_attr( $value['border-hover'] ) : ''; ?>"/>
				</td>
			<?php endif; ?>
		</tr>
		</tbody>
	</table>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
