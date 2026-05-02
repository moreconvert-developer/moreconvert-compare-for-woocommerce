<?php
/**
 * Field Class
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.6
 */

namespace MoreConvert\McCompare\MCTOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Fields' ) ) {

	/**
	 * Class Fields
	 */
	class Fields {
		/**
		 * Single instance of the class
		 *
		 * @var Fields
		 */
		protected static $instance;
		/**
		 * Config
		 *
		 * @var array
		 */
		private $config;
		/**
		 * Sections
		 *
		 * @var mixed|string
		 */
		public $sections;
		/**
		 * Options
		 *
		 * @var mixed|string
		 */
		public $options;

		/**
		 * Option type
		 *
		 * @var mixed|string
		 */
		public $type;
		/**
		 * Title
		 *
		 * @var mixed|string
		 */
		public $title;

		/**
		 * Option id
		 *
		 * @var mixed|string
		 */
		public $id;
		/**
		 * Option description
		 *
		 * @var mixed|string
		 */
		public $desc;

		/**
		 * Option sidebar
		 *
		 * @var array
		 */
		public $sidebar;
		/**
		 * Saved options
		 *
		 * @var mixed|void
		 */
		public $saved_options;

		/**
		 * Header buttons
		 *
		 * @var array
		 */
		public $header_buttons;

		/**
		 * Header menu
		 *
		 * @var array
		 */
		public $header_menu;

		/**
		 * Footer buttons
		 *
		 * @var array
		 */
		public $footer_buttons;

		/**
		 * Page buttons
		 *
		 * @var array
		 */
		public $page_buttons;

		/**
		 * Logo
		 *
		 * @var string
		 */
		public $logo;

		/**
		 * Steps
		 *
		 * @var array
		 */
		public $steps;

		/**
		 * Subtitle
		 *
		 * @var string
		 */
		public $subtitle;

		/**
		 * Class array
		 *
		 * @var array
		 */
		public $class_array;

		/**
		 * Tools
		 *
		 * @var array
		 */
		public $tools;

		/**
		 * Ajax saving
		 *
		 * @var boolean
		 */
		public $ajax_saving;

		/**
		 * Sticky buttons
		 *
		 * @var boolean
		 */
		public $sticky_buttons;



		/**
		 * Back to dashboard
		 *
		 * @var boolean
		 */
		public $back_to_dashboard;

		/**
		 * Add form tag
		 *
		 * @var boolean
		 */
		public $add_form_tag;

		/**
		 * Removable query args
		 *
		 * @var array
		 */
		public $removable_query_args;

		/**
		 * Field values
		 *
		 * @var array
		 */
		public $field_values;

		/**
		 * Fields
		 *
		 * @var bool
		 */
		public $fields;

		/**
		 * Returns single instance of the class
		 *
		 * @return Fields
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor of the class
		 *
		 * @param array $args array of options.
		 *
		 * @return void
		 * @version 2.1.0
		 */
		public function __construct( $args = array() ) {

			$this->sections             = $args['sections'] ?? '';
			$this->options              = $args['options'] ?? '';
			$this->title                = $args['title'] ?? '';
			$this->logo                 = $args['logo'] ?? '';
			$this->header_buttons       = $args['header_buttons'] ?? array();
			$this->header_menu          = $args['header_menu'] ?? array();
			$this->footer_buttons       = $args['footer_buttons'] ?? array();
			$this->page_buttons         = $args['page_buttons'] ?? array();
			$this->type                 = $args['type'] ?? '';
			$this->id                   = $args['id'] ?? '';
			$this->desc                 = $args['desc'] ?? '';
			$this->sidebar              = $args['sidebar'] ?? array();
			$this->steps                = $args['steps'] ?? array();
			$this->tools                = $args['tools'] ?? array();
			$this->subtitle             = $args['subtitle'] ?? '';
			$this->class_array          = $args['class_array'] ?? array();
			$this->ajax_saving          = $args['ajax_saving'] ?? false;
			$this->sticky_buttons       = $args['sticky_buttons'] ?? false;
			$this->back_to_dashboard    = $args['back_to_dashboard'] ?? false;
			$this->field_values         = $args['field_values'] ?? false;
			$this->fields               = $args['fields'] ?? false;
			$this->add_form_tag         = $args['add_form_tag'] ?? true;
			$this->removable_query_args = $args['removable_query_args'] ?? array();
			$this->saved_options        = $this->get_option();

			// Get configuration from singleton.
			$this->config = Config::get_instance()->get_config();
		}

		/**
		 * Print html output.
		 *
		 * @return void
		 * @version 2.5.5
		 */
		public function output() {
			do_action(
				'moreconvert_framework_output_panel_' . $this->type,
				array(
					'sections'             => $this->sections,
					'options'              => $this->options,
					'title'                => $this->title,
					'logo'                 => $this->logo,
					'header_buttons'       => $this->header_buttons,
					'header_menu'          => $this->header_menu,
					'footer_buttons'       => $this->footer_buttons,
					'page_buttons'         => $this->page_buttons,
					'type'                 => $this->type,
					'id'                   => $this->id,
					'desc'                 => $this->desc,
					'sidebar'              => $this->sidebar,
					'steps'                => $this->steps,
					'tools'                => $this->tools,
					'subtitle'             => $this->subtitle,
					'class_array'          => $this->class_array,
					'saved_options'        => $this->saved_options,
					'ajax_saving'          => $this->ajax_saving,
					'sticky_buttons'       => $this->sticky_buttons,
					'back_to_dashboard'    => $this->back_to_dashboard,
					'field_values'         => $this->field_values,
					'fields'               => $this->fields,
					'add_form_tag'         => $this->add_form_tag,
					'removable_query_args' => $this->removable_query_args,
				)
			);

			?>
			<?php if ( ! has_action( 'moreconvert_framework_output_panel_' . $this->type ) ) : ?>

				<?php if ( 'fields-type' === $this->type ) : ?>
					<div class="mct-wrapper">
						<div class="mct-options">
							<?php if ( is_array( $this->fields ) && ! empty( $this->fields ) ) : ?>
									<?php
									// print table of fields.
									$this->print_table_fields( 'values', $this->fields );
									?>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( 'setting-type' === $this->type ) : ?>
					<?php
					// phpcs:disable WordPress.Security.NonceVerification.Recommended
					$active_section          = isset( $_GET['section'] ) && '' !== $_GET['section'] ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
					$active_tab              = isset( $_GET['tab'] ) && '' !== $_GET['tab'] ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
					$type_class              = isset( $_GET['type'] ) && 'class' === $_GET['type'];
					$has_sidebar             = ! empty( $this->sidebar );
					$container_sticky_start  = $this->sticky_buttons ? '<div class="mct-sticky-container">' : '';
					$container_sticky_middle = $this->sticky_buttons ? '</div><div class="mct-sticky-holder"></div><div class="mct-sticky">' : '';
					$container_sticky_end    = $this->sticky_buttons ? '</div>' : '';
					$sticky_class            = $this->sticky_buttons ? 'mct-article' : 'mt-20';
					// phpcs:enable
					?>
					<div class="wrap mct-wrapper">
						<?php if ( isset( $this->title ) ) : ?>
							<div class="mct-header">
								<figure class="mct-logo d-flex margin-bet f-center no-mar">
									<?php echo isset( $this->logo ) ? wp_kses_post( $this->logo ) : ''; ?>
									<figcaption>
										<?php echo esc_attr( $this->title ); ?>
									</figcaption>
								</figure>
								<?php
								if ( isset( $this->header_menu ) && ! empty( $this->header_menu ) ) {
									$this->print_header_menu( $this->header_menu );
								}
								if ( isset( $this->header_buttons ) && ! empty( $this->header_buttons ) ) {
									$this->print_header_buttons( $this->header_buttons );}
								?>
							</div>
						<?php endif; ?>
						<div class="message-holder">
							<h1 class="wp-heading-inline no-mar"></h1>
							<?php $this->message(); ?>
						</div>

						<?php if ( '' !== $this->sections && ! empty( $this->sections ) ) : ?>
							<div
								class="mct-section-wrapper" <?php echo '' !== $active_section ? 'style="display: none"' : ''; ?>>
								<div class="mct-options">
									<div class="mct-inside">
										<div class="table-title">
											<strong class=""><?php esc_html_e( 'Manage Settings', 'moreconvert-compare-for-woocommerce' ); ?></strong>
										</div>
										<table class="widefat striped  mct-sections ">
											<tbody>
											<?php foreach ( $this->sections as $k => $section ) : ?>
												<tr class="">
													<td>
														<p class="d-flex space-between mct-fix-mar">
													<span>
													<?php echo esc_attr( $section ); ?>
													</span>
															<a href="#option_<?php echo esc_attr( $k ); ?>" class="center-align btn-primary min-width-btn">
																<?php esc_html_e( 'Manage', 'moreconvert-compare-for-woocommerce' ); ?>
															</a>
														</p>
													</td>
												</tr>
											<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>

							</div>
						<?php endif; ?>
						<?php foreach ( $this->options as $k => $option ) : ?>
							<?php $current_tab = ''; ?>
							<div id="<?php echo esc_attr( 'option_' . $k ); ?>" class=" mct-section-content" <?php echo empty( $this->sections ) || 'option_' . $k === $active_section ? '' : 'style="display: none"'; ?>>

								<div class="mct-options <?php echo $has_sidebar ? 'has-sidebar' : ''; ?>">
									<div class="mct-inside ">
										<?php do_action( 'moreconvert_framework_start_mct_inside', $this ); ?>
										<?php if ( true === $this->add_form_tag && false === $type_class ) : ?>
										<form method="post" action="" class="mct-inside-inner mct-form <?php echo $this->ajax_saving ? 'mc-ajax-saving' : ''; ?>">
										<?php endif; ?>

											<?php if ( '' !== $this->sections && ! empty( $this->sections ) ) : ?>
												<div class="d-flex space-between mct-fix-mar ">
													<strong class="wp-header-inline"><?php echo esc_attr( $this->sections[ $k ] ); ?></strong>
													<div class="margin-bet">
														<?php wp_nonce_field( 'mct-' . $k, 'mct-' . $k . '-nonce' ); ?>
														<input type="hidden" name="mct-option_id" value="<?php echo esc_attr( $this->id ); ?>" />
														<input type="hidden" name="mct-form-options" value='<?php echo wp_json_encode( Helpers::get_main_key_options( $this->options ) ); ?>'>
														<button class="btn-secondary min-width-btn mct-back-btn"><?php esc_html_e( 'Back', 'moreconvert-compare-for-woocommerce' ); ?></button>
														<button class="btn-primary min-width-btn ico-btn check-btn mct-save-btn"
																name="mct-action"
																value="<?php echo esc_attr( $k ); ?>"
																type="submit"><?php esc_html_e( 'Save Settings', 'moreconvert-compare-for-woocommerce' ); ?></button>
														<button class="btn-secondary min-width-btn mct-reset-btn"
																name="mct-reset"
																value="<?php echo esc_attr( $k ); ?>"
																type="submit"><?php esc_html_e( 'Reset Settings', 'moreconvert-compare-for-woocommerce' ); ?></button>
													</div>
												</div>
												<hr>
											<?php endif; ?>

											<?php echo wp_kses_post( $container_sticky_start ); ?>

											<?php if ( isset( $option['tabs'] ) && is_array( $option['tabs'] ) && ! empty( $option['tabs'] ) ) : ?>
												<nav class="nav-tab-wrapper mct-tabs">
													<?php foreach ( $option['tabs'] as $i => $tab ) : ?>
														<?php
														if ( '' === $current_tab ) {
															$current_tab = ( ( empty( $this->sections ) ) && ( '' !== $active_tab ) ) || ( '' !== $active_section && $active_section === $k && '' !== $active_tab ) ? $active_tab : $i;
														}
														if ( isset( $option['fields'][ $i ]['type'] ) && 'class' === $option['fields'][ $i ]['type'] ) {
															$removable_query_args   = wp_removable_query_args();
															$removable_query_args[] = 'paged';
															$removable_query_args[] = '_wpnonce';
															$removable_query_args[] = '_wp_http_referer';
															if ( ! empty( $this->removable_query_args ) ) {
																$removable_query_args = array_merge( $removable_query_args, $this->removable_query_args );
															}
															$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
															$url         = add_query_arg(
																array(
																	'tab'  => $i,
																	'type' => 'class',
																),
																remove_query_arg( $removable_query_args, $request_uri ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
															);
															if ( $current_tab === $i ) {
																$type_class = 'class';
															}
															?>
															<a class="nav-tab external-link <?php echo ( $current_tab === $i ) ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_attr( $tab ); ?></a>
															<?php
														} else {
															if ( true === $type_class ) {
																$url = remove_query_arg( 'type' );
																$url = add_query_arg( 'tab', $i, $url );
															}
															?>
															<a class="nav-tab <?php echo ( true === $type_class ) ? 'external-link' : ''; ?> <?php echo ( $current_tab === $i ) ? 'nav-tab-active' : ''; ?>" href="<?php echo true === $type_class ? esc_url( $url ) : '#' . esc_attr( $i ); ?>"><?php echo esc_attr( $tab ); ?></a>
															<?php
														}
														?>

													<?php endforeach; ?>
												</nav>
											<?php endif; ?>
											<?php if ( isset( $option['tabs'] ) && is_array( $option['tabs'] ) && ! empty( $option['tabs'] ) ) : ?>
												<?php foreach ( $option['fields'] as $tabkey => $fields ) : ?>
													<?php
													if ( isset( $option['fields'][ $tabkey ]['type'] ) && 'class' === $option['fields'][ $tabkey ]['type'] ) {
														if ( is_callable( $option['fields'][ $tabkey ]['class'] ) && $current_tab === $tabkey ) {

															call_user_func( $option['fields'][ $tabkey ]['class'] );

														}
														continue;
													}
													?>
													<div class="mct-tab-content" id="<?php echo esc_attr( $tabkey ); ?>" <?php echo ( $current_tab === $tabkey ) ? '' : 'style="display: none"'; ?>>
														<?php
														// print table of fields.
														$this->print_table_fields( $k, $fields );
														?>
													</div>
												<?php endforeach; ?>
											<?php else : ?>
												<?php if ( isset( $option['fields'] ) && is_array( $option['fields'] ) && ! empty( $option['fields'] ) ) : ?>
													<div class="mct-tab-content">
														<?php
														// print table of fields.
														$this->print_table_fields( $k, $option['fields'] );
														?>
													</div>
												<?php endif; ?>

											<?php endif; ?>

											<?php echo wp_kses_post( $container_sticky_middle ); ?>

											<?php if ( ( empty( $this->sections ) ) && false === $type_class ) : ?>
												<div class="<?php echo esc_attr( $sticky_class ); ?>">
													<div class="d-flex space-between ">
														<?php wp_nonce_field( 'mct-' . $k, 'mct-' . $k . '-nonce' ); ?>
														<input type="hidden" name="mct-option_id" value="<?php echo esc_attr( $this->id ); ?>" />
														<input type="hidden" name="mct-form-options" value='<?php echo wp_json_encode( Helpers::get_main_key_options( $this->options ) ); ?>'>
														<button class="btn-primary min-width-btn ico-btn save-btn  mct-save-btn" name="mct-action" value="<?php echo esc_attr( $k ); ?>" type="submit">
														<svg width="21" x="0px" y="0px" viewBox="0 0 486 486" xml:space="preserve">
															<g>
																<path d="M473.7,485.75c6.8,0,12.3-5.5,12.3-12.3v-359.8c0-3.6-1.6-7-4.3-9.3L363,2.85c-0.2-0.2-0.4-0.3-0.6-0.4
																	c-0.3-0.2-0.5-0.4-0.8-0.6c-0.4-0.2-0.7-0.4-1.1-0.6c-0.3-0.1-0.6-0.3-0.9-0.4c-0.4-0.2-0.9-0.3-1.3-0.4c-0.3-0.1-0.6-0.2-0.9-0.2
																	c-0.8-0.1-1.5-0.2-2.3-0.2H12.3C5.5,0.05,0,5.55,0,12.35v461.3c0,6.8,5.5,12.3,12.3,12.3h461.4V485.75z M384.5,461.25h-283v-184.1
																	c0-3.7,3-6.6,6.6-6.6h269.8c3.7,0,6.6,3,6.6,6.6V461.25z M161.8,24.45h180.9v127.8c0,0.8-0.6,1.4-1.4,1.4h-178
																	c-0.8,0-1.4-0.7-1.4-1.4V24.45H161.8z M24.6,24.45h112.8v127.8c0,14.3,11.6,25.9,25.9,25.9h178c14.3,0,25.9-11.6,25.9-25.9V38.75
																	l94.2,80.6v341.9H409v-184.1c0-17.2-14-31.1-31.1-31.1H108.1c-17.2,0-31.1,14-31.1,31.1v184.2H24.6V24.45z"/>
																<path d="M227.4,77.65h53.8v32.6c0,6.8,5.5,12.3,12.3,12.3s12.3-5.5,12.3-12.3v-44.8c0-6.8-5.5-12.3-12.3-12.3h-66.1
																	c-6.8,0-12.3,5.5-12.3,12.3S220.7,77.65,227.4,77.65z"/>
																<path d="M304.5,322.85h-123c-6.8,0-12.3,5.5-12.3,12.3s5.5,12.3,12.3,12.3h123c6.8,0,12.3-5.5,12.3-12.3
																	S311.3,322.85,304.5,322.85z"/>
																<path d="M304.5,387.75h-123c-6.8,0-12.3,5.5-12.3,12.3s5.5,12.3,12.3,12.3h123c6.8,0,12.3-5.5,12.3-12.3
																	S311.3,387.75,304.5,387.75z"/>
															</g>
														</svg>
														<?php esc_html_e( 'Save Settings', 'moreconvert-compare-for-woocommerce' ); ?></button>
														<button class="btn-secondary min-width-btn  mct-reset-btn"
																name="mct-reset"
																value="<?php echo esc_attr( $k ); ?>"
																type="submit"><?php esc_html_e( 'Reset Settings', 'moreconvert-compare-for-woocommerce' ); ?></button>
													</div>
												</div>
											<?php endif; ?>

											<?php echo wp_kses_post( $container_sticky_end ); ?>

										<?php if ( true === $this->add_form_tag && false === $type_class ) : ?>
											</form>
										<?php endif; ?>
										<?php do_action( 'moreconvert_framework_end_mct_inside', $this ); ?>
									</div>
									<?php if ( true === $has_sidebar ) : ?>
										<div
											class="mct-sidebar <?php echo ( isset( $option['tabs'] ) && is_array( $option['tabs'] ) && ! empty( $option['tabs'] ) ) ? 'has-margin' : ''; ?>">
											<div class="mct-sidebar-inner">
												<?php foreach ( $this->sidebar as $sidebar ) : ?>
													<div class="mct-article <?php echo isset( $sidebar['class'] ) ? esc_attr( $sidebar['class'] ) : ''; ?>">
														<?php echo isset( $sidebar['image'] ) ? wp_kses_post( '<figure>' . $sidebar['image'] . '</figure>' ) : ''; ?>
														<?php echo isset( $sidebar['title'] ) ? wp_kses_post( '<h2 class="sidebar-title">' . $sidebar['title'] . '</h2>' ) : ''; ?>
														<?php echo isset( $sidebar['desc'] ) ? wp_kses_post( '<p>' . $sidebar['desc'] . '</p>' ) : ''; ?>
														<?php echo isset( $sidebar['content'] ) ? wp_kses_post( '<div class="sidebar-content">' . $sidebar['content'] . '</div>' ) : ''; ?>
														<?php if ( ! empty( $sidebar['button'] ) ) : ?>
															<p>
																<a target="<?php echo isset( $sidebar['button']['btn_target'] ) ? esc_attr( $sidebar['button']['btn_target'] ) : '_blank'; ?>" class="<?php echo esc_attr( $sidebar['button']['btn_class'] ); ?>" href="<?php echo esc_url( $sidebar['button']['btn_url'] ); ?>"><?php echo wp_kses_post( $sidebar['button']['btn_label'] ); ?></a>
															</p>
														<?php endif; ?>
														<?php if ( ! empty( $sidebar['buttons'] ) ) : ?>
															<?php foreach ( $sidebar['buttons'] as $button ) : ?>
																<p>
																	<a target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : '_blank'; ?>" class="<?php echo esc_attr( $button['btn_class'] ); ?>" href="<?php echo esc_url( $button['btn_url'] ); ?>"><?php echo wp_kses_post( $button['btn_label'] ); ?></a>
																</p>
															<?php endforeach; ?>
														<?php endif; ?>
														<?php echo isset( $sidebar['footer_content'] ) ? wp_kses_post( '<div class="sidebar-footer-content">' . $sidebar['footer_content'] . '</div>' ) : ''; ?>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
						<div class="mct-footer"></div>
					</div>
				<?php endif; ?>

				<?php if ( 'wizard-type' === $this->type && ! empty( $this->steps ) ) : ?>
					<?php
					// phpcs:disable WordPress.Security.NonceVerification.Recommended
					$current_step = isset( $_GET['step'] ) && '' !== $_GET['step'] ? sanitize_text_field( wp_unslash( $_GET['step'] ) ) : array_keys( $this->steps )[0];
					$action_url   = wp_nonce_url(
						add_query_arg(
							array(
								'step' => 'ready',
								'mct-' . $this->id . '-wizard-finish' => 1,
							)
						),
						'mct-' . $this->id . '-wizard-finish-nonce'
					);
					// phpcs:enable
					?>
					<div class="wrap mct-wrapper mct-wizard">
						<?php if ( isset( $this->title ) ) : ?>
							<div class="mct-header">
								<figure class="mct-logo d-flex margin-bet f-center no-mar">
									<?php echo isset( $this->logo ) ? wp_kses_post( $this->logo ) : ''; ?>
									<figcaption>
										<?php echo esc_attr( $this->title ); ?>
									</figcaption>
								</figure>
								<?php
								if ( isset( $this->header_menu ) && ! empty( $this->header_menu ) ) {
									$this->print_header_menu( $this->header_menu );
								}
								if ( isset( $this->header_buttons ) && ! empty( $this->header_buttons ) ) {
									$this->print_header_buttons( $this->header_buttons );}
								?>
							</div>
						<?php endif; ?>
						<div class="message-holder">
							<h1 class="wp-heading-inline no-mar"></h1>
							<?php $this->message(); ?>
						</div>
						<div class="mct-options ">
							<ul class="steps">
								<?php foreach ( $this->steps as $k => $step ) : ?>
									<li class="step step-<?php echo esc_attr( $k ); ?> <?php echo $current_step === $k ? 'step-success' : ''; ?> "
										data-step="<?php echo esc_attr( $k ); ?>">
										<div class="step-content">
											<span class="step-circle"><span
													class="dashicons dashicons-yes"></span></span>
											<span class="step-text"><?php echo esc_attr( $step['steptitle'] ); ?></span>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
							<form class="wizard-form mct-form <?php echo $this->ajax_saving ? 'mc-ajax-saving' : ''; ?>" method="post" action="<?php echo esc_url( $action_url ); ?>">
								<?php wp_nonce_field( 'mct-' . $this->id . '-wizard', 'mct-' . $this->id . '-wizard-nonce' ); ?>
								<input type="hidden" name="mct-option_id" value="<?php echo esc_attr( $this->id ); ?>" />
								<input type="hidden" name="mct-action-wizard" value="<?php echo esc_attr( $this->id ); ?>">
								<input type="hidden" name="mct-form-options" value='<?php echo wp_json_encode( $this->options ); ?>'>
								<?php foreach ( $this->steps as $k => $step ) : ?>
									<div id="wizard_content_<?php echo esc_attr( $k ); ?>" class="wizard-content mct-article <?php echo esc_attr( $k ); ?>" <?php echo ( $current_step !== $k ) ? 'style="display:none"' : ''; ?> >
										<?php
										if ( isset( $step['before_title'] ) ) {
											echo wp_kses_post( $step['before_title'] );
										}

										do_action( 'moreconvert_framework_wizard_before_title_step_field_' . $this->id . '_' . $k, $step );
										?>
										<h2>
											<?php echo wp_kses_post( $step['title'] ); ?>
										</h2>
										<?php if ( isset( $step['subtitle'] ) ) : ?>
											<p class="description subtitle">
												<small><?php echo wp_kses_post( $step['subtitle'] ); ?></small></p>
										<?php endif; ?>
										<?php if ( isset( $step['content'] ) ) : ?>
											<br>
											<div class="description content"><?php echo wp_kses_post( $step['content'] ); ?></div>
											<br>
										<?php endif; ?>
										<?php if ( isset( $step['top_desc'] ) ) : ?>
											<p class="description top-desc"><?php echo wp_kses_post( $step['top_desc'] ); ?></p>
											<hr>
										<?php endif; ?>
										<?php
										do_action( 'moreconvert_framework_wizard_before_step_field_' . $this->id . '_' . $k, $step );
										?>
										<?php
										if ( isset( $step['fields'] ) ) {
											$this->print_wizard_fields( $step['fields'] );
										}
										?>
										<?php
										do_action( 'moreconvert_framework_wizard_after_step_title_' . $this->id . '_' . $k, $step );
										?>
										<?php if ( isset( $step['bottom_desc'] ) ) : ?>
											<hr>
											<p class="description bottom-desc"><?php echo wp_kses_post( $step['bottom_desc'] ); ?></p>
										<?php endif; ?>

										<?php if ( isset( $step['buttons'] ) && ! empty( $step['buttons'] ) ) : ?>
											<div class="wizard-btns d-flex f-center ">
												<?php foreach ( $step['buttons'] as $button ) : ?>
													<a class="<?php echo isset( $button['btn_class'] ) ? esc_attr( $button['btn_class'] ) : 'btn-primary'; ?>" href="<?php echo esc_url( $button['btn_url'] ); ?>" target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : '_blank'; ?>"><?php echo wp_kses_post( $button['btn_label'] ); ?></a>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
										<?php if ( 'welcome' === $k ) : ?>
											<p class="center-align mar">
												<a href="" class="btn-primary ico-btn check-btn next-step"><?php esc_attr_e( 'Lets Go', 'moreconvert-compare-for-woocommerce' ); ?></a>
											</p>
											<p class="center-align">
												<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'mct-' . $this->id . '-wizard-skip', '1', admin_url() ), 'mct-' . $this->id . '-wizard-skip-nonce' ) ); ?>" class="btn-text"><?php esc_attr_e( 'NO, Not Ready Now', 'moreconvert-compare-for-woocommerce' ); ?></a>
											</p>
										<?php elseif ( 'ready' === $k ) : ?>
											<?php // do anything. ?>
										<?php else : ?>
											<p class="wizard-navigation  d-flex f-center">
												<a href="#" class="btn-text back-step <?php echo esc_attr( $k ); ?>"><?php esc_attr_e( 'Back to the previous step', 'moreconvert-compare-for-woocommerce' ); ?></a>
												<a href="#" class="btn-primary check-btn ico-btn greenlight-btn inverse-btn next-step <?php echo esc_attr( $k ); ?>"><?php esc_attr_e( 'Continue', 'moreconvert-compare-for-woocommerce' ); ?></a>
											</p>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</form>
							<?php if ( $this->back_to_dashboard ) : ?>
							<p class="center-align">
								<a class="btn-primary brown-btn " href="<?php echo 'ready' === $current_step ? esc_url( admin_url() ) : esc_url( wp_nonce_url( add_query_arg( 'mct-' . $this->id . '-wizard-skip', '1', admin_url() ), 'mct-' . $this->id . '-wizard-skip-nonce' ) ); ?>"><?php esc_attr_e( 'Back to WordPress Dashboard', 'moreconvert-compare-for-woocommerce' ); ?></a>
							</p>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( 'tools-type' === $this->type ) : ?>
					<div class="wrap mct-wrapper">
						<?php if ( isset( $this->title ) ) : ?>
							<div class="mct-header">
								<figure class="mct-logo d-flex margin-bet f-center no-mar">
									<?php echo isset( $this->logo ) ? wp_kses_post( $this->logo ) : ''; ?>
									<figcaption>
										<?php echo esc_attr( $this->title ); ?>
									</figcaption>
								</figure>
								<?php
								if ( isset( $this->header_menu ) && ! empty( $this->header_menu ) ) {
									$this->print_header_menu( $this->header_menu );
								}
								if ( isset( $this->header_buttons ) && ! empty( $this->header_buttons ) ) {
									$this->print_header_buttons( $this->header_buttons );}
								?>
							</div>
						<?php endif; ?>
						<div class="message-holder">
							<h1 class="wp-heading-inline no-mar"></h1>
							<?php $this->message(); ?>
						</div>
						<div class="mct-options">
							<div class="mct-article ">
								<div class="article-title">
									<h2><?php echo isset( $this->subtitle ) ? esc_attr( $this->subtitle ) : ''; ?></h2>
									<div
										class="description"><?php echo isset( $this->desc ) ? wp_kses_post( $this->desc ) : ''; ?></div>
								</div>
								<ul class="mct-tools">
									<?php foreach ( $this->tools as $k => $tool ) : ?>
										<li class="<?php echo isset( $tool['container_class'] ) ? esc_attr( $tool['container_class'] ) : ''; ?>">
											<a href="<?php echo isset( $tool['url'] ) ? esc_url( $tool['url'] ) : ''; ?>" data-modal="modal_<?php echo esc_attr( $k ); ?>" class="<?php echo isset( $tool['popup'] ) ? 'modal-toggle' : ''; ?>">
												<?php echo ! isset( $tool['url'] ) && isset( $tool['pro_label'] ) ? '<span class="is-pro">' . esc_attr( $tool['pro_label'] ) . '</span>' : ''; ?>
												<?php
												if ( isset( $tool['image'] ) ) {
													echo wp_kses_post( $tool['image'] );
												} elseif ( isset( $tool['image_url'] ) ) {
													echo '<img class="icon" src="' . esc_url( $tool['image_url'] ) . '"/>';
												}
												?>

												<span
													class="tool-title"><?php echo esc_attr( $tool['title'] ); ?></span>
												<?php if ( isset( $tool['desc'] ) && '' !== $tool['desc'] ) : ?>
													<p class="tool-desc">
														<?php echo wp_kses_post( $tool['desc'] ); ?>
													</p>
												<?php endif; ?>
											</a>
											<?php if ( isset( $tool['popup'] ) ) : ?>
												<div id="modal_<?php echo esc_attr( $k ); ?>" class="mct-modal <?php echo esc_attr( 'modal_' . $k ); ?>" style="display:none">
													<div class="modal-overlay modal-toggle" data-modal="modal_<?php echo esc_attr( $k ); ?>"></div>
													<div class="modal-wrapper modal-transition">
														<button class="modal-close modal-toggle"
																data-modal="modal_<?php echo esc_attr( $k ); ?>"><span
																class="dashicons dashicons-no-alt"></span></button>
														<div class="modal-body">
															<div class="modal-image">
																<img alt="<?php echo esc_attr( $tool['popup']['title'] ); ?>"
																	src="<?php echo esc_url( $tool['popup']['image_url'] ); ?>"/>
															</div>
															<div class="modal-content">
																<h2><?php echo esc_attr( $tool['popup']['title'] ); ?></h2>
																<p class="desc"><?php echo wp_kses_post( $tool['popup']['desc'] ); ?></p>
																<?php if ( isset( $tool['popup']['buttons'] ) && ! empty( $tool['popup']['buttons'] ) ) : ?>
																	<div class="modal-buttons">
																		<?php foreach ( $tool['popup']['buttons'] as $button ) : ?>
																			<a data-modal="modal_<?php echo esc_attr( $k ); ?>" class="<?php echo esc_attr( $button['btn_class'] ); ?>" href="<?php echo esc_url( $button['btn_url'] ); ?>" target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : '_blank'; ?>"><?php echo esc_attr( $button['btn_label'] ); ?></a>
																		<?php endforeach; ?>
																	</div>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?>

										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>

					</div>
				<?php endif; ?>

				<?php if ( 'class-type' === $this->type ) : ?>
					<div class="wrap mct-wrapper">
						<?php if ( isset( $this->title ) ) : ?>
							<div class="mct-header">
								<figure class="mct-logo d-flex margin-bet f-center no-mar">
									<?php echo isset( $this->logo ) ? wp_kses_post( $this->logo ) : ''; ?>
									<figcaption>
										<?php echo esc_attr( $this->title ); ?>
									</figcaption>
								</figure>
								<?php
								if ( isset( $this->header_menu ) && ! empty( $this->header_menu ) ) {
									$this->print_header_menu( $this->header_menu );
								}
								if ( isset( $this->header_buttons ) && ! empty( $this->header_buttons ) ) {
									$this->print_header_buttons( $this->header_buttons );}
								?>
							</div>
						<?php endif; ?>
						<div class="message-holder">
							<h1 class="wp-heading-inline no-mar"></h1>
							<?php $this->message(); ?>
						</div>
						<div class="mct-options">
							<?php
							if ( is_callable( $this->class_array ) ) {

								call_user_func( $this->class_array );

							}
							?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( 'ajax-wizard-type' === $this->type && ! empty( $this->steps ) ) : ?>
					<?php
					// phpcs:disable WordPress.Security.NonceVerification.Recommended
					$current_step = isset( $_GET['step'] ) && '' !== $_GET['step'] ? sanitize_text_field( wp_unslash( $_GET['step'] ) ) : array_keys( $this->steps )[0];
					// phpcs:enable
					?>
					<div class="mct-wizard">
						<div class="mct-options ">
							<?php if ( count( $this->steps ) > 1 ) : ?>
								<ul class="steps">
									<?php foreach ( $this->steps as $k => $step ) : ?>
										<li class="step step-<?php echo esc_attr( $k ); ?> <?php echo $current_step === $k ? 'step-success' : ''; ?> "
											data-step="<?php echo esc_attr( $k ); ?>">
											<div class="step-content">
												<span class="step-circle"><span
														class="dashicons dashicons-yes"></span></span>
												<span class="step-text"><?php echo esc_attr( $step['steptitle'] ); ?></span>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<div class="ajax-message-holder"></div>
							<form id="<?php echo esc_attr( $this->id ); ?>_form" class="ajax-wizard-form mct-form <?php echo $this->ajax_saving ? 'mc-ajax-saving' : ''; ?>" method="post"  action="">
								<?php foreach ( $this->steps as $k => $step ) : ?>
									<div id="wizard_content_<?php echo esc_attr( $k ); ?>" class="wizard-content mct-article <?php echo esc_attr( $k ); ?>" <?php echo ( $current_step !== $k ) ? 'style="display:none"' : ''; ?> >
										<div class="d-flex f-center space-between">
											<h2><?php echo esc_attr( $step['title'] ); ?></h2>
											<div class="d-flex f-center gap-5">
												<?php if ( isset( $step['doc'] ) ) : ?>
													<!-- MCT Document -->
													<a href="<?php echo esc_url( $step['doc'] ); ?>" target="_blank" class="btn-flat article-guide">
														<?php esc_attr_e( 'Section Guide', 'moreconvert-compare-for-woocommerce' ); ?>
														<?php if ( isset( $step['help'] ) ) : ?>
															<!-- MCT Help Tip -->
															<div class="mct-help-tip-wrap no-float">
																<span class="mct-help-tip-dec">
																	<?php if ( isset( $step['help_image'] ) && ! empty( $step['help_image'] ) ) : ?>
																		<img alt="img" src="<?php echo esc_url( $step['help_image'] ); ?>"/>
																	<?php endif; ?>
																	<p><?php echo wp_kses_post( $step['help'] ); ?></p>
																</span>
															</div>
														<?php endif; ?>
													</a>
												<?php elseif ( isset( $step['help'] ) ) : ?>
													<!-- MCT Help Tip -->
													<div class="mct-help-tip-wrap no-float">
														<span class="mct-help-tip-dec">
															<?php if ( isset( $step['help_image'] ) && ! empty( $step['help_image'] ) ) : ?>
																<img alt="img" src="<?php echo esc_url( $step['help_image'] ); ?>"/>
															<?php endif; ?>
															<p><?php echo wp_kses_post( $step['help'] ); ?></p>
														</span>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<?php if ( isset( $step['subtitle'] ) ) : ?>
											<p class="description subtitle"><small><?php echo wp_kses_post( $step['subtitle'] ); ?></small></p>
										<?php endif; ?>
										<?php if ( isset( $step['content'] ) ) : ?>
											<br>
											<div class="description content"><?php echo wp_kses_post( $step['content'] ); ?></div>
											<br>
										<?php endif; ?>
										<?php if ( isset( $step['top_desc'] ) ) : ?>
											<p class="description top-desc"><?php echo wp_kses_post( $step['top_desc'] ); ?></p>
											<hr>
										<?php endif; ?>
										<?php
										if ( isset( $step['fields'] ) ) {
											foreach ( $step['fields'] as $key => $field ) {
												$step['fields'][ $key ]['section'] = $this->id;
											}
											$this->print_wizard_fields( $step['fields'] );
										}
										?>
										<?php if ( isset( $step['bottom_desc'] ) ) : ?>
											<hr>
											<p class="description bottom-desc"><?php echo wp_kses_post( $step['bottom_desc'] ); ?></p>
										<?php endif; ?>
										<?php if ( isset( $step['buttons'] ) && ! empty( $step['buttons'] ) ) : ?>

											<div class="wizard-btns margin-bet d-flex f-center">
												<?php foreach ( $step['buttons'] as $button ) : ?>
													<?php
													$custom_attributes = array();
													if ( ! empty( $button['custom_attributes'] ) && is_array( $button['custom_attributes'] ) ) {
														foreach ( $button['custom_attributes'] as $attribute => $attribute_value ) {
															$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
														}
													}
													$custom_attributes = implode( ' ', $custom_attributes );
													?>
													<a class="<?php echo isset( $button['btn_class'] ) ? esc_attr( $button['btn_class'] ) : 'btn-primary'; ?>" href="<?php echo isset( $button['btn_url'] ) ? esc_url( $button['btn_url'] ) : ''; ?>" target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : ''; ?>"
														<?php echo wp_kses_post( $custom_attributes ); ?>
													><?php echo wp_kses_post( $button['btn_label'] ); ?></a>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</form>

							<?php if ( isset( $this->footer_buttons ) && ! empty( $this->footer_buttons ) ) : ?>
								<div class="mct-header-btns center-align">
									<?php foreach ( $this->footer_buttons as $button ) : ?>
										<a class="<?php echo esc_attr( $button['btn_class'] ); ?>" href="<?php echo esc_url( $button['btn_url'] ); ?>" target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : '_blank'; ?>"><?php echo esc_attr( $button['btn_label'] ); ?></a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

						</div>
					</div>
				<?php endif; ?>

				<?php if ( 'page-404-type' === $this->type ) : ?>

					<div class="mct-article mct-404-page">
						<div class="content-wrapper">
							<h2><?php echo isset( $this->subtitle ) ? esc_attr( $this->subtitle ) : ''; ?></h2>
							<div
								class="description"><?php echo isset( $this->subtitle ) ? wp_kses_post( $this->desc ) : ''; ?></div>
						</div>
						<div class="image-wrapper">
							<figure>
								<img src="<?php echo esc_url( $this->config['assets_url'] . '/img/404.svg' ); ?>" alt="404"/>
							</figure>
							<?php if ( isset( $this->page_buttons ) && ! empty( $this->page_buttons ) ) : ?>
								<div class="mct-404-btns center-align">
									<?php foreach ( $this->page_buttons as $button ) : ?>
										<a class="<?php echo esc_attr( $button['btn_class'] ); ?>" href="<?php echo esc_url( $button['btn_url'] ); ?>" target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : ''; ?>"><?php echo esc_attr( $button['btn_label'] ); ?></a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

				<?php endif; ?>

			<?php endif; ?>

			<?php
		}

		/**
		 * Print table of group fields
		 *
		 * @param string $section_key Section key.
		 * @param array  $fields Fields.
		 *
		 * @version 2.3.1
		 */
		public function print_table_fields( $section_key, $fields ) {
			?>
			<table class="form-table" role="presentation">
				<tbody>
				<?php foreach ( $fields as $fieldkey => $field ) : ?>
					<?php if ( isset( $field['type'] ) && ! in_array( $field['type'], array( 'end', 'separator', 'start', 'manage', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) ) : ?>

						<tr class="row-options row-<?php echo esc_attr( $fieldkey ); ?> <?php echo isset( $field['parent_class'] ) ? esc_attr( $field['parent_class'] ) : ''; ?>">
						<th scope="row">
							<?php echo isset( $field['label'] ) ? esc_attr( $field['label'] ) : ''; ?>
							<?php if ( isset( $field['help'] ) && ! empty( $field['help'] ) ) : ?>
								<!-- MCT Help Tip -->
								<div class="mct-help-tip-wrap">
									<span class="mct-help-tip-dec">
										<?php if ( isset( $field['help_image'] ) && ! empty( $field['help_image'] ) ) : ?>
											<img src="<?php echo esc_url( $field['help_image'] ); ?>" alt="<?php echo isset( $field['label'] ) ? esc_attr( $field['label'] ) : ''; ?>"/>
										<?php endif; ?>
										<p><?php echo esc_attr( $field['help'] ); ?></p>
									</span>
								</div>
							<?php endif; ?>
						</th>
						<td>

					<?php endif; ?>

					<?php $this->print_field( $section_key, $fieldkey, $field ); ?>

					<?php if ( isset( $field['type'] ) && ! in_array( $field['type'], array( 'end', 'separator', 'start', 'manage', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) ) : ?>

						</td>
						</tr>

					<?php endif; ?>

				<?php endforeach; ?>
				</tbody>
			</table>
			<?php
		}


		/**
		 * Print table of group wizard fields
		 *
		 * @param array $fields Fields.
		 *
		 * @since 2.3.1
		 */
		public function print_wizard_fields( $fields ) {
			?>
			<table class="form-table" role="presentation">
				<tbody>
				<?php foreach ( $fields as $fieldkey => $field ) : ?>

					<?php if ( ! in_array( $field['type'], array( 'hidden', 'separator', 'end', 'start', 'manage', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) ) : ?>

						<tr class="row-options row-<?php echo esc_attr( $fieldkey ); ?> <?php echo isset( $field['parent_class'] ) ? esc_attr( $field['parent_class'] ) : ''; ?>">
						<th scope="row">
							<?php echo isset( $field['label'] ) ? esc_attr( $field['label'] ) : ''; ?>
							<?php if ( isset( $field['help'] ) && ! empty( $field['help'] ) ) : ?>
								<!-- MCT Help Tip -->
								<div class="mct-help-tip-wrap">
									<span class="mct-help-tip-dec">
										<?php if ( isset( $field['help_image'] ) && ! empty( $field['help_image'] ) ) : ?>
											<img src="<?php echo esc_url( $field['help_image'] ); ?>" alt="<?php echo isset( $field['label'] ) ? esc_attr( $field['label'] ) : ''; ?>"/>
										<?php endif; ?>
										<p><?php echo esc_attr( $field['help'] ); ?></p>
									</span>
								</div>
							<?php endif; ?>
						</th>
						<td>
					<?php endif; ?>

					<?php $this->print_field( $field['section'] ?? '', $fieldkey, $field ); ?>

					<?php if ( ! in_array( $field['type'], array( 'hidden', 'end', 'separator', 'start', 'manage', 'iframe', 'column-start', 'column-end', 'columns-start', 'columns-end' ), true ) ) : ?>
						</td>
						</tr>

					<?php endif; ?>

				<?php endforeach; ?>
				</tbody>
			</table>
			<?php
		}


		/**
		 * Print fields
		 *
		 * @param string $section section key.
		 * @param string $name field key.
		 * @param array  $field array of field args.
		 *
		 * @return bool|void
		 */
		public function print_field( $section, $name, $field ) {

			if ( empty( $field['type'] ) ) {
				return false;
			}

			$field_template = $this->config['template_path'] . '/fields/' . sanitize_title( $field['type'] ) . '.php';

			$field_template = apply_filters( 'moreconvert_framework_get_field_template_path', $field_template, $field );

			if ( file_exists( $field_template ) ) {
				extract( $field ); // phpcs:ignore WordPress.PHP.DontExtract
				$custom_attributes = array();
				$dependencies      = '';
				if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
					foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
				}
				$custom_attributes = implode( ' ', $custom_attributes );
				$data              = isset( $field['data'] ) ? $this->html_data_to_string( $field['data'] ) : '';
				if ( 'group-fields' === $field['type'] ) {
					$groups_value = array();
					foreach ( $field['fields'] as $k => $v ) {
						$group_field_value  = $this->get_field_option( $section, $k, $v['type'] );
						$groups_value[ $k ] = ( '' === $group_field_value && ! $this->isset_field( $section, $k ) && isset( $v['default'] ) ) ? $v['default'] : $group_field_value;
					}
					$value = $groups_value;

				} else {
					$value = $this->get_field_option( $section, $name, $field['type'] );
					$value = ( '' === $value && ! $this->isset_field( $section, $name ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				}
				$field_id   = $field['id'] ?? $name;
				$class      = $field['class'] ?? '';
				$links      = $field['links'] ?? '';
				$options_id = $this->id;
				if ( isset( $field['remove_name'] ) && true === $field['remove_name'] ) {
					$name  = '';
					$value = $field['default'] ?? $value;
				}

				if ( isset( $field['dependencies'] ) ) {
					if ( isset( $field['dependencies']['id'] ) ) {
						$dependencies .= " data-deps='" . wp_json_encode(
							array(
								'id'    => esc_attr( $field['dependencies']['id'] ),
								'value' => esc_attr( $field['dependencies']['value'] ),
							)
						) . "'";
					} else {
						$dependencies .= " data-deps='" . wp_json_encode( $field['dependencies'] ) . "'";
					}
				}

				$assets_url   = $this->config['assets_url'];
				$template_dir = $this->config['template_path'];
				$mct_fields   = $this;
				include $field_template;
			}
		}

		/**
		 * Print repeater fields
		 *
		 * @param string       $section section key.
		 * @param string       $name repeater field name.
		 * @param int          $index index.
		 * @param string       $field_key field key.
		 * @param array        $field array of field args.
		 * @param string|array $value value of field.
		 *
		 * @return bool|void
		 */
		public function print_field_repeater( $section, $name, $index, $field_key, $field, $value = '' ) {

			if ( empty( $field['type'] ) ) {
				return false;
			}

			$field_template = $this->config['template_path'] . '/fields/' . sanitize_title( $field['type'] ) . '.php';

			$field_template = apply_filters( 'moreconvert_framework_get_field_template_path', $field_template, $field );

			if ( file_exists( $field_template ) ) {
				extract( $field ); // phpcs:ignore WordPress.PHP.DontExtract
				$custom_attributes = array();
				$dependencies      = '';
				if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
					foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
				}
				$custom_attributes = implode( ' ', $custom_attributes );
				$data              = isset( $field['data'] ) ? $this->html_data_to_string( $field['data'] ) : '';
				$field_id          = $name . '[' . $index . '][' . $field_key . ']';
				$class             = isset( $field['class'] ) ? $field_key . ' ' . $field['class'] : $field_key;
				$links             = $field['links'] ?? '';
				$name              = $name . '[' . $index . '][' . $field_key . ']';
				$options_id        = $this->id;

				if ( isset( $field['dependencies'] ) ) {
					$dependencies .= " data-repdeps='" . wp_json_encode( $field['dependencies'] ) . "'";
				}
				if ( isset( $field['remove_name'] ) && true === $field['remove_name'] ) {
					$name  = '';
					$value = $field['default'] ?? $value;
				}

				$assets_url   = $this->config['assets_url'];
				$template_dir = $this->config['template_path'];
				$mct_fields   = $this;
				include $field_template;
			}
		}

		/**
		 * Print repeater fields
		 *
		 * @param string       $section section key.
		 * @param string       $name repeater field name.
		 * @param int          $index index.
		 * @param string       $field_key field key.
		 * @param array        $field array of field args.
		 * @param string|array $value value of field.
		 *
		 * @return bool|void
		 * @since 1.1.0
		 */
		public function print_field_manage( $section, $name, $index, $field_key, $field, $value = '' ) {

			if ( empty( $field['type'] ) ) {
				return false;
			}

			$field_template = $this->config['template_path'] . '/fields/' . sanitize_title( $field['type'] ) . '.php';

			$field_template = apply_filters( 'moreconvert_framework_get_field_template_path', $field_template, $field );

			if ( file_exists( $field_template ) ) {
				extract( $field ); // phpcs:ignore WordPress.PHP.DontExtract
				$custom_attributes = array();
				$dependencies      = '';
				if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
					foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
				}
				$custom_attributes = implode( ' ', $custom_attributes );
				$data              = isset( $field['data'] ) ? $this->html_data_to_string( $field['data'] ) : '';
				$field_id          = $name . '_' . $index . '_' . $field_key;
				$class             = $field['class'] ?? '';
				$links             = $field['links'] ?? '';
				$options_id        = $this->id;

				if ( isset( $field['dependencies'] ) ) {
					$dependencies .= ' data-mngdeps="' . esc_attr( $name . '_' . $index . '_' . $field['dependencies']['id'] ) . '"';
					$dependencies .= ' data-deps-value="' . esc_attr( $field['dependencies']['value'] ) . '"';
				} elseif ( isset( $field['parent_dependencies'] ) ) {
					if ( isset( $field['parent_dependencies']['id'] ) ) {
						$dependencies .= " data-deps='" . wp_json_encode(
							array(
								'id'    => esc_attr( $field['parent_dependencies']['id'] ),
								'value' => esc_attr( $field['parent_dependencies']['value'] ),
							)
						) . "'";
					} else {
						$dependencies .= " data-deps='" . wp_json_encode( $field['parent_dependencies'] ) . "'";
					}
				}
				$name = $name . '[' . $index . '][' . $field_key . ']';
				if ( isset( $field['remove_name'] ) && true === $field['remove_name'] ) {
					$name  = '';
					$value = $field['default'] ?? $value;
				}

				$assets_url   = $this->config['assets_url'];
				$template_dir = $this->config['template_path'];
				$mct_fields   = $this;
				include $field_template;

			}
		}

		/**
		 * Print header buttons
		 *
		 * @param array $header_buttons header buttons.
		 *
		 * @return void
		 */
		public function print_header_buttons( $header_buttons ) {
			?>
			<div class="mct-header-btns">
				<?php foreach ( $header_buttons as $button ) : ?>
					<a class="<?php echo esc_attr( $button['btn_class'] ); ?>" href="<?php echo esc_url( $button['btn_url'] ); ?>" target="<?php echo isset( $button['btn_target'] ) ? esc_attr( $button['btn_target'] ) : '_blank'; ?>"><?php echo wp_kses_post( $button['btn_label'] ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php
		}

		/**
		 * Print header menu
		 *
		 * @param array $header_menu header menu.
		 *
		 * @return void
		 */
		public function print_header_menu( $header_menu ) {
			if ( ! empty( $_SERVER['HTTP_HOST'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
				$protocol    = is_ssl() ? 'https://' : 'http://';
				$current_url = esc_url_raw( $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );// phpcs:ignore
				$current_url = esc_url_raw( remove_query_arg( 'tab', $current_url ) );
			} else {
				$current_url = esc_url_raw( remove_query_arg( 'tab' ) );
			}
			?>
			<div class="mct-hamburger-icon">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<nav class="mct-header-menu">
				<ul class="mct-menu">
					<?php foreach ( $header_menu as $nav_item ) : ?>
						<?php
						$nav_classes = array();
						if ( isset( $nav_item['class'] ) ) {
							$nav_classes[] = $nav_item['class'];
						}
						if ( isset( $nav_item['disabled'] ) && $nav_item['disabled'] ) {
							$nav_classes[] = 'disabled';
						}
						if ( isset( $nav_item['url'] ) && $nav_item['url'] === $current_url ) {
							$nav_classes[] = 'active';
						}
						if ( isset( $nav_item['submenu'] ) && ! empty( $nav_item['submenu'] ) ) {
							$nav_classes[] = 'mct-has-submenu';
						}
						?>
						<li class="<?php echo esc_attr( implode( ' ', $nav_classes ) ); ?>">
							<?php if ( isset( $nav_item['disabled'] ) && $nav_item['disabled'] ) : ?>
								<span class="disabled"><?php echo esc_html( $nav_item['text'] ); ?></span>
								<span class="badge"><?php echo esc_html( $nav_item['disabled_text'] ); ?></span>
							<?php elseif ( isset( $nav_item['submenu'] ) && ! empty( $nav_item['submenu'] ) ) : ?>
								<a class="toggle-submenu" href="<?php echo isset( $nav_item['url'] ) ? esc_url( $nav_item['url'] ) : '#'; ?>">
									<span><?php echo esc_html( $nav_item['text'] ); ?></span>
									<span class="dashicons dashicons-arrow-down-alt2"></span>
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $nav_item['url'] ); ?>" target="<?php echo isset( $nav_item['target'] ) ? esc_attr( $nav_item['target'] ) : '_self'; ?>">
									<span><?php echo esc_html( $nav_item['text'] ); ?></span>
								</a>
							<?php endif; ?>
							<?php if ( isset( $nav_item['submenu'] ) ) : ?>
								<ul class="mct-submenu">
									<?php foreach ( $nav_item['submenu'] as $subnav_item ) : ?>
										<?php
										$subnav_classes = array();
										if ( isset( $subnav_item['class'] ) ) {
											$subnav_classes[] = $subnav_item['class'];
										}
										if ( isset( $subnav_item['disabled'] ) && $subnav_item['disabled'] ) {
											$subnav_classes[] = 'disabled';
										}
										if ( isset( $subnav_item['url'] ) && $subnav_item['url'] === $current_url ) {
											$subnav_classes[] = 'active';
										}
										?>
										<li class="<?php echo esc_attr( implode( ' ', $subnav_classes ) ); ?>">
											<?php if ( isset( $subnav_item['disabled'] ) && $subnav_item['disabled'] ) : ?>
												<span class="disabled"><?php echo esc_html( $subnav_item['text'] ); ?></span>
												<span class="badge"><?php echo esc_html( $subnav_item['disabled_text'] ); ?></span>
											<?php else : ?>
												<a href="<?php echo esc_url( $subnav_item['url'] ); ?>" target="<?php echo isset( $subnav_item['target'] ) ? esc_attr( $subnav_item['target'] ) : '_self'; ?>"><?php echo esc_html( $subnav_item['text'] ); ?></a>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<?php
		}

		/**
		 * Get single field option value.
		 *
		 * @param string $section Section.
		 * @param string $field field_key.
		 * @param string $field_type field_type.
		 *
		 * @return mixed|string
		 */
		public function get_field_option( $section, $field, $field_type ) {
			$options = $this->saved_options;
			return isset( $options[ $section ] ) && isset( $options[ $section ][ $field ] ) ? ( in_array(
				$field_type,
				array(
					'checkbox',
					'switch',
				),
				true
			) && '' === $options[ $section ][ $field ] ? '0' : $options[ $section ][ $field ] ) : '';
		}

		/**
		 * Get isset field in options
		 *
		 * @param string $section Section.
		 * @param string $field field_key.
		 *
		 * @return bool
		 * @since 1.3.2
		 */
		public function isset_field( $section, $field ) {
			$options = $this->saved_options;
			return isset( $options[ $section ] ) && isset( $options[ $section ][ $field ] );
		}

		/**
		 * Get option.
		 *
		 * @return mixed|void
		 * @version 2.1.1
		 */
		public function get_option() {
			return apply_filters( 'moreconvert_framework_get_option', false !== $this->field_values ? array( 'values' => $this->field_values ) : get_option( $this->id, array() ), $this->id );
		}

		/**
		 * Convert Html data attribute to string.
		 *
		 * @param array $data Data attributes.
		 * @param bool  $echo Echo or not.
		 *
		 * @return string|void
		 */
		private function html_data_to_string( $data = array(), bool $echo = false ) {
			$html_data = '';

			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					$data_attribute = "data-{$key}";
					$data_value     = ! is_array( $value ) ? $value : implode( ',', $value );

					$html_data .= ' ' . esc_attr( $data_attribute ) . '="' . esc_attr( $data_value ) . '"';
				}
				$html_data .= ' ';
			}

			if ( $echo ) {
				echo wp_kses_post( $html_data );
			} else {
				return $html_data;
			}
		}

		/**
		 * Message
		 * define an array of message and show the content od message if
		 * is find in the query string
		 */
		public function message() {
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			$message = apply_filters(
				'moreconvert_framework_panel_messages',
				array(
					'saved'        => $this->get_message( '<strong>' . __( 'Settings saved.', 'moreconvert-compare-for-woocommerce' ) . '</strong>', 'updated', false ),
					'reset'        => $this->get_message( '<strong>' . __( 'Settings reset.', 'moreconvert-compare-for-woocommerce' ) . '</strong>', 'updated', false ),
					'not-validate' => $this->get_message( '<strong>' . __( 'Some fields not validate.', 'moreconvert-compare-for-woocommerce' ) . '</strong>', 'error', false ),
					'not-access'   => $this->get_message( '<strong>' . __( 'You cannot access to save changes.', 'moreconvert-compare-for-woocommerce' ) . '</strong>', 'error', false ),
				)
			);
			$output  = '';
			if ( ! empty( $message ) ) {
				foreach ( $message as $key => $value ) {
					if ( isset( $_GET[ $key ] ) ) {
						$output .= wp_kses_post( $value );
					}
				}
			}
			if ( '' !== $output ) {
				echo wp_kses_post( '<div class="message-holder"><h1 class="wp-heading-inline no-mar"></h1>' . $output . '</div>' );
			}

			// phpcs:enable
		}

		/**
		 * Get Message
		 * return html code of message
		 *
		 * @param string $message The message.
		 * @param string $type The type of message (can be 'error' or 'updated').
		 * @param bool   $should_print Set to true if you want to print the message.
		 *
		 * @return string
		 */
		public function get_message( string $message, string $type = 'error', bool $should_print = true ) {
			$message = '<div id="message" class="' . esc_attr( $type ) . ' fade"><p>' . wp_kses_post( $message ) . '</p></div>';
			if ( $should_print ) {
				echo wp_kses_post( $message );
			}

			return $message;
		}
	}
}
