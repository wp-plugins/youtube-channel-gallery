jQuery(document).ready(function($) {
	
	//thumbnails
	var ytcplayer = {};
	$('.ytclink').click(function(){
		var iframeid = $(this).attr('data-playerid');
		var youtubeid = $(this).attr('href').split("youtu.be/")[1];
		var quality = $(this).attr('data-quality');
		ytcplayVideo (iframeid, youtubeid, quality);

		return false;
	});

	function ytcplayVideo (iframeid, youtubeid, quality) {
		if(iframeid in ytcplayer) { 
			ytcplayer[iframeid].loadVideoById(youtubeid); 
		}else{
			ytcplayer[iframeid] = new YT.Player(iframeid, {
				events: { 
					'onReady': function(){
						ytcplayer[iframeid].loadVideoById(youtubeid);
						ytcplayer[iframeid].setPlaybackQuality(quality);
					}
				}
			});
		}

	}


	//Equal Height Blocks in Rows
	//http://css-tricks.com/equal-height-blocks-in-rows/
	var currentTallest = 0,
	currentRowStart = 0,
	rowDivs = new Array(),
	$el,
	topPosition = 0;

	jQuery('.ytc-td-bottom .ytc-row .ytctitledesc-cont').each(function() {

		$el = jQuery(this);
		topPostion = $el.position().top;
		
		if (currentRowStart != topPostion) {
			// we just came to a new row.  Set all the heights on the completed row
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
			// set the variables for the new row
			rowDivs.length = 0; // empty the array
			currentRowStart = topPostion;
			currentTallest = $el.height();
			rowDivs.push($el);
		} else {
			// another div on the current row.  Add it to the list and check if it's taller
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		}

		// do the last row
		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
 
	});

});
