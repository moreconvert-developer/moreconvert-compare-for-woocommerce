<?php
/**
 * Template for displaying the Manage field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.1.1
 * @since 1.1.0
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
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $fields                    array Array of all fields
 * @var $section                   string active Section
 * @var $field                     array Array of all field attributes
 * @var $mct_fields                Fields
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
</tbody>
</table>

<div id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo isset( $field['title'] ) && '' !== $field['title'] ? 'mct-article ' : ''; ?> <?php echo ' article-' . esc_attr( $field_id ); ?>  <?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<?php if ( isset( $field['title'] ) && '' !== $field['title'] ) : ?>
		<div class="article-title">
			<h2>
				<?php echo esc_attr( $field['title'] ); ?>
			</h2>
			<?php if ( isset( $help ) ) : ?>
				<p class="description"><?php echo wp_kses_post( $help ); ?></p>
			<?php endif; ?>
		</div>
		<br>
		<br>
	<?php endif; ?>
	<table class="mct-border-table mct-manages mct-responsive-table">
		<thead>
		<tr>
			<th><?php esc_html_e( 'Row', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<?php
			foreach ( $field['table-fields'] as $k => $col ) {
				echo '<th>';
				echo esc_attr( $col['label'] );
				if ( isset( $col['help'] ) && ! empty( $col['help'] ) ) :
					?>
					<!-- MCT Help Tip -->
					<div class="mct-help-tip-wrap">
					<span class="mct-help-tip-dec">
						<?php if ( isset( $col['help_image'] ) && ! empty( $col['help_image'] ) ) : ?>
							<img src="<?php echo esc_url( $col['help_image'] ); ?>"/>
						<?php endif; ?>
						<p><?php echo esc_attr( $col['help'] ); ?></p>
					</span>
					</div>
					<?php
				endif;

				echo '</th>';
			}
			?>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		for ( $i = 0; $i <= $field['count'] - 1; $i++ ) {
			?>

			<tr class="manage-row">
				<td data-title="<?php esc_attr_e( 'Row', 'moreconvert-compare-for-woocommerce' ); ?>">
					Email-0<?php echo esc_attr( $i + 1 ); ?></td>
				<?php
				foreach ( $field['table-fields'] as $k => $col ) {
					echo '<td data-title="' . esc_attr( $col['label'] ) . '">';
					if ( isset( $field['default'][ $i ][ $k ] ) ) {
						$col['default'] = $field['default'][ $i ][ $k ];
					}

					$val = $value[ $i ][ $k ] ?? '';

					if ( in_array( $col['type'], array( 'switch', 'checkbox' ), true ) ) {
						$val = $value[ $i ][ $k ] ?? '0';
					}
					if ( isset( $col['value_class'] ) && ! empty( $col['value_class'] ) && is_callable( $col['value_class'] ) && isset( $col['value_depend'] ) && '' !== $col['value_depend'] ) {

						$value_dep = ( 'row' === $col['value_depend'] ) ? $i : ( $value[ $i ][ $col['value_depend'] ] ?? '' );

						if ( '' !== $value_dep ) {

							$val = call_user_func( $col['value_class'], $value_dep );
						}
					}
					$mct_fields->print_field_manage( $section, $name, $i, $k, $col, $val );
					echo '&nbsp;</td>';
				}
				?>
				<td data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
					<a href="#<?php echo esc_attr( $field_id ); ?>_<?php echo esc_attr( $i ); ?>" class="mar-5 center-align btn-secondary ico-btn gear-btn min-width-btn show-manage-item small-btn">
						<?php esc_html_e( 'Manage', 'moreconvert-compare-for-woocommerce' ); ?>
					</a>
					<?php if ( isset( $field['table-action'] ) ) : ?>
						<a href="#" data-id="<?php echo esc_attr( $i ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>" class="mar-5 center-align  <?php echo esc_attr( $field['table-action']['class'] ); ?>">
							<?php echo esc_attr( $field['table-action']['title'] ); ?>
						</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php } ?>

		</tbody>
	</table>

</div>
<?php
for ( $i = 0; $i <= $field['count'] - 1; $i++ ) :
	// print table of fields.
	?>
	<div class="mct-manage-item mct-article" id="<?php echo esc_attr( $field_id ); ?>_<?php echo esc_attr( $i ); ?>" style="display: none">
		<div class="article-title">
			<h2 class="margin-bet d-flex space-between">
				<span><?php echo $field['row-title'] ? esc_attr( str_replace( '%s', $i + 1, $field['row-title'] ) ) : ''; ?></span>
				<a class="back-manage-item btn-secondary min-width-btn center-align small-btn " href="#"><?php esc_html_e( 'Close', 'moreconvert-compare-for-woocommerce' ); ?></a>
			</h2>
			<span class="description"><?php echo $field['row-desc'] ? esc_attr( $field['row-desc'] ) : ''; ?></span>
		</div>

		<table class="form-table" role="presentation">
			<tbody>
			<?php foreach ( $field['fields'] as $k => $col ) : ?>
				<?php
				if ( isset( $field['default'][ $i ][ $k ] ) ) {
					$col['default'] = $field['default'][ $i ][ $k ];
				}
				?>
				<?php if ( ! in_array( $col['type'], array( 'end', 'separator', 'start', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) ) : ?>
					<tr class="row-options  row-<?php echo esc_attr( $field_id ); ?> <?php echo isset( $col['parent_class'] ) ? esc_attr( $col['parent_class'] ) : ''; ?>">
					<th scope="row">
						<?php echo esc_attr( $col['label'] ); ?>
						<?php if ( isset( $col['help'] ) && ! empty( $col['help'] ) ) : ?>
							<!-- MCT Help Tip -->
							<div class="mct-help-tip-wrap">
								<span class="mct-help-tip-dec">
									<?php if ( isset( $col['help_image'] ) && ! empty( $col['help_image'] ) ) : ?>
										<img src="<?php echo esc_url( $col['help_image'] ); ?>"/>
									<?php endif; ?>
									<p><?php echo esc_attr( $col['help'] ); ?></p>
								</span>
							</div>
						<?php endif; ?>
					</th>
					<td>
				<?php endif; ?>
				<?php $mct_fields->print_field_manage( $section, $name, $i, $k, $col, $value[ $i ][ $k ] ?? '' ); ?>
				<?php if ( ! in_array( $col['type'], array( 'end', 'separator', 'start', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) ) : ?>
					</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
endfor;
?>
<table class="form-table" role="presentation">
	<tbody>
