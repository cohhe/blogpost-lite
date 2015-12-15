// On window load. This waits until images have loaded which is essential
/*global jQuery:false, my_ajax:false, on_resize:false */
/*jshint unused:false */
jQuery(window).load(function() {
	"use strict";

	// jQuery('.wpb_thumbnails-posts').isotope().isotope();
	jQuery(document).trigger('blogpost:ajaxPageLoad');


	// Hide loading effect
	jQuery('.overlay-hide').hide();

	jQuery('#vh_loading_effect').addClass( 'hide' ).delay(500).queue(function(next){
		jQuery(this).hide();
		next();
	});

	jQuery('body').removeClass('disable-animations');

	jQuery(window).bind("debouncedresize", function() {
		if ( jQuery('.page-wrapper').width() < 900 ) {
			jQuery('#author-description').addClass('full-width');
		} else {
			jQuery('#author-description').removeClass('full-width');
		}
	});
});

var blogpost = {};

(function($){

	/*------------------------------------------------------------
	 * FUNCTION: Scroll Page Back to Top
	 * Used for ajax navigation scroll position reset
	 *------------------------------------------------------------*/

	function scrollPageToTop(){
		// Height hack for mobile/tablet
		$('body').css('height', 'auto');

		if( blogpost.device != 'desktop' ){
			$('body').scrollTop(0);
		}else{
			$('.content-wrapper').scrollTop(0);
		}

		$('body').css('height', '');
	}

	/*------------------------------------------------------------
	 * FUNCTION: Ajax Load Pages
	 *------------------------------------------------------------*/

	function ajaxLoadPages(){

		var hashedLink;
		var ajaxLoadPageTime = 0;
		var popped = true;

		// Event: Link clicked
		$('html').on('click','a',function(e) {

			if ( jQuery(this).parent().hasClass('menu-item-has-children') ) {
				e.preventDefault();
				jQuery('.menu-item.menu-item-has-children').removeClass('active');
				jQuery(this).parent().addClass('active');
				return;
			};

			// Suppress double clicks
			var now = new Date().getTime();
			var dt = now - ajaxLoadPageTime;
			if ( dt < 700 ) {
				e.preventDefault();
				return;
			}
			ajaxLoadPageTime = now;

			var href = $(this).attr('href');

			if( isExternal(href) ){
				return;
			}

			// assume that clicked link is hashed
			hashedLink = true;

			if (
				( !$(this).is(".ab-item, .comment-reply-link, #cancel-comment-reply-link, .comment-edit-link, .wp-playlist-caption, .js-skip-ajax") ) &&
				( href.indexOf('#') == -1 ) &&
				( href.indexOf('wp-login.php') == -1 ) &&
				( href.indexOf('/wp-admin/') == -1 ) &&
				( href.indexOf('wp-content/uploads/') == -1 ) &&
				( $(this).attr('target') != '_blank' ) &&
				// WPML: on lang change, full page load
				( $(this).attr('hreflang') !== '' ) &&
				( $(this).parents('#lang_sel').length === 0 ) &&
				( $(this).parents('#lang_sel_click').length === 0 ) &&
				( $(this).parents('#lang_sel_list').length === 0 ) &&
				( $(this).parents('.menu-item-language').length === 0 ) &&
				// Disqus: doesn't support ajax
				( typeof DISQUS === 'undefined' ) &&
				( typeof countVars === 'undefined' || typeof countVars.disqusShortname === 'undefined' )
			){
				e.preventDefault();
				popped = true;
				hashedLink = false;

				// change only main content and leave sidebar intact
				var pagination = $(this).is('.page-numbers') ? true : false;
				push_state(href, pagination);
			}

		});

		// Event: Popstate - Location History Back/Forward
		$(window).on('popstate',function(){
			// if hashed link, load native way
			// popped? don't trigger on init page load [chrome bug]
			if(!hashedLink && popped){
				ajaxLoadPage(location.href);
			}
			popped = true;
		});

		// Function: PushState and trigger ajax loader
		function push_state(href, pagination){
			history.pushState({page: href}, '', href);
			ajaxLoadPage(href, pagination);
		}

		// Function: Ajax Load Page
		function ajaxLoadPage(href, pagination) {

			jQuery('body').addClass('disable-animations');
			$('body').removeClass('ajax-main-content-loading-end ajax-content-wrapper-loading-end');
			if ( !$('.page-wrapper').hasClass('menu-active') ) {
				$('#vh_loading_effect').addClass('full');
			} else {
				$('#vh_loading_effect').removeClass('full');
			}
			// $('#vh_loading_effect').show().animate({
			// 	opacity: "1"
			// }, 300);

			jQuery('#vh_loading_effect').removeClass( 'hide' ).show();

			var timeStarted = 0;

			timeStarted = new Date().getTime();

			blogpost.xhr = $.ajax({
				type: "GET",
				url: href,
				success: function(data, response, xhr){

					// Check if css animation had time to finish
					// before new page load animation starts
					var now = new Date().getTime();
					var timeDiff = now - timeStarted;
					if( timeDiff < 1000 ) {
						setTimeout( ajaxLoadPageCallback, (1000-timeDiff) );
					}else{
						ajaxLoadPageCallback();
					}

					function ajaxLoadPageCallback() {
						var $data = $(data);

						// Update Page Title in browser window
						var pageTitle = $data.filter('title').text();
						document.title = pageTitle;

						// console.log('Page loaded in: '+timeDiff+'ms');

						if (typeof google === "object" && typeof google.maps === "object") {
							// Google api loaded
						} else {
							// Load google api
							if ( $data.find('#contact-us-map').length ) {
								var script = document.createElement("script");
								script.type = "text/javascript";
								script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&ver=3&callback=vh_contact_map";
								document.head.appendChild(script);
							};
						}

						$('.page-wrapper').html( $data.find('#page-inner-container') );

						// font-awesome-css
						if ( $data.filter('#font-awesome-css').length ) {
							var font_awesome = $data.filter('#font-awesome-css').attr('href');
							$('.page-wrapper').prepend( '<link rel="stylesheet" id="font-awesome-css" href="'+font_awesome+'" type="text/css" media="screen">' );
						};

						$('body').attr('class', $data.find('#body-classes').attr('class'));
						setTimeout(function() {
							jQuery('#vh_loading_effect').addClass( 'hide' ).delay(500).queue(function(next){
								jQuery(this).hide();
								next();
							});
							$(document).trigger('blogpost:ajaxPageLoad');

							jQuery(".main-inner").resize(function() {
								ResizeContentElements();
							});

							if ( jQuery('.rev_slider_wrapper .rev_slider').length ) {
								jQuery('.rev_slider_wrapper .rev_slider').revolution({
									startheight:600,
									navigationArrows:"none"
								});
								jQuery('.rev_slider_wrapper .rev_slider').show();
							};
						}, 300);
						jQuery(".nano").nanoScroller({ scroll: 'top' });
						setTimeout(function() {
							jQuery('body').removeClass('disable-animations');
							jQuery('#vh_loading_effect').addClass( 'hide' ).delay(500).queue(function(next){
								jQuery(this).hide();
								next();
							});
						}, 700);

						scrollPageToTop();
					}
				}
			});
		}

		// Function: RegExp: Check if url external
		function isExternal(url) {
			var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
			if (typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) return true;
			if (typeof match[2] === "string" && match[2].length > 0 && match[2].replace(new RegExp(":("+{"http:":80,"https:":443}[location.protocol]+")?$"), "") !== location.host) return true;
			return false;
		}

	}

	jQuery(document).ready( ajaxLoadPages );

	function BlogMasonry() {
		jQuery('.wpb_thumbnails-posts').isotope({
			layoutMode: 'masonry',
			itemSelector: '.blog-inner-container',
			percentPosition: true,
			gutter: 0,
			columnWidth: '.grid-sizer',
			transitionDuration: 0
		});
	}
	$(document).on( 'blogpost:ajaxPageLoad', BlogMasonry );
	$(document).on( 'blogpost:ajaxBlogLoad', BlogMasonry );

	function ResizeContentElements() {
		if ( jQuery.cookie('vh_menu_state') == '1' && ( jQuery.cookie('vh_sidebar_state') == '1' && jQuery('.sidebar-right').length && !jQuery('body').hasClass('reading-mode') ) ) {
			jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('menu-active sidebar-active');
		} else if ( jQuery.cookie('vh_menu_state') == '1' && ( jQuery.cookie('vh_sidebar_state') == '0' || !jQuery('.sidebar-right').length && !jQuery('body').hasClass('reading-mode') ) ) {
			jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('menu-active');
			jQuery('header.header, .page-wrapper, .side-menu-container, body').removeClass('sidebar-active');
		} else if ( jQuery.cookie('vh_menu_state') == '0' && ( jQuery.cookie('vh_sidebar_state') == '1' && jQuery('.sidebar-right').length && !jQuery('body').hasClass('reading-mode') ) ) {
			jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('sidebar-active');
			jQuery('header.header, .page-wrapper, .side-menu-container, body').removeClass('menu-active');
		}

		jQuery(window).bind("debouncedresize", function() {
			if ( jQuery('.page-wrapper').width() < 900 ) {
				jQuery('#author-description').addClass('full-width');
			} else {
				jQuery('#author-description').removeClass('full-width');
			}
		});

		if ( jQuery('.wpb_accordion_wrapper.ui-accordion').length ) {
			jQuery('.wpb_accordion_wrapper.ui-accordion .wpb_accordion_section').first().addClass('active');
		};

		if ( jQuery('.open-post-image-carousel').length ) {
			var image_width = jQuery('.page-wrapper').width();
			jQuery('.open-post-carousel-container img').css('width', image_width+'px');
			jQuery('.open-post-image-carousel').jcarousel({
				animation: {
					duration: 0
				}
			});

			jQuery(window).bind("debouncedresize", function() {
				var image_width = jQuery('.page-wrapper').width();
				jQuery('.open-post-carousel-container img').css('width', image_width+'px');
			});
		};

		if ( jQuery('.post-image-carousel').length ) {
			var image_width = jQuery('.blog-inner-container').width();
			jQuery('.post-carousel-container img').css('width', image_width+'px');
			jQuery('.post-image-carousel').jcarousel({
				animation: {
					duration: 0
				}
			});
		};

		if ( jQuery('body').hasClass('.admin-bar') ) {
			jQuery('.page-wrapper').height(jQuery(window).height()-32);
		} else {
			jQuery('.page-wrapper').height(jQuery(window).height());
		}
		if ( jQuery('.nano-content').length ) {
			jQuery('.nano').nanoScroller();
		};
		jQuery('.wpb_thumbnails-posts').isotope( 'layout' );

		if ( !jQuery('body').hasClass('sidebar-active') ) {
			jQuery('header.header, .page-wrapper, .side-menu-container').removeClass('sidebar-active');
		};
		if ( !jQuery('body').hasClass('menu-active') ) {
			jQuery('header.header, .page-wrapper, .side-menu-container').removeClass('menu-active');
		};
	}
	$(document).on( 'blogpost:ajaxPageLoad', ResizeContentElements );

})(jQuery);

/*
Plugin: jQuery Parallax
Version 1.1.3
Author: Ian Lunn
Twitter: @IanLunn
Author URL: http://www.ianlunn.co.uk/
Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

jQuery(document).ready(function($) {
	"use strict";

	jQuery(".main-inner").resize(function() {
		ResizeElements();
	});

	if ( jQuery('#buy-now-ribbon').length && window.self === window.top ) {
		jQuery('#buy-now-ribbon').show();
	};

	if ( jQuery('body').hasClass('.admin-bar') ) {
		jQuery('.page-wrapper').height(jQuery(window).height()-32);
	} else {
		jQuery('.page-wrapper').height(jQuery(window).height());
	}
	if ( jQuery('.nano-content').length ) {
		jQuery('.nano').nanoScroller();
	};

	function ResizeElements() {
		if ( jQuery('.post-image-carousel').length ) {
			var image_width = jQuery('.blog-inner-container').width();
			jQuery('.post-carousel-container img').css('width', image_width+'px');
			jQuery('.post-image-carousel').jcarousel({
				animation: {
					duration: 0
				}
			});

			jQuery(window).bind("debouncedresize", function() {
				var image_width = jQuery('.blog-inner-container').width();
				jQuery('.post-carousel-container img').css('width', image_width+'px');
			});
		};

		if ( jQuery('.open-post-image-carousel').length ) {
			jQuery(window).bind("debouncedresize", function() {
				var image_width = jQuery('.page-wrapper').width();
				jQuery('.open-post-carousel-container img').css('width', image_width+'px');
			});
		};

		if ( !jQuery('.page-wrapper').hasClass('menu-active') && !jQuery('.page-wrapper').hasClass('sidebar-active') ) {
			var new_width = jQuery('.open-post-image-carousel').width();
			jQuery('.open-post-image-carousel').jcarousel().jcarousel("scroll", '0');
			jQuery('.post-gallery-controls .carousel-bullet').removeClass('active');
			jQuery('.post-gallery-controls .carousel-bullet').first().addClass('active');
			jQuery('.open-post-carousel-container img').stop().animate({
				width: new_width+'px'
			}, 150, 'linear');
		} else if ( jQuery('.page-wrapper').hasClass('menu-active') && jQuery('.page-wrapper').hasClass('sidebar-active') ) {
			var new_width = jQuery('.open-post-image-carousel').width();
			jQuery('.open-post-image-carousel').jcarousel().jcarousel("scroll", '0');
			jQuery('.post-gallery-controls .carousel-bullet').removeClass('active');
			jQuery('.post-gallery-controls .carousel-bullet').first().addClass('active');
			jQuery('.open-post-carousel-container img').stop().animate({
				width: new_width+'px'
			}, 300, 'linear');
		} else if ( jQuery('.page-wrapper').hasClass('menu-active') && !jQuery('.page-wrapper').hasClass('sidebar-active') ) {
			var new_width = jQuery('.open-post-image-carousel').width();
			jQuery('.open-post-image-carousel').jcarousel().jcarousel("scroll", '0');
			jQuery('.post-gallery-controls .carousel-bullet').removeClass('active');
			jQuery('.post-gallery-controls .carousel-bullet').first().addClass('active');
			jQuery('.open-post-carousel-container img').stop().animate({
				width: new_width+'px'
			}, 200, 'linear');
		} else if ( !jQuery('.page-wrapper').hasClass('menu-active') && jQuery('.page-wrapper').hasClass('sidebar-active') ) {
			var new_width = jQuery('.open-post-image-carousel').width();
			jQuery('.open-post-image-carousel').jcarousel().jcarousel("scroll", '0');
			jQuery('.post-gallery-controls .carousel-bullet').removeClass('active');
			jQuery('.post-gallery-controls .carousel-bullet').first().addClass('active');
			jQuery('.open-post-carousel-container img').stop().animate({
				width: new_width+'px'
			}, 150, 'linear');
		}

		if ( jQuery('body').hasClass('.admin-bar') ) {
			jQuery('.page-wrapper').height(jQuery(window).height()-32);
		} else {
			jQuery('.page-wrapper').height(jQuery(window).height());
		}
		
		if ( jQuery('.nano-content').length ) {
			jQuery('.nano').nanoScroller();
		};


		jQuery('.wpb_thumbnails-posts').isotope().isotope( 'layout' );
	}
	// $(document).on( 'blogpost:resizeElements', ResizeElements );
	// $(document).on( 'blogpost:ajaxBlogLoad', ResizeElements );

	function get_next_blog_page(next_page) {
		var current_url = window.location.origin+window.location.pathname;
		var get_params = window.location.search.substring(1);

		if ( get_params == '' ) {
			current_url = current_url+'?paged='+next_page;
		} else if ( get_params.indexOf('paged') == -1 ) {
			current_url = current_url+'?'+get_params+'&paged='+next_page;
		};

		return current_url;
	}

	function ajaxLoadBlog(href, pagination) {

		var pagination_split = pagination.split('|');
		if ( parseInt(pagination_split['0']) == parseInt(pagination_split['1']) ) {
			jQuery('.main-inner .loading-effect').hide();
			jQuery('.main-inner #load-more-posts').css('opacity', '0');
			jQuery('.main-inner .no-more-posts').show();
			return;
		} else if ( parseInt(pagination_split['0']) < parseInt(pagination_split['1']) ) {
			var next_page = parseInt(pagination_split['0'])+1;
			var load_next_href = get_next_blog_page(next_page);
		};

		push_state(load_next_href, pagination);

		// Function: PushState and trigger ajax loader
		function push_state(href, pagination){
			// history.pushState({page: href}, '', href);
			ajaxLoadBlogPosts(href, pagination);
		}

		// Function: Ajax Load Page
		function ajaxLoadBlogPosts(href, pagination) {

			$('body').removeClass('ajax-main-content-loading-end ajax-content-wrapper-loading-end');
			if ( !$('.page-wrapper').hasClass('menu-active') ) {
				$('#vh_loading_effect').addClass('full');
			};
			
			jQuery('.main-inner .loading-effect').css('opacity', '1');
			jQuery('.main-inner #load-more-posts').css('opacity', '0');

			var timeStarted = 0;

			timeStarted = new Date().getTime();

			blogpost.xhr = $.ajax({
				type: "GET",
				url: href,
				success: function(data, response, xhr){

					// Check if css animation had time to finish
					// before new page load animation starts
					var now = new Date().getTime();
					var timeDiff = now - timeStarted;
					if( timeDiff < 1000 ) {
						setTimeout( ajaxLoadPageCallback, (1000-timeDiff) );
					}else{
						ajaxLoadPageCallback();
					}

					function ajaxLoadPageCallback(){
						var $data = $(data);

						// Update Page Title in browser window
						var pageTitle = $data.filter('title').text();
						document.title = pageTitle;

						console.log('Page loaded in: '+timeDiff+'ms');

						var elems = $data.find('#main-teaser-grid-container li');

						$('.wpb_thumbnails-posts').append( elems.addClass('added') ).isotope( 'appended', $(elems) );
						
						setTimeout(function() {
							elems.removeClass('added');
							jQuery('body').removeClass('blog-loading-disabled');
						}, 1000);

						setTimeout(function() {
						}, 1500)

						$('body').attr('class', $data.find('#body-classes').attr('class'));
						setTimeout(function() {
							$(document).trigger('blogpost:ajaxBlogLoad');
						}, 300);
						jQuery('.main-inner .loading-effect').css('opacity', '0');
						jQuery('.main-inner #load-more-posts').css('opacity', '1');
					}
				}
			});
		}

	}

	jQuery(".nano").bind("scrollend", function(e){

		if ( jQuery(window).width() > 767 ) {
			if ( jQuery('.wpb_thumbnails-posts').length && !jQuery('.wpb_thumbnails-posts').hasClass('module') && !jQuery('body').hasClass('blog-loading-disabled') ) {
				var pagination = jQuery('#blog-page-pagination').val();
				var href = jQuery('#blog-page-href').val();
				ajaxLoadBlog(href, pagination);
				jQuery('body').addClass('blog-loading-disabled');

				var pagination_split = pagination.split('|');
				if ( parseInt(pagination_split['0']) == parseInt(pagination_split['1']) ) {
					return;
				} else if ( parseInt(pagination_split['0']) < parseInt(pagination_split['1']) ) {
					var next_page = parseInt(pagination_split['0'])+1;
					jQuery('#blog-page-pagination').val(next_page+'|'+pagination_split['1']);
				};
			}

			if ( jQuery('.wpb_thumbnails-posts').length && jQuery('.wpb_thumbnails-posts').hasClass('module') && !jQuery('body').hasClass('blog-loading-disabled') ) {
				var initial = jQuery('#ajax-posts-initial').val();
				var next = jQuery('#ajax-posts-next').val();
				var categories = jQuery('#ajax-posts-categories').val();
				var paged = jQuery('#ajax-posts-pagination').val();
				var fav = jQuery('#ajax-posts-favorite').val();

				if ( paged == 'max' ) {
					jQuery('.main-inner .loading-effect').hide();
					jQuery('.main-inner .no-more-posts').show();
					return;
				};

				jQuery('body').addClass('blog-loading-disabled');
				jQuery('.main-inner .loading-effect').css('opacity', '1');

				jQuery.ajax({
					type: 'POST',
					url: my_ajax.ajaxurl,
					data: {"action": "ajax-blog-posts", initial_posts: initial, next_posts: next, post_categories: categories, post_paged: paged, favorite: fav},
					success: function(response) {

						var parsed_data = jQuery.parseJSON(response);

						jQuery('.main-inner .loading-effect').css('opacity', '0');

						var elems = $(parsed_data.new_posts);
						jQuery('.wpb_thumbnails-posts.module').append( elems.addClass('added') ).isotope( 'appended', elems );

						setTimeout(function() {
							elems.removeClass('added');
							jQuery('.wpb_thumbnails-posts').isotope( 'layout' );
							jQuery('body').removeClass('blog-loading-disabled');
						}, 1000);

						setTimeout(function() {
							jQuery('.wpb_thumbnails-posts').isotope( 'layout' );
						}, 1500);

						if ( parsed_data.pagination > parseInt(paged)+parseInt(next) ) {
							jQuery('#ajax-posts-pagination').val( parseInt(paged)+parseInt(next) );
						} else if ( parsed_data.pagination == paged ) {
							jQuery('#ajax-posts-pagination').val('max');
						} else {
							jQuery('#ajax-posts-pagination').val('max');
						}

						return false;
					}
				});
			}
		}
	});

	jQuery(document).on('click', '#load-more-posts', function() {
		if ( jQuery('.wpb_thumbnails-posts').length && !jQuery('.wpb_thumbnails-posts').hasClass('module') && !jQuery('body').hasClass('blog-loading-disabled') ) {
			var pagination = jQuery('#blog-page-pagination').val();
			var href = jQuery('#blog-page-href').val();
			ajaxLoadBlog(href, pagination);
			jQuery('body').addClass('blog-loading-disabled');

			var pagination_split = pagination.split('|');
			if ( parseInt(pagination_split['0']) == parseInt(pagination_split['1']) ) {
				return;
			} else if ( parseInt(pagination_split['0']) < parseInt(pagination_split['1']) ) {
				var next_page = parseInt(pagination_split['0'])+1;
				jQuery('#blog-page-pagination').val(next_page+'|'+pagination_split['1']);
			};
		}

		if ( jQuery('.wpb_thumbnails-posts').length && jQuery('.wpb_thumbnails-posts').hasClass('module') && !jQuery('body').hasClass('blog-loading-disabled') ) {
			var initial = jQuery('#ajax-posts-initial').val();
			var next = jQuery('#ajax-posts-next').val();
			var categories = jQuery('#ajax-posts-categories').val();
			var paged = jQuery('#ajax-posts-pagination').val();

			if ( paged == 'max' ) {
				jQuery('.main-inner #load-more-posts').css('opacity', '0');
				jQuery('.main-inner .loading-effect').hide();
				jQuery('.main-inner .no-more-posts').show();
				return;
			};

			jQuery('body').addClass('blog-loading-disabled');
			jQuery('.main-inner .loading-effect').css('opacity', '1');
			jQuery('.main-inner #load-more-posts').css('opacity', '0');

			jQuery.ajax({
				type: 'POST',
				url: my_ajax.ajaxurl,
				data: {"action": "ajax-blog-posts", initial_posts: initial, next_posts: next, post_categories: categories, post_paged: paged},
				success: function(response) {

					var parsed_data = jQuery.parseJSON(response);

					jQuery('.main-inner .loading-effect').css('opacity', '0');
					jQuery('.main-inner #load-more-posts').css('opacity', '1');

					var elems = $(parsed_data.new_posts);
					jQuery('.wpb_thumbnails-posts.module').append( elems.addClass('added') ).isotope( 'appended', elems );

					setTimeout(function() {
						elems.removeClass('added');
						jQuery('.wpb_thumbnails-posts').isotope( 'layout' );
						jQuery('body').removeClass('blog-loading-disabled');
					}, 1000);

					setTimeout(function() {
						jQuery('.wpb_thumbnails-posts').isotope( 'layout' );
					}, 1500);

					if ( parsed_data.pagination > parseInt(paged)+parseInt(next) ) {
						jQuery('#ajax-posts-pagination').val( parseInt(paged)+parseInt(next) );
					} else if ( parsed_data.pagination == paged ) {
						jQuery('#ajax-posts-pagination').val('max');
					} else {
						jQuery('#ajax-posts-pagination').val('max');
					}

					return false;
				}
			});
		}
	});

	jQuery(document).on('click', '.favorite-article:not(.disabled)', function() {
		jQuery(this).attr('data-id');
		jQuery(this).addClass('disabled');
		var current_article = jQuery(this);

		jQuery.ajax({
			type: 'POST',
			url: my_ajax.ajaxurl,
			data: {"action": "ajax_favorite_post", fpost_id: jQuery(this).attr('data-id'), fav_action: jQuery(this).attr('data-favorite')},
			success: function(response) {
				current_article.toggleClass('icon-heart-filled');
				current_article.toggleClass('icon-heart-empty');
				current_article.removeClass('disabled');
				return false;
			}
		});
	});

	jQuery(document).on('click', '.article-back', function() {
		history.back();
	});

	// var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 400, easingIn : mina.easeinout } );
	// loader.show();

	// var $isotope_container = jQuery(".blog .wpb_thumbnails");

	// $isotope_container.isotope({ straightAcross : true });

	// // update columnWidth on window resize
	// jQuery(window).bind("debouncedresize", function() {
	// 	$isotope_container.isotope({

	// 		// update columnWidth to a percentage of container width
	// 		masonry: { columnWidth: $isotope_container.width() / 2 }
	// 	});

	// 	if ( jQuery(window).width() <= 767 ) {
	// 		jQuery(".video-module-title").each(function(i, val) { console.log(jQuery(this).val());
	// 			if (jQuery(this).val() == '&nbsp;') {
	// 				jQuery(this).hide();
	// 			}
	// 		});
	// 	}
		
	// });

	jQuery('.entry-content .ad-close').on( 'click', function() {
	  var element = jQuery(this).parent().parent().parent();
	  jQuery('.wpb_thumbnails-posts').isotope( 'remove', element ).isotope('layout');
	});

	jQuery(".scroll-to-top").click(function() {
		jQuery(".nano").nanoScroller({ scroll: 'top' });
		return false;
	});

	jQuery(window).bind("debouncedresize", function() {
		// jQuery('.wpb_thumbnails').isotope();
		if ( jQuery('.rev_slider_wrapper .rev_slider').length ) {
			jQuery('.rev_slider_wrapper .rev_slider').revredraw();
		};
		jQuery('.wpb_thumbnails-posts').isotope( 'layout' );
	});

	jQuery(document.body).on('click', '.wpb_accordion_section.group', function() {
		jQuery('.wpb_accordion_section').removeClass('active');
		jQuery(this).addClass('active');
	});

	// jQuery('body .wrapper .wpb_alert').fadeIn('500', function() {
	// 	setTimeout( function() {
	// 		jQuery('body .wrapper .wpb_alert').fadeOut();
	// 	}, 4000 );
	// });

	jQuery('.header-menu-button').click(function() {
		if (jQuery.cookie('vh_menu_state') == '1' ) {
			jQuery.cookie('vh_menu_state', '0', { path: '/' });
		} else {
			jQuery.cookie('vh_menu_state', '1', { path: '/' });
		}

		if ( jQuery('.rev_slider_wrapper .rev_slider').length ) {
			setTimeout(function() {
				jQuery('.rev_slider_wrapper .rev_slider').revredraw();
			}, 300);
		};

		if ( jQuery(window).width() > 767 ) {
			jQuery('header.header, .page-wrapper, .side-menu-container, body').toggleClass('menu-active');
		} else {
			jQuery('.side-menu-container').toggleClass('mobile-menu');
			jQuery('#mobile-menu-overlay').toggleClass('active');
		}
	});

	jQuery('#mobile-menu-icon').click(function() {
		jQuery('.side-menu-container').toggleClass('mobile-menu');
		jQuery('#mobile-menu-overlay').toggleClass('active');
	});

	if ( jQuery('.forcefullwidth_wrapper_tp_banner').length ) {
		jQuery('.forcefullwidth_wrapper_tp_banner').css('margin-top', '-91px')
	};

	jQuery(document.body).on('click', '.header-sidebar-button', function() {
		if (jQuery.cookie('vh_sidebar_state') == '1' ) {
			jQuery.cookie('vh_sidebar_state', '0', { path: '/' });
		} else {
			jQuery.cookie('vh_sidebar_state', '1', { path: '/' });
		}

		jQuery('header.header, .page-wrapper, .side-menu-container, body').toggleClass('sidebar-active');
		if ( !jQuery(this).hasClass('sidebar') ) {
			jQuery('.header-sidebar-button').show();
			jQuery(this).hide();

			jQuery('.nano .nano-pane').addClass('hide');
			setTimeout(function() {
				jQuery('.nano .nano-pane').removeClass('hide');
				if ( jQuery('.rev_slider_wrapper .rev_slider').length ) {
					jQuery('.rev_slider_wrapper .rev_slider').revredraw();
				};
			}, 300);
		} else {
			setTimeout(function() {
				jQuery('.header-sidebar-button').show();
			}, 200);

			jQuery('.nano .nano-pane').addClass('hide');
			setTimeout(function() {
				jQuery('.nano .nano-pane').removeClass('hide');
				if ( jQuery('.rev_slider_wrapper .rev_slider').length ) {
					jQuery('.rev_slider_wrapper .rev_slider').revredraw();
				};
			}, 300);
		}
	});

	if ( jQuery('.page-wrapper').hasClass('sidebar-active') ) {
		jQuery('.header-sidebar-button:not(sidebar)').hide();
	};

	if (jQuery.cookie('vh_sidebar_state') == null) {
		jQuery.cookie('vh_sidebar_state', '1', { path: '/' });
	};

	if (jQuery.cookie('vh_menu_state') == null) {
		jQuery.cookie('vh_menu_state', '1', { path: '/' });
	};

	jQuery(document.body).on('click', '.header-search-button', function() {	
		if ( jQuery(this).parent().hasClass('active') ) {
			if ( jQuery(this).parent().find('.footer_search_input').val() == '' ) {
				var current_parrent = jQuery(this).parent();
				var current_button = jQuery(this);

				current_parrent.find('.footer_search_input').animate({
					width: "36"
				}, 200, function() {
					current_parrent.removeClass('active');
				});
				setTimeout(function() {
					current_parrent.find('.footer_search_input').animate({
						opacity: "0"
					}, 150);
				}, 50);
			} else {
				jQuery(this).parent().find('button').click();
			}
		} else {
			jQuery(this).parent().find('.footer_search_input').css('opacity', '1');
			jQuery(this).parent().find('.footer_search_input').animate({
				width: "190"
			}, 200);
		}
		jQuery(this).parent().addClass('active');
	});

	jQuery(document.body).on('click', '.header-share-button', function() {
		if ( jQuery(this).hasClass('active') ) {
			jQuery(this).parent().find('.header-social-icon').fadeOut(100, function() {
				jQuery(this).parent().find('.header-social-icon').css({'left': '0', 'opacity': '0'});
			});
		} else {
			if ( jQuery('.header-button-container .icon-count-1').length ) {
				jQuery(this).parent().find('.icon-count-1').show().animate({
					left: "80px",
					opacity: "0.6"
				}, {
					duration: '300', // how fast we are animating
					easing: 'swing', // the type of easing
					complete: function() { // the callback

					}
				});
			};

			if ( jQuery('.header-button-container .icon-count-2').length ) {
				var button2 = jQuery(this).parent().find('.icon-count-2');
				setTimeout( function() {
					button2.show().animate({
						left: "160px",
						opacity: "0.6"
					}, {
						duration: '500', // how fast we are animating
						easing: 'swing', // the type of easing
						complete: function() { // the callback

						}
					});
				}, 150 );
			};

			if ( jQuery('.header-button-container .icon-count-3').length ) {
				var button3 = jQuery(this).parent().find('.icon-count-3');
				setTimeout( function() {
					button3.show().animate({
						left: "240px",
						opacity: "0.6"
					}, {
						duration: '700', // how fast we are animating
						easing: 'swing', // the type of easing
						complete: function() { // the callback

						}
					});
				}, 300 );
			};

			if ( jQuery('.header-button-container .icon-count-4').length ) {
				var button4 = jQuery(this).parent().find('.icon-count-4');
				setTimeout( function() {
					button4.show().animate({
						left: "320px",
						opacity: "0.6"
					}, {
						duration: '900', // how fast we are animating
						easing: 'swing', // the type of easing
						complete: function() { // the callback

						}
					});
				}, 450 );
			};
		}
		jQuery(this).toggleClass('active');
	});

	if ( !jQuery('.header-button-container .icon-count-1').length ) {
		jQuery('.header-share-button').hide();
	};

	// if ( jQuery('.wpb_thumbnails-posts li').length > 5 ) {
	// 	var post_count = jQuery('.wpb_thumbnails-posts li').length;
	// 	var random_number = Math.floor((Math.random() * post_count) + 2);
	// 	jQuery('.teaser_grid_container ul').find(':nth-child('+random_number+')').addClass('wide');
	// };

	jQuery(document.body).on('click', '.header-menu li a', function() {
		 if ( !jQuery(this).parent().parent().hasClass('sub-menu') ) {
		 	if ( !jQuery(this).parent().hasClass('active') ) {
		 		jQuery('.header-menu li').removeClass('current-menu-item active');
		 	} else {
		 		jQuery('.header-menu li').removeClass('current-menu-item');
		 	}
		} else {
			jQuery('.header-menu li:not(.menu-item-has-children)').removeClass('current-menu-item');
		}
		jQuery(this).parent().addClass('current-menu-item');
	});

	jQuery(document).on('click', '.side-menu-container .logo a', function() {
		jQuery('.header-menu li').removeClass('current-menu-item active');
	});

	jQuery(document).on('click', '.header-menu .menu-item', function() {
		jQuery('.header-menu .menu-item .sub-menu').stop().slideUp(200);
		jQuery(this).find('.sub-menu').stop().slideDown(200);
		
		jQuery(this).removeClass('active');
	});

	// jQuery('.header-menu .menu-item').on('click', function() {
	// 	jQuery('.header-menu .menu-item .sub-menu').slideUp(200);
	// 	jQuery(this).find('.sub-menu').slideDown(200);
		
	// 	jQuery(this).removeClass('active');
	// });

	jQuery(document).on('click', '.post-gallery-controls .carousel-bullet', function() {
		console.log('test');
		jQuery(this).parent().find('.carousel-bullet').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parent().parent().find('.post-image-carousel, .open-post-image-carousel').jcarousel("scroll", jQuery(this).index());
		jQuery(this).parent().parent().find('.post-image-carousel, .open-post-image-carousel').css('opacity', '0').stop().animate({
			opacity: "1"
		}, 500);
	});

	if ( jQuery('body').scrollTop() < 150 ) {
		jQuery('.scroll-to-top').hide();
	};

	// Comment form validation
	// jQuery(document).on('click', '#commentform #submit', function(e) {
	// 	jQuery('.comment-form-error').hide();
	// 	var error_count = 0;

	// 	if ( jQuery('#commentform #author').length ) {
	// 		if ( jQuery('#commentform #author').val() == '' || jQuery('#commentform #author').val().length < 3 ) {
	// 			jQuery('.comment-form-author').find('.comment-form-error').fadeIn();
	// 			error_count++;
	// 		};
	// 	};

	// 	if ( jQuery('#commentform #email').length ) {
	// 		if ( jQuery('#commentform #email').val() == '' || jQuery('#commentform #email').val().indexOf('@') == -1 || jQuery('#commentform #email').val().indexOf('.') == -1 || jQuery('#commentform #email').val().length < 5 ) {
	// 			jQuery('.comment-form-email').find('.comment-form-error').fadeIn();
	// 			error_count++;
	// 		};
	// 	};

	// 	if ( jQuery('#commentform #comment').length ) {
	// 		if ( jQuery('#commentform #comment').val() == '' || jQuery('#commentform #comment').val().length < 10 ) {
	// 			jQuery('.comment-form-comment').find('.comment-form-error').fadeIn();
	// 			error_count++;
	// 		};
	// 	};

	// 	if ( error_count != 0 ) {
	// 		e.preventDefault();
	// 	}
	// });

	jQuery(".nano").on("update", function(event, vals) {
		if ( vals.position > 150 ) {
			jQuery('.top-header .logo').stop().fadeOut();
			jQuery('.scroll-to-top').stop().fadeIn();
		} else {
			jQuery('.top-header .logo').stop().fadeIn();
			jQuery('.scroll-to-top').stop().fadeOut();
		}
	});

	var element, circle, d, x, y;
	jQuery(document).on('click', '.wpb_button', function(e) {
		element = $(this).find('span');

		if ( !element.length ) {
			return false;
		};

		if(element.find(".circle").length == 0)
			element.prepend("<span class='circle'></span>");
			
		circle = element.find(".circle");
		circle.removeClass("animate");
		
		if(!circle.height() && !circle.width()) {
			d = Math.max(element.outerWidth(), element.outerHeight());
			circle.css({height: d, width: d});
		}
		
		x = e.pageX - element.offset().left - circle.width()/2;
		y = e.pageY - element.offset().top - circle.height()/2;
		
		circle.css({top: y+'px', left: x+'px'}).addClass("animate");
	});

	jQuery(document).on('click', '.header-reading-button', function() {
		jQuery('body').toggleClass('reading-mode');
		if ( !jQuery('body').hasClass('reading-mode') ) {
			if (jQuery.cookie('vh_menu_state') == '1' ) {
				jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('menu-active');
			}

			if (jQuery.cookie('vh_sidebar_state') == '1' ) {
				jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('sidebar-active');
			}
		} else {
			jQuery('header.header, .page-wrapper, .side-menu-container, body').removeClass('sidebar-active menu-active');
		}
		jQuery('.header-sidebar-button:not(.sidebar)').show();
	});

	if ( jQuery(window).width() >= 767 ) {
		jQuery("a.menu-trigger").click(function() {
			jQuery(".mp-menu").css({top: jQuery(document).scrollTop() });

			return false;
		});
	}

	jQuery(".fixed_menu .social-container").css({ 'top' : (jQuery(window).height()) - ( jQuery(".fixed_menu .social-container").height() + 60 ) });

	jQuery(".gallery-icon a").attr('rel', 'prettyphoto');

	// jQuery("a[rel^='prettyPhoto']").prettyPhoto();

	// Opacity hover effect
	jQuery(".opacity_hover").mouseenter(function() {
		var social = this;
		jQuery(social).animate({ opacity: "0.8" }, 80, function() {
			jQuery(social).animate({ opacity: "1.0" }, 80);
		});
	});

	var $window = $(window);
	var windowHeight = $window.height();

	$window.resize(function () {
		windowHeight = $window.height();
		jQuery(".fixed_menu .social-container").css({ 'top' : (jQuery(window).height()) - ( jQuery(".fixed_menu .social-container").height() + 60 ) });
	});

	/**
	 * jQuery.LocalScroll - Animated scrolling navigation, using anchors.
	 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogpost.com
	 * Dual licensed under MIT and GPL.
	 * Date: 3/11/2009
	 * @author Ariel Flesler
	 * @version 1.2.7
	 **/
	;(function($){var l=location.href.replace(/#.*/,'');var g=$.localScroll=function(a){$('body').localScroll(a)};g.defaults={duration:1e3,axis:'y',event:'click',stop:true,target:window,reset:true};g.hash=function(a){if(location.hash){a=$.extend({},g.defaults,a);a.hash=false;if(a.reset){var e=a.duration;delete a.duration;$(a.target).scrollTo(0,a);a.duration=e}i(0,location,a)}};$.fn.localScroll=function(b){b=$.extend({},g.defaults,b);return b.lazy?this.bind(b.event,function(a){var e=$([a.target,a.target.parentNode]).filter(d)[0];if(e)i(a,e,b)}):this.find('a,area').filter(d).bind(b.event,function(a){i(a,this,b)}).end().end();function d(){return!!this.href&&!!this.hash&&this.href.replace(this.hash,'')==l&&(!b.filter||$(this).is(b.filter))}};function i(a,e,b){var d=e.hash.slice(1),f=document.getElementById(d)||document.getElementsByName(d)[0];if(!f)return;if(a)a.preventDefault();var h=$(b.target);if(b.lock&&h.is(':animated')||b.onBefore&&b.onBefore.call(b,a,f,h)===false)return;if(b.stop)h.stop(true);if(b.hash){var j=f.id==d?'id':'name',k=$('<a> </a>').attr(j,d).css({position:'absolute',top:$(window).scrollTop(),left:$(window).scrollLeft()});f[j]='';$('body').prepend(k);location=e.hash;k.remove();f[j]=d}h.scrollTo(f,b).trigger('notify.serialScroll',[f])}})(jQuery);
});

function header_size() {

	jQuery(window).on('touchmove', function(event) {
		set_height();
	});
	var win    = jQuery(window),
	header     = jQuery('.header .top-header'),
	logo       = jQuery('.header .top-header .logo img'),
	elements   = jQuery('.header, .top-header .header-social-icons div a, .top-header .logo, .top-header .header_search, .header_search .search .gray-form .footer_search_input, .top-header .menu-btn.icon-menu-1'),
	el_height  = jQuery(elements).filter(':first').height(),
	isMobile   = 'ontouchstart' in document.documentElement,
	set_height = function() {
		var st = win.scrollTop(), newH = 0;

		if(st < el_height/2) {
			newH = el_height - st;
			header.removeClass('header-small');
		} else {
			newH = el_height/2;
			header.addClass('header-small');
		}

		elements.css({'height': newH + 'px', 'line-height': newH + 'px'});
		logo.css({'max-height': newH + 'px'});
	}

	if(!header.length) {
		return false;
	}

	win.scroll(set_height);
	set_height();
}

// debulked onresize handler

function on_resize(c,t){
	"use strict";

	var onresize=function(){clearTimeout(t);t=setTimeout(c,100);};return c;
}


function clearInput (input, inputValue) {
	"use strict";

	if (input.value === inputValue) {
		input.value = '';
	}
}

jQuery(document).ready(function() {
	"use strict";

	if ( jQuery.cookie('vh_menu_state') == '1' && ( jQuery.cookie('vh_sidebar_state') == '1' && jQuery('.sidebar-right').length ) ) {
		jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('menu-active sidebar-active');
	} else if ( jQuery.cookie('vh_menu_state') == '1' && ( jQuery.cookie('vh_sidebar_state') == '0' || !jQuery('.sidebar-right').length ) ) {
		jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('menu-active');
		jQuery('header.header, .page-wrapper, .side-menu-container, body').removeClass('sidebar-active');
	} else if ( jQuery.cookie('vh_menu_state') == '0' && ( jQuery.cookie('vh_sidebar_state') == '1' && jQuery('.sidebar-right').length ) ) {
		jQuery('header.header, .page-wrapper, .side-menu-container, body').addClass('sidebar-active');
		jQuery('header.header, .page-wrapper, .side-menu-container, body').removeClass('menu-active');
	}

	// Top menu
	if( jQuery(".header .sf-menu").length ) {
		var menuOptions = {
			speed:      'fast',
			speedOut:   'fast',
			hoverClass: 'sfHover',
		}
		// initialise plugin
		var menu = jQuery('.header .sf-menu').superfish(menuOptions);
	}
	// !Top menu

	// Search widget
	jQuery('.search.widget .sb-icon-search').click(function(el){
		el.preventDefault();
		jQuery('.search.widget form').submit();
	});
	// !Seaarch widget

	// Search widget
	jQuery('.search-no-results .main-inner .sb-icon-search').click(function(el){
		el.preventDefault();
		jQuery('.search-no-results .main-inner .search form').submit();
	});
	// !Seaarch widget
	

	// Social icons hover effect
	jQuery(".social_links li a").mouseenter(function() {
		var social = this;
		jQuery(social).animate({ opacity: "0.5" }, 250, function() {
			jQuery(social).animate({ opacity: "1.0" }, 100);
		});
	});
	// !Social icons hover effect

	// Widget contact form - send
	jQuery("#contact_form").submit(function() {
		jQuery("#contact_form").parent().find("#error, #success").hide();
		var str = jQuery(this).serialize();
		jQuery.ajax({
			type: "POST",
			url: my_ajax.ajaxurl,
			data: 'action=contact_form&' + str,
			success: function(msg) {
				if(msg === 'sent') {
					jQuery("#contact_form").parent().find("#success").fadeIn("slow");
				} else {
					jQuery("#contact_form").parent().find("#error").fadeIn("slow");
				}
			}
		});
		return false;
	});
	// !Widget contact form - send

	/* Merge gallery */
	jQuery('.merge-gallery div').mouseenter(function() {
		jQuery(this).find('.gallery-caption').animate({
			bottom: jQuery(this).find('img').height()
		},250);
	}).mouseleave(function() {
		jQuery(this).find('.gallery-caption').animate({
			bottom: jQuery(this).find('img').height() + 150
		},250);
	});
});

jQuery(document).ready(function($){
	var $shareButtons=$(".main.share-button")
		,$toggleButton=$(".main.share-toggle-button")

		,menuOpen=false
		,buttonsNum=$shareButtons.length
		,buttonsMid=(buttonsNum)
		,spacing=45

	jQuery('.share .share-toggle-button').on("mousedown",function(){
		if ( jQuery(this).parent().hasClass('main') ) {
			toggleShareMenu('main');
		} else {
			toggleShareMenu('menu');
		}
		
	});

	function toggleShareMenu(location){
		if ( location == 'main' ) {
			$shareButtons=$(".main.share .share-button")
			,$toggleButton=$(".main.share .share-toggle-button")
			,buttonsNum=$shareButtons.length
			,buttonsMid=(buttonsNum)
		} else {
			$shareButtons=$(".menu.share .share-button")
			,$toggleButton=$(".menu.share .share-toggle-button")
			,buttonsNum=$shareButtons.length
			,buttonsMid=(buttonsNum)
		}

		menuOpen=!menuOpen

		menuOpen?openShareMenu(location):closeShareMenu(location);
	}

	function openShareMenu(location){
		if ( location == 'main' ) {
			jQuery('.main.share').addClass('active');
		} else {
			jQuery('.menu.share').addClass('active');
		}
		
		TweenMax.to($toggleButton,0.1,{
			scaleX:1.2,
			scaleY:0.6,
			ease:Quad.easeOut,
			onComplete:function(){
				TweenMax.to($toggleButton,.8,{
					scale:1,
					ease:Elastic.easeOut,
					easeParams:[1.1,0.6]
				})
				TweenMax.to($toggleButton.children(".share-icon"),.8,{
					scale:1.4,
					ease:Elastic.easeOut,
					easeParams:[1.1,0.6]
				})
			}
		})
		$shareButtons.each(function(i){
			var $cur=$(this);
			var pos=i-buttonsMid;
			if(pos>=0) pos+=1;
			var dist=Math.abs(pos);
			$cur.css({
				zIndex:buttonsMid-dist
			});
			TweenMax.to($cur,0.5*(dist),{
				x:Math.abs(pos*spacing),
				scaleY:0.6,
				scaleX:0.8,
				ease:Elastic.easeOut,
				easeParams:[0.25,0.3]
			});
			TweenMax.to($cur,.8,{
				delay:(0.1*(dist))-0.1,
				scale:0.6,
				ease:Elastic.easeOut,
				easeParams:[1.1,0.6]
			})
				
			TweenMax.fromTo($cur.children(".share-icon"),0.2,{
				scale:0
			},{
				delay:(0.2*dist)-0.1,
				scale:1,
				ease:Quad.easeInOut
			})
		})
	}
	function closeShareMenu(location){
		TweenMax.to([$toggleButton,$toggleButton.children(".share-icon")],1.4,{
			delay:0.1,
			scale:1,
			ease:Elastic.easeOut,
			easeParams:[1.1,0.3]
		});
		$shareButtons.each(function(i){
			var $cur=$(this);
			var pos=i-buttonsMid;
			if(pos>=0) pos+=1;
			var dist=Math.abs(pos);
			$cur.css({
				zIndex:dist
			});

			TweenMax.to($cur,0.4+((buttonsMid-dist)*0.1),{
				x:0,
				scale:.7,
				ease:Quad.easeInOut,
				onComplete:function(){
					if ( location == 'main' ) {
						jQuery('.main.share').removeClass('active');
					} else {
						jQuery('.menu.share').removeClass('active');
					}
				}
			});
				
			TweenMax.to($cur.children(".share-icon"),0.2,{
				scale:0,
				ease:Quad.easeIn
			});
		})
	}

	jQuery('.wpcf7-form-control-wrap input, .wpcf7-form-control-wrap textarea').focus(function() {
		if ( jQuery(this).parent().parent().hasClass('input-unfocused') ) {
			jQuery(this).parent().parent().removeClass('input-unfocused');
		};
		jQuery(this).parent().parent().addClass('input-focused');
	});

	jQuery('.wpcf7-form-control-wrap input, .wpcf7-form-control-wrap textarea').blur(function() {
		if ( jQuery(this).val() == '' ) {
			jQuery(this).parent().parent().removeClass('input-focused');
			jQuery(this).parent().parent().addClass('input-unfocused');
		} else {
			jQuery(this).parent().parent().addClass('input-focused');
		}
	});
});