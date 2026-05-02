<?php
/**
 * Compare Frontend
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

namespace MoreConvert\McCompare;

use WC_Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Frontend' ) ) {
	/**
	 * This class handles frontend for MoreConvert Compare for WooCommerce plugin
	 */
	class Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var Frontend
		 */
		protected static $instance;

		/**
		 * Options
		 *
		 * @var bool|MCTOptions\Options
		 */
		public $options = false;
		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			if ( ! $this->options ) {
				$this->options = new MCTOptions\Options( 'moreconvert_compare_options' );
			}
			if ( moreconvert_compare_is_true( $this->options->get_option( 'compare_enable', '1' ) ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'register_styles_and_scripts' ), 1000000 );
				add_action( 'init', array( $this, 'add_button' ) );
				add_action( 'wp_footer', array( $this, 'render_compare_popup' ), 1000 );
				add_filter( 'moreconvert_compare_fields_to_show', array( $this, 'fields_to_show' ) );
				add_filter( 'moreconvert_compare_product_attributes', array( $this, 'product_attributes' ), 10, 2 );
				add_filter( 'moreconvert_compare_product_variation_attributes', array( $this, 'product_variation_attributes' ), 10, 4 );
				add_filter( 'moreconvert_compare_product_data', array( $this, 'product_data' ), 10, 2 );

			}
		}

		/**
		 * Filter and Sort Attributes of Variable Product
		 *
		 * @param array      $attrs attributes.
		 * @param WC_Product $product Product object.
		 * @param WC_Product $variation Variation object.
		 * @param array      $variation_attributes available attributes.
		 *
		 * @return array
		 */
		public function product_variation_attributes( $attrs, $product, $variation, $variation_attributes ) {
			$fields_to_show = apply_filters( 'moreconvert_compare_fields_to_show', array() );
			$ordered_fields = apply_filters(
				'moreconvert_compare_attribute_order',
				array(
					'stock'             => 10,
					'reviews'           => 20,
					'weight'            => 30,
					'dimensions'        => 40,
					'sku'               => 50,
					'attributes_p'      => 60,
					'short_description' => 1000,
				)
			);
			$attributes     = array();
			foreach ( $ordered_fields as $field => $order ) {
				if ( ! in_array( $field, $fields_to_show, true ) ) {
					continue;
				}
				switch ( $field ) {
					case 'stock':
						$availability = $variation->get_availability();
						$value        = sprintf( '<span class="%s">%s</span>', esc_attr( $availability['class'] ), $availability['availability'] ? esc_html( $availability['availability'] ) : esc_html__( 'In stock', 'moreconvert-compare-for-woocommerce' ) );
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'stock_text', __( 'Availability', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'stock',
							'value' => $value,
						);
						break;
					case 'reviews':
						$value_format = $this->options->get_option( 'reviews_format', __( '{count} Reviews ({rating} stars)', 'moreconvert-compare-for-woocommerce' ) );
						$value_format = str_replace( array( '{count}', '{rating}' ), array( $product->get_review_count(), $product->get_average_rating() ), $value_format );
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'reviews_text', __( 'Reviews', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'reviews',
							'value' => $value_format,
						);
						break;
					case 'weight':
						$value = $variation->get_weight();
						$value = ! empty( $value ) ? wc_format_weight( $value ) : '';

						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'weight_text', __( 'Weight', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'weight',
							'value' => $value,
						);
						break;
					case 'dimensions':
						$value        = $variation->get_dimensions( false );
						$value        = ( ! empty( $value['length'] ) || ! empty( $value['width'] ) || ! empty( $value['height'] ) ) ? wc_format_dimensions( $value ) : '';
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'dimensions_text', __( 'Dimensions', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'dimensions',
							'value' => $value,
						);
						break;
					case 'sku':
						$value        = $variation->get_sku();
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'sku_text', __( 'SKU', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'sku',
							'value' => $value,
						);
						break;
					case 'short_description':
						$value        = $variation->get_description();
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'short_description_text', __( 'Description', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'short_description',
							'value' => '' === trim( $value ) ? $product->get_short_description() : $value,
						);
						break;
				}
			}
			$custom_order = $ordered_fields['attributes_p'] ?? 60;
			if ( in_array( 'attributes_p', $fields_to_show, true ) ) {
				foreach ( $product->get_attributes() as $attr_key => $attribute ) {
					if ( ! $attribute->get_visible() ) {
						continue;
					}
					$attr_name = wc_attribute_label( $attribute->get_name() );
					if ( $attribute->get_variation() ) {
						$term_slug = isset( $variation_attributes[ 'attribute_' . $attr_key ] ) ? $variation_attributes[ 'attribute_' . $attr_key ] : '';
						if ( $term_slug ) {
							if ( $attribute->is_taxonomy() ) {
								$term       = get_term_by( 'slug', $term_slug, $attr_key );
								$attr_value = $term ? $term->name : $term_slug;
							} else {
								$attr_value = $term_slug;
							}
						} else {
							$attr_value = '';
						}
					} else {
						$attr_value = implode( ', ', $attribute->get_terms() ? wp_list_pluck( $attribute->get_terms(), 'name' ) : $attribute->get_options() );
					}
					$attributes[] = array(
						'order' => $custom_order++,
						'label' => $attr_name,
						'key'   => $attr_key,
						'value' => $attr_value,
					);
				}
			}
			usort(
				$attributes,
				function ( $a, $b ) {
					return $a['order'] <=> $b['order'];
				}
			);
			if ( is_array( $attrs ) ) {
				$attributes = array_merge( $attrs, $attributes );
				usort(
					$attributes,
					function ( $a, $b ) {
						return $a['order'] <=> $b['order'];
					}
				);
			}
			return $attributes;
		}

		/**
		 * Filter and Sort Attributes of Product
		 *
		 * @param array      $attrs attributes.
		 * @param WC_Product $product Product object.
		 *
		 * @return array
		 */
		public function product_attributes( $attrs, $product ) {
			$fields_to_show = apply_filters( 'moreconvert_compare_fields_to_show', array() );
			$ordered_fields = apply_filters(
				'moreconvert_compare_attribute_order',
				array(
					'stock'             => 10,
					'reviews'           => 20,
					'weight'            => 30,
					'dimensions'        => 40,
					'sku'               => 50,
					'attributes_p'      => 60,
					'short_description' => 1000,
				)
			);
			$attributes     = array();
			foreach ( $ordered_fields as $field => $order ) {
				if ( ! in_array( $field, $fields_to_show, true ) ) {
					continue;
				}
				switch ( $field ) {
					case 'stock':
						$availability = $product->get_availability();
						$value        = sprintf( '<span class="%s">%s</span>', esc_attr( $availability['class'] ), $availability['availability'] ? esc_html( $availability['availability'] ) : esc_html__( 'In stock', 'moreconvert-compare-for-woocommerce' ) );
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'stock_text', __( 'Availability', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'stock',
							'value' => $value,
						);
						break;
					case 'reviews':
						$value_format = $this->options->get_option( 'reviews_format', __( '{count} Reviews ({rating} stars)', 'moreconvert-compare-for-woocommerce' ) );
						$value_format = str_replace( array( '{count}', '{rating}' ), array( $product->get_review_count(), $product->get_average_rating() ), $value_format );
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'reviews_text', __( 'Reviews', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'reviews',
							'value' => $value_format,
						);
						break;
					case 'weight':
						$value = $product->get_weight();
						$value = ! empty( $value ) ? wc_format_weight( $value ) : '';

						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'weight_text', __( 'Weight', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'weight',
							'value' => $value,
						);
						break;
					case 'dimensions':
						$value = $product->get_dimensions( false );
						$value = ( ! empty( $value['length'] ) || ! empty( $value['width'] ) || ! empty( $value['height'] ) ) ? wc_format_dimensions( $value ) : '';

						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'dimensions_text', __( 'Dimensions', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'dimensions',
							'value' => $value,
						);
						break;
					case 'sku':
						$value        = $product->get_sku();
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'sku_text', __( 'SKU', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'sku',
							'value' => $value,
						);
						break;
					case 'short_description':
						$value        = $product->get_short_description();
						$attributes[] = array(
							'order' => $order,
							'label' => $this->options->get_option( 'short_description_text', __( 'Description', 'moreconvert-compare-for-woocommerce' ) ),
							'key'   => 'short_description',
							'value' => $value,
						);
						break;
				}
			}
			$custom_order = $ordered_fields['attributes_p'] ?? 60;
			if ( in_array( 'attributes_p', $fields_to_show, true ) ) {
				foreach ( $product->get_attributes() as $attribute ) {
					if ( ! $attribute->get_visible() ) {
						continue;
					}
					$attr_name  = wc_attribute_label( $attribute->get_name() );
					$attr_value = implode( ', ', $attribute->get_terms() ? wp_list_pluck( $attribute->get_terms(), 'name' ) : array() );
					if ( $attr_value ) {
						$attributes[] = array(
							'order' => $custom_order++,
							'label' => $attr_name,
							'key'   => $attribute->get_name(),
							'value' => $attr_value,
						);
					}
				}
			}

			usort(
				$attributes,
				function ( $a, $b ) {
					return $a['order'] <=> $b['order'];
				}
			);
			if ( is_array( $attrs ) ) {
				$attributes = array_merge( $attrs, $attributes );
				usort(
					$attributes,
					function ( $a, $b ) {
						return $a['order'] <=> $b['order'];
					}
				);
			}
			return $attributes;
		}

		/**
		 * Product Data
		 *
		 * @param array      $data Product data.
		 * @param WC_Product $product Product object.
		 *
		 * @return array
		 */
		public function product_data( $data, $product ) {
			if ( 'variable' === $product->get_type() ) {
				$parent_attributes      = apply_filters( 'moreconvert_compare_product_attributes', array(), $product );
				$parent_price_html      = $product->get_price_html();
				$parent_image           = $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : wc_placeholder_img_src();
				$parent_add_to_cart_url = esc_url( $product->add_to_cart_url() );
				$data                   = array_merge(
					$data,
					array(
						'parent_attributes'      => $parent_attributes,
						'parent_price_html'      => $parent_price_html,
						'parent_image'           => $parent_image,
						'parent_add_to_cart_url' => $parent_add_to_cart_url,
						'parent_is_purchasable'  => false,
					)
				);
			}

			return wp_parse_args(
				$data,
				array(
					'id'              => $product->get_id(),
					'title'           => $product->get_name(),
					'image'           => $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : wc_placeholder_img_src(),
					'url'             => get_permalink( $product->get_id() ),
					'price'           => $product->get_price_html(),
					'add_to_cart_url' => esc_url( $product->add_to_cart_url() ),
					'is_purchasable'  => $product->is_purchasable() && $product->is_in_stock(),
					'attributes'      => apply_filters( 'moreconvert_compare_product_attributes', array(), $product ),
					'type'            => $product->get_type(),
					'has_variations'  => 'variable' === $product->get_type(),
				)
			);
		}

		/**
		 * Fields to Show in comparison table
		 *
		 * @return array
		 */
		public function fields_to_show() {
			return (array) $this->options->get_option(
				'fields_to_show',
				array(
					'image',
					'title',
					'price',
					'sku',
					'reviews',
					'stock',
					'attributes_p',
					'variations_p',
					'add_to_cart',
					'short_description',
				)
			);
		}

		/**
		 * Add the "Add to Compare" button. Needed to use in wp_head hook.
		 *
		 * @return void
		 */
		public function add_button() {
			$enabled_on_single = moreconvert_compare_is_true( $this->options->get_option( 'show_in_single', true ) );
			if ( $enabled_on_single ) {
				$positions = apply_filters(
					'moreconvert_compare_button_positions',
					array(
						'before_add_to_cart_button' => array(
							array(
								'hook'     => 'woocommerce_before_add_to_cart_button',
								'priority' => 90,
							),
						),
						'after_add_to_cart_button'  => array(
							array(
								'hook'     => 'woocommerce_after_add_to_cart_button',
								'priority' => 20,
							),
						),
					)
				);

				// Add the link "Add to compare".
				$position = $this->options->get_option( 'single_position', 'before_add_to_cart_button' );

				if ( 'shortcode' !== $position && isset( $positions[ $position ] ) ) {

					if ( isset( $positions[ $position ]['hook'] ) ) {
						add_action(
							$positions[ $position ]['hook'],
							array(
								$this,
								'print_button_single',
							),
							floatval( $positions[ $position ]['priority'] )
						);
					} elseif ( ! empty( $positions[ $position ] ) ) {
						foreach ( $positions[ $position ] as $hook ) {
							add_action(
								$hook['hook'],
								array(
									$this,
									'print_button_single',
								),
								floatval( $hook['priority'] )
							);
						}
					}
				}
			}

			// check if Add to compare button is enabled for loop.
			$enabled_on_loop = moreconvert_compare_is_true( $this->options->get_option( 'show_in_loop', true ) );

			if ( ! $enabled_on_loop ) {
				return;
			}

			$positions = apply_filters(
				'moreconvert_compare_loop_positions',
				array(
					'before_add_to_cart' => array(
						'hook'     => 'woocommerce_after_shop_loop_item',
						'priority' => 7,
					),
					'after_add_to_cart'  => array(
						'hook'     => 'woocommerce_after_shop_loop_item',
						'priority' => 15,
					),
				)
			);

			// Add the link "Add to compare".
			$position = $this->options->get_option( 'loop_position', 'before_add_to_cart' );

			if ( 'shortcode' !== $position && isset( $positions[ $position ] ) ) {
				if ( isset( $positions[ $position ]['hook'] ) ) {
					add_action(
						$positions[ $position ]['hook'],
						array(
							$this,
							'print_button_loop',
						),
						floatval( $positions[ $position ]['priority'] )
					);
				} elseif ( ! empty( $positions[ $position ] ) ) {
					foreach ( $positions[ $position ] as $hook ) {
						add_action(
							$hook['hook'],
							array(
								$this,
								'print_button_loop',
							),
							floatval( $hook['priority'] )
						);
					}
				}
			}
		}

		/**
		 * Render compare popup in footer
		 *
		 * @return void
		 */
		public function render_compare_popup() {
			moreconvert_compare_get_template( 'moreconvert-compare-popup.php', array( 'fields_to_show' => apply_filters( 'moreconvert_compare_fields_to_show', array() ) ), false );
		}

		/**
		 * Register scripts and styles required by the plugin
		 *
		 * @return void
		 */
		public function register_styles_and_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Enqueue styles.
			wp_register_style( 'moreconvert-compare', MORECONVERT_COMPARE_URL . 'assets/css/compare' . $suffix . '.css', array(), MORECONVERT_COMPARE_VERSION );

			wp_register_script( 'moreconvert-compare', MORECONVERT_COMPARE_URL . 'assets/js/compare' . $suffix . '.js', array( 'jquery', 'wp-util' ), MORECONVERT_COMPARE_VERSION, true );

			wp_localize_script(
				'moreconvert-compare',
				'McCompare',
				array(
					'ajax_url'     => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'   => wp_create_nonce( 'moreconvert_compare_nonce' ),
					'max_products' => apply_filters( 'moreconvert_compare_comparison_max_products', 4 ),
					'limit'        => apply_filters( 'moreconvert_compare_search_product_limit', 10 ),
					'texts'        => array(
						'add_to_compare'         => esc_js( $this->options->get_option( 'add_to_compare_text', __( 'Add to Compare', 'moreconvert-compare-for-woocommerce' ) ) ),
						'select_product'         => esc_js( $this->options->get_option( 'select_product_text', __( 'Select Product', 'moreconvert-compare-for-woocommerce' ) ) ),
						'search_products'        => esc_js( $this->options->get_option( 'search_product_text', __( 'Search products...', 'moreconvert-compare-for-woocommerce' ) ) ),
						'no_products'            => esc_js( $this->options->get_option( 'no_products_text', __( 'No products found.', 'moreconvert-compare-for-woocommerce' ) ) ),
						'product_count_template' => esc_js( $this->options->get_option( 'product_count_template_text', __( 'Showing {loaded} of {totalProducts} products', 'moreconvert-compare-for-woocommerce' ) ) ),
						'no_more_products'       => esc_js( $this->options->get_option( 'no_more_products_text', __( 'No more products', 'moreconvert-compare-for-woocommerce' ) ) ),
						'error'                  => esc_js( $this->options->get_option( 'error_text', __( 'An error occurred.', 'moreconvert-compare-for-woocommerce' ) ) ),
						'add_to_cart'            => esc_js( $this->options->get_option( 'add_to_cart_text', __( 'Add to Cart', 'moreconvert-compare-for-woocommerce' ) ) ),
						'specifications'         => esc_js( $this->options->get_option( 'specifications_text', __( 'Specifications', 'moreconvert-compare-for-woocommerce' ) ) ),
						'top_products'           => esc_js( $this->options->get_option( 'top_products_text', __( 'Top Products for Comparison', 'moreconvert-compare-for-woocommerce' ) ) ),
					),
				)
			);

			if ( 'external' === $this->options->get_option( 'css_print_method', 'internal' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_saved_css_file' ), 999999999 );
			} else {
				$custom_css = $this->build_custom_css();
				if ( $custom_css ) {
					wp_add_inline_style( 'moreconvert-compare', wp_strip_all_tags( $custom_css ) );
				}
			}
		}

		/**
		 * Print Add to compare button in the single product page
		 *
		 * @return void
		 */
		public function print_button_single() {
			echo do_shortcode( '[moreconvert_compare_button is_single="true"]' );
		}

		/**
		 * Print Add to compare button in the loop products
		 *
		 * @return void
		 */
		public function print_button_loop() {
			echo do_shortcode( '[moreconvert_compare_button is_single="false"]' );
		}

		/**
		 * Enqueue plugin scripts.
		 *
		 * @return void
		 */
		public static function enqueue_scripts() {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_style( 'moreconvert-compare' );
			wp_enqueue_script( 'wp-util' ); // Required for wp.template.
			wp_enqueue_script( 'moreconvert-compare' );
		}

		/**
		 * Generate CSS codes to append to each page, to apply custom style to compare elements
		 *
		 * @return string Generated CSS code
		 */
		public function build_custom_css() {
			$generated_code = '';
			$rules          = apply_filters(
				'moreconvert_compare_css_rules',
				array(
					'button_icon_size_single'        => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-single-position .moreconvert-compare-svg',
						'rules'    => array(
							'rule'    => 'width: %1$s !important;height: %1$s !important',
							'default' => $this->options->get_option( 'button_icon_size_single', '20px' ),
						),
					),
					'button_font_size_single'        => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-single-position',
						'rules'    => array(
							'rule'    => 'font-size: %s !important',
							'default' => $this->options->get_option( 'button_font_size_single', 'inherit' ),
						),
					),
					'button_color_single'            => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-single-position',
						'rules'    => array(
							'rule'    => 'color: %s !important',
							'default' => $this->options->get_option( 'button_color_single', '#515151' ),
						),
					),
					'button_background_color_single' => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-single-position',
						'rules'    => array(
							'rule'    => 'background-color: %s !important',
							'default' => $this->options->get_option( 'button_background_color_single', '#e9e6ed' ),
						),
					),
					'button_single_border'           => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-single-position',
						'rules'    => array(
							'rule'    => 'border: %s !important',
							'default' => $this->options->get_option( 'button_single_border', '1px solid transparent' ),
						),
					),

					'button_icon_size_loop'          => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-loop-position .moreconvert-compare-svg',
						'rules'    => array(
							'rule'    => 'width: %1$s !important;height: %1$s !important',
							'default' => $this->options->get_option( 'button_icon_size_loop', '20px' ),
						),
					),
					'button_font_size_loop'          => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-loop-position',
						'rules'    => array(
							'rule'    => 'font-size: %s !important',
							'default' => $this->options->get_option( 'button_font_size_loop', 'inherit' ),
						),
					),
					'button_color_loop'              => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-loop-position',
						'rules'    => array(
							'rule'    => 'color: %s !important',
							'default' => $this->options->get_option( 'button_color_loop', '#515151' ),
						),
					),
					'button_background_color_loop'   => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-loop-position',
						'rules'    => array(
							'rule'    => 'background-color: %s !important',
							'default' => $this->options->get_option( 'button_background_color_loop', '#e9e6ed' ),
						),
					),
					'button_loop_border'             => array(
						'selector' => '.moreconvert-compare-button.moreconvert-compare-loop-position',
						'rules'    => array(
							'rule'    => 'border: %s !important',
							'default' => $this->options->get_option( 'button_loop_border', '1px solid transparent' ),
						),
					),
				)
			);
			if ( ! empty( $rules ) ) {
				foreach ( $rules as $id => $rule ) {

					// retrieve values from db.
					$values     = $this->options->get_option( $id );
					$new_rules  = array();
					$rules_code = '';

					if ( isset( $rule['rules']['rule'] ) ) {
						// if we have a single-valued option, just search for the rule to apply.
						$status = $rule['rules']['status'] ?? '';

						$new_rules[ $status ]   = array();
						$new_rules[ $status ][] = $this->build_css_rule( $rule['rules']['rule'], $values, $rule['rules']['default'] );
					} elseif ( isset( $rule['type'] ) && 'repeater' === $rule['type'] ) {
						// if we have a repeater field cycle through rules, and generate CSS code.
						if ( is_array( $values ) && ! empty( $values ) ) {
							foreach ( $values as $k => $row ) {
								foreach ( $rule['rules'] as $property => $css ) {
									$status = $css['status'] ?? '';

									if ( ! isset( $new_rules[ $status ] ) ) {
										$new_rules[ $status ] = array();
									}

									$new_rules[ $k ][ $status ][] = $this->build_css_rule( $css['rule'], $row[ $property ] ?? false, $css['default'] );
								}
							}
						}
					} else {
						// otherwise, cycle through rules, and generate CSS code.
						foreach ( $rule['rules'] as $property => $css ) {
							$status = $css['status'] ?? '';

							if ( ! isset( $new_rules[ $status ] ) ) {
								$new_rules[ $status ] = array();
							}

							$new_rules[ $status ][] = $this->build_css_rule( $css['rule'], $values[ $property ] ?? false, $css['default'] );
						}
					}

					// if code was generated, prepend selector.
					if ( ! empty( $new_rules ) ) {
						if ( isset( $rule['type'] ) && 'repeater' === $rule['type'] ) {
							foreach ( $new_rules as $k => $row ) {

								$selector = sprintf( $rule['selector'], $k );
								foreach ( $row as $status => $rules ) {
									if ( ! empty( $status ) ) {
										$updated_selector = array();
										$split_selectors  = explode( ',', $selector );

										foreach ( $split_selectors as $split_selector ) {
											$updated_selector[] = $split_selector . $status;
										}

										$selector = implode( ',', $updated_selector );
									}

									$rules_code .= $selector . '{' . implode( '', $rules ) . '}';
								}
							}
						} else {
							foreach ( $new_rules as $status => $rules ) {
								$selector = $rule['selector'];

								if ( ! empty( $status ) ) {
									$updated_selector = array();
									$split_selectors  = explode( ',', $rule['selector'] );

									foreach ( $split_selectors as $split_selector ) {
										$updated_selector[] = $split_selector . $status;
									}

									$selector = implode( ',', $updated_selector );
								}

								$rules_code .= $selector . '{' . implode( '', $rules ) . '}';
							}
						}
					}

					// append new rule to generated CSS.
					$generated_code .= $rules_code;
				}
			}
			$generated_code  = apply_filters( 'moreconvert_compare_custom_css_output', $generated_code );
			$generated_code .= $this->options->get_option( 'custom_css', true );

			return wp_strip_all_tags( $generated_code );
		}

		/**
		 * Enqueue external css file
		 *
		 * @return void
		 * @since 1.7.7
		 */
		public function enqueue_saved_css_file() {

			if ( wp_style_is( 'moreconvert-compare', 'enqueued' ) ) {
				$upload_dir = wp_upload_dir();
				wp_enqueue_style( 'moreconvert-compare-inline-styles', $upload_dir['baseurl'] . '/more-convert/moreconvert-compare-inline.css', null, get_option( 'moreconvert_compare_css_version', 1 ) );
			}
		}

		/**
		 * Generate each single CSS rule that will be included in custom plugin CSS
		 *
		 * @param string $rule Rule to use; placeholders may be applied to be replaced with value {@see sprintf}.
		 * @param string $value Value to inject inside rule, replacing placeholders.
		 * @param string $default_value Default value, to be used instead of value when it is empty.
		 *
		 * @return string Formatted CSS rule
		 */
		protected function build_css_rule( $rule, $value, $default_value = '' ) {
			$value = ( '0' === $value || ( ! empty( $value ) && ! is_array( $value ) ) ) ? $value : $default_value;

			return sprintf( rtrim( $rule, ';' ) . ';', $value );
		}

		/**
		 * Returns single instance of the class
		 *
		 * @return Frontend
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
}
