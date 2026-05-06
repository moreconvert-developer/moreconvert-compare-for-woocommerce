/**
 * Mc Option scripts
 *
 * @author MoreConvert
 * @package
 * @version 2.5.6
 */

// phpcs:disable
(function ($) {
	$.noConflict();
	const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
	const mcValidateField = function (object) {
		if (object.is(':visible') && object.hasClass('validate')) {
			if (object[0].checkValidity()) {
				object.removeClass('invalid');
				object.addClass('valid');
				object.siblings('.error').remove();
			} else {
				object.removeClass('valid');
				object.addClass('invalid');
				if (!object.siblings('.error').length) {
					const message = object.data('error')
						? object.data('error')
						: object[0].validationMessage;
					$(
						'<label class="error">' + message + '</label>'
					).insertAfter(object);
				}
			}
		} else {
			object.removeClass('valid');
			object.removeClass('invalid');
			object.siblings('.error').remove();
		}
		/*if (
			object[0].validity.badInput === false &&
			! object.is( ":required" ) || ! object.is( ':visible' )
		) {
			if (object.hasClass( "validate" )) {
				object.removeClass( "valid" );
				object.removeClass( "invalid" );
			}
		} else {
			if (object.hasClass( "validate" )) {
				// Check for character counter attributes.
				if (object.is( ":valid" )) {
					object.removeClass( "invalid" );
					object.addClass( "valid" );
				} else {
					object.removeClass( "valid" );
					object.addClass( "invalid" );
					if ( ! object.siblings( '.error' ).length) {
						$( '<label class="error">' + object.data( 'error' ) + '</label>' ).insertAfter( object );
					}
				}
			}
		}*/
	};

	$(function () {
		function initValidate() {
			/**
			 * Validate input and textarea
			 *
			 * @type {string}
			 */
			// Text based inputs.
			const inputSelector =
				'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], input[type=date], input[type=time], textarea';

			$(document).on(
				'change input cut copy paste',
				inputSelector,
				function () {
					mcValidateField($(this));
				}
			);
			// HTML DOM FORM RESET handling.
			$(document).on('reset', function (e) {
				const formReset = $(e.target);
				if (formReset.is('form')) {
					formReset
						.find(inputSelector)
						.removeClass('valid')
						.removeClass('invalid');
				}
			});

			document.addEventListener(
				'blur',
				function (e) {
					const $inputElement = $(e.target);
					if ($inputElement.is(inputSelector)) {
						mcValidateField($inputElement);
					}
				},
				true
			);
		}

		$(document.body)
			.on('mc-sidebar-init', function () {
				if ($('.mct-sidebar').length > 0) {
					const msie6 = $.browser === 'msie' && $.browser.version < 7;

					if (!msie6 && $('.mct-sidebar').offset() !== null) {
						if ($(window).width() > 1200) {
							const sectionHeight =
								$('.mct-section-wrapper').offset() !== null &&
								!$('.mct-section-wrapper').is(':hidden')
									? $('.mct-section-wrapper').height()
									: 0;

							$('.mct-options').each(function (index, elem) {
								const sidebar = $(elem).find('.mct-sidebar');

								if (sidebar.offset() !== null) {
									const topSidebar =
											sidebar.offset().top -
											parseFloat(
												$(elem)
													.css('margin-top')
													.replace(/auto/, 0)
											),
										height = $(elem)
											.find('.mct-sidebar-inner')
											.height(),
										winHeight = $(window).height(),
										offsetbottom = 100,
										top = topSidebar - sectionHeight;

									$(window).on('scroll', function () {
										const y = $(this).scrollTop();
										const footerTop =
											$('.mct-footer').offset().top -
											parseFloat(
												$('.mct-footer')
													.css('margin-top')
													.replace(/auto/, 0)
											);

										if (
											$(window).width() > 1200 &&
											y > top
										) {
											const insideHeight = $(
													'.mct-inside:visible .mct-inside-inner'
												).height(),
												p =
													y + height + offsetbottom >
													footerTop
														? -1 *
															(height +
																offsetbottom -
																(footerTop -
																	(y +
																		winHeight)) -
																winHeight)
														: '60px';

											if (height < insideHeight) {
												sidebar
													.addClass('sidebarfixed')
													.css({
														top: p,
														width: '300px',
													});
											} else {
												sidebar
													.removeClass('sidebarfixed')
													.removeAttr('style');
											}
										} else {
											sidebar
												.removeClass('sidebarfixed')
												.removeAttr('style');
										}
									});
								}
							});
						} else {
							$('.mct-sidebar')
								.removeClass('sidebarfixed')
								.removeAttr('style');
						}
					}
				}
			})
			.trigger('mc-sidebar-init');

		$(document.body).on('mc-wizard-next-step', function () {
			const elem = $('.next-step:visible');
			const inputSelector =
				'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], input[type=date], input[type=time], textarea';

			const inputFields = elem
				.closest('.wizard-content')
				.find(inputSelector);
			$(inputFields)
				.each(function (index, item) {
					mcValidateField($(item));
				})
				.promise()
				.done(function () {
					const invalidFields = elem
						.closest('.wizard-content')
						.find('.invalid');
					if (!invalidFields.length) {
						const step = $('.step-success'),
							currentStep = step.data('step'),
							nextStep = step.next().attr('data-step');
						$('.step-' + currentStep).removeClass('step-success');
						$('.step-' + nextStep).addClass('step-success');
						$('.wizard-content').hide();
						$('.wizard-content.' + nextStep).show();
						window.history.replaceState(
							'',
							'',
							updateURLParameter(
								window.location.href,
								'step',
								nextStep
							)
						);
					}
				});
		});
		$(document.body).on('mc-wizard-back-step', function () {
			const step = $('.step-success'),
				currentStep = step.data('step'),
				prevStep = step.prev().attr('data-step');
			$('.step-' + currentStep).removeClass('step-success');
			$('.step-' + prevStep).addClass('step-success');
			$('.wizard-content').hide();
			$('.wizard-content.' + prevStep).show();
			window.history.replaceState(
				'',
				'',
				updateURLParameter(window.location.href, 'step', prevStep)
			);
		});

		$(document.body).on(
			'click',
			'form.mc-ajax-saving .mct-save-btn',
			function (event) {
				event.preventDefault();

				const currentForm = $(this).closest('form').eq(0),
					elem = $(this);

				if (currentForm[0].checkValidity()) {
					if (typeof tinyMCE !== 'undefined') {
						tinyMCE.triggerSave(true, true);
					}
					$.ajax({
						url: mctAdminParams.ajax_url,
						type: 'POST',
						dataType: 'json',
						data: {
							action: mctAdminParams.plugin_id + '_ajax_saving',
							_wpnonce: mctAdminParams.ajax_nonce,
							data: encodeURIComponent(currentForm.serialize()),
						},
						beforeSend() {
							elem.addClass('loading');
						},
						complete() {
							elem.removeClass('loading');
						},
						success(response) {
							if (
								response &&
								response.data &&
								response.data.message
							) {
								const alertType =
									response.success &&
									true === response.success
										? 'success'
										: 'error';
								showSnack(response.data.message, alertType);
							}
						},
					});
				} else {
					// If the form is invalid, find the first invalid field and scroll to it.
					const invalidField = currentForm.find(':invalid')[0];
					const tabPane = $(invalidField).closest('.mct-tab-content');
					const tabLink = $(
						'a.nav-tab:not(.nav-tab-active)[href="#' +
							tabPane.attr('id') +
							'"]'
					);
					if (tabLink.length) {
						tabLink.click();
					}
					$('html, body').animate(
						{
							scrollTop: $(invalidField).offset().top - 100,
						},
						'slow'
					);
				}

				return false;
			}
		);

		function initWizard() {
			$('body').on(
				'click',
				'.mct-wizard .last.next-step:not(.modal-toggle)',
				function (event) {
					event.stopPropagation();
					event.preventDefault();
					$('.wizard-form').submit();
					return false;
				}
			);
			$('body').on(
				'click',
				'.mct-wizard .back-step:not(.modal-toggle)',
				function (event) {
					event.preventDefault();
					$(document.body).trigger('mc-wizard-back-step');
					return false;
				}
			);
			$('body').on(
				'click',
				'.mct-wizard .next-step:not(.last):not(.modal-toggle)',
				function (event) {
					event.preventDefault();
					$(document.body).trigger('mc-wizard-next-step');
					return false;
				}
			);

			$('body').on(
				'click',
				'.mct-wizard li.step:not(.modal-toggle)',
				function (event) {
					event.preventDefault();
					$(this)
						.closest('.steps')
						.find('.step')
						.removeClass('step-success');

					const currentStep = $(this).data('step');
					$('.step-' + currentStep).addClass('step-success');
					$('.wizard-content').hide();
					$('.wizard-content.' + currentStep).show();
					window.history.replaceState(
						'',
						'',
						updateURLParameter(
							window.location.href,
							'step',
							currentStep
						)
					);

					return false;
				}
			);
		}

		function initColorpicker() {
			const setColorOpacity = function (colorStr, opacity) {
				let rgbaCol;
				if (colorStr.indexOf('rgb(') === 0) {
					rgbaCol = colorStr.replace('rgb(', 'rgba(');
					rgbaCol = rgbaCol.replace(')', ', ' + opacity + ')');
					return rgbaCol;
				}

				if (colorStr.indexOf('rgba(') === 0) {
					rgbaCol =
						colorStr.substr(0, colorStr.lastIndexOf(',') + 1) +
						opacity +
						')';
					return rgbaCol;
				}

				if (colorStr.length === 6) {
					colorStr = '#' + colorStr;
				}

				if (colorStr.indexOf('#') === 0) {
					rgbaCol =
						'rgba(' +
						parseInt(colorStr.slice(-6, -4), 16) +
						',' +
						parseInt(colorStr.slice(-4, -2), 16) +
						',' +
						parseInt(colorStr.slice(-2), 16) +
						',' +
						opacity +
						')';
					return rgbaCol;
				}
				return colorStr;
			};

			// Add Color Picker to all inputs that have 'mct-color-picker' class.
			$('.mct-color-picker').wpColorPicker({
				change(event, ui) {
					const color = setColorOpacity(
						ui.color.toString(),
						ui.color._alpha
					);
					const background =
						'linear-gradient(' +
						color +
						' 0%, ' +
						color +
						' 100%) repeat scroll 0% 0% ,url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==") repeat scroll 0% 0%';
					$(this)
						.closest('.wp-picker-container')
						.find('.wp-color-result')
						.css('background-color', '#fff');
					$(this)
						.closest('.wp-picker-container')
						.find('.color-alpha')
						.css('background', background);
					$(this)
						.closest('.wp-picker-container')
						.find('.color-alpha')
						.css('border-color', color);
				},
				clear() {
					$(this)
						.closest('.wp-picker-container')
						.find('.wp-color-result')
						.css('background-color', '#fff');
					$(this)
						.closest('.wp-picker-container')
						.find('.color-alpha')
						.css('border-color', '#e4dbd0');
					$(this)
						.closest('.wp-picker-container')
						.find('.color-alpha')
						.css('background', 'none');
				},
			});
			$('.mct-wrapper .wp-picker-container').each(function (index, elem) {
				const border = $(elem).find('.wp-color-picker').val();

				if ('rgb(255, 255, 255)' !== border) {
					const background =
						'linear-gradient(' +
						border +
						' 0%, ' +
						border +
						' 100%) repeat scroll 0% 0% ,url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==") repeat scroll 0% 0%';
					$(elem)
						.closest('.wp-picker-container')
						.find('.color-alpha')
						.css('border-color', border);
					$(elem)
						.closest('.wp-picker-container')
						.find('.color-alpha')
						.css('background', background);
					$(elem)
						.find('.wp-color-result')
						.css('background-color', '#fff');
				}
			});
			$('.mct-wrapper .wp-picker-container').on(
				'click.wpcolorpicker',
				function (event) {
					const elem = $(event.currentTarget),
						offset = elem.offset(),
						pagewidth = $(document).width(),
						pickerwidth = 260;

					if ($('body').hasClass('rtl')) {
						if (offset.left < pickerwidth) {
							elem.find('.wp-picker-holder').css('left', '0px');
							elem.find('.wp-picker-input-wrap').css(
								'left',
								'0px'
							);
						} else {
							elem.find('.wp-picker-holder').css('right', '0px');
							elem.find('.wp-picker-input-wrap').css(
								'right',
								'0px'
							);
						}
					} else if (offset.left + pickerwidth > pagewidth) {
						elem.find('.wp-picker-holder').css('right', '0px');
						elem.find('.wp-picker-input-wrap').css('right', '0px');
					} else {
						elem.find('.wp-picker-holder').css('left', '0px');
						elem.find('.wp-picker-input-wrap').css('left', '0px');
					}
				}
			);
		}

		function initSelect2() {
			$('.select2-trigger').select2({
				width: '100%',
				minimumResultsForSearch: 8,
			});
			$(document.body).trigger('wc-enhanced-select-init');
			const showLinks = $('.page-show-links-trigger');
			showLinks.each(function () {
				const currentElement = $(this),
					parent = currentElement.closest('div'),
					target = parent.find('.page-show-links-target');
				if (target.length) {
					if (currentElement.hasClass('select2-hidden-accessible')) {
						currentElement.on('select2:select', function () {
							const selectedOption = $(this)
								.find('option:selected')
								.val();
							target.addClass('hidden-option');
							parent
								.find('[data-page-id="' + selectedOption + '"]')
								.removeClass('hidden-option');
						});
					} else {
						currentElement.on('change', function () {
							const selectedOption = $(this).val();
							target.addClass('hidden-option');
							parent
								.find('[data-page-id="' + selectedOption + '"]')
								.removeClass('hidden-option');
						});
					}
				}
			});
			const iconSelect = $('.select-icon');
			iconSelect.each(function () {
				const t = $(this),
					renderOption = function (state) {
						if (!state.id || !$(state.element).data('image')) {
							return state.text;
						}
						return $(
							'<span class="d-flex f-center space-between">' +
								state.text +
								$(state.element).data('image') +
								'</span>'
						);
					};

				t.select2({
					templateResult: renderOption,
				});
			});
		}

		function initDatePicker() {
			const daterangepicker = $('.mct-daterangepicker'),
				datepicker = $('.mct-datepicker'),
				parentEl = $('#wpbody'),
				opens = $('body').hasClass('rtl') ? 'left' : 'right';
			if (datepicker) {
				datepicker.each(function () {
					const element = $(this);
					element.daterangepicker({
						singleDatePicker: true,
						parentEl: parentEl.length > 0 ? parentEl : $('body'),
						opens,
					});
				});
			}
			if (daterangepicker) {
				daterangepicker.each(function () {
					const element = $(this);
					element.daterangepicker({
						autoUpdateInput: false,
						locale: {
							applyLabel:
								mctAdminParams.range_datepicker.applyLabel,
							cancelLabel:
								mctAdminParams.range_datepicker.cancelLabel,
							customRangeLabel:
								mctAdminParams.range_datepicker
									.customRangeLabel,
						},
						ranges: {
							[mctAdminParams.range_datepicker.last_7_days]: [
								moment().subtract(6, 'days'),
								moment(),
							],
							[mctAdminParams.range_datepicker.last_30_days]: [
								moment().subtract(29, 'days'),
								moment(),
							],
							[mctAdminParams.range_datepicker.last_90_days]: [
								moment().subtract(89, 'days'),
								moment(),
							],
							[mctAdminParams.range_datepicker.last_365_days]: [
								moment().subtract(364, 'days'),
								moment(),
							],
						},
						alwaysShowCalendars: true,
						parentEl: parentEl.length > 0 ? parentEl : $('body'),
						opens,
					});
					element.on('apply.daterangepicker', function (ev, picker) {
						$(this).val(
							picker.startDate.format('MM/DD/YYYY') +
								' - ' +
								picker.endDate.format('MM/DD/YYYY')
						);
					});
					element.on('cancel.daterangepicker', function () {
						$(this).val('');
					});
				});
			}
		}

		function initSearchPost() {
			$('.mc-post-search')
				.filter(':not(.enhanced)')
				.each(function () {
					const placeholder = $(this).data('placeholder'),
						minimumInputLength = $(this).data(
							'minimum_input_length'
						);
					$(this).select2({
						ajax: {
							url: mctAdminParams.search_post_url,
							dataType: 'json',
							delay: 250,
							beforeSend(xhr) {
								xhr.setRequestHeader(
									'X-WP-Nonce',
									mctAdminParams.nonce
								);
							},
							data(params) {
								return {
									search_term: params.term,
								};
							},
							processResults(data) {
								return {
									results: data,
								};
							},
							cache: true,
						},
						minimumInputLength,
						placeholder,
					});
				});
		}

		function initSearchUser() {
			$('.mc-user-search')
				.filter(':not(.enhanced)')
				.each(function () {
					const placeholder = $(this).data('placeholder'),
						minimumInputLength = $(this).data(
							'minimum_input_length'
						);
					$(this).select2({
						ajax: {
							url: mctAdminParams.search_user_url,
							dataType: 'json',
							delay: 250,
							beforeSend(xhr) {
								xhr.setRequestHeader(
									'X-WP-Nonce',
									mctAdminParams.nonce
								);
							},
							data(params) {
								return {
									search_term: params.term,
								};
							},
							processResults(data) {
								return {
									results: data,
								};
							},
							cache: true,
						},
						minimumInputLength,
						placeholder,
					});
				});
		}

		function initCssEditor() {
			$('.mct-code-editor').each(function () {
				const textarea = $(this)[0];
				const type = $(this).data('type').trim();
				const height = $(this).data('height').trim() || 'auto';
				let mode = 'text/plain';

				if (type === 'css') {
					mode = 'css';
				} else if (type === 'php') {
					mode = 'php';
				} else if (type === 'javascript' || type === 'js') {
					mode = 'javascript';
				} else if (type === 'html') {
					mode = 'htmlmixed';
				} else if (type === 'json') {
					mode = 'application/json';
				}

				const editorSettings = {
					type,
					codemirror: {
						mode,
						styleActiveLine: true,
						gutters: [
							'CodeMirror-linenumbers',
							'CodeMirror-lint-markers',
						],
						lineNumbers: true,
						autoCloseBrackets: true,
						matchBrackets: true,
						viewportMargin: Infinity,
						height,
						autoRefresh: true,
					},
				};
				if (type === 'php') {
					editorSettings.codemirror.extraKeys = {
						'Ctrl-Space': 'autocomplete',
					};
				} else {
					editorSettings.codemirror.lint = true;
				}
				const editor = wp.codeEditor.initialize(
					textarea,
					editorSettings
				);
				editor.codemirror.on('change', function () {
					textarea.value = editor.codemirror.getValue();
				});
			});
		}

		$('.mct-repeater.simple-repeater').repeater({
			limitMessage: mctAdminParams.i18n_limit_repeater_alert,
			show() {
				$(this)
					.find('.btn-translation')
					.css({ opacity: 0, 'pointer-events': 'none' });
				$(this).slideDown();

				$.each(
					$(this).find('.wp-picker-container'),
					function (index, elem) {
						const field = $(elem).find('.mct-color-picker').clone();
						$(elem).before(field);
					}
				);
				$(this).find('.wp-picker-container').remove();

				$(this)
					.find('.select2-hidden-accessible')
					.removeClass('enhanced')
					.removeClass('select2-hidden-accessible');
				$(this).find('.select2-container').remove();

				initColorpicker();
				initRepeaterDependencies();
				initOptgroupDependencies();
				initSelect2();
				initSearchPost();
				initSearchUser();
				initDatePicker();
			},
			hide(deleteElement) {
				if (confirm(mctAdminParams.i18n_delete_repeater_confirm)) {
					$(this).slideUp(deleteElement);
				}
			},
			ready() {},
		});

		$('.mct-repeater.nested-repeater').repeater({
			limitMessage: mctAdminParams.i18n_limit_repeater_alert,
			repeaters: [
				{
					// (Required)
					// Specify the jQuery selector for this nested repeater
					selector: '.inner-repeater',
					show() {
						$(this).slideDown();

						$.each(
							$(this).find('.wp-picker-container'),
							function (index, elem) {
								const field = $(elem)
									.find('.mct-color-picker')
									.clone();
								$(elem).before(field);
							}
						);
						$(this).find('.wp-picker-container').remove();

						$(this)
							.find('.select2-hidden-accessible')
							.removeClass('enhanced')
							.removeClass('select2-hidden-accessible');
						$(this).find('.select2-container').remove();

						initColorpicker();
						initRepeaterDependencies();
						initOptgroupDependencies();
						initSelect2();
						initSearchPost();
						initSearchUser();
						initDatePicker();
					},
				},
			],
			show() {
				$(this)
					.find('.btn-translation')
					.css({ opacity: 0, 'pointer-events': 'none' });
				$(this).slideDown();

				/**
				 * Remove extra rows after add new repeater Group
				 */
				if ($(this).find('tr').length > 1) {
					$.each($(this).find('tr'), function (index, elem) {
						if (index > 0) {
							elem.remove();
						}
					});
				}

				$.each(
					$(this).find('.wp-picker-container'),
					function (index, elem) {
						const field = $(elem).find('.mct-color-picker').clone();
						$(elem).before(field);
					}
				);
				$(this).find('.wp-picker-container').remove();

				$(this)
					.find('.select2-hidden-accessible')
					.removeClass('enhanced')
					.removeClass('select2-hidden-accessible');
				$(this).find('.select2-container').remove();

				initColorpicker();
				initRepeaterDependencies();
				initOptgroupDependencies();
				initSelect2();
				initSearchPost();
				initSearchUser();
				initDatePicker();
			},
		});

		/**
		 * Add a click event to the "+" button in the inner-repeater  row
		 */
		$('body').on('click', '.inner-repeater .add-new-row', function (e) {
			e.preventDefault();
			$(this)
				.closest('.inner-repeater')
				.find('[data-repeater-create]')
				.trigger('click');
			return false;
		});
		// The "Upload" button.
		$('.mct_upload_image_button').on('click', function () {
			const sendAttachmentBkp = wp.media.editor.send.attachment;
			const button = $(this);

			wp.media.editor.send.attachment = function (props, attachment) {
				$(button).parent().prev().attr('src', attachment.url);
				$(button).prev().val(attachment.id);
				wp.media.editor.send.attachment = sendAttachmentBkp;
			};
			wp.media.editor.open(button);
			return false;
		});

		// The "Remove" button (remove the value from input type='hidden').
		$('.mct_remove_image_button').on('click', function () {
			const answer = confirm(mctAdminParams.i18n_delete_image_confirm);
			if (answer === true) {
				const src = $(this).parent().prev().attr('data-src');
				$(this).parent().prev().attr('src', src);
				$(this).prev().prev().val('');
			}
			return false;
		});

		// The "Upload" button.
		$('.mct_upload_file_button').on('click', function (e) {
			e.preventDefault();
			const button = $(this);
			const mimetypes =
				button.closest('.upload-file').data('mimetypes') || '';
			const title = button.closest('.upload-file').data('title');
			const buttonText = button
				.closest('.upload-file')
				.data('button-text');
			const mimeTypeArray = mimetypes.split(',').map(function (type) {
				return type.trim();
			});

			const mediaFrame = wp.media({
				title,
				button: {
					text: buttonText,
				},
				library: {
					post_mime_type: mimeTypeArray,
				},
				multiple: false,
			});

			mediaFrame.on('select', function () {
				const attachment = mediaFrame
					.state()
					.get('selection')
					.first()
					.toJSON();
				if (!mimeTypeArray.includes(attachment.mime)) {
					console.error(
						'Invalid file type selected:',
						attachment.mime
					);
					return;
				}
				$(button).text(attachment.filename);
				$(button).parent().find('input').val(attachment.id);
				$(button)
					.parent()
					.find(
						'.mct_remove_file_button, .mct_import_file_button,.mct_action_file_button'
					)
					.show();
			});

			// Open the media frame.
			mediaFrame.open();
			return false;
		});
		// The "Remove" button (remove the value from input type='hidden').
		$('.mct_remove_file_button').on('click', function () {
			const answer = confirm(mctAdminParams.i18n_delete_file_confirm);
			if (answer === true) {
				$(this).parent().find('input').val('');
				$(this)
					.parent()
					.find('.mct_upload_file_button')
					.text(
						$(this)
							.parent()
							.find('.mct_upload_file_button')
							.data('label')
					);
				$(this)
					.parent()
					.find(
						'.mct_remove_file_button,.mct_import_file_button,.mct_action_file_button'
					)
					.hide();
			}
			return false;
		});

		$('body').on('click', '.mct_import_file_button', function (event) {
			event.preventDefault();
			const elem = $(this);
			$.ajax({
				url: mctAdminParams.ajax_url,
				data: {
					action: mctAdminParams.plugin_id + '_import_settings',
					key: mctAdminParams.ajax_nonce,
					attachment_id: elem.parent().find('input').val(),
					option_id: elem.closest('.upload-file').data('option_id'),
				},
				method: 'post',
				beforeSend() {
					elem.addClass('loading');
				},
				complete() {
					elem.removeClass('loading');
				},
				success(response) {
					if (response.data.message) {
						const alertType =
							response.success && true === response.success
								? 'success'
								: 'error';
						showSnack(response.data.message, alertType);
					}
					if (response && response.success) {
						location.reload(true);
					}
				},
			});

			return false;
		});

		$('body').on('click', '.mct_export_file_button', function (event) {
			event.preventDefault();
			const elem = $(this);
			$.ajax({
				url: mctAdminParams.ajax_url,
				data: {
					action: mctAdminParams.plugin_id + '_export_settings',
					key: mctAdminParams.ajax_nonce,
					option_id: elem.data('option_id'),
				},
				method: 'post',
				beforeSend() {
					elem.addClass('loading');
				},
				complete() {
					elem.removeClass('loading');
				},
				success(response) {
					const data = response.data;
					const blob = new Blob([data.filecontent], {
						type: 'text/json;charset=utf-8;',
					});
					if (window.navigator.msSaveOrOpenBlob) {
						window.navigator.msSaveOrOpenBlob(blob, data.filename);
					} else {
						const url = URL.createObjectURL(blob);
						const a = document.createElement('a');
						a.href = url;
						a.download = data.filename;
						$(document.body).append(a);
						a.click();
						setTimeout(function () {
							$(a).remove();
							if (data.message) {
								const alertType =
									response.success &&
									true === response.success
										? 'success'
										: 'error';
								showSnack(data.message, alertType);
							}
						}, 5000);
					}

					if (data.message) {
						const alertType =
							response.success && true === response.success
								? 'success'
								: 'error';
						showSnack(data.message, alertType);
					}
				},
				error(xhr) {
					console.error(xhr.responseText);
				},
			});

			return false;
		});

		$('body').on('click', '.mct-sections a', function (event) {
			event.preventDefault();
			$('.mct-section-wrapper').hide();
			$('.mct-section-content').hide();
			$($(this).attr('href')).show();
			const newUrl = removeURLParams('tab');
			window.history.replaceState('', '', newUrl);
			window.history.replaceState(
				'',
				'',
				updateURLParameter(
					window.location.href,
					'section',
					$(this).attr('href')
				)
			);
			$('.mct-sidebar').removeClass('sidebarfixed').removeAttr('style');
			$(document.body).trigger('mc-sidebar-init');
		});

		$('body').on('click', '.mct-back-btn', function (event) {
			event.preventDefault();
			$('.mct-section-content').hide();
			$('.mct-section-wrapper').show();
		});

		$('body').on(
			'click',
			'.mct-tabs a:not(.external-link)',
			function (event) {
				event.preventDefault();
				$(this)
					.closest('.mct-section-content')
					.find('.mct-tab-content')
					.hide();
				$(this)
					.closest('.mct-section-content')
					.find('.mct-tabs a')
					.removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
				$($(this).attr('href')).show();
				window.history.replaceState(
					'',
					'',
					updateURLParameter(
						window.location.href,
						'tab',
						$(this).attr('href')
					)
				);
				initSticky();
				return false;
			}
		);

		$('body').on('click', '.mct-copy-btn', function (event) {
			event.preventDefault();
			const textBox = $(this).parent().find('.mct-copy-text');
			textBox.select();
			document.execCommand('copy');
		});

		$('body').on(
			'click',
			'.mct-wrapper code:not(.disable-copy)',
			function (event) {
				event.preventDefault();
				const codeContent = $(this).text();

				const tempInput = $('<input>');
				$('body').append(tempInput);

				tempInput.val(codeContent).select();

				document.execCommand('copy');
				tempInput.remove();
			}
		);

		$('body').on('click', '.show-manage-item', function (event) {
			event.preventDefault();
			const itemId = $(this).attr('href');
			$('.mct-manage-item').hide();
			$('.manage-row').removeClass('is-current');
			$(this).closest('.manage-row').addClass('is-current');
			$(itemId).show();
			// $("html, body").animate({scrollTop: 0}, "slow");
			$('html, body').animate(
				{
					scrollTop: $(itemId).offset().top - 100,
				},
				'slow'
			);
			$(document).trigger('changed-manage-item');
			return false;
		});

		$('body').on('click', '.back-manage-item', function (event) {
			event.preventDefault();
			$('.manage-row').removeClass('is-current');
			$('.mct-manage-item').hide();
			initDependencies();
			initSectionDependencies();
			initRepeaterDependencies();
			initOptgroupDependencies();
			initManageDependencies();
			return false;
		});

		// collapsible article.
		$('body').on('click', '.article-title h2', function (event) {
			event.preventDefault();
			const elem = $(this).closest('.mct-article');
			if (elem.hasClass('mct-accordion')) {
				$('.mct-article.mct-accordion:visible')
					.not(elem)
					.addClass('collapsed');
			}
			elem.toggleClass('collapsed');
			return false;
		});
		$('body').on('click', '.mct-article.collapsed', function (event) {
			event.preventDefault();
			if ($(this).hasClass('mct-accordion')) {
				$('.mct-article.mct-accordion:visible').addClass('collapsed');
			}

			$(this).removeClass('collapsed');
			return false;
		});
		// fix article link.
		$('body').on('touchend click', '.article-title h2 a', function (event) {
			event.preventDefault();
			const url = $(this).prop('href');
			const target = $(this).prop('target');

			if (url) {
				// # open in new window if "_blank" used
				if (target === '_blank') {
					window.open(url, target);
				} else {
					window.location = url;
				}
			}
			return false;
		});

		// Reset form.
		$('body').on('click', '.mct-reset-btn', function () {
			if (!confirm(mctAdminParams.i18n_reset_confirm)) {
				return false;
			}
		});

		// modal.
		// Quick & dirty toggle to demonstrate modal toggle behavior.
		$('body').on('click', '.modal-toggle', function (e) {
			e.preventDefault();
			const modal = $(this).data('modal');
			$('#' + modal)
				.removeAttr('style')
				.toggleClass('is-visible');
			$('body').toggleClass('mct-modal-enabled');
		});

		$('body').on('click', '.mct-header-menu .toggle-submenu', function (e) {
			if (
				!isMobile &&
				$(this).attr('href') &&
				$(this).attr('href') !== '#' &&
				$(this).attr('href') !== '#!'
			) {
				return;
			}

			if (isMobile) {
				e.preventDefault();
				$(this)
					.closest('li.mct-has-submenu')
					.toggleClass('show-submenu');
			}
		});

		function updateURLParameter(url, param, paramVal) {
			let newAdditionalURL = '';
			let tempArray = url.split('?');
			const baseURL = tempArray[0];
			const additionalURL = tempArray[1];
			let temp = '';
			if (additionalURL) {
				tempArray = additionalURL.split('&');
				for (let i = 0; i < tempArray.length; i++) {
					if (tempArray[i].split('=')[0] !== param) {
						newAdditionalURL += temp + tempArray[i];
						temp = '&';
					}
				}
			}

			const rowsTxt = temp + '' + param + '=' + paramVal.replace('#', '');
			return baseURL + '?' + newAdditionalURL + rowsTxt;
		}

		function removeURLParams(sParam) {
			let url = window.location.href.split('?')[0] + '?';
			const sPageURL = decodeURIComponent(
					window.location.search.substring(1)
				),
				sURLVariables = sPageURL.split('&');
			let sParameterName, i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] !== sParam) {
					url =
						url + sParameterName[0] + '=' + sParameterName[1] + '&';
				}
			}
			return url.substring(0, url.length - 1);
		}

		function initSticky() {
			const container = $('.mct-sticky-container');
			const stickyElement = $('.mct-sticky');
			const stickyHolder = $('.mct-sticky-holder');

			if (
				!container.length ||
				!stickyElement.length ||
				!stickyHolder.length
			) {
				return;
			}

			const containerWidth = container.width();
			stickyElement.width(containerWidth);

			const stickyHolderRect = stickyHolder[0].getBoundingClientRect();
			const scrollTop = $(window).scrollTop();
			const windowHeight = $(window).height();

			if (
				windowHeight + scrollTop - 200 > container.offset().top &&
				stickyHolderRect.top >= windowHeight
			) {
				stickyElement.addClass('scrolling');
			} else {
				stickyElement.removeClass('scrolling');
			}
		}

		// Handle dependencies.
		function dependenciesHandler(deps, values) {
			let result = true;
			// Single dependency.
			if (typeof deps === 'string') {
				deps = $(deps);
			}

			const inputType = deps.data('type');
			let val = deps.val();

			if ('checkbox' === inputType) {
				val = deps.is(':checked') ? '1' : '0';
			} else if ('radio' === inputType) {
				val = deps.find('input[type="radio"]').filter(':checked').val();
			} else if ('checkbox-group' === inputType) {
				val = [];
				deps.find('input[type="checkbox"]:checked').each(function () {
					val.push($(this).val());
				});
			}

			values = values.split(',');

			for (let i = 0; i < values.length; i++) {
				if (Array.isArray(val)) {
					if (val.includes(values[i])) {
						result = true;
						break;
					} else {
						result = false;
					}
				} else if (val !== values[i]) {
					result = false;
				} else {
					result = true;
					break;
				}
			}

			return result;
		}

		function initRepeaterDependencies() {
			$('[data-repdeps]:not( .deps-initialized )').each(function () {
				const t = $(this),
					field = t.closest('.row-options'),
					items = Array.isArray(t.data('repdeps'))
						? t.data('repdeps')
						: [t.data('repdeps')];

				// init field deps.
				t.addClass('deps-initialized');
				$.each(items, function (index, data) {
					const className = data.id,
						wrapper = t.closest('tr'),
						elem = wrapper.find('.' + className);

					$(elem)
						.on('change', function () {
							let showing = true;
							$.each(items, function (i, d) {
								const el = wrapper.find('.' + d.id);
								showing =
									true === dependenciesHandler(el, d.value) &&
									showing;
							});
							if (showing) {
								field.show();
							} else {
								field.hide();
							}
						})
						.trigger('change');
				});
			});
		}

		function initManageDependencies() {
			$('[data-mngdeps]:not( .deps-initialized )').each(function () {
				const t = $(this);
				const field = t.closest('.row-options');
				// init field deps.
				t.addClass('deps-initialized');

				const deps = '#' + t.data('mngdeps'),
					value = t.data('deps-value');

				$(deps)
					.on('change', function () {
						const showing = dependenciesHandler(
							deps,
							value.toString()
						);
						if (showing) {
							field.show(300);
						} else {
							field.hide(300);
						}
					})
					.trigger('change');
			});
		}

		function initDependencies() {
			$('[data-deps]:not( .deps-initialized, .mct-article )').each(
				function () {
					const t = $(this),
						field = t.closest('.row-options'),
						items = Array.isArray(t.data('deps'))
							? t.data('deps')
							: [t.data('deps')];

					// init field deps.
					t.addClass('deps-initialized');
					$.each(items, function (index, data) {
						$('#' + data.id)
							.on('change', function () {
								let showing = true;
								$.each(items, function (i, d) {
									showing =
										true ===
											dependenciesHandler(
												'#' + d.id,
												d.value
											) && showing;
								});
								if (showing) {
									field.show();
								} else {
									field.hide();
								}
							})
							.trigger('change');
					});
				}
			);
		}

		function initSectionDependencies() {
			$('.mct-article[data-deps]:not( .deps-initialized )').each(
				function () {
					const t = $(this),
						items = Array.isArray(t.data('deps'))
							? t.data('deps')
							: [t.data('deps')];
					// init field deps.
					t.addClass('deps-initialized');
					$.each(items, function (index, data) {
						$('#' + data.id)
							.on('change', function () {
								let showing = true;
								$.each(items, function (i, d) {
									showing =
										true ===
											dependenciesHandler(
												'#' + d.id,
												d.value
											) && showing;
								});
								if (showing) {
									t.fadeIn(300);
								} else {
									t.fadeOut(300);
								}
							})
							.trigger('change');
					});
				}
			);
		}

		function initOptgroupDependencies() {
			$('[data-optgroup-deps]:not( .deps-initialized )').each(
				function () {
					const t = $(this),
						field = t.closest('optgroup'),
						items = Array.isArray(t.data('optgroup-deps'))
							? t.data('optgroup-deps')
							: [t.data('optgroup-deps')];

					// init field deps.
					t.addClass('deps-initialized');
					$.each(items, function (index, data) {
						$('#' + data.id)
							.on('change', function () {
								let showing = true;
								$.each(items, function (i, d) {
									showing =
										true ===
											dependenciesHandler(
												'#' + d.id,
												d.value
											) && showing;
								});
								if (showing) {
									field.show();
								} else {
									field.hide();
								}
							})
							.trigger('change');
					});
				}
			);
		}

		function showSnack(error, alertType) {
			const x = document.getElementById('snackbar');
			x.innerHTML = error;
			x.className = 'show ' + alertType;
			setTimeout(function () {
				x.className = x.className.replace('show', '');
			}, 3000);
		}

		initDependencies();
		initSectionDependencies();
		initRepeaterDependencies();
		initOptgroupDependencies();
		initManageDependencies();
		initColorpicker();
		initSelect2();
		initSearchPost();
		initSearchUser();
		initDatePicker();
		initCssEditor();
		initWizard();
		initValidate();
		initSticky();

		$(window).on('resize', function () {
			if ($('.mct-sidebar').length > 0) {
				$(document.body).trigger('mc-sidebar-init');
			}
		});

		// Listen for window resize and scroll events.
		$(window).on('resize scroll', function () {
			initSticky();
		});

		$('.mct-hamburger-icon').click(function () {
			$('.mct-header-menu').toggleClass('current');
			$(this).toggleClass('current');
		});
		$('.mct-has-submenu .toggle-submenu').click(function (e) {
			e.preventDefault();
			$(this).closest('.mct-has-submenu').toggleClass('current');
		});

		$('.mct-wrapper table.form-table').each(function () {
			if ($(this).find('tbody').children().length === 0) {
				$(this).addClass('empty-body');
			}
		});
	});
})(jQuery);
// eslint-disable-next-line no-unused-vars
function mcDepsLink(elem) {
	const element = elem.nextElementSibling;
	if (elem.options[elem.selectedIndex].value === 'custom-link') {
		element.style.display = 'inline-block';
	} else {
		element.setAttribute(
			'style',
			'margin-top: 10px ;display: none !important;'
		);
	}
}
