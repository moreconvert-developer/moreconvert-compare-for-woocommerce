/**
 *  Jquery.repeater version 1.2.1
 * https://github.com/DubFriend/jquery.repeater
 * (MIT) 09-10-2016
 * Brian Detering <BDeterin@gmail.com> (http://www.briandetering.net/)
 *
 * @param {jQuery} $ - The jQuery object.
 * @package
 */

(function ($) {
	'use strict';

	const identity = function (x) {
		return x;
	};

	const isArray = function (value) {
		return Array.isArray(value);
	};

	const isObject = function (value) {
		return !isArray(value) && value instanceof Object;
	};
	// eslint-disable-next-line no-unused-vars
	const isNumber = function (value) {
		return value instanceof Number;
	};
	// eslint-disable-next-line no-unused-vars
	const isFunction = function (value) {
		return value instanceof Function;
	};

	const indexOf = function (object, value) {
		return $.inArray(value, object);
	};

	const inArray = function (array, value) {
		return indexOf(array, value) !== -1;
	};

	const foreach = function (collection, callback) {
		for (const i in collection) {
			if (collection.hasOwnProperty(i)) {
				callback(collection[i], i, collection);
			}
		}
	};

	const last = function (array) {
		return array[array.length - 1];
	};

	const argumentsToArray = function (args) {
		return Array.prototype.slice.call(args);
	};

	const extend = function () {
		const extended = {};
		foreach(argumentsToArray(arguments), function (o) {
			foreach(o, function (val, key) {
				extended[key] = val;
			});
		});
		return extended;
	};

	const mapToArray = function (collection, callback) {
		const mapped = [];
		foreach(collection, function (value, key, coll) {
			mapped.push(callback(value, key, coll));
		});
		return mapped;
	};

	const mapToObject = function (collection, callback, keyCallback) {
		const mapped = {};
		foreach(collection, function (value, key, coll) {
			key = keyCallback ? keyCallback(key, value) : key;
			mapped[key] = callback(value, key, coll);
		});
		return mapped;
	};

	const map = function (collection, callback, keyCallback) {
		return isArray(collection)
			? mapToArray(collection, callback)
			: mapToObject(collection, callback, keyCallback);
	};

	const pluck = function (arrayOfObjects, key) {
		return map(arrayOfObjects, function (val) {
			return val[key];
		});
	};

	const filter = function (collection, callback) {
		let filtered;

		if (isArray(collection)) {
			filtered = [];
			foreach(collection, function (val, key, coll) {
				if (callback(val, key, coll)) {
					filtered.push(val);
				}
			});
		} else {
			filtered = {};
			foreach(collection, function (val, key, coll) {
				if (callback(val, key, coll)) {
					filtered[key] = val;
				}
			});
		}

		return filtered;
	};

	const call = function (collection, functionName, args) {
		return map(collection, function (object) {
			return object[functionName].apply(object, args || []);
		});
	};

	// execute callback immediately and at most one time on the minimumInterval,
	// ignore block attempts.
	// eslint-disable-next-line no-unused-vars
	const throttle = function (minimumInterval, callback) {
		let timeout = null;
		return function () {
			const that = this,
				args = arguments;
			if (timeout === null) {
				timeout = setTimeout(function () {
					timeout = null;
				}, minimumInterval);
				callback.apply(that, args);
			}
		};
	};

	const mixinPubSub = function (object) {
		object = object || {};
		const topics = {};

		object.publish = function (topic, data) {
			foreach(topics[topic], function (callback) {
				callback(data);
			});
		};

		object.subscribe = function (topic, callback) {
			topics[topic] = topics[topic] || [];
			topics[topic].push(callback);
		};

		object.unsubscribe = function (callback) {
			foreach(topics, function (subscribers) {
				const index = indexOf(subscribers, callback);
				if (index !== -1) {
					subscribers.splice(index, 1);
				}
			});
		};

		return object;
	};

	// jquery.input version 0.0.0
	// https://github.com/DubFriend/jquery.input
	// (MIT) 09-04-2014
	// Brian Detering <BDeterin@gmail.com> (http://www.briandetering.net/).
	const createBaseInput = function (fig, my) {
		const self = mixinPubSub(),
			$self = fig.$;

		self.getType = function () {
			throw 'implement me (return type. "text", "radio", etc.)';
		};

		self.$ = function (selector) {
			return selector ? $self.find(selector) : $self;
		};

		self.disable = function () {
			self.$().prop('disabled', true);
			self.publish('isEnabled', false);
		};

		self.enable = function () {
			self.$().prop('disabled', false);
			self.publish('isEnabled', true);
		};

		my.equalTo = function (a, b) {
			return a === b;
		};

		my.publishChange = (function () {
			let oldValue;
			return function (e, domElement) {
				const newValue = self.get();
				if (!my.equalTo(newValue, oldValue)) {
					self.publish('change', { e, domElement });
				}
				oldValue = newValue;
			};
		})();

		return self;
	};

	const createInput = function (fig, my) {
		const self = createBaseInput(fig, my);

		self.get = function () {
			return self.$().val();
		};

		self.set = function (newValue) {
			self.$().val(newValue);
		};

		self.clear = function () {
			self.set('');
		};

		my.buildSetter = function (callback) {
			return function (newValue) {
				callback.call(self, newValue);
			};
		};

		return self;
	};

	const inputEqualToArray = function (a, b) {
		a = isArray(a) ? a : [a];
		b = isArray(b) ? b : [b];

		let isEqual = true;
		if (a.length !== b.length) {
			isEqual = false;
		} else {
			foreach(a, function (value) {
				if (!inArray(b, value)) {
					isEqual = false;
				}
			});
		}

		return isEqual;
	};

	const createInputButton = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'button';
		};

		self.$().on('change', function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputCheckbox = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'checkbox';
		};

		self.get = function () {
			const values = [];
			self.$()
				.filter(':checked')
				.each(function () {
					values.push($(this).val());
				});
			return values;
		};

		self.set = function (newValues) {
			newValues = isArray(newValues) ? newValues : [newValues];

			self.$().each(function () {
				$(this).prop('checked', false);
			});

			foreach(newValues, function (value) {
				self.$()
					.filter('[value="' + value + '"]')
					.prop('checked', true);
			});
		};

		my.equalTo = inputEqualToArray;

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputEmail = function (fig) {
		const my = {},
			self = createInputText(fig, my);

		self.getType = function () {
			return 'email';
		};

		return self;
	};

	const createInputFile = function (fig) {
		const my = {},
			self = createBaseInput(fig, my);

		self.getType = function () {
			return 'file';
		};

		self.get = function () {
			return last(self.$().val().split('\\'));
		};

		self.clear = function () {
			// http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery.
			this.$().each(function () {
				$(this).wrap('<form>').closest('form').get(0).reset();
				$(this).unwrap();
			});
		};

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputHidden = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'hidden';
		};

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputMultipleFile = function (fig) {
		const my = {},
			self = createBaseInput(fig, my);

		self.getType = function () {
			return 'file[multiple]';
		};

		self.get = function () {
			// http://stackoverflow.com/questions/14035530/how-to-get-value-of-html-5-multiple-file-upload-variable-using-jquery.
			const fileListObject = self.$().get(0).files || [],
				names = [],
				length = fileListObject.length;
			let i;
			for (i = 0; i < (length || 0); i += 1) {
				names.push(fileListObject[i].name);
			}

			return names;
		};

		self.clear = function () {
			// http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery.
			this.$().each(function () {
				$(this).wrap('<form>').closest('form').get(0).reset();
				$(this).unwrap();
			});
		};

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputMultipleSelect = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'select[multiple]';
		};

		self.get = function () {
			return self.$().val() || [];
		};

		/*self.set = function (newValues) {
			self.$().val(
				newValues === ''
					? []
					: isArray(newValues)
						? newValues
						: [newValues]
			);
		};*/

		self.set = function (newValues) {
			let valueToSet;
			if (newValues === '') {
				valueToSet = [];
			} else if (isArray(newValues)) {
				valueToSet = newValues;
			} else {
				valueToSet = [newValues];
			}
			self.$().val(valueToSet);
		};

		my.equalTo = inputEqualToArray;

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputPassword = function (fig) {
		const my = {},
			self = createInputText(fig, my);

		self.getType = function () {
			return 'password';
		};

		return self;
	};

	const createInputRadio = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'radio';
		};

		self.get = function () {
			return self.$().filter(':checked').val() || null;
		};

		self.set = function (newValue) {
			if (!newValue) {
				self.$().each(function () {
					$(this).prop('checked', false);
				});
			} else {
				self.$()
					.filter('[value="' + newValue + '"]')
					.prop('checked', true);
			}
		};

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputRange = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'range';
		};

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputSelect = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'select';
		};

		self.$().change(function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputText = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'text';
		};

		self.$().on('change keyup keydown', function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputTextarea = function (fig) {
		const my = {},
			self = createInput(fig, my);

		self.getType = function () {
			return 'textarea';
		};

		self.$().on('change keyup keydown', function (e) {
			my.publishChange(e, this);
		});

		return self;
	};

	const createInputURL = function (fig) {
		const my = {},
			self = createInputText(fig, my);

		self.getType = function () {
			return 'url';
		};

		return self;
	};

	const buildFormInputs = function (fig) {
		const inputs = {},
			$self = fig.$;

		const constructor = fig.constructorOverride || {
			button: createInputButton,
			text: createInputText,
			url: createInputURL,
			email: createInputEmail,
			password: createInputPassword,
			range: createInputRange,
			textarea: createInputTextarea,
			select: createInputSelect,
			'select[multiple]': createInputMultipleSelect,
			radio: createInputRadio,
			checkbox: createInputCheckbox,
			file: createInputFile,
			'file[multiple]': createInputMultipleFile,
			hidden: createInputHidden,
		};

		const addInputsBasic = function (type, selector) {
			const $input = isObject(selector) ? selector : $self.find(selector);

			$input.each(function () {
				const name = $(this).attr('name');
				inputs[name] = constructor[type]({
					$: $(this),
				});
			});
		};

		const addInputsGroup = function (type, selector) {
			const names = [],
				$input = isObject(selector) ? selector : $self.find(selector);

			if (isObject(selector)) {
				inputs[$input.attr('name')] = constructor[type]({
					$: $input,
				});
			} else {
				// group by name attribute.
				$input.each(function () {
					if (indexOf(names, $(this).attr('name')) === -1) {
						names.push($(this).attr('name'));
					}
				});

				foreach(names, function (name) {
					inputs[name] = constructor[type]({
						$: $self.find('input[name="' + name + '"]'),
					});
				});
			}
		};

		if ($self.is('input, select, textarea')) {
			if (
				$self.is('input[type="button"], button, input[type="submit"]')
			) {
				addInputsBasic('button', $self);
			} else if ($self.is('textarea')) {
				addInputsBasic('textarea', $self);
			} else if (
				$self.is('input[type="text"]') ||
				($self.is('input') && !$self.attr('type'))
			) {
				addInputsBasic('text', $self);
			} else if ($self.is('input[type="password"]')) {
				addInputsBasic('password', $self);
			} else if ($self.is('input[type="email"]')) {
				addInputsBasic('email', $self);
			} else if ($self.is('input[type="url"]')) {
				addInputsBasic('url', $self);
			} else if ($self.is('input[type="range"]')) {
				addInputsBasic('range', $self);
			} else if ($self.is('select')) {
				if ($self.is('[multiple]')) {
					addInputsBasic('select[multiple]', $self);
				} else {
					addInputsBasic('select', $self);
				}
			} else if ($self.is('input[type="file"]')) {
				if ($self.is('[multiple]')) {
					addInputsBasic('file[multiple]', $self);
				} else {
					addInputsBasic('file', $self);
				}
			} else if ($self.is('input[type="hidden"]')) {
				addInputsBasic('hidden', $self);
			} else if ($self.is('input[type="radio"]')) {
				addInputsGroup('radio', $self);
			} else if ($self.is('input[type="checkbox"]')) {
				addInputsGroup('checkbox', $self);
			} else {
				// in all other cases default to a "text" input interface.
				addInputsBasic('text', $self);
			}
		} else {
			addInputsBasic(
				'button',
				'input[type="button"], button, input[type="submit"]'
			);
			addInputsBasic('text', 'input[type="text"]');
			addInputsBasic('password', 'input[type="password"]');
			addInputsBasic('email', 'input[type="email"]');
			addInputsBasic('url', 'input[type="url"]');
			addInputsBasic('range', 'input[type="range"]');
			addInputsBasic('textarea', 'textarea');
			addInputsBasic('select', 'select:not([multiple])');
			addInputsBasic('select[multiple]', 'select[multiple]');
			addInputsBasic('file', 'input[type="file"]:not([multiple])');
			addInputsBasic('file[multiple]', 'input[type="file"][multiple]');
			addInputsBasic('hidden', 'input[type="hidden"]');
			addInputsGroup('radio', 'input[type="radio"]');
			addInputsGroup('checkbox', 'input[type="checkbox"]');
		}

		return inputs;
	};

	$.fn.inputVal = function (newValue) {
		const $self = $(this);

		const inputs = buildFormInputs({ $: $self });

		if ($self.is('input, textarea, select')) {
			if (typeof newValue === 'undefined') {
				return inputs[$self.attr('name')].get();
			}
			inputs[$self.attr('name')].set(newValue);
			return $self;
		}
		if (typeof newValue === 'undefined') {
			return call(inputs, 'get');
		}
		foreach(newValue, function (value, inputName) {
			inputs[inputName].set(value);
		});
		return $self;
	};

	$.fn.inputOnChange = function (callback) {
		const $self = $(this);
		const inputs = buildFormInputs({ $: $self });
		foreach(inputs, function (input) {
			input.subscribe('change', function (data) {
				callback.call(data.domElement, data.e);
			});
		});
		return $self;
	};

	$.fn.inputDisable = function () {
		const $self = $(this);
		call(buildFormInputs({ $: $self }), 'disable');
		return $self;
	};

	$.fn.inputEnable = function () {
		const $self = $(this);
		call(buildFormInputs({ $: $self }), 'enable');
		return $self;
	};

	$.fn.inputClear = function () {
		const $self = $(this);
		call(buildFormInputs({ $: $self }), 'clear');
		return $self;
	};

	$.fn.repeaterVal = function () {
		const parse = function (raw) {
			const parsed = [];

			foreach(raw, function (val, key) {
				let parsedKey = [];
				if (key !== 'undefined') {
					parsedKey.push(key.match(/^[^\[]*/)[0]);
					parsedKey = parsedKey.concat(
						map(key.match(/\[[^\]]*\]/g), function (bracketed) {
							return bracketed.replace(/[\[\]]/g, '');
						})
					);

					parsed.push({
						val,
						key: parsedKey,
					});
				}
			});

			return parsed;
		};

		const build = function (parsed) {
			if (
				parsed.length === 1 &&
				(parsed[0].key.length === 0 ||
					(parsed[0].key.length === 1 && !parsed[0].key[0]))
			) {
				return parsed[0].val;
			}

			foreach(parsed, function (p) {
				p.head = p.key.shift();
			});

			const groupedItems = (function () {
				const grouped = {};

				foreach(parsed, function (p) {
					if (!grouped[p.head]) {
						grouped[p.head] = [];
					}
					grouped[p.head].push(p);
				});

				return grouped;
			})();

			let built;

			if (/^[0-9]+$/.test(parsed[0].head)) {
				built = [];
				foreach(groupedItems, function (group) {
					built.push(build(group));
				});
			} else {
				built = {};
				foreach(groupedItems, function (group, key) {
					built[key] = build(group);
				});
			}

			return built;
		};

		return build(parse($(this).inputVal()));
	};

	$.fn.repeater = function (fig) {
		fig = fig || {};

		let setList;

		$(this).each(function () {
			const $self = $(this);

			const show =
				fig.show ||
				function () {
					$(this).show();
				};

			const hide =
				fig.hide ||
				function (removeElement) {
					removeElement();
				};

			const $list = $self.find('[data-repeater-list]').first();

			const $filterNested = function ($items, repeaters) {
				return $items.filter(function () {
					return repeaters
						? $(this).closest(
								pluck(repeaters, 'selector').join(',')
							).length === 0
						: true;
				});
			};

			const $items = function () {
				return $filterNested(
					$list.find('[data-repeater-item]'),
					fig.repeaters
				);
			};

			const $itemTemplate = $list
				.find('[data-repeater-item]')
				.first()
				.clone()
				.hide();

			const $firstDeleteButton = $filterNested(
				$filterNested(
					$(this).find('[data-repeater-item]'),
					fig.repeaters
				)
					.first()
					.find('[data-repeater-delete]'),
				fig.repeaters
			);

			if (fig.isFirstItemUndeletable && $firstDeleteButton) {
				$firstDeleteButton.remove();
			}

			const getGroupName = function () {
				const groupName = $list.data('repeater-list');
				return fig.$parent
					? fig.$parent.data('item-name') + '[' + groupName + ']'
					: groupName;
			};

			const initNested = function ($listItems) {
				if (fig.repeaters) {
					$listItems.each(function () {
						const $item = $(this);
						foreach(fig.repeaters, function (nestedFig) {
							$item
								.find(nestedFig.selector)
								.repeater(
									extend(nestedFig, { $parent: $item })
								);
						});
					});
				}
			};

			const $foreachRepeaterInItem = function (repeaters, $item, cb) {
				if (repeaters) {
					foreach(repeaters, function (nestedFig) {
						cb.call($item.find(nestedFig.selector)[0], nestedFig);
					});
				}
			};

			const setIndexes = function ($itemsList, groupName, repeaters) {
				$itemsList.each(function (index) {
					const $item = $(this);
					$item.data('item-name', groupName + '[' + index + ']');
					$filterNested($item.find('[name]'), repeaters).each(
						function () {
							const $input = $(this);
							// match non empty brackets (ex: "[foo]").
							const matches = $input
								.attr('name')
								.match(/\[[^\]]+\]/g);

							const name = matches
								? // strip "[" and "]" characters.
									last(matches).replace(/\[|\]/g, '')
								: $input.attr('name');

							const newName =
								groupName +
								'[' +
								index +
								'][' +
								name +
								']' +
								($input.attr('multiple') ? '[]' : ''); // $input.is(':checkbox').
							$input.attr('name', newName);
							/*
							 * new Change for work with repeater fields By MoreConvert
							 */
							$foreachRepeaterInItem(
								repeaters,
								$item,
								function (nestedFig) {
									const $repeater = $(this);
									setIndexes(
										$filterNested(
											$repeater.find(
												'[data-repeater-item]'
											),
											nestedFig.repeaters || []
										),
										groupName +
											'[' +
											index +
											']' +
											'[' +
											$repeater
												.find('[data-repeater-list]')
												.first()
												.data('repeater-list') +
											']',
										nestedFig.repeaters
									);
								}
							);
						}
					);
				});
				$list
					.find('input[name][checked]')
					.removeAttr('checked')
					.prop('checked', true);
			};

			setIndexes($items(), getGroupName(), fig.repeaters);
			initNested($items());
			if (fig.initEmpty) {
				$items().remove();
			}

			if (fig.ready) {
				fig.ready(function () {
					setIndexes($items(), getGroupName(), fig.repeaters);
				});
			}

			const appendItem = (function () {
				const setItemsValues = function ($item, itemData, repeaters) {
					if (itemData || fig.defaultValues) {
						const inputNames = {};
						$filterNested($item.find('[name]'), repeaters).each(
							function () {
								const key = $(this)
									.attr('name')
									.match(/\[([^\]]*)(\]|\]\[\])$/)[1];
								inputNames[key] = $(this).attr('name');
							}
						);

						$item.inputVal(
							map(
								filter(
									itemData || fig.defaultValues,
									function (val, name) {
										return inputNames[name];
									}
								),
								identity,
								function (name) {
									return inputNames[name];
								}
							)
						);
					}

					$foreachRepeaterInItem(
						repeaters,
						$item,
						function (nestedFig) {
							const $repeater = $(this);
							$filterNested(
								$repeater.find('[data-repeater-item]'),
								nestedFig.repeaters
							).each(function () {
								const fieldName = $repeater
									.find('[data-repeater-list]')
									.data('repeater-list');
								if (itemData && itemData[fieldName]) {
									const $template = $(this).clone();
									$repeater
										.find('[data-repeater-item]')
										.remove();
									foreach(
										itemData[fieldName],
										function (dataEntry) {
											const $itemClone =
												$template.clone();
											setItemsValues(
												$itemClone,
												dataEntry,
												nestedFig.repeaters || []
											);
											$repeater
												.find('[data-repeater-list]')
												.append($itemClone);
										}
									);
								} else {
									setItemsValues(
										$(this),
										nestedFig.defaultValues,
										nestedFig.repeaters || []
									);
								}
							});
						}
					);
				};

				return function ($item, data) {
					$list.append($item);
					setIndexes($items(), getGroupName(), fig.repeaters);
					$item.find('[name]').each(function () {
						$(this).inputClear();
					});
					setItemsValues(
						$item,
						data || fig.defaultValues,
						fig.repeaters
					);
				};
			})();

			const addItem = function (data) {
				const $item = $itemTemplate.clone();
				appendItem($item, data);
				if (fig.repeaters) {
					initNested($item);
				}
				show.call($item.get(0));
			};

			setList = function (rows) {
				$items().remove();
				foreach(rows, addItem);
			};

			$filterNested(
				$self.find('[data-repeater-create]'),
				fig.repeaters
			).on('click', function () {
				const limit = $self.data('limit'),
					length = $items().length;
				if (limit && parseInt(limit) > 0) {
					if (length < limit) {
						addItem();
					} else {
						alert(fig.limitMessage);
					}
				} else {
					addItem();
				}
			});

			$list.on('click', '[data-repeater-delete]', function () {
				const elem = $(this),
					parent = elem.closest('.mct-repeater');
				const self = $(this).closest('[data-repeater-item]').get(0);
				hide.call(self, function () {
					$(self).remove();
					setIndexes($items(), getGroupName(), fig.repeaters);
					/**
					 * Remove inner repeater if not any fields
					 *
					 * @author MoreConvert
					 */
					if (parent.hasClass('inner-repeater')) {
						if (parent.find('tr').length === 0) {
							parent.closest('[data-repeater-item]').remove();
						}
					}
				});
			});
		});

		this.setList = setList;

		return this;
	};
})(jQuery);
