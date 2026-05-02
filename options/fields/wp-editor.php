<?php
/**
 * Template for displaying the Wp Editor Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.2.0
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
 * @var $editor_height             integer Editor Height
 * @var $field                     array Array of all field attributes
 * @var $translatable              bool
 * @var $options_id                string option id
 * @var $section                   string section name
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$editor_id = str_replace( array( '[', ']' ), '_', $name );

$args = array(

	'wpautop'       => false,
	// Choose if you want to use wpautop.
	'media_buttons' => true,
	// Choose if showing media button(s).
	'textarea_name' => $name,
	// Set the textarea name to something different, square brackets [] can be used here.
	'textarea_rows' => 10,

	'editor_height' => $editor_height ?? '',
	// Set the number of rows.
	'tabindex'      => '',
	'editor_css'    => '',
	// Intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
	'editor_class'  => '',
	// Add extra class(es) to the editor textarea.
	'teeny'         => false,
	// Output the minimal editor config used in Press This.
	'dfw'           => false,
	// Replace the default fullscreen with DFW (needs specific DOM elements and css).
	'tinymce'       => true,
	// Load TinyMCE, can be used to pass settings directly to TinyMCE using an array().
	'quicktags'     => true,
	// Load Quicktags, can be used to pass settings directly to Quicktags using an array().
);
?>
<div class="d-flex gap-5">
	<div class="editor  <?php echo esc_attr( $class ); ?>" data-type="wp_editor"
		<?php echo wp_kses_post( $dependencies ); ?>
		<?php echo wp_kses_post( $custom_attributes ); ?>
		<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>><?php wp_editor( $value, $editor_id, $args ); ?></div>
	<?php if ( ! empty( $translatable ) && true === $translatable && defined( 'WPML_ST_VERSION' ) ) : ?>
		<?php
		$url = add_query_arg(
			array(
				'page'    => 'wpml-string-translation/menu/string-translation.php',
				'context' => esc_attr( 'admin_texts_' . $options_id ),
				'search'  => esc_attr( ! empty( $section ) ? '[' . esc_attr( $options_id ) . '][' . esc_attr( $section ) . ']' . esc_attr( $name ) : '[' . esc_attr( $options_id ) . ']' . esc_attr( $name ) ),
			),
			admin_url( 'admin.php' )
		);
		?>
		<a href="<?php echo esc_url( $url ); ?>" class="btn-secondary min-pad btn-translation"  target="_blank" style="text-align:center;display: inline-block;vertical-align: middle;"><span class="dashicons dashicons-translation"></span></a>
	<?php endif; ?>
</div>
<?php if ( isset( $desc ) ) : ?>
	<br/><p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
