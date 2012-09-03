<?php
/*
Plugin Name: Youtube Channel Gallery
Plugin URI: http://www.poselab.com/
Description: Show a youtube video and a gallery of thumbnails for a youtube channel.
Author: Javier Gómez Pose
Author URI: http://www.poselab.com/
Version: 1.5.2
License: GPL2
	
	Copyright 2010 Javier Gómez Pose  (email : javierpose@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/**
 * widget class.
 */
class YoutubeChannelGallery_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		//localization
		load_plugin_textdomain('youtube-channel-gallery', false, dirname(plugin_basename( __FILE__ ) ) . '/languages/' ); 
		add_shortcode('Youtube_Channel_Gallery', array($this, 'YoutubeChannelGallery_Shortcode'));  
		
		//load admin scripts
		add_action('admin_print_scripts', array($this, 'register_admin_scripts_and_styles'));
		
		parent::__construct(
			'youtubechannelgallery_widget', // Base ID
			 __( 'Youtube Channel Gallery', 'youtube-channel-gallery' ), // Name
			array( 'description' => __( 'Show a youtube video and a gallery of thumbnails for a youtube channel', 'youtube-channel-gallery' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {

		// Load JavaScript and stylesheets  
		$this->register_scripts_and_styles();


		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			echo $this->ytchag_rss_markup($instance);

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ytchag_user'] = strip_tags( $new_instance['ytchag_user'] );

		// Player options
		$instance['ytchag_video_width'] = strip_tags( $new_instance['ytchag_video_width'] );
		$instance['ytchag_ratio'] = strip_tags( $new_instance['ytchag_ratio'] );
		$instance['ytchag_theme'] = strip_tags( $new_instance['ytchag_theme'] );
		$instance['ytchag_color'] = strip_tags( $new_instance['ytchag_color'] );
		$instance['ytchag_autoplay'] = strip_tags( $new_instance['ytchag_autoplay'] );
		$instance['ytchag_rel'] = strip_tags( $new_instance['ytchag_rel'] );
		$instance['ytchag_showinfo'] = ( isset( $new_instance['ytchag_showinfo'] ) ? 0 : 1 );  
		$instance['ytchag_origin'] = strip_tags( $new_instance['ytchag_origin'] );		

		// Thumbnail options
		$instance['ytchag_maxitems'] = strip_tags( $new_instance['ytchag_maxitems'] );
		$instance['ytchag_thumb_width'] = strip_tags( $new_instance['ytchag_thumb_width'] );
		$instance['ytchag_link_tx'] = strip_tags( $new_instance['ytchag_link_tx'] );

		// Link options
		$instance['ytchag_link'] = $new_instance['ytchag_link'];

		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$title      = esc_attr($instance['title']);
		$ytchag_user = strip_tags($instance['ytchag_user']);

		// Player options
		$ytchag_video_width = strip_tags($instance['ytchag_video_width']);
		$ytchag_ratio = strip_tags($instance['ytchag_ratio']);
		$ytchag_theme = strip_tags($instance['ytchag_theme']);
		$ytchag_color = strip_tags($instance['ytchag_color']);
		$ytchag_autoplay = strip_tags($instance['ytchag_autoplay']);
		$ytchag_rel = strip_tags($instance['ytchag_rel']);
		$ytchag_showinfo = strip_tags($instance['ytchag_showinfo']);
		$ytchag_origin = strip_tags($instance['ytchag_origin']);

		// Thumbnails options
		$ytchag_maxitems = strip_tags($instance['ytchag_maxitems']);
		$ytchag_thumb_width = strip_tags($instance['ytchag_thumb_width']);
		$ytchag_link_tx = strip_tags($instance['ytchag_link_tx']);

		// Link options
		$ytchag_link = esc_attr($instance['ytchag_link']);

		?>


			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'youtube-channel-gallery' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'ytchag_user' ); ?>"><?php _e( 'YouTube user name:', 'youtube-channel-gallery' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_user' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_user' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_user ); ?>" />
			</p>

			<p>
				<a class="edit-player-options hide-if-no-js">Change player options...</a>
				<fieldset class="player-options-select" style="display: none; ">
    				<legend >Player options:</legend>

					<p>
						<label for="ytchag_video_width"><?php _e( 'Video width:', 'youtube-channel-gallery' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_video_width' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_video_width' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_video_width ); ?>" />
					</p>

					<p>

						<label for="ytchag_ratio"><?php _e( 'Aspect ratio:', 'youtube-channel-gallery' ); ?></label>
						<select class="widefat" id="<?php echo $this->get_field_id( 'ytchag_ratio' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_ratio' ); ?>">
							<option value="4x3"<?php selected( $instance['ytchag_ratio'], '4x3' ); ?>><?php _e( 'Standard (4x3)', 'youtube-channel-gallery' ); ?></option>
							<option value="16x9"<?php selected( $instance['ytchag_ratio'], '16x9' ); ?>><?php _e( 'Widescreen (16x9)', 'youtube-channel-gallery' ); ?></option>
						</select>
					</p>

					<p>

						<label for="ytchag_theme"><?php _e( 'Theme:', 'youtube-channel-gallery' ); ?></label>
						<select class="widefat" id="<?php echo $this->get_field_id( 'ytchag_theme' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_theme' ); ?>">
							<option value="dark"<?php selected( $instance['ytchag_theme'], 'dark' ); ?>><?php _e( 'Dark', 'youtube-channel-gallery' ); ?></option>
							<option value="light"<?php selected( $instance['ytchag_theme'], 'light' ); ?>><?php _e( 'Light', 'youtube-channel-gallery' ); ?></option>
						</select>
					</p>

					<p>

						<label for="ytchag_color"><?php _e( 'Progress bar color:', 'youtube-channel-gallery' ); ?></label>
						<select class="widefat" id="<?php echo $this->get_field_id( 'ytchag_color' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_color' ); ?>">
							<option value="red"<?php selected( $instance['ytchag_color'], 'red' ); ?>><?php _e( 'Red', 'youtube-channel-gallery' ); ?></option>
							<option value="white"<?php selected( $instance['ytchag_color'], 'white' ); ?>><?php _e( 'White', 'youtube-channel-gallery' ); ?></option>
						</select>
					</p>
											         
						<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_autoplay'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_autoplay' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_autoplay' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'ytchag_autoplay' ); ?>"><?php _e('Autoplay', 'youtube-channel-gallery'); ?></label>

					<br>

						<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_rel'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_rel' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_rel' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'ytchag_rel' ); ?>"><?php _e('Show related videos', 'youtube-channel-gallery'); ?></label> 

					<br>

						<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_showinfo'], false ); ?> id="<?php echo $this->get_field_id( 'ytchag_showinfo' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_showinfo' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'ytchag_showinfo' ); ?>"><?php _e('Show info (title, uploader)', 'youtube-channel-gallery'); ?></label> 

					<br>

						<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_origin'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_origin' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_origin' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'ytchag_origin' ); ?>"><?php _e('Extra security (Uncheck if it does not work properly)', 'youtube-channel-gallery'); ?></label> 
						

				</fieldset>
			</p>



			<p>
				<a class="edit-thumbnails-options hide-if-no-js">Change thumbnails options...</a>
				<fieldset class="thumbnails-options-select" style="display: none; ">
    				<legend >Thumbnails options:</legend>

		
					<p>
						<label for="ytchag_maxitems"><?php _e( 'Number of videos to show:', 'youtube-channel-gallery' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_maxitems' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_maxitems' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_maxitems ); ?>" />
					</p>    
				
					<p>
						<label for="ytchag_thumb_width"><?php _e( 'Thumbnail width:', 'youtube-channel-gallery' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_thumb_width ); ?>" />
					</p>
				
					<p>
						<label for="ytchag_link_tx"><?php _e( 'Thumbnail columns:', 'youtube-channel-gallery' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_link_tx' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_link_tx' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_link_tx ); ?>" />
					</p>

				</fieldset>
			</p>



			<p>
				<a class="edit-link-options hide-if-no-js">Change link options...</a>
				<fieldset class="link-options-select" style="display: none; ">
    				<legend >Thumbnails options:</legend>

				
					<p>
						<label for="ytchag_link_tx"><?php _e( 'Link text:', 'youtube-channel-gallery' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_link_tx' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_link_tx' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_link_tx ); ?>" />
					</p>

    					<p>
						<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_link'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_link' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_link' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'ytchag_link' ); ?>"><?php _e('Show link to channel', 'youtube-channel-gallery'); ?></label>
					</p>

				</fieldset>					
			</p>    	


		<?php 
	}


	/*--------------------------------------------------*/ 
	/* Private Functions 
	/*--------------------------------------------------*/
	private function ytchag_rss_markup($instance){

		//$instance variables
		//----------------
			$ytchag_user = apply_filters('ytchag_user', $instance['ytchag_user']);

			// Player options
			$ytchag_video_width = apply_filters('ytchag_video_width', $instance['ytchag_video_width']);
			$ytchag_ratio = apply_filters('ytchag_ratio', $instance['ytchag_ratio']);
			$ytchag_theme = apply_filters('ytchag_theme', $instance['ytchag_theme']);
			$ytchag_color = apply_filters('ytchag_color', $instance['ytchag_color']);
			$ytchag_autoplay = apply_filters('ytchag_autoplay', $instance['ytchag_autoplay']);
			$ytchag_rel = apply_filters('ytchag_rel', $instance['ytchag_rel']);
			$ytchag_showinfo = apply_filters('ytchag_showinfo', $instance['ytchag_showinfo']);
			$ytchag_origin = apply_filters('ytchag_origin', $instance['ytchag_origin']);

			// Thumbnail options
			$ytchag_maxitems = apply_filters('ytchag_maxitems', $instance['ytchag_maxitems']);
			$ytchag_thumb_width = apply_filters('ytchag_thumb_width', $instance['ytchag_thumb_width']);
			$ytchag_link_tx = apply_filters('ytchag_link_tx', $instance['ytchag_link_tx']);
			$ytchag_link_tx = apply_filters('ytchag_link_tx', $instance['ytchag_link_tx']);

			// Link options
			$ytchag_link = apply_filters('ytchag_link', $instance['ytchag_link']);

		//end $instance variables

		//defaults
		//----------------
			// Player options
			$ytchag_video_width = ( $ytchag_video_width ) ? $ytchag_video_width : 250;
			$ytchag_theme = ( $ytchag_theme ) ? '&theme='. $ytchag_theme : '';
			$ytchag_color = ( $ytchag_color ) ? '&color='. $ytchag_color : '';
			$ytchag_autoplay = ( $ytchag_autoplay ) ? '&autoplay=1' : '';
			$ytchag_rel = ( $ytchag_rel ) ? '' : '&rel=0';
			$ytchag_showinfo = ( $ytchag_showinfo ) ? '&showinfo=0' : '';
			$ytchag_origin = ( $ytchag_origin ) ? '&origin='. site_url()  : '';

			// Thumbnail options
			$ytchag_thumb_width = ( $ytchag_thumb_width ) ? $ytchag_thumb_width : 85;
			$ytchag_link_tx = ( $ytchag_link_tx ) ? $ytchag_link_tx : 0;
			$ytchag_link_tx = ( $ytchag_link_tx ) ? $ytchag_link_tx : __('Show more videos»', 'youtube-channel-gallery');

		//end defaults



		//heights of video and thumbnail
		if ($ytchag_ratio == '16x9') {
			$ytchag_video_heigh = round( ($ytchag_video_width * 9) / 16);
		} else {
			$ytchag_video_heigh = round( ($ytchag_video_width * 3) / 4);
		}

		$ytchag_thumb_height = $ytchag_thumb_width*75/100; // 75% 'cos sizes of thumbnail in xml file are 480x360 and 120x90

		if( $ytchag_user ) { // only if user name inserted 
			
			// links
			$ytchag_rss_url 	= "http://gdata.youtube.com/feeds/api/users/" . $ytchag_user . "/uploads";
			$ytchag_link_url 	= "http://www.youtube.com/user/" . $ytchag_user;
			
			if (  file_get_contents($ytchag_rss_url) == '' ) { // check if correct user name

				$content.= '<p class="empty">' .  __('You must insert a valid YouTube user name.', 'youtube-channel-gallery') . '</p>';

			} else{

				//RSS Feed
				
				include_once(ABSPATH . WPINC . '/feed.php');
				
				$rss = fetch_feed($ytchag_rss_url);
				$maxitems = ( $ytchag_maxitems ) ? $ytchag_maxitems : 9;
				$items = $rss->get_items(0, $maxitems);
				

				if (!empty($items)) {
					$i = 0;
					$column = 0;
					foreach ( $items as $item ) {

						$url = $item->get_permalink();
						$youtubeid = $this->youtubeid($url);
						$title = $item->get_title();

						if ($enclosure = $item->get_enclosure()){

							//extract thumbnail
							//-----------------

							//thumbnail index in xml
							$big = 0;
							$small = 1;
							$size = $small;
							if($ytchag_thumb_width > '120'){
								$size = $big;
							}

							$allThumbs = $enclosure->get_thumbnails();
							foreach ($allThumbs as $index => $allThumb) {
								if ($index == $size) {
									$thumb = $allThumbs[$index];
								}
							}
						}

						//Show the player: iframe player
						if($i == 0) {
							//count the plugin occurrences on page
							STATIC $plugincount = 0;
							$plugincount++;
							
						$content = '<iframe id="ytcplayer' . $plugincount . '" class="ytcplayer" allowfullscreen width="' . $ytchag_video_width . '" height="' . $ytchag_video_heigh . '" src="http://www.youtube.com/embed/' . $youtubeid . '?version=3' . $ytchag_theme . $ytchag_color .  $ytchag_autoplay . $ytchag_rel . $ytchag_showinfo . $ytchag_origin .'&enablejsapi=1" frameborder="0"></iframe>';
						$content.=	'<ul class="ytchagallery">';

							
						} // if player end
						$i++;

						$column++;

						// list of thumbnail videos						
						$content.= '<li class="ytccell-' . $column . '">';
						$content.= '<a class="ytcthumb" href="http://www.youtube.com/watch?v=' . $youtubeid . '" target="_blank" onclick="ytcplayVideo(event, \'ytcplayer' . $plugincount . '\', \'' . $youtubeid . '\');" alt="' . $title . '" title="' . $title . '" style="background-image: url(' . $thumb . ');">';
						$content.= '<div class="ytcplay" style="width: ' . $ytchag_thumb_width . 'px; height: ' . $ytchag_thumb_height . 'px"></div></a></li>';

					
						if($ytchag_link_tx !=0 && $column%$ytchag_link_tx === 0){
							$column = 0;
						}
					} //foreach end

					$content.= '</ul>';

						//link to youtube.com gallery
					if( $ytchag_link) {
						$content.= '<a href="' . $ytchag_link_url . '" class="more">' . $ytchag_link_tx . '</a>';
					}
				}

			}
		} else {
			$content.= '<p class="empty">' .  __('You must insert a YouTube user name.', 'youtube-channel-gallery') . '</p>';
		}
		return $content;
	}//ytchag_rss_markup

	//parse youtube url to extract id
	private function youtubeid($url) {
		$url_string = parse_url($url, PHP_URL_QUERY);
		parse_str($url_string, $args);
		return isset($args['v']) ? $args['v'] : false;
	}//youtubeid


	// load css or js
	private function register_scripts_and_styles() {
			wp_enqueue_script('youtube_player_api', 'http://www.youtube.com/player_api', false, false, true);
			wp_enqueue_script('youtube-channel-gallery', plugins_url('/scripts.js', __FILE__), false, false, true);
			wp_enqueue_style('youtube-channel-gallery', plugins_url('/styles.css', __FILE__), false, false, 'all');
	}//register_scripts_and_styles


	public function register_admin_scripts_and_styles($hook) {
		// load in admin > widgets.php
		//if( 'widgets.php' != $hook ) 
		//	return;
			wp_enqueue_script('youtube-channel-gallery', plugins_url('/admin-script.js', __FILE__), false, false, true);
			wp_enqueue_style('youtube-channel-gallery', plugins_url('/admin-styles.css', __FILE__), false, false, 'all');
	}



	/*--------------------------------------------------*/ 
	/* Shortcode 
	/*--------------------------------------------------*/

	public function YoutubeChannelGallery_Shortcode($atts) {

		// Load JavaScript and stylesheets  
		$this->register_scripts_and_styles();

		extract( shortcode_atts( array(
			'user' => '',

			// Player options
			'videowidth' => '250',
			'ratio' => '',
			'theme' => '',
			'color' => '',
			'autoplay' => '',
			'rel' => '',
			'showinfo' => '',
			'origin' => '',

			// Thumbnail options
			'maxitems' => '9',
			'thumbwidth' => '85',
			'thumbcolumns' => '0',
			'link' => '',
			'link_tx' => ''

		), $atts ) );

		$instance['ytchag_user'] = $user;

		// Player options
		$instance['ytchag_video_width'] = $videowidth;
		$instance['ytchag_ratio'] = $ratio;
		$instance['ytchag_theme'] = $theme;
		$instance['ytchag_color'] = $color;
		$instance['ytchag_autoplay'] = $autoplay;
		$instance['ytchag_rel'] = $rel;
		$instance['ytchag_showinfo'] = $showinfo;
		$instance['ytchag_origin'] = $origin;
			
		// Thumbnail options
		$instance['ytchag_maxitems'] = $maxitems;
		$instance['ytchag_thumb_width'] = $thumbwidth;
		$instance['ytchag_link_tx'] = $thumbcolumns;

		// Link options
		$instance['ytchag_link'] = $link;
		$instance['ytchag_link_tx'] = $link_tx;


		return $this->ytchag_rss_markup($instance);

	} // YoutubeChannelGallery_Shortcode


} // class YoutubeChannelGallery_Widget

// register YoutubeChannelGallery_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "YoutubeChannelGallery_Widget" );' ) );

//check valid user name
//correct bug in shortcode position
//correct css hover thumbnails
//added new fields to control other Youtube player options
//Added video url on thumbnails to improve link syntax, accessibility and SEO
//Redesign of widget interface for managing so many fields
//improved control of height player
//improved error handling in javascript. Now, if the API fails it uses the link to Youtube
//added field to customize the text link to youtube 


//uninstall
?>