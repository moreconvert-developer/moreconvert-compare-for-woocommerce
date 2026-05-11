<?php
/**
 * Plugin Options.
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.7
 */

namespace MoreConvert\McCompare\MCTOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Define framework version.
const VERSION = '1.0.0';
// Define base path and URL for the framework within the plugin.
if ( ! defined( 'MORECONVERT_COMPARE_OPTIONS_PATH' ) ) {
	define( 'MORECONVERT_COMPARE_OPTIONS_PATH', __DIR__ );
}

if ( ! defined( 'MORECONVERT_COMPARE_OPTIONS_URL' ) ) {
	define( 'MORECONVERT_COMPARE_OPTIONS_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
}

// Autoload framework classes.
spl_autoload_register(
	function ( $class_name ) {
		$prefix   = 'MoreConvert\\McCompare\\MCTOptions\\';
		$base_dir = MORECONVERT_COMPARE_OPTIONS_PATH . '/';
		if ( strpos( $class_name, $prefix ) === 0 ) {
			$relative_class = substr( $class_name, strlen( $prefix ) );
			$file_name      = 'class-' . str_replace( array( '\\', '_' ), array( '/', '-' ), strtolower( $relative_class ) ) . '.php';
			$file           = $base_dir . $file_name;
			if ( file_exists( $file ) ) {
				require $file;
			}
		}
	}
);

/**
 * Initialize the framework
 *
 * @param array $config Plugin configuration.
 */
function init( $config ) {
	// Merge with defaults.
	$defaults = array(
		'plugin_id'     => '',
		'assets_url'    => MORECONVERT_COMPARE_OPTIONS_URL . '/assets',
		'template_path' => MORECONVERT_COMPARE_OPTIONS_PATH,
	);

	$config = wp_parse_args( $config, $defaults );

	// Store configuration in Config singleton.
	$config_manager = Config::get_instance();
	$config_manager->set_config( $config );

	// Initialize framework components.
	new Ajax_Handler();

	return true;
}
