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
	lnobIsIE11 	= !!window.MSInputMethodContext && !!document.documentMode;


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

/* Set cookie -------------------------------- */

function setCookie( name, value, days ) {
    var expires = "";
    if ( days ) {
        var date = new Date();
        date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + ( value || "" )  + expires + "; path=/";
}

/* Get cookie -------------------------------- */

function getCookie( name ) {
    var nameEQ = name + "=";
    var ca = document.cookie.split( ';' );
    for ( var i=0; i < ca.length; i++ ) {
        var c = ca[i];
        while ( c.charAt( 0 ) == ' ' ) c = c.substring( 1, c.length );
        if ( c.indexOf( nameEQ ) == 0 ) return c.substring( nameEQ.length, c.length );
    }
    return null;
}

/* Delete cookie ----------------------------- */

function deleteCookie( name ) {   
    document.cookie = name + '=; Max-Age=-99999999;';
}

/* Output AJAX errors ------------------------ */

function ajaxErrors( jqXHR, exception ) {
	var message = '';
	if ( jqXHR.status === 0 ) {
		message = 'Not connect.n Verify Network.';
	} else if ( jqXHR.status == 404 ) {
		message = 'Requested page not found. [404]';
	} else if ( jqXHR.status == 500 ) {
		message = 'Internal Server Error [500].';
	} else if ( exception === 'parsererror' ) {
		message = 'Requested JSON parse failed.';
	} else if ( exception === 'timeout' ) {
		message = 'Time out error.';
	} else if ( exception === 'abort' ) {
		message = 'Ajax request aborted.';
	} else {
		message = 'Uncaught Error.n' + jqXHR.responseText;
	}
	console.log( 'AJAX ERROR:' + message );
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

			var currentScrollPos = $lnobWin.scrollTop(),
				docHeight = $body.outerHeight(),
				winHeight = $lnobWin.outerHeight();

			// Detect scrolling.
			if ( currentScrollPos > 0 || $( 'html' ).css( 'position' ) == 'fixed' ) {
				$body.addClass( 'is-scrolling' );
			} else {
				$body.removeClass( 'is-scrolling' );
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

			// Get the class to toggle, if specified.
			var classToToggle = $toggle.data( 'class-to-toggle' ) ? $toggle.data( 'class-to-toggle' ) : 'active';

			// Toggle the target of the clicked toggle.
			if ( $toggle.data( 'toggle-type' ) == 'slidetoggle' ) {
				var duration = $toggle.data( 'toggle-duration' ) ? $toggle.data( 'toggle-duration' ) : 250;
				$target.slideToggle( duration );
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
		}

	},

	// Show modals on load and click.
	// URL format: url.com?modal=modal-id
	showOnLoadAndClick: function() {

		var key = 'modal';

		// Show modal on load.
		if ( window.location.search.indexOf( key ) !== -1 ) {
			var modalID = getQueryStringValue( key )
			LNOB.coverModals.showModal( modalID );
		}

		// Show modal on link click.
		$lnobDoc.on( 'click', 'a', function() {
			if ( $( this ).attr( 'href' ) && $( this ).attr( 'href' ).indexOf( key ) !== -1 ) {
				var modalID = getQueryStringValue( key, $( this ).attr( 'href' ) );
				LNOB.coverModals.showModal( modalID );
				return false;
			}
		} );

	},

	// Show a modal based on ID.
	showModal: function( modalID ) {

		var modalTargetStr 	= '#' + modalID,
			$modalTarget 	= $( modalTargetStr );

		if ( modalID && $modalTarget.length ) {

			var $modalToggles = $( '*[data-toggle-target="' + modalTargetStr + '"]' );

			// Trigger by clicking one of the toggles, if they exist.
			if ( $modalToggles.length ) {
				$modalToggles.first().trigger( 'click' );

			// If not, approximate toggle trigger behavior.
			} else {
				$modalTarget.addClass( 'active' ).trigger( 'toggled' );
				LNOB.scrollLock.setTo( true );
			}

		}

	}

} // LNOB.coverModals


/*	-----------------------------------------------------------------------------------------------
	Element In View
--------------------------------------------------------------------------------------------------- */

LNOB.elementInView = {

	init: function() {

		$targets = $( '.do-spot' );
		LNOB.elementInView.run( $targets );

		// Rerun on AJAX content loaded.
		$lnobWin.on( 'ajax-content-loaded', function() {
			$targets = $( '.do-spot' );
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
		$( 'a[href*="#"]' ).not( '[href="#"]' ).not( '[href="#0"]' ).on( 'click', function( e ) {
			if ( location.pathname.replace(/^\//, '' ) == this.pathname.replace(/^\//, '' ) && location.hostname == this.hostname ) {
				$target = $( this.hash ).length ? $( this.hash ) : $( '[name=' + this.hash.slice(1) + ']' );
				var updateHistory = $( this ).attr( 'data-update-history' ) == 'false' ? false : true;
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
	scrollToTarget: function( $target, $clickElem, updateHistory ) {

		if ( $target.length ) {

			var additionalOffset 	= 0,
				scrollSpeed			= 500;

			// Get options.
			if ( $clickElem && $clickElem.length ) {
				additionalOffset 	= $clickElem.data( 'additional-offset' ) ? $clickElem.data( 'additional-offset' ) : 0,
				scrollSpeed 		= $clickElem.data( 'scroll-speed' ) ? $clickElem.data( 'scroll-speed' ) : 500;
			}

			// Determine offset.
			var originalOffset = $target.offset().top,
				scrollOffset = originalOffset + additionalOffset;

			// Update history, if set.
			if ( updateHistory ) {
				var hash = $target.attr( 'id' ) ? '#' + $target.attr( 'id' ) : '';
				if ( hash ) {
					history.replaceState( {}, '', hash );
				}
			}

			// Animate.
			$( 'html, body' ).animate( {
				scrollTop: scrollOffset,
			}, scrollSpeed, function() {
				$lnobWin.trigger( 'did-interval-scroll' );
			} );

		}

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

		if ( scrollLocked ) {
			return;
		}

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
		$lnobWin.scrollLeft( 0 ).scrollTop( 0 );

		scrollLocked = true;
	},

	// Unlock the scroll (do not call this directly).
	unlock: function() {

		if ( ! scrollLocked ) {
			return;
		}

		// Revert styles and state.
		$( 'html' ).attr( 'style', $( '<x>' ).css( prevLockStyles ).attr( 'style' ) || '' );
		$( 'html' ).removeClass( 'scroll-locked' );
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
	Load More
--------------------------------------------------------------------------------------------------- */

LNOB.loadMore = {

	init: function() {

		var $pagination = $( '.pagination' );

		// First, check that there's a pagination
		if ( $pagination.length ) {

			// Default values for variables
			window.lnobLMLoading = false;
			window.lnobLMLastPage = false;

			LNOB.loadMore.prepare( $pagination );

		}

	},

	prepare: function( $pagination ) {

		// Get the query arguments from the pagination element.
		var queryArgs = JSON.parse( $pagination.attr( 'data-query-args' ) );

		$paginationWrapper = $pagination.closest( '.pagination-wrapper' )

		// If we're already at the last page, exit out here
		if ( queryArgs.paged == queryArgs.max_num_pages ) {
			$paginationWrapper.addClass( 'last-page' );
		}

		// Get the load more type.
		var loadMoreType = $pagination.data( 'pagination-type' ) ? $pagination.data( 'pagination-type' ) : 'links';

		// Do the appropriate load more detection, depending on the type
		if ( loadMoreType == 'button' ) {
			LNOB.loadMore.detectButtonClick( $pagination );
		}

	},

	// Load more on click.
	detectButtonClick: function( $pagination ) {

		$lnobDoc.on( 'click', '.load-more-button', function() {

			// Get the query arguments from the pagination element.
			var queryArgs = JSON.parse( $pagination.attr( 'data-query-args' ) );

			// Make sure we aren't already loading.
			if ( lnobLMLoading ) return;

			LNOB.loadMore.loadPosts( $pagination, queryArgs );

			return false;
			
		} );

	},

	// Load the posts
	loadPosts: function( $pagination, queryArgs ) {

		var $paginationWrapper = $pagination.closest( '.pagination-wrapper' );

		// We're now loading.
		lnobLMLoading = true;

		$paginationWrapper.addClass( 'loading' );

		// Increment paged.
		queryArgs.paged++;

		// Write the updated query args to the pagination.
		$pagination.attr( 'data-query-args', JSON.stringify( queryArgs ) );

		// Prepare the query args for submission.
		var jsonQueryArgs = JSON.stringify( queryArgs );

		$.ajax( {
			url: lnobData.ajaxURL,
			type: 'post',
			data: {
				action: 'lnob_ajax_load_more',
				json_data: jsonQueryArgs
			},
			success: function( result ) {

				// Get the results.
				var $result 			= $( result ),
					$articleWrapper 	= $( $pagination.data( 'load-more-target' ) );

				$paginationWrapper.removeClass( 'loading' );

				// If there are no results, we're at the last page.
				if ( ! $result.length ) {
					lnobLMLoading = false;
					$paginationWrapper.addClass( 'last-page' );

				} else if ( $result.length ) {

					// Append the results.
					$articleWrapper.append( $result );

					$lnobWin.trigger( 'ajax-content-loaded' );
					$lnobWin.trigger( 'did-interval-scroll' );

					// Update history.
					if ( $pagination.data( 'update-history' ) ) {
						LNOB.loadMore.updateHistory( queryArgs.paged );
					}

					// We're now finished with the loading.
					lnobLMLoading = false;

					// If that was the last page, make sure we don't check for any more.
					if ( queryArgs.paged == queryArgs.max_num_pages ) {
						$paginationWrapper.addClass( 'last-page' );
						lnobLMLastPage = true;
						return;
					} else {
						$paginationWrapper.removeClass( 'last-page' );
						lnobLMLastPage = false;
					}

				}

			},

			error: function( jqXHR, exception ) {
				ajaxErrors( jqXHR, exception );
			}

		} );

	},

	// Update browser history
    updateHistory: function( paged ) {

		var newUrl,
			currentUrl = document.location.href;

		var hasPaginationRegexp = new RegExp( '^(.*/page)/[0-9]*/(.*$)' );

		if ( hasPaginationRegexp.test( currentUrl ) ) {
			newUrl = currentUrl.replace( hasPaginationRegexp, '$1/' + paged + '/$2' );
		} else {
			var beforeSearchReplaceRegexp = new RegExp( '^([^?]*)(\\??.*$)' );
			newUrl = currentUrl.replace( beforeSearchReplaceRegexp, '$1page/' + paged + '/$2' );
		}

		history.pushState( {}, '', newUrl );

	}

} // LNOB.loadMore


/*	-----------------------------------------------------------------------------------------------
	Function Calls
--------------------------------------------------------------------------------------------------- */

$lnobDoc.ready( function() {

	LNOB.intervalScroll.init();		// Check for scroll on an interval.
	LNOB.resizeEnd.init();			// Trigger event at end of resize.
	LNOB.isScrolling.init();			// Check for scroll direction.
	LNOB.toggles.init();				// Handle toggles.
	LNOB.coverModals.init();			// Handle cover modals.
	LNOB.elementInView.init();		// Check if elements are in view.
	LNOB.smoothScroll.init();			// Smooth scroll to anchor link or a specific element.
	LNOB.scrollLock.init();			// Scroll Lock.
	LNOB.focusManagement.init();		// Focus Management.
	LNOB.loadMore.init();				// Load More.

} );