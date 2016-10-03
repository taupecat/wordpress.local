// Define our "rp3" object, if not already defined
if ( rp3 === undefined ) { var rp3 = {}; }

var rp3 = (function($) {

	'use strict';

	var $body = $('body'),

	/* ==========================================================================
	Breakpoints
	========================================================================== */

	breakpoint,

	getBreakpoint = function() {

		var breakpointSmall			= 321 / 16,
			breakpointMedium		= 600 / 16,
			breakpointIntermediate	= 800 / 16,
			breakpointLarge			= 1000 / 16;

		// Upgrade, if appropriate
		if ( window.matchMedia( "( min-width: " + breakpointLarge + "em )" ).matches ) {
			return 'large';
		} else if ( window.matchMedia( "( min-width: " + breakpointIntermediate + "em )" ).matches ) {
			return 'intermediate';
		} else if ( window.matchMedia( "( min-width: " + breakpointMedium + "em )" ).matches ) {
			return 'medium';
		} else if ( window.matchMedia( "( min-width: " + breakpointSmall + "em )" ).matches ) {
			return 'small';
		}

		// If all else fails, return default.
		return 'default';
	},

	/* ==========================================================================
	   Raptor Jim
	========================================================================== */

	raptorJim = function() {
		var $body = $('body');

		if ( $body.hasClass('error404') ) {
			$body.raptorize({
				'enterOn': 'konami-code'
			});

			$('#rp404').raptorize({
				"enterOn" : "timer",
				"delayTime": 300000
			});
		}
	},

	/* ==========================================================================
	   Reveal Comments
	========================================================================== */

	revealComments = function() {
		$( 'body' ).on( 'click', '.single-blog__comments__trigger', function(e) {
			e.preventDefault();

			var $this = $(this),
				post_id = $this.data('commentPost'),
				$commentsForm = $('#single-blog__comments__form-' + post_id);

			$commentsForm.slideDown();
		});
	},

	// Sorry for the mess...
	campaignMonitor = function() {

		$('#subForm').on( 'submit', function(e) {
			e.preventDefault();
			$.getJSON(
				this.action + "?callback=?",
				$(this).serialize(),
				function( data ) {
					if ( data.Status === 400 ) {
						alert( "Error: " + data.Message );
					} else {
						var successMsg = "Thank you!<br><br>" + data.Message,
							modalElement = $('<div>').addClass( 'blog__subscribe__modal' ),
							template = _.template( $('#blog-template-subscription-modal' ).html() );

						if ( ! Modernizr.touch ) {
							modalElement.html( template() );
							modalElement.find( '#blog-subscription-modal__message' ).html( successMsg );
							$body.append( modalElement );
							$('input[type="email"]').val('');
							modalElement.find( '#blog-subscription-modal__close' ).on( 'click', function(e) {
								e.preventDefault();
								modalElement.fadeOut( 100, function() {
									$(this).remove();
								});
							});

							$body.keydown( function ( e ) {
								if(e.keyCode == 27) {
									e.preventDefault();
									modalElement.fadeOut( 100, function() {
										$(this).remove();
									});
								}
							});
						} else {
							$('input[type="email"]').val('');
							alert( successMsg.replace( '<br><br>', "\n\n" ) );
						}
					}
				}
			);
		});
	},

	/** Track Blog Related Posts clicks */
	trackBlogRelated = function() {
		$( 'body' ).on( 'click', '.single-blog__related__post', function() {

			if ( undefined !== window.ga ) {
				ga( 'send', 'event', 'Navigation', 'Blog Related Post Clicked' );
			}
		});
	},

	blogSubHeader = function() {

		var $blogSubHeader = $('#blog-header__sub-header'),
			waypoint;

		if ( $blogSubHeader.size() > 0 ) {

			waypoint = $blogSubHeader.waypoint({
				handler: function( direction ) {
					if ( 'down' === direction ) {
						$blogSubHeader.addClass('fixed');
					} else if ( 'up' === direction ) {
						$blogSubHeader.removeClass('fixed');
					}
				}
			});
		}
	},

	/* ==========================================================================
	   Copy permalink to clipboard
	========================================================================== */

	copyPermalinkSuccess = function( postID ) {
		var $toolTip = $('#share-link-' + postID);

		$toolTip.addClass( 'tt--open tt--success' );

		setTimeout( function() {
			$toolTip.removeClass( 'tt--open tt--success' );
		}, 3000 );
	},

	copyPermalinkFailure = function( postID ) {
		var $toolTip = $('#share-link-' + postID);

		$toolTip.addClass( 'tt--open tt--failure' );

		setTimeout( function() {
			$toolTip.removeClass( 'tt--open tt--failure' );
		}, 3000 );
	},

	copyPermalinkToClipboard = function() {

		var clipboard = new Clipboard('.share-link'),
			$shareLink = $('.share-link'),
			postID;

		$shareLink.on( 'click', function(e) {
			e.preventDefault();
			postID = $(this).data( 'post-id' );
		});

		clipboard.on( 'success', function() {
			copyPermalinkSuccess( postID );
		});

		clipboard.on( 'error', function() {
			copyPermalinkFailure( postID );
		});
	},

	copyPermalinkOption = function() {

		var $shareLink = $('li.share-link');

		$shareLink.each( function() {

			var postID = $(this).data( 'post-id' );

			$( '#share-link-' + postID ).insertBefore( $('#post-' + postID + ' .share-end' ) );
		});
	},

	/* ==========================================================================
	   Display a Career Article
	========================================================================== */

	displayNthCareerArticle = function( i, $thisButton ) {

		var panelID, $columnContainer,
			thisID = $thisButton.data('id'),
			$thisPost = $('#post-' + thisID),
			$thisContent = $thisPost.find('.careers__content');

		panelID = $thisButton.parents( '.page__panel' ).data( 'panel-id' );

		// Close previously open buttons in this section
		$('#panel-' + panelID).find('button.careers__trigger').each( function() {

			if ( $(this).hasClass('open') ) {
				$(this).removeClass('open');
			}
		});

		$thisButton.addClass('open');

		$columnContainer = $('#panel-' + panelID ).find( '.careers__inner__right' );

		$columnContainer.html( $thisContent.html() );
	},

	displayCareerArticle = function() {

		var $allPosts			= $('.careers__article'),
			$buttons			= $('button.careers__trigger'),
			thisID, $thisPost, $thisContent,
			thatID, $thatPost, $thatContent;

		$buttons.each( function(i) {

			// Disable the link that only does something if JS isn't available
			$(this).find('a').on( 'click', function(e) {
				e.preventDefault();
			});

			$(this).on( 'click', function() {

				// Proceed as normal…
				thisID = $(this).data( 'id' );
				$thisPost = $('#post-' + thisID);
				$thisContent = $thisPost.find( '.careers__content' );

				if ( ( breakpoint === 'medium' ) || ( breakpoint === 'intermediate' ) || ( breakpoint === 'large' ) ) {

					/** Column Mode */

					displayNthCareerArticle( i, $(this) );

				} else {

					/** Accordion Mode */

					if ( $thisPost.hasClass( 'open' ) ) {

						$thisPost.addClass('close').removeClass('open');

						if ( Modernizr.csstransitions ) {
							$thisPost.one( 'transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function() {
								$thisPost.removeClass( 'close' );
								$thisContent.slideUp( 200 );
							});
						} else {

						}
					} else if ( ! $thisPost.hasClass( 'close' ) ) {

						// Close any other open posts
						$allPosts.each( function() {

							thatID = $(this).find('.careers__trigger').data( 'id' );
							$thatPost = $('#post-' + thatID);
							$thatContent = $('#post-' + thatID + '-content');

							if ( ( thisID !== thatID ) && ( $thatPost.hasClass( 'open' ) ) ) {
								$thatPost.removeClass('open');
								$thatContent.slideUp( 200, function() {

									$('html, body').animate({
										scrollTop: $thisPost.offset().top
									}, 200);
								});
							}
						});

						$thisContent.slideDown( 200, function() {
							$thisPost.addClass('open');
						});
					}
				}
			});
		});
	},

	displayFirstCareerArticle = function() {

		$('.page__panel__careers').each( function() {
			displayNthCareerArticle( 0, $(this).find('button.careers__trigger').eq(0) );
		});
	},

	/* ==========================================================================
	   Element fade in/fade out (expose to public)
	========================================================================== */

	elementFadeIn = function( element ) {

		element.addClass( 'open' );
	},

	elementFadeOut = function( element ) {

		element.addClass( 'close' ).removeClass( 'open' );

		if ( Modernizr.csstransitions ) {

			element.one( 'transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function () {
				element.removeClass( 'close' );
			});
		}
	},

	/* ==========================================================================
	   Init
	========================================================================== */

	init = function() {
		raptorJim();
		revealComments();
		trackBlogRelated();
		campaignMonitor();
		blogSubHeader();
		copyPermalinkToClipboard();
		copyPermalinkOption();
		displayCareerArticle();


		var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

		var observer = new MutationObserver( function() {

			// fired when a mutation occurs
			copyPermalinkOption();
			copyPermalinkToClipboard();
		});

		// define what element should be observed by the observer
		// and what types of mutations trigger the callback
		observer.observe(document, {
			subtree: true,
			attributes: true
		});

		// Figure out our current breakpoint. It's a constant struggle.

		breakpoint = getBreakpoint();

		$(window).on( 'resize', function() {
			breakpoint = getBreakpoint();
		});

		if ( $body.hasClass('page-careers') ) {

			if ( ( breakpoint === 'medium' ) || ( breakpoint === 'intermediate' ) || ( breakpoint === 'large' ) ) {
				displayFirstCareerArticle();
			}
		}
	};

	return {
		init:init,
		elementFadeIn:elementFadeIn,
		elementFadeOut:elementFadeOut
	};

}(jQuery));

(function() {
	'use strict';
	rp3.init();
}());
