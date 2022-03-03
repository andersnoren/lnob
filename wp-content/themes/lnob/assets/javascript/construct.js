/*	-----------------------------------------------------------------------------------------------
	Namespace
--------------------------------------------------------------------------------------------------- */

var LNOB = LNOB || {},
    $ = jQuery;


/*	-----------------------------------------------------------------------------------------------
	Global variables
--------------------------------------------------------------------------------------------------- */

var $lnobDoc 		= $( document ),
    $lnobWin 		= $( window ),
	lnobIsIE11 		= !!window.MSInputMethodContext && !!document.documentMode;


/*	-----------------------------------------------------------------------------------------------
	Helper functions
--------------------------------------------------------------------------------------------------- */

/* Get the value from a query string key ----- */

function getQueryStringValue( key, string ) {
	if ( typeof string === 'undefined' ) { string = window.location.search; }
	key = key.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp( '[\\?&]' + key + '=([^&#]*)' );
    var results = regex.exec( string );
    return results === null ? '' : decodeURIComponent( results[1].replace(/\+/g, ' ' ) );
}

/* Comma Seperate Number --------------------- */

function commaSeparateNumber( val ) {
	while ( /(\d+)(\d{3})/.test( val.toString() ) ) {
		val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
	}
	return val;
}


/*	-----------------------------------------------------------------------------------------------
	Interval Scroll
--------------------------------------------------------------------------------------------------- */

LNOB.intervalScroll = {

	init: function() {

		didScroll = false;

		// Check for the scroll event.
		$lnobWin.on( 'scroll load', function() {
			didScroll = true;
		} );

		// Once every 250ms, check if we have scrolled, and if we have, do the intensive stuff.
		setInterval( function() {
			if ( didScroll ) {
				didScroll = false;

				// When this triggers, we know that we have scrolled.
				$lnobWin.trigger( 'did-interval-scroll' );

			}

		}, 250 );

	},

} // LNOB.intervalScroll


/*	-----------------------------------------------------------------------------------------------
	Resize End Event
--------------------------------------------------------------------------------------------------- */

LNOB.resizeEnd = {

	init: function() {

		var resizeTimer;

		$lnobWin.on( 'resize', function(e) {

			clearTimeout( resizeTimer );
			
			resizeTimer = setTimeout( function() {

				// Trigger this at the end of screen resizing.
				$lnobWin.trigger( 'resize-end' );
						
			}, 250 );

		} );

	},

} // LNOB.resizeEnd


/*	-----------------------------------------------------------------------------------------------
	Is Scrolling
--------------------------------------------------------------------------------------------------- */

LNOB.isScrolling = {

	init: function() {

		scrollPos = 0;

		// Sensitivity for the scroll direction check (higher = more scroll required to reverse).
		directionBuffer = 50;

		var $body = $( 'body' );

		$lnobWin.on( 'did-interval-scroll', function() {

			var currentScrollPos 	= $lnobWin.scrollTop(),
				scrollLockPos 		= $( 'html' ).attr( 'scrollock-position' ),
				docHeight 			= $body.outerHeight(),
				winHeight 			= $lnobWin.outerHeight();

			// If scroll lock has been set, use the position stored before locking.
			if ( scrollLockPos ) currentScrollPos = scrollLockPos;

			// Detect scrolling.
			if ( currentScrollPos > 0 ) {
				$body.addClass( 'is-scrolling' );
			} else {
				$body.removeClass( 'is-scrolling' );
			}

			if ( currentScrollPos > $lnobWin.outerHeight() ) {
				$body.addClass( 'scrolled-screen-height' );
			} else {
				$body.removeClass( 'scrolled-screen-height' );
			}

			// Detect whether we're at the bottom.
			if ( currentScrollPos + winHeight >= docHeight ) {
				$body.addClass( 'scrolled-to-bottom' );
			} else {
				$body.removeClass( 'scrolled-to-bottom' );
			}

			// Detect the scroll direction.
			if ( currentScrollPos > ( $lnobWin.outerHeight() / 3 ) ) {
				
				if ( Math.abs( scrollPos - currentScrollPos ) >= directionBuffer ) {
				
					// Scrolling down.
					if ( currentScrollPos > scrollPos ){
						$( 'body' ).addClass( 'scrolling-down' ).removeClass( 'scrolling-up' );

					// Scrolling up.
					} else {
						$( 'body' ).addClass( 'scrolling-up' ).removeClass( 'scrolling-down' );
					}

				}

			} else {

				$( 'body' ).removeClass( 'scrolling-up scrolling-down' );

			}

			scrollPos = currentScrollPos;

		} );

	}

} // LNOB.isScrolling


/*	-----------------------------------------------------------------------------------------------
	Toggles
--------------------------------------------------------------------------------------------------- */

LNOB.toggles = {

	init: function() {

		// Do the toggle.
		LNOB.toggles.toggle();

		// Check for toggle/untoggle on resize.
		LNOB.toggles.resizeCheck();

		// Check for untoggle on escape key press.
		LNOB.toggles.untoggleOnEscapeKeyPress();

		// Check for untoggle on outside click.
		LNOB.toggles.untoggleOnOutsideClick();

	},

	// Do the toggle.
	toggle: function() {

		$lnobDoc.on( 'click', '*[data-toggle-target]', function( e ) {

			// Get our targets.
			var $toggle = $( this ),
				targetString = $( this ).data( 'toggle-target' );

			if ( targetString == 'next' ) {
				var $target = $toggle.next();
			} else {
				var $target = $( targetString );
			}

			// Trigger events on the toggle targets before they are toggled.
			if ( $target.is( '.active' ) ) {
				$target.trigger( 'toggle-target-before-active' );
			} else {
				$target.trigger( 'toggle-target-before-inactive' );
			}

			// For cover modals, set a short timeout duration so the class animations have time to play out.
			var timeOutTime = $target.hasClass( 'cover-modal' ) ? 5 : 0;

			setTimeout( function() {

				// Get the class to toggle, if specified.
				var classToToggle = $toggle.data( 'class-to-toggle' ) ? $toggle.data( 'class-to-toggle' ) : 'active';

				// Toggle the target of the clicked toggle.
				if ( $toggle.data( 'toggle-type' ) == 'slidetoggle' ) {
					var duration = $toggle.data( 'toggle-duration' ) ? $toggle.data( 'toggle-duration' ) : 250;
					$target.slideToggle( duration );
				} else if ( $toggle.data( 'toggle-type' ) == 'toggle' ) {
					$target.toggle();
				} else {
					$target.toggleClass( classToToggle );
				}

				// If the toggle target is 'next', only give the clicked toggle the active class.
				if ( targetString == 'next' ) {
					$toggle.toggleClass( 'active' )

				// If not, toggle all toggles with this toggle target.
				} else {
					$( '*[data-toggle-target="' + targetString + '"]' ).toggleClass( 'active' );
				}

				// Toggle body class.
				if ( $toggle.data( 'toggle-body-class' ) ) {
					$( 'body' ).toggleClass( $toggle.data( 'toggle-body-class' ) );
				}

				// Check whether to lock the screen scroll.
				if ( $toggle.data( 'lock-scroll' ) ) {
					LNOB.scrollLock.setTo( true );
				} else if ( $toggle.data( 'unlock-scroll' ) ) {
					LNOB.scrollLock.setTo( false );
				} else if ( $toggle.data( 'toggle-scroll-lock' ) ) {
					LNOB.scrollLock.setTo();
				}

				// Check whether to set focus.
				if ( $toggle.data( 'set-focus' ) ) {
					var $focusElement = $( $toggle.data( 'set-focus' ) );
					if ( $focusElement.length ) {
						if ( $toggle.is( '.active' ) ) {
							$focusElement.focus();
						} else {
							$focusElement.blur();
						}
					}
				}

				// Trigger the toggled event on the toggle target.
				$target.trigger( 'toggled' );

				if ( $toggle.hasClass( 'active' ) ) {
					$target.trigger( 'toggled-active' );
				} else {
					$target.trigger( 'toggled-inactive' );
				}

			}, timeOutTime );

			return false;

		} );
	},

	// Check for toggle/untoggle on screen resize.
	resizeCheck: function() {

		if ( $( '*[data-untoggle-above], *[data-untoggle-below], *[data-toggle-above], *[data-toggle-below]' ).length ) {

			$lnobWin.on( 'resize', function() {

				var winWidth = $lnobWin.width(),
					$toggles = $( '.toggle' );

				$toggles.each( function() {

					$toggle = $( this );

					var unToggleAbove = $toggle.data( 'untoggle-above' ),
						unToggleBelow = $toggle.data( 'untoggle-below' ),
						toggleAbove = $toggle.data( 'toggle-above' ),
						toggleBelow = $toggle.data( 'toggle-below' );

					// If no width comparison is set, continue.
					if ( ! unToggleAbove && ! unToggleBelow && ! toggleAbove && ! toggleBelow ) {
						return;
					}

					// If the toggle width comparison is true, toggle the toggle.
					if ( 
						( ( ( unToggleAbove && winWidth > unToggleAbove ) ||
						( unToggleBelow && winWidth < unToggleBelow ) ) &&
						$toggle.hasClass( 'active' ) )
						||
						( ( ( toggleAbove && winWidth > toggleAbove ) ||
						( toggleBelow && winWidth < toggleBelow ) ) &&
						! $toggle.hasClass( 'active' ) )
					) {
						$toggle.trigger( 'click' );
					}

				} );

			} );

		}

	},

	// Close toggle on escape key press.
	untoggleOnEscapeKeyPress: function() {

		$lnobDoc.keyup( function( e ) {
			if ( e.key === "Escape" ) {

				$( '*[data-untoggle-on-escape].active' ).each( function() {
					if ( $( this ).hasClass( 'active' ) ) {
						$( this ).trigger( 'click' );
					}
				} );
					
			}
		} );

	},

	// Close toggle on outside click.
	untoggleOnOutsideClick: function() {

		$lnobDoc.on( 'click', function( e ) {
			var $clickTarget = $( e.target ),
				$toggles = $( '*[data-untoggle-on-outside-click].active' );

			$toggles.each( function() {
				var $toggle = $( this ),
					$toggleTarget = $( $toggle.attr( 'data-toggle-target' ) );

				// On click outside the toggle or the toggle target, untoggle.
				if ( 
					! $clickTarget.is( $toggle ) && 
					! $clickTarget.parents().is( $toggle ) &&
					! $clickTarget.is( $toggleTarget ) && 
					! $clickTarget.parents().is( $toggleTarget )
				) {
					$toggle.trigger( 'click' );
				}
			} );
		} );

	},

} // LNOB.toggles


/*	-----------------------------------------------------------------------------------------------
	Cover Modals
--------------------------------------------------------------------------------------------------- */

LNOB.coverModals = {

	init: function() {

		if ( $( '.cover-modal' ).length ) {
			LNOB.coverModals.showOnLoadAndClick();
			LNOB.coverModals.hideAndShowModals();
		}

	},

	// Show modals on load and click.
	// URL format: url.com?modal=modal-id
	showOnLoadAndClick: function() {

		var key = 'modal';

		// Show modal on load.
		if ( window.location.search.indexOf( key ) !== -1 ) {
			var modalID = getQueryStringValue( key )
			LNOB.coverModals.toggleModal( modalID );
		}

		// Show modal on link click.
		$lnobDoc.on( 'click', 'a', function() {
			if ( $( this ).attr( 'href' ) && $( this ).attr( 'href' ).indexOf( key ) !== -1 ) {
				var modalID = getQueryStringValue( key, $( this ).attr( 'href' ) );
				LNOB.coverModals.toggleModal( modalID );
				return false;
			}
		} );

		$lnobDoc.on( '.cover-modal', 'toggled-inactive', function() {
			$lnobWin.trigger( 'did-interval-scroll' )
		} );

	},

	// Hide and show modals before and after their animations have played out.
	hideAndShowModals: function() {

		var $modals = $( '.cover-modal' );

		// Show the modal.
		$modals.on( 'toggle-target-before-inactive', function( e ) {
			if ( e.target != this ) return;
			$( this ).addClass( 'show-modal' );
		} );

		// Hide the modal after a delay, so animations have time to play out.
		$modals.on( 'toggle-target-before-active', function( e ) {
			if ( e.target != this ) return;

			var $modal = $( this );
			setTimeout( function() {
				$modal.removeClass( 'show-modal' );
			}, 250 );
		} );

	},

	toggleModal: function( modalID ) {

		var modalTargetStr 	= '#' + modalID,
			$modalTarget 	= $( modalTargetStr );

		if ( modalID && $modalTarget.length ) {

			var $modalToggles = $( '*[data-toggle-target="' + modalTargetStr + '"]' );

			// Trigger by clicking one of the toggles, if they exist.
			if ( $modalToggles.length ) {
				$modalToggles.first().trigger( 'click' );
			}

		}

	}

} // LNOB.coverModals


/*	-----------------------------------------------------------------------------------------------
	Element In View
--------------------------------------------------------------------------------------------------- */

LNOB.elementInView = {

	init: function() {

		$targets = $( '.do-spot' ).filter( ':visible' );
		LNOB.elementInView.run( $targets );

		// Rerun on AJAX content loaded.
		$lnobWin.on( 'ajax-content-loaded', function() {
			$targets = $( '.do-spot' ).filter( ':visible' );
			LNOB.elementInView.run( $targets );
		} );

	},

	run: function( $targets ) {

		if ( $targets.length ) {

			// Add class indicating the elements will be spotted.
			$targets.each( function() {
				$( this ).addClass( 'will-be-spotted' );
			} );

			LNOB.elementInView.handleFocus( $targets );

			$lnobWin.on( 'load resize orientationchange did-interval-scroll', function() {
				LNOB.elementInView.handleFocus( $targets );
			} );

		}

	},

	handleFocus: function( $targets ) {

		// Check for our targets.
		$targets.each( function() {

			var $this = $( this );

			if ( LNOB.elementInView.isVisible( $this, checkAbove = true ) ) {
				$this.addClass( 'spotted' ).trigger( 'spotted' );
			}

		} );

	},

	// Determine whether the element is in view.
	isVisible: function( $elem, checkAbove ) {

		if ( typeof checkAbove === 'undefined' ) {
			checkAbove = true;
		}

		var winHeight 				= $lnobWin.height();

		var docViewTop 				= $lnobWin.scrollTop(),
			docViewBottom			= docViewTop + winHeight,
			docViewLimit 			= docViewBottom;

		var elemTop 				= $elem.offset().top;

		// For elements with a transform: translateY value, subtract the translateY value for the elemTop comparison point.
		// IE11 doesn't support WebKitCSSMatrix, so don't do it in IE11.
		var elemTransform = window.getComputedStyle( $elem[0] ).getPropertyValue( 'transform' );
		if ( elemTransform && ! lnobIsIE11 ) {
			var elemTransformMatrix = new WebKitCSSMatrix( elemTransform );
			if ( elemTransformMatrix ) {
				elemTranslateY = elemTransformMatrix.m42;
				if ( elemTranslateY ) {
					elemTop = elemTop - elemTranslateY;
				}
			}
		}

		// If checkAbove is set to true, which is default, return true if the browser has already scrolled past the element.
		if ( checkAbove && ( elemTop <= docViewBottom ) ) {
			return true;
		}

		// If not, check whether the scroll limit exceeds the element top.
		return ( docViewLimit >= elemTop );

	}

} // LNOB.elementInView


/*	-----------------------------------------------------------------------------------------------
	Smooth Scroll
--------------------------------------------------------------------------------------------------- */

LNOB.smoothScroll = {

	init: function() {

		// Scroll to on-page elements by hash.
		$( 'a[href*="#"]' ).not( '[href="#"]' ).not( '[href="#0"]' ).not( '.disable-hash-scroll' ).on( 'click', function( e ) {
			if ( location.pathname.replace(/^\//, '' ) == this.pathname.replace(/^\//, '' ) && location.hostname == this.hostname ) {
				$target = $( this.hash ).length ? $( this.hash ) : $( '[name=' + this.hash.slice(1) + ']' );
				var updateHistory = $( this ).attr( 'data-update-history' ) == 'true' ? true : false;
				LNOB.smoothScroll.scrollToTarget( $target, $( this ), updateHistory );
				e.preventDefault();
			}
		} );

		// Scroll to elements specified with a data attribute.
		$lnobDoc.on( 'click', '*[data-scroll-to]', function( e ) {
			var $target = $( $( this ).data( 'scroll-to' ) ),
				updateHistory = $( this ).attr( 'data-update-history' ) == 'true' ? true : false;
			LNOB.smoothScroll.scrollToTarget( $target, $( this ), updateHistory );
			e.preventDefault();
		} );

	},

	// Scroll to target.
	scrollToTarget: function( $target, $clickElem = null, updateHistory ) {

		if ( $target.length ) {

			var additionalOffset 	= 0,
				scrollSpeed			= 500;

			// Get options.
			if ( $clickElem && $clickElem.length ) {
				additionalOffset 	= $clickElem.data( 'additional-offset' ) ? $clickElem.data( 'additional-offset' ) : 0,
				scrollSpeed 		= $clickElem.data( 'scroll-speed' ) ? $clickElem.data( 'scroll-speed' ) : 500;
			}

			// Close any parent modal before calculating offset and scrolling.
			if ( $clickElem && $clickElem.closest( '.cover-modal' ) ) {
				LNOB.coverModals.toggleModal( $clickElem.closest( '.cover-modal' ).attr( 'id' ) );
			}

			// Determine offset.
			var originalOffset 	= $target.offset().top,
				scrollOffset 	= originalOffset + additionalOffset;

			// Update history, if set.
			if ( updateHistory ) {
				var hash = $target.attr( 'id' ) ? '#' + $target.attr( 'id' ) : '';
				if ( hash ) {
					history.replaceState( {}, '', hash );
				}
			}

			// Scroll to position.
			LNOB.smoothScroll.scrollToPosition( scrollOffset, scrollSpeed );

		}

	},

	// Scroll to position.
	scrollToPosition: function( scrollOffset, scrollSpeed = 500 ) {

		// Animate.
		$( 'html, body' ).animate( {
			scrollTop: scrollOffset,
		}, scrollSpeed, function() {
			$lnobWin.trigger( 'did-interval-scroll' );
		} );

	}

} // LNOB.smoothScroll


/*	-----------------------------------------------------------------------------------------------
	Scroll Lock
--------------------------------------------------------------------------------------------------- */

LNOB.scrollLock = {

	init: function() {

		// Init variables.
		window.scrollLocked = false,
		window.prevScroll = {
			scrollLeft : $lnobWin.scrollLeft(),
			scrollTop  : $lnobWin.scrollTop()
		},
		window.prevLockStyles = {},
		window.lockStyles = {
			'overflow-y' : 'scroll',
			'position'   : 'fixed',
			'width'      : '100%'
		};

		// Instantiate cache in case someone tries to unlock before locking.
		LNOB.scrollLock.saveStyles();

	},

	// Save context's inline styles in cache.
	saveStyles: function() {

		var styleAttr = $( 'html' ).attr( 'style' ),
			styleStrs = [],
			styleHash = {};

		if ( ! styleAttr ) {
			return;
		}

		styleStrs = styleAttr.split( /;\s/ );

		$.each( styleStrs, function serializeStyleProp( styleString ) {
			if ( ! styleString ) {
				return;
			}

			var keyValue = styleString.split( /\s:\s/ );

			if ( keyValue.length < 2 ) {
				return;
			}

			styleHash[ keyValue[ 0 ] ] = keyValue[ 1 ];
		} );

		$.extend( prevLockStyles, styleHash );
	},

	// Lock the scroll (do not call this directly).
	lock: function() {

		var appliedLock = {};

		if ( scrollLocked ) return;

		// Save scroll state and styles.
		prevScroll = {
			scrollLeft : $lnobWin.scrollLeft(),
			scrollTop  : $lnobWin.scrollTop()
		};

		LNOB.scrollLock.saveStyles();

		// Compose our applied CSS, with scroll state as styles.
		$.extend( appliedLock, lockStyles, {
			'left' : - prevScroll.scrollLeft + 'px',
			'top'  : - prevScroll.scrollTop + 'px'
		} );

		// Then lock styles and state.
		$( 'html' ).css( appliedLock ).addClass( 'scroll-locked' );
		$( 'html' ).attr( 'scrollock-position', prevScroll.scrollTop );
		$lnobWin.scrollLeft( 0 ).scrollTop( 0 );

		scrollLocked = true;
	},

	// Unlock the scroll (do not call this directly).
	unlock: function() {

		if ( ! scrollLocked ) return;

		// Revert styles and state.
		$( 'html' ).attr( 'style', $( '<x>' ).css( prevLockStyles ).attr( 'style' ) || '' );
		$( 'html' ).removeClass( 'scroll-locked' ).removeAttr( 'scrollock-position' );
		$lnobWin.scrollLeft( prevScroll.scrollLeft ).scrollTop( prevScroll.scrollTop );

		scrollLocked = false;
	},

	// Call this to lock or unlock the scroll.
	setTo: function( on ) {

		// If an argument is passed, lock or unlock accordingly.
		if ( arguments.length ) {
			if ( on ) {
				LNOB.scrollLock.lock();
			} else {
				LNOB.scrollLock.unlock();
			}
			// If not, toggle to the inverse state.
		} else {
			if ( scrollLocked ) {
				LNOB.scrollLock.unlock();
			} else {
				LNOB.scrollLock.lock();
			}
		}

	},

} // LNOB.scrollLock


/*	-----------------------------------------------------------------------------------------------
	Focus Management
--------------------------------------------------------------------------------------------------- */

LNOB.focusManagement = {

	init: function() {

		// Focus loops.
		LNOB.focusManagement.focusLoops();

	},

	focusLoops: function() {

		// Add focus loops, for use by full screen modals and other elements that need to trap.
		$lnobDoc.keydown( function( e ) {

			var $focusElement 	= $( ':focus' ),
				$focusLoop 		= $focusElement.closest( '.focus-loop' ),
				$destination 	= false;

			if ( e.keyCode == 9 && $focusLoop.length ) {

				// Get the first and last visible focusable elements in focus loop containers, for comparison against the focused element.
				var $focusable 		= $focusLoop.find( 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])' ).filter( ':visible' ),
					$focusableFirst = $focusable.first(),
					$focusableLast 	= $focusable.last();

				// Tabbing backwards.
				if ( e.shiftKey ) {
					if ( $focusElement.is( $focusableFirst ) ) {
						$destination = $focusableLast;
					}
				}
				// Tabbing forwards.
				else {
					if ( $focusElement.is( $focusableLast ) ) {
						$destination = $focusableFirst;
					}
				}

				// If a destination is set, change focus.
				if ( $destination ) {
					$destination.focus();
					return false;
				}

			}

		} );

	}

} // LNOB.focusManagement


/*	-----------------------------------------------------------------------------------------------
	Dynamic Screen Height
--------------------------------------------------------------------------------------------------- */

LNOB.dynamicScreenHeight = {

	init: function() {

		var $screenHeight = $( '.screen-height' );

		$screenHeight.css( 'min-height', $lnobWin.innerHeight() );

		setTimeout( function() {
			$screenHeight.css( 'min-height', $lnobWin.innerHeight() );
		}, 500 );

		$lnobWin.on( 'resize orientationchange', function() {
			$screenHeight.css( 'min-height', $lnobWin.innerHeight() );
		} );

	},

} // LNOB.dynamicScreenHeight


/*	-----------------------------------------------------------------------------------------------
	Front Page
--------------------------------------------------------------------------------------------------- */

LNOB.frontPage = {

	init: function() {

		// Rotating LNOB symbol.
		LNOB.frontPage.lnobSymbol();

		// Global Goals.
		LNOB.frontPage.gg();

		// Main Menu.
		LNOB.frontPage.mainMenu();

		// Footnotes.
		LNOB.frontPage.footnotes();

		// Insert citation mark in the pull quote block.
		LNOB.frontPage.pullQuote();

	},

	lnobSymbol: function() {

		var degrees = Math.random() * ( 360 - 0 ) + 0;

		$( '.lnob-symbol-inner' ).css( { 'transform': 'rotate(' + degrees + 'deg)' } );
		$( '.lnob-symbol' ).addClass( 'do-spot' );
		$lnobWin.trigger( 'ajax-content-loaded' );

	},

	gg: function() {

		// Scroll behavior on expanding/collapsing.
		$( '.gg-content' ).on( 'toggled-active', function() {
			$( this ).closest( '.gg' ).addClass( 'showing-content' );
			$lnobWin.trigger( 'resize' );
			LNOB.smoothScroll.scrollToTarget( $( $( this ) ) );

			// Make sure that the do-spot elements are triggered.
			$lnobWin.trigger( 'ajax-content-loaded' );
		} );
		
		$( '.gg-content' ).on( 'toggled-inactive', function() {
			$( this ).closest( '.gg' ).removeClass( 'showing-content' );
			$lnobWin.trigger( 'resize' );

			// Get the offset of the current global goal.
			// Note: offset().top doesn't work with elements set to a sticky position.
			var ggOffset 		= $( '.global-goals' ).offset().top,
				currentIndex 	= $( this ).closest( '.gg' ).index();

			$( '.global-goals .gg' ).each( function() {
				if ( $( this ).index() < currentIndex ) {
					ggOffset += $( this ).outerHeight();
				}
			} );
			
			// Scroll to the global goal.
			LNOB.smoothScroll.scrollToPosition( ggOffset );
			
		} );

	},

	mainMenu: function() {

		$lnobDoc.on( 'mouseenter focus', '.menu-gg-grid a', function() {
			$( '.menu-gg-grid a' ).addClass( 'not-hover' );
			$( this ).removeClass( 'not-hover' );
		} );

		$lnobDoc.on( 'mouseleave blur', '.menu-gg-grid', function() {
			$( '.menu-gg-grid a' ).removeClass( 'not-hover' );
		} );

	},

	footnotes: function() {

		$( '.footnotes-button' ).on( 'click', function() {
			$( this ).add( $( this ).closest( '.gg' ).find( '.footnotes-box-wrapper' ) ).toggleClass( 'active' );
		} );

		$lnobDoc.on( 'click', '.footnote-identifier-link', function() {

			var $gg 				= $( this ).closest( '.gg' ),
				$footnotesBox 		= $gg.find( '.footnotes-box-wrapper' ),
				$footnotesButton 	= $gg.find( '.footnotes-button' );

			$footnotesBox.add( $footnotesButton ).addClass( 'active' );
			LNOB.smoothScroll.scrollToTarget( $footnotesBox, $( this ) );

			return false;
		} );

	},

	// Insert a quotation mark in the Pull Quote block.
	pullQuote: function() {

		$( '.wp-block-pullquote blockquote' ).each( function() {
			$( this ).prepend( '<svg fill="none" height="90" viewBox="0 0 128 90" width="128" xmlns="http://www.w3.org/2000/svg"><g fill="#e5243b"><path d="m25.858 57.1973c-14.514-1.4113-25.858-13.6462-25.858-28.5307 0-15.8321 12.8345-28.6666 28.6666-28.6666 15.4835 0 28.1 12.2756 28.648 27.6254.8398 13.9425-4.6097 44.5845-42.2484 61.0211-8.50382 3.7136-15.473915-9.4876-6.7187-14.6183 5.4389-3.1873 14.7079-10.0411 17.5105-16.8309z"/><path d="m96.4657 57.1973c-14.5139-1.4113-25.858-13.6462-25.858-28.5307 0-15.8321 12.8345-28.6666 28.6666-28.6666 15.4837 0 28.0997 12.2756 28.6477 27.6254.84 13.9425-4.609 44.5845-42.248 61.0211-8.5039 3.7136-15.474-9.4876-6.7188-14.6183 5.4389-3.1873 14.708-10.0411 17.5105-16.8309z"/></g></svg>' )
		} );

	}

} // LNOB.frontPage


/*	-----------------------------------------------------------------------------------------------
	Count Up
--------------------------------------------------------------------------------------------------- */

LNOB.countUp = {

	init: function() {

		$( '.count-up' ).each( function() {

			// Remove thousand decimals, since we add those ourselves on count
			var countValue = $( this ).text().replace( ',', '' );

			// Skip counters with dot decimals
			if ( countValue.indexOf( '.' ) >= 0 ) return;

			// Skip non numeric values
			if ( ! $.isNumeric( countValue ) ) return;

			$( this ).attr( 'data-count-value', countValue.replace( /[^0-9]/g, "" ) );
			$( this ).css( 'width', $( this ).outerWidth() );
			$( this ).text( '0' );
		} );

		$lnobWin.on( 'resize-end', function() {
			$( '.count-up' ).css( 'width', 'auto' );
		} );

		$( '.count-up' ).closest( '.do-spot' ).on( 'spotted', function() {

			var $countElem = $( this ).find( '.count-up' ),
				countValue = $countElem.attr( 'data-count-value' ),
				countDuration = $countElem.data( 'count-duration' ) ? $countElem.data( 'count-duration' ) : 1000;

			// Skip counters that aren't setup
			if ( ! countValue ) return;

			if ( $countElem.hasClass( 'started-count' ) ) return;
			$countElem.addClass( 'started-count' );

			$( { countNum: '0' } ).animate( { countNum: countValue }, {
				duration: countDuration,
				easing: 'linear',
				step: function() {
					$countElem.text( commaSeparateNumber( Math.floor( this.countNum ) ) );
				},
				complete: function() {
					$countElem.text( commaSeparateNumber( countValue ) );
					$countElem.addClass( 'counted' );
				}
			} );

		} );

	},

} // LNOB.countUp


/*	-----------------------------------------------------------------------------------------------
	Function Calls
--------------------------------------------------------------------------------------------------- */

$lnobDoc.ready( function() {

	LNOB.intervalScroll.init();			// Check for scroll on an interval.
	LNOB.resizeEnd.init();				// Trigger event at end of resize.
	LNOB.isScrolling.init();			// Check for scroll direction.
	LNOB.scrollLock.init();				// Scroll Lock.
	LNOB.toggles.init();				// Handle toggles.
	LNOB.coverModals.init();			// Handle cover modals.
	LNOB.elementInView.init();			// Check if elements are in view.
	LNOB.smoothScroll.init();			// Smooth scroll to anchor link or a specific element.
	LNOB.focusManagement.init();		// Focus Management.
	LNOB.frontPage.init();				// Front Page.
	LNOB.dynamicScreenHeight.init();	// Dynamic screen height.
	LNOB.countUp.init();				// Count Up.

} );