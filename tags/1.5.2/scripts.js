var ytcplayer = {};

function ytcplayVideo (e, iframeid, youtubeid) {
	if (!e) var e = window.event;
	try {

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
		e.preventDefault();
	} 

	catch (err) {
		console.log('error with API. Try unchecking extra security option in the widget options')
	}

}