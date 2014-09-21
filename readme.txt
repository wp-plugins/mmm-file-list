=== Mmm Simple File List ===
Contributors: MManifesto
Donate link: http://www.mediamanifesto.com/donate/
Tags: File List, Shortcode
Requires at least: 3.4
Tested up to: 4.0
Stable tag: 4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to list files in a given directory using a basic shortcode.

== Description ==

This is a simple plugin to list files in a given directory using this shortcode: [MMFileList  /].

Parameters:

* **folder**: Relative to the base uploads directory of your wordpress install directory.
* **format**: Unordered list (format="li") or comma-delimited (format="comma")
* **types**: Only list given file types (e.g. types="pdf,doc,txt")
* **class**: Only used for the "li" format, applies a given class to the unordered list (e.g. class="mmm-list")

== Installation ==

1. Download and install the plugin from WordPress dashboard. You can also upload the entire “MmmFileList” folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress


== Frequently Asked Questions ==

= Why should I use this plugin? =

Say you have a folder on your webserver with 30 files you want to list but you don't want to tediously write out the html, load them as media to your WordPress site or edit your htaccess to allow directory listing.  This would be the ideal case to have a quick and dirty solution that handles updates to files without additional work on your part.

= Are there other output formats available? =

Not at this time.  If you want to request them via the forums here then I can have them added fairly quickly.

== Screenshots ==

1. Sample of the "li" output used with a fairly large set of bylaws.

== Changelog ==

= 0.1a =
* Fixed a bug related to folders within the given path
* Updated support docs and plugin description to show that folder is the base uploads directory and not the base directory.

= 0.1 =
* Initial release to WordPress.org

== Upgrade Notice ==

= 0.1a =
If you're having trouble with folders in your chosen directory then you should upgrade to fix that bug.