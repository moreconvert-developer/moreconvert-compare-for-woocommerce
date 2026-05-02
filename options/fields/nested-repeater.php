<?php
/**
 * Template for displaying the repeater field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.0.0
 */

namespace MoreConvert\McCompare\MCTOptions;

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
 * @var $repeater_fields           array Array of all fields
 * @var $add_new_label             string Label of "add new row"
 * @var $section                   string active Section
 * @var $field                     array Array of all field attributes
 * @var $mct_fields                Fields
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="<?php echo esc_attr( $field_id ); ?>" class="mct-repeater nested-repeater <?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<table class="mct-responsive-table mct-nested-repeater-table" data-repeater-list="<?php echo esc_attr( $name ); ?>">
		<tbody>
		<?php if ( is_array( $value ) && ! empty( $value ) ) : ?>
			<?php foreach ( $value as $k => $row ) : ?>
				<tr data-repeater-item>
					<?php foreach ( $repeater_fields as $field_key => $field_value ) : ?>
						<td class="row-options no-pad" data-row-title="<?php echo esc_attr( $field_value['label'] ); ?>">
							<?php $mct_fields->print_field_repeater( $section, $name, $k, $field_key, $field_value, $row[ $field_key ] ?? '' ); ?>
						</td>
					<?php endforeach; ?>

				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr data-repeater-item>
				<?php foreach ( $repeater_fields as $field_key => $field_value ) : ?>
					<td class="row-options no-pad" data-row-title="<?php echo esc_attr( $field_value['label'] ); ?>">
						<?php $mct_fields->print_field_repeater( $section, $name, '', $field_key, $field_value ); ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endif; ?>

		</tbody>
	</table>
	<button data-repeater-create type="button" class="btn-secondary">
		<?php echo esc_attr( $add_new_label ); ?>
	</button>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
