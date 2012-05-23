=== Pinterest "Pin It" Button ===
Contributors: pderksen
Tags: pinterest, pin it, social
Requires at least: 3.0
Tested up to: 3.3.2
Stable tag: trunk

Add a Pinterest "Pin It" Button to your site to let your visitors easily pin your awesome content!

== Description ==

Add a Pinterest "Pin It" Button to your site to let your visitors easily pin your awesome content!

### "Pin It" Button Features: ###

* Specify image to pin or let the reader select
* Add horizontal or vertical pin count
* Display or hide on any post, page or category
* Custom CSS for aligning just right
* Includes widget and full shortcode support

= Resources =

* [View Live Demo &raquo;](http://bruisesandbandaids.com/2011/newborn-photography-props/)
* [Pinterest Plugin Updates &raquo;](http://pinterestplugin.com)
* [User Support & Feature Requests &raquo;](http://pinterestplugin.com/support)

= More Features =

* Display above and/or below content
* Optionally display on post excerpts
* Optionally specify URL, image and description for each button

Take advantage of the exploding traffic Pinterest is generating by encouraging your readers to pin your content using this simple "Pin It" button.

**Pinterest Drives More Traffic Than Google+, YouTube and LinkedIn Combined** - Mashable [[link]](http://mashable.com/2012/02/01/pinterest-traffic-study/)

**Pinterest Rate of Referral Now Close to Twitter, Google+** - The Wall Street Journal [[link]](http://blogs.wsj.com/tech-europe/2012/02/03/pinterest-rate-of-referral-now-close-to-twitter-google/)

**Pinterest Hits 10 Million U.S. Monthly Uniques Faster Than Any Standalone Site Ever** - TechCrunch [[link]](http://techcrunch.com/2012/02/07/pinterest-monthly-uniques/)

= More Pinterest Plugins =

* [Top Pinned Posts](http://pinterestplugin.com/)
* ["Follow" Button](http://pinterestplugin.com/follow-button)
* [Pinterest Block](http://pinterestplugin.com/pinterest-block)

== Installation ==

**Finding and installing through the WordPress admin:**

1. If searching for this plugin in your WordPress admin, search for "pin it button".
1. Find the plugin that's labeled *Pinterest "Pin It" Button" with "Pin It" in quotes.
1. Also look for my name as the author (*Phil Derksen*). There are other "Pin It" button plugins which is why this can be confusing.
1. Click "Install Now", then Activate, then head to the new menu item on the left labeled "Pin It Button".

Here's a video walkthrough of the install process:

http://www.youtube.com/watch?v=NsEGeVpmD0Y

**Alternative installation methods:**

* Download this plugin, then upload through the WordPress admin (Plugins > Add New > Upload)
* Download this plugin, unzip the contents, then FTP upload to the `/wp-content/plugins/` directory

Note: If you overwrite the plugin using an FTP upload, you may lose some saved settings.

== Frequently Asked Questions ==

**The "Pin It" button is showing up, but clicking it does nothing. What can I try?**

Here are some things to try. After each one re-test a couple pages on your site to see if that fixed it.

* Clear your browser cache.
* Log out of your WordPress admin and refresh the page with the button(s).
* Test in a different browser.
* If using any WordPress caching plugin, please empty/clear it (examples: W3 Total Cache, WP-Cache and WP SuperCache).
* See known plugin conflicts below.
* Disable other social sharing plugins being used.
* Disable other plugins one by one until the issue is fixed. (Please let us know if you find and incompatible plugin.
* Switch to a different theme temporarily, preferably the default WordPress TwentyEleven theme. Please let us know if you find an incompatible theme.
* If you find a different solution that fixes the issue for you (or find an incompatible plugin or theme), please let us know.

**Known plugin conflicts**

* W3 Total Cache: Make sure Minify mode is set to Manual, not Auto.
* Google Analytics for WordPress: Make sure "Track outbound clicks as pageviews" is un-checked under Advanced Settings.

**How do I display the button in places other than above or below the content?**

* Use the shortcode `[pinit]` to display the button within content. See shortcode instructions on settings page for attributes you can specify.
* Use the function `<?php echo do_shortcode('[pinit]'); ?>` to display within template or theme files.

**How do I get the "Pin It" button to line up next to my other social sharing icons?**

* Add custom CSS on the settings screen and optionally remove the surrounding `<div>` tag.
* See some of our [custom CSS examples](http://pinterestplugin.com/pin-it-button-custom-css/).

**I had an old version of the plugin that worked and now it doesn't. Can I get it back?

* Yes, you can [download previous versions here](http://wordpress.org/extend/plugins/pinterest-pin-it-button/download/).
* You'll need to deactivate and delete the current plugin, then go to Plugins > Add New > Upload to upload the zip file to your site.

**I'd like to report a bug or submit a feature request.**

* Go to the [User Support & Feature Requests Forum](http://pinterestplugin.com/support)

== Screenshots ==

1. Settings page
2. Button display below a post
3. Widget options
4. Per page settings
5. Advanced settings

== Changelog ==

= 1.3.1 =
* Changed: Modified button JavaScript to be in line with Pinterest's current button embed JavaScript
* Changed: Split up internal code files for easier maintenance and updates
* Fixed: For the shortcode, if the attributes "url", "image_url" and/or "description" aren't specified, it will try and use the post's custom page url, image url and/or description if found. If not found it will default to the post's url, first image in post and post title.
* Fixed: Changed the way defaults are set upon install so it shouldn't override previous settings
* Fixed: Uninstall now removes custom post meta fields

= 1.3.0 =
* Added: Added a Pin Count option (horizontal or vertical)
* Added: Added new button style where image is pre-selected (like official Pinterest button)
* Added: Added fields for specifying URL, image URL and description for new button style **image pre-selected**
* Added: Added float option for alignment (none, left or right) to widget and shortcode
* Added: Can now remove div tag wrapper surrounding shortcode
* Added: Can now remove widget surrounding div tag wrapper
* Changed: Moved "Follow" button widget to separate plugin: [Pinterest "Follow" Button](http://wordpress.org/extend/plugins/pinterest-follow-button/)
* Changed: Both button styles now embed iframe (like official Pinterest button)
* Changed: External JavaScript now loads in footer for better performance
* Fixed: Fixed bug where front page was still showing button even when Front Page was unchecked
* Fixed: Fixed bug where some settings weren't saved when upgrading the plugin
* Fixed: Fixed bug where tag, author, date and search archive pages were not displaying the button

= 1.2.1 =
* Fixed: Fixed bug with hiding posts/pages/categories when upgrading from a previous version

= 1.2.0 =
* Added: Added option to hide button per page/post
* Added: Added option to hide button per category
* Added: Added widget to display "Pin It" button
* Added: Added widget to display "Follow" on Pinterest button
* Fixed: Fixed CSS where some blogs weren't displaying the button properly

= 1.1.3 =
* Added: Added option to hide button on individual posts and pages (on post/page editing screen)

= 1.1.2 =
* Fixed: Removed use of session state storing for now as it caused errors for some

= 1.1.1 =
* Fixed: Updated jQuery coding method to avoid JavaScript conflicts with other plugins and themes some were getting

= 1.1.0 =
* Added: Added custom CSS area for advanced layout and styling
* Added: Added checkbox option to remove the button's surrounding `<div>` tag
* Added: Button image and style updated to match Pinterest's current embed code
* Fixed: Changed the way the button click is called to solve pinning issues in Internet Explorer

= 1.0.2 =
* Added: Added checkbox option to display/hide button on post excerpts
* Fixed: "Pin It" links generated by the shortcode should not show up when viewing the post in RSS readers

= 1.0.1 =
* Added: Added checkbox option to display/hide button on "front page" (sometimes different than home page)

= 1.0.0 =
* Added: Added checkbox options to select what types of pages the button should appear on
* Added: Display options above and below content are now checkboxes (one or both can be selected)
* Added: Added shortcode [pinit] to display button within content

= 0.1.2 =
* Changed: Moved javascript that fires on button click to a separate file

= 0.1.1 =
* Fixed style sheet reference

= 0.1.0 =
* Initial release
