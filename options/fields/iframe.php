<?php
/**
 * Template for displaying the Columns
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.4.3
 * @since 2.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template variables:
 *
 * @var $title    string Field title
 * @var $desc     string Description
 * @var $help     string help tips
 * @var $field_id string Field Id
 * @var $class string Field class
 * @var $src string Src.
 * @var $data string Data attributes
 * @var $custom_attributes  string Custom attributes
 * @var $dependencies string Dependencies
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

?>
</body>
</table>
<?php if ( isset( $title ) ) : ?>
	<p>
		<strong><?php echo esc_attr( $title ); ?></strong>
		<?php if ( isset( $help ) ) : ?>
			<!-- MCT Help Tip -->
			<div class="mct-help-tip-wrap no-float">
						<span class="mct-help-tip-dec">
							<?php if ( isset( $help_image ) && ! empty( $help_image ) ) : ?>
								<img src="<?php echo esc_url( $help_image ); ?>"/>
							<?php endif; ?>
							<p><?php echo wp_kses_post( $help ); ?></p>
						</span>
			</div>
		<?php endif; ?>
	</p>
<?php endif; ?>
<iframe class="<?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $field_id ); ?>" loading="lazy"
		src="<?php echo 'about:blank' !== $src ? esc_url( $src ) : 'about:blank'; ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>>
</iframe>
<?php if ( isset( $desc ) ) : ?>
	<p class='description'>
		<?php echo wp_kses_post( $desc ); ?>
	</p>
<?php endif; ?>
<table class="form-table" role="presentation">
	<tbody>
