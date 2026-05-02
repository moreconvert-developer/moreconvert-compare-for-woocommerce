<?php
/**
 * Template for displaying the group fields
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
 * @var $fields                    array Array of all fields
 * @var $section                   string active Section
 * @var $field                     array Array of all field attributes
 * @var $header_hide               bool
 * @var $mct_fields                Fields
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$header_hide = $header_hide ?? false;
?>

<div id="<?php echo esc_attr( $field_id ); ?>" class="mct-group-fields <?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<table class="<?php echo $header_hide ? '' : 'mct-border-table'; ?> mct-responsive-table">
		<?php if ( ! $header_hide ) : ?>
		<thead>
			<tr class="mct-group-row">
				<?php
				foreach ( $fields as $field_key => $field_value ) {
					$col_dependencies = '';
					if ( isset( $field_value['dependencies'] ) ) {
						if ( isset( $field_value['dependencies']['id'] ) ) {
							$col_dependencies .= " data-deps='" . wp_json_encode(
								array(
									'id'    => esc_attr( $field_value['dependencies']['id'] ),
									'value' => esc_attr( $field_value['dependencies']['value'] ),
								)
							) . "'";
						} else {
							$col_dependencies .= " data-deps='" . wp_json_encode( $field_value['dependencies'] ) . "'";
						}
					}
					?>
					<th class="row-options">
						<input type="hidden" <?php echo wp_kses_post( $col_dependencies ); ?>/>
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
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
		<tr class="mct-group-row">
			<?php foreach ( $fields as $field_key => $field_value ) : ?>
				<td class="row-options" data-title="<?php echo esc_attr( $field_value['label'] ); ?>">
					<?php
					$field_value['default'] = $value[ $field_key ] ?? '';
					$mct_fields->print_field( $section, $field_key, $field_value );
					?>
				</td>
			<?php endforeach; ?>
		</tr>
		</tbody>
	</table>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
