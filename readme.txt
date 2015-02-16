=== Mmm Simple File List ===
Contributors: MManifesto
Donate link: http://www.mediamanifesto.com/donate/
Tags: File List, Shortcode
Requires at least: 3.4
Tested up to: 4.1
Stable tag: 4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to list files in a given directory using a basic shortcode.

== Description ==

This is a simple plugin to list files in a given directory using this shortcode: [MMFileList /].


**Parameters:**

* **folder**: Relative to the base uploads directory of your wordpress install (e.g. siteurl.com/wp-content/uploads/mm/yy/ or siteurl.com/wp-content/ or siteurl.com/media).  You can check your media settings from your WordPress dashboard in Settings -> Media.  If you organize your uploads in the WordPress default month / year base folder you should either prepend this field with "/../../" or disable that setting before uploading files.
* **format**: Tabular (format="table") or Unordered list (format="li") or comma-delimited (format="comma") or Unordered List of Images (format="img" Note: this will put all files in <img> tags) or Custom (format="custom") for using the Shortcode content to create a custom template (see Custom Formats section below for more information)
* **types**: Only list given file types (e.g. types="pdf,doc,txt"), If no types are specified then all files in the directory will be listed.
* **class**: Only used for the "li", "img" and "table" formats, applies a given class to the shortcode output (e.g. &#60;ul class="mmm-list"&#62; / for more information on styling check out the FAQ)
* **limit**: The default value will list all files in the directory.  You can add a positive number of your choice and that amount of files will be displayed.
* **orderby**: Current params can be either "name" (default) or "date" which sorts the files by date modified since date created seems to be hit and miss.
* **target**: This parameter lets you set a "target" for the links to the listed files (This is typically used to open the files in a new window)


**Output:**

For all html formats you can expect to see the following output wrapped in styleable containers:

* Filename (linked to the File Url)
* File Size

At this point "comma" is the only available text output and it only outputs the url to the file in a comma delimited list (no links - just text).

If the folder you've entered isn't found or there are no files with the extensions you've listed there will be some warning text output to let you know.  This text is wrapped in a "mmm-warning" class in case you want to style it out (for more information on styling check out the FAQ)


**Usage Examples:**

Let's say you're using the default WordPress Media settings so we can expect your uploads folder to be in /wp-content/uploads/mm/yy/ with this in mind the shortcode "folder" attribute will look in a directory relative to this.  With this base directory say we want to list "png" files in the folder "/wp-content/uploads/cats/" we would use the following shortcode:

[MMFileList folder="/../../cats/" format="table" types="png" /]

If you have you disabled the setting to store uploads in the /mm/yy/ folder structure (you can do this within Settings -&#62; Media) and wanted to display that same file you would use this shortcode:

[MMFileList folder="/cats/" format="table" types="png" /]

This will result in a tabular list of all .png files in the /wp-content/uploads/cats/ folder.


**Custom Formats**

The "li" and "custom" formats allow you to define a template using the content portion of the shortcode.  The difference between these two output formats is that "li" will still wrap all the output in a &#60;ul&#62; tag and each file will be wrapped in a &#60;li&#62; tag.  Here is an example of how to create a custom template:

[MMFileList folder="/cats/" format="li"]&#60;div class="taco"&#62;&#60;a href="{url}"&#62;{name} ({size})&#60;/a&#62;&#60;/div&#62;[/MMFileList]

Variables that can be used with the custom templates are as follows:

* {name} - This will output the filename
* {size} - This will output the filesize
* {url} - This will output the file url
* If you would like more information available to be output don't hesitate to a send in a request via the support forum


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

= The "img" format keeps trying to display non-images, what gives? =

The "img" format outputs all files in <li><a><img></a></li> blocks so if you have non-image files in the same directory I recommend using the "types" parameter to specify only images should be displayed then include a second shortcode to include all the non-image types you want to display.

== Screenshots ==

1. Sample of the "li" output used with a fairly large set of bylaws.

== Changelog ==

= 1.3 =

* Updated human_filesize function to work according to the standardized International System.

= 1.2 =

* Fixed bug with array_map usage in 5.2.17

= 1.1 =

* Fixed missing break; in the format switch

= 1.0 =

* Added hooks for shortcode content to customize output of some templates
* Added a "custom" output format for use with the content hook
* Organized the code and updated some comments to reflect the hook change
* Added some "borrowed" code to fix the empty <p> tag issue common with WordPress shortcodes

= 0.7 =

* Added "img" output format to create an Unordered List of Images.
* Changed code so all files in the directory will output if no types are listed (this may annoy some people but it makes the experience much friendlier for new people).

= 0.6a =

* Added slightly more robust path code so trailing / preceeding slashes aren't so tricky to work with.  e.g. say you want to load files from a folder called "cats".  You can now simply have folder="cats" instead of having to include the first slash "/cats" and having to avoid using the trailing slash "/cats/".  Any variation should work along with handling of cases when too many slashes are added "//cats//".

= 0.6 =

* Reworked how the class parameter is passed through the code so more information can be sent along with it
* Added the option to include target for the links using the aforementioned method

= 0.5 =
* Added limit and orderby params to the shortcode
* Changed the code to check if the directory exists before trying to get the files (this fixes the issue with warning messages from being displayed while having debug mode enabled)

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

= 0.5 =
New shortcode params and some code fixes for users running in debug mode.

= 0.4 =
Debug text added in case you're not seeing files when you expect them to appear.

= 0.3 =
Adds functionality and some code cleanup.

= 0.2 =
If you're having trouble with folders in your chosen directory then you should upgrade to fix that bug.