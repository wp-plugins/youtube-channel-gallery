=== Youtube Channel Gallery ===
Contributors: javitxu123
Donate link: http://poselab.com/
Tags: widget, gallery, youtube, channel, user
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show a youtube video and a gallery of thumbnails for a youtube channel.

== Description ==

Show a youtube video and a gallery of thumbnails for a youtube user channel. 

= Features: =
* Display latest thumbnail videos from YouTube user channel.
* When you click on one of the thumbnails the video plays at the top.
* This plugin uses the YouTube IFrame player API that allows YouTube to serve an HTML5 player, rather than a Flash player, for mobile devices that do not support Flash.
* You can choose to use this plugin as a widget or as a shortcode.
* You can use multiple instances of the plugin on the same page.


= Demo: =
You can see a demo of the plugin at the following URL:

[Youtube Channel Gallery Demo](http://poselab.com/youtube-channel-gallery)


= Widget fields: =
Description of the different fields of the widget.

* Title: Widget Title.
* YouTube user name: the username of the user's Youtube videos you want to show.

Player:

* Video width: indicates the width of the video player. Value: Number.
* Aspect ratio: indicates the proportions of the player, widescreen (16:9) or standard (4:3) format.
* Theme: display player controls (like a 'play' button or volume control) within a dark or light control bar.
* Progress bar color: specifies the color that will be used in the player's video progress bar to highlight the amount of the video that the viewer has already seen.
* Autoplay: Automatically play the initial video when the player loads.
* Show related videos: Load related videos once playback of initial video starts and display in "genie menu" when menu button is pressed.
* Show info (title, uploader): Display information like the video title and rating before the video starts playing.

Thumbnails:

* Number of videos to show: It must be a number indicating the number of thumbnails to be displayed.
* Thumbnail size: indicates the width of the thumbnails. The height is automatically generated.
* Aspect ratio: indicates the proportions of the thumbnails, widescreen (16:9) or standard (4:3) format.
* Thumbnail columns: assign a numeric class to each thumbnail based on the number of columns to apply styles to each column.

Link:

* Link text: field to customize the text of the link to the gallery on YouTube.
* Show link to channel: option to display a link to the youtube user channel.


= Shortcode syntax: =
If you want to use it as Shortcode:

`[Youtube_Channel_Gallery 
user="MaxonC4D" 
videowidth="500" 
ratio="16/9" 
theme="light" 
color="white" 
autoplay="1" 
rel="1" 
showinfo="1" 
maxitems="9" 
thumbwidth="90" 
thumbratio="16/9" 
thumbcolumns="3"
]`

The attributes used in the shortcode are the same as the fields available in the widget, except the title field.

* user: YouTube user name (required).

Player:

* videowidth: Video width. Values: Number. (optional).
* ratio: Aspect ratio. Values:  4/3 (default) / 16/9. (optional).
* theme: Theme. Values: dark (default) / light. (optional).
* color: Progress bar color. Values: red (default) / white. (optional).
* autoplay: Autoplay. Values: 0 (default) / 1. (optional).
* rel: Show related videos. Values: 0 (default) / 1. (optional).
* showinfo: Show info (title, uploader). Values: 0 (default) / 1. (optional).

Thumbnails:

* maxitems: Number of videos to show. Values: Number. (optional).
* thumbwidth: Thumbnail size. Values: Number. (optional).
* thumbratio: Aspect ratio. Values: 4/3 (default) / 16/9. (optional).
* thumbcolumns: Thumbnail columns. Values: Number. (optional).

Link:

* link_tx: Link text. Values: String. (optional).
* link: Show link to channel. Values: 0 (default) / 1. (optional).

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

= 1.5.3 =
* Added tabs to the widget interface to better organize the fields.
* Added new fields to control the player (Aspect ratio, Progress bar color, Autoplay, Show related videos, Show info).
* Added Aspect ratio field to thumbnails.
* Added Link text field to Links.
* Added class to last thumbnail of each row to delete the margin-right in CSS.
* Added class to first thumbnail of each row to clear float in CSS.
* Check that the inserted user name exists.
* Changes in CSS.

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
* Trying to correct issue that some users have with the origin parameter of the player.

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



== Upgrade Notice ==

= 1.5.3 =
* New fields to control the player