// Define our "rp3" object, if not already defined
if ( rp3 === undefined ) { var rp3 = {}; }

rp3.video = (function($) {
	'use strict';

	var

	$body = $('body'),

	// player, done = false,

	onYouTubeIframeAPIReady = function() {

		var $videoTrigger = $('.video__trigger.youtube');

		// Loop through each video on the page
		$videoTrigger.each( function() {

			// Get the hash which serves as the basis for all of our unique IDs
			var hash = $(this).data('id'),
				$modal = $('#' + hash + '__modal'), // the modal
				iframeId = hash + '__iframe',
				youtubePlayer = new YT.Player( iframeId );

			// Clicking on the trigger reveals the modal and plays the video
			$(this).on( 'click', function(e) {

				if ( ! Modernizr.touchevents ) {
					e.preventDefault();
					$modal.addClass('visible');
					youtubePlayer.playVideo();
				} else {
					location.href = $(this).attr( 'href' );
				}
			});
		});
	},


	/* ==========================================================================
	   Load videos on work item pages
	========================================================================== */

	vimeoToggle = function() {

		var $videoTrigger = $('.video__trigger.vimeo');

		// Loop through each video on the page
		$videoTrigger.each( function() {

			// Get the hash which serves as the basis for all of our unique IDs
			var hash = $(this).data('id'),
				$modal = $('#' + hash + '__modal'), // the modal
				$iframe = $('#' + hash + '__iframe'), // the vimeo iframe
				vimeoPlayer = $f( $iframe[0] ); // the vimeo player for the purposes of the API

			vimeoPlayer.addEvent( 'ready' ); // initialize the player API

			// Clicking on the trigger reveals the modal and plays the video
			$(this).on( 'click', function(e) {

				e.preventDefault();
				$modal.addClass('visible');
				vimeoPlayer.api( 'play' );
			});
		});
	},

	/* ==========================================================================
	   Front Page Video With Audio
	========================================================================== */

	frontPageVideoAudio = function() {

		var $frontPage__video = $('#front-page__video');

		if ( 0 < $frontPage__video.length ) {

			var $playAudioLink = $('#play-with-audio'),
				$iFrame = $frontPage__video[0],
				player = $f($iFrame);

			player.addEvent( 'ready', function() {

				player.api( 'setVolume', 0 );
			});

			$playAudioLink.on( 'click', function(e) {

				e.preventDefault();

				if ( undefined !== window.ga ) {
					ga( 'send', 'event', 'Interface Elements', 'Enable Audio' );
				}

				player.api( 'setVolume', 1 );
				player.api( 'seekTo', 0 );
				$(this).fadeOut( 100, function() {
					$(this).remove();
				});
			});
		}
	},

	/* ==========================================================================
	   Fix Blog Video Aspect Ratios
	========================================================================== */

	fixBlogVideoAspectRatios = function() {

		var $iframes = $('iframe[src*="vimeo"], iframe[src*="youtube"]'),
			$container,
			$iframeParent,
			videoContainer = 'video-container';

		$iframes.each( function() {

			$iframeParent = $(this).parent();

			if ( ! $iframeParent.hasClass( videoContainer ) ) {
				$container = $('<div>').addClass( videoContainer );
				$container.append( $(this) );
				$iframeParent.append( $container );
			}
		});
	},


	/* ==========================================================================
	   Init
	========================================================================== */

	init = function() {

		// Load YouTube iFrame API

		var tag = document.createElement('script');
		tag.src = 'https://www.youtube.com/iframe_api';
		var firstScriptTag = document.getElementsByTagName( 'script' )[0];
		firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );

		vimeoToggle();
		fixBlogVideoAspectRatios();

		if ( $body.hasClass( 'home' ) ) {
			frontPageVideoAudio();
		}
	};

	return {
		init : init,
		onYouTubeIframeAPIReady : onYouTubeIframeAPIReady,
		fixBlogVideoAspectRatios : fixBlogVideoAspectRatios
	};
}(jQuery));


(function() {
	'use strict';
	rp3.video.init();
}());


/** As far as I can tell, this has to sit in the global namespace. */

function onYouTubeIframeAPIReady() {
	rp3.video.onYouTubeIframeAPIReady();
}
