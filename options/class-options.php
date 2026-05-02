<?php
/**
 * Options Class
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.1.1
 */

namespace MoreConvert\McCompare\MCTOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Options' ) ) {
	/**
	 * Class Options
	 */
	class Options {


		/**
		 * Options
		 *
		 * @var mixed|void
		 */
		public $options;

		/**
		 * Option id
		 *
		 * @var string
		 */
		public $option_id;

		/**
		 * Constructor of the class
		 *
		 * @param string $option_id Option_id.
		 *
		 * @version 2.1.1
		 */
		public function __construct( string $option_id ) {

			$this->option_id = sanitize_key( $option_id );
			$this->options   = apply_filters( 'moreconvert_framework_get_option', get_option( $option_id, array() ), $option_id );
		}

		/**
		 * Get value of field
		 *
		 * @param string  $field field key.
		 * @param string  $default_value default value.
		 * @param boolean $set_empty_value_by_default set empty value with default value.
		 *
		 * @return string
		 * @version 2.4.2
		 */
		public function get_option( $field, $default_value = '', $set_empty_value_by_default = false ) {

			$value = $default_value;
			if ( is_array( $this->options ) && ! empty( $this->options ) ) {
				foreach ( $this->options as $section ) {
					if ( isset( $section[ $field ] ) ) {
						$value = $section[ $field ];
						break;
					}
				}
			}
			if ( '' === $value && $set_empty_value_by_default ) {
				$value = $default_value;
			}

			return $value;
		}

		/**
		 * Update value of field
		 *
		 * @param string       $field field key.
		 * @param mixed|string $value value.
		 * @param string       $section section.
		 *
		 * @return bool
		 * @version 2.4.5
		 * @since 1.1.0
		 */
		public function update_option( $field, $value, $section = '' ) {

			$new_option = $this->options;
			$found      = false;
			if ( is_array( $this->options ) && ! empty( $this->options ) ) {
				if ( '' !== $section ) {
					$new_option[ $section ][ $field ] = $value;
					$found                            = true;
				} else {
					foreach ( $this->options as $k => $section ) {

						if ( isset( $section[ $field ] ) ) {
							$new_option[ $k ][ $field ] = $value;
							$found                      = true;
						}
					}
					// if not have any section. TODO:test it.
					if ( false === $found ) {
						foreach ( $this->options as $field_index => $field_key ) {
							if ( $field_key === $field ) {
								$new_option[ $field_index ][ $field ] = $value;
								$found                                = true;
							}
						}
					}
				}
			}

			if ( true === $found && apply_filters( 'moreconvert_framework_options_can_update', true, $this->option_id ) ) {
				$this->options = $new_option;
				update_option( $this->option_id, $new_option );
			}

			return true;
		}

		/**
		 * Move option between sections
		 *
		 * @param string $from_section section key.
		 * @param string $to_section section ke.
		 * @param array  $options option_keys.
		 *
		 * @since 2.4.6
		 */
		public function move_options( $from_section, $to_section, $options ) {

			$new_option = $this->options;
			$found      = false;
			if ( is_array( $this->options ) && ! empty( $this->options ) ) {
				foreach ( $options as $option ) {
					if ( isset( $new_option[ $from_section ][ $option ] ) ) {
						if ( ! isset( $new_option[ $to_section ][ $option ] ) ) {
							$new_option[ $to_section ][ $option ] = $new_option[ $from_section ][ $option ];
						}
						unset( $new_option[ $from_section ][ $option ] );
						$found = true;
					}
				}
			}

			if ( true === $found && apply_filters( 'moreconvert_framework_options_can_update', true, $this->option_id ) ) {
				$this->options = $new_option;
				update_option( $this->option_id, $new_option );
			}

			return true;
		}

		/**
		 * Replace options
		 *
		 * @param array $new_options new options.
		 *
		 * @return void
		 */
		public function replace_options( $new_options ) {
			$this->options = $new_options;
			update_option( $this->option_id, $new_options );
		}
	}
}
