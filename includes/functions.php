<?php
/**
 * MoreConvert Compare for WooCommerce Functions
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* === TEMPLATE FUNCTIONS === */

if ( ! function_exists( 'moreconvert_compare_locate_template' ) ) {
	/**
	 * Locate the templates and return the path of the file found
	 *
	 * @param string $path Path to locate.
	 *
	 * @version 1.0.0
	 * @return string
	 */
	function moreconvert_compare_locate_template( $path ) {
		$woocommerce_base = WC()->template_path();

		$template_woocommerce_path = $woocommerce_base . $path;
		$template_path             = '/' . $path;
		$plugin_path               = MORECONVERT_COMPARE_DIR . 'templates/' . $path;

		$located = locate_template(
			array(
				$template_woocommerce_path, // Search in <theme>/woocommerce/.
				$template_path,            // Search in <theme>/.
			)
		);

		if ( ! $located && file_exists( $plugin_path ) ) {
			return apply_filters( 'moreconvert_compare_locate_template', $plugin_path, $path );
		}

		return apply_filters( 'moreconvert_compare_locate_template', $located, $path );
	}
}

if ( ! function_exists( 'moreconvert_compare_get_template' ) ) {
	/**
	 * Retrieve a template file.
	 *
	 * @param string $path Path to get.
	 * @param mixed  $var Variables to send to template.
	 * @param bool   $should_return Whether to return or print the template.
	 *
	 * @return string|void
	 */
	function moreconvert_compare_get_template( $path, $var = null, $should_return = false ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames
		$located = moreconvert_compare_locate_template( $path );

		if ( $var && is_array( $var ) ) {
			$atts = $var;
			extract( $var ); // @codingStandardsIgnoreLine.
		}

		if ( $should_return ) {
			ob_start();
		}

		// Include file located.
		include $located;

		if ( $should_return ) {
			return ob_get_clean();
		}
	}
}

/* === TESTER FUNCTIONS === */

if ( ! function_exists( 'moreconvert_compare_is_true' ) ) {
	/**
	 * Is something true?
	 *
	 * @param string|bool|int $value The value to check for.
	 *
	 * @return bool
	 */
	function moreconvert_compare_is_true( $value ): bool {
		return 'yes' === strtolower( $value ) || 'true' === strtolower( $value ) || 1 === $value || '1' === $value || true === $value || 'on' === strtolower( $value );
	}
}

if ( ! function_exists( 'moreconvert_compare_str_contains' ) ) {
	/**
	 * Determine if a string contains a given substring
	 *
	 * @param string $haystack String.
	 * @param string $needle Substring.
	 *
	 * @return bool
	 */
	function moreconvert_compare_str_contains( $haystack, $needle ) {
		if ( function_exists( 'str_contains' ) ) {
			return '' !== $needle && str_contains( $haystack, $needle ) !== false;
		}
		return '' !== $needle && mb_strpos( $haystack, $needle ) !== false;
	}
}

if ( ! function_exists( 'moreconvert_compare_sanitize_svg' ) ) {
	/**
	 * Sanitize SVG
	 *
	 * @param string $svg SVG code.
	 * @return false|string
	 * @since 1.0.0
	 */
	function moreconvert_compare_sanitize_svg( string $svg ) {
		$sanitizer = new enshrined\svgSanitize\Sanitizer();
		return $sanitizer->sanitize( $svg );
	}
}
