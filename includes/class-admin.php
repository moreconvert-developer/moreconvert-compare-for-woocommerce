<?php
/**
 * Compare Admin
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 */

namespace MoreConvert\McCompare;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Admin' ) ) {
	/**
	 * Compare Admin
	 */
	class Admin {

		/**
		 * Single instance of the class
		 *
		 * @var Admin
		 */
		protected static $instance;

		/**
		 * Compare options
		 *
		 * @var array
		 */
		public $compare_options;

		/**
		 * Text options
		 *
		 * @var array
		 */
		public $text_options;

		/**
		 * Main panel
		 *
		 * @var MCTOptions\Admin
		 */
		public $main_panel;

		/**
		 * Text panel
		 *
		 * @var MCTOptions\Admin
		 */
		public $text_panel;

		/**
		 * Installed state
		 *
		 * @var bool
		 */
		public $installed;

		/**
		 * Header menu
		 *
		 * @var array
		 */
		public $header_menu;

		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 14 );
			add_action( 'init', array( $this, 'init_options' ) );
			add_action(
				'moreconvert_framework_panel_after_moreconvert_compare_options_ajax_update',
				array(
					$this,
					'after_ajax_update_options',
				)
			);
			add_action(
				'moreconvert_framework_panel_after_moreconvert_compare_options_update',
				array(
					$this,
					'after_update_options',
				)
			);
		}

		/**
		 * Initialize options
		 *
		 * @return void
		 */
		public function init_options() {
			$this->header_menu = apply_filters(
				'moreconvert_compare_admin_header_menu',
				array(
					array(
						'id'   => 'settings',
						'text' => __( 'Settings', 'moreconvert-compare-for-woocommerce' ),
						'url'  => add_query_arg(
							array( 'page' => 'moreconvert-compare' ),
							admin_url( 'admin.php' )
						),
					),
					array(
						'id'   => 'text',
						'text' => __( 'Text', 'moreconvert-compare-for-woocommerce' ),
						'url'  => add_query_arg(
							array( 'page' => 'moreconvert-compare-text-compare' ),
							admin_url( 'admin.php' )
						),
					),
				)
			);

			$this->compare_options = array(
				'options'        => apply_filters(
					'moreconvert_compare_admin_options',
					array(
						'compare' => array(
							'tabs'   => array(
								'general' => __( 'General', 'moreconvert-compare-for-woocommerce' ),
								'button'  => __( 'Button', 'moreconvert-compare-for-woocommerce' ),
								'table'   => __( 'Comparison Table', 'moreconvert-compare-for-woocommerce' ),
							),
							'fields' => array(
								'general' => apply_filters(
									'moreconvert_compare_general_settings',
									array(
										'start-article-display-settings' => array(
											'type'  => 'start',
											'title' => __( 'Display Settings', 'moreconvert-compare-for-woocommerce' ),
											'desc'  => __( 'You only need to set them once after installing the plugin.', 'moreconvert-compare-for-woocommerce' ),
										),
										'compare_enable'   => array(
											'label'   => __( 'Enable Compare', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'switch',
											'default' => '1',
										),
										'end-article-display-settings' => array(
											'type' => 'end',
										),
										'start-article-compare-advanced-settings' => array(
											'type'  => 'start',
											'title' => __( 'Advanced settings', 'moreconvert-compare-for-woocommerce' ),
											'desc'  => __( 'These settings are not necessary and you can use them if you want.', 'moreconvert-compare-for-woocommerce' ),
										),
										'custom_css'       => array(
											'label'     => __( 'Custom CSS', 'moreconvert-compare-for-woocommerce' ),
											'help'      => __( 'This feature allows you to add your own custom CSS code to modify the appearance of your website. Use this feature if you want to make specific design changes that cannot be done through the plugin\'s existing styling options.', 'moreconvert-compare-for-woocommerce' ),
											'type'      => 'code-editor',
											'code_type' => 'css',
											'editor_height' => '400px',
										),
										'css_print_method' => array(
											'type'    => 'select',
											'default' => 'internal',
											'label'   => esc_html__( 'CSS Print Method', 'moreconvert-compare-for-woocommerce' ),
											'options' => array(
												'external' => esc_html__( 'External File', 'moreconvert-compare-for-woocommerce' ),
												'internal' => esc_html__( 'Internal Embedding', 'moreconvert-compare-for-woocommerce' ),
											),
											'desc'    => __( 'For better performance and organization, it is recommended to use an external file for CSS rather than internal embedding.', 'moreconvert-compare-for-woocommerce' ),
										),
										'remove_all_data'  => array(
											'label' => __( 'Remove all data', 'moreconvert-compare-for-woocommerce' ),
											'desc'  => __( 'Uncheck , if you want to prevent data loss when deleting the plugin', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'checkbox',
										),
										'end-article-compare-advanced-settings' => array(
											'type' => 'end',
										),
									)
								),
								'button'  => apply_filters(
									'moreconvert_compare_button_settings',
									array(
										'start-article-button-settings' => array(
											'type'  => 'start',
											'title' => __( '"Add to Compare" Buttons Style', 'moreconvert-compare-for-woocommerce' ),
										),
										'button_single_style' => array(
											'section' => 'compare',
											'label'   => __( 'Button Single style', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'group-fields',
											'fields'  => array(
												'button_single_border' => array(
													'label' => __( 'Border Css', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'text',
													'default' => 'none',
													'custom_attributes' => array(
														'style' => 'width:100px',
													),
												),
												'button_icon_size_single'   => array(
													'label' => __( 'Icon size', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'text',
													'default' => '20px',
													'custom_attributes' => array(
														'style' => 'width:80px',
													),
												),
												'button_font_size_single'   => array(
													'label' => __( 'Font size', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'text',
													'default' => 'inherit',
													'custom_attributes' => array(
														'style' => 'width:80px',
													),
												),
												'button_color_single'       => array(
													'label' => __( 'Color', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'color',
													'default' => '#515151',
												),
												'button_background_color_single'       => array(
													'label' => __( 'Background color', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'color',
													'default' => '#e9e6ed',
												),
											),
											'desc'    => __( 'e.g. 10px, 15rem, 13em etc.', 'moreconvert-compare-for-woocommerce' ),
										),
										'button_loop_style' => array(
											'section' => 'compare',
											'label'   => __( 'Button Loop style', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'group-fields',
											'fields'  => array(
												'button_loop_border' => array(
													'label' => __( 'Border Css', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'text',
													'default' => 'none',
													'custom_attributes' => array(
														'style' => 'width:100px',
													),
												),
												'button_icon_size_loop'   => array(
													'label' => __( 'Icon size', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'text',
													'default' => '20px',
													'custom_attributes' => array(
														'style' => 'width:80px',
													),
												),
												'button_font_size_loop'   => array(
													'label' => __( 'Font size', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'text',
													'default' => 'inherit',
													'custom_attributes' => array(
														'style' => 'width:80px',
													),
												),
												'button_color_loop'       => array(
													'label' => __( 'Color', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'color',
													'default' => '#515151',
												),
												'button_background_color_loop'       => array(
													'label' => __( 'Background color', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'color',
													'default' => '#e9e6ed',
												),
											),
											'desc'    => __( 'e.g. 10px, 15rem, 13em etc.', 'moreconvert-compare-for-woocommerce' ),
										),

										'end-article-button-settings' => array(
											'type' => 'end',
										),

										// Products single.
										'start-article-single' => array(
											'type'         => 'start',
											/* translators: 1: single/loop product page text */
											'title'        => sprintf( __( '"Add to Compare" Button %s', 'moreconvert-compare-for-woocommerce' ), '<span style="color:#fd5d00">' . __( 'on Single Product Page', 'moreconvert-compare-for-woocommerce' ) . '</span>' ),
											'desc'         => __( 'Design your compare button on your product page.', 'moreconvert-compare-for-woocommerce' ),
											'class'        => 'mct-accordion',
											'dependencies' => array(
												'id'    => 'compare_enable',
												'value' => '1',
											),
										),
										'show_in_single'   => array(
											'label'   => __( 'Show in Single Product', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'switch',
											'default' => '1',
											'desc'    => __( 'Show compare button on single product pages.', 'moreconvert-compare-for-woocommerce' ),
										),
										'single_position'  => array(
											'label'   => __( 'Button position', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'select',
											'class'   => 'select2-trigger',
											'options' => apply_filters(
												'moreconvert_compare_single_position_options',
												array(
													'before_add_to_cart_button' => __( 'Before "add to cart" button', 'moreconvert-compare-for-woocommerce' ),
													'after_add_to_cart_button' => __( 'After "add to cart" button', 'moreconvert-compare-for-woocommerce' ),
													'shortcode' => __( 'Use shortcode', 'moreconvert-compare-for-woocommerce' ),
												)
											),
											'help'    => __( 'button position on the product page', 'moreconvert-compare-for-woocommerce' ),
											'default' => 'before_add_to_cart_button',
										),
										'shortcode_button' => array(
											'label'        => __( 'Shortcode button', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'copy-text',
											'default'      => '[moreconvert_compare_button is_single="true"]',
											'dependencies' => array(
												array(
													'id' => 'show_in_single',
													'value' => '1',
												),
												array(
													'id' => 'single_position',
													'value' => 'shortcode',
												),
											),
											'help'         => __( 'Use this shortcode to specify a custom position. Just copy this shortcode wherever you want the button to be displayed.', 'moreconvert-compare-for-woocommerce' ),
										),
										'end-article-single' => array(
											'type' => 'end',
										),
										'start-article-loop' => array(
											'type'         => 'start',
											/* translators: 1: single/loop product page text */
											'title'        => sprintf( __( '"Add to Compare" Button %s', 'moreconvert-compare-for-woocommerce' ), '<span style="color:#fd5d00">' . __( 'on Product Listings/Loops', 'moreconvert-compare-for-woocommerce' ) . '</span>' ),
											'desc'         => __( 'Design your compare button on your shop page and other loops.', 'moreconvert-compare-for-woocommerce' ),
											'class'        => 'mct-accordion',
											'dependencies' => array(
												array(
													'id' => 'compare_enable',
													'value' => '1',
												),
											),
										),
										// Products loop.
										'show_in_loop'     => array(
											'label'   => __( 'Show "add to Compare" in listings', 'moreconvert-compare-for-woocommerce' ),
											'desc'    => __( 'Enable the "add to Compare" feature in WooCommerce products\' listing', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'switch',
											'default' => '1',
											'help'    => __( 'By activating this option, the Add to Compare button will be displayed in all lists.', 'moreconvert-compare-for-woocommerce' ),
										),
										'loop_position'    => array(
											'label'        => __( 'Button position in listings', 'moreconvert-compare-for-woocommerce' ),
											'desc'         => __( 'Choose where to show "Add to Compare" button or link in WooCommerce products\' listing.', 'moreconvert-compare-for-woocommerce' ),
											'default'      => 'before_add_to_cart',
											'type'         => 'select',
											'class'        => 'select2-trigger',
											'options'      => apply_filters(
												'moreconvert_compare_loop_position_options',
												array(
													'before_add_to_cart' => __( 'Before "add to cart" button', 'moreconvert-compare-for-woocommerce' ),
													'after_add_to_cart'  => __( 'After "add to cart" button', 'moreconvert-compare-for-woocommerce' ),
													'shortcode'          => __( 'Use shortcode', 'moreconvert-compare-for-woocommerce' ),
												)
											),
											'dependencies' => array(
												'id'    => 'show_on_loop',
												'value' => '1',
											),
											'help'         => __( 'Select the button position to view in the lists. Preferably similar to the button position on the product page.', 'moreconvert-compare-for-woocommerce' ),
										),
										'loop_shortcode_button' => array(
											'label'        => __( 'Shortcode button', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'copy-text',
											'default'      => '[moreconvert_compare_button]',
											'dependencies' => array(
												array(
													'id' => 'show_in_loop',
													'value' => '1',
												),
												array(
													'id' => 'loop_position',
													'value' => 'shortcode',
												),
											),
										),
										'end-article-loop' => array(
											'type' => 'end',
										),
									)
								),
								'table'   => apply_filters(
									'moreconvert_compare_table_settings',
									array(
										'start-article-table-settings' => array(
											'type'  => 'start',
											'title' => __( 'Comparison Table', 'moreconvert-compare-for-woocommerce' ),
										),
										'fields_to_show' => array(
											'label'   => __( 'Fields to Show', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'checkbox-group',
											'options' => array(
												'image'   => __( 'Image', 'moreconvert-compare-for-woocommerce' ),
												'title'   => __( 'Title', 'moreconvert-compare-for-woocommerce' ),
												'price'   => __( 'Price', 'moreconvert-compare-for-woocommerce' ),
												'sku'     => __( 'SKU', 'moreconvert-compare-for-woocommerce' ),
												'reviews' => __( 'Review Count & Rating', 'moreconvert-compare-for-woocommerce' ),
												'stock'   => __( 'Stock', 'moreconvert-compare-for-woocommerce' ),
												'add_to_cart' => __( 'Add to cart', 'moreconvert-compare-for-woocommerce' ),
												'attributes_p' => __( 'Attributes', 'moreconvert-compare-for-woocommerce' ),
												'variations_p' => __( 'Variations', 'moreconvert-compare-for-woocommerce' ),
												'short_description' => __( 'Short description', 'moreconvert-compare-for-woocommerce' ),
												'weight'  => __( 'Weight', 'moreconvert-compare-for-woocommerce' ),
												'dimensions' => __( 'Dimensions', 'moreconvert-compare-for-woocommerce' ),
											),
											'default' => array(
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
											),
										),
										'end-article-table-settings' => array(
											'type' => 'end',
										),
									)
								),
							),
						),
					)
				),
				'title'          => __( 'Compare Settings', 'moreconvert-compare-for-woocommerce' ),
				'logo'           => '<img src="' . MORECONVERT_COMPARE_URL . 'assets/img/logo.svg" width="45" height="40" alt="logo"/>',
				'header_buttons' => array(),
				'sidebar'        => array(),
				'header_menu'    => $this->header_menu,
				'type'           => 'setting-type',
				'ajax_saving'    => true,
				'sticky_buttons' => true,
				'id'             => 'moreconvert_compare_options',
			);

			$this->text_options = array(
				'options'        => apply_filters(
					'moreconvert_compare_text_options',
					array(
						'text' => array(
							'tabs'   => array(
								'labels' => __( 'Labels', 'moreconvert-compare-for-woocommerce' ),
							),
							'fields' => array(
								'labels' => apply_filters(
									'moreconvert_compare_text_settings',
									array(
										'start-article-text-settings' => array(
											'type'  => 'start',
											'title' => __( 'Text Settings', 'moreconvert-compare-for-woocommerce' ),
										),
										'add_to_compare_text' => array(
											'label'        => __( 'Add to Compare Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Add to Compare', 'moreconvert-compare-for-woocommerce' ),
										),
										'select_product_text' => array(
											'label'        => __( 'Select a Product Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Select a Product', 'moreconvert-compare-for-woocommerce' ),
										),
										'add_to_cart_text' => array(
											'label'        => __( 'Add to Cart Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Add to Cart', 'moreconvert-compare-for-woocommerce' ),
										),
										'specifications_text' => array(
											'label'        => __( 'Specifications Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Specifications', 'moreconvert-compare-for-woocommerce' ),
										),
										'stock_text'       => array(
											'label'        => __( 'Availability Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Availability', 'moreconvert-compare-for-woocommerce' ),
										),
										'reviews_text'     => array(
											'label'        => __( 'Reviews Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Reviews', 'moreconvert-compare-for-woocommerce' ),
										),
										'reviews_format'   => array(
											'label'        => __( 'Reviews Format', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( '{count} Reviews ({rating} stars)', 'moreconvert-compare-for-woocommerce' ),
											'desc'         => __( 'You can use the following placeholders: <code>{count}</code>,<code>{rating}</code>', 'moreconvert-compare-for-woocommerce' ),
										),
										'weight_text'      => array(
											'label'        => __( 'Weight Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Weight', 'moreconvert-compare-for-woocommerce' ),
										),
										'dimensions_text'  => array(
											'label'        => __( 'Dimensions Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Dimensions', 'moreconvert-compare-for-woocommerce' ),
										),
										'sku_text'         => array(
											'label'        => __( 'SKU Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'SKU', 'moreconvert-compare-for-woocommerce' ),
										),
										'short_description_text' => array(
											'label'        => __( 'Description Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Description', 'moreconvert-compare-for-woocommerce' ),
										),
										'end-article-text-settings' => array(
											'type' => 'end',
										),
										'start-article-popup-settings' => array(
											'type'  => 'start',
											'title' => __( 'Popup Text Settings', 'moreconvert-compare-for-woocommerce' ),
										),
										'top_products_text' => array(
											'label'        => __( 'Top Products  Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Top Products for Comparison', 'moreconvert-compare-for-woocommerce' ),
										),
										'search_product_text' => array(
											'label'        => __( 'Search a Product Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Search a Product', 'moreconvert-compare-for-woocommerce' ),
										),
										'no_products_text' => array(
											'label'        => __( 'No Products Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'No products in your comparison list.', 'moreconvert-compare-for-woocommerce' ),
										),
										'product_count_template_text' => array(
											'label'        => __( 'Popup product count template', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'Showing {loaded} of {totalProducts} products', 'moreconvert-compare-for-woocommerce' ),
											'desc'         => __( 'You can use the following placeholders: <code>{loaded}</code>,<code>{totalProducts}</code>', 'moreconvert-compare-for-woocommerce' ),
										),
										'no_more_products_text' => array(
											'label'        => __( 'No more products Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'No more products.', 'moreconvert-compare-for-woocommerce' ),
										),
										'error_text'       => array(
											'label'        => __( 'error Text', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'text',
											'translatable' => true,
											'default'      => __( 'An error occurred.', 'moreconvert-compare-for-woocommerce' ),
										),
										'end-article-popup-settings' => array(
											'type' => 'end',
										),
									)
								),
							),
						),
					)
				),
				'title'          => __( 'Compare Text Settings', 'moreconvert-compare-for-woocommerce' ),
				'logo'           => '<img src="' . MORECONVERT_COMPARE_URL . 'assets/img/logo.svg" width="45" height="40" alt="logo"/>',
				'header_buttons' => array(),
				'sidebar'        => array(),
				'header_menu'    => $this->header_menu,
				'type'           => 'setting-type',
				'ajax_saving'    => true,
				'sticky_buttons' => true,
				'id'             => 'moreconvert_compare_options',
			);

			$this->main_panel = new MCTOptions\Admin( $this->compare_options );
			$this->text_panel = new MCTOptions\Admin( $this->text_options );
			// load default value to options.
			$compare_options = $this->main_panel->get_options();
			if ( empty( $compare_options ) || ! isset( $compare_options['compare'] ) ) {
				$this->main_panel->set_default_options( 'compare' );
			}
			// load default value to options.
			$text_options = $this->text_panel->get_options();
			if ( empty( $text_options ) || ! isset( $text_options['texts'] ) ) {
				$this->text_panel->set_default_options( 'texts' );
			}
		}


		/**
		 * Update moreconvert-compare-inline.css after update plugin settings.
		 *
		 * @since 1.7.7
		 */
		public function after_update_options() {
			// phpcs:disable WordPress.Security
			if ( isset( $_POST['mct-action'] ) ) {
				$css_print_method = isset( $_POST['css_print_method'] ) ? sanitize_text_field( wp_unslash( $_POST['css_print_method'] ) ) : 'internal';
				if ( 'external' === $css_print_method ) {
					$this->create_css_file();
				}
			}
			// phpcs:enable WordPress.Security
		}


		/**
		 * Update page ids and remove data state after update plugin settings.
		 *
		 * @param array $new_options Mew options.
		 *
		 * @since 1.5.9
		 */
		public function after_ajax_update_options( $new_options ) {
			if ( isset( $new_options['compare'] ) ) {
				$state = isset( $new_options['compare']['remove_all_data'] ) ? sanitize_text_field( wp_unslash( $new_options['compare']['remove_all_data'] ) ) : '';
				update_option( 'moreconvert_compare_remove_all_data', $state );
				$css_print_method = isset( $new_options['compare']['css_print_method'] ) ? sanitize_text_field( wp_unslash( $new_options['compare']['css_print_method'] ) ) : 'internal';
				if ( 'external' === $css_print_method ) {
					$this->create_css_file();
				}
			}
		}

		/**
		 * Create css file for external method
		 *
		 * @return void
		 * @since 1.7.7
		 */
		protected function create_css_file() {
			$upload_dir = wp_upload_dir();
			if ( file_exists( trailingslashit( $upload_dir['basedir'] . '/more-convert/' ) . 'moreconvert-compare-inline.css' ) ) {
				wp_delete_file( trailingslashit( $upload_dir['basedir'] . '/more-convert/' ) . 'moreconvert-compare-inline.css' );
			}
			if ( wp_mkdir_p( $upload_dir['basedir'] . '/more-convert/' ) && ! file_exists( trailingslashit( $upload_dir['basedir'] ) . '/more-convert/moreconvert-compare-inline.css' ) ) {
				$file_handle = @fopen( trailingslashit( $upload_dir['basedir'] ) . '/more-convert/moreconvert-compare-inline.css', 'w' ); //phpcs:ignore
				if ( $file_handle ) {
					fwrite( $file_handle, Frontend::get_instance()->build_custom_css() ); //phpcs:ignore
					fclose( $file_handle ); //phpcs:ignore
					update_option( 'moreconvert_compare_css_version', floatval( get_option( 'moreconvert_compare_css_version', 1 ) ) + .001 );
				}
			}
		}


		/**
		 * Add admin menu
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			add_menu_page(
				__( 'MC Compare', 'moreconvert-compare-for-woocommerce' ),
				__( 'MC Compare', 'moreconvert-compare-for-woocommerce' ),
				'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities
				'moreconvert-compare',
				array( $this, 'show_compare_settings_page' ),
				MORECONVERT_COMPARE_URL . 'assets/img/compare.svg',
				56
			);
			$page  = add_submenu_page(
				'moreconvert-compare',
				__( 'Settings', 'moreconvert-compare-for-woocommerce' ),
				__( 'Settings', 'moreconvert-compare-for-woocommerce' ),
				'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities
				'moreconvert-compare',
				array( $this, 'show_compare_settings_page' )
			);
			$texts = add_submenu_page(
				'moreconvert-compare',
				__( 'Text', 'moreconvert-compare-for-woocommerce' ),
				__( 'Text', 'moreconvert-compare-for-woocommerce' ),
				'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities
				'moreconvert-compare-text-compare',
				array( $this, 'show_text_settings_page' )
			);
			add_action( 'load-' . $page, array( $this->main_panel, 'init' ) );
			add_action( 'load-' . $texts, array( $this->main_panel, 'init' ) );
		}

		/**
		 * Show compare settings page
		 *
		 * @return void
		 */
		public function show_compare_settings_page() {
			?>
			<div id="moreconvert_compare_options">
				<?php
				$fields = new MCTOptions\Fields( $this->compare_options );
				$fields->output();
				?>
			</div>
			<div id="snackbar"></div>
			<?php
		}

		/**
		 * Show text settings page
		 *
		 * @return void
		 */
		public function show_text_settings_page() {
			?>
			<div id="moreconvert_compare_options">
				<?php
				$fields = new MCTOptions\Fields( $this->text_options );
				$fields->output();
				?>
			</div>
			<div id="snackbar"></div>
			<?php
		}

		/**
		 * Run the installation
		 *
		 * @return void
		 */
		public function install() {
			if ( wp_doing_ajax() ) {
				return;
			}

			$stored_version = get_option( 'moreconvert_compare_version' );

			if ( version_compare( $stored_version, MORECONVERT_COMPARE_VERSION, '<' ) ) {
				// Update.
				do_action( 'moreconvert_compare_updated' );
			}

			do_action( 'moreconvert_compare_installed' );
		}

		/**
		 * Returns single instance of the class
		 *
		 * @return Admin
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
}
