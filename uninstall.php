<?php 
	if( !defined( ‘WP_UNINSTALL_PLUGIN’ ) )
		exit ();

	// Delete options 
	delete_option( 'YoutubeChannelGallery_Widget' );
?>