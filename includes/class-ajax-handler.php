<?php
/**
 * Compare AJAX Handler
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

namespace MoreConvert\McCompare;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ajax_Handler' ) ) {
	/**
	 * This class handles AJAX requests for the compare plugin
	 */
	class Ajax_Handler {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wp_ajax_moreconvert_compare_search_products', array( $this, 'search_products' ) );
			add_action( 'wp_ajax_nopriv_moreconvert_compare_search_products', array( $this, 'search_products' ) );
			add_action( 'wp_ajax_moreconvert_compare_get_suggested_products', array( $this, 'search_products' ) );
			add_action( 'wp_ajax_nopriv_moreconvert_compare_get_suggested_products', array( $this, 'search_products' ) );

			add_action( 'wp_ajax_moreconvert_compare_get_variations', array( $this, 'get_variations' ) );
			add_action( 'wp_ajax_nopriv_moreconvert_compare_get_variations', array( $this, 'get_variations' ) );
		}

		/**
		 * Handle AJAX request to search products
		 */
		public function search_products() {
			check_ajax_referer( 'moreconvert_compare_nonce', 'nonce' );
			$search_query    = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
			$base_product_id = isset( $_POST['base_product_id'] ) ? absint( $_POST['base_product_id'] ) : 0;
			$paged           = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
			$limit           = isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 10;

			$products = array();
			$total    = 0;
			if ( $base_product_id ) {
				$base_product = wc_get_product( $base_product_id );
				if ( $base_product && $base_product->exists() ) {
					$args = array(
						'limit'     => $limit,
						'page'      => $paged,
						'status'    => 'publish',
						'exclude'   => array( $base_product_id ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
						'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
							'relation' => 'OR',
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => wp_get_post_terms( $base_product_id, 'product_cat', array( 'fields' => 'ids' ) ),
							),
							array(
								'taxonomy' => 'product_tag',
								'field'    => 'term_id',
								'terms'    => wp_get_post_terms( $base_product_id, 'product_tag', array( 'fields' => 'ids' ) ),
							),
						),
						'paginate'  => true,
					);
					if ( $search_query ) {
						$args['s'] = $search_query;
					}
					$results = wc_get_products( $args );
					foreach ( $results->products as $product ) {
						$products[] = apply_filters( 'moreconvert_compare_product_data', array(), $product );
					}
					$total = $results->total;
				}
			}
			wp_send_json_success(
				array(
					'products' => $products,
					'total'    => $total,
				)
			);
		}

		/**
		 * Handle AJAX request to fetch variations for a product
		 */
		public function get_variations() {
			check_ajax_referer( 'moreconvert_compare_nonce', 'nonce' );
			$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
			if ( ! $product_id ) {
				wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			$wc_product = wc_get_product( $product_id );
			if ( ! $wc_product || 'variable' !== $wc_product->get_type() ) {
				wp_send_json_error( array( 'message' => __( 'Not a variable product', 'moreconvert-compare-for-woocommerce' ) ) );
			}

			$available_variations = $wc_product->get_available_variations();
			$variations           = array();
			foreach ( $available_variations as $var ) {
				$variation       = wc_get_product( $var['variation_id'] );
				$formatted_attrs = apply_filters( 'moreconvert_compare_product_variation_attributes', array(), $wc_product, $variation, $var['attributes'] );

				$variations[] = array(
					'variation_id'         => $var['variation_id'],
					'formatted_attributes' => $formatted_attrs,
					'is_purchasable'       => $variation->is_purchasable() && $variation->is_in_stock(),
					'image_thumbnail'      => $var['image_id'] ? wp_get_attachment_image_url( $var['image_id'], 'thumbnail' ) : wc_placeholder_img_src(),
					'add_to_cart_url'      => esc_url(
						add_query_arg(
							array(
								'add-to-cart'  => $product_id,
								'variation_id' => $var['variation_id'],
							) + $var['attributes'],
							get_permalink( $product_id )
						)
					),
					'price_html'           => wc_price( $var['display_price'] ),
					'attributes'           => $var['attributes'],  // Keep for matching.
				);
			}

			// Add variation attributes for separate selects.
			$variation_attributes = $wc_product->get_variation_attributes();
			// Add default attributes (prefixed).
			$default_attributes = $wc_product->get_default_attributes();
			$default_prefixed   = array();
			foreach ( $default_attributes as $key => $val ) {
				$default_prefixed[ 'attribute_' . sanitize_title( $key ) ] = $val;
			}

			wp_send_json_success(
				array(
					'variations'           => $variations,
					'variation_attributes' => $variation_attributes,
					'default_attributes'   => $default_prefixed,
					'html_variations'      => moreconvert_compare_get_template(
						'moreconvert-compare-variation-html.php',
						array(
							'product'              => $wc_product,
							'attributes'           => $variation_attributes,
							'available_variations' => $available_variations,
							'clear_text'           => __( 'Clear', 'moreconvert-compare-for-woocommerce' ),
							'outofstock_message'   => apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'moreconvert-compare-for-woocommerce' ) ), // phpcs:ignore  WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
						),
						true
					),
				)
			);
		}
	}

	new Ajax_Handler();
}
