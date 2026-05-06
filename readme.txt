=== MoreConvert Compare for WooCommerce ===
Contributors: moreconvert
Donate link: https://moreconvert.com/donations/moreconvert-compare-for-woocommerce/
Tags: woocommerce, compare, product compare, woocommerce compare, product comparison
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 8.0
WC requires at least: 6.3
WC tested up to: 10.7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A Powerful WooCommerce plugin for product comparison. Features customizable table, variable product support, GDPR compliance and optimized performance
== Description ==

MoreConvert Compare for WooCommerce is a feature-rich plugin designed to help your customers make informed purchasing decisions by enabling easy side-by-side product comparisons. With customizable buttons, tables, and text labels, it integrates seamlessly into your WooCommerce store.

### Key Features:
- **Add to Compare Buttons**: Automatically add comparison buttons to single product pages and shop loops, or use shortcodes for custom placement.
- **AJAX-Powered Functionality**: Smooth, no-page-reload adding/removing of products to the comparison table.
- **Customizable Comparison Table**: Display product images, prices, reviews, stock status, attributes, and more in a responsive popup or page.
- **Variable Product Support**: Handle variations with dropdown selectors for accurate comparisons.
- **Search and Suggestions**: Built-in product search and suggested products based on categories/tags.
- **Admin Settings**: Easy-to-use settings for button styles, positions, custom CSS, and text translations.
- **Shortcodes**: Use `[moreconvert_compare_button]` for custom buttons anywhere on your site.
- **Performance Optimized**: External CSS options and non-persistent caching for fast loading.
- **GDPR Compliant**: No personal data is collected or stored; uses browser session-based storage only, cleared upon browser closure.

Perfect for stores with multiple similar products, like electronics, clothing, or furniture. Boost conversions by reducing cart abandonment through better product discovery.

### Privacy Policy
MoreConvert Compare for WooCommerce uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

== External Services ==

This plugin relies on the following third party/external services:
* **Appsero**: This plugin uses the Appsero SDK to collect basic telemetry data upon user confirmation. This data helps us troubleshoot problems faster and make product improvements. It sends non-sensitive telemetry data (such as WordPress version, PHP version, and plugin settings) to `https://api.appsero.com`. [Privacy Policy](https://appsero.com/privacy-policy/)
* **icanhazip.com**: As part of the Appsero SDK, the plugin may make a request to `https://icanhazip.com/` to determine the server's public IP address for analytical geolocation purposes. [Privacy Policy](https://www.cloudflare.com/privacypolicy/)

== Installation ==

1. Upload `moreconvert-compare-for-woocommerce.zip` to the `/wp-content/plugins/` directory
2. Activate the Plugin through the 'Plugins' menu in WordPress
3. Ensure WooCommerce is installed and active (version 6.3 or higher).
4. Go to **MC Compare > Settings** in your WordPress admin to configure button positions, styles, and enable the feature.
5. Optionally, customize text labels under **MC Compare > Text**.
6. Add comparison buttons to your theme if not automatic, or use the shortcode `[moreconvert_compare_button]` for custom placement.

== Frequently Asked Questions ==

= Where does the "Add to Compare" button appear? =
By default, buttons appear before/after the add-to-cart button on single product pages and in shop loops. Customize positions in **Settings > General**.

= Can I compare variable products? =
Yes! Variable products show variation selectors in the comparison table for precise attribute matching.

= How do I translate text labels? =
Use **MC Compare > Text** to override default labels like "Add to Compare" or "Specifications".

= Does it support custom attributes? =
Absolutely. All WooCommerce product attributes are displayed in the comparison table.

= Is the comparison table responsive? =
Yes, it's fully responsive and works on mobile devices.

= Can I add a compare button via shortcode? =
Use `[moreconvert_compare_button product_id="123"]` to add a button for a specific product.

= What if I need custom CSS? =
Enable "Custom CSS" in **Settings > Advanced** to add your own styles.

== Screenshots ==

1. **Admin Settings Page**: general options.
2. **Admin Settings Page**: button styles and positions.
3. **Admin Settings Page**: table options.
4. **Text Customization**: Easily translate and customize all user-facing text.
5. **Frontend Compare Button**: Sleek "Add to Compare" button on product pages.
6. **Comparison Popup Table**: Side-by-side product comparison with images, prices, and attributes.
7. **Product Search in Popup**: Search for additional products to compare dynamically.
8. **Comparison Popup Table**: Side-by-side product comparison with images, prices, and attributes.


== Changelog ==

= 1.0.0 =

* Initial release.
