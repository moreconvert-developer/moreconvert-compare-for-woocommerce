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
 * @var $field_key                 string Field Key
 * @var $value                     array Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
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
<div id="<?php echo esc_attr( $field_id ); ?>" class="mct-repeater inner-repeater <?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<table class="mct-border-table mct-responsive-table" data-repeater-list="<?php echo esc_attr( $field_key ); ?>">
		<tbody>
		<?php if ( is_array( $value ) && ! empty( $value ) ) : ?>
			<?php foreach ( $value as $k => $row ) : ?>
				<tr data-repeater-item>
					<?php foreach ( $repeater_fields as $field_key => $field_value ) : ?>
						<td class="row-options " data-title="<?php echo esc_attr( $field_value['label'] ); ?>">
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
							<?php $mct_fields->print_field_repeater( $section, $name, $k, $field_key, $field_value, $row[ $field_key ] ?? '' ); ?>
						</td>
					<?php endforeach; ?>
					<td data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
						<div class="margin-bet">
							<a class="btn-secondary small-btn min-pad add-new-row inline" href="#">
								<?php echo wp_kses_post( $field['add_button'] ?? '<span class="dashicons dashicons-plus"></span>' ); ?>
							</a>
							<a data-repeater-delete class="inline" href="#">
								<?php echo wp_kses_post( $field['remove_button'] ?? '<span class="dashicons dashicons-minus"></span>' ); ?>
							</a>
						</div>
					</td>

				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr data-repeater-item>
				<?php foreach ( $repeater_fields as $field_key => $field_value ) : ?>
					<td class="row-options" data-title="<?php echo esc_attr( $field_value['label'] ); ?>">
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
						<?php $mct_fields->print_field_repeater( $section, $name, '', $field_key, $field_value ); ?>
					</td>
				<?php endforeach; ?>
				<td data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
					<div class="margin-bet">
						<a class="btn-secondary small-btn min-pad  inline add-new-row" href="#">
							<?php echo wp_kses_post( $field['add_button'] ?? '<span class="dashicons dashicons-plus"></span>' ); ?>
						</a>
						<a data-repeater-delete class="inline" href="#">
							<?php echo wp_kses_post( $field['remove_button'] ?? '<span class="dashicons dashicons-minus"></span>' ); ?>
						</a>
					</div>

				</td>

			</tr>
		<?php endif; ?>

		</tbody>
	</table>
	<a data-repeater-create class="btn-secondary small-btn min-pad hidden-option" href="#"><span
				class="dashicons dashicons-plus"></span> </a>


</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
