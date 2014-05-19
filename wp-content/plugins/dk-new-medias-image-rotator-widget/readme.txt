=== Image Rotator Widget ===
Contributors: srcoley, douglaskarr
Tags: jquery, widget, widgets, image, images, rotator, slider
Requires at least: 2.7
Tested up to: 3.5.1
Stable tag: 0.2.9

Bare bones image rotator.

== Description ==

**Overview**

A widgetized plugin that puts an image rotator on your theme. You can choose from three different transitions: linear, loop, and fade. In the widget settings you have the ability to upload/select images to add to the Image Rotator, and to drag & drop to reorder the order you want the images to appear. Watch the video below for a short demonstation of workflow. 

http://www.youtube.com/watch?v=D7YMN8b0Olg

**Features**

* Use the WordPress 3.5 Media Uploader to upload or select images
* Make images click through to a link
* Choose from three different smooth transitions
* Set a transition speed
* Drag & drop to order images in whichever way you like
* Use the Image Rotator Widget multiple times on the same page
* Widgetized, so you can rotate your images anywhere 
* Works with modern versions of Chrome, Safari, Firefox, and Internet Explorer

**Includes**

* `jQuery`.`qtip`
* `jQuery`.`imagesLoaded`

**About**

The Image Rotator Widget was written by [Stephen Coley](http://coley.co) of [DK New Media](http://dknewmedia.com)

== Installation ==

Here's how you can get started quickly.

1. Upload `image-rotator-widget` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to `Appearance` -> `Widgets`
1. Drag the `Image Rotator Widget` box to an empty slot
1. Choose transition type
1. Add desired images
1. Sort images in any fashion
1. Save!

== Frequently Asked Questions ==

= How do I link an image in the rotator? =

To assign a link to an image, use the "Link To" option while in the Media Uploader.

= Does this plugin require a Widget-ready theme? =

Yes. Without a Widget-ready theme, you will not be able to display the image rotator.

= What if I still have a question? =

You can ask questions [Here](http://www.dknewmedia.com/#contact "DK New Media Contact Page")

== Screenshots ==

1. DK New Media's Image Rotator Widget.
2. Once you've selected or uploaded the image, you must click "Send to Image Rotator."
3. Images added to the widget are listed in dynamically in the Images section.
4. Multiple images have been added to this widget
4. Hover over the image name to see a tooltip that contains the image.
5. Drag & Drop images to sort, right in the widget settings.

== Changelog ==

= 0.1.1 =

* readme.txt updates
* bugfix to only run widget's scripts on the widget.php page

= 0.1.2 =

* WordPress 3.3 fix

= 0.1.3 =

* Added ability to use the "From URL" tab on the Media Upload when selecting an image.

= 0.1.4 =

* Added optional widget setting "title".
* Fixed localization bug with "Send the Image Rotator Widget" button

= 0.1.5 =

* Fixed installation bug

= 0.1.6 =

* Fixed 3.4 a bug - wouldn't send the image url to the widget
* Added Transition Speed to the widget settings

= 0.1.7 =

* Fixed 3.4.1 bug
* Added ability to make images in the rotator linkable

= 0.1.8 =
* Fixes bug with linking images

= 0.1.9 =
* Fixes 0.1.8 bad release.

= 0.2 =
* Fixes 0.1.9 bad release.

= 0.2.1 =
* Fixes error that shows "unexpected output" when activating.

= 0.2.2 = 
* Bugfix - Trasistion Speed has been fixed for all three transitions
* Bugfix - Images set to link to themselves would not work
* Update - Now includes the latest release of imagesloaded.js

= 0.2.3 =
* Feature - Added the ability to open links in new a tab/window.
* Bugfix - Some images were clicking through to a link that was undefined. This has been fixed.
* Bugfix - Mouse now only turns to the pointer when an image is linked.
* Bugfix - Transition speed for the fade transition now works properly.

= 0.2.4 =
* Update - Now uses the 3.5 Media Uploader
* Update - Added responsive styles as suggested: http://wordpress.org/support/topic/responsive-css?replies=2
* Bugfix - Height issue when using two or more irw widgets on the same page
* Bugfix - Better compatibility for users upgrading from older versions

= 0.2.5 =
* Bugfix - No more disappearing links after adding/removing images or after changing the order of the images
* New Feature - An option to add a nofollow attribute to image links
* Optimization - Adds width and height attributes to the image elements
* Optimization - Links now work with anchor tags instead of a javascript on click events

= 0.2.6 =
* Bugfix - PHP getimagesize() is now used with a local file path instead of a url.

= 0.2.7 =
* New Feature - Added a feature to randomize the order of images. 

= 0.2.8 =
* Bug Fix - Switched $_SERVER['DOCUMENT_ROOT'] to get_home_path() to reduce getimagesize() errors

= 0.2.9 =
* Bug Fix - Stripped out additional url slashes to prevent getimagesize() errors.
* Bug Fix - Applied patch provided by deltafactory(thanks!) to help relieve mixed content warnings on ssl sites.
* Bug Fix - Added auto width and heights to images, per Tukkan's suggestion, to fix a bug where images weren't appearing in IE.
* Bug Fix - Changed the widget's z-index to 0 to prevent unwanted overlapping.

== Upgrade Notice ==

= 0.1.1 =
Works with WordPress 3.3. This version fixes the bug that broke the "Insert into Post" button on the new/edit post pages. 

= 0.1.2 =
Fixes a bug in 3.3 that adds an image's thumbnail url instead of the original url, to the widget.

= 0.1.3 =
Added ability to use the "From URL" tab on the Media Upload when selecting an image.

= 0.1.4 =

Added optional widget setting "title" and fixed localization bug with "Send the Image Rotator Widget" button

= 0.1.5 =

Fixed installation bug

= 0.1.6 =

Fixed 3.4 a bug - wouldn't send the image url to the widget
Added Transition Speed to the widget settings

= 0.1.7 =

This update fixes a WordPress 3.4.1 bug and allows you to make images in your rotator linkable!

= 0.1.8 =

This update fixes a bug some were experiencing with adding links to images.

= 0.1.9 =

If you were having issues making your images linkable, update now. The problem has been fixed.

= 0.2 =

Those of you having trouble with linking images, please update now.

= 0.2.1 =

This update solves a non-harmful bug that showed a warning notice while activating the plugin.

= 0.2.2 =

This update fixes the transition speed setting for ALL transitions. I have also fixed a few bugs related to linking images. The imagesloaded.js library included in the plugin has been updated to the latest version.

= 0.2.3 =

With version 0.2.3 you can open linked images in a new tab/window. This version also fixes the issue where some images were linking to undefined links. Also, now a users cursor will only turn to a pointer if the image they're hovering over is linked. Also fixes a fade transition speed bug. Update now!

= 0.2.4 =

You can now add images to your rotator using WordPress' new 3.5 Media Uploader. We've also added better responsive support and fixed a few bugs related to linking images.

= 0.2.5 =

Now you can set your links to nofollow. Also no more disappearing links! This was happening to some users when they tried to add or remove images and when changing the image order. This bug has been fixed. The plugin mark-up has been optimized and requires less javascript.

= 0.2.6 =

This update aims to fix the 'Warning: getimagesize()' bug. PHP's getimagesize() is now used with a local file path instead of a url.

= 0.2.7 =

Added a feature to randomize the order of images. 

= 0.2.8 =

This update helps resolve the 'getimagesize' error that was appearing for some users.

= 0.2.9 =

This update fixes several issues that were brought to our attention on the forums. Fixed some getimagesize() issues, SSL mixed content warning issues, Internet Explorer issues with displaying images, and an issue with the widget overlapping dropdown menus.
