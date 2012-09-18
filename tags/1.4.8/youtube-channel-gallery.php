	<?php
	/*
	Plugin Name: Youtube Channel Gallery
	Plugin URI: http://www.poselab.com/
	Description: Show a youtube video and a gallery of thumbnails for a youtube channel.
	Author: Javier Gómez Pose
	Author URI: http://www.poselab.com/
	Version: 1.4.8
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
				if ( ! empty( $title ) ){
					echo $before_title . $title . $after_title;
				}

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
			
			$instance['ytchag_link'] = $new_instance['ytchag_link'];
			$instance['ytchag_maxitems'] = strip_tags( $new_instance['ytchag_maxitems'] );
			$instance['ytchag_video_width'] = strip_tags( $new_instance['ytchag_video_width'] );
			$instance['ytchag_thumb_width'] = strip_tags( $new_instance['ytchag_thumb_width'] );
			$instance['ytchag_thumb_columns'] = strip_tags( $new_instance['ytchag_thumb_columns'] );
			$instance['ytchag_theme'] = strip_tags( $new_instance['ytchag_theme'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 */
		public function form( $instance ) {
			$title      = esc_attr($instance['title']);
			$ytchag_user = strip_tags($instance['ytchag_user']);
			$ytchag_link = esc_attr($instance['ytchag_link']);
			$ytchag_maxitems = strip_tags($instance['ytchag_maxitems']);
			$ytchag_video_width = strip_tags($instance['ytchag_video_width']);
			$ytchag_thumb_width = strip_tags($instance['ytchag_thumb_width']);
			$ytchag_thumb_columns = strip_tags($instance['ytchag_thumb_columns']);
			$ytchag_theme = strip_tags($instance['ytchag_theme']);
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
					<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_link'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_link' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_link' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'ytchag_link' ); ?>"><?php _e('Show link to channel:', 'youtube-channel-gallery'); ?></label><br />
				</p>    
			
				<p>
					<label for="ytchag_maxitems"><?php _e( 'Number of videos to show:', 'youtube-channel-gallery' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_maxitems' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_maxitems' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_maxitems ); ?>" />
				</p>    
			
				<p>
					<label for="ytchag_video_width"><?php _e( 'Video width:', 'youtube-channel-gallery' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_video_width' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_video_width' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_video_width ); ?>" />
				</p>
			
				<p>
					<label for="ytchag_thumb_width"><?php _e( 'Thumbnail width:', 'youtube-channel-gallery' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_thumb_width ); ?>" />
				</p>
			
				<p>
					<label for="ytchag_thumb_columns"><?php _e( 'Thumbnail columns:', 'youtube-channel-gallery' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumb_columns' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumb_columns' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_thumb_columns ); ?>" />
				</p>

				<p>
					<label for="ytchag_theme"><?php _e( 'Theme:', 'youtube-channel-gallery' ); ?></label>
					<select class="widefat" id="<?php echo $this->get_field_id( 'ytchag_theme' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_theme' ); ?>">
						<option value="dark"<?php selected( $instance['ytchag_theme'], 'dark' ); ?>><?php _e( 'Dark', 'youtube-channel-gallery' ); ?></option>
						<option value="light"<?php selected( $instance['ytchag_theme'], 'light' ); ?>><?php _e( 'Light', 'youtube-channel-gallery' ); ?></option>
					</select>
				</p>

			<?php 
		}


		/*--------------------------------------------------*/ 
		/* Private Functions 
		/*--------------------------------------------------*/
		private function ytchag_rss_markup($instance){

			//$instance variables
			$ytchag_user = apply_filters('ytchag_user', $instance['ytchag_user']);
			$ytchag_link = apply_filters('ytchag_link', $instance['ytchag_link']);
			$ytchag_maxitems = apply_filters('ytchag_maxitems', $instance['ytchag_maxitems']);
			$ytchag_video_width = apply_filters('ytchag_video_width', $instance['ytchag_video_width']);
			$ytchag_thumb_width = apply_filters('ytchag_thumb_width', $instance['ytchag_thumb_width']);
			$ytchag_thumb_columns = apply_filters('ytchag_thumb_columns', $instance['ytchag_thumb_columns']);
			$ytchag_theme = apply_filters('ytchag_theme', $instance['ytchag_theme']);

			//defaults
			$ytchag_video_width = ( $ytchag_video_width ) ? $ytchag_video_width : 250;
			$ytchag_thumb_width = ( $ytchag_thumb_width ) ? $ytchag_thumb_width : 85;
			$ytchag_thumb_columns = ( $ytchag_thumb_columns ) ? $ytchag_thumb_columns : 0;
			$ytchag_theme = ( $ytchag_theme ) ? $ytchag_theme : 'dark';

			//heights of video and thumbnail
			$ytchag_video_heigh = round($ytchag_video_width/(16/9) + 32);
			$ytchag_thumb_height = round($ytchag_thumb_width*75/100); // 75% 'cos sizes of thumbnail in xml file are 480x360 and 120x90

			if( $ytchag_user ) { // only if user name inserted 
				
				// links
				$ytchag_rss_url 	= "http://gdata.youtube.com/feeds/api/users/" . $ytchag_user . "/uploads";
				$ytchag_link_url 	= "http://www.youtube.com/user/" . $ytchag_user;
				

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

						//Show me the player: iframe player
						if($i == 0) {
							//count the plugin occurrences on page
							STATIC $plugincount = 0;
							$plugincount++;

							$content = '<iframe id="ytcplayer' . $plugincount . '" class="ytcplayer" width="' . $ytchag_video_width . '" height="' . $ytchag_video_heigh . '" src="http://www.youtube.com/embed/' . $youtubeid . '?&autoplay=0&theme=' . $ytchag_theme . '&enablejsapi=1" frameborder="0"></iframe>';

							$content.= '<ul class="ytchagallery">';

						} // if player end
						$i++;

						$column++;
						// list of thumbnail videos						
						
						$content.= '<li class="ytccell-' . $column . '">';
						$content.= '<a class="ytcthumb" href="javascript: ytcplayVideo(\'ytcplayer' . $plugincount . '\', \'' . $youtubeid . '\');" alt="' . $title . '" title="' . $title . '" style="background-image: url(' . $thumb . ');">';
						$content.= '<div class="ytcplay" style="width: ' . $ytchag_thumb_width . 'px; height: ' . $ytchag_thumb_height . 'px"></div>';
						$content.= '</a></li>';
					
						if($ytchag_thumb_columns !=0 && $column%$ytchag_thumb_columns === 0){
							$column = 0;
						}
					} //foreach end

					$content.= '</ul>';

						//link to youtube.com gallery
					if( $ytchag_link) {
						$content.= '<a href="' . $ytchag_link_url . '" class="more">' . __('Show more videos»', 'youtube-channel-gallery') . '</a>';
					
						}
				}
			} else {
					$content.= '<p class="empty">' . __('There is no video to show.', 'youtube-channel-gallery') . '</p>';
				
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

		/*--------------------------------------------------*/ 
		/* Shortcode 
		/*--------------------------------------------------*/

		public function YoutubeChannelGallery_Shortcode($atts) {

			// Load JavaScript and stylesheets  
			$this->register_scripts_and_styles();

			extract( shortcode_atts( array(
				'user' => '',
				'link' => '0',
				'maxitems' => '9',
				'videowidth' => '280',
				'thumbwidth' => '85',
				'thumbcolumns' => '0',
				'theme' => 'dark',
			), $atts ) );

			$instance['ytchag_user'] = $user;
			
			$instance['ytchag_link'] = $link;
			$instance['ytchag_maxitems'] = $maxitems;
			$instance['ytchag_video_width'] = $videowidth;
			$instance['ytchag_thumb_width'] = $thumbwidth;
			$instance['ytchag_thumb_columns'] = $thumbcolumns;
			$instance['ytchag_theme'] = $theme;


			return $this->ytchag_rss_markup($instance);

		} // YoutubeChannelGallery_Shortcode


	} // class YoutubeChannelGallery_Widget

	// register YoutubeChannelGallery_Widget widget
	add_action( 'widgets_init', create_function( '', 'register_widget( "YoutubeChannelGallery_Widget" );' ) );


	?>
