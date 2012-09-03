jQuery(document).ready(function($) {
	$('.edit-player-options').live('click',function(){ 
		$(".player-options-select").slideToggle("fast");
	});

	
	$('.edit-thumbnails-options').live('click',function(){ 
		$(".thumbnails-options-select").slideToggle("fast");
	});

	
	$('.edit-link-options').live('click',function(){ 
		$(".link-options-select").slideToggle("fast");
	});
});