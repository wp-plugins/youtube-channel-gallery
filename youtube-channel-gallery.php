<?php
/*
Plugin Name: Youtube Channel Gallery
Plugin URI: http://www.poselab.com/
Description: Show a youtube video and a gallery of thumbnails for a youtube channel.
Author: Javier Gómez Pose
Author URI: http://www.poselab.com/
Version: 1.4
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

			ytcg_rss_markup($instance);

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ytcg_user'] = strip_tags( $new_instance['ytcg_user'] );
		
		$instance['ytcg_link'] = $new_instance['ytcg_link'];
		$instance['ytcg_maxitems'] = strip_tags( $new_instance['ytcg_maxitems'] );
		$instance['ytcg_video_width'] = strip_tags( $new_instance['ytcg_video_width'] );
		$instance['ytcg_thumb_width'] = strip_tags( $new_instance['ytcg_thumb_width'] );
		$instance['ytcg_thumb_columns'] = strip_tags( $new_instance['ytcg_thumb_columns'] );
		$instance['ytcg_theme'] = strip_tags( $new_instance['ytcg_theme'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$title      = esc_attr($instance['title']);
		$ytcg_user = strip_tags($instance['ytcg_user']);
		$ytcg_link = esc_attr($instance['ytcg_link']);
		$ytcg_maxitems = strip_tags($instance['ytcg_maxitems']);
		$ytcg_video_width = strip_tags($instance['ytcg_video_width']);
		$ytcg_thumb_width = strip_tags($instance['ytcg_thumb_width']);
		$ytcg_thumb_columns = strip_tags($instance['ytcg_thumb_columns']);
		$ytcg_theme = strip_tags($instance['ytcg_theme']);
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'youtube-channel-gallery' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'ytcg_user' ); ?>"><?php _e( 'YouTube user name:', 'youtube-channel-gallery' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'ytcg_user' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_user' ); ?>" type="text" value="<?php echo esc_attr( $ytcg_user ); ?>" />
			</p>
		
			<p>
				<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytcg_link'], true ); ?> id="<?php echo $this->get_field_id( 'ytcg_link' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_link' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'ytcg_link' ); ?>"><?php _e('Show link to channel:', 'youtube-channel-gallery'); ?></label><br />
			</p>    
		
			<p>
				<label for="ytcg_maxitems"><?php _e( 'Number of videos to show:', 'youtube-channel-gallery' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'ytcg_maxitems' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_maxitems' ); ?>" type="text" value="<?php echo esc_attr( $ytcg_maxitems ); ?>" />
			</p>    
		
			<p>
				<label for="ytcg_video_width"><?php _e( 'Video width:', 'youtube-channel-gallery' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'ytcg_video_width' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_video_width' ); ?>" type="text" value="<?php echo esc_attr( $ytcg_video_width ); ?>" />
			</p>
		
			<p>
				<label for="ytcg_thumb_width"><?php _e( 'Thumbnail width:', 'youtube-channel-gallery' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'ytcg_thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $ytcg_thumb_width ); ?>" />
			</p>
		
			<p>
				<label for="ytcg_thumb_columns"><?php _e( 'Thumbnail columns:', 'youtube-channel-gallery' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'ytcg_thumb_columns' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_thumb_columns' ); ?>" type="text" value="<?php echo esc_attr( $ytcg_thumb_columns ); ?>" />
			</p>

			<p>
				<label for="ytcg_theme"><?php _e( 'Theme:', 'youtube-channel-gallery' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'ytcg_theme' ); ?>" name="<?php echo $this->get_field_name( 'ytcg_theme' ); ?>">
					<option value="dark"<?php selected( $instance['ytcg_theme'], 'dark' ); ?>><?php _e( 'Dark', 'youtube-channel-gallery' ); ?></option>
					<option value="light"<?php selected( $instance['ytcg_theme'], 'light' ); ?>><?php _e( 'Light', 'youtube-channel-gallery' ); ?></option>
				</select>
			</p>

		<?php 
	}


	/*--------------------------------------------------*/ 
	/* Private Functions 
	/*--------------------------------------------------*/


	// load css or js
	private function register_scripts_and_styles() {
			wp_enqueue_script('youtube_player_api', 'http://www.youtube.com/player_api', false, false, true);
			wp_enqueue_script('youtube-channel-gallery', plugins_url('/scripts.js', __FILE__), false, false, true);
			wp_enqueue_style('youtube-channel-gallery', plugins_url('/styles.css', __FILE__), false, false, 'all');
	}

} // class YoutubeChannelGallery_Widget

// register YoutubeChannelGallery_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "YoutubeChannelGallery_Widget" );' ) );



/*--------------------------------------------------*/ 
/* Functions 
/*--------------------------------------------------*/

function ytcg_rss_markup($instance){

	//$instance variables
	$ytcg_user = apply_filters('ytcg_user', $instance['ytcg_user']);
	$ytcg_link = apply_filters('ytcg_link', $instance['ytcg_link']);
	$ytcg_maxitems = apply_filters('ytcg_maxitems', $instance['ytcg_maxitems']);
	$ytcg_video_width = apply_filters('ytcg_video_width', $instance['ytcg_video_width']);
	$ytcg_thumb_width = apply_filters('ytcg_thumb_width', $instance['ytcg_thumb_width']);
	$ytcg_thumb_columns = apply_filters('ytcg_thumb_columns', $instance['ytcg_thumb_columns']);
	$ytcg_theme = apply_filters('ytcg_theme', $instance['ytcg_theme']);

	//defaults
	$ytcg_video_width = ( $ytcg_video_width ) ? $ytcg_video_width : 250;
	$ytcg_thumb_width = ( $ytcg_thumb_width ) ? $ytcg_thumb_width : 85;
	$ytcg_thumb_columns = ( $ytcg_thumb_columns ) ? $ytcg_thumb_columns : 0;
	$ytcg_theme = ( $ytcg_theme ) ? $ytcg_theme : 'dark';

	//heights of video and thumbnail
	$ytcg_video_heigh = round($ytcg_video_width/(16/9) + 32);
	$ytcg_thumb_height = $ytcg_thumb_width*75/100; // 75% 'cos sizes of thumbnail in xml file are 480x360 and 120x90

	if( $ytcg_user ) { // only if user name inserted 
		
		// links
		$ytcg_rss_url 		= "http://gdata.youtube.com/feeds/api/users/" . $ytcg_user . "/uploads";
		$ytcg_link_url 	= "http://www.youtube.com/user/" . $ytcg_user;
		

		//RSS Feed
		
		include_once(ABSPATH . WPINC . '/feed.php');
		
		$rss = fetch_feed($ytcg_rss_url);
		$maxitems = ( $ytcg_maxitems ) ? $ytcg_maxitems : 9;
		$items = $rss->get_items(0, $maxitems);
		

		if (!empty($items)) {
			$i = 0;
			$column = 0;
			foreach ( $items as $item ) {

				$url = $item->get_permalink();
				$youtubeid = youtubeid($url);
				$title = $item->get_title();

				if ($enclosure = $item->get_enclosure()){

					//extract thumbnail
					//-----------------

					//thumbnail index in xml
					$big = 0;
					$small = 1;
					$size = $small;
					if($ytcg_thumb_width > '120'){
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
				?>	
				<iframe id="ytcplayer<?php echo $plugincount; ?>" type="text/html" width="250" height="200" src="http://www.youtube.com/embed/<?php echo $youtubeid; ?>?&autoplay=0&theme=<?php echo $ytcg_theme; ?>&enablejsapi=1&origin=<?php echo site_url(); ?>" frameborder="0"></iframe>
					<ul class="ytcgallery">

				<?php	
				} // if player end
				$i++;

				$column++;
				// list of thumbnail videos						
				?>
				<li class="ytccell-<?php echo $column; ?>">
					<a class="ytcthumb" href="javascript: ytcplayVideo('ytcplayer<?php echo $plugincount; ?>', '<?php echo $youtubeid; ?>');" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" style="background-image: url(<?php echo $thumb; ?>);">
						<div class="ytcplay" style="width: <?php echo $ytcg_thumb_width; ?>px; height: <?php echo $ytcg_thumb_height; ?>px"></div>
					</a>
				</li>

			<?php 
				if($ytcg_thumb_columns !=0 && $column%$ytcg_thumb_columns === 0){
					$column = 0;
				}
			} //foreach end
			?>
			</ul>
			<?php
				//link to youtube.com gallery
			if( $ytcg_link) {
			?>
				<a href="<?php echo $ytcg_link_url ?>" class="more"><?php _e('Show more videos»', 'youtube-channel-gallery') ?></a>
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
 function youtubeid($url) {
	$url_string = parse_url($url, PHP_URL_QUERY);
	parse_str($url_string, $args);
	return isset($args['v']) ? $args['v'] : false;
}



/*--------------------------------------------------*/ 
/* Shortcode 
/*--------------------------------------------------*/

	function YoutubeChannelGallery_Shortcode($atts) {

		extract( shortcode_atts( array(
			'user' => '',
			'link' => '0',
			'maxitems' => '9',
			'videowidth' => '280',
			'thumbwidth' => '85',
			'thumbcolumns' => '0',
			'theme' => 'dark',
		), $atts ) );

		$instance['ytcg_user'] = $user;
		
		$instance['ytcg_link'] = $link;
		$instance['ytcg_maxitems'] = $maxitems;
		$instance['ytcg_video_width'] = $videowidth;
		$instance['ytcg_thumb_width'] = $thumbwidth;
		$instance['ytcg_thumb_columns'] = $thumbcolumns;
		$instance['ytcg_theme'] = $theme;


		ytcg_rss_markup($instance);
	}
	add_shortcode('Youtube_Channel_Gallery', 'YoutubeChannelGallery_Shortcode'); 
?>
