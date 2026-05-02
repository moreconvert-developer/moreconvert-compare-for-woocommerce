<?php
/**
 * Helper Class
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 */

namespace MoreConvert\McCompare\MCTOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper Class
 */
class Helpers {


	/**
	 * Get main array options
	 * return an array with all key options
	 *
	 * @param array $options array of options.
	 *
	 * @return array
	 * @version 1.1.0
	 */
	public static function get_main_key_options( array $options ): array {
		$all_fields = array();
		foreach ( $options as $section => $value ) {
			$section_fields = array();
			if ( isset( $value['tabs'] ) ) {
				foreach ( $value['tabs'] as $tab => $fields ) {
					foreach ( $options[ $section ]['fields'][ $tab ] as $k => $v ) {
						if ( isset( $v['type'] ) && ! in_array( $v['type'], array( 'end', 'separator', 'hidden-name', 'start', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) && ( ! isset( $v['remove_name'] ) || false === $v['remove_name'] ) ) {
							if ( 'group-fields' === $v['type'] ) {
								foreach ( $v['fields'] as $fk => $fv ) {
									$section_fields[] = $fk;
								}
							} else {
								$section_fields[] = $k;
							}
						}
					}
				}
			} else {
				foreach ( $value['fields'] as $k => $v ) {
					if ( isset( $v['type'] ) && ! in_array( $v['type'], array( 'end', 'separator', 'hidden-name', 'start', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) && ( ! isset( $v['remove_name'] ) || false === $v['remove_name'] ) ) {
						if ( 'group-fields' === $v['type'] ) {
							foreach ( $v['fields'] as $fk => $fv ) {
								$section_fields[] = $fk;
							}
						} else {
							$section_fields[] = $k;
						}
					}
				}
			}
			$all_fields[ $section ] = $section_fields;
		}
		return $all_fields;
	}

	/**
	 * Retrieve the full field definition array for a given section and field name.
	 *
	 * @param array  $options The all options.
	 * @param string $section_key The section ID (e.g., 'general', 'styling').
	 * @param string $field_name  The field name (e.g., 'my_text_field').
	 *
	 * @return array|false Field definition array if found, false otherwise.
	 */
	public static function get_field_definition( $options, $section_key, $field_name ) {
		// Guard: ensure options config exists and section is set.
		if ( empty( $options ) || ! isset( $options[ $section_key ] ) ) {
			return false;
		}

		$section = $options[ $section_key ];

		// Case 1: Section has tabs.
		if ( isset( $section['tabs'] ) && is_array( $section['tabs'] ) && ! empty( $section['tabs'] ) ) {
			foreach ( $section['fields'] as $tab_key => $fields ) {
				$return_field = self::get_field( $fields, $field_name );
				if ( $return_field ) {
					return $return_field;
				}
			}
			return false;
		}

		// Case 2: Section without tabs - fields directly under 'fields'.
		if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
			return self::get_field( $section, $field_name );
		}

		// Field not found.
		return false;
	}


	/**
	 * Get Field
	 *
	 * @param array  $fields fields.
	 * @param string $field_name field key.
	 *
	 * @return false|mixed
	 */
	private static function get_field( $fields, $field_name ) {
		if ( ! empty( $fields ) ) {
			if ( isset( $fields[ $field_name ] ) ) {
				return $fields[ $field_name ];
			}
			$return_field = false;
			foreach ( $fields as $key => $field ) {

				/**
				* If ( $key === $field_name && isset( $field['type']) && in_array( $field['type'], array( 'checkbox', 'checkbox-group', 'code-editor', 'color' , 'datepicker', 'daterange', 'email', 'hidden', 'hidden-name', 'multi-select', 'number', 'page-select', 'radio', 'search-post', 'search-product', 'search-product-cat', 'search-users', 'select', 'select-file', 'select-icon', 'switch', 'text', 'textarea', 'upload-image', 'url', 'value', 'wp-editor' ), true ) ) {
				*   return $field;
				*}
				*/

				if ( ! empty( $field['fields'] ) ) {
					$return_field = self::get_field( $field['fields'], $field_name );

				}
				if ( ! empty( $field['table-fields'] ) ) {
					$return_field = self::get_field( $field['table-fields'], $field_name );
				}

				if ( ! empty( $field['repeater_fields'] ) ) {
					$return_field = self::get_field( $field['repeater_fields'], $field_name );
				}
				if ( $return_field ) {
					return $return_field;
				}
			}
		}

		return false;
	}
}
