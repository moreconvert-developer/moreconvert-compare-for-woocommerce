<?php
/**
 * Template for displaying the Code Editor Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $class                     string Field class
 * @var $field_id                  string Field id
 * @var $value                     string Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies              string Dependencies
 * @var $desc                      string Description
 * @var $editor_height             integer Editor Height
 * @var $code_type                 string code type 'css , php , ... '
 * @var $field                     array Array of all field attributes
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
wp_add_inline_style(
	'code-editor',
	'
    .CodeMirror {
        background-color: #F9F9F9 !important;
    }
    .CodeMirror-gutters {
        background-color: #ebebeb !important;
    }
    '
);
?>
<textarea id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( $name ); ?>"
		class="mct-code-editor <?php echo esc_attr( $class ); ?>"
		data-type="<?php echo esc_attr( $code_type ?? 'css' ); ?>"
		data-height="<?php echo esc_attr( $editor_height ?? '400px' ); ?>"
		rows="15"
	<?php echo wp_kses_post( $dependencies ); ?>
	<?php echo wp_kses_post( $custom_attributes ); ?>
	<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>
	style="direction: ltr; width: 100%;"><?php echo esc_textarea( $value ); ?></textarea>

<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
