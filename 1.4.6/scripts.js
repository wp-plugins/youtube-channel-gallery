var ytcplayer = {};

function ytcplayVideo (iframeid, youtubeid) {

	if(iframeid in ytcplayer) { 
		ytcplayer[iframeid].loadVideoById(youtubeid); 
	}else{
		ytcplayer[iframeid] = new YT.Player(iframeid, {
			events: { 
				'onReady': function(){
					ytcplayer[iframeid].loadVideoById(youtubeid);
				}
			}
		});
	}

}