function ytcplayVideo (iframeid, youtubeid) {
	if(window[iframeid].loadVideoById) { 
		window[iframeid].loadVideoById(youtubeid); 
	}else{
		window[iframeid] = new YT.Player(iframeid, {
			events: { 
				'onReady': function(){
					window[iframeid].loadVideoById(youtubeid);
				}
			}
		});
	}


}