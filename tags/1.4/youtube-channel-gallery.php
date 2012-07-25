<?php
/*
Plugin Name: Youtube Channel Gallery
Plugin URI: http://www.poselab.com/
Description: Show a youtube video and a gallery of thumbnails for a youtube channel.
Author: Javier Gómez Pose
Author URI: http://www.poselab.com/
Version: 1.0
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



/*
class

*/
class YoutubeChannelGallery_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		//localization
		load_plugin_textdomain('youtube-channel-gallery', false, dirname(plugin_basename( __FILE__ ) ) . '/languages/' );  
		
		parent::__construct(
			'youtubechannelgallery_widget', // Base ID
			 __( 'Youtube Channel Gallery', 'youtube-channel-gallery' ), // Name
			array( 'description' => __( 'Show a youtube video and a gallery of thumbnails for a youtube channel', 'youtube-channel-gallery' ), ) // Args
		);

		// Load JavaScript and stylesheets  
		$this->register_scripts_and_styles();

	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			$this->ytchag_rss_markup($instance);

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

		//defaults
		$ytchag_video_width = ( $ytchag_video_width ) ? $ytchag_video_width : 250;
		$ytchag_thumb_width = ( $ytchag_thumb_width ) ? $ytchag_thumb_width : 85;
		$ytchag_thumb_columns = ( $ytchag_thumb_columns ) ? $ytchag_thumb_columns : 0;

		//heights of video and thumbnail
		$ytchag_video_heigh = round($ytchag_video_width/(16/9) + 32);
		$ytchag_thumb_height = $ytchag_thumb_width*75/100; // 75% 'cos sizes of thumbnail in xml file are 480x360 and 120x90

		if( $ytchag_user ) { // only if user name inserted 
			
			// links
			$ytchag_rss_url 		= "http://gdata.youtube.com/feeds/api/users/" . $ytchag_user . "/uploads";
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
						//option IFrame embeds using <iframe> tags
						//echo '<iframe id="ytcplayer" type="text/html" width="250" height="200" src="http://www.youtube.com/embed/'.$youtubeid.'?&autoplay=0&origin='.site_url().'" frameborder="0"></iframe>';

						//IFrame Player API
					?>
							<div id="ytcplayer" class="ytcplayer"></div>
								<script>
									var tag = document.createElement('script');
									tag.src = 'http://www.youtube.com/player_api';
									var firstScriptTag = document.getElementsByTagName('script')[0];
									firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
									var player;
									function onYouTubePlayerAPIReady() {
										
										player = new YT.Player('ytcplayer', {
											width: '<?php echo $ytchag_video_width; ?>',
											height: '<?php echo $ytchag_video_heigh; ?>',
											videoId: '<?php echo $youtubeid; ?>'
										});
									}

									function onYouTubePlayerAPIReady2(other) {
										player.stopVideo();
										player.loadVideoById(other);
									}
									</script>
						<ul class="ytcgallery">

					<?php	
					} // if player end
					$i++;

					$column++;
					// list of thumbnail videos						
					?>
					<li class="ytccell-<?php echo $column; ?>">
						<a class="db-yt-thumb" href="javascript: onYouTubePlayerAPIReady2('<?php echo $youtubeid; ?>');" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" style="background-image: url(<?php echo $thumb; ?>); width: <?php echo $ytchag_thumb_width; ?>px; height: <?php echo $ytchag_thumb_height; ?>px">
							<div class="db-yt-play"></div>
						</a>
					</li>

				<?php 
					if($ytchag_thumb_columns !=0 && $column%$ytchag_thumb_columns === 0){
						$column = 0;
					}
				} //foreach end
				?>
				</ul>
				<?php
					//link to youtube.com gallery
				if( $ytchag_link) {
				?>
					<a href="<?php echo $ytchag_link_url ?>" class="more"><?php _e('Show more videos»', 'youtube-channel-gallery') ?></a>
				<?php 
					}
			}
		} else {
			?>
				<p class="empty"><?php _e('There is no video to show.', 'youtube-channel-gallery') ?></p>
			<?php
		}
	}

	//parse youtube url to extract id
	private function youtubeid($url) {
		$url_string = parse_url($url, PHP_URL_QUERY);
		parse_str($url_string, $args);
		return isset($args['v']) ? $args['v'] : false;
	}

	// load css or js
	private function register_scripts_and_styles() {
			//$this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/js/admin.js', true); 
			$this->load_file('youtube-channel-gallery', '/' . 'youtube-channel-gallery' . '/youtube-channel-gallery.css'); 
	}

	//register css or js
	private function load_file($name, $file_path, $is_script = false) { 
		$url = WP_PLUGIN_URL . $file_path; 
		$file = WP_PLUGIN_DIR . $file_path; 
 
		if(file_exists($file)) { 
			if($is_script) { 
				wp_register_script($name, $url); 
				wp_enqueue_script($name); 
			} else { 
				wp_register_style($name, $url); 
				wp_enqueue_style($name); 
			}
		} 
	} // end load_file 




} // class YoutubeChannelGallery_Widget

// register YoutubeChannelGallery_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "YoutubeChannelGallery_Widget" );' ) );

?>
