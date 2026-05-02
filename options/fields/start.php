<?php
/**
 * Template for displaying the Start Article
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.3.1
 * @since 1.1.0
 */

/**
 * Template variables:
 *
 * @var $title                     string Field title
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     string Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $help                      string help tips
 * @var $youtube                   string video url
 * @var $doc                       string doc url
 * @var $help_image                string help tips image
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

</tbody>
</table>
<div class="mct-article <?php echo 'article-' . esc_attr( $field_id ); ?> <?php echo esc_attr( $class ); ?>" <?php echo wp_kses_post( $dependencies ); ?> <?php echo wp_kses_post( $custom_attributes ); ?>>
	<div class="article-title d-flex f-center f-wrap space-between">
		<div >
			<h2>
				<span><?php echo wp_kses_post( $title ); ?></span>
			</h2>

			<?php if ( isset( $desc ) ) : ?>
				<p class='description'>
					<?php echo wp_kses_post( $desc ); ?>
				</p>
			<?php endif; ?>
		</div>
		<div class="d-flex f-center gap-5">
			<?php if ( isset( $doc ) ) : ?>
				<!-- MCT Document -->
				<a href="<?php echo esc_url( $doc ); ?>" target="_blank" class="btn-flat article-guide">
					<?php esc_attr_e( 'Section Guide', 'moreconvert-compare-for-woocommerce' ); ?>
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
				</a>
			<?php elseif ( isset( $help ) ) : ?>
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
		</div>

	</div>
	<table class="form-table" role="presentation">
		<tbody>
