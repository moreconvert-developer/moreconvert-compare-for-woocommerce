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
 * @var $limit                     integer Limit account of repeater fields
 * @var $mct_fields                Fields
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="<?php echo esc_attr( $field_id ); ?>" class="mct-repeater simple-repeater <?php echo esc_attr( $class ); ?>" data-limit="<?php echo isset( $limit ) ? esc_attr( $limit ) : 0; ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<table class="mct-border-table mct-responsive-table mct-repeater-table" data-repeater-list="<?php echo esc_attr( $name ); ?>">
		<thead>
			<tr class="mct-repeater-header">
				<?php
				foreach ( $repeater_fields as $field_key => $field_value ) {
					?>
					<th>
						<?php echo esc_attr( $field_value['label'] ); ?>
						<?php if ( isset( $field_value['help'] ) && ! empty( $field_value['help'] ) ) : ?>
							<!-- MCT Help Tip -->
							<div class="mct-help-tip-wrap">
								<span class="mct-help-tip-dec">
									<?php if ( isset( $field_value['help_image'] ) && ! empty( $field_value['help_image'] ) ) : ?>
										<img src="<?php echo esc_url( $field_value['help_image'] ); ?>"/>
									<?php endif; ?>
									<p><?php echo esc_attr( $field_value['help'] ); ?></p>
								</span>
							</div>
						<?php endif; ?>
					</th>
					<?php
				}
				?>
				<th><?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if ( is_array( $value ) && ! empty( $value ) ) : ?>
			<?php foreach ( $value as $k => $row ) : ?>
				<tr class="mct-repeater-row" data-repeater-item>
					<?php foreach ( $repeater_fields as $field_key => $field_value ) : ?>
						<td class="row-options " data-title="<?php echo esc_attr( $field_value['label'] ); ?>">
							<?php $mct_fields->print_field_repeater( $section, $name, $k, $field_key, $field_value, $row[ $field_key ] ?? '' ); ?>
						</td>
					<?php endforeach; ?>
					<td data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
						<a data-repeater-delete href="#"><span class="dashicons dashicons-dismiss"></span></a>
					</td>

				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr class="mct-repeater-row" data-repeater-item>
				<?php foreach ( $repeater_fields as $field_key => $field_value ) : ?>
					<td class="row-options" data-title="<?php echo esc_attr( $field_value['label'] ); ?>">
						<?php $mct_fields->print_field_repeater( $section, $name, '', $field_key, $field_value ); ?>
					</td>
				<?php endforeach; ?>
				<td data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
					<a data-repeater-delete href="#"><span class="dashicons dashicons-dismiss"></span></a>
				</td>

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
