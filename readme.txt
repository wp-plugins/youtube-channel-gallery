=== Youtube Channel Gallery ===
Contributors: javitxu123
Donate link: http://poselab.com/
Tags: widget, gallery, youtube, channel, user
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show a YouTube video and a gallery of thumbnails for a youtube channel.

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
Description of the different fields of the plugin:

* Title: Widget Title.
* YouTube user name: the username of the user's Youtube videos you want to show. Shortcode attribute: user; value: String. (Required).

Player:

* Video width: indicates the width of the video player. Shortcode attribute: videowidth; value: Number. (Optional).
* Aspect ratio: indicates the proportions of the player, standard (4:3) or widescreen (16:9) format. Shortcode attribute: ratio; values: 4x3 (default) or 16x9. (Optional).
* Theme: display player controls (like a 'play' button or volume control) within a dark or light control bar. Shortcode attribute: theme; values: dark (default) or light. (Optional).
* Progress bar color: specifies the color that will be used in the player's video progress bar to highlight the amount of the video that the viewer has already seen. Shortcode attribute: color; values: red (default) or white. (Optional).
* Autoplay: automatically play the initial video when the player loads. Shortcode attribute: autoplay; values: 0 (default) or 1. (Optional).
* Show related videos: this parameter indicates whether the player should show related videos when playback of the initial video ends. Shortcode attribute: rel; values: 0 (default) or 1. (Optional).
* Show info (title, uploader): display information like the video title and rating before the video starts playing. Shortcode attribute: showinfo; values: 0 (default) or 1. (Optional).

Thumbnails:

* Number of videos to show: it must be a number indicating the number of thumbnails to be displayed. Shortcode attribute: maxitems; value: Number. (Optional).
* Thumbnail width: indicates the width of the thumbnails. The height is automatically generated based on the aspect ratio selected. Shortcode attribute: thumbwidth; value: Number. (Optional).
* Aspect ratio: indicates the proportions of the thumbnails, standard (4:3) or widescreen (16:9) format. Shortcode attribute: thumbratio; values: 4x3 (default) or 16x9. (Optional).
* Thumbnail columns: it allows to control the number of columns in which the thumbnails are distributed. Shortcode attribute: thumbcolumns; value: Number. (Optional).
* Show title: it displays the title of the thumbnail with a link to play the video in the player. Shortcode attribute: title; values: 0 (default) or 1. (Optional).
* Show description: it shows the description of the thumbnail with the number of specified words. Shortcode attribute: description; values: 0 (default) or 1. (Optional).
* Thumbnail alignment: it defines the alignment of the thumbnail respect to its description and title. Shortcode attribute: thumbnail_alignment; values: left (default), right, top or bottom. (Optional).
* Description words number: the maximum number of words displayed in the description. Shortcode attribute: descriptionwordsnumber; value: Number. (Optional).

Link:

* Link text: field to customize the text of the link to the gallery on YouTube. Shortcode attribute: link_tx; value: String. (Optional).
* Show link to channel: option to display a link to the youtube user channel. Shortcode attribute: link; values: 0 (default) or 1. (Optional).


= Shortcode syntax: =
In the following example are all attributes that can be used with the shortcode and explained above:

`[Youtube_Channel_Gallery user="MaxonC4D" videowidth="500" ratio="16x9" theme="light" color="white" autoplay="1" rel="1" showinfo="1" maxitems="9" thumbwidth="90" thumbratio="16x9" thumbcolumns="3" title="1" description="1" thumbnail_alignment="left" descriptionwordsnumber="10"]`


= Languages: =
* Spanish (es_ES) - [PoseLab](http://poselab.com/)

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
2. Youtube Channel Gallery example.
3. Youtube Channel Gallery example.
4. Youtube Channel Gallery example.


== Changelog ==

= 1.6.1 =
* Added options to show title and description with thumbnails.
* Added new classes to better manage the final appearance (rows, columns, even, odd, number of row an column).
* Calculated width between thumbnails.

= 1.5.4 =
* Corrected error when file_get_contents() is disabled in the server configuration by allow_url_fopen=0.
* Corrected error with Show info (title, uploader) field.

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