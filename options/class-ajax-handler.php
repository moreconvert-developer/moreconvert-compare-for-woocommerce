<?php
/**
 * Static class that will handle all ajax calls for the list
 *
 * @author MoreConvert
 * @package MC Messages
 * @version 1.3.3
 */

namespace MoreConvert\McCompare\MCTOptions;

use WP_Query;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Ajax_Handler' ) ) {
	/**
	 * Ajax Handler
	 */
	class Ajax_Handler {
		/**
		 * Plugin configs
		 *
		 * @var array configs.
		 */
		private $config;

		/**
		 * Constructor.
		 */
		public function __construct() {
			// Get configuration from singleton.
			$this->config = Config::get_instance()->get_config();

			// Register AJAX actions.
			add_action( 'wp_ajax_' . $this->config['plugin_id'] . '_import_settings', array( $this, 'import_settings' ) );
			add_action( 'wp_ajax_' . $this->config['plugin_id'] . '_export_settings', array( $this, 'export_settings' ) );
			add_action( 'wp_ajax_' . $this->config['plugin_id'] . '_ajax_saving', array( $this, 'ajax_saving' ) );

			// Register route.
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );

			add_filter( 'upload_mimes', array( $this, 'add_json_mimetype' ) );
		}

		/**
		 * Import settings
		 *
		 * @return void
		 */
		public function import_settings() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions to access this page.', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			check_ajax_referer( 'ajax-nonce', 'key' );
			$option_id     = isset( $_POST['option_id'] ) ? sanitize_text_field( wp_unslash( $_POST['option_id'] ) ) : false;
			$attachment_id = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : false;

			if ( ! $option_id || ! $attachment_id ) {
				wp_send_json_error();
			}
			$options = new Options( $option_id );

			$file_path    = get_attached_file( $attachment_id );
			$file_content = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions

			$json = json_decode( $file_content, true );
			if ( ! is_array( $json ) || empty( $json ) ) {
				// Handle error if the content is not a valid JSON object.
				wp_send_json_error( array( 'message' => __( 'Invalid JSON content', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			if ( ! isset( $json['options'] ) || ! isset( $json['option_id'] ) || $option_id !== $json['option_id'] ) {
				wp_send_json_error( array( 'message' => __( 'Wrong JSON content', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			$config             = Config::get_instance();
			$all_options_config = $config->get( $option_id );
			if ( ! $all_options_config || ! isset( $all_options_config['options'] ) ) {
				wp_send_json_error( array( 'message' => __( 'Configuration not found for this option ID.', 'moreconvert-compare-for-woocommerce' ) ) );
			}
			$imported_raw  = $json['options'];
			$saved_options = array();
			if ( ! empty( $imported_raw ) ) {
				foreach ( $imported_raw  as $section => $items ) {
					if ( ! empty( $items ) ) {
						foreach ( $items as $field_name ) {

							$raw_value = isset( $data[ $field_name ] ) ? wp_unslash( $data[ $field_name ] ) : '';
							$field_def = Helpers::get_field_definition( $all_options_config['options'], sanitize_key( $section ), sanitize_key( $field_name ) );

							$field_type = $field_def['type'] ?? 'text';

							$saved_options[ sanitize_key( $section ) ][ sanitize_key( $field_name ) ] = Sanitizer::sanitize_field( $field_type, $raw_value, $field_def );

						}
					}
				}
			}
			$options->replace_options( $saved_options );

			wp_send_json_success( array( 'message' => __( 'Successfully Imported.', 'moreconvert-compare-for-woocommerce' ) ) );
		}

		/**
		 * Export settings
		 *
		 * @return void
		 */
		public function export_settings() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions to access this page.', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			check_ajax_referer( 'ajax-nonce', 'key' );
			$option_id = isset( $_POST['option_id'] ) ? sanitize_text_field( wp_unslash( $_POST['option_id'] ) ) : false;
			if ( ! $option_id ) {
				wp_send_json_error();
			}
			$options = new Options( $option_id );

			$name = get_bloginfo( 'name' );

			// WordPress can have a blank site title, which will cause initial client creation to fail.
			if ( empty( $name ) ) {
				$name = wp_parse_url( home_url(), PHP_URL_HOST );
				$port = wp_parse_url( home_url(), PHP_URL_PORT );
				if ( $port ) {
					$name .= ':' . $port;
				}
			}

			$name = preg_replace( '/[^A-Za-z0-9 ]/', '', $name ?? '' );
			$name = preg_replace( '/\s+/', ' ', $name ?? '' );
			$name = str_replace( ' ', '-', $name );

			wp_send_json_success(
				array(
					'message'     => __( 'Successfully Exported.', 'moreconvert-compare-for-woocommerce' ),
					'filecontent' => wp_json_encode( $options ),
					'filename'    => "$name-$option_id.json",
				)
			);
		}

		/**
		 * Ajax saving settings
		 *
		 * @return void
		 */
		public function ajax_saving() {
			check_ajax_referer( 'ajax-nonce', '_wpnonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions to access this page.', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			if ( ! isset( $_POST['data'] ) ) {
				wp_send_json_error( array( 'message' => __( 'No data was received.', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			parse_str( wp_unslash( urldecode( $_POST['data'] ) ), $data );  // phpcs:ignore WordPress.Security

			$option_id = isset( $data['mct-option_id'] ) ? sanitize_text_field( wp_unslash( $data['mct-option_id'] ) ) : false;
			if ( ! $option_id ) {
				wp_send_json_error( array( 'message' => __( 'Missing option ID.', 'moreconvert-compare-for-woocommerce' ) ) );
			}
			$saved_options = apply_filters( 'moreconvert_framework_get_option', get_option( $option_id, array() ), $option_id );
			$options       = isset( $data['mct-form-options'] ) ? json_decode( wp_unslash( $data['mct-form-options'] ), true ) : array(); // phpcs:ignore WordPress.Security
			if ( ! is_array( $options ) || empty( $options ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid JSON structure', 'moreconvert-compare-for-woocommerce' ) ) );
			}
			$config             = Config::get_instance();
			$all_options_config = $config->get( $option_id );

			if ( ! $all_options_config || ! isset( $all_options_config['options'] ) ) {
				wp_send_json_error( array( 'message' => __( 'Configuration not found.', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			if ( ! empty( $options ) ) {
				foreach ( $options as $section => $items ) {
					if ( ! empty( $items ) ) {
						foreach ( $items as $field_name ) {

							$raw_value = isset( $data[ $field_name ] ) ? wp_unslash( $data[ $field_name ] ) : '';
							$field_def = Helpers::get_field_definition( $all_options_config['options'], sanitize_key( $section ), sanitize_key( $field_name ) );

							$field_type = $field_def['type'] ?? 'text';

							$saved_options[ sanitize_key( $section ) ][ sanitize_key( $field_name ) ] = Sanitizer::sanitize_field( $field_type, $raw_value, $field_def );

						}
					}
				}
			}
			$validate = apply_filters( 'moreconvert_framework_ajax_validate', true, $option_id, $saved_options );

			if ( true === $validate ) {

				if ( apply_filters( 'moreconvert_framework_options_can_update', true, $option_id, $saved_options ) ) {
					update_option( $option_id, $saved_options );
					do_action( 'moreconvert_framework_panel_after_' . $option_id . '_ajax_update', $saved_options );
					wp_send_json_success(
						array(
							'message' => __( 'Settings Saved!', 'moreconvert-compare-for-woocommerce' ) . '<small>' . __( 'Don\'t forget to clear your website and browser cache to see the changes.', 'moreconvert-compare-for-woocommerce' ) . '</small>',
						)
					);
				} else {
					wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions to access this page.', 'moreconvert-compare-for-woocommerce' ) ) );
				}
			} else {
				wp_send_json_error( array( 'message' => __( 'Failed validate form.', 'moreconvert-compare-for-woocommerce' ) ) );
			}
		}

		/**
		 * Add search post routes
		 *
		 * @return void
		 */
		public function register_routes() {
			register_rest_route(
				'mct-options/v1',
				'/search-posts',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'search_posts' ),
					'permission_callback' => array( $this, 'get_rest_permission' ),
				)
			);
			register_rest_route(
				'mct-options/v1',
				'/search-users',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'search_users' ),
					'permission_callback' => array( $this, 'get_rest_permission' ),
				)
			);
		}

		/**
		 * Rest Permission
		 *
		 * @return bool
		 */
		public static function get_rest_permission() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}
			return true;
		}

		/**
		 * Search posts
		 *
		 * @param WP_REST_Request $request  The request object.
		 *
		 * @return array
		 */
		public static function search_posts( $request ) {
			$search_term = isset( $request['search_term'] ) ? sanitize_text_field( $request['search_term'] ) : '';

			$args = array(
				'post_type'      => 'any',
				'posts_per_page' => -1,
				's'              => $search_term,
			);

			$query   = new WP_Query( $args );
			$results = array();

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$results[] = array(
						'id'   => get_the_ID(),
						'text' => get_the_title(),
					);
				}
			}

			wp_reset_postdata();

			return $results;
		}

		/**
		 * Search users
		 *
		 * @param WP_REST_Request $request  The request object.
		 *
		 * @return array
		 */
		public static function search_users( $request ) {
			$search_term = isset( $request['search_term'] ) ? sanitize_text_field( $request['search_term'] ) : '';

			$users = get_users(
				array(
					'search' => esc_sql( "*{$search_term}*" ),
					'fields' => array( 'ID', 'user_login', 'user_email' ),
					'number' => 20,
				)
			);

			$results = array();
			foreach ( $users as $user ) {
				$results[] = array(
					'id'   => $user->ID,
					'text' => $user->user_login . ' (' . $user->user_email . ')',
				);
			}

			return $results;
		}

		/**
		 * Add json mimetype
		 *
		 * @param array $mimes mimetypes.
		 *
		 * @return array
		 */
		public static function add_json_mimetype( $mimes ) {
			// Add support for JSON files.
			$mimes['json'] = 'application/json';

			return $mimes;
		}
	}
}
