<?php
/**
 * Plugin Name: MoreConvert Compare for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/moreconvert-compare-for-woocommerce
 * Description:A Powerful WooCommerce plugin for product comparison. Features customizable table, variable product support, GDPR compliance and optimized performance.
 * Version: 1.0.0
 * Author: MoreConvert
 * Author URI: https://moreconvert.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: moreconvert-compare-for-woocommerce
 * Domain Path: /languages/
 * Requires PHP: 8.0
 * Requires Plugins: woocommerce
 * WC requires at least: 6.3
 * WC tested up to: 10.7.0
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

/**
 * Copyright 2025 MoreConvert Solutions (email: info@moreconvert.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, version 2 or later, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */


namespace MoreConvert\McCompare;

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MORECONVERT_COMPARE_URL' ) ) {
	define( 'MORECONVERT_COMPARE_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'MORECONVERT_COMPARE_MAIN_FILE' ) ) {
	define( 'MORECONVERT_COMPARE_MAIN_FILE', __FILE__ );
}

if ( ! defined( 'MORECONVERT_COMPARE_DIR' ) ) {
	define( 'MORECONVERT_COMPARE_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'MORECONVERT_COMPARE_INC' ) ) {
	define( 'MORECONVERT_COMPARE_INC', MORECONVERT_COMPARE_DIR . 'includes/' );
}


if ( ! class_exists( 'enshrined\svgSanitize\Sanitizer' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\install', 12 );
add_action( 'moreconvert_compare_for_woocommerce_init', __NAMESPACE__ . '\\load', 0 );


/**
 * Plugin install
 *
 * @return void
 */
function install() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( function_exists( 'WC' ) ) {
		require_once MORECONVERT_COMPARE_INC . 'functions.php';
		do_action( 'moreconvert_compare_for_woocommerce_init' );
	}
}

/**
 * Load plugin components
 *
 * @return void
 */
function load() {
	// Include framework initializer.
	require_once MORECONVERT_COMPARE_DIR . 'options/init.php';

	// Initialize framework.
	MCTOptions\init(
		array(
			'plugin_id'     => 'moreconvert_compare_for_woocommerce',
			'assets_url'    => MORECONVERT_COMPARE_URL . 'options/assets',
			'template_path' => MORECONVERT_COMPARE_DIR . 'options',
		)
	);

	require_once MORECONVERT_COMPARE_INC . 'class-frontend.php';
	require_once MORECONVERT_COMPARE_INC . 'class-ajax-handler.php';
	require_once MORECONVERT_COMPARE_INC . 'class-shortcodes.php';
	require_once MORECONVERT_COMPARE_INC . 'class-load-compare.php';

	if ( is_admin() ) {
		require_once MORECONVERT_COMPARE_INC . 'class-admin.php';
	}
	// Initialize the compare functionality.
	Load_Compare::get_instance();
}


add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
		}
	}
);


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker() {

	if ( ! class_exists( 'MoreConvert\McCompare\Appsero\Client' ) ) {
		require_once MORECONVERT_COMPARE_DIR . 'lib/appsero/class-client.php';
	}

	$client = new Appsero\Client( '15538f29-1d1d-4aba-acf6-e3fd3c97a55b', 'MoreConvert Compare for WooCommerce Plugin', __FILE__ );

	// Active insights.
	$client->insights()->add_plugin_data()->init();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\appsero_init_tracker', 20 );
