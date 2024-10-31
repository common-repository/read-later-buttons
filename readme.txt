=== Read Later Buttons ===
Contributors: csixty4 
Donate link: http://www.giffordcatshelter.org/donations/
Tags: instapaper, pocket, readability, kindle, read later, readlater, widget
Requires at least: 3.5
Tested up to: 3.9
Stable tag: 1.2.1
License: MIT
License URI: http://opensource.org/licenses/MIT

Adds "read later" buttons to a site. Can send content to Kindle (additional plugin required), Instapaper, Pocket, and Readability.

== Description ==

Read Later Buttons provides a widget and a shortcode for displaying "read later" buttons for the popular apps Instapaper, Pocket, and Readability. It can also display a [Send to Kindle](http://wordpress.org/extend/plugins/send-to-kindle/) button if that plugin is installed, letting your users send your content to their e-reader to enoy when they have the time.

This plugin is designed to function well and look good without much fuss, but it also provides a shortcode and filters so you can control how the buttons look & work.

**Shortcode**

Use the `[read_later_buttons]` shortcode to render buttons for every one of the supported services. You can also include the names of one or more services (i.e. `[read_later_buttons instapaper pocket]`) to just get those buttons. Valid service names are `kindle`, `instapaper`, `pocket`, and `readlater`.

The shortcode can also display an estimated reading time if you include the "time" parameter (`[read_later_buttons time]`). The reading time can be combined with service names (`[read_later_buttons time instapaper]`) and the time will render above the buttons. When including the time estimate, the plugin won't display all the buttons by default. You need to include the "all" parameter (i.e. `[read_later_buttons time all]`) in that case. Also, the shortcode has to be used inside "the loop" in order to calculate a time -- in other words in a page, post, or the appropriate parts of your theme template.

**Filters**

1. read_later_buttons_do_css: return false to prevent loading the default CSS (doesn't affect the Kindle plugin)
1. read_later_buttons_instapaper: alter the HTML generated for the Instapaper button
1. read_later_buttons_pocket: alter the HTML generated for the Pocket button
1. read_later_buttons_readability: alter the HTML generated for the Readability button
1. read_later_buttons_reading_time: alter the HTML generated for the reading time estimate
1. read_later_buttons_link: alter or replace the link passed to the read later services (doesn't affect the Kindle plugin)

== Installation ==

Install Read Later Buttons automatically from your admin account by selecting "Plugins", then "Add new" from the sidebar menu. Search for Read Later Buttons, then choose "Install Now".

or

Download the latest Read Later Buttons archive from wordpress.org.
Unzip the archive and upload the read_later_buttons directory to the /wp-content/plugins/ directory on your WordPress site.
Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I enable the Kindle button? =

Install and activate the [Send to Kindle](http://wordpress.org/extend/plugins/send-to-kindle/) plugin. Read Later Buttons will detect that the Kindle button is available and offer a checkbox for it on the widget configuration form.

= The plugin uses a lot of CSS to style the buttons. Can I get rid of it and start from scratch? = 

Sure! In your theme's functions.php file, include the following code:

`function disable_read_later_buttons_css($status) {
	return false;
}
add_filter('read_later_buttons_do_css', 'disable_read_later_buttons_css');`

That will remove the CSS from the Instapaper, Pocket, and Readlater buttons whether you're using the widget or the shortcode. You'll have to find your own way to alter the Kindle button's styling.

== Screenshots ==

1. Read Later buttons, with a reading time estimate, added to a post using the shortcode. Of course, this is just a demonstration. You can use the shortcode anywhere shortcodes are supported, even adding them to your page templates by calling [do_shortcode()](http://codex.wordpress.org/Function_Reference/do_shortcode).
2. The Read Later Buttons widget in the sidebar.
3. The widget can be configured to show any combination of services, letting you balance features with space in your sidebar.

== Changelog ==

= 1.2.1 =
* Code optimizations

= 1.2 =
* French & Spanish translations by Gabriel Vivas

= 1.1 =
* Use SVG images where supported (except for Kindle)

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.2.1 =
* Version 1.2 was released prematurely. It shouldn’t have broken anything on your sites, but it wasn’t tested like it should have been. So, I’m releasing version 1.2.1 with some code improvements & optimizations that shouldn’t change anything in your experience or your readers’, but bring the code more in-line with WordPress coding standards in case someone wants to read the source to the plugin & learn from it. Thanks again to Gabriel Vivas for the excellent Spanish & French translations that led to this new version.

= 1.1 =
* This release adds Javascript to figure out if a browser can display SVG images and uses them if possible. This means your Instapaper, Pocket, and Readlater buttons get Retina display-ready vector images wherever they're supported. All other users continue to get the same PNG images used in v1.0, which still weren't too bad.

= 1.0 =
First official release
