/*------------------------------------------------------------
Plugin Name: Youtube Channel Gallery
Plugin URI: http://www.poselab.com/
Version: 2.2.2
Description: Show a youtube video and a gallery of thumbnails for a youtube channel.
------------------------------------------------------------*/	
jQuery(document).ready(function($) {
	
    var PAGE = {};
	//thumbnails
	var ytcplayer = {};
    $('.youtubechannelgallery').on('click', '.ytclink', function(e) {
		var iframeid = $(this).attr('data-playerid');
		var youtubeid = $(this).attr('href').split("watch?v=")[1];
		var quality = $(this).attr('data-quality');
		checkIfInView($('#' + iframeid));
		ytcplayVideo (iframeid, youtubeid, quality);

		return false;
	});

	$('.popup-youtube').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false,
        gallery: {
          enabled:true
        }
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


	//Scroll to element only if not in view - jQuery
	//http://stackoverflow.com/a/10130707/1504078
	function checkIfInView(element){
		if($(element).offset()){
			if($(element).offset().top < $(window).scrollTop()){
			//scroll up
			$('html,body').animate({scrollTop:$(element).offset().top - 10}, 500);
		}
		else if($(element).offset().top + $(element).height() > $(window).scrollTop() + (window.innerHeight || document.documentElement.clientHeight)){
			//scroll down
			$('html,body').animate({scrollTop:$(element).offset().top - (window.innerHeight || document.documentElement.clientHeight) + $(element).height() + 10}, 500);}
		}
	}

    $('.youtubechannelgallery').on('click', '.ytc-paginationlink', function(e) {

      var $this = $(this),
          $widget = $this.parents('.youtubechannelgallery'),
          token = $this.data('token'),
          playlist = $this.data('playlist'),
          cid = $this.data('cid'),
          wid = $widget.find('[id^=ytc-]').attr('id'),
          $search = $widget.find('.search'),
          $searchSelect = $widget.find('.search-select');

      if (PAGE[wid]) {
        if ($this.hasClass('ytc-next')) {
          PAGE[wid] += 1;
        }
        else {
          PAGE[wid] -= 1;
        }
      }
      else {
        PAGE[wid] = 2;
      }

	  $.ajax({
	    url: ytcAjax.ajaxurl,
	    type: 'POST',
	    data: {
	      action: 'ytc_next',
	      wid: wid,
	      token: token,
	      cid: cid,
	      playlist: playlist,
	      search: $search.length ? $search.val() : '',
	      tag: $searchSelect.length ? $searchSelect.val() : ''
        },
        success: function(data) {

          $widget.find('.ytc-thumbnails').html(data);

          $widget.find('.ytc-currentpage').html(PAGE[wid]);

          $('.popup-youtube').magnificPopup({
              disableOn: 700,
              type: 'iframe',
              mainClass: 'mfp-fade',
              removalDelay: 160,
              preloader: false,

              fixedContentPos: false,
				gallery: {
				  enabled:true
				}
          });
        }
      });

	  return true;
    });

    $('.youtubechannelgallery').on('keyup blur', '.search', function(e) {

      var $this = $(this);

      if (e.type === 'blur' || e.type === 'focusout' || (e.type === 'keyup' && e.which === 13)) {

        var $widget = $this.parents('.youtubechannelgallery'),
            wid = $widget.find('[id^=ytc-]').attr('id'),
            cid = $this.data('cid'),
            tag = $widget.find('.search-select').val();

        $.ajax({
          url: ytcAjax.ajaxurl,
          type: 'POST',
          data: {
            action: 'ytc_search',
            wid: wid,
	        cid: cid,
            q: this.value,
            tag: tag
          },
          success: function(data) {

            if (data === '') {
              $this.val('');
              return;
            }

            $widget.find('.ytc-thumbnails').html(data);

            $('.popup-youtube').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,

                fixedContentPos: false,
		        gallery: {
		          enabled:true
		        }
            });
          }
        });

      }
      return true;
    });

    $('.youtubechannelgallery').on('change', '.search-select', function(e) {

      var $this = $(this);

      var $widget = $this.parents('.youtubechannelgallery'),
          wid = $widget.find('[id^=ytc-]').attr('id'),
          cid = $this.data('cid'),
          $search = $widget.find('.search');

      $.ajax({
        url: ytcAjax.ajaxurl,
        type: 'POST',
        data: {
          action: 'ytc_search',
          wid: wid,
          cid: cid,
          q: $search.length ? $search.val() : '',
          tag: $this.val()
        },
        success: function(data) {

          if (data === '') {
            $this.val('');
            return;
          }

          $widget.find('.ytc-thumbnails').html(data);

          $('.popup-youtube').magnificPopup({
              disableOn: 700,
              type: 'iframe',
              mainClass: 'mfp-fade',
              removalDelay: 160,
              preloader: false,

              fixedContentPos: false,
              gallery: {
                enabled:true
              }
          });
        }
      });

      return true;
    });
});
