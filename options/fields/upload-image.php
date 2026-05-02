<?php
/**
 * Template for displaying the Upload Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.0.0
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     string Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies                 string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 * @var $assets_url                string assets url
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$default_image = $assets_url . '/img/no-image.svg';

if ( ! empty( $value ) ) {
	$image_attributes = wp_get_attachment_image_src( $value );
	$src              = $image_attributes[0];
} else {
	$src = $default_image;
}
?>
<div class="upload-image <?php echo ! empty( $class ) ? esc_attr( $class ) : ''; ?>" id="<?php echo esc_attr( $field_id ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<img class="" data-src="<?php echo esc_url( $default_image ); ?>" src="<?php echo esc_url( $src ); ?>" alt="upload image"/>
	<div>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
		<button type="submit"
				class="mct_upload_image_button min-width-btn btn-secondary"><?php esc_attr_e( 'Add Image', 'moreconvert-compare-for-woocommerce' ); ?></button>
		<button type="submit" class="mct_remove_image_button btn-secondary min-pad"><span
					class="dashicons dashicons-no-alt"></span></button>
	</div>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description" <?php echo wp_kses_post( $dependencies ); ?>><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
