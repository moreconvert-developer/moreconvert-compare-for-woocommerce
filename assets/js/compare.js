"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
/**
 * MoreConvert Compare for WooCommerce Frontend Script
 *
 * Handles the compare popup, search, suggestions, and temporary compare list using wp.template.
 *
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.2
 */

/* jshint esversion: 9 */
/* globals jQuery, wp, McCompare, console */
// phpcs:disable
(function ($, document) {
  /**
   * Compare manager class.
   *
   * @param {jQuery} $element The element to initialize the compare functionality on.
   */
  var CompareManager = function CompareManager($element) {
    // Properties
    this.$element = $element;
    this.allCompareProducts = []; // Stores all added products (up to max_products).
    this.compareProducts = []; // Displayed products (sliced based on screen size).
    this.isUpdating = false; // Flag to prevent recursive updates.
    this.data = {
      specifications: McCompare.texts.specifications
    };
    this.currentPage = 1;
    this.totalProducts = 0;
    this.isLoading = false;
    this.isSearchActive = false;
    this.currentQuery = '';
    this.observer = null;
    this.activeAjaxRequest = null;

    // Initialize
    this.init();
  };

  /**
   * Initialize the compare manager.
   */
  CompareManager.prototype.init = function () {
    // Validate dependencies
    if (typeof jQuery === 'undefined') {
      console.error('jQuery is not loaded.');
      return false;
    }
    if (typeof wp === 'undefined' || typeof wp.template !== 'function') {
      console.error('wp.template is not available. Ensure wp-util is enqueued.');
      return false;
    }
    if (!document.getElementById('tmpl-moreconvert-compare-popup-template')) {
      console.error('Template #tmpl-moreconvert-compare-popup-template not found in DOM.');
      return false;
    }
    if (!document.getElementById('tmpl-moreconvert-compare-add-product-popup-template')) {
      console.error('Template #tmpl-moreconvert-compare-add-product-popup-template not found in DOM.');
      return false;
    }
    if (!McCompare || !McCompare.texts || !McCompare.ajax_url || !McCompare.ajax_nonce) {
      console.error('McCompare global object is missing or incomplete.');
      return false;
    }

    // Templates
    this.comparePopupTemplate = wp.template('moreconvert-compare-popup-template');
    this.addProductPopupTemplate = wp.template('moreconvert-compare-add-product-popup-template');

    // Append templates to DOM
    $(document.body).append(this.comparePopupTemplate(this.data));
    this.data = {
      select_product: McCompare.texts.select_product || 'Select a product',
      search_products: McCompare.texts.search_products || 'Search products',
      moreconvert_compare_nonce: McCompare.ajax_nonce,
      top_products: McCompare.texts.top_products || 'Top Products'
    };
    $(document.body).append(this.addProductPopupTemplate(this.data));

    // Bind events
    this.bindEvents();

    // Bind resize event
    window.addEventListener('resize', this.checkScreenWidth.bind(this));
  };

  /**
   * Bind all event handlers.
   */
  CompareManager.prototype.bindEvents = function () {
    // Reset variations
    $(document.body).on('click', '.moreconvert-compare-reset-variations', function (_this) {
      return function (e) {
        e.preventDefault();
        _this.isUpdating = false;
        $(this).closest('form.variations_form').each(function () {
          var index = $(this).closest('.moreconvert-compare-product-card').data('index');
          if (_this.compareProducts[index]) {
            _this.resetProduct(_this.compareProducts[index]);
            $(this).find('.variations select').val('');
            _this.updateAvailableOptions(index);
          }
        });
        return false;
      };
    }(this));

    // Compare button click
    $(document).on('click', '.moreconvert-compare-button', function (_this) {
      return function (e) {
        e.preventDefault();
        var productData = $(this).data('product');
        if (!productData) {
          console.error('No product data found for .moreconvert-compare-button');
          return;
        }
        productData.variation_attributes = {};
        productData.loading_variations = true;
        _this.initializeComparison(productData);
        productData.loading_variations = false;
        if (productData.has_variations) {
          $.ajax({
            url: McCompare.ajax_url,
            type: 'POST',
            data: {
              action: 'moreconvert_compare_get_variations',
              nonce: McCompare.ajax_nonce,
              product_id: productData.id
            },
            success: function success(response) {
              if (response.success && response.data.variations) {
                productData = _this.processProductData(productData, response.data);
                _this.allCompareProducts = [productData];
                _this.compareProducts = _this.allCompareProducts.slice(0, _this.getMaxDisplayed());
                _this.updateCompareColumns();
              }
              //_this.initializeComparison(productData);
            },
            error: function error(jqXHR, textStatus) {
              console.error('AJAX error fetching variations:', textStatus);
              //_this.initializeComparison(productData);
            }
          });
        } else {
          //_this.initializeComparison(productData);
        }
      };
    }(this));

    // Add product to compare list
    $(document).on('click', '.moreconvert-compare-add-to-compare-button, .moreconvert-compare-product-link', function (_this) {
      return function (e) {
        e.preventDefault();
        var productData = $(this).data('product');
        if (!productData) {
          console.error('No product data found for .moreconvert-compare-add-to-compare-button or .moreconvert-compare-product-link');
          return;
        }
        productData.variation_attributes = {};
        productData.loading_variations = true;
        _this.addProductToCompare(productData, $(e.target));
        productData.loading_variations = false;
        if (productData.has_variations) {
          $.ajax({
            url: McCompare.ajax_url,
            type: 'POST',
            data: {
              action: 'moreconvert_compare_get_variations',
              nonce: McCompare.ajax_nonce,
              product_id: productData.id
            },
            success: function success(response) {
              if (response.success && response.data.variations) {
                productData = _this.processProductData(productData, response.data);
                _this.addProductToCompare(productData, $(e.target));
                _this.syncVariationAttributes();
              }
            },
            error: function error(jqXHR, textStatus) {
              console.error('AJAX error adding product to compare:', textStatus);
              //_this.addProductToCompare(productData, $(e.target));
              //_this.syncVariationAttributes();
            }
          });
        } else {
          //_this.addProductToCompare(productData, $(e.target));
          //_this.syncVariationAttributes();
        }
      };
    }(this));

    // Open add product popup
    $(document).on('click', '.moreconvert-compare-section .moreconvert-compare-display-popup-button', function (e) {
      e.preventDefault();
      $('.moreconvert-compare-add-product-popup-wrapper').fadeIn();
    });

    // Hide add product popup
    $(document).on('click', '.moreconvert-compare-add-product-popup', function (e) {
      e.preventDefault();
      if ($(e.target).hasClass('moreconvert-compare-add-product-popup')) {
        $('.moreconvert-compare-add-product-popup-wrapper').fadeOut();
      }
    });

    // Close add product popup
    $(document).on('click', '.moreconvert-compare-close-button', function (e) {
      e.preventDefault();
      $('.moreconvert-compare-add-product-popup-wrapper').fadeOut();
    });

    // Search form submit prevention
    $(document).on('submit', '.moreconvert-compare-search-form', function (e) {
      e.preventDefault();
    });

    // Search input debounced
    $(document).on('input', '.moreconvert-compare-search-form .moreconvert-compare-input', this.debounce(function (_this) {
      return function () {
        var searchQuery = $(this).val().trim();
        var baseProductId = $(this).closest('.moreconvert-compare-search-form').find('.moreconvert-compare-base-product-id').val();
        _this.currentQuery = searchQuery;
        _this.isSearchActive = !!searchQuery;
        _this.currentPage = 1;
        _this.totalProducts = 0;
        $('.moreconvert-compare-products-grid').empty();
        $('.moreconvert-compare-products-section').find('.moreconvert-compare-message').remove();
        if (_this.activeAjaxRequest) {
          _this.activeAjaxRequest.abort();
          _this.activeAjaxRequest = null;
        }
        if (searchQuery) {
          _this.loadProducts(baseProductId, true);
        } else {
          _this.fetchSuggestedProducts(baseProductId);
        }
      };
    }(this), 300));

    // Remove product from compare
    $(document).on('click', '.moreconvert-compare-remove-from-compare', function (_this) {
      return function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        _this.allCompareProducts = _this.allCompareProducts.filter(function (p) {
          return p.id !== productId;
        });
        _this.compareProducts = _this.allCompareProducts.slice(0, _this.getMaxDisplayed());
        _this.updateCompareColumns();
        _this.syncVariationAttributes();
        var baseProductId = $('.moreconvert-compare-modal-content .moreconvert-compare-base-product-id').val();
        _this.fetchSuggestedProducts(baseProductId);
      };
    }(this));

    // Close compare popup
    $(document).on('click', '.moreconvert-compare-close-compare', function (_this) {
      return function (e) {
        e.preventDefault();
        _this.allCompareProducts = [];
        _this.compareProducts = [];
        _this.updateCompareColumns();
      };
    }(this));
  };

  /**
   * Sync variation attributes for all products.
   */
  CompareManager.prototype.syncVariationAttributes = function () {
    $.each(this.compareProducts, function (index, product) {
      var attr_keys = Object.keys(product.variation_attributes || []);
      if (attr_keys) {
        attr_keys.forEach(function (k) {
          var key = 'attribute_' + k;
          var current_value = product.selected_attributes[key] || '';
          $(".moreconvert-compare-variation-index-".concat(index, " [data-attribute_name=\"").concat(key, "\"]")).val(current_value).trigger('change');
        });
      }
    });
  };

  /**
   * Get maximum number of displayed products based on screen width.
   *
   * @return {number} Maximum number of products to display.
   */
  CompareManager.prototype.getMaxDisplayed = function () {
    return window.innerWidth < 993 ? 2 : McCompare.max_products || 4;
  };

  /**
   * Get scrollbar width.
   *
   * @return {number} Scrollbar width in pixels.
   */
  CompareManager.prototype.getScrollbarWidth = function () {
    return window.innerWidth - document.documentElement.clientWidth;
  };

  /**
   * Disable body scroll.
   */
  CompareManager.prototype.disableBodyScroll = function () {
    var scrollbarWidth = this.getScrollbarWidth();
    $('html').css('--scrollbar-width', scrollbarWidth + 'px');
    $('html').addClass('no-scroll');
  };

  /**
   * Enable body scroll.
   */
  CompareManager.prototype.enableBodyScroll = function () {
    $('html').removeClass('no-scroll');
    $('html').css('--scrollbar-width', '');
  };

  /**
   * Reset product data to default state.
   *
   * @param {Object} Product The product object to reset.
   */
  CompareManager.prototype.resetProduct = function (Product) {
    Product.is_purchasable = false;
    Product.default_attributes = {};
    Product.selected_variation = null;
    Product.selected_variation_id = null;
    Product.attributes = Product.parent_attributes || [];
    Product.image = Product.parent_image || '';
    Product.price = Product.parent_price_html || '';
    Product.add_to_cart_url = Product.parent_add_to_cart_url || '';
    if (Product.selected_attributes) {
      Product.selected_attributes = {};
      Product.selected_labels = {};
    }
  };

  /**
   * Process product data with variation information.
   *
   * @param {Object} productData The product data.
   * @param {Object} responseData The AJAX response data.
   * @return {Object} Processed product data.
   */
  CompareManager.prototype.processProductData = function (productData, responseData) {
    var parentFields = {
      parent_attributes: productData.parent_attributes || [],
      parent_price_html: productData.parent_price_html || '',
      parent_image: productData.parent_image || '',
      parent_add_to_cart_url: productData.parent_add_to_cart_url || '',
      parent_is_purchasable: productData.parent_is_purchasable || false
    };
    if (productData.has_variations === true) {
      Object.assign(productData, parentFields);
    }
    productData.is_purchasable = false;
    productData.variations = responseData.variations || [];
    productData.html_variations = responseData.html_variations || '';
    productData.variation_attributes = responseData.variation_attributes || {};
    productData.default_attributes = responseData.default_attributes || {};
    productData.selected_attributes = _objectSpread({}, productData.default_attributes);
    productData.selected_labels = {};
    var selectedVar = this.findMatchingVariation(productData.variations, productData.selected_attributes);
    if (selectedVar) {
      productData.selected_variation = selectedVar;
      productData.is_purchasable = selectedVar.is_purchasable || false;
      productData.attributes = selectedVar.formatted_attributes || [];
      productData.image = selectedVar.image_thumbnail || productData.parent_image;
      productData.price = selectedVar.price_html || productData.parent_price_html;
      productData.add_to_cart_url = selectedVar.add_to_cart_url || productData.parent_add_to_cart_url;
    } else {
      productData.selected_variation = null;
      productData.attributes = productData.parent_attributes || [];
      productData.image = productData.parent_image || '';
      productData.price = productData.parent_price_html || '';
      productData.add_to_cart_url = productData.parent_add_to_cart_url || '';
      productData.selected_attributes = {};
    }
    return productData;
  };

  /**
   * Initialize comparison with a product.
   *
   * @param {Object} productData The product data.
   */
  CompareManager.prototype.initializeComparison = function (productData) {
    if (!productData || !productData.id) {
      console.error('Invalid product data for comparison');
      return;
    }
    if (this.allCompareProducts.some(function (p) {
      return p.id === productData.id;
    })) {
      return;
    }
    if (this.allCompareProducts.length >= (McCompare.max_products || 4)) {
      return;
    }
    this.allCompareProducts = [productData];
    this.compareProducts = this.allCompareProducts.slice(0, this.getMaxDisplayed());
    this.updateCompareColumns();
    $('.moreconvert-compare-products-grid').empty();
    $('.moreconvert-compare-products-section').find('.moreconvert-compare-message').remove();
    $('.moreconvert-compare-products-count').empty();
    $('.moreconvert-compare-search-form .moreconvert-compare-input').val('');
    $('.moreconvert-compare-modal-content .moreconvert-compare-base-product-id').val(productData.id);
    this.fetchSuggestedProducts(productData.id);
    $('.moreconvert-compare-popup').fadeIn();
    this.disableBodyScroll();
  };

  /**
   * Add product to compare list.
   *
   * @param {Object} productData The product data.
   * @param {jQuery} $button The button element.
   */
  CompareManager.prototype.addProductToCompare = function (productData, $button) {
    if (!productData || !productData.id) {
      console.error('Invalid product data for adding to compare');
      return;
    }
    if (this.allCompareProducts.some(function (p) {
      return p.id === productData.id;
    })) {
      // replace productData with old
      var index = this.allCompareProducts.findIndex(function (p) {
        return p.id === productData.id;
      });
      if (index !== -1) {
        this.allCompareProducts[index] = productData;
        this.compareProducts[index] = productData;
        this.updateCompareColumns();
      }
      return;
    }
    if (this.allCompareProducts.length >= (McCompare.max_products || 4)) {
      return;
    }
    this.allCompareProducts.push(productData);
    this.compareProducts = this.allCompareProducts.slice(0, this.getMaxDisplayed());
    this.updateCompareColumns();
    $('.moreconvert-compare-add-product-popup-wrapper').fadeOut();
    $button.closest('.moreconvert-compare-product-card').remove();
    var loadedCount = $('.moreconvert-compare-products-grid').children().length;
    this.updateProductCount(loadedCount);
  };

  /**
   * Debounce function to limit the rate of execution.
   *
   * @param {Function} func The function to debounce.
   * @param {number} wait The wait time in milliseconds.
   * @return {Function} Debounced function.
   */
  CompareManager.prototype.debounce = function (func, wait) {
    var timeout;
    return function () {
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      var context = this;
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        return func.apply(context, args);
      }, wait);
    };
  };

  /**
   * Find matching variation based on attributes.
   *
   * @param {Array} variations The variations array.
   * @param {Object} chosen_attributes The chosen attributes.
   * @return {Object|null} Matching variation or null.
   */
  CompareManager.prototype.findMatchingVariation = function (variations, chosen_attributes) {
    if (!variations || !chosen_attributes || Object.keys(chosen_attributes).length === 0) {
      return null;
    }
    return variations.find(function (v) {
      return Object.keys(chosen_attributes).every(function (key) {
        return chosen_attributes[key] === '' || v.attributes[key] === chosen_attributes[key] || v.attributes[key] === '';
      });
    });
  };

  /**
   * Find matching variation by ID.
   *
   * @param {Array} variations The variations array.
   * @param {string} variation_id The variation ID.
   * @return {Object|null} Matching variation or null.
   */
  CompareManager.prototype.findMatchingVariationID = function (variations, variation_id) {
    if (!variations || !variation_id || variation_id.length === 0) {
      return null;
    }
    return variations.find(function (variation) {
      return variation.variation_id === variation_id;
    });
  };

  /**
   * Update available options for a product.
   *
   * @param {number} index The product index.
   */
  CompareManager.prototype.updateAvailableOptions = function (index) {
    if (this.isUpdating || !this.compareProducts[index]) {
      return; // Prevent recursion or invalid index.
    }
    this.isUpdating = true;
    var product = this.compareProducts[index];
    if (!product.has_variations) {
      this.isUpdating = false;
      return;
    }
    var selectedVar = this.findMatchingVariation(product.variations, product.selected_attributes) || this.findMatchingVariationID(product.variations, product.selected_variation_id);
    var $card = $(".moreconvert-compare-product-card:nth-child(".concat(index + 1, ")"));
    if (selectedVar) {
      product.selected_variation = selectedVar;
      product.attributes = selectedVar.formatted_attributes || [];
      product.image = selectedVar.image_thumbnail || product.parent_image;
      product.price = selectedVar.price_html || product.parent_price_html;
      product.add_to_cart_url = selectedVar.add_to_cart_url || product.parent_add_to_cart_url;
      product.is_purchasable = selectedVar.is_purchasable || false;
    } else {
      product.selected_variation = null;
      product.attributes = product.parent_attributes || [];
      product.image = product.parent_image || '';
      product.price = product.parent_price_html || '';
      product.add_to_cart_url = product.parent_add_to_cart_url || '';
      product.is_purchasable = false;
    }
    $card.find('.moreconvert-compare-product-image').attr('src', product.image);
    $card.find('.moreconvert-compare-price-text').html(product.price);
    //$card.find('.moreconvert-compare-add-to-cart').attr('action', product.add_to_cart_url);
    $card.find('input[name="variation_id"]').val(selectedVar ? selectedVar.variation_id : '');
    $card.find('input[name^="attribute_"]').remove();
    if (product.selected_attributes) {
      $.each(product.selected_attributes, function (key, value) {
        if (value !== '') {
          $card.find('.moreconvert-compare-add-to-cart').append("<input type=\"hidden\" name=\"".concat(key, "\" value=\"").concat(value, "\" />"));
        }
      });
    }
    this.compareProducts[index].attributes = selectedVar ? selectedVar.formatted_attributes : product.attributes;
    this.updateCompareColumns();
    this.syncVariationAttributes();
    this.isUpdating = false;
  };

  /**
   * Update compare columns in the UI.
   */
  CompareManager.prototype.updateCompareColumns = function () {
    var _this2 = this;
    var $grid = $('.moreconvert-compare-grid-layout').empty();
    var $title = $('.moreconvert-compare-specs-title').hide();
    var $specs = $('.moreconvert-compare-specs-content').empty();
    if (this.compareProducts.length === 0) {
      $('.moreconvert-compare-popup').fadeOut();
      this.enableBodyScroll();
      $('.moreconvert-compare-add-product-popup-wrapper').fadeOut();
      return;
    }
    $('.moreconvert-compare-base-product-id').val(this.compareProducts[0].id);
    $.each(this.compareProducts, function (index, product) {
      var $cardTemplate = wp.template('moreconvert-compare-product-card-template');
      var data = {
        product: product,
        index: index,
        add_to_cart_text: McCompare.texts.add_to_cart || 'Add to Cart'
      };
      $grid.append($cardTemplate(data));
    });
    $('.moreconvert-compare-variation-selector .variations_form').each(function (_this) {
      return function () {
        var $form = $(this);
        try {
          $form.wc_variation_form();
          $form.find('.variations select').trigger('change');
        } catch (e) {
          console.error('Error initializing variation form:', e);
        }
        $form.on('change', 'select', function (__this) {
          return function () {
            var $select = $(this);
            var attr_key = $select.data('attribute_name');
            var value = $select.val();
            var label = $select.find('option:selected').text();
            var index = $select.closest('.moreconvert-compare-variation-selector').data('index');
            if (__this.compareProducts[index]) {
              __this.compareProducts[index].selected_attributes[attr_key] = value;
              if (value !== '') {
                __this.compareProducts[index].selected_labels[attr_key] = label;
              }
              __this.updateAvailableOptions(index);
            }
          };
        }(_this));
        $form.on('found_variation', function (__this) {
          return function (e, variation) {
            var index = $(this).closest('.moreconvert-compare-product-card').data('index');
            if (__this.compareProducts[index]) {
              __this.compareProducts[index].selected_variation_id = variation.variation_id;
            }
          };
        }(_this));
      };
    }(this));
    if (this.allCompareProducts.length < (McCompare.max_products || 4) && this.compareProducts.length < this.getMaxDisplayed()) {
      var $buttonTemplate = wp.template('moreconvert-compare-add-button-template');
      var data = {
        select_product_text: McCompare.texts.select_product || 'Select a product'
      };
      $grid.append($buttonTemplate(data));
    }
    if (window.innerWidth > 992 && $grid.children().length < (McCompare.max_products || 4)) {
      for (var i = $grid.children().length; i < (McCompare.max_products || 4); i++) {
        $grid.append('<div class="moreconvert-compare-product-card moreconvert-compare-empty"></div>');
      }
    }
    var attrOrders = {};
    var attrLabels = {};
    this.compareProducts.forEach(function (product) {
      (product.attributes || []).forEach(function (attr) {
        if (!attrLabels[attr.key]) {
          attrLabels[attr.key] = attr.label;
        }
        if (!(attr.key in attrOrders) || attr.order && attr.order < attrOrders[attr.key]) {
          attrOrders[attr.key] = attr.order || 0;
        }
      });
    });
    var sortedKeys = Object.keys(attrOrders).sort(function (a, b) {
      return attrOrders[a] - attrOrders[b];
    });
    var attributes = {};
    sortedKeys.forEach(function (attrKey) {
      attributes[attrKey] = new Array(_this2.compareProducts.length).fill('');
    });
    this.compareProducts.forEach(function (product, index) {
      (product.attributes || []).forEach(function (attr) {
        attributes[attr.key][index] = attr.value || (product.selected_labels && product.selected_labels['attribute_' + attr.key] ? product.selected_labels['attribute_' + attr.key] : '');
      });
    });
    var attributeTemplate = wp.template('moreconvert-compare-add-attribute-template');
    $.each(attributes, function (attrKey, values) {
      if (!this.isEmptyArray(values)) {
        var name = attrLabels[attrKey] || attrKey;
        var _data = {
          key: attrKey,
          name: name,
          values: values,
          empty_cells: []
        };
        if (window.innerWidth > 992 && this.compareProducts.length < (McCompare.max_products || 4)) {
          for (var _i = this.compareProducts.length; _i < (McCompare.max_products || 4); _i++) {
            _data.empty_cells.push('');
          }
        }
        var $row = $(attributeTemplate(_data));
        $title.show();
        $specs.append($row);
      }
    }.bind(this));
    if (!this.isUpdating) {
      this.compareProducts.forEach(function (product, index) {
        if (product.has_variations) {
          _this2.updateAvailableOptions(index);
        }
      });
    }
  };

  /**
   * Check if an array is empty or contains only empty values.
   *
   * @param {Array} arr The array to check.
   * @return {boolean} True if array is empty or contains only empty values.
   */
  CompareManager.prototype.isEmptyArray = function (arr) {
    return arr.every(function (item) {
      return item == null || item === '' || item === undefined || item === '-';
    });
  };

  /**
   * Load products via AJAX.
   *
   * @param {string} baseProductId The base product ID.
   * @param {boolean} isSearch Whether it's a search request.
   * @param {boolean} append Whether to append products.
   */
  CompareManager.prototype.loadProducts = function (baseProductId) {
    var isSearch = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    var append = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    if (this.isLoading) {
      return;
    }
    this.isLoading = true;
    $('.moreconvert-compare-loading').show();
    var ajaxData = {
      action: isSearch ? 'moreconvert_compare_search_products' : 'moreconvert_compare_get_suggested_products',
      nonce: McCompare.ajax_nonce,
      base_product_id: baseProductId,
      paged: this.currentPage,
      limit: McCompare.limit || 10
    };
    if (isSearch) {
      ajaxData.search = this.currentQuery;
    }
    this.activeAjaxRequest = $.ajax({
      url: McCompare.ajax_url,
      type: 'POST',
      data: ajaxData,
      success: function (_this) {
        return function (response) {
          $('.moreconvert-compare-loading').hide();
          _this.isLoading = false;
          _this.activeAjaxRequest = null;
          if (response.success && response.data.products) {
            if (_this.currentPage > 0 && response.data.products.length === 0 && _this.totalProducts < (McCompare.limit || 10)) {
              return;
            }
            if (_this.currentPage > 0 && response.data.products.length === 0) {
              $('.moreconvert-compare-products-section').append('<div class="moreconvert-compare-message moreconvert-compare-no-more-products">' + (McCompare.texts.no_more_products || 'No more products') + '</div>');
              return;
            }
            if (_this.currentPage === 0 && response.data.products.length === 0) {
              $('.moreconvert-compare-products-grid').html('<div class="moreconvert-compare-message moreconvert-compare-no-products">' + (McCompare.texts.no_products || 'No products found') + '</div>');
              _this.updateProductCount(0);
              return;
            }
            var $grid = $('.moreconvert-compare-products-grid');
            if (!append) {
              $grid.empty();
              $('.moreconvert-compare-products-section').find('.moreconvert-compare-message').remove();
            }
            _this.totalProducts = response.data.total || 0;
            var loadedCount = $grid.children().length + response.data.products.length;
            _this.updateProductCount(loadedCount);
            if (_this.observer) {
              var previousLastCard = $('.moreconvert-compare-products-grid .moreconvert-compare-product-card:last-child')[0];
              if (previousLastCard) {
                _this.observer.unobserve(previousLastCard);
              }
            }
            $.each(response.data.products, function (index, product) {
              if (!_this.allCompareProducts.some(function (p) {
                return p.id === product.id;
              })) {
                var $itemTemplate = wp.template('moreconvert-compare-item-template');
                var data = {
                  productJson: JSON.stringify(product),
                  product: product,
                  add_to_compare_text: McCompare.texts.add_to_compare || 'Add to Compare'
                };
                $grid.append($itemTemplate(data));
              }
            });
            if (response.data.products.length > 0 && loadedCount < _this.totalProducts) {
              var $lastCard = $('.moreconvert-compare-products-grid .moreconvert-compare-product-card:last-child')[0];
              if ($lastCard) {
                if (!_this.observer) {
                  _this.observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                      if (entry.isIntersecting && !_this.isLoading) {
                        var totalPages = Math.ceil(_this.totalProducts / (McCompare.limit || 10));
                        if (totalPages > _this.currentPage) {
                          _this.currentPage++;
                          _this.loadProducts(baseProductId, _this.isSearchActive, true);
                        }
                      }
                    });
                  }, {
                    root: $('.moreconvert-compare-body')[0],
                    threshold: 0.1
                  });
                }
                _this.observer.observe($lastCard);
              }
            } else {
              if (_this.observer) {
                var lastCard = $('.moreconvert-compare-products-grid .moreconvert-compare-product-card:last-child')[0];
                if (lastCard) {
                  _this.observer.unobserve(lastCard);
                }
              }
              $('.moreconvert-compare-products-section').find('.moreconvert-compare-message').remove();
              if (_this.totalProducts >= (McCompare.limit || 10) && response.data.products.length === 0) {
                $('.moreconvert-compare-products-section').append('<div class="moreconvert-compare-message moreconvert-compare-no-more-products">' + (McCompare.texts.no_more_products || 'No more products') + '</div>');
              }
            }
          } else {
            $('.moreconvert-compare-products-grid').html('<div class="moreconvert-compare-message moreconvert-compare-no-products">' + (McCompare.texts.no_products || 'No products found') + '</div>');
            _this.updateProductCount(0);
          }
        };
      }(this),
      error: function (_this) {
        return function (jqXHR, textStatus) {
          $('.moreconvert-compare-loading').hide();
          _this.isLoading = false;
          _this.activeAjaxRequest = null;
          if (textStatus !== 'abort') {
            $('.moreconvert-compare-products-section').find('.moreconvert-compare-message').remove();
            $('.moreconvert-compare-products-grid').html('<div class="moreconvert-compare-message moreconvert-compare-error">' + (McCompare.texts.error || 'An error occurred') + '</div>');
            _this.updateProductCount(0);
            if (_this.observer) {
              var lastCard = $('.moreconvert-compare-products-grid .moreconvert-compare-product-card:last-child')[0];
              if (lastCard) {
                _this.observer.unobserve(lastCard);
              }
            }
          }
        };
      }(this)
    });
  };

  /**
   * Fetch suggested products.
   *
   * @param {string} baseProductId The base product ID.
   */
  CompareManager.prototype.fetchSuggestedProducts = function (baseProductId) {
    this.isSearchActive = false;
    this.currentQuery = '';
    this.currentPage = 1;
    this.totalProducts = 0;
    $('.moreconvert-compare-products-grid').empty();
    this.loadProducts(baseProductId, false);
  };

  /**
   * Update product count display.
   *
   * @param {number} loaded The number of loaded products.
   */
  CompareManager.prototype.updateProductCount = function (loaded) {
    var text = (McCompare.texts.product_count_template || '{loaded} of {totalProducts} products').replace('{loaded}', loaded).replace('{totalProducts}', this.totalProducts);
    $('.moreconvert-compare-products-count').text(text);
  };

  /**
   * Check screen width and adjust displayed products.
   */
  CompareManager.prototype.checkScreenWidth = function () {
    if (window.innerWidth < 993) {
      if (this.allCompareProducts.length > 2) {
        this.allCompareProducts = this.allCompareProducts.slice(0, 2);
      }
    }
    this.compareProducts = this.allCompareProducts.slice(0, this.getMaxDisplayed());
    this.updateCompareColumns();
  };

  /**
   * Extend jQuery.
   */
  $.fn.extend({
    wc_compare_manager: function wc_compare_manager() {
      return this.each(function () {
        new CompareManager($(this));
      });
    }
  });

  // Initialize the compare manager on DOM ready.
  $(function () {
    try {
      $(document.body).wc_compare_manager();
    } catch (e) {
      console.error('Error initializing CompareManager:', e);
    }
  });
})(jQuery, document);