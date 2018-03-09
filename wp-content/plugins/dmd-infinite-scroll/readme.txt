=== DMD Infinite Scroll ===
Contributors: deadmustdie
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5PGYEVW4EAS
Tags: infinite scroll, load more buttons, woocommerce infinite scroll, wordpress infinite scroll, ajax pagination
Requires at least: 4.2
Tested up to: 4.7.3
Stable tag: 0.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Infinite scroll and AJAX pagination for WooCommerce and WordPress

== Description ==

Plugin provide AJAX loading for WooCommerce products and can be used for any other posts. Paginate up to 10 sets of posts in the same template.

Can be compatible with any other AJAX plugins, that has option to call custom JavaScript code.

*   Infinite Scroll - Automatically load new products(posts) when the user scroll down and reaches the bottom of the products(posts).
*   Load More Button - Click to load new products(posts).
*   Pagination - Normal pagination but load the next page with AJAX.

Use one plugin for different pages and post types. Work great with WordPress and WooCommerce

= Features =
* Infinite scroll, load more buttons or AJAX pagination for posts and products.
* Multiple sets of settings.
* Infinite scroll and load more button for next page and previous page.
* Only the required number of pages is displayed at the same time.
* Page can be scrolled to the top, when products is replaced.
* Custom threshold for infinite scroll
* Custom load image can be loaded.
* Easy stylization for next page button and previous page button.
* Custom JavaScript code can be execute before or after posts(products) load
* HTML5 PushState

= Setup =
* Once the plugin is installed navigate in admin area to "DMD Infinite Scroll"( "DMD Plugins" -> "DMD Infinite Scroll" ).
* Select theme from drop down list "Presets". If your theme isn't listed then add the correct selectors.
* You can use special tool to setup selectors(work only for "Setting set 1")

1. Your site with posts(products) must contain at least 3(three) pages.
1. Open second page.
1. Add to the page link "?dmd_is=selectors" or "&dmd_is=selectors" if link already has "?" symbol.
1. Click on element and move to the previous element with "Parent" button on right and bottom.
1. Red border show element, that selected.
1. Click next if this is correct element.

* This is experimental tool. It may not work with some themes. It is also sometimes necessary to edit the result of yourself.
* If this tool isn't work for you, then use your browser developer tools.
* "Posts Selector" The selector that wraps all of the posts/products.
* "Post Selector" The selector of an individual post/product.
* "Pagination Selector" The selector of the post/product pagination.
* "Next Selector" The selector of the pagination next link.
* "Previous Selector" The selector of the pagination next link.
* "Pages Selector" The selector of the other pagination links.
* Choose settings, that you want.
* Click "Save Changes".

= Multiple sets of settings =
* Select in "Sets count" option how many sets you need.
* Click "Save and reload" button.
* You can navigate between settings sets with help of "Current set" drop down list.

= Styling =
* You can style buttons in admin area.
* Navigate to "DMD Infinite Scroll"( "DMD Plugins" -> "DMD Infinite Scroll" )
* Open "Styles" tab.
* Click on "Edit styles" button.
* Click option(Margin, Border, Padding, Content) to edit it.

* Also you can style any button with help of CSS code.
1. "div.dmd_next_page" - wraper for next page button. "div.dmd_next_page a.button" - next page button.
1. "div.dmd_previous_page" - wraper for previous page button. "div.dmd_previous_page a.button" - previous page button.
1. "div.dmd_ajax_product_load" - wraper for loading image. "div.dmd_ajax_product_load img" - loading image.

== Installation ==

= Upload in the WordPress Dashboard =
1. Click "Upload Plugin" in the plugins dashboard.
1. Choose "dmd-infinite-scroll.zip" from your computer.
1. Click "Install Now".
1. Activate the plugin in the plugins dashboard.

= Upload With FTP =
1. Upload the "dmd-infinite-scroll" folder to the "/wp-content/plugins/" directory.
1. Activate the plugin in the plugins dashboard.

== Frequently Asked Questions ==

= Why infinite scroll doesn't work on my site?(doesn't load products? doesn't remove pagination?) =

1. Please check, that you have correct selectors.
1. Check, that front end page with post(products) doesn't have JavaScript errors.

= How can I get feature, that plugin doesn't have? =

1. Create topic on support with feature request.
1. Wait for answer is it possible to add this feature and is it planned to add this feature.
1. If this is possible to add it, but you don't want to wait or it is not planned to add it, then you can send donation to provide stimulus to add this feature.

== Screenshots ==

1. /assets/screenshot-1.png

== Changelog ==

= 0.9.1 =
* Fixes: Some files was missed

= 0.9.0 =
* Features: Lazy Load for images
* Features: Animation for post/product with Lazy Load
* Features: Preset for Twenty Seventeen theme
* Features: More presets for WooCommerce
* Fixes: border styles doesn't work with some themes
* Fixes: WooCommerce products count fix
* Fixes: Settings page style

= 0.8.0 =
* First beta version(use it on live site on your own risk).

== Upgrade Notice ==

= 0.8.0 =
First public version. Can contains some bugs and doesn't work with some other plugins or themes.
Create topic on support with bugs or issues on your sites.