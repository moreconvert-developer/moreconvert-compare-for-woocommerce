<?php
/**
 * Template for displaying the add-button field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.3.0
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $links                     array  Button links
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     array Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 * @var $limit                     integer Limit account of repeater fields
 * @var $helps                     array Array of help for each column
 * @var $translatable              bool
 * @var $options_id                string option id
 * @var $section                   string section name
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="<?php echo esc_attr( $field_id ); ?>" class="mct-repeater simple-repeater <?php echo esc_attr( $class ); ?>" data-limit="<?php echo isset( $limit ) ? esc_attr( $limit ) : 0; ?>"
	<?php echo wp_kses_post( $dependencies ); ?>>
	<table class="mct-border-table mct-responsive-table" data-repeater-list="<?php echo esc_attr( $name ); ?>">
		<thead>
		<tr>
			<th><?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th><?php esc_attr_e( 'Text', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th><?php esc_attr_e( 'Background', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th><?php esc_attr_e( 'Background hover', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th><?php esc_attr_e( 'Text color', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th><?php esc_attr_e( 'Text hover color', 'moreconvert-compare-for-woocommerce' ); ?></th>
			<th>
				<?php esc_attr_e( 'Border radius', 'moreconvert-compare-for-woocommerce' ); ?>
				<?php if ( isset( $helps['border-radius'] ) && ! empty( $helps['border-radius'] ) ) : ?>
					<!-- MCT Help Tip -->
					<div class="mct-help-tip-wrap">
							<span class="mct-help-tip-dec">
								<?php if ( isset( $helps['border-radius']['help_image'] ) && ! empty( $helps['border-radius']['help_image'] ) ) : ?>
									<img src="<?php echo esc_url( $helps['border-radius']['help_image'] ); ?>"/>
								<?php endif; ?>
								<p><?php echo esc_attr( $helps['border-radius']['help'] ); ?></p>
							</span>
					</div>
				<?php endif; ?>
			</th>
			<th><?php esc_attr_e( 'Button link', 'moreconvert-compare-for-woocommerce' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if ( is_array( $value ) && ! empty( $value ) ) : ?>
			<?php foreach ( $value as $k => $row ) : ?>
				<?php $row['link'] = $row['link'] ?? 'back'; ?>
				<tr data-repeater-item>
					<td class="td-action" data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
						<a data-repeater-delete href="#"><span class="dashicons dashicons-dismiss"></span></a>
					</td>
					<td class="td-label" data-title="<?php esc_attr_e( 'Text', 'moreconvert-compare-for-woocommerce' ); ?>">
						<div class="d-inline-flex f-center gap-5">
							<input class="" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][label]" value="<?php echo esc_attr( $row['label'] ); ?>" type="text" autocomplete="off"/>
							<?php if ( ! empty( $translatable ) && true === $translatable && defined( 'WPML_ST_VERSION' ) ) : ?>
								<?php
								$url = add_query_arg(
									array(
										'page'    => 'wpml-string-translation/menu/string-translation.php',
										'context' => esc_attr( 'admin_texts_' . $options_id ),
										'search'  => esc_attr( ! empty( $section ) ? '[' . esc_attr( $options_id ) . '][' . esc_attr( $section ) . '][' . esc_attr( $name ) . '][' . esc_attr( $k ) . ']label' : '[' . esc_attr( $options_id ) . '][' . esc_attr( $name ) . '][' . esc_attr( $k ) . ']label' ),
									),
									admin_url( 'admin.php' )
								);
								?>
								<a href="<?php echo esc_url( $url ); ?>" class="btn-secondary min-pad btn-translation" target="_blank" style="text-align:center;display: inline-block;vertical-align: middle;"><span class="dashicons dashicons-translation"></span></a>
							<?php endif; ?>
						</div>
					</td>
					<td class="td-bg" data-title="<?php esc_attr_e( 'Background', 'moreconvert-compare-for-woocommerce' ); ?>">
						<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][background]" value="<?php echo esc_attr( $row['background'] ); ?>" type="text" autocomplete="off"/>
					</td>
					<td class="td-bg-hover" data-title="<?php esc_attr_e( 'Background hover', 'moreconvert-compare-for-woocommerce' ); ?>">
						<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][background-hover]" value="<?php echo esc_attr( $row['background-hover'] ); ?>" type="text" autocomplete="off"/>
					</td>
					<td class="td-label-color" data-title="<?php esc_attr_e( 'Text color', 'moreconvert-compare-for-woocommerce' ); ?>">
						<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][label-color]" value="<?php echo esc_attr( $row['label-color'] ); ?>" type="text" autocomplete="off"/>
					</td>
					<td class="td-label-hover" data-title="<?php esc_attr_e( 'Text hover color', 'moreconvert-compare-for-woocommerce' ); ?>">
						<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][label-hover-color]" value="<?php echo esc_attr( $row['label-hover-color'] ); ?>" type="text" autocomplete="off"/>
					</td>
					<td class="td-label-hover" data-title="<?php esc_attr_e( 'Border radius', 'moreconvert-compare-for-woocommerce' ); ?>">
						<input style="width:80px" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][border-radius]" value="<?php echo isset( $row['border-radius'] ) ? esc_attr( $row['border-radius'] ) : 0; ?>" type="text" autocomplete="off"/>
					</td>
					<td class="td-link" data-title="<?php esc_attr_e( 'Button link', 'moreconvert-compare-for-woocommerce' ); ?>">
						<select class="btn-link-type"
								name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][link]"
								onchange="mc_deps_link(this);">
							<?php foreach ( $links as $key => $label ) : ?>
								<option
										value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $row['link'] ); ?>><?php echo esc_attr( $label ); ?></option>
							<?php endforeach; ?>
						</select>
						<input class="show_on_custom-link" style="margin-top: 10px ;display: <?php echo 'custom-link' === $row['link'] ? 'inline-block' : 'none !important'; ?>" placeholder="<?php esc_attr_e( 'Url', 'moreconvert-compare-for-woocommerce' ); ?>" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $k ); ?>][custom-link]" value="<?php echo isset( $row['custom-link'] ) ? esc_url( $row['custom-link'] ) : ''; ?>" type="url" autocomplete="off"/>
					</td>


				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr data-repeater-item>
				<td class="td-action" data-title="<?php esc_attr_e( 'Action', 'moreconvert-compare-for-woocommerce' ); ?>">
					<a data-repeater-delete href="#"><span class="dashicons dashicons-dismiss"></span></a>
				</td>
				<td class="td-label" data-title="<?php esc_attr_e( 'Text', 'moreconvert-compare-for-woocommerce' ); ?>">
					<div class="d-inline-flex f-center gap-5">
						<input class="" autocomplete="off" type="text" name="<?php echo esc_attr( $name ); ?>[][label]"/>
						<?php if ( ! empty( $translatable ) && true === $translatable && defined( 'WPML_ST_VERSION' ) ) : ?>
							<?php
							$url = add_query_arg(
								array(
									'page'    => 'wpml-string-translation/menu/string-translation.php',
									'context' => esc_attr( 'admin_texts_' . $options_id ),
									'search'  => esc_attr( ! empty( $section ) ? '[' . esc_attr( $options_id ) . '][' . esc_attr( $section ) . '][' . esc_attr( $name ) . ']' : '[' . esc_attr( $options_id ) . '][' . esc_attr( $name ) . ']' ),
								),
								admin_url( 'admin.php' )
							);
							?>
							<a href="<?php echo esc_url( $url ); ?>" class="btn-secondary min-pad btn-translation" target="_blank" style="text-align:center;display: inline-block;vertical-align: middle;"><span class="dashicons dashicons-translation"></span></a>
						<?php endif; ?>
					</div>
				</td>
				<td class="td-bg" data-title="<?php esc_attr_e( 'Background', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[][background]" type="text" autocomplete="off"/>
				</td>
				<td class="td-bg-hover" data-title="<?php esc_attr_e( 'Background hover', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[][background-hover]" type="text" autocomplete="off"/>
				</td>
				<td class="td-label-color" data-title="<?php esc_attr_e( 'Text color', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[][label-color]" type="text" autocomplete="off"/>
				</td>
				<td class="td-label-hover" data-title="<?php esc_attr_e( 'Text hover color', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="mct-color-picker" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>[][label-hover-color]" type="text" autocomplete="off"/>
				</td>
				<td class="td-label-hover" data-title="<?php esc_attr_e( 'Border radius', 'moreconvert-compare-for-woocommerce' ); ?>">
					<input class="" style="width:80px" name="<?php echo esc_attr( $name ); ?>[][border-radius]" type="text" autocomplete="off"/>
				</td>
				<td class="td-link" data-title="<?php esc_attr_e( 'Button link', 'moreconvert-compare-for-woocommerce' ); ?>">
					<select class="" name="<?php echo esc_attr( $name ); ?>[][link]" onchange="mc_deps_link(this);">
						<?php foreach ( $links as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<input class="show_on_custom-link" style="margin-top: 10px ;display: none !important;" placeholder="<?php esc_attr_e( 'Url', 'moreconvert-compare-for-woocommerce' ); ?>" name="<?php echo esc_attr( $name ); ?>[][custom-link]" type="url" autocomplete="off"/>

				</td>
			</tr>
		<?php endif; ?>

		</tbody>
	</table>
	<button data-repeater-create type="button" class="btn-secondary">
		<?php esc_attr_e( 'Add new button', 'moreconvert-compare-for-woocommerce' ); ?>
	</button>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
