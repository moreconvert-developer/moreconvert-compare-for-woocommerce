<?php
/**
 * Compare Popup Template
 *
 * Displays the full-screen compare popup with search and product suggestions.
 *
 * @package MoreConvert Compare for WooCommerce
 * @version 1.0.0
 *
 * @var $fields_to_show array Fields to show in comparison table
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<script type="text/template" id="tmpl-moreconvert-compare-popup-template">
	<div class="moreconvert-compare-popup">
		<div class="moreconvert-compare-container">
			<div class="moreconvert-compare-loader-hidden"></div>
			<div class="moreconvert-compare-sticky-header">
				<div class="moreconvert-compare-grid-layout"></div>
			</div>
			<div class="moreconvert-compare-specs-container">
				<div>
					<div class="moreconvert-compare-specs-title" style="display:none">{{ data.specifications }}</div>
					<div class="moreconvert-compare-specs-content"></div>
				</div>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-moreconvert-compare-add-product-popup-template">
	<div class="moreconvert-compare-add-product-popup-wrapper">
		<div class="moreconvert-compare-add-product-popup">
			<div class="moreconvert-compare-modal-content">
				<div class="moreconvert-compare-header">
					<div class="moreconvert-compare-header-content">
						<div class="moreconvert-compare-search-container">
							<div class="moreconvert-compare-search-wrapper">
								<div class="moreconvert-compare-search-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
								</div>
								<div class="moreconvert-compare-search-input">
									<form class="moreconvert-compare-search-form" method="post">
										<input type="text" name="moreconvert_compare_search" autocomplete="off" class="moreconvert-compare-input" placeholder="{{ data.search_products }}" />
										<input type="hidden" name="moreconvert_compare_nonce" value="{{ data.moreconvert_compare_nonce }}" />
										<input type="hidden" name="moreconvert_compare_base_product_id" class="moreconvert-compare-base-product-id" value="0" />
									</form>
								</div>
								<div class="moreconvert-compare-close-button">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="moreconvert-compare-body">
					<div class="moreconvert-compare-products-container">
						<div class="moreconvert-compare-products-header">
							<div class="moreconvert-compare-products-title">{{ data.top_products }}</div>
							<div class="moreconvert-compare-products-count"></div>
						</div>
						<div class="moreconvert-compare-products-list">
							<div class="moreconvert-compare-products-section">
								<div class="moreconvert-compare-products-grid"></div>
								<div class="moreconvert-compare-loading" style="display: none;">
									<div class="moreconvert-compare-loading-circles">
										<div class="moreconvert-compare-loading-circle"></div>
										<div class="moreconvert-compare-loading-circle"></div>
										<div class="moreconvert-compare-loading-circle"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</script>
<script type="text/template" id="tmpl-moreconvert-compare-item-template">
	<div class="moreconvert-compare-product-card">
		<a class="moreconvert-compare-product-link" href="{{ data.product.url }}" target="_blank" data-product-id="{{ data.product.id }}" data-product='{{ data.productJson }}'>
			<div class="moreconvert-compare-product-content">
				<div class="moreconvert-compare-product-image-container">
					<div class="moreconvert-compare-image-wrapper">
						<picture>
							<source type="image/webp" srcset="{{ data.product.image }}?x-oss-process=image/resize,m_lfit,h_300,w_300/format,webp/quality,q_80" />
							<source type="image/jpeg" srcset="{{ data.product.image }}?x-oss-process=image/resize,m_lfit,h_300,w_300/quality,q_80" />
							<img class="moreconvert-compare-image" src="{{ data.product.image }}?x-oss-process=image/resize,m_lfit,h_300,w_300/quality,q_80" alt="{{ data.product.title }}" />
						</picture>
					</div>
				</div>
				<div class="moreconvert-compare-product-details">
					<div class="moreconvert-compare-product-title"> {{ data.product.title }} </div>
					<div class="moreconvert-compare-product-info">
						<div class="moreconvert-compare-product-price"> {{{ data.product.price }}} </div>
					</div>
				</div>
			</div>
		</a>
		<button class="moreconvert-compare-add-to-compare-button button" data-product-id="{{ data.product.id }}" data-product='{{ data.productJson }}'>
			{{ data.add_to_compare_text }}
		</button>
	</div>
</script>
<script type="text/template" id="tmpl-moreconvert-compare-product-card-template">
	<div class="moreconvert-compare-product-card" data-index="{{ data.index }}">
		<div class="moreconvert-compare-card-content">
			<?php if ( in_array( 'image', $fields_to_show, true ) ) : ?>
				<div class="moreconvert-compare-image-container">
					<div class="moreconvert-compare-image-wrapper">
						<a href="{{ data.product.url }}">
							<img class="moreconvert-compare-product-image" src="{{ data.product.image }}" width="100%" height="auto" alt="{{ data.product.title }}" />
							<div class="moreconvert-compare-remove-from-compare" data-product-id="{{ data.product.id }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
							</div>
						</a>

					</div>
				</div>
			<?php else : ?>
				<div class="moreconvert-compare-remove-from-compare" data-product-id="{{ data.product.id }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
				</div>
			<?php endif; ?>
			<?php if ( in_array( 'title', $fields_to_show, true ) ) : ?>
				<div class="moreconvert-compare-product-title"> {{ data.product.title }} </div>
			<?php endif; ?>
			<?php if ( in_array( 'price', $fields_to_show, true ) ) : ?>
			<div class="moreconvert-compare-price-container">
				<div>
					<div class="moreconvert-compare-price-align">
						<div class="moreconvert-compare-price-content">
							<div class="moreconvert-compare-price-text"> {{{ data.product.price }}} </div>
						</div>
						<div class="moreconvert-compare-currency-container"><div class="moreconvert-compare-currency-icon"></div></div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<?php if ( in_array( 'variations_p', $fields_to_show, true ) ) : ?>
				<# if (data.product.has_variations && data.product.loading_variations) { #>
					<div class="moreconvert-compare-variation-skeleton">
						<table class="skeleton-table moreconvert-compare-variation-table variations" id="skeletonTable">
							<tbody>
							<tr>
								<th><div class="skeleton-label"></div></th>
								<td>
									<div class="skeleton-input"></div>
								</td>
							</tr>
							<tr>
								<th><div class="skeleton-label"></div></th>
								<td>
									<div class="skeleton-input"></div>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				<# } else if (data.product.has_variations && data.product.variation_attributes) { #>
					<div class="moreconvert-compare-variation-selector first-time-loaded moreconvert-compare-variation-index-{{ data.index }}" data-index="{{ data.index }}">
						{{{ data.product.html_variations }}}
					</div>
				<# } #>
			<?php endif; ?>
			<?php if ( in_array( 'add_to_cart', $fields_to_show, true ) ) : ?>
			<div class="moreconvert-compare-button-container">
					<form class="moreconvert-compare-add-to-cart" action="{{ data.product.url }}" method="post">
						<input type="hidden" name="add-to-cart" value="{{ data.product.id }}" />
						<# if (data.product.has_variations && data.product.selected_variation) { #>
						<input type="hidden" name="variation_id" value="{{ data.product.selected_variation.variation_id }}" />
						<# _.each(data.product.selected_attributes, function(value, key) { #>
						<input type="hidden" name="{{ key }}" value="{{ value }}" />
						<# }); #>
						<# } #>

						<button type="submit" class="moreconvert-compare-add-to-cart-button button" {{{ false === data.product.is_purchasable ? "disabled" : "" }}} >
							{{ data.add_to_cart_text }}
						</button>

					</form>

			</div>
			<?php endif; ?>
		</div>
</script>
<script type="text/template" id="tmpl-moreconvert-compare-add-button-template">
	<div class="moreconvert-compare-section">
		<button class="moreconvert-compare-display-popup-button button">
			{{ data.select_product_text }}
		</button>
		<div class="moreconvert-compare-close-compare">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-moreconvert-compare-add-attribute-template">
	<div class="moreconvert-compare-specs-text-center moreconvert-compare-attr-{{{ data.key }}}">
		<div class="moreconvert-compare-specs-label">{{ data.name }}</div>
		<div class="moreconvert-compare-specs-grid">
			<# _.each(data.values, function(value) { #>
			<div class="moreconvert-compare-specs-item"><div class="moreconvert-compare-specs-value">{{{ value }}}</div></div>
			<# }); #>
			<# _.each(data.empty_cells, function() { #>
			<div class="moreconvert-compare-specs-item"><div class="moreconvert-compare-specs-value"></div></div>
			<# }); #>
		</div>
	</div>
</script>
