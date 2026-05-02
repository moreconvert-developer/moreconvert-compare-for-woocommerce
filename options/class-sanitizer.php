<?php
/**
 * Sanitization Class
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 */

namespace MoreConvert\McCompare\MCTOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitizer Class
 */
class Sanitizer {

	/**
	 * Main dispatcher: sanitize a value based on field type.
	 *
	 * @param string $type   Field type (e.g., 'text', 'email', 'repeater').
	 * @param mixed  $value  Raw input value.
	 * @param array  $field  Field definition (contains args like 'options', 'limit').
	 *
	 * @return mixed Sanitized value.
	 */
	public static function sanitize_field( $type, $value, $field = array() ) {
		switch ( $type ) {
			case 'text':
			case 'email':
			case 'number':
			case 'hidden':
			case 'hidden-name':
			case 'datepicker':
			case 'page-select':
			case 'daterange':
				return sanitize_text_field( $value );
			case 'url':
				return esc_url_raw( $value );

			case 'textarea':
				return sanitize_textarea_field( $value );

			case 'wp-editor':
				// Allow safe HTML (e.g., from TinyMCE).
				return wp_kses_post( $value );

			case 'color':
				// Ensure valid hex/rgba? We'll just sanitize text.
				return sanitize_text_field( $value );

			case 'checkbox':
			case 'switch':
				return (int) (bool) $value;

			case 'checkbox-group':
			case 'color-style':
			case 'multi-select':
				if ( ! is_array( $value ) ) {
					return array();
				}
				return array_map( 'sanitize_text_field', $value );
			case 'add-button':
				return self::sanitize_add_button( $value );
			case 'nested-repeater':
			case 'inner-repeater':
			case 'repeater':
				return self::sanitize_repeater( $value, $field );
			case 'manage':
				return self::sanitize_manage( $value, $field );
			case 'radio':
			case 'select':
			case 'select-icon':
				// Ensure value is one of the predefined options.
				$options = $field['options'] ?? array();
				if ( self::is_valid_option( $value, $options ) ) {
					return sanitize_text_field( $value );
				}
				return $field['default'] ?? '';

			case 'select-file':
			case 'upload-image':
				// filename or url.
				return sanitize_text_field( $value );

			case 'code-editor':
				// Code content – do not strip anything (already stored as plain text).
				// But prevent potential XSS if output in unsafe context.
				return sanitize_textarea_field( $value ); // assuming esc_textarea will be used on output.

			case 'search-product':
			case 'search-product-cat':
			case 'search-post':
			case 'search-users':
				// Array of IDs or slugs.
				if ( ! is_array( $value ) ) {
					return array();
				}
				if ( 'search-product-cat' === $type ) {
					// slugs.
					return array_map( 'sanitize_title', $value );
				}
				return array_map( 'absint', $value );

			default:
				// Fallback.
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Reusable option validator for opt groups.
	 *
	 * @param string $value value.
	 * @param array  $options options.
	 *
	 * @return bool
	 */
	private static function is_valid_option( $value, $options ) {
		if ( ! is_array( $options ) ) {
			return false;
		}
		foreach ( $options as $key => $option ) {
			if ( $key === $value ) {
				return true;
			}
			if ( is_array( $option ) && isset( $option['options'] ) && is_array( $option['options'] ) ) {
				if ( self::is_valid_option( $value, $option['options'] ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Sanitize manage (table) field value.
	 * Processes both table-fields and fields (data fields only).
	 *
	 * @param array $value Raw manage data (array of rows).
	 * @param array $field Field definition containing 'table-fields', 'fields', and 'count'.
	 * @return array Sanitized manage data.
	 */
	private static function sanitize_manage( $value, $field ) {
		if ( ! is_array( $value ) ) {
			return array();
		}

		// Collect all data field definitions from table-fields and fields.
		$all_data_fields = array();

		// 1. Table fields (directly defined under 'table-fields').
		if ( isset( $field['table-fields'] ) && is_array( $field['table-fields'] ) ) {
			foreach ( $field['table-fields'] as $key => $sub_field ) {
				$type = isset( $sub_field['type'] ) ? $sub_field['type'] : '';
				if ( 'value' !== $type ) {
					$all_data_fields[ $key ] = $sub_field;
				}
			}
		}

		// 2. Additional fields from the 'fields' layout (skip layout-only types).
		$layout_types = array( 'title', 'value', 'end', 'separator', 'start', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' );
		if ( isset( $field['fields'] ) && is_array( $field['fields'] ) ) {
			foreach ( $field['fields'] as $key => $sub_field ) {
				$type = isset( $sub_field['type'] ) ? $sub_field['type'] : '';
				// Only include if it's a real data field, not a layout helper.
				if ( ! in_array( $type, $layout_types, true ) ) {
					$all_data_fields[ $key ] = $sub_field;
				}
			}
		}

		if ( empty( $all_data_fields ) ) {
			return array();
		}

		$max_rows       = isset( $field['count'] ) ? intval( $field['count'] ) : 0;
		$sanitized_rows = array();
		$row_index      = 0;

		foreach ( $value as $row ) {
			if ( $max_rows > 0 && $row_index >= $max_rows ) {
				break;
			}
			if ( ! is_array( $row ) ) {
				$row_index++;
				continue;
			}

			$clean_row = array();
			foreach ( $all_data_fields as $key => $sub_field ) {
				$raw_value = isset( $row[ $key ] ) ? $row[ $key ] : '';
				$sub_type  = isset( $sub_field['type'] ) ? $sub_field['type'] : 'text';

				// Use the existing sanitization logic for each field type.
				$clean_row[ $key ] = self::sanitize_field( $sub_type, $raw_value, $sub_field );
			}

			$sanitized_rows[] = $clean_row;
			$row_index++;
		}

		return $sanitized_rows;
	}

	/**
	 * Simple sanitization for button settings array.
	 *
	 * @param array $buttons The raw array.
	 * @return array Sanitized array.
	 */
	private static function sanitize_add_button( $buttons ) {
		if ( ! is_array( $buttons ) ) {
			return array();
		}

		$sanitized = array();

		foreach ( $buttons as $button ) {
			$clean_button = array();

			foreach ( $button as $key => $value ) {
				$clean_key = sanitize_key( $key );
				if ( 'custom-link' === $key ) {
					$clean_value = esc_url_raw( $value );
				} else {
					$clean_value = sanitize_text_field( $value );
				}

				$clean_button[ $clean_key ] = $clean_value;
			}

			$sanitized[] = $clean_button;
		}

		return $sanitized;
	}

	/**
	 * Sanitize repeater field value.
	 *
	 * @param array $value Raw repeater data (array of items).
	 * @param array $field Field definition containing 'repeater_fields' and 'limit'.
	 * @return array Sanitized repeater data.
	 */
	private static function sanitize_repeater( $value, $field ) {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$repeater_fields = isset( $field['repeater_fields'] ) && is_array( $field['repeater_fields'] )
			? $field['repeater_fields']
			: array();

		if ( empty( $repeater_fields ) ) {
			return array();
		}

		$limit           = isset( $field['limit'] ) ? intval( $field['limit'] ) : 0;
		$sanitized_items = array();
		$count           = 0;

		foreach ( $value as $item ) {
			if ( $limit > 0 && $count >= $limit ) {
				break;
			}

			if ( ! is_array( $item ) ) {
				continue;
			}

			$clean_item = array();
			foreach ( $repeater_fields as $sub_key => $sub_field ) {
				$raw_value = isset( $item[ $sub_key ] ) ? $item[ $sub_key ] : '';
				$sub_type  = isset( $sub_field['type'] ) ? $sub_field['type'] : 'text';

				// Use the same sanitize_field method recursively (but avoid infinite loop for 'repeater' inside repeater – optional).
				$clean_item[ $sub_key ] = self::sanitize_field( $sub_type, $raw_value, $sub_field );
			}

			$sanitized_items[] = $clean_item;
			$count++;
		}

		return $sanitized_items;
	}

}
