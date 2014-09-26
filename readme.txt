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

**Parameters:**

* **folder**: Relative to the base uploads directory of your wordpress install (e.g. siteurl.com/wp-content/uploads/mm/yy/ or siteurl.com/wp-content/ or siteurl.com/media).  You can check your media settings from your WordPress dashboard in Settings -> Media.  If you organize your uploads into a month / year base folder you should either prepend the field with "/../../" or disable that setting.
* **format**: Tabular (format="table") or Unordered list (format="li") or comma-delimited (format="comma")
* **types**: Only list given file types (e.g. types="pdf,doc,txt")
* **class**: Only used for the "li" and "table" formats, applies a given class to the unordered list (e.g. class="mmm-list" / for more information on styling check out the FAQ)

**Output:**

For all html formats you can expect to see the following output wrapped in styleable containers:

* Filename (linked to the File Url)
* File Size

At this point "comma" is the only available text output and it only outputs the url to the file in a comma delimited list (no links - just text).

If the folder you've entered isn't found or there are no files with the extensions you've listed there will be some warning text output to let you know.  This text is wrapped in a "mmm-warning" class in case you want to style it out (for more information on styling check out the FAQ)

**Usage Examples:**

Let's say you're using the default WordPress Media settings so we can expect your uploads folder to be in /wp-content/uploads/mm/yy/ with this in mind the shortcode "folder" attribute will look in a directory relative to this.  With this base directory say we want to list "png" files in the folder "/wp-content/uploads/cats/" we would use the following shortcode:

[MMFileList folder="/../../cats/" format="table" types="png" /]

If you have you disabled the setting to store uploads in the /mm/yy/ folder structure (you can do this within Settings -> Media) and wanted to display that same file you would use this shortcode:

[MMFileList folder="/cats/" format="table" types="png" /]

This will result in a tabular list of all .png files in the /wp-content/uploads/cats/ folder.  It's important to ensure that you add the first "/" in that folder attribute to ensure you don't end up with the system looks for a directory like /wp-content/uploadscats/.


== Installation ==

1. Download and install the plugin from WordPress dashboard. You can also upload the entire “MmmFileList” folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress


== Frequently Asked Questions ==

= Why should I use this plugin? =

Say you have a folder on your webserver with 30 files you want to list but you don't want to tediously write out the html, load them as media to your WordPress site or edit your htaccess to allow directory listing.  This would be the ideal case to have a quick and dirty solution that handles updates to files without additional work on your part.

= Are there other output formats available? =

Not at this time.  If you want to request them via the forums here then I can have them added fairly quickly.

= Why not have a settings page or upload functionality? =

The idea behind this plugin is to be really simple and not mess with your site.  The plugin file itself is designed so that you could just copy / paste it into your functions.php and it would run without even needing to worry about a plugin.  If you are looking for a full featured file manager you should take a look at [File Away](https://wordpress.org/support/view/plugin-reviews/file-away).

= How can I style the list with the class I've added? =

If you have admin access to your site or your theme allows you to add custom styles you can add CSS for the classes you've added into there.

Example:

If you want to remove the warning text that is output when folders / files are not found you can add the style:

.mmm-warning {display: none;}

== Screenshots ==

1. Sample of the "li" output used with a fairly large set of bylaws.

== Changelog ==

= 0.4 =
* Added some output to show if the folder was not found or if there were no files of the given extension(s) found in the directory
* Note: These new messages are wrapped in divs with a "mmm-warning" class so they can be styled to be hidden.

= 0.3 =
* Added "table" output format
* Added "filesize" to information that is output (this should automatically format to the nearest reasonable size B,K,M,G etc..)
* Adjusted how the file array is built so it's more extensible
* General Code Cleanup (naming changes, readibility prioritized over condensed & dehydrated code)

= 0.2 =
* Fixed a bug related to folders within the given path
* Updated support docs and plugin description to show that folder is the base uploads directory and not the base directory.

= 0.1 =
* Initial release to WordPress.org

== Upgrade Notice ==

= 0.4 =
Debug text added in case you're not seeing files when you expect them to appear.

= 0.3 =
Adds functionality and some code cleanup.

= 0.2 =
If you're having trouble with folders in your chosen directory then you should upgrade to fix that bug.