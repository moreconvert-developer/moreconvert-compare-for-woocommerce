<?php
/**
 * Compare Shortcodes Class
 *
 * Handles all shortcode functionality for the compare system.
 *
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

namespace MoreConvert\McCompare;

use WC_Product;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Shortcodes' ) ) {
	/**
	 * Class Shortcodes
	 *
	 * Handles shortcode operations for displaying compare pages, buttons, and counters.
	 */
	class Shortcodes {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_shortcode( 'moreconvert_compare_button', array( $this, 'compare_button_shortcode' ) );
		}


		/**
		 * Compare button shortcode
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		public function compare_button_shortcode( $atts ) {
			global $post, $product;
			$atts = shortcode_atts(
				array(
					'product_id' => 0,
					'class'      => 'moreconvert-compare-button button',
					'is_single'  => false,
				),
				$atts,
				'moreconvert_compare_button'
			);

			// product object.
			$wc_product = ( isset( $atts['product_id'] ) && '' !== trim( $atts['product_id'] ) ) ? wc_get_product( $atts['product_id'] ) : false;
			$wc_product = $wc_product ? $wc_product : ( $product instanceof WC_Product ? $product : false );
			$wc_product = $wc_product ? $wc_product : ( $post instanceof WP_Post ? wc_get_product( $post->ID ) : false );

			if ( ! $wc_product instanceof WC_Product ) {
				return '';
			}

			$product_id = $wc_product->get_id();

			$options = new MCTOptions\Options( 'moreconvert_compare_options' );
			if ( ! moreconvert_compare_is_true( $options->get_option( 'compare_enable', '1' ) ) ) {
				return '';
			}

			$icon         = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13 3.99976H6C4.89543 3.99976 4 4.89519 4 5.99976V17.9998C4 19.1043 4.89543 19.9998 6 19.9998H13M17 3.99976H18C19.1046 3.99976 20 4.89519 20 5.99976V6.99976M20 16.9998V17.9998C20 19.1043 19.1046 19.9998 18 19.9998H17M20 10.9998V12.9998M12 1.99976V21.9998" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';
			$is_single    = isset( $atts['is_single'] ) && moreconvert_compare_is_true( $atts['is_single'] );
			$button_class = $is_single ? $atts['class'] . ' moreconvert-compare-single-position' : $atts['class'] . ' moreconvert-compare-loop-position';

			// enqueue scripts.
			Frontend::enqueue_scripts();

			return moreconvert_compare_get_template(
				'moreconvert-compare-button.php',
				array(
					'product_id'   => $product_id,
					'button_class' => $button_class,
					'icon'         => $icon,
					'is_svg_icon'  => true,
					'button_text'  => $options->get_option( 'add_to_compare_text', __( 'Compare', 'moreconvert-compare-for-woocommerce' ) ),
					'product_data' => apply_filters( 'moreconvert_compare_product_data', array(), $wc_product ),
				),
				true
			);
		}
	}

	new Shortcodes();
}
