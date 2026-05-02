<?php
/**
 * Template for displaying the search post Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.6
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $label                     string Field label
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     array Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'select2' );

wp_enqueue_script( 'selectWoo' );

wp_enqueue_script( 'wc-enhanced-select' );

?>
<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_attr( $label ); ?></label>
<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo '' !== $name ? esc_attr( $name ) . '[]' : ''; ?>" multiple="multiple" class="mc-post-search <?php echo esc_attr( $class ); ?>" data-allow_clear="true" data-minimum_input_length="3"
	data-placeholder="<?php esc_attr_e( 'Search for a post&hellip;', 'moreconvert-compare-for-woocommerce' ); ?>"
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
	<?php
	if ( is_array( $value ) && ! empty( $value ) ) {
		foreach ( $value as $_post_id ) {
			$_post = get_post( $_post_id );
			if ( is_object( $_post ) ) {
				echo '<option value="' . esc_attr( $_post_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $_post->post_title ) ) . '</option>';
			}
		}
	}
	?>
</select>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
