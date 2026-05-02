<?php
/**
 * Template for displaying the Page Select Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.0.0
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
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $exclude                   array Exclude page ids
 * @var $field                     array Array of all field attributes
 * @var $show_links                bool show/hide page links
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$class = $show_links ? $class . ' page-show-links-trigger ' : $class;
?>
<div class="d-flex gap-5"
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>
	<?php echo wp_kses_post( $dependencies ); ?>>
	<?php
	wp_dropdown_pages(
		array(
			'id'                => esc_attr( $field_id ),
			'class'             => esc_attr( $class ),
			'name'              => esc_attr( $name ),
			'echo'              => 1,
			'show_option_none'  => esc_attr__( '&mdash; Select a page  &mdash;', 'moreconvert-compare-for-woocommerce' ),
			'option_none_value' => '0',
			'selected'          => esc_attr( $value ),
			'post_type'         => 'page',
		)
	);
	?>
	<?php
	if ( 0 < intval( $value ) && $show_links ) {
		echo '<div class="d-inline-flex gap-5 f-center page-show-links-target" data-page-id="' . esc_attr( $value ) . '">';
		echo '<a href="' . esc_url( get_edit_post_link( $value ) ) . '" target="_blank" class="btn-secondary center-align min-pad"><span class="dashicons dashicons-edit"></span></a>';
		echo '<a href="' . esc_url( get_the_permalink( $value ) ) . '" target="_blank" class="btn-secondary center-align min-pad" ><span class="dashicons dashicons-visibility"></span></a>';
		echo '</div>';
	}
	?>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
