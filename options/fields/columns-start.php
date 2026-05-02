<?php
/**
 * Template for displaying the Columns
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.4.3
 * @since 2.4.3
 */

/**
 * Template variables:
 *
 * @var $field_id string Field Id
 * @var $class string Field class
 * @var $columns int columns count
 * @var $data string Data attributes
 * @var $custom_attributes  string Custom attributes
 * @var $dependencies string Dependencies
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</tbody>
</table>
<div class="mct-columns columns-<?php echo esc_attr( $columns ); ?>  <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $field_id ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
