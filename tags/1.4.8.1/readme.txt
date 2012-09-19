=== Youtube Channel Gallery ===
Contributors: javitxu123
Donate link: http://poselab.com/
Tags: widget, gallery, youtube, channel, user
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.4.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show a youtube video and a gallery of thumbnails for a youtube channel.

== Description ==

Show a youtube video and a gallery of thumbnails for a youtube user channel. 

= Features: =
* Display latest thumbnail videos from YouTube user channel.
* When you click on one of the thumbnails the video plays at the top.
* This plugin uses the YouTube IFrame player API that allows YouTube to serve an HTML5 player rather than a Flash player for mobile devices that do not support Flash.
* You can choose to use this plugin as a widget or as a shortcode.
* You can use multiple instances of the plugin on the same page.

= Widget fields: =
* Title: Widget Title.
* YouTube user name: the username of the user's Youtube videos you want to show.
* Show link to channel: option to display a link to the youtube user channel.
* Number of videos to show: It must be a number indicating the number of thumbnails to be displayed.
* Video width: indicates the width of the video player.
* Thumbnail size: indicates the width of the thumbnails. The height is automatically generated.
* Thumbnail columns: assign a numeric class to each thumbnail based on the number of columns to apply styles to each column.
* Theme: select the youtube player theme (dark or light).

= Shortcode syntax: =
If you want to use it as Shortcode:

`[Youtube_Channel_Gallery user="MaxonC4D" maxitems="3" theme="dark"]`

The attributes used in the shortcode are the same as the fields available in the widget, except the title field.

* user: YouTube user name (required).
* link: Show link to channel. Values: 0 or 1. (optional).
* maxitems: Number of videos to show (optional).
* videowidth: Video width (optional).
* thumbwidth: Thumbnail size (optional).
* thumbcolumns: Thumbnail columns (optional).
* theme: Theme. Values: dark or light (optional).


= Demo: =
You can see a demo of the plugin at the following URL:

[Youtube Channel Gallery Demo](http://poselab.com/youtube-channel-gallery)

= Languages: =
* Spanish (es_ES) - [PoseLab](http://poselab.com/)
* Brazilian Portuguese (pt_BR). Thanks to Rodny.

If you have created your own language pack, or have an update of an existing one, you can [send me](mailto:javierpose@gmail.com) your gettext PO and MO so that I can bundle it into the Youtube Channel Gallery.


== Installation ==

1. Upload the *.zip copy of this plugin into your WordPress through your 'Plugin' admin page.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place the widget in your desired sidebar through the "widgets" admin page.

== Frequently Asked Questions ==

= Where is the “widgets” admin page? =

The “widgets” admin page is found in the administrator part (wp-admin) of your WordPress site. Go to Appearance > Widgets.

= How do I find the YouTube user name? =

The username who uploaded a video to Youtube is located below each video, where says something like in this example, "Published on June 25, 2012 by DisneyShorts", where DisneyShorts is the username.

== Screenshots ==

1. Youtube Channel Gallery admin area.
2. Youtube Channel Gallery.

== Changelog ==

= 1.4.8.1 =
* Fixed warning: Cannot modify header information...

= 1.4.8 =
* Fixed bug with shortcode position.
* Deleted decimals to thumbnail heights.
* Added background-size to CSS of thumbnails to control image size.
* Added Brazilian Portuguese (pt_BR). Thanks to Rodny.

= 1.4.7 =
* Removed parameter 'origin' from Youtube iframe Player to solve the issue that some users have on clicking the thumbnails.

= 1.4.6 =
* Tweak on CSS.
* Trying to correct issue that some users have with the player origin parameter.

= 1.4.5 =
* Tweaks on CSS.
* Now the CSS and JS files are loaded only on the page in which appears the plugin.
* Correction on JS, because it did not work in IE and FF.
* Code organization. More OOP.

= 1.4.2 =
* Fixed issue with CSS.

= 1.4.1 =
* Added width and height to player.
* Reverted name variable prefixes.

= 1.4 =
* Added shortcode feature.
* Multiple instances of the plugin on the same page.
* Added theme selector.
* Improved use of Iframe YouTube Player API (now synchronous).
* Added effect: hover on thumbnails to display a play button. 

= 1.0 =
* Initial Release.