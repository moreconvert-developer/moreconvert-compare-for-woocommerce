<?php
/**
 * Main Class Compare
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

namespace MoreConvert\McCompare;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Load_Compare' ) ) {
	/**
	 * WooCommerce MC Compare
	 */
	class Load_Compare {
		/**
		 * Single instance of the class
		 *
		 * @var Load_Compare
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * Plugin database version
		 *
		 * @var string
		 */
		public $db_version = '1.0.0';

		/**
		 * Returns single instance of the class
		 *
		 * @return Load_Compare
		 */
		public static function get_instance(): Load_Compare {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			define( 'MORECONVERT_COMPARE_VERSION', $this->version );

			// Init frontend.
			Frontend::get_instance();

			// Init admin handling.
			if ( is_admin() ) {
				Admin::get_instance();
			}

			// Register the data store with WooCommerce.
			add_filter( 'moreconvert_compare_locate_template', array( $this, 'locate_template' ), 10, 2 );
		}

		/**
		 * Locate the templates and return the path of the file found
		 *
		 * @param string $located Located template path.
		 * @param string $path Path to locate.
		 *
		 * @return string
		 */
		public function locate_template( $located, $path ) {
			$compare_plugin_path = MORECONVERT_COMPARE_DIR . 'templates/' . $path;
			if ( ! $located && file_exists( $compare_plugin_path ) ) {
				return $compare_plugin_path;
			}
			return $located;
		}
	}
}
