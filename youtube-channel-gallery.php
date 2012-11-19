<?php
	/*
	Plugin Name: Youtube Channel Gallery
	Plugin URI: http://www.poselab.com/
	Description: Show a youtube video and a gallery of thumbnails for a youtube channel.
	Author: Javier Gómez Pose
	Author URI: http://www.poselab.com/
	Version: 1.6.2
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

			// Player options
			$instance['ytchag_video_width'] = strip_tags( $new_instance['ytchag_video_width'] );
			$instance['ytchag_ratio'] = strip_tags( $new_instance['ytchag_ratio'] );
			$instance['ytchag_theme'] = strip_tags( $new_instance['ytchag_theme'] );
			$instance['ytchag_color'] = strip_tags( $new_instance['ytchag_color'] );
			$instance['ytchag_autoplay'] = strip_tags( $new_instance['ytchag_autoplay'] );
			$instance['ytchag_rel'] = strip_tags( $new_instance['ytchag_rel'] );
			$instance['ytchag_showinfo'] = strip_tags( $new_instance['ytchag_showinfo'] );	
			
			// Thumbnail options
			$instance['ytchag_maxitems'] = strip_tags( $new_instance['ytchag_maxitems'] );
			$instance['ytchag_thumb_width'] = strip_tags( $new_instance['ytchag_thumb_width'] );
			$instance['ytchag_thumb_ratio'] = strip_tags( $new_instance['ytchag_thumb_ratio'] );
			$instance['ytchag_thumb_columns'] = strip_tags( $new_instance['ytchag_thumb_columns'] );
			$instance['ytchag_title'] = strip_tags( $new_instance['ytchag_title'] );
			$instance['ytchag_description'] = strip_tags( $new_instance['ytchag_description'] );
			$instance['ytchag_thumbnail_alignment'] = strip_tags( $new_instance['ytchag_thumbnail_alignment'] );
			$instance['ytchag_description_words_number'] = strip_tags( $new_instance['ytchag_description_words_number'] );

			// Link options
			$instance['ytchag_link'] = $new_instance['ytchag_link'];
			$instance['ytchag_link_tx'] = strip_tags( $new_instance['ytchag_link_tx'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 */
		public function form( $instance ) {
			$title = esc_attr($instance['title']);
			$ytchag_user = strip_tags($instance['ytchag_user']);

			// Player options
			$ytchag_video_width = strip_tags($instance['ytchag_video_width']);
			$ytchag_ratio = strip_tags($instance['ytchag_ratio']);
			$ytchag_theme = strip_tags($instance['ytchag_theme']);
			$ytchag_color = strip_tags($instance['ytchag_color']);
			$ytchag_autoplay = strip_tags($instance['ytchag_autoplay']);
			$ytchag_rel = strip_tags($instance['ytchag_rel']);
			$ytchag_showinfo = strip_tags($instance['ytchag_showinfo']);

			// Thumbnail options
			$ytchag_maxitems = strip_tags($instance['ytchag_maxitems']);
			$ytchag_thumb_width = strip_tags($instance['ytchag_thumb_width']);
			$ytchag_thumb_ratio = strip_tags($instance['ytchag_thumb_ratio']);
			$ytchag_thumb_columns = strip_tags($instance['ytchag_thumb_columns']);
			$ytchag_title = strip_tags($instance['ytchag_title']);
			$ytchag_description = strip_tags($instance['ytchag_description']);
			$ytchag_thumbnail_alignment = strip_tags($instance['ytchag_thumbnail_alignment']);
			$ytchag_description_words_number = strip_tags($instance['ytchag_description_words_number']);

			// Link options
			$ytchag_link = esc_attr($instance['ytchag_link']);
			$ytchag_link_tx = strip_tags($instance['ytchag_link_tx']);

			?>

			<div class="ytchg">
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'youtube-channel-gallery' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'ytchag_user' ); ?>"><?php _e( 'YouTube user name:', 'youtube-channel-gallery' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_user' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_user' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_user ); ?>" />
				</p>


				<script type="text/javascript">
					jQuery(document).ready(function($) {

						//tabs
						//---------------
						$('#tabs-<?php echo $this->id; ?> > div').hide();
						$('#tabs-<?php echo $this->id; ?>-1').show();
						$('#tabs-<?php echo $this->id; ?> ul li:first').addClass('active');

						$('#tabs-<?php echo $this->id; ?> ul li a').click(function(){
							//not work on the current tab
							if(!$(this).parent().hasClass('active')){
								$('#tabs-<?php echo $this->id; ?> ul li').removeClass('active');
								$(this).parent().addClass('active');
								var currentTab = $(this).attr('href');
								//slideUp and slideDown to give it animation
								$('#tabs-<?php echo $this->id; ?> > div').slideUp('fast');
								$(currentTab).slideDown('fast');
							}
							return false;							
						});


						//checkboxes with associated content
						//---------------
						show_title_description ();


						$('#tabs-<?php echo $this->id; ?>-2 .ytchg-tit-desc a').click(function(){
							if(!$(this).parent().parent().hasClass('active')){
								slide_title_description ( 'slideDown' );								
							} else{
								slide_title_description ( 'slideUp' );	
							}
							return false;
						});


						function slide_title_description ( action ){
							if(action == 'slideDown'){								
								$('#tabs-<?php echo $this->id; ?>-2 .ytchg-title-and-description').slideDown('fast');
								$('#tabs-<?php echo $this->id; ?>-2 fieldset.ytchg-field-tit-desc').addClass('ytchg-fieldborder active');
							} else if(action == 'slideUp'){
								$('#tabs-<?php echo $this->id; ?>-2 .ytchg-title-and-description').slideUp('fast');
								$('#tabs-<?php echo $this->id; ?>-2 fieldset.ytchg-field-tit-desc').removeClass('ytchg-fieldborder active');
							}
						}

						function show_title_description (){
							if( $('#tabs-<?php echo $this->id; ?>-2 .ytchg-tit').is(':checked') || $('#tabs-<?php echo $this->id; ?>-2 .ytchg-desc').is(':checked')){
								$('#tabs-<?php echo $this->id; ?>-2 .ytchg-title-and-description').show();
								$('#tabs-<?php echo $this->id; ?>-2 fieldset.ytchg-field-tit-desc').addClass('ytchg-fieldborder active');
							} else{
								$('#tabs-<?php echo $this->id; ?>-2 .ytchg-title-and-description').hide();

							}
						}

						/*
						*/

					});
				</script>


				<?php //http://wordpress.stackexchange.com/questions/5515/update-widget-form-after-drag-and-drop-wp-save-bug?>

				<div id="tabs-<?php echo $this->id; ?>" class="ytchgtabs">


					<ul class="ytchgtabs-tabs">
						<li><a href="#tabs-<?php echo $this->id; ?>-1"><?php _e( 'Player', 'youtube-channel-gallery' ); ?></a></li>
						<li><a href="#tabs-<?php echo $this->id; ?>-2"><?php _e( 'Thumbnails', 'youtube-channel-gallery' ); ?></a></li>
						<li><a href="#tabs-<?php echo $this->id; ?>-3"><?php _e( 'Link', 'youtube-channel-gallery' ); ?></a></li>
					</ul>


					<?php 
					/*
					Player Tab
					--------------------
					*/
					?>
					<div id="tabs-<?php echo $this->id; ?>-1" class="ytchgtabs-content">

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

							<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_showinfo'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_showinfo' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_showinfo' ); ?>" />
							<label for="<?php echo $this->get_field_id( 'ytchag_showinfo' ); ?>"><?php _e('Show info (title, uploader)', 'youtube-channel-gallery'); ?></label> 

					</div>


					<?php 
					/*
					Thumbnails Tab
					--------------------
					*/
					?>
					<div id="tabs-<?php echo $this->id; ?>-2">
						<p>
							<label for="ytchag_maxitems"><?php _e( 'Number of videos to show:', 'youtube-channel-gallery' ); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_maxitems' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_maxitems' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_maxitems ); ?>" />
						</p>    
					
						<p>
							<label for="ytchag_thumb_width"><?php _e( 'Thumbnail width:', 'youtube-channel-gallery' ); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_thumb_width ); ?>" />
						</p>

						<p>

							<label for="ytchag_thumb_ratio"><?php _e( 'Aspect ratio:', 'youtube-channel-gallery' ); ?></label>
							<select class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumb_ratio' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumb_ratio' ); ?>">
								<option value="4x3"<?php selected( $instance['ytchag_thumb_ratio'], '4x3' ); ?>><?php _e( 'Standard (4x3)', 'youtube-channel-gallery' ); ?></option>
								<option value="16x9"<?php selected( $instance['ytchag_thumb_ratio'], '16x9' ); ?>><?php _e( 'Widescreen (16x9)', 'youtube-channel-gallery' ); ?></option>
							</select>
						</p>
					
						<p>
							<label for="ytchag_thumb_columns"><?php _e( 'Thumbnail columns:', 'youtube-channel-gallery' ); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumb_columns' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumb_columns' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_thumb_columns ); ?>" />
						</p>

						<p>
							<fieldset class="ytchg-field-tit-desc">
								<legend class="ytchg-tit-desc">
									<a href="#"><?php _e('Show title or description', 'youtube-channel-gallery'); ?></a>
								</legend>

								<div class="ytchg-title-and-description">

									<p>
										<input class="checkbox ytchg-tit" type="checkbox" <?php checked( (bool) $instance['ytchag_title'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_title' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_title' ); ?>" />
										<label for="<?php echo $this->get_field_id( 'ytchag_title' ); ?>"><?php _e('Show title', 'youtube-channel-gallery'); ?></label>
									</p>
									
									<p>
										<input class="checkbox ytchg-desc" type="checkbox" <?php checked( (bool) $instance['ytchag_description'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_description' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_description' ); ?>" />
										<label for="<?php echo $this->get_field_id( 'ytchag_description' ); ?>"><?php _e('Show description', 'youtube-channel-gallery'); ?></label>
									</p>

									<p>
										<label for="ytchag_thumbnail_alignment"><?php _e( 'Thumbnail alignment:', 'youtube-channel-gallery' ); ?></label>
										<select class="widefat" id="<?php echo $this->get_field_id( 'ytchag_thumbnail_alignment' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_thumbnail_alignment' ); ?>">
											<option value="left"<?php selected( $instance['ytchag_thumbnail_alignment'], 'left' ); ?>><?php _e( 'Left', 'youtube-channel-gallery' ); ?></option>
											<option value="right"<?php selected( $instance['ytchag_thumbnail_alignment'], 'right' ); ?>><?php _e( 'Right', 'youtube-channel-gallery' ); ?></option>
											<option value="top"<?php selected( $instance['ytchag_thumbnail_alignment'], 'top' ); ?>><?php _e( 'Top', 'youtube-channel-gallery' ); ?></option>
											<option value="bottom"<?php selected( $instance['ytchag_thumbnail_alignment'], 'bottom' ); ?>><?php _e( 'Bottom', 'youtube-channel-gallery' ); ?></option>
										</select>
									</p>

									<p>
										<label for="ytchag_description_words_number"><?php _e( 'Description words number:', 'youtube-channel-gallery' ); ?></label>
										<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_description_words_number' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_description_words_number' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_description_words_number ); ?>" />
									</p> 
								</div>
							</fieldset> 
						</p>



					</div>


					<?php 
					/*
					Link Tab
					--------------------
					*/
					?>
					<div id="tabs-<?php echo $this->id; ?>-3">

						<p>
							<label for="ytchag_link_tx"><?php _e( 'Link text:', 'youtube-channel-gallery' ); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id( 'ytchag_link_tx' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_link_tx' ); ?>" type="text" value="<?php echo esc_attr( $ytchag_link_tx ); ?>" />
						</p>

						<p>
							<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['ytchag_link'], true ); ?> id="<?php echo $this->get_field_id( 'ytchag_link' ); ?>" name="<?php echo $this->get_field_name( 'ytchag_link' ); ?>" />
							<label for="<?php echo $this->get_field_id( 'ytchag_link' ); ?>"><?php _e('Show link to channel:', 'youtube-channel-gallery'); ?></label>
						</p>

					</div>
				</div>



			</div>

			<?php 
		}


		/*--------------------------------------------------*/ 
		/* Private Functions 
		/*--------------------------------------------------*/
		private function ytchag_rss_markup($instance){

			//$instance variables
			//--------------------------------
			$ytchag_user = apply_filters('ytchag_user', $instance['ytchag_user']);

			// Player options
			$ytchag_video_width = apply_filters('ytchag_video_width', $instance['ytchag_video_width']);
			$ytchag_ratio = apply_filters('ytchag_ratio', $instance['ytchag_ratio']);
			$ytchag_theme = apply_filters('ytchag_theme', $instance['ytchag_theme']);
			$ytchag_color = apply_filters('ytchag_color', $instance['ytchag_color']);
			$ytchag_autoplay = apply_filters('ytchag_autoplay', $instance['ytchag_autoplay']);
			$ytchag_rel = apply_filters('ytchag_rel', $instance['ytchag_rel']);
			$ytchag_showinfo = apply_filters('ytchag_showinfo', $instance['ytchag_showinfo']);

			// Thumbnail options
			$ytchag_maxitems = apply_filters('ytchag_maxitems', $instance['ytchag_maxitems']);
			$ytchag_thumb_width = apply_filters('ytchag_thumb_width', $instance['ytchag_thumb_width']);
			$ytchag_thumb_ratio = apply_filters('ytchag_thumb_ratio', $instance['ytchag_thumb_ratio']);
			$ytchag_thumb_columns = apply_filters('ytchag_thumb_columns', $instance['ytchag_thumb_columns']);
			$ytchag_title = apply_filters('ytchag_title', $instance['ytchag_title']);
			$ytchag_description = apply_filters('ytchag_description', $instance['ytchag_description']);
			$ytchag_thumbnail_alignment = apply_filters('ytchag_thumbnail_alignment', $instance['ytchag_thumbnail_alignment']);
			$ytchag_description_words_number = apply_filters('ytchag_description_words_number', $instance['ytchag_description_words_number']);

			// Link options
			$ytchag_link = apply_filters('ytchag_link', $instance['ytchag_link']);
			$ytchag_link_tx = apply_filters('ytchag_link_tx', $instance['ytchag_link_tx']);
			//--------------------------------
			//end $instance variables


			//defaults
			//--------------------------------
			// Player options
			$ytchag_video_width = ( $ytchag_video_width ) ? $ytchag_video_width : 250;
			$ytchag_theme = ( $ytchag_theme ) ? '&theme='. $ytchag_theme : ''; //defaul dark
			$ytchag_color = ( $ytchag_color ) ? '&color='. $ytchag_color : ''; //defaul red
			$ytchag_autoplay = ( $ytchag_autoplay ) ? '&autoplay='. $ytchag_autoplay : ''; //defaul 0
			$ytchag_rel = ( $ytchag_rel ) ? '&rel='. $ytchag_rel : '&rel=0'; //defaul 1
			$ytchag_showinfo = ( $ytchag_showinfo ) ? '&showinfo='. $ytchag_showinfo : '&showinfo=0'; //defaul 1

			// Thumbnail options
			$ytchag_thumb_width = ( $ytchag_thumb_width ) ? $ytchag_thumb_width : 85;
			$ytchag_thumb_columns = ( $ytchag_thumb_columns ) ? $ytchag_thumb_columns : 0;

				//title and desc
				$ytchag_title = ( $ytchag_title ) ? $ytchag_title : 0;
				$ytchag_description = ( $ytchag_description ) ? $ytchag_description : 0;
				$ytchag_thumbnail_alignment = ( $ytchag_thumbnail_alignment ) ? $ytchag_thumbnail_alignment : 'left';
				$ytchag_description_words_number = ( $ytchag_description_words_number ) ? $ytchag_description_words_number : 10;

			// Link options
			$ytchag_link = ( $ytchag_link ) ? $ytchag_link : 0;
			$ytchag_link_tx = ( $ytchag_link_tx ) ? $ytchag_link_tx : __('Show more videos»', 'youtube-channel-gallery');
			//--------------------------------
			//end defaults


			//heights of video and thumbnail
			//--------------------------------
			//video height
			if ($ytchag_ratio == '16x9') {
				$ytchag_video_heigh = round( ($ytchag_video_width * 9) / 16);
			} else {
				$ytchag_video_heigh = round( ($ytchag_video_width * 3) / 4);
			}

			//thumbnail height
			if ($ytchag_thumb_ratio == '16x9') {
				$ytchag_thumb_height = round( ($ytchag_thumb_width * 9) / 16);
			} else {
				$ytchag_thumb_height = round( ($ytchag_thumb_width * 3) / 4);
			}
			

			// only if user name inserted 
			if( $ytchag_user ) { 
				
				// links
				$ytchag_rss_url 	= "http://gdata.youtube.com/feeds/api/users/" . $ytchag_user . "/uploads";
				$ytchag_link_url 	= "http://www.youtube.com/user/" . $ytchag_user;
				
				//RSS Feed				
				include_once(ABSPATH . WPINC . '/feed.php');
				
				$rss = fetch_feed($ytchag_rss_url);


				// check if no correct user name
				if (!is_wp_error( $rss ) ) {

					$maxitems = ( $ytchag_maxitems ) ? $ytchag_maxitems : 9;
					$items = $rss->get_items(0, $maxitems);
					

					if (!empty($items)) {
						$i = 0;
						$column = 0;
						foreach ( $items as $item ) {
							$url = $item->get_permalink();
							$youtubeid = $this->youtubeid($url);
							$title = $item->get_title();
							$description = $item->get_description();

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


							//title and description content
							if($ytchag_title || $ytchag_description){
								$title_and_description_alignment_class = ' ytc-td-' . $ytchag_thumbnail_alignment;
								$title_and_description_content= '<div class="ytctitledesc-cont">';

								if($ytchag_title){
									$title_and_description_content.= '<h5 class="ytctitle"><a href="javascript: ytcplayVideo(\'ytcplayer' . $plugincount . '\', \'' . $youtubeid . '\');">' . $title . '</a></h5>';
								}

								if($ytchag_description){
									$description = wp_trim_words( $description, $num_words = $ytchag_description_words_number, $more = '&hellip;' );
									$title_and_description_content.= '<div class="ytctdescription">' . $description . '</div>';
								}

								$title_and_description_content.= '</div>';
							}//end title and description content


							//rows and columns control

							$column++;
							$columnlastfirst = '';
							if($ytchag_thumb_columns !=0 && $column%$ytchag_thumb_columns === 0){
								$columnlastfirst = ' ytccell-last';
							}
							if($ytchag_thumb_columns !=0 && $column === 1){
								$columnlastfirst = ' ytccell-first';
								STATIC $rowcount = 0;
								$rowcount++;					
								$row_oddeven = ($rowcount%2==1)?' ytc-r-odd':' ytc-r-even';
								$tableclass = ' ytc-table';			
								$columnnumber = ' ytc-columns'. $ytchag_thumb_columns;

							}// end columns control


							//The content
							//--------------------------------

							//Show me the player: iframe player
							if($i == 0) {
								//count the plugin occurrences on page
								STATIC $plugincount = 0;
								$plugincount++;

								$content = '<iframe id="ytcplayer' . $plugincount . '" class="ytcplayer" allowfullscreen width="' . $ytchag_video_width . '" height="' . $ytchag_video_heigh . '" src="http://www.youtube.com/embed/' . $youtubeid . '?version=3' . $ytchag_theme . $ytchag_color .  $ytchag_autoplay . $ytchag_rel . $ytchag_showinfo .'&enablejsapi=1" frameborder="0"></iframe>';

								$content.= '<ul class="ytchagallery ytccf' . $tableclass . $title_and_description_alignment_class . $columnnumber . '">';

							} // if player end
							$i++;



							if($columnlastfirst == ' ytccell-first'){
								$content.=  "\n\n" .'<div class="ytccf ytc-row ytc-r-' . $rowcount . $row_oddeven . ' ">' . "\n\n";
							}

							//$content.= '$column: ' + $column;
							$content.=  "\n\n" . '	<li class="ytccell-' . $column . $columnlastfirst . '">';

								if($ytchag_thumb_columns !=0 && $column%$ytchag_thumb_columns === 0 ){
									$column = 0;								
								}

								$content.= '<div class="ytcliinner">';

									if($ytchag_thumbnail_alignment == 'bottom'){
										$content.= $title_and_description_content;

									}

									$content.= '<div class="ytcthumb-cont">';
									$content.= '<a class="ytcthumb" href="javascript: ytcplayVideo(\'ytcplayer' . $plugincount . '\', \'' . $youtubeid . '\');" alt="' . $title . '" title="' . $title . '" style="background-image: url(' . $thumb . ');">';
									$content.= '<div class="ytcplay" style="width: ' . $ytchag_thumb_width . 'px; height: ' . $ytchag_thumb_height . 'px"></div>';
									$content.= '</a>';
									$content.= '</div>';

									if($ytchag_thumbnail_alignment != 'bottom'){
										$content.= $title_and_description_content;
									}

								$content.= '</div>';

							$content.= '</li>' . "\n\n";

							if($columnlastfirst == ' ytccell-last'){
								$content.= '</div>' . "\n\n\n";
							}
							
						} //foreach end

						//if last row 
						if($ytchag_thumb_columns !=0 && $columnlastfirst != ' ytccell-last'){
								$content.= '</div>' . "\n\n\n";
						}

						$content.= '</ul>';

							//link to youtube.com gallery
						if( $ytchag_link) {
							$content.= '<a href="' . $ytchag_link_url . '" class="ytcmore">' . $ytchag_link_tx . '</a>';						
						}
					}
				} else {
					$content= '<p class="empty">' .  __('You must insert a valid YouTube user name.', 'youtube-channel-gallery') . '</p>';
				} // end check user name

			// user name not inserted 
			} else {
					$content= '<p class="empty">' . __('There is no video to show.', 'youtube-channel-gallery') . '</p>';				
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
				wp_enqueue_script('jquery');
				wp_enqueue_script('youtube_player_api', 'http://www.youtube.com/player_api', false, false, true);
				wp_enqueue_script('youtube-channel-gallery', plugins_url('/scripts.js', __FILE__), false, false, true);
				wp_enqueue_style('youtube-channel-gallery', plugins_url('/styles.css', __FILE__), false, false, 'all');
		}//register_scripts_and_styles


		public function register_admin_scripts_and_styles($hook) {
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
				'videowidth' => '',
				'ratio' => '',
				'theme' => '',
				'color' => '',
				'autoplay' => '',
				'rel' => '',
				'showinfo' => '',

				// Thumbnail options
				'maxitems' => '',
				'thumbwidth' => '',
				'thumbratio' => '',
				'thumbcolumns' => '',
				'title' => '',
				'description' => '',
				'thumbnail_alignment' => '',
				'descriptionwordsnumber' => '',

				// Link options
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
				
			// Thumbnail options
			$instance['ytchag_maxitems'] = $maxitems;
			$instance['ytchag_thumb_width'] = $thumbwidth;
			$instance['ytchag_thumb_ratio'] = $thumbratio;
			$instance['ytchag_thumb_columns'] = $thumbcolumns;
			$instance['ytchag_title'] = $title;
			$instance['ytchag_description'] = $description;
			$instance['ytchag_thumbnail_alignment'] = $thumbnail_alignment;
			$instance['ytchag_description_words_number'] = $descriptionwordsnumber;

			// Link options
			$instance['ytchag_link'] = $link;
			$instance['ytchag_link_tx'] = $link_tx;


			return '<div class="ytcshort">'. $this->ytchag_rss_markup($instance) . '</div>';

		} // YoutubeChannelGallery_Shortcode


	} // class YoutubeChannelGallery_Widget

	// register YoutubeChannelGallery_Widget widget
	add_action( 'widgets_init', create_function( '', 'register_widget( "YoutubeChannelGallery_Widget" );' ) );


?>