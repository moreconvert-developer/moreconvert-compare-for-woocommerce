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
if ( ! defined( __NAMESPACE__ . '\PATH' ) ) {
	define( __NAMESPACE__ . '\PATH', __DIR__ );
}

if ( ! defined( __NAMESPACE__ . '\URL' ) ) {
	define( __NAMESPACE__ . '\URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
}

// Autoload framework classes.
spl_autoload_register(
	function ( $class_name ) {
		$prefix   = 'MoreConvert\\McCompare\\MCTOptions\\';
		$base_dir = PATH . '/';
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
		'assets_url'    => URL . '/assets',
		'template_path' => PATH,
	);

	$config = wp_parse_args( $config, $defaults );

	// Store configuration in Config singleton.
	$config_manager = Config::get_instance();
	$config_manager->set_config( $config );

	// Initialize framework components.
	new Ajax_Handler();

	if ( class_exists( __NAMESPACE__ . '\\Demo' ) && defined( 'MORECONVERT_MCTOPTIONS_DEMO' ) && true === MORECONVERT_MCTOPTIONS_DEMO ) {
		Demo::get_instance();
	}
	return true;
}
