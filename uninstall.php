<?php
/**
 * MoreConvert Compare for WooCommerce Uninstall
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;


/**
 * Uninstall MoreConvert Compare for WooCommerce
 *
 * @return void
 */
function moreconvert_compare_uninstall() {
	global $wpdb;

	/**
	 * Only remove ALL data if "Remove all data" is checked in plugin options.
	 * This is to prevent data loss when deleting the plugin from the backend
	 */
	if ( '1' === get_option( 'moreconvert_compare_remove_all_data' ) ) {

		// Delete options.
		$wpdb->query(  // @codingStandardsIgnoreLine.
			$wpdb->prepare(
				"DELETE FROM $wpdb->options 
         					WHERE option_name LIKE %s 
            				OR option_name LIKE %s",
				'moreconvert_compare_%',
				'moreconvert_appsero_moreconvert-compare%'
			)
		);
		// Clear any cached data that has been removed.
		wp_cache_flush();
	}
}

/**
 * Uninstall MoreConvert Compare for WooCommerce in the multisite
 *
 * @return void
 */
function moreconvert_compare_multisite_uninstall() {
	global $wpdb;
	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ); // @codingStandardsIgnoreLine.
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $b_id ) {
		switch_to_blog( $b_id );
		moreconvert_compare_uninstall();
	}

	switch_to_blog( $original_blog_id );
}

if ( ! is_multisite() ) {
	moreconvert_compare_uninstall();
} else {
	moreconvert_compare_multisite_uninstall();
}
