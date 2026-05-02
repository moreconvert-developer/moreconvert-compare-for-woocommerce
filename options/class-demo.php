<?php
/**
 * Demo Class
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.6
 */

namespace MoreConvert\McCompare\MCTOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Demo' ) ) {
	/**
	 * This class handles demo for options plugin
	 */
	class Demo {

		/**
		 * Single instance of the class
		 *
		 * @var Demo
		 */
		protected static $instance;

		/**
		 * Demo options
		 *
		 * @var array
		 */
		public $demo_options;

		/**
		 * Main panel
		 *
		 * @var Admin
		 */
		public $main_panel;

		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 14 );
			add_action( 'init', array( $this, 'init_options' ) );
		}

		/**
		 * Add admin menu
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			$page = add_menu_page(
				__( 'MC Options Demo', 'moreconvert-compare-for-woocommerce' ),
				__( 'MC Options Demo', 'moreconvert-compare-for-woocommerce' ),
				'edit_posts', // phpcs:ignore WordPress.WP.Capabilities
				'moreconvert-options-demo',
				array( $this, 'show_demo_settings_page' ),
			);

			add_action( 'load-' . $page, array( $this->main_panel, 'init' ) );
		}


		/**
		 * Initialize options
		 *
		 * @return void
		 */
		public function init_options() {
			$this->demo_options = array(
				'options'        => array(
					'demo' => array(
						'tabs'   => array(
							'simple' => __( 'Simple', 'moreconvert-compare-for-woocommerce' ),
							'editor' => __( 'Editors', 'moreconvert-compare-for-woocommerce' ),
							'groups' => __( 'Groups', 'moreconvert-compare-for-woocommerce' ),
							'select' => __( 'select', 'moreconvert-compare-for-woocommerce' ),
							'custom' => __( 'Custom', 'moreconvert-compare-for-woocommerce' ),
						),
						'fields' => array(
							'simple' => array(
								'start-article-simple-settings' => array(
									'type'  => 'start',
									'title' => __( 'Simple Settings', 'moreconvert-compare-for-woocommerce' ),
								),
								'checkbox'        => array(
									'label' => __( 'Checkbox', 'moreconvert-compare-for-woocommerce' ),
									'desc'  => __( 'checkbox description', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'checkbox',
								),
								'switch'          => array(
									'label'   => __( 'Switch', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'switch',
									'default' => '1',
								),
								'text'            => array(
									'label'   => __( 'Text', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'text',
									'default' => 'test',
								),
								'email'           => array(
									'label'   => __( 'Email', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'email',
									'default' => 'a@b.com',
								),
								'number'          => array(
									'label'   => __( 'Number', 'moreconvert-compare-for-woocommerce' ),
									'desc'    => __( 'number description.', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'number',
									'default' => 5,
									'help'    => __( 'number help.', 'moreconvert-compare-for-woocommerce' ),
								),
								'url'             => array(
									'label' => __( 'URL', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'url',
								),
								'radio'           => array(
									'label'   => __( 'Radio', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'radio',
									'options' => array(
										'item_1' => __( 'Item 1', 'moreconvert-compare-for-woocommerce' ),
										'item_2' => __( 'Item 2', 'moreconvert-compare-for-woocommerce' ),
										'item_3' => __( 'Item 3', 'moreconvert-compare-for-woocommerce' ),
									),
									'default' => 'item_2',
									'help'    => __( 'Radio help.', 'moreconvert-compare-for-woocommerce' ),
								),
								'color'           => array(
									'label'   => __( 'Color', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'color',
									'default' => '#515151',
								),
								'datepicker'      => array(
									'label' => __( 'Date', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'datepicker',
								),
								'daterange'       => array(
									'label' => __( 'DateRange', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'daterange',
								),

								// Not value.
								'button'          => array(
									'label'             => __( 'Export Settings( button )', 'moreconvert-compare-for-woocommerce' ),
									'type'              => 'button',
									'class'             => 'mct_export_file_button btn-secondary',
									'default'           => __( 'Export', 'moreconvert-compare-for-woocommerce' ),
									'help'              => __( 'This feature allows you to download a backup file of your current plugin settings. This can be useful if you want to transfer your settings to another website or keep a copy for safekeeping.', 'moreconvert-compare-for-woocommerce' ),
									'custom_attributes' => array(
										'data-option_id' => 'moreconvert_demo_options',
									),
								),

								'import_settings' => array(
									'label'             => __( 'Import Settings', 'moreconvert-compare-for-woocommerce' ),
									'type'              => 'import',
									'default'           => __( 'Import', 'moreconvert-compare-for-woocommerce' ),
									'help'              => __( 'This feature allows you to upload a backup file of previously exported plugin settings. This can be useful if you need to restore your settings after updating the plugin or moving to a new website.', 'moreconvert-compare-for-woocommerce' ),
									'custom_attributes' => array(
										'data-title'       => __( 'Select Json File', 'moreconvert-compare-for-woocommerce' ),
										'data-button-text' => __( 'Select This File', 'moreconvert-compare-for-woocommerce' ),
										'data-option_id'   => 'moreconvert_demo_options',
										'data-mimetypes'   => 'application/json',
									),
								),
								'copy_text'       => array(
									'label'   => __( 'Copy Text', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'copy-text',
									'default' => '[moreconvert_demo_shortcode]',
									'help'    => __( 'Use this shortcode to specify a custom position. Just copy this shortcode wherever you want the shortcode to be displayed.', 'moreconvert-compare-for-woocommerce' ),
								),
								'end-article-simple-settings' => array(
									'type' => 'end',
								),
							),
							'editor' => array(
								'start-article-editors-settings' => array(
									'type'  => 'start',
									'title' => __( 'Editors', 'moreconvert-compare-for-woocommerce' ),
								),
								'code_editor' => array(
									'label'     => __( 'Custom CSS', 'moreconvert-compare-for-woocommerce' ),
									'help'      => __( 'This feature allows you to add your own custom CSS code to modify the appearance of your website. Use this feature if you want to make specific design changes that cannot be done through the plugin\'s existing styling options.', 'moreconvert-compare-for-woocommerce' ),
									'type'      => 'code-editor',
									'code_type' => 'css',
								),
								'wp_editor'   => array(
									'label'             => __( 'Wp Editor', 'moreconvert-compare-for-woocommerce' ),
									'type'              => 'wp-editor',
									'translatable'      => true,
									'default'           => __( 'default value.', 'moreconvert-compare-for-woocommerce' ),
									'custom_attributes' => array(
										'style' => 'max-width:100%;width:100%',
									),
								),
								'textarea'    => array(
									'label'        => __( 'Textarea', 'moreconvert-compare-for-woocommerce' ),
									'desc'         => __( 'You can use the following placeholders:<br><code>{test}</code>', 'moreconvert-compare-for-woocommerce' ),
									'type'         => 'textarea',
									'translatable' => true,
								),
								'end-article-editors-settings' => array(
									'type' => 'end',
								),
							),
							'groups' => array(
								'start-article-groups-settings' => array(
									'type'  => 'start',
									'title' => __( 'All Group Fields', 'moreconvert-compare-for-woocommerce' ),
								),
								'checkbox_group' => array(
									'label'   => __( 'Checkbox Group', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'checkbox-group',
									'options' => array(
										'item_1' => __( 'Item 1', 'moreconvert-compare-for-woocommerce' ),
										'item_2' => __( 'Item 2', 'moreconvert-compare-for-woocommerce' ),
										'item_3' => __( 'Item 3', 'moreconvert-compare-for-woocommerce' ),
										'item_4' => __( 'Item 4', 'moreconvert-compare-for-woocommerce' ),
										'item_5' => __( 'Item 5', 'moreconvert-compare-for-woocommerce' ),
									),
									'default' => array(
										'item_1',
										'item_3',
										'item_4',
									),
								),
								'color_style'    => array(
									'label'   => __( 'Color Style', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'color-style',
									'default' => array(
										'color'            => '#515151',
										'color-hover'      => '#fff',
										'background'       => '#ebebeb',
										'background-hover' => '#e67e22',
										'border'           => 'rgb(0,0,0,0)',
										'border-hover'     => 'rgb(0,0,0,0)',
									),
								),
								'group_fields'   => array(
									'section' => 'demo',
									'label'   => __( 'Group Fields', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'group-fields',
									'fields'  => array(
										'button_single_border' => array(
											'label'   => __( 'Border Css', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'text',
											'default' => 'none',
											'custom_attributes' => array(
												'style' => 'width:100px',
											),
										),
										'button_icon_size_single'   => array(
											'label'   => __( 'Icon size', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'text',
											'default' => '20px',
											'custom_attributes' => array(
												'style' => 'width:80px',
											),
										),
										'button_font_size_single'   => array(
											'label'   => __( 'Font size', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'text',
											'default' => 'inherit',
											'custom_attributes' => array(
												'style' => 'width:80px',
											),
										),
										'button_color_single'       => array(
											'label'   => __( 'Color', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'color',
											'default' => '#515151',
										),
										'button_background_color_single'       => array(
											'label'   => __( 'Background color', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'color',
											'default' => '#e9e6ed',
										),
									),
									'desc'    => __( 'e.g. 10px, 15rem, 13em etc.', 'moreconvert-compare-for-woocommerce' ),
								),
								'end-article-groups-settings' => array(
									'type' => 'end',
								),
							),
							'select' => array(
								'start-article-select-settings' => array(
									'type'  => 'start',
									'title' => __( 'Select Fields', 'moreconvert-compare-for-woocommerce' ),
								),
								'select'         => array(
									'type'    => 'select',
									'default' => 'internal',
									'label'   => esc_html__( 'Select', 'moreconvert-compare-for-woocommerce' ),
									'options' => array(
										'item_1' => esc_html__( 'item 1', 'moreconvert-compare-for-woocommerce' ),
										'item_2' => esc_html__( 'item 2', 'moreconvert-compare-for-woocommerce' ),
									),
									'desc'    => __( 'select description.', 'moreconvert-compare-for-woocommerce' ),
								),
								'select_icon'    => array(
									'label'   => __( 'Select icon', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'select-icon',
									'class'   => 'select2-trigger',
									'options' => array(
										'notification-1' => esc_html__( 'Notification 1', 'moreconvert-compare-for-woocommerce' ),
										'notification-2' => esc_html__( 'Notification 2', 'moreconvert-compare-for-woocommerce' ),
										'notification-3' => esc_html__( 'Notification 3', 'moreconvert-compare-for-woocommerce' ),
										'notification-4-light' => esc_html__( 'Notification 4 light', 'moreconvert-compare-for-woocommerce' ),
										'notification-4-regular' => esc_html__( 'Notification 4 regular', 'moreconvert-compare-for-woocommerce' ),
										'notification-5-light' => esc_html__( 'Notification 5 light', 'moreconvert-compare-for-woocommerce' ),
										'notification-5-regular' => esc_html__( 'Notification 5 regular', 'moreconvert-compare-for-woocommerce' ),
										'notification-6-light' => esc_html__( 'Notification 6 light', 'moreconvert-compare-for-woocommerce' ),
										'notification-6-regular' => esc_html__( 'Notification 6 regular', 'moreconvert-compare-for-woocommerce' ),
										'notification-7-light' => esc_html__( 'Notification 7 light', 'moreconvert-compare-for-woocommerce' ),
										'notification-7-regular' => esc_html__( 'Notification 7 regular', 'moreconvert-compare-for-woocommerce' ),
										'custom'         => esc_html__( 'Custom icon', 'moreconvert-compare-for-woocommerce' ),
									),
									'default' => 'notification-1',
								),
								'multi_select'   => array(
									'label'   => __( 'Multi Select', 'moreconvert-compare-for-woocommerce' ),
									'type'    => 'multi-select',
									'class'   => 'select2-trigger',
									'options' => array(
										'item_1' => __( 'Item 1', 'moreconvert-compare-for-woocommerce' ),
										'item_2' => __( 'Item 2', 'moreconvert-compare-for-woocommerce' ),
										'item_3' => __( 'Item 3', 'moreconvert-compare-for-woocommerce' ),
										'item_4' => __( 'Item 4', 'moreconvert-compare-for-woocommerce' ),
										'item_5' => __( 'Item 5', 'moreconvert-compare-for-woocommerce' ),
									),
									'default' => 'item_1',
								),
								'page_select'    => array(
									'label'      => __( 'page select', 'moreconvert-compare-for-woocommerce' ),
									'type'       => 'page-select',
									'show_links' => true,
									'class'      => 'select2-trigger',
									'help'       => __( 'Wishlist page needs to be selected so the plugin knows where it is. You should choose it upon installation of the plugin or create it manually.', 'moreconvert-compare-for-woocommerce' ),
								),
								'search_post'    => array(
									'label'             => __( 'Posts', 'moreconvert-compare-for-woocommerce' ),
									'type'              => 'search-post',
									'custom_attributes' => array(
										'data-post-types' => 'post,page',
									),
								),
								'search_product' => array(
									'label' => __( 'products', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'search-product',
								),
								'search_cat'     => array(
									'label' => __( 'categories', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'search-product-cat',
								),
								'search_user'    => array(
									'label' => __( 'Users', 'moreconvert-compare-for-woocommerce' ),
									'type'  => 'search-users',
								),
								'end-article-select-settings' => array(
									'type' => 'end',
								),
							),
							'custom' => array(
								'start-article-custom-settings' => array(
									'type'  => 'start',
									'title' => __( 'Custom Fields', 'moreconvert-compare-for-woocommerce' ),
								),
								'add_button'      => array(
									'label'        => __( 'Add button', 'moreconvert-compare-for-woocommerce' ),
									'type'         => 'add-button',
									'translatable' => true,
									'links'        => array(
										'back'         => __( 'Close pop up', 'moreconvert-compare-for-woocommerce' ),
										'signup-login' => __( 'Sign-up or login', 'moreconvert-compare-for-woocommerce' ),
										'custom-link'  => __( 'Custom url', 'moreconvert-compare-for-woocommerce' ),
									),
									'default'      => array(
										array(
											'label'       => __( 'View My Wishlist', 'moreconvert-compare-for-woocommerce' ),
											'background'  => '#555555',
											'background-hover' => '#555555',
											'label-color' => '#ffffff',
											'label-hover-color' => '#ffffff',
											'border-radius' => '2px',
											'link'        => 'signup-login',
											'custom-link' => '',
										),
										array(
											'label'       => __( 'Close', 'moreconvert-compare-for-woocommerce' ),
											'background'  => 'rgba(0,0,0,0)',
											'background-hover' => 'rgba(0,0,0,0)',
											'label-color' => '#7e7e7e',
											'label-hover-color' => '#7e7e7e',
											'border-radius' => '2px',
											'link'        => 'back',
											'custom-link' => '',
										),
									),

									'limit'        => 3,
									'help'         => __( 'These settings are related to the button. You can consider more than one button.', 'moreconvert-compare-for-woocommerce' ),
								),
								'repeater'        => array(
									'label'           => __( 'Repeater', 'moreconvert-compare-for-woocommerce' ),
									'type'            => 'repeater',
									'add_new_label'   => __( 'Add another social link', 'moreconvert-compare-for-woocommerce' ),
									'limit'           => 5,
									'repeater_fields' => array(
										'social-name' => array(
											'label'   => __( 'Name', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'select',
											'class'   => 'select2-trigger',
											'options' => array(
												'instagram' => __( 'Instagram', 'moreconvert-compare-for-woocommerce' ),
												'telegram' => __( 'Telegram', 'moreconvert-compare-for-woocommerce' ),
												'reddit'   => __( 'Reddit', 'moreconvert-compare-for-woocommerce' ),
												'whatsapp' => __( 'whatsapp', 'moreconvert-compare-for-woocommerce' ),
												'dribbble' => __( 'dribbble', 'moreconvert-compare-for-woocommerce' ),
												'amazon'   => __( 'amazon', 'moreconvert-compare-for-woocommerce' ),
												'spotify'  => __( 'spotify', 'moreconvert-compare-for-woocommerce' ),
												'behance'  => __( 'behance', 'moreconvert-compare-for-woocommerce' ),
												'location' => __( 'location', 'moreconvert-compare-for-woocommerce' ),
												'tumblr'   => __( 'tumblr', 'moreconvert-compare-for-woocommerce' ),
												'pinterest' => __( 'pinterest', 'moreconvert-compare-for-woocommerce' ),
												'youtube'  => __( 'youtube', 'moreconvert-compare-for-woocommerce' ),
												'linkedin' => __( 'linkedin', 'moreconvert-compare-for-woocommerce' ),
												'twitter'  => __( 'twitter(X)', 'moreconvert-compare-for-woocommerce' ),
												'facebook' => __( 'facebook', 'moreconvert-compare-for-woocommerce' ),

											),
										),
										'social-url'  => array(
											'label' => __( 'URL', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'url',
										),
									),
								),
								'nested_repeater' => array(
									'label'           => __( 'Nested repeater Group', 'moreconvert-compare-for-woocommerce' ),
									'type'            => 'nested-repeater',
									'add_new_label'   => __( 'OR', 'moreconvert-compare-for-woocommerce' ),
									'repeater_fields' => array(
										'inner_repeater' => array(
											'label'      => __( 'inner repeater', 'moreconvert-compare-for-woocommerce' ),
											'type'       => 'inner-repeater',
											'desc'       => __( 'Or', 'moreconvert-compare-for-woocommerce' ),
											'add_button' => __( 'And', 'moreconvert-compare-for-woocommerce' ),
											'remove_button' => '<span class="dashicons dashicons-no-alt"></span>',
											'repeater_fields' => array(
												'condition_type'               => array(
													'label' => __( 'Condition Type', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'select',
													'options' => array(
														'' => __( 'Please, Select Condition Type', 'moreconvert-compare-for-woocommerce' ),
														array(
															'label'   => __( 'Marketing Toolkits', 'moreconvert-compare-for-woocommerce' ),
															'options' => array(
																'campaign-status'   => __( 'Campaigns State', 'moreconvert-compare-for-woocommerce' ),
																'automation-status' => __( 'Automations State', 'moreconvert-compare-for-woocommerce' ),
															),
														),
													),
													'default' => '',
													'custom_attributes' => array(
														'autocomplete' => 'off',
													),
												),
												'condition_campaign_operator'           => array(
													'label' => '',
													'type' => 'select',
													'options' => array(
														'sent' => __( 'was sent', 'moreconvert-compare-for-woocommerce' ),
														'not-sent' => __( 'was not sent', 'moreconvert-compare-for-woocommerce' ),
														'opened' => __( 'was opened', 'moreconvert-compare-for-woocommerce' ),
														'not-opened' => __( 'was not opened', 'moreconvert-compare-for-woocommerce' ),
														'clicked' => __( 'was clicked', 'moreconvert-compare-for-woocommerce' ),
														'not-clicked' => __( 'was not clicked', 'moreconvert-compare-for-woocommerce' ),
													),
													'dependencies' => array(
														'id' => 'condition_type',
														'value' => 'campaign-status',
													),
												),
												'condition_automation_operator'         => array(
													'label' => '',
													'type' => 'select',
													'options' => array(
														'active' => __( 'Active in', 'moreconvert-compare-for-woocommerce' ),
														'not-active' => __( 'Not active in', 'moreconvert-compare-for-woocommerce' ),
														'completed' => __( 'Has completed', 'moreconvert-compare-for-woocommerce' ),
													),
													'dependencies' => array(
														'id' => 'condition_type',
														'value' => 'automation-status',
													),
												),
												'condition_campaigns'          => array(
													'label' => __( 'Campaigns', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'select',
													'class' => 'select2-trigger',
													'options' => array(
														'item_1' => __( 'Campaign 1', 'moreconvert-compare-for-woocommerce' ),
														'item_2' => __( 'Campaign 2', 'moreconvert-compare-for-woocommerce' ),
														'item_3' => __( 'Campaign 3', 'moreconvert-compare-for-woocommerce' ),
													),
													'dependencies' => array(
														'id' => 'condition_type',
														'value' => 'campaign-status',
													),
												),
												'condition_automations'        => array(
													'label' => __( 'Automations', 'moreconvert-compare-for-woocommerce' ),
													'type' => 'select',
													'class' => 'select2-trigger',
													'options' => array(
														'item_1' => __( 'automation 1', 'moreconvert-compare-for-woocommerce' ),
														'item_2' => __( 'automation 2', 'moreconvert-compare-for-woocommerce' ),
														'item_3' => __( 'automation 3', 'moreconvert-compare-for-woocommerce' ),
													),
													'dependencies' => array(
														'id'    => 'condition_type',
														'value' => 'automation-status',
													),
												),
											),
										),
									),
								),
								'manage'          => array(
									'type'         => 'manage',
									'count'        => 5,
									/* translators: %s: do not translated ,this is a placeholder */
									'row-title'    => __( 'Reminder email %s', 'moreconvert-compare-for-woocommerce' ),
									'row-desc'     => __( 'Specify sending time and content of email here.', 'moreconvert-compare-for-woocommerce' ),
									'table-fields' => array(
										'enable_email'    => array(
											'label' => __( 'Activation', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'switch',
											'help'  => __( 'enable email. So the ability to send automatic email is activated.', 'moreconvert-compare-for-woocommerce' ),
										),
										'mail_subject'    => array(
											'label' => __( 'Email subject', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'value',
										),
										'send_after_days' => array(
											'label'        => __( 'Send after', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'value',
											/* translators: %s: do not translated ,this is a placeholder */
											'value_format' => __( '%s day(s)', 'moreconvert-compare-for-woocommerce' ),
										),
										'queue'           => array(
											'label'   => __( 'Queue', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'value',
											'default' => 0,
										),
									),
									'table-action' => array(
										'title' => __( 'Test email', 'moreconvert-compare-for-woocommerce' ),
										'class' => ' ico-btn email-btn btn-primary min-width-btn small-btn wlfmc-send-offer-email-test',
									),
									'fields'       => array(
										'offer_columns_start' => array(
											'type'    => 'columns-start',
											'columns' => 2,
										),
										'offer_column_one_start' => array(
											'type'  => 'column-start',
											'class' => 'flexible-rows',
										),
										'send_after_days' => array(
											'label' => __( 'Send this email after days', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'number',
											'custom_attributes' => array(
												'min' => 0,
											),
											'desc'  => __( 'Zero means send the email immediately after conditions  happen', 'moreconvert-compare-for-woocommerce' ),
											'help'  => __( 'After how many days will this email be sent? Enter the number of days. If you want the email to be sent as soon as products are added to the wishlist, enter zero (Usually for the first email).', 'moreconvert-compare-for-woocommerce' ),
										),
										'message_content' => array(
											'label'        => __( 'Message content', 'moreconvert-compare-for-woocommerce' ),
											'desc'         => __( 'Just for Moreconvert Messages plugin', 'moreconvert-compare-for-woocommerce' ),
											'type'         => 'textarea',
											'default'      => '0',
											'parent_class' => 'enable-for-pro',
											'custom_attributes' => array(
												'disabled' => 'true',
											),
										),
										'mail_heading'    => array(
											'label' => __( 'Email heading', 'moreconvert-compare-for-woocommerce' ),
											'class' => 'mail_heading',
											'desc'  => __( 'Enter the title for the email notification. Leave blank to use the default heading: "<i>There is a deal for you!</i>". You can use the following placeholders: <code>{username}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{expiry_date}</code> <code>{site_name}</code> <code>{site_description}</code>', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'text',
										),
										'mail_subject'    => array(
											'label'   => __( 'Email subject', 'moreconvert-compare-for-woocommerce' ),
											'desc'    => __( 'Enter the email subject line. Leave blank to use the default subject: "<i>A product of your Wishlist is on sale</i>". You can use the following placeholders: <code>{username}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{expiry_date}</code> <code>{site_name}</code> <code>{site_description}</code>', 'moreconvert-compare-for-woocommerce' ),
											'default' => '',
											'type'    => 'text',
										),
										'html_content'    => array(
											'label'   => __( 'Email html content', 'moreconvert-compare-for-woocommerce' ),
											'desc'    => __( 'This field lets you modify the main content of the HTML email. You can use the following placeholders: <code>{username}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{expiry_date}</code> <code>{wishlist_url}</code> <code>{checkout_url}</code> <code>{shop_url}</code> <code>{site_name}</code> <code>{site_description}</code> <code>{unsubscribe_url}</code> <code>{wishlist_items}</code>', 'moreconvert-compare-for-woocommerce' ),
											'class'   => 'html_content',
											'type'    => 'wp-editor',
											'default' => '',
											'editor_height' => 300,
											'parent_dependencies' => array(
												'id'    => 'mail-type',
												'value' => 'html,mc-template,simple-template',
											),
										),
										'text_content'    => array(
											'label'   => __( 'Email plain content', 'moreconvert-compare-for-woocommerce' ),
											'desc'    => __( 'This field lets you modify the main content of the text email. You can use the following placeholders: <code>{username}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{expiry_date}</code> <code>{wishlist_url}</code> <code>{checkout_url}</code> <code>{shop_url}</code> <code>{site_name}</code> <code>{site_description}</code> <code>{unsubscribe_url}</code> <code>{wishlist_items}</code>', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'textarea',
											'default' => '',
											'class'   => 'resizeable text_content',
											'custom_attributes' => array(
												'cols' => '120',
												'rows' => '10',
											),
											'parent_dependencies' => array(
												'id'    => 'mail-type',
												'value' => 'plain',
											),
										),
										'additional-placeholders' => array(
											'label' => __( 'Additional placeholder', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'title',
											'desc'  => __( 'You can use the following placeholders in email content:<br><strong> On Sale Automations:</strong><br><code>{regular_price}</code> <code>{sale_price}</code> <code>{product_name}</code> <code>{product_url}</code> <code>{add_to_cart_url}</code> <code>{product_image}</code><br><strong> Back in Stock Automations:</strong><br><code>{stock_quantity}</code><code>{product_name}</code> <code>{product_url}</code> <code>{add_to_cart_url}</code> <code>{product_image}</code><br><strong> Low Stock Automations:</strong><br><code>{stock_quantity}</code><code>{product_name}</code> <code>{product_url}</code> <code>{add_to_cart_url}</code> <code>{product_image}</code><br><strong> Price Change Automations:</strong><br><code>{old_price}</code> <code>{new_price}</code><code>{product_name}</code> <code>{product_url}</code> <code>{add_to_cart_url}</code> <code>{product_image}</code>', 'moreconvert-compare-for-woocommerce' ),
										),
										'html_footer'     => array(
											'label'   => __( 'Email html footer', 'moreconvert-compare-for-woocommerce' ),
											'desc'    => __( 'This field lets you modify the footer content of the HTML email. You can use the following placeholders: <code>{username}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{expiry_date}</code> <code>{wishlist_url}</code> <code>{checkout_url}</code> <code>{shop_url}</code> <code>{site_name}</code> <code>{site_description}</code> <code>{unsubscribe_url}</code>', 'moreconvert-compare-for-woocommerce' ),
											'class'   => 'html_footer',
											'type'    => 'wp-editor',
											'default' => __( '<a href="{unsubscribe_url}">Unsubscribe</a>', 'moreconvert-compare-for-woocommerce' ),
											'editor_height' => 150,
											'parent_dependencies' => array(
												'id'    => 'mail-type',
												'value' => 'html,mc-template,simple-template',
											),
										),
										'text_footer'     => array(
											'label'   => __( 'Email plain footer', 'moreconvert-compare-for-woocommerce' ),
											'desc'    => __( 'This field lets you modify the footer content of the text email. You can use the following placeholders: <code>{username}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{expiry_date}</code> <code>{wishlist_url}</code> <code>{checkout_url}</code> <code>{shop_url}</code> <code>{site_name}</code> <code>{site_description}</code> <code>{unsubscribe_url}</code>', 'moreconvert-compare-for-woocommerce' ),
											'type'    => 'textarea',
											'default' => __( 'unsubscribe:{unsubscribe_url}', 'moreconvert-compare-for-woocommerce' ),
											'class'   => 'resizeable text_footer',
											'custom_attributes' => array(
												'cols' => '120',
												'rows' => '5',
											),
											'parent_dependencies' => array(
												'id'    => 'mail-type',
												'value' => 'plain',
											),
										),
										'offer_column_one_end' => array(
											'type' => 'column-end',
										),
										'offer_column_two_start' => array(
											'type' => 'column-start',
											'custom_attributes' => array(
												'height' => '100%',
											),
										),
										'offer_preview_email' => array(
											'title' => __( 'Preview:', 'moreconvert-compare-for-woocommerce' ),
											'type'  => 'iframe',
											'class' => 'preview_iframe_wrapper',
											'src'   => 'about:blank',
											'custom_attributes' => array(
												'width'  => '100%',
												'height' => '100%',
												'style'  => is_rtl() ? 'max-width:calc( 100% - 40px );padding:0 20px;border-right:1px solid #f2f2f2;max-height:calc( 100% - 100px );min-height:500px' : 'max-width:calc( 100% - 40px );padding:0 20px;border-left:1px solid #f2f2f2;max-height:calc( 100% - 100px );min-height:500px;',
											),
										),
										'offer_column_two_end' => array(
											'type' => 'column-end',
										),
										'offer_columns_end' => array(
											'type' => 'columns-end',
										),
									),
									'default'      => array(
										array(
											'enable_email' => '1',
											'send_after_days' => '1',
											'message_content' => '',
											'mail_heading' => __( 'Check it out, {user_first_name}', 'moreconvert-compare-for-woocommerce' ),
											'mail_subject' => __( 'Check it out, {user_first_name}', 'moreconvert-compare-for-woocommerce' ),
											'html_content' => '',
											'text_content' => '',
											'text_footer'  => __( 'unsubscribe:{unsubscribe_url}', 'moreconvert-compare-for-woocommerce' ),
											'html_footer'  => __( '<a href="{unsubscribe_url}">Unsubscribe</a>', 'moreconvert-compare-for-woocommerce' ),
										),
										array(
											'enable_email' => '1',
											'send_after_days' => '3',
											'message_content' => '',
											'mail_heading' => __( 'Deals you’ve been waiting for!', 'moreconvert-compare-for-woocommerce' ),
											'mail_subject' => __( 'Deals you’ve been waiting for!', 'moreconvert-compare-for-woocommerce' ),
											'html_content' => '',
											'text_content' => '',
											'text_footer'  => __( 'unsubscribe:{unsubscribe_url}', 'moreconvert-compare-for-woocommerce' ),
											'html_footer'  => __( '<a href="{unsubscribe_url}">Unsubscribe</a>', 'moreconvert-compare-for-woocommerce' ),
										),
										array(
											'enable_email' => '1',
											'send_after_days' => '5',
											'message_content' => '',
											'mail_heading' => __( 'Got {coupon_amount} off your favorites?', 'moreconvert-compare-for-woocommerce' ),
											'mail_subject' => __( 'Got {coupon_amount} off your favorites?', 'moreconvert-compare-for-woocommerce' ),
											'html_content' => '',
											'text_content' => '',
											'text_footer'  => __( 'unsubscribe:{unsubscribe_url}', 'moreconvert-compare-for-woocommerce' ),
											'html_footer'  => __( '<a href="{unsubscribe_url}">Unsubscribe</a>', 'moreconvert-compare-for-woocommerce' ),
										),
										array(
											'enable_email' => '1',
											'send_after_days' => '7',
											'message_content' => '',
											'mail_heading' => __( 'The item on your wishlist is almost sold out!', 'moreconvert-compare-for-woocommerce' ),
											'mail_subject' => __( 'The item on your wishlist is almost sold out!', 'moreconvert-compare-for-woocommerce' ),
											'html_content' => '',
											'text_content' => '',
											'text_footer'  => __( 'unsubscribe:{unsubscribe_url}', 'moreconvert-compare-for-woocommerce' ),
											'html_footer'  => __( '<a href="{unsubscribe_url}">Unsubscribe</a>', 'moreconvert-compare-for-woocommerce' ),
										),
										array(
											'enable_email' => '1',
											'send_after_days' => '9',
											'message_content' => '',
											'mail_heading' => __( 'Just one day for your wishlist', 'moreconvert-compare-for-woocommerce' ),
											'mail_subject' => __( 'Just one day for your wishlist', 'moreconvert-compare-for-woocommerce' ),
											'html_content' => '',
											'text_content' => '',
											'text_footer'  => __( 'unsubscribe:{unsubscribe_url}', 'moreconvert-compare-for-woocommerce' ),
											'html_footer'  => __( '<a href="{unsubscribe_url}">Unsubscribe</a>', 'moreconvert-compare-for-woocommerce' ),
										),
									),
								),
								'end-article-custom-settings' => array(
									'type' => 'end',
								),
							),
						),
					),
				),
				'title'          => __( 'FrameWork Demo Settings', 'moreconvert-compare-for-woocommerce' ),
				'logo'           => '<img src="' . MORECONVERT_COMPARE_URL . 'assets/img/logo.svg" width="45" height="40" alt="logo"/>',
				'header_buttons' => array(),
				'sidebar'        => array(),
				'header_menu'    => array(),
				'type'           => 'setting-type',
				'ajax_saving'    => true,
				'sticky_buttons' => true,
				'id'             => 'moreconvert_demo_options',
			);
			$this->main_panel = new Admin( $this->demo_options );
		}

		/**
		 * Show Demo settings page
		 *
		 * @return void
		 */
		public function show_demo_settings_page() {
			?>
			<div id="moreconvert_demo_options">
				<?php
				$fields = new Fields( $this->demo_options );
				$fields->output();
				?>
			</div>
			<div id="snackbar"></div>
			<?php
		}

		/**
		 * Returns single instance of the class
		 *
		 * @return Demo
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
}
