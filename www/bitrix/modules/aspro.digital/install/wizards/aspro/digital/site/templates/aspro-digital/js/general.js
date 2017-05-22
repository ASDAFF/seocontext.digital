getRandomInt = function(min, max){
	return Math.floor(Math.random() * (max - min)) + min;
}

ShowOverlay = function(){
	$('<div class="jqmOverlay waiting"></div>').appendTo('body');
}

HideOverlay = function(){
	$('.jqmOverlay').detach();
}

CheckTopMenuDotted = function(){
	var menu = $('nav.mega-menu.sliced');
	//return;

	if(menu.length)
	{
		menu.each(function(){
			var menuMoreItem = $(this).find('td.js-dropdown');
			if($(this).parents('.collapse').css('display') == 'none'){
				return false;
			}

			var block_w = $(this).closest('div').actual('width');
			var	menu_w = $(this).find('table').actual('outerWidth');
			var afterHide = false;

			while(menu_w > block_w) {
				menuItemOldSave = $(this).find('td').not('.nosave').last();
				if(menuItemOldSave.length){
					menuMoreItem.show();
					menuItemNewSave = '<li class="' + (menuItemOldSave.hasClass('dropdown') ? 'dropdown-submenu ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + menu_w + '">' + menuItemOldSave.find('.wrap').html() + '</li>';
					menuItemOldSave.remove();
					menuMoreItem.find('> .wrap > .dropdown-menu').prepend(menuItemNewSave);
					menu_w = $(this).find('table').actual('outerWidth');
					afterHide = true;
				}
				//menu.find('.nosave').css('display', 'table-cell');
				else{
					break;
				}
			}

			if(!afterHide) {
				do {
					var menuItemOldSaveCnt = menuMoreItem.find('.dropdown-menu').find('li').length;
					menuItemOldSave = menuMoreItem.find('.dropdown-menu').find('li').first();
					if(!menuItemOldSave.length) {
						menuMoreItem.hide();
						break;
					}
					else {
						var hideWidth = menuItemOldSave.attr('data-hidewidth');
						if(hideWidth > block_w) {
							break
						}
						else {
							menuItemNewSave = '<td class="' + (menuItemOldSave.hasClass('dropdown-submenu') ? 'dropdown ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + block_w + '"><div class="wrap">' + menuItemOldSave.html() + '</div></td>';
							menuItemOldSave.remove();
							$(menuItemNewSave).insertBefore($(this).find('td.js-dropdown'));
							if(!menuItemOldSaveCnt) {
								menuMoreItem.hide();
								break;
							}
						}
					}
					menu_w = $(this).find('table').actual('outerWidth');
				}
				while(menu_w <= block_w);
			}
			$(this).find('td').css('visibility', 'visible');
			$(this).find('td').removeClass('unvisible');
			markerNav();
		})
	}
	return false;
}

CheckTopVisibleMenu = function(that) {
	var dropdownMenu = $('.dropdown-menu:visible').last();

	if(dropdownMenu.length){
		dropdownMenu.find('a').css('white-space', '');
		dropdownMenu.css('left', '');
		dropdownMenu.css('right', '');
		dropdownMenu.removeClass('toright');

		var dropdownMenu_left = dropdownMenu.offset().left;
		if(typeof(dropdownMenu_left) != 'undefined'){
			var menu = dropdownMenu.parents('.mega-menu');
			if(!menu.length)
				menu = dropdownMenu.closest('.logo-row');
			var menu_width = menu.outerWidth();
			var menu_left = menu.offset().left;
			var menu_right = menu_left + menu_width;
			var isToRight = dropdownMenu.parents('.toright').length > 0;
			var parentsDropdownMenus = dropdownMenu.parents('.dropdown-menu');
			var isHasParentDropdownMenu = parentsDropdownMenus.length > 0;
			if(isHasParentDropdownMenu){
				var parentDropdownMenu_width = parentsDropdownMenus.first().outerWidth();
				var parentDropdownMenu_left = parentsDropdownMenus.first().offset().left;
				var parentDropdownMenu_right = parentDropdownMenu_width + parentDropdownMenu_left;
			}

			if(parentDropdownMenu_right + dropdownMenu.outerWidth() > menu_right){
				dropdownMenu.find('a').css('white-space', 'normal');
			}

			var dropdownMenu_width = dropdownMenu.outerWidth();
			var dropdownMenu_right = dropdownMenu_left + dropdownMenu_width;

			if(dropdownMenu_right > menu_right || isToRight){
				var addleft = 0;
				addleft = menu_right - dropdownMenu_right;
				if(isHasParentDropdownMenu || isToRight){
					dropdownMenu.css('left', 'auto');
					dropdownMenu.css('right', '100%');
					dropdownMenu.addClass('toright');
				}
				else{
					var dropdownMenu_curLeft = parseInt(dropdownMenu.css('left'));
					dropdownMenu.css('left', (dropdownMenu_curLeft + addleft) + 'px');
				}
			}
		}
	}
}

MegaMenuFixed = function(){
	var animationTime = 150;

	$('.logo_and_menu-row .burger').on('click', function(){
		$('.mega_fixed_menu').fadeIn(animationTime);
	});

	$('.mega_fixed_menu .svg.svg-close').on('click', function(){
		$(this).closest('.mega_fixed_menu').fadeOut(animationTime);
	});

	$('.mega_fixed_menu .dropdown-menu .arrow').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).closest('.dropdown-submenu').find('.dropdown-menu').slideToggle(animationTime);
		$(this).closest('.dropdown-submenu').addClass('opened');
	});
}

CheckPopupTop = function(){
	var popup = $('.jqmWindow.show');
	if(popup.length){
		var documentScollTop = $(document).scrollTop();
		var windowHeight = $(window).height();
		var popupTop = parseInt(popup.css('top'));
		var popupHeight = popup.height();

		if(windowHeight >= popupHeight){
			// center
			popupTop = (windowHeight - popupHeight) / 2;
		}
		else{
			if(documentScollTop > documentScrollTopLast){
				// up
				popupTop -= documentScollTop - documentScrollTopLast;
			}
			else if(documentScollTop < documentScrollTopLast){
				// down
				popupTop += documentScrollTopLast - documentScollTop;
			}

			if(popupTop + popupHeight < windowHeight){
				// bottom
				popupTop = windowHeight - popupHeight;
			}
			else if(popupTop > 0){
				// top
				popupTop = 0;
			}
		}
		popup.css('top', popupTop + 'px');
	}
}

CheckMainBannerSliderVText = function(slider){
	if(slider.parents('.banners-big').length){
		var sh = slider.height();
		slider.find('.item').each(function() {
			var curSlideTextInner = $(this).find('.text .inner');
			if(curSlideTextInner.length){
				var ith = curSlideTextInner.actual('height');
				var p = (ith >= sh ? 0 : Math.floor((sh - ith) / 2));
				curSlideTextInner.css('padding-top', p + 'px');
			}
		});
	}
}

CheckStickyFooter = function() {
	BX.addCustomEvent('onWindowResize', function(eventdata){
		if(!isMobile)
		{
			try{
				var footerHeight = $('footer').outerHeight();
				ignoreResize.push(true);
				$('footer').css('margin-top', '-' + footerHeight + 'px');
				$('.body').css('margin-bottom', '-' + footerHeight + 'px');
				$('.main').css('padding-bottom', footerHeight + 0 + 'px');
				ignoreResize.pop();
			}
			catch(e){}
		}
	});
}

verticalAlign = function(class_name){
	if(typeof class_name == "undefined")
		class_name = 'auto_align';
    if($('.'+class_name).length)
    {
	    maxHeight = 0;
	    $('.'+class_name).each(function(){
	        if ($(this).height()> maxHeight){
	            maxHeight = $(this).height();
	        };
	    });
	    $('.'+class_name).each(function(){

	            delta = Math.round((maxHeight - $(this).height())/2);
	            $(this).css({'padding-top': delta+'px', 'padding-bottom': delta+'px'});
	    });
	}
}

getGridSize = function(counts, custom_counts) {
	var z = parseInt($('.body_media').css('top'));
	if(typeof(custom_counts) != 'undefined')
	{
		if(window.matchMedia('(max-width: 700px)').matches)
			return (counts[3] ? counts[3] : counts[2]);
		else if(window.matchMedia('(max-width: 850px)').matches)
			return counts[2];
		else if(window.matchMedia('(max-width: 1100px)').matches)
			return counts[1];
		else
			return counts[0];
	}
	else
	{
		if(window.matchMedia('(max-width: 600px)').matches)
		{
			return (counts[3] ? counts[3] : counts[2]);
		}
		else
			return (z == 2 ? counts[0] : z == 1 ? counts[1] : counts[2]);
	}
}

CheckFlexSlider = function(){
	$('.flexslider:not(.thmb):visible').each(function(){
		var slider = $(this);
		slider.resize();
		var counts = slider.data('flexslider').vars.counts,
			slide_counts = slider.data('flexslider').vars.slide_counts;
		if(typeof(counts) != 'undefined'){
			var cnt = getGridSize(counts, slider.data('flexslider').vars.customGrid);
			var to0 = (cnt != slider.data('flexslider').vars.minItems || cnt != slider.data('flexslider').vars.maxItems || cnt != slider.data('flexslider').vars.move);
			if(to0){
				slider.data('flexslider').vars.minItems = cnt;
				slider.data('flexslider').vars.maxItems = cnt;
				if(typeof(slide_counts) != 'undefined')
					slider.data('flexslider').vars.move = slide_counts;
				else
					slider.data('flexslider').vars.move = cnt;

				slider.flexslider(0);
				slider.resize();
				slider.resize(); // twise!
			}
		}
	});
}

CheckHeaderFixed = function(){
	var header_fixed = $('#headerfixed');
		header = $('header').first();
	if(header_fixed.length){
		if(header.length)
		{
			var isHeaderFixed = false,
				headerCanFix = true,
				headerFixedHeight = header_fixed.actual('outerHeight'),
				headerNormalHeight = header.actual('outerHeight'),
				headerDiffHeight = headerNormalHeight - headerFixedHeight,
				mobileBtnMenu = $('.btn.btn-responsive-nav'),
				headerTop = $('#panel:visible').actual('outerHeight');
				topBlock = $('.TOP_HEADER').first();

			if(headerDiffHeight <= 0)
				headerDiffHeight = 0;
			if(topBlock.length)
				headerTop += topBlock.actual('outerHeight');

			$(window).scroll(function(){
				if(!isMobile)
				{
					var scrollTop = $(window).scrollTop();
					headerCanFix = !mobileBtnMenu.is(':visible')/* && !$('.dropdown-menu:visible').length*/;

					var current_is = $('.search-wrapper .search-input:visible'),
						title_search_result = $('.title-search-result.'+current_is.attr('id')),
						pos, pos_input;

					if(!isHeaderFixed)
					{
						if((scrollTop > headerNormalHeight + headerTop) && headerCanFix)
						{
							isHeaderFixed = true;
							header_fixed.css('top', '-' + headerNormalHeight + 'px');
							header_fixed.addClass('fixed');
							// header_fixed.stop(0).animate({top: '0'}, 300);

							header_fixed.animate({top: '0'}, {duration:300, complete:
								function(){}
							});
							markerNav();
							CheckTopMenuDotted();
						}
					}
					else if(isHeaderFixed || !headerCanFix)
					{
						if((scrollTop <= headerDiffHeight + headerTop) || !headerCanFix)
						{
							isHeaderFixed = false;
							header_fixed.removeClass('fixed');
						}
					}
				}
			});
		}
	}
}

CheckObjectsSizes = function() {
	$('.container iframe,.container object,.container video').each(function() {
		var height_attr = $(this).attr('height');
		var width_attr = $(this).attr('width');
		if (height_attr && width_attr) {
			$(this).css('height', $(this).outerWidth() * height_attr / width_attr);
		}
	});
}

scrollToTop = function(){
	if(arDigitalOptions['THEME']['SCROLLTOTOP_TYPE'] !== 'NONE'){
		var _isScrolling = false;
		// Append Button
		$('body').append($('<a />').addClass('scroll-to-top ' + arDigitalOptions['THEME']['SCROLLTOTOP_TYPE'] + ' ' + arDigitalOptions['THEME']['SCROLLTOTOP_POSITION']).attr({'href': '#', 'id': 'scrollToTop'}));
		$('#scrollToTop').click(function(e){
			e.preventDefault();
			$('body, html').animate({scrollTop : 0}, 500);
			return false;
		});
		// Show/Hide Button on Window Scroll event.
		$(window).scroll(function(){
			if(!_isScrolling) {
				_isScrolling = true;
				var bottom = 23,
					scrollVal = $(window).scrollTop(),
					windowHeight = $(window).height();

				var footerOffset = 0;
				if ($('footer').get(0)) {
					footerOffset = $('footer').offset().top;
				}
				if(scrollVal > 150){
					$('#scrollToTop').stop(true, true).addClass('visible');
					_isScrolling = false;
				}
				else{
					$('#scrollToTop').stop(true, true).removeClass('visible');
					_isScrolling = false;
				}
				CheckScrollToTop();
			}
		});
	}
}

CheckScrollToTop = function(){
	var bottom = 23,
		scrollVal = $(window).scrollTop(),
		windowHeight = $(window).height();
	if($('footer').length)
		var footerOffset = $('footer').offset().top;

	if(scrollVal + windowHeight > footerOffset){
		$('#scrollToTop').css('bottom', bottom + scrollVal + windowHeight - footerOffset);
	}
	else if(parseInt($('#scrollToTop').css('bottom')) > bottom){
		$('#scrollToTop').css('bottom', bottom);
	}
}

var isMobile = jQuery.browser.mobile;
var players = {};

if(isMobile){
	document.documentElement.className += ' mobile';
}

function startMainBannerSlideVideo($slide){
	if($('.banners-big').length)
	{
		if(typeof(CoverPlayer) === 'undefined'){
			CoverPlayer = function(){
				var $videoCover = $('.video.cover');
				if($videoCover.length){
					var bannersHeight = $('.banners-big').height()
					var bannersWidth = $('.banners-big').width()
					var windowWidth = $(window).width()
					var height = windowWidth * 9 / 16

					$videoCover.each(function(i, node){
						node.style.height = height + 'px'
						node.style.marginTop = ((bannersHeight - height) / 2) + 'px'
					})
				}
			}
			$(window).resize(function() {
				if($('.video.cover').length){
					CoverPlayer();
				}
			});
		}

		var slideActiveIndex = $slide.attr('data-slide_index')
		var $slides = $slide.closest('.items').find('.item[data-slide_index="'+ slideActiveIndex +'"]')
		var videoSource = $slide.attr('data-video_source')

		if(videoSource){
			var videoPlayerSrc = $slide.attr('data-video_src')
			var videoSoundDisabled = $slide.attr('data-video_disable_sound')
			var bVideoSoundDisabled = videoSoundDisabled == 1
			var videoLoop = $slide.attr('data-video_loop')
			var bVideoLoop = videoLoop == 1
			var videoCover = $slide.attr('data-video_cover')
			var bVideoCover = videoCover == 1 && !isMobile
			var videoUnderText = $slide.attr('data-video_under_text')
			var bVideoUnderText = videoUnderText == 1
			if(videoSource === 'LINK'){
				var videoPlayer = $slide.attr('data-video_player')
				var bVideoPlayerYoutube = videoPlayer === 'YOUTUBE'
				var bVideoPlayerVimeo = videoPlayer === 'VIMEO'

				if(videoPlayerSrc && !$slide.find('iframe.video').length){					
					$slides.each(function(i, node){
						var $_slide = $(node);
						var videoID = getRandomInt(100, 1000);
						if(bVideoPlayerVimeo){
							videoPlayerSrc += '&player_id=vimeoplayer';
						}
						pauseMainBanner();
						$_slide.prepend('<iframe id="player_' + videoID + '" class="video' + (bVideoCover == true ? ' cover' : '') + '" src="'+ videoPlayerSrc +'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
						if(typeof(players) !== 'undefined' && players){
							players[videoID] = {id: 'player_' + videoID, mute: (bVideoSoundDisabled ? '1' : '0'), loop: (bVideoLoop ? '1' : '0'), cover: (bVideoCover ? '1' : '0')};
							if(bVideoPlayerYoutube){
								waitYTPlayer(150, function(){
								//setTimeout(function(){
									window[players[videoID].id] = new YT.Player(
										players[videoID].id, {
											events: {
												'onReady': onYoutubePlayerReady
											}
										}
									);
									if(bVideoSoundDisabled){
										window[players[videoID].id].addEventListener('onReady', muteYoutubePlayer);
									}
									if(bVideoLoop){
										window[players[videoID].id].addEventListener('onStateChange', loopYoutubePlayer);
									}
								//},150);
								});
							}
							else if(bVideoPlayerVimeo){
								var iframe = $('#'+players[videoID].id)
								if(iframe.length){
									setTimeout(function(){
										iframe.closest('.item').addClass('started')
										pauseMainBanner()
									},150)
									var player = iframe[0].childNodes
									// console.log(iframe, player)
									/*player.addEvent('play', function() {
										console.log('play')
									});*/
								}
							}
						}
					});
				}
			}
			if(videoPlayerSrc && !$slide.find('.video').length){
				$slides.each(function(i, node){
					var $_slide = $(node);
					var videoID = getRandomInt(100, 1000);
					$_slide.prepend('<video id="player_' + videoID + '" class="video' + (bVideoCover == true ? ' cover' : '') + '"' + (bVideoLoop == true ? ' loop ' : '') + (bVideoSoundDisabled == true ? ' muted ' : '') + ' autoplay><source src="'+ videoPlayerSrc +'" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\' /><p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video</p></iframe>');
					if(typeof(players) !== 'undefined' && players){
						players[videoID] = {id: 'player_' + videoID, mute: (bVideoSoundDisabled ? '1' : '0'), loop: (bVideoLoop ? '1' : '0'), cover: (bVideoCover ? '1' : '0')};
						// if(bVideoCover){
							document.getElementById(players[videoID].id).addEventListener('play', onHtml5PlayerPlay);
						// }
					}
				});
			}
		}
	}
}

function muteYoutubePlayer(e) {
	e.target.mute()
}

function loopYoutubePlayer(e) {
	if(e.data === YT.PlayerState.ENDED){
		e.target.playVideo()
	}
}

function onYoutubePlayerReady(e) {
    e.target.playVideo()
    var videoID = e.target.a.id.replace('player_', '')
    if(videoID){
    	var $slide = $('#player_' + videoID).closest('.item')
    	$slide.addClass('started')
    	pauseMainBanner()
    	//setMainBannerSlideVerticalCenter($slide)
		var cover = players[videoID].cover
		if(cover){
	    	CoverPlayer()
	    }
    }
}

function onHtml5PlayerPlay(e){
    var videoID = e.target.id.replace('player_', '')
    if(videoID){
    	var $slide = $('#player_' + videoID).closest('.item')
    	$slide.addClass('started')
    	pauseMainBanner()
    	// setMainBannerSlideVerticalCenter($slide)
		var cover = players[videoID].cover
		if(cover){
	    	CoverPlayer()
	    }
    }
}

function pauseMainBanner(){
	$('.banners-big .flexslider').flexslider('pause');
}

$.fn.equalizeHeights = function( outer, classNull, minHeight ){
	var maxHeight = this.map( function( i, e ){
		var minus_height=0,
			calc_height=0;
		if(classNull!==false){
			minus_height=parseInt($(e).find(classNull).actual('outerHeight'));
		}
		if(minus_height)
			minus_height+=12;

		$(e).css('height', '');
		if( outer == true ){
			calc_height=$(e).actual('outerHeight')-minus_height;
		}else{
			calc_height=$(e).actual('height')-minus_height;
		}
		if(minHeight!==false){
			if(calc_height<minHeight){
				calc_height+=(minHeight-calc_height);
			}
			if(window.matchMedia('(max-width: 520px)').matches){
				calc_height=300;
			}
			if(window.matchMedia('(max-width: 400px)').matches){
				calc_height=200;
			}
		}
		return calc_height;
	}).get();

	for(var i = 0, c = maxHeight.length; i < c; ++i){
		if(maxHeight[i] % 2){
			--maxHeight[i];
		}
	}

	return this.height( Math.max.apply( this, maxHeight ) );
}

$.fn.getFloatWidth = function(){
	var width = 0

	if($(this).length){
		var rect = $(this)[0].getBoundingClientRect()
		if(!(width = rect.width)){
			width = rect.right - rect.left
		}
	}

	return width
}

$.fn.sliceHeight = function( options ){
	function _slice(el){
		el.each(function() {
			$(this).css('line-height', '');
			$(this).css('height', '');
		});
		if(typeof(options.autoslicecount) == 'undefined' || options.autoslicecount !== false){
			var elsw=(typeof(options.row) !== 'undefined' && options.row.length) ?  el.first().parents(options.row).getFloatWidth() : el.first().parents('.items').getFloatWidth(),
				elw=(typeof(options.item) !== 'undefined' && options.item.length) ? $(options.item).first().getFloatWidth() : (el.first().hasClass('item') ? el.first().getFloatWidth() : el.first().parents('.item').getFloatWidth());

			if(!elsw){
				elsw = el.first().parents('.row').getFloatWidth();
			}
			if(elw && options.fixWidth)
				elw -= options.fixWidth;

			if(elsw && elw){
				options.slice = Math.floor(elsw / elw);
			}
		}
		if(typeof(options.typeResize) == 'undefined' || options.typeResize == false)
		{
			if(options.slice){
					for(var i = 0; i < el.length; i += options.slice){
						$(el.slice(i, i + options.slice)).equalizeHeights(options.outer, options.classNull, options.minHeight, options.typeResize, options.typeValue);
					}
			}
			if(options.lineheight){
				var lineheightAdd = parseInt(options.lineheight);
				if(isNaN(lineheightAdd)){
					lineheightAdd = 0;
				}
				el.each(function() {
					$(this).css('line-height', ($(this).actual('height') + lineheightAdd) + 'px');
				});
			}
		}
	}
	var options = $.extend({
		slice: null,
		outer: false,
		lineheight: false,
		autoslicecount: true,
		classNull: false,
		minHeight: false,
		row:false,
		item:false,
		typeResize:false,
		typeValue:false,
		fixWidth:0,
	}, options);

	var el = $(this);
	ignoreResize.push(true);
	_slice(el);
	ignoreResize.pop();

	BX.addCustomEvent('onWindowResize', function(eventdata) {
		ignoreResize.push(true);
		_slice(el);
		ignoreResize.pop();
	});
}

waitingExists = function(selector, delay, callback){
	if(typeof(callback) !== 'undefined' && selector.length && delay > 0){
		delay = parseInt(delay);
		delay = (delay < 0 ? 0 : delay);

		if(!$(selector).length){
			setTimeout(function(){
				waitingExists(selector, delay, callback);
			}, delay);
		}
		else{
			callback();
		}
	}
}

waitingNotExists = function(selectorExists, selectorNotExists, delay, callback){
	if(typeof(callback) !== 'undefined' && selectorExists.length && selectorNotExists.length && delay > 0){
		delay = parseInt(delay);
		delay = (delay < 0 ? 0 : delay);

		setTimeout(function(){
			if(selectorExists.length && !$(selectorNotExists).length){
				callback();
			}
		}, delay);
	}
}

function onLoadjqm(hash){
	var name = $(hash.t).data('name'),
		top = (($(window).height() > hash.w.height()) ? Math.floor(($(window).height() - hash.w.height()) / 2) : 0) + 'px';
	$.each($(hash.t).get(0).attributes, function(index, attr){
		if(/^data\-autoload\-(.+)$/.test(attr.nodeName)){
			var key = attr.nodeName.match(/^data\-autoload\-(.+)$/)[1];
			var el = $('input[name="'+key.toUpperCase()+'"]');
			el.val( $(hash.t).data('autoload-'+key) ).attr('readonly', 'readonly');
			el.closest('.form-group').addClass('input-filed');
			el.attr('title', el.val());
		}
	});

	var eventdata = {action:'loadForm'};
	BX.onCustomEvent('onCompleteAction', [eventdata, $(hash.t)[0]]);

	if($(hash.t).data('autohide')){
		$(hash.w).data('autohide', $(hash.t).data('autohide'));
	}
	if(name == 'order_product'){
		if($(hash.t).data('product')) {
			$('input[name="PRODUCT"]').closest('.form-group').addClass('input-filed');
			$('input[name="PRODUCT"]').val($(hash.t).data('product')).attr('readonly', 'readonly').attr('title', $('input[name="PRODUCT"]').val());
		}
	}
	if(name == 'question'){
		if($(hash.t).data('product')) {
			$('input[name="NEED_PRODUCT"]').closest('.form-group').addClass('input-filed');
			$('input[name="NEED_PRODUCT"]').val($(hash.t).data('product')).attr('readonly', 'readonly').attr('title', $('input[name="NEED_PRODUCT"]').val());
		}
	}
	hash.w.addClass('show').css({'margin-left': '-' + Math.floor(hash.w.width() / 2) + 'px', 'top': top, 'opacity': 1});
}

function onHide(hash){
	if($(hash.w).data('autohide')){
		eval($(hash.w).data('autohide'));
	}
	// hash.w.css('opacity', 0).hide();
	hash.w.animate({'opacity': 0}, 200, function(){
		hash.w.hide();
		hash.w.empty();
		hash.o.remove();
		hash.w.removeClass('show');
	});
}

function parseUrlQuery() {
	var data = {};
	if(location.search) {
		var pair = (location.search.substr(1)).split('&');
		for(var i = 0; i < pair.length; i ++) {
			var param = pair[i].split('=');
			data[param[0]] = param[1];
		}
	}
	return data;
}

$.fn.jqmEx = function(){
	$(this).each(function(){
		var _this = $(this);
		var name = _this.data('name');

		if(name.length){
			var script = arDigitalOptions['SITE_DIR'] + 'ajax/form.php';
			var paramsStr = ''; var trigger = ''; var arTriggerAttrs = {};
			$.each(_this.get(0).attributes, function(index, attr){
				var attrName = attr.nodeName;
				var attrValue = _this.attr(attrName);
				trigger += '[' + attrName + '=\"' + attrValue + '\"]';
				arTriggerAttrs[attrName] = attrValue;
				if(/^data\-param\-(.+)$/.test(attrName)){
					var key = attrName.match(/^data\-param\-(.+)$/)[1];
					paramsStr += key + '=' + attrValue + '&';
				}
			});
			var triggerAttrs = JSON.stringify(arTriggerAttrs);
			var encTriggerAttrs = encodeURIComponent(triggerAttrs);
			if(name == 'auth')
				script += '?' + paramsStr + 'auth=Y';
			else
				script += '?' + paramsStr + 'data-trigger=' + encTriggerAttrs;

			if(!$('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').length){
				if(_this.attr('disabled') != 'disabled'){
					$('body').find('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').remove();
					$('body').append('<div class="' + name + '_frame jqmWindow" style="width:500px" data-trigger="' + encTriggerAttrs + '"></div>');
					$('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').jqm({trigger: trigger, onLoad: function(hash){onLoadjqm(hash);}, onHide: function(hash){onHide(hash);}, ajax:script});
				}
			}
		}
	});
}

InitFlexSlider = function() {
	$('.flexslider:not(.thmb):not(.flexslider-init):visible').each(function(){
		var slider = $(this);
		var options;
		var defaults = {
			animationLoop: false,
			controlNav: false,
			directionNav: true,
			animation: "slide"
		}
		var config = $.extend({}, defaults, options, slider.data('plugin-options'));
		if(typeof(config.counts) != 'undefined' && config.direction !== 'vertical'){
			var slide_counts = '';
			if(typeof(slider.data('plugin-options')) != 'undefined')
			{
				if('slide_counts' in slider.data('plugin-options'))
					slide_counts = slider.data('plugin-options').slide_counts;
			}
			config.maxItems =  getGridSize(config.counts);
			config.minItems = getGridSize(config.counts);

			if(slide_counts)
				config.move = slide_counts;
			else
				config.move = getGridSize(config.counts);

			config.itemWidth = 200;
		}

		// custom direction nav
		if(typeof(config.customDirection) != 'undefined')
			config.customDirectionNav = $(config.customDirection);

		config.prevText = BX.message("FANCY_PREV"),           //String: Set the text for the "previous" directionNav item
		config.nextText = BX.message("FANCY_NEXT"),

		config.after = config.start = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlide', [eventdata]);
		}

		config.end = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlideEnd', [eventdata]);
		}

		slider.addClass('dark-nav');
		slider.flexslider(config).addClass('flexslider-init');
		if(config.controlNav)
			slider.addClass('flexslider-control-nav');
		if(config.directionNav)
			slider.addClass('flexslider-direction-nav');
	});
}

InitFlexSliderClass = function(class_name) {
	//$('.flexslider:not(.thmb):not(.flexslider-init)').each(function(){

		var slider = $(class_name);
		var options;
		var defaults = {
			animationLoop: false,
			controlNav: false,
			directionNav: true,
			animation: "slide"
		}
		var config = $.extend({}, defaults, options, slider.data('plugin-options'));

		var slide_counts = '';
		if(typeof(slider.data('plugin-options')) != 'undefined')
		{
			if('slide_counts' in slider.data('plugin-options'))
				slide_counts = slider.data('plugin-options').slide_counts;
		}

		if(typeof(config.counts) != 'undefined' && config.direction !== 'vertical'){
			config.maxItems =  getGridSize(config.counts);
			config.minItems = getGridSize(config.counts);
			config.move = getGridSize(config.counts);

			config.itemWidth = 200;
		}
		if(slide_counts)
			config.move = slide_counts;

		// custom direction nav
		if(typeof(config.customDirection) != 'undefined')
			config.customDirectionNav = $(config.customDirection);

		config.prevText = BX.message("FANCY_PREV"),           //String: Set the text for the "previous" directionNav item
		config.nextText = BX.message("FANCY_NEXT"),

		config.after = config.start = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlide', [eventdata]);
		}

		config.end = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlideEnd', [eventdata]);
		}

		slider.flexslider(config).addClass('flexslider-init');
		if(config.controlNav)
			slider.addClass('flexslider-control-nav');
		if(config.directionNav)
			slider.addClass('flexslider-direction-nav');
	//});
}

SliceHeightBlocks = function(){
	$('*[data-slice="Y"]').each(function(){
		var slice_els = $(this).find('*[data-slice-block="Y"]');
		if(slice_els.length)
		{
			var slice_params = {};
			if(slice_els.data('slice-params'))
				slice_params = slice_els.data('slice-params');
			slice_els.sliceHeight(slice_params);
		}
	})
}

createTableCompare = function(originalTable, appendDiv, cloneTable){
	try{
		if($('.tarifs .head-block:visible').length){
			var clone = originalTable.clone().addClass('clone');
			if(cloneTable.length){
				cloneTable.remove();
				appendDiv.html('');
				appendDiv.html(clone);
			}else{
				appendDiv.append(clone);
			}
		}
	}
	catch(e){}
	finally{}
}

CheckHeaderFixedMenu = function(){
	if(arDigitalOptions['THEME']['HEADER_FIXED'] == 2 && $('#headerfixed .js-nav').length && window.matchMedia('(min-width: 992px)').matches)
	{
		$('#headerfixed .js-nav').css('width','0');
		var all_width = 0,
			cont_width = $('#headerfixed .maxwidth-theme').actual('width'),
			padding_menu = $('#headerfixed .logo-row.v2 .menu-block').actual('outerWidth')-$('#headerfixed .logo-row.v2 .menu-block').actual('width');
		$('#headerfixed .logo-row.v2 > .inner-table-block').each(function(){
			if(!$(this).hasClass('menu-block'))
				all_width += $(this).actual('outerWidth');
		})
		$('#headerfixed .js-nav').width(cont_width-all_width-padding_menu);
	}
}

CheckTopMenuPadding = function(){
	if($('.logo_and_menu-row .right-icons .wrap_icon').length && $('.logo_and_menu-row .menu-row').length){
		var menuPosition = $('.menu-row .menu-only').position().left,
			maxWidth = $('.logo_and_menu-row .maxwidth-theme').width() - 32,
			leftPadding = 0,
			rightPadding = 0;

		$('.logo_and_menu-row .menu-row>div').each(function(indx){
			if(!$(this).hasClass('menu-only')){
				var elementPosition = $(this).position().left,
					elementWidth = $(this).outerWidth();

				if(elementPosition > menuPosition){
					rightPadding += elementWidth;
				}else{
					leftPadding += elementWidth;
				}
			}
		}).promise().done(function(){
			$('.logo_and_menu-row .menu-only').css({'padding-left': leftPadding, 'padding-right': rightPadding});
		});
	}
}

CheckTopMenuOncePadding = function(){
	if($('.menu-row.sliced .right-icons .wrap_icon').length){
		var menuPosition = $('.menu-row .menu-only').position().left,
			maxWidth = $('.logo_and_menu-row .maxwidth-theme').width() - 32,
			leftPadding = 0,
			rightPadding = 0;

		$('.menu-row.sliced .maxwidth-theme>div>div').each(function(indx){
			if(!$(this).hasClass('menu-only')){
				var elementPosition = $(this).position().left,
					elementWidth = $(this).outerWidth();

				if(elementPosition > menuPosition){
					rightPadding += elementWidth;
				}else{
					leftPadding += elementWidth;
				}
			}
		}).promise().done(function(){
			$('.menu-row.sliced .menu-only').css({'padding-left': leftPadding, 'padding-right': rightPadding});
		});
	}
}

CheckSearchWidth = function(){
	if($('.logo_and_menu-row .search_wrap').length){
		var searchPosition = $('.logo_and_menu-row .search_wrap').position().left,
			maxWidth = $('.logo_and_menu-row .maxwidth-theme').width() - 32;
			width = 0;

		$('.logo_and_menu-row .maxwidth-theme>div').each(function(){
			if(!$(this).hasClass('search_wrap')){
				var elementWidth = $(this).outerWidth();

				width = (width ? width - elementWidth : maxWidth - elementWidth);
			}
		}).promise().done(function(){
			$('.logo_and_menu-row .search_wrap').outerWidth(width).css({'opacity': 1, 'visibility': 'visible'});
		});
	}
}

waitCounter = function(idCounter, delay, callback){
	var obCounter = window['yaCounter' + idCounter];
	if(typeof obCounter == 'object')
	{
		if(typeof callback == 'function')
			callback();
		
	}
	else
	{
		setTimeout(function(){
			waitCounter(idCounter, delay, callback);
		}, delay);
	}
}

waitYTPlayer = function(delay, callback){
	if((typeof YT !== "undefined") && YT && YT.Player)
	{
		if(typeof callback == 'function')
			callback();
	}
	else
	{
		setTimeout(function(){
			waitYTPlayer(delay, callback);
		}, delay);
	}
}

$(document).ready(function(){
	CheckTopMenuPadding();
	CheckTopMenuOncePadding();
	scrollToTop();
	CheckStickyFooter();
	CheckHeaderFixed();
	CheckTopMenuDotted();
	MegaMenuFixed();
	CheckSearchWidth();

	setTimeout(function() {$(window).resize();}, 150); // need to check resize flexslider & menu
	setTimeout(function() {$(window).scroll();}, 250); // need to check position fixed ask block
	// $(window).scroll();

	if(arDigitalOptions['THEME']['USE_DEBUG_GOALS'] === 'Y'){
		$.cookie('_ym_debug', '1');
	}
	else{
		$.cookie('_ym_debug', null);
	}

	/*  --- Bind mobile menu  --- */
	var $mobileMenu = $("#mobilemenu")
	if($mobileMenu.length){
		$mobileMenu.isLeftSide = $mobileMenu.hasClass('leftside')
		$mobileMenu.isOpen = $mobileMenu.hasClass('show')
		$mobileMenu.isDowndrop = $mobileMenu.find('>.scroller').hasClass('downdrop')

		$('#mobileheader .burger').click(function(){
			SwipeMobileMenu()
		})

		if($mobileMenu.isLeftSide){
			$mobileMenu.parent().append('<div id="mobilemenu-overlay"></div>')
			var $mobileMenuOverlay = $('#mobilemenu-overlay')

			$mobileMenuOverlay.click(function(){
				if($mobileMenu.isOpen){
					CloseMobileMenu()
				}
			});

			$(document).swiperight(function(e) {
				if(!$(e.target).closest('.flexslider').length && !$(e.target).closest('.swipeignore').length){
					OpenMobileMenu()
				}
			});

			$(document).swipeleft(function(e) {
				if(!$(e.target).closest('.flexslider').length && !$(e.target).closest('.swipeignore').length){
					CloseMobileMenu()
				}
			});
		}
		else{
			$('#mobileheader').click(function(e){
				if(!$(e.target).closest('#mobilemenu').length && !$(e.target).closest('.burger').length && $mobileMenu.isOpen){
					CloseMobileMenu()
				}
			});
		}

		$('#mobilemenu .menu a,#mobilemenu .social-icons a').click(function(e){
			var $this = $(this)
			if($this.hasClass('parent')){
				e.preventDefault()

				if(!$mobileMenu.isDowndrop){
					$this.closest('li').addClass('expanded')
					MoveMobileMenuWrapNext()
				}
				else{
					if(!$this.closest('li').hasClass('expanded')){
						$this.closest('li').addClass('expanded')
					}
					else{
						$this.closest('li').removeClass('expanded')
					}
				}
			}
			else{
				if($this.closest('li').hasClass('counters')){
					var href = $this.attr('href')
					if(typeof href !== 'undefined'){
						window.location.href = href
						window.location.reload()
					}
				}

				if(!$this.closest('.menu_back').length){
					CloseMobileMenu()
				}
			}
		})

		$('#mobilemenu .dropdown .menu_back').click(function(e){
			e.preventDefault()
			var $this = $(this)
			MoveMobileMenuWrapPrev()
			setTimeout(function(){
				$this.closest('.expanded').removeClass('expanded')
			}, 400)
		})

		OpenMobileMenu = function(){
			if(!$mobileMenu.isOpen){
				// hide styleswitcher
				if($('.style-switcher').hasClass('active')){
					$('.style-switcher .switch').trigger('click')
				}
				$('.style-switcher .switch').hide()

				if($mobileMenu.isLeftSide){
					// show overlay
					setTimeout(function(){
						$mobileMenuOverlay.fadeIn('fast')
					}, 100)
				}
				else{
					// scroll body to top & set fixed
					$('body').scrollTop(0).css({position: 'fixed'})

					// set menu top = bottom of header
					$mobileMenu.css({top: + ($('#mobileheader').height() + $('#mobileheader').offset().top) + 'px'})

					// change burger icon
					$('#mobileheader .burger').addClass('c')
				}

				// show menu
				$mobileMenu.addClass('show')
				$mobileMenu.isOpen = true

				if(!$mobileMenu.isDowndrop){
					var $wrap = $mobileMenu.find('.wrap').first()
					var params =  $wrap.data('params')
					if(typeof params === 'undefined'){
						params = {
							depth: 0,
							scroll: {},
							height: {}
						}
					}
					$wrap.data('params', params)
				}
			}
		}

		CloseMobileMenu = function(){
			if($mobileMenu.isOpen){
				// hide menu
				$mobileMenu.removeClass('show')
				$mobileMenu.isOpen = false

				// show styleswitcher
				$('.style-switcher .switch').show()

				if($mobileMenu.isLeftSide){
					// hide overlay
					setTimeout(function(){
						$mobileMenuOverlay.fadeOut('fast')
					}, 100)
				}
				else{
					// change burger icon
					$('#mobileheader .burger').removeClass('c')

					// body unset fixed
					$('body').css({position: ''})
				}

				if(!$mobileMenu.isDowndrop){
					setTimeout(function(){
						var $scroller = $mobileMenu.find('.scroller').first()
						var $wrap = $mobileMenu.find('.wrap').first()
						var params =  $wrap.data('params')
						params.depth = 0
						$wrap.data('params', params).attr('style', '')
						$mobileMenu.scrollTop(0)
						$scroller.css('height', '')
					}, 400)
				}
			}
		}

		SwipeMobileMenu = function(){
			if($mobileMenu.isOpen){
				CloseMobileMenu()
			}
			else{
				OpenMobileMenu()
			}
		}

		function MoveMobileMenuWrapNext(){
			if(!$mobileMenu.isDowndrop){
				var $scroller = $mobileMenu.find('.scroller').first()
				var $wrap = $mobileMenu.find('.wrap').first()
				if($wrap.length){
					var params =  $wrap.data('params')
					var $dropdownNext = $mobileMenu.find('.expanded>.dropdown').eq(params.depth)
					if($dropdownNext.length){
						// save scroll position
						params.scroll[params.depth] = parseInt($mobileMenu.scrollTop())

						// height while move animating
						params.height[params.depth + 1] = Math.max($dropdownNext.height(), (!params.depth ? $wrap.height() : $mobileMenu.find('.expanded>.dropdown').eq(params.depth - 1).height()))
						$scroller.css('height', params.height[params.depth + 1] + 'px')

						// inc depth
						++params.depth

						// translateX for move
						$wrap.css('transform', 'translateX(' + -100 * params.depth + '%)')

						// scroll to top
						setTimeout(function() {
							$mobileMenu.animate({scrollTop : 0}, 200);
						}, 100)

						// height on enimating end
						var h = $dropdownNext.height()
						setTimeout(function() {
							if(h){
								$scroller.css('height', h + 'px')
							}
							else{
								$scroller.css('height', '')
							}
						}, 200)
					}

					$wrap.data('params', params)
				}
			}
		}

		function MoveMobileMenuWrapPrev(){
			if(!$mobileMenu.isDowndrop){
				var $scroller = $mobileMenu.find('.scroller').first()
				var $wrap = $mobileMenu.find('.wrap').first()
				if($wrap.length){
					var params =  $wrap.data('params')
					if(params.depth > 0){
						var $dropdown = $mobileMenu.find('.expanded>.dropdown').eq(params.depth - 1)
						if($dropdown.length){
							// height while move animating
							$scroller.css('height', params.height[params.depth] + 'px')

							// dec depth
							--params.depth

							// translateX for move
							$wrap.css('transform', 'translateX(' + -100 * params.depth + '%)')

							// restore scroll position
							setTimeout(function() {
								$mobileMenu.animate({scrollTop : params.scroll[params.depth]}, 200);
							}, 100)

							// height on enimating end
							var h = (!params.depth ? false : $mobileMenu.find('.expanded>.dropdown').eq(params.depth - 1).height())
							setTimeout(function() {
								if(h){
									$scroller.css('height', h + 'px')
								}
								else{
									$scroller.css('height', '')
								}
							}, 200)
						}
					}

					$wrap.data('params', params)
				}
			}
		}
	}
	/*  --- END Bind mobile menu  --- */


	/* change type2 menu for fixed */
	if($('#headerfixed .js-nav').length)
	{
		if(arDigitalOptions['THEME']['HEADER_FIXED'] == 2)
			CheckHeaderFixedMenu();

		setTimeout(function(){
			$('#headerfixed .js-nav').addClass('opacity1');
		},350);
	}

	/* close search block */
	$("html, body").on('mousedown', function(e){
		e.stopPropagation();
		var search_target = $(e.target).closest('.bx_searche');
		if(!$(e.target).hasClass('inline-search-block') && !$(e.target).hasClass('svg') && !search_target.length)
		{
			$('.inline-search-block').removeClass('show');
			$('.title-search-result').hide();
			if(arDigitalOptions['THEME']['TYPE_SEARCH'] == 'fixed')
				$('.jqmOverlay.search').detach();
		}

		if(isMobile)
		{
			if(search_target.length)
				location.href = search_target.attr('href');
		}
		var class_name = $(e.target).attr('class');
		if(typeof(class_name) == 'undefined' || class_name.indexOf('tooltip') < 0) //tooltip link
			$('.tooltip-link').tooltip('hide');
	});
	$('.inline-search-block').find('*').on('mousedown', function(e){
		e.stopPropagation();
	});


	$('.filter-action').on('click', function(){
		$(this).toggleClass('active');
		$(this).find('.svg').toggleClass('white');
		if($('.text_before_items').length)
		{
			var top_pos = $('.filters-wrap').position();
			$('.bx_filter').css({'top':top_pos.top+40});
		}
		$('.bx_filter').slideToggle();
	})

	waitingNotExists('#bx-composite-banner .bx-composite-btn', '#footer .col-sm-3.hidden-md.hidden-lg #bx-composite-banner .bx-composite-btn', 500, function() {
		$('#footer .col-sm-3.hidden-md.hidden-lg #bx-composite-banner').html($('#bx-composite-banner .bx-composite-btn').parent().html());
	});

	$.extend( $.validator.messages, {
		required: BX.message('JS_REQUIRED'),
		email: BX.message('JS_FORMAT'),
		equalTo: BX.message('JS_PASSWORD_COPY'),
		minlength: BX.message('JS_PASSWORD_LENGTH'),
		remote: BX.message('JS_ERROR')
	});

	$.validator.addMethod(
		'regexp', function( value, element, regexp ){
			var re = new RegExp( regexp );
			return this.optional( element ) || re.test( value );
		},
		BX.message('JS_FORMAT')
	);

	$.validator.addMethod(
		'filesize', function( value, element, param ){
			return this.optional( element ) || ( element.files[0].size <= param )
		},
		BX.message('JS_FILE_SIZE')
	);

	$.validator.addMethod(
		'date', function( value, element, param ) {
			var status = false;
			if(!value || value.length <= 0){
				status = false;
			}
			else{
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4})$');
				var matches = re.exec(value);
				if(matches){
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]));
				}
			}
			return status;
		}, BX.message('JS_DATE')
	);

	$.validator.addMethod(
		'datetime', function( value, element, param ) {
			var status = false;
			if(!value || value.length <= 0){
				status = false;
			}
			else{
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2})$');
				var matches = re.exec(value);
				if(matches){
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1], matches[6], matches[7]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]) && (composedDate.getHours() == matches[6]) && (composedDate.getMinutes() == matches[7]));
				}
			}
			return status;
		}, BX.message('JS_DATETIME')
	);

	$.validator.addMethod(
		'extension', function(value, element, param){
			param = typeof param === 'string' ? param.replace(/,/g, '|') : 'png|jpe?g|gif';
			return this.optional(element) || value.match(new RegExp('.(' + param + ')$', 'i'));
		}, BX.message('JS_FILE_EXT')
	);

	$.validator.addMethod(
		'captcha', function( value, element, params ){
			return $.validator.methods.remote.call(this, value, element,{
				url: arDigitalOptions['SITE_DIR'] + 'ajax/check-captcha.php',
				type: 'post',
				data:{
					captcha_word: value,
					captcha_sid: function(){
						return $(element).closest('form').find('input[name="captcha_sid"]').val();
					}
				}
			});
		},
		BX.message('JS_ERROR')
    );

	/*reload captcha*/
	$('body').on( 'click', '.refresh', function(e){
		e.preventDefault();
		$.ajax({
			url: arDigitalOptions['SITE_DIR'] + 'ajax/captcha.php'
		}).done(function(text){
			if($('.captcha_sid').length)
			{
				$('.captcha_sid').val(text);
				$('.captcha_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + text);
			}
		});
	});

	$.validator.addClassRules({
		'phone':{
			regexp: arDigitalOptions['THEME']['VALIDATE_PHONE_MASK']
		},
		'confirm_password':{
			equalTo: 'input.password',
			minlength: 6
		},
		'password':{
			minlength: 6
		},
		'inputfile':{
			extension: arDigitalOptions['THEME']['VALIDATE_FILE_EXT'],
			filesize: 5000000
		},
		'datetime':{
			datetime: ''
		},
		'captcha':{
			captcha: ''
		}
	});

	$.validator.setDefaults({
	   highlight: function( element ){
			$(element).parent().addClass('error');
		},
		unhighlight: function( element ){
			$(element).parent().removeClass('error');
		}
	});

	InitFlexSlider();

	// for check flexslider bug in composite mode
	waitingNotExists('.detail .galery #slider', '.detail .galery #slider .flex-viewport', 1000, function() {
		InitFlexSlider();
		setTimeout(function() {
			$(window).resize();
		}, 350);
	});

	/*check mobile device*/
	if(jQuery.browser.mobile){
		$('.hint span').remove();

		$('*[data-event="jqm"]').on('click', function(e){
			e.preventDefault();
			var _this = $(this);
			var name = _this.data('name');

			if(name.length){
				var script = arDigitalOptions['SITE_DIR'] + 'form/';
				var paramsStr = ''; var arTriggerAttrs = {};
				$.each(_this.get(0).attributes, function(index, attr){
					var attrName = attr.nodeName;
					var attrValue = _this.attr(attrName);
					arTriggerAttrs[attrName] = attrValue;
					if(/^data\-param\-(.+)$/.test(attrName)){
						var key = attrName.match(/^data\-param\-(.+)$/)[1];
						paramsStr += key + '=' + attrValue + '&';
					}
				});

				var triggerAttrs = JSON.stringify(arTriggerAttrs);
				var encTriggerAttrs = encodeURIComponent(triggerAttrs);
				script += '?name=' + name + '&' + paramsStr + 'data-trigger=' + encTriggerAttrs;
				location.href = script;
			}
		});

		$('.fancybox').removeClass('fancybox');
	}
	else{
		$(document).on('click', '*[data-event="jqm"]', function(e){
			e.preventDefault();
			e.stopPropagation();
			$(this).jqmEx();
			$(this).trigger('click');

		});
	}

	$('.animate-load').on('click', function(){
		$(this).parent().addClass('loadings');
	})

	BX.addCustomEvent('onCompleteAction', function(eventdata, _this){
		try{
			if(eventdata.action === 'loadForm')
			{
				$(_this).parent().removeClass('loadings');
			}
			else if(eventdata.action === 'loadBasket')
			{
				var basket_link = $('.basket-link');
				if(basket_link.length)
				{
					basket_link.attr('title', $(_this).find('a').attr('title'));
					if($(_this).find('a .count').length){
						var count = basket_link.find('.count').length ? $(_this).find('.count').text() : $(_this).find('.count').text();
						basket_link.find('.prices').text($(_this).find('.icon').data('summ'));
						if(basket_link.find('.count').length)
						{
							basket_link.find('.count').text(count);
							if(count)
								basket_link.addClass('basket-count');
							else
								basket_link.removeClass('basket-count');
						}
						else
						{
							basket_link.find('.js-basket-block').append($(_this).find('.count'));
							basket_link.addClass('basket-count');
							CheckHeaderFixedMenu();
						}

						$('#mobilemenu .menu .ready .count').text(count);
						if(count){
							$('#mobilemenu .menu .ready .count').removeClass('empted');
						}
						else{
							$('#mobilemenu .menu .ready .count').addClass('empted');
						}
					}
					else
					{
						basket_link.find('.count').remove();
						basket_link.removeClass('basket-count');
						CheckHeaderFixedMenu();
					}
				}
			}
			else if(eventdata.action === 'loadRSS')
			{
			}
		}
		catch(e){
			console.error(e)
		}
	})

	BX.addCustomEvent('onCounterGoals', function(eventdata){
		if(arDigitalOptions['THEME']['YA_GOLAS'] == 'Y' && arDigitalOptions['THEME']['YA_COUNTER_ID'])
		{
			var idCounter = arDigitalOptions['THEME']['YA_COUNTER_ID'];
			idCounter = parseInt(idCounter);

			if(typeof eventdata != 'object')
				eventdata = {goal: 'undefined'};
			
			if(typeof eventdata.goal != 'string')
				eventdata.goal = 'undefined';
			
			if(idCounter)
			{
				try
				{
					waitCounter(idCounter, 50, function(){
						var obCounter = window['yaCounter' + idCounter];
						if(typeof obCounter == 'object'){
							obCounter.reachGoal(eventdata.goal);
						}
					});
				}
				catch(e)
				{
					console.error(e)
				}
			}
			else
			{
				console.info('Bad counter id!', idCounter);
			}
		}
	})

	/* show print */
	if(arDigitalOptions['THEME']['PRINT_BUTTON'] == 'Y')
	{
		setTimeout(function(){
			if($('.page-top .rss-block.top').length)
			{
				$('<div class="print-link"><i class="svg svg-print"></i></div>').insertBefore($('.page-top .rss-block.top .shares-block'));
			}
			else if($('.page-top .rss').length)
			{
				$('<div class="print-link"><i class="svg svg-print"></i></div>').insertAfter($('.page-top .rss'));
			}
			else if($('.page-top h1').length)
				$('<div class="print-link"><i class="svg svg-print"></i></div>').insertBefore($('.page-top h1'));
			// else
				// $('footer .print-block').html('<div class="print-link"><i class="svg svg-print"><svg id="Print.svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path class="cls-1" d="M1553,287h-2v3h-8v-3h-2a2,2,0,0,1-2-2v-5a2,2,0,0,1,2-2h2v-4h8v4h2a2,2,0,0,1,2,2v5A2,2,0,0,1,1553,287Zm-8,1h4v-4h-4v4Zm4-12h-4v2h4v-2Zm4,4h-12v5h2v-3h8v3h2v-5Z" transform="translate(-1539 -274)"/></svg></i></div>');
		},150);
	}

	$(document).on('click', '.print-link', function(){
		window.print();
	})

	$('.head-block .item-link').on('click', function(){
		var _this = $(this);
		_this.siblings().removeClass('active');
		_this.addClass('active');
	})

	$('table.table').each(function(){
		var _this = $(this),
			first_td = _this.find('thead tr th');
		if(!first_td.length)
			first_td = _this.find('thead tr td');
		if(first_td.length)
		{
			var j = 0;
			_this.find('tbody tr td').each(function(i){
				if(j > first_td.length-1)
					j = 0;

				$('<div class="th-mobile">'+first_td[j].textContent+'</div>').appendTo($(this));
				j++;
			})
		}
	})

	// menu marker
	$(document).on('mouseenter', 'table td .wrap > a', function(){
		/*$(this).stop(true, true);
		var itemParentPos = $(this).closest('td').position().left,
			itemPos = $(this).position().left,
			mainPos = itemParentPos + itemPos + parseInt($(this).css('padding-left'));
		$(this).closest('.mega-menu').find('.marker-nav').css('left', mainPos);
		$(this).closest('.mega-menu').find('.marker-nav').css('width', $(this).find('.line-wrapper').actual('outerWidth'));*/
	});

	$(document).on('mouseleave', '.mega-menu', function(){
		markerNav();
	});

	$('a.fancybox').fancybox();

	/* flex pagination */
	$('.flex-viewport .item').on('mouseenter', function(){
		$(this).closest('.flexslider').find('.flex-control-nav').toggleClass('noz');
		$(this).closest('.flexslider').find('.flex-control-nav').css('z-index','0');
	})
	$('.flex-viewport .item').on('mouseleave', function(){
		$(this).closest('.flexslider').find('.flex-control-nav').toggleClass('noz');
		$(this).closest('.flexslider').find('.flex-control-nav').css('z-index','2');
	})

	/* ajax more items */
	$(document).on('click', '.ajax_load_btn', function(){
		var url=$(this).closest('.bottom_nav').find('.module-pagination .flex-direction-nav .flex-next').attr('href'),
			th=$(this).find('.more_text_ajax');
		if(!th.hasClass('loading'))
		{
			th.addClass('loading');

			$.ajax({
				url: url,
				data: {"AJAX_REQUEST": "Y"},
				success: function(html){

					if($('.banners-small.front').length)
					{
						$('.banners-small .items.row').append(html);
						$('.bottom_nav').html($('.banners-small .items.row .bottom_nav').html());
						$('.banners-small .items.row .bottom_nav').remove();
					}
					else
					{
						$(html).insertBefore($('.blog .bottom_nav'));
						$('.bottom_nav').html($('.blog .bottom_nav:hidden').html());
						$('.blog .bottom_nav:hidden').remove();
					}

					var eventdata = {action:'ajaxContentLoaded'};
					BX.onCustomEvent('onCompleteAction', [eventdata, th[0]]);
					
					setTimeout(function(){
						$('.banners-small .item.normal-block').sliceHeight();
						th.removeClass('loading');
					}, 100);
				}
			})
		}
	})

	/* bug fix in ff*/
	$('img').removeAttr('draggable');

	clicked_tab = 0;

	$('.title-tab-heading').on('click', function(){
		var container = $(this).parent(),
			slide_block = $(this).next();

		clicked_tab = container.index()+1;

		container.siblings().removeClass('active');
		$('.catalog.detail .nav.nav-tabs li').removeClass('active');

		if(container.hasClass('active'))
		{
			slide_block.slideUp(200, function(){
				container.removeClass('active');
			});
		}
		else
		{
			container.addClass('active');
			slide_block.slideDown();
		}
	})

	// Responsive Menu Events
	var addActiveClass = false;
	$('#mainMenu li.dropdown > a > i, #mainMenu li.dropdown-submenu > a > i').on('click', function(e){
		e.preventDefault();
		if($(window).width() > 979) return;
		addActiveClass = $(this).closest('li').hasClass('resp-active');
		// $('#mainMenu').find('.resp-active').removeClass('resp-active');
		if(!addActiveClass){
			$(this).closest("li").addClass("resp-active");
		}else{
			$(this).closest("li").removeClass("resp-active");
		}
	});

	/*animate increment*/
	$('.spincrement').counterUp({
		delay: 80,
    	time: 1000
	});

	$('.bx_filter_input_container input[type=text]').numeric({allow:"."});

	/* search sync */
	$(document).on('keyup', '.search-input-div input', function(e){
		var inputValue = $(this).val();
		$('.search-input-div input').val(inputValue);

		if($(this).closest('#headerfixed').length)
		{
			if(e.keyCode == 13)
				$('.search form').submit();
		}
	});
	$(document).on('click', '.search-button-div button', function(e){
		if($(this).closest('#headerfixed').length)
			$('.search form').submit();
	});

	$('.inline-search-show, .inline-search-hide').on('click', function(e){
		if(window.matchMedia('(min-width: 600px)').matches)
		{
			if(typeof($(this).data('type_search')) != 'undefined' && $(this).data('type_search') == 'fixed')
				$('.inline-search-block').addClass('fixed');

			if(arDigitalOptions['THEME']['TYPE_SEARCH'] != 'fixed')
			{
				var height_block = 0;
				if(!$('header > .top-block').length)
				{
					height_block = $(this).closest('.maxwidth-theme').actual('outerHeight');
					if($(this).closest('.top-block').length)
						height_block = $(this).closest('.top-block').actual('outerHeight');
					else if($(this).closest('header.header-v8').length)
						height_block = $(this).closest('header.header-v8').actual('outerHeight');
					$('.inline-search-block').css({
						'height': height_block,
						'line-height': height_block-4+'px',
						'top': -height_block
					})
				}
			}

			$('.inline-search-block').toggleClass('show');
			if($('.top-block').length)
			{
				if($('.inline-search-block').hasClass('show'))
					$('.inline-search-block').css('background', $('.top-block').css('background-color'));
				else
					$('.inline-search-block').css('background', '#fff');
			}
			if(arDigitalOptions['THEME']['TYPE_SEARCH'] == 'fixed')
			{
				if($('.inline-search-block').hasClass('show'))
					$('<div class="jqmOverlay search"></div>').appendTo('body');
				else
					$('.jqmOverlay').detach();
			}
		}
		else
			location.href = arDigitalOptions['SITE_DIR'] + 'search/';
	})

	if($('.styled-block .row > div.col-md-3').length){
		BX.addCustomEvent('onWindowResize', function(eventdata) {
			try{
				ignoreResize.push(true);
				$('.styled-block .row > div.col-md-3').each(function() {
					$(this).css({'height': '', 'line-height': ''});
					var z = parseInt($('.body_media').css('top'));
					if(z > 0){
						var rowHeight = $(this).parents('.row').first().actual('outerHeight');
						$(this).css({'height': rowHeight + 'px', 'line-height' : rowHeight + 'px'});
					}
				});
			}
			catch(e){}
			finally{
				ignoreResize.pop();
			}
		});
	}

	if($('.order-block').length){
		BX.addCustomEvent('onWindowResize', function(eventdata) {
			try{
				ignoreResize.push(true);
				$('.order-block').each(function() {
					var cols = $(this).find('.row > div');
					if(cols.length){
						var colFirst = cols.first();
						var colLast = cols.last();
						var colText = colFirst.find('.text');
						var bText = colText.length;
						var bOnlyText = cols.length === 1 && bText;
						var bPrice = colFirst.find('.price').length;
						var z = parseInt($('.body_media').css('top'));

						cols.css({'height': '', 'padding-top': '', 'padding-bottom': ''});
						colText.css({'height': '', 'padding-top': '', 'padding-bottom': ''});
						if((!bPrice && z > 0) || (bPrice && z > 1)){
							var minHeight = 83;

							if(!bOnlyText){
								var colLast_height = colLast.outerHeight();
								colLast_height = colLast_height >= minHeight ? colLast_height : minHeight;
							}

							if(bText){
								var colFirst_height = colFirst.outerHeight();
								colFirst_height = colFirst_height >= minHeight ? colFirst_height : minHeight;
							}

							var colMax_height = (bText ? (!bOnlyText ? (colLast_height >= colFirst_height ? colLast_height : colFirst_height) : colLast_height) : colFirst_height);

							if(!bOnlyText){
								var textPadding = 41 + (colMax_height - colFirst.outerHeight()) / 2;
								colLast.find('.btns').css({'padding-top': textPadding + 'px', 'padding-bottom': textPadding + 'px', 'height': colMax_height + 'px'});
							}
							if(bText){
								colLast.css({'height': colMax_height + 'px'});
								var textPadding = 41 + (colMax_height - colText.outerHeight()) / 2;
								colText.css({'padding-top': textPadding + 'px', 'padding-bottom': textPadding + 'px', 'height': colMax_height + 'px'});
							}
						}
					}
				});
			}
			catch(e){}
			finally{
				ignoreResize.pop();
			}
		});
	}

	if($('.equal-padding').length)
	{
		BX.addCustomEvent('onWindowResize', function(eventdata){
			try{
				ignoreResize.push(true);
				$('.equal-padding').each(function() {
					$(this).find('.text').css({'padding-top': '0px', 'padding-bottom': '0px'});
					var equal_block = $(this).siblings('.equals'),
						height = $(this).actual('outerHeight');

					delta = Math.round((equal_block.actual('outerHeight') - height)/2);
					if(delta)
						$(this).find('.text').css({'padding-top': delta+'px', 'padding-bottom': delta+'px'});
				})
			}
			catch(e){}
			finally{
				ignoreResize.pop();
			}
		});
	}

	$(document).on('click', '.mega-menu .dropdown-menu', function(e){
		e.stopPropagation()
	});

	$(document).on('click', '.mega-menu .dropdown-toggle.more-items', function(e){
		e.preventDefault();
	});

	$('.table-menu .dropdown,.table-menu .dropdown-submenu,.table-menu .dropdown-toggle').on('mouseenter', function() {
		CheckTopVisibleMenu();
	});

	$('.mega-menu .search-item .search-icon, .menu-row #title-search .fa-close').on('click', function(e) {
		e.preventDefault();
		$('.menu-row #title-search').toggleClass('hide');
	});

	$('.mega-menu ul.nav .search input').on('keyup', function(e) {
		var inputValue = $(this).val();
		$('.menu-row > .search input').val(inputValue);
		if(e.keyCode == 13){
			$('.menu-row > .search form').submit();
		}
	});

	$('.menu-row > .search input').on('keyup', function(e) {
		var inputValue = $(this).val();
		$('.mega-menu ul.nav .search input').val(inputValue);
		if(e.keyCode == 13){
			$('.menu-row > .search form').submit();
		}
	});

	$('.mega-menu ul.nav .search button').on('click', function(e) {
		e.preventDefault();
		var inputValue = $(this).parents('.search').find('input').val();
		$('.menu-and-search .search input').val(inputValue);
		$('.menu-row > .search form').submit();
	});

	$('.filter .calendar').on('click', function() {
		var button = $(this).next();
		if(button.hasClass('calendar-icon')){
			button.trigger('click');
		}
	});

	/*sliceheights*/
	if($('.banners-small .item.normal-block').length)
		$('.banners-small .item.normal-block').sliceHeight();
	if($('.teasers .item').length)
		$('.teasers .item').sliceHeight();
	if($('.wrap-portfolio-front .row.items > div').length)
		$('.wrap-portfolio-front .row.items > div').sliceHeight({'row': '.row.items', 'item': '.item1'});

	SliceHeightBlocks();

	/* toggle */
	var $this = this,
		previewParClosedHeight = 25;

	$('section.toggle > label').prepend($('<i />').addClass('fa fa-plus'));
	$('section.toggle > label').prepend($('<i />').addClass('fa fa-minus'));
	$('section.toggle.active > p').addClass('preview-active');
	$('section.toggle.active > div.toggle-content').slideDown(350, function() {});

	$('section.toggle > label').click(function(e){
		var parentSection = $(this).parent(),
			parentWrapper = $(this).parents('div.toogle'),
			previewPar = false,
			isAccordion = parentWrapper.hasClass('toogle-accordion');

		if(isAccordion && typeof(e.originalEvent) != 'undefined') {
			parentWrapper.find('section.toggle.active > label').trigger('click');
		}

		parentSection.toggleClass('active');

		// Preview Paragraph
		if( parentSection.find('> p').get(0) ){
			previewPar = parentSection.find('> p');
			var previewParCurrentHeight = previewPar.css('height');
			previewPar.css('height', 'auto');
			var previewParAnimateHeight = previewPar.css('height');
			previewPar.css('height', previewParCurrentHeight);
		}

		// Content
		var toggleContent = parentSection.find('> div.toggle-content');

		if( parentSection.hasClass('active') ){
			$(previewPar).animate({
				height: previewParAnimateHeight
			}, 350, function() {
				$(this).addClass('preview-active');
			});
			toggleContent.slideDown(350, function() {});
		}
		else{
			$(previewPar).animate({
				height: previewParClosedHeight
			}, 350, function() {
				$(this).removeClass('preview-active');
			});
			toggleContent.slideUp(350, function() {});
		}
	});

	/* accordion */
	$('.accordion-head').on('click', function(e){
		e.preventDefault();
		if(!$(this).next().hasClass('collapsing')){
			$(this).toggleClass('accordion-open');
			$(this).toggleClass('accordion-close');
		}
	});

	/* progress bar */
	$('[data-appear-progress-animation]').each(function(){
		var $this = $(this);
		$this.appear(function(){
			var delay = ($this.attr('data-appear-animation-delay') ? $this.attr('data-appear-animation-delay') : 1);
			if( delay > 1 )
				$this.css('animation-delay', delay + 'ms');
			$this.addClass($this.attr('data-appear-animation'));

			setTimeout(function(){
				$this.animate({
					width: $this.attr('data-appear-progress-animation')
				}, 1500, 'easeOutQuad', function() {
					$this.find('.progress-bar-tooltip').animate({
						opacity: 1
					}, 500, 'easeOutQuad');
				});
			}, delay);
		}, {accX: 0, accY: -50});
	});

	/* portfolio item */
	$('.item.animated-block').appear(function(){
		var $this = $(this);

		$this.addClass($this.data('animation')).addClass('visible');

	}, {accX: 0, accY: 150})

	$('a[rel=tooltip]').tooltip();
	$('span[data-toggle=tooltip]').tooltip();

	$('select.sort').on('change', function(){
		location.href = $(this).val();
	});

	setTimeout(function(th){
		$('.catalog.group.list .item').each(function(){
			var th = $(this);
			if((tmp = th.find('.image').outerHeight() - th.find('.text_info').outerHeight()) > 0){
				th.find('.text_info .titles').height(th.find('.text_info .titles').outerHeight() + tmp);
			}
		})
	}, 50);

	/* ajax tabs*/
	$('.tabs_ajax .head-block .item-link').on('click', function(){
		var index = $(this).index(),
			body_block = $(this).closest('.tabs_ajax').find('.body-block'),
			obQuery = parseUrlQuery(),
			url_post = arDigitalOptions['SITE_DIR'] + 'include/mainpage/comp_catalog_ajax.php';
		$(this).siblings().removeClass('active');
		$(this).addClass('active');

		if('clear_cache' in obQuery)
			url_post += '?clear_cache='+obQuery.clear_cache;

		if(!$(this).hasClass('clicked'))
		{
			$.ajax({
				url: url_post,
				type: 'POST',
				data: {AJAX_POST: 'Y', AJAX_PARAMS: $(this).closest('.item-views.catalog').find('.request-data').data('value'), GLOBAL_FILTER: body_block.find('.item-block:eq('+index+')').data('filter')},
			}).success(function(html){
				body_block.find('.item-block:eq('+index+')').html(html);

				InitFlexSliderClass(body_block.find('.item-block:eq('+index+')').find('.flexslider')); //reinit flexslider

				body_block.css('height', body_block.find('.item-block.active').actual('outerHeight'));

				body_block.find('.item-block').removeClass('active').removeClass('opacity1').addClass('opacity0');
				body_block.find('.item-block:eq('+index+')').addClass('active');

				setTimeout(function(){
					body_block.css('height', 'auto');

					//recalculate height
					body_block.find('.item-block:eq('+index+') .catalog.item-views.table .item .title').sliceHeight();
					body_block.find('.item-block:eq('+index+') .catalog.item-views.table .item .cont').sliceHeight();
					body_block.find('.item-block:eq('+index+') .catalog.item-views.table .item .slice_price').sliceHeight();
					body_block.find('.item-block:eq('+index+') .catalog.item-views.table .item').sliceHeight({classNull: '.footer-button'});

					body_block.find('.item-block:eq('+index+')').removeClass('opacity0').addClass('opacity1');
				},100)
			});
		}
		else
		{
			body_block.find('.item-block').removeClass('active').removeClass('opacity1').addClass('opacity0');
			body_block.find('.item-block:eq('+index+')').addClass('active').removeClass('opacity0').addClass('opacity1');
		}
		$(this).addClass('clicked');
	})

	/*item galery*/
	$('.thumbs .item a').on('click', function(e){
		e.preventDefault();
		$('.thumbs .item').removeClass('current');
		$(this).closest('.item').toggleClass('current');
		$('.slides li' + $(this).attr('href')).addClass('current').siblings().removeClass('current');
	});

	$('header.fixed .btn-responsive-nav').on('click', function() {
		$('html, body').animate({scrollTop: 0}, 400);
	});

	$('body').on('click', '.form .refresh-page', function(){
		location.href = location.href;
	});


	
	$(document).on('click', '.basket.fly .opener', function(){
		if(window.matchMedia('(max-width: 767px)').matches)
			location.href = arDigitalOptions['THEME']['URL_BASKET_SECTION'];
		else
			$(this).closest('.ajax_basket').toggleClass('opened');
	})
	
	$(document).on('click', '.basket.fly .close_block', function()
	{
		$('.basket.fly .opener').trigger('click');
	})

	/* animated labels */
	$(document).on("focus", ".animated-labels input,.animated-labels textarea", function(){
		$(this).closest(".animated-labels").addClass("input-filed");
	}).on("focusout", ".animated-labels input,.animated-labels textarea", function(){
		if("" != $(this).val())
			$(this).closest(".animated-labels").addClass("input-filed");
		else
			$(this).closest(".animated-labels").removeClass("input-filed");
	})

	/* accordion action*/
	$('.panel-collapse').on('hidden.bs.collapse', function(){
		$(this).parent().toggleClass('opened');
	})
	$('.panel-collapse').on('show.bs.collapse', function(){
		$(this).parent().toggleClass('opened');
	})

	// DIGITAL BASKET
	// - basket fly close
	$(document).on('click', function(){
		if($('.basket.fly').length && $('.ajax_basket').hasClass('opened')){
			$('.ajax_basket').removeClass('opened');
		}
	});

	$(document).on('click', '.basket.fly', function(e){
		e.stopPropagation();
	});

	// - COUNTER
	var timerBasketCounter = false;

	// -- keyup input
	$(document).on('keydown', '.count', function(e){
		// Allow: backspace, delete, tab, escape, enter and .
		if($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			 // Allow: Ctrl+A, Command+A
			(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
			 // Allow: home, end, left, right, down, up
			(e.keyCode >= 35 && e.keyCode <= 40)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)){
			e.preventDefault();
		}
	});
	$(document).on('keyup', '.count', function(e){
		var $this = $(this),
			counterInputValueNew = $this.val(),
			price = $this.closest('.item').find('input[name=PRICE]').val();

		Summ($this, counterInputValueNew, price);
	});

	// -- scroll after apply option
	if($('.instagram_ajax').length)
	{
		BX.addCustomEvent('onCompleteAction', function(eventdata){
			if(eventdata.action === 'instagrammLoaded')
				scrollPreviewBlock();
		});
	}
	else
		scrollPreviewBlock();

	$('select.region').on('change', function(){
		var val = parseInt($(this).val());
		if($('select.city').length)
		{
			if(val)
			{
				$('select.city').removeAttr('disabled');
				$('select.city option').hide();
				$('select.city option[data-parent_section='+val+']').show();
			}
			else
				$('select.city').attr('disabled', 'disabled');
		}
	})

	$('select.city, select.region').on('change', function(){
		var _this = $(this),
			val = parseInt(_this.val());
		if(_this.hasClass('region'))
		{
			$('select.city option:eq(0)').show();
			$('select.city').val(0);
		}

		if((_this.hasClass('region') && !val) || _this.hasClass('city'))
		{
			$.ajax({
				type: 'POST',
				data: {ID: val},
			}).success(function(html){
				var ob = BX.processHTML(html);
				$('.ajax_items')[0].innerHTML = ob.HTML;
				BX.ajax.processScripts(ob.SCRIPT);
			})
		}
	})

	// -- blur input
	$(document).on('blur', '.count', function(){
		BasketCounter($(this));
	});

	// -- click minus, plus button
	$(document).on('click', '.minus, .plus', function(e){
		e.stopPropagation();
		BasketCounter($(this));
	});

	// - Add2Basket
	$(document).on('click', '.to_cart', function(e){
		e.stopPropagation();
		var item = $(this).closest('[data-item]'),
			_this = $(this),
			itemData = item.data('item'),
			buyBlock = item.find('.buy_block'),
			counter = buyBlock.find('.counter'),
			buttonToCart = buyBlock.find('.to_cart'),
			itemQuantity = parseFloat(buttonToCart.data('quantity')),
			countItem = ($('.basket').length ? parseInt($('.basket .count').text()) : parseInt($('.basket_top:visible .count').text()));

		$('.basket_top .count').text(countItem + 1).removeClass('empted');
		$('.basket .count').text(countItem + 1).removeClass('empted');

		if(typeof(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined')
		{
			if($.trim(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) === 'HEADER' && $('.basket_top').length)
				var bBasketTop = true;
			else if($('.basket.fly').length)
				var bBasketFly = true;
		}

		if(isNaN(itemQuantity) || itemQuantity <= 0){
			itemQuantity = 1;
		}

		if(!isNaN(itemData.ID) && parseInt(itemData.ID) > 0){
			buyBlock.addClass('in');

			$.ajax({
				url: arDigitalOptions['SITE_DIR'] + 'include/footer/site-basket.php',
				type: 'POST',
				data: {itemData: itemData, quantity: itemQuantity},
			}).success(function(html){
				var eventdata = {action:'loadForm'};
				BX.onCustomEvent('onCompleteAction', [eventdata, _this[0]]);

				if(bBasketTop){
					$('.ajax_basket').html(html);
					var eventdata = {action:'loadBasket'};
					BX.onCustomEvent('onCompleteAction', [eventdata, $('.ajax_basket')[0]]);
				}

				if(bBasketFly){
					if($('.basket.fly').length){
						$('.ajax_basket').html(html);
						setTimeout(function(){
							if(!$('.ajax_basket').hasClass('opened')){
								$('.ajax_basket').addClass('opened');
							}
						}, 50);
					}
				}

				if(arDigitalOptions['THEME']['USE_SALE_GOALS'] != 'N')
				{
					var eventdata = {goal: 'goal_basket_add', params: {itemData: itemData, quantity: itemQuantity}};
					BX.onCustomEvent('onCounterGoals', [eventdata]);
				}
			});
		}
		else{
			return;
		}
	});

	// - Remove9Basket
	$(document).on('click', '.remove', function(){
		var item = $(this).closest('[data-item]'),
			itemData = item.data('item'),
			bRemove = 'Y',
			bRemoveAll = ($.trim($(this).closest('[data-remove_all]').data('remove_all')) === 'Y' ? 'Y' : false);
			getCurUri = $.trim($('input[name=getPageUri]').val()),
			countItem = ($('.basket').length ? parseInt($('.basket .item').length) : parseInt($('.basket_top:visible .item').length)),
			bOneItem = (countItem - 1 <= 0),
			scrollTop = ($('.basket.fly').length ? $('.basket.fly .items_wrap').scrollTop() : ($('.basket_top:visible').length ? $('.basket_top .items:visible').scrollTop() : ''));

		var _ajax = function(){
			$.ajax({
				url: arDigitalOptions['SITE_DIR'] + 'include/footer/site-basket.php',
				data: {itemData: itemData, remove: bRemove, removeAll: bRemoveAll},
			}).success(function(html){

				if(bBasketTop){
					$('.ajax_basket').html(html);
					$('.basket_top .items').scrollTop(scrollTop);
				}

				if(getCurUri){
					$.ajax({
						url: getCurUri,
						type: 'POST',
					}).success(function(html){
						if($('.basket.default').length){
							$('.basket.default').html(html);
						}
					});
				}

				if(bBasketFly){
					$('.ajax_basket').html(html);
					$('.ajax_basket').addClass('opened');
					$('.basket.fly .items_wrap').scrollTop(scrollTop);
				}

				if(arDigitalOptions['THEME']['USE_SALE_GOALS'] != 'N')
				{
					var eventdata = {goal: 'goal_basket_remove', params: {itemData: itemData, remove: bRemove, removeAll: bRemoveAll}};
					BX.onCustomEvent('onCounterGoals', [eventdata]);
				}
			});
		}

		if(typeof(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined')
		{
			if($.trim(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) === 'HEADER' && $('.basket_top').length)
				var bBasketTop = true;
			else if($('.basket.fly').length)
				var bBasketFly = true;
		}

		if(typeof(itemData) !== 'undefined' && (!isNaN(itemData.ID) && itemData.ID > 0) || bRemoveAll){
			if(bRemoveAll){
				$('.buy_block').removeClass('in');
				$('.basket .count').text(0).addClass('empted');
				$('.basket_top .count').text(0).addClass('empted');
				$('#mobilemenu .menu .ready .count').text(0).addClass('empted');
			}
			else{
				$('[data-item]').each(function(){
					if($(this).data('item').ID == itemData.ID){
						$(this).find('.buy_block').removeClass('in');
					}
				});
				if($('.basket').length){
					if($('.basket_top .count').length)
						$('.basket_top .count').text(parseFloat($('.basket_top:visible .count').text()) - 1);
					else
					{
						$('.basket .count').text(parseFloat($('.basket .count').text()) - 1);
						$('.basket_top .count').text(parseFloat($('.basket .count').text()) - 1);
					}
				}
				else{
					$('.basket_top .count').text(parseFloat($('.basket_top:visible .count').text()) - 1);
				}

				$('#mobilemenu .menu .ready .count').text(parseFloat($('#mobilemenu .menu .ready .count').text()) - 1);
			}

			if(bOneItem && !bRemoveAll){
				if(item.closest('.basket_top').length){
					item.closest('.dropdown').animate({opacity: 0}, 200, function(){
						_ajax();
					});
				}
				else{
					item.closest('.basket').find('.count').addClass('empted');
					item.closest('.basket_wrap').fadeOut(200, function(){
						item.closest('.basket').find('.basket_empty').fadeIn(200, function(){
							_ajax();
						});
					});
				}
			}
			else if(bRemoveAll){
				$('.basket_wrap').fadeOut(200, function(){
					$('.remove.all').remove();
					$('.basket').find('.basket_empty').fadeIn(200, function(){
						_ajax();
					});
				});
			}
			else if(!bOneItem){
				item.animate({opacity: 0}, 200).slideUp(200, function(){
					_ajax();
				});
			}
		}
		else{
			return;
		}
	});
	$(document).on('click', '.print', function(){
		window.print();
	});

	$('.choise').on('click', function(){
		var _this = $(this);
		if(typeof(_this.data('block')) != 'undefined')
		{
			scrollToBlock(_this.data('block'));
		}
	})
});

scrollPreviewBlock = function(){
	if(typeof($.cookie('scoll_block')) != 'undefined' && $.cookie('scoll_block'))
	{
		var scoll_block = $($.cookie('scoll_block'));
		if(scoll_block.length)
		{
			$('body, html').animate({scrollTop: scoll_block.offset().top}, 500);
			$.cookie('scoll_block', null);
		}
	}
}

scrollToBlock = function(block){
	if($(block).length)
	{
		var offset = $(block).offset().top;
		if(typeof($(block).data('toggle')) != 'undefined')
			$(block).click();

		if(typeof($(block).data('offset')) != 'undefined')
			offset += $(block).data('offset');
		$('body, html').animate({scrollTop: offset}, 500);
	}
}

// START VIDEO BUTTON
$(document).on('click', '.banners-big .item .btn-video', function(){
	$(this).addClass('loading');
	$(this).closest('.item').addClass('loading');
	startMainBannerSlideVideo($(this).closest('.item'));
});

//DIGITAL BASKET
function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function(n, prec){
		var k = Math.pow(10, prec);
		return '' + (Math.round(n*k)/k).toFixed(prec);
	};

	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}

	if ((s[1] || '')
		.length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}

	return s.join(dec);
}


setBasketItemsClasses = function(){
	if(typeof(arBasketItems) !== 'undefined' && typeof(arBasketItems) !== 'string'){
		if(Object.keys(arBasketItems).length){
			for(var key in arBasketItems){
				$('[data-item]').each(function(){
					if($(this).data('item').ID == key){
						$(this).find('.buy_block').addClass('in');
					}
				});
			}
		}
	}
}

function Summ(el, counterInputValueNew, price){
	if(counterInputValueNew <= 0){
		counterInputValueNew = 1;
	}

	var summ = number_format(counterInputValueNew*price, 0, '.', ' '),
		allSumm = 0;

	el.closest('.items').find('.item').each(function(){
		var $this = $(this),
			price = parseFloat($this.find('input[name=PRICE]').val()),
			count =  parseFloat($this.find('input.count').val());

		if(count <= 0){
			count = 1;
		}

		if(!isNaN(price) && !isNaN(count)){
			allSumm += count*price;
		}
	});

	allSumm = number_format(parseFloat(allSumm), 0, '.', ' ');

	el.closest('.item').find('.summ .price_val').text(summ);
	el.closest('.basket').find('.foot .total>span').text(allSumm);
}

var timerBasketUpdate = false;
// - COUNTER
BasketCounter = function(el){
	var elClass = $.trim(el.attr('class')),
		bClassMinus = (elClass.indexOf('minus') > -1),
		bClassPlus = (elClass.indexOf('plus') > -1),
		bClassCount = (elClass.indexOf('count') > -1),
		getCurUri = $.trim($('input[name=getPageUri]').val()),
		buyBlock = el.closest('.buy_block'),
		buttonToCart = buyBlock.find('.to_cart'),
		counterInput = el.closest('.counter').find('input.count'),
		counterInputValue = parseFloat($.trim(counterInput.val())),
		price = parseFloat(buyBlock.find('input[name=PRICE]').val()),
		counterInputMaxCount = Math.pow(10, parseInt(counterInput.attr('maxlength'))) - 1;

	// class minus button
	if(bClassMinus){
		var counterInputValueNew = counterInputValue - 1;

		if(counterInputValueNew <= 0){
			counterInputValueNew = 1;
		}

		counterInput.val(counterInputValueNew);

		Summ(el, counterInputValueNew, price);

		if(timerBasketUpdate){
			clearTimeout(timerBasketUpdate);
			timerBasketUpdate = false;
		}

		timerBasketUpdate = setTimeout(function(){
			BasketUpdate(el, counterInputValueNew);
			timerBasketUpdate = false;
		}, 700);
	}
	// class plus button
	else if(bClassPlus){
		var counterInputValueNew = counterInputValue + 1;

		if(counterInputValueNew > counterInputMaxCount){
			counterInputValueNew = counterInputMaxCount;
		}

		counterInput.val(counterInputValueNew);
		Summ(el, counterInputValueNew, price);

		if(timerBasketUpdate){
			clearTimeout(timerBasketUpdate);
			timerBasketUpdate = false;
		}

		timerBasketUpdate = setTimeout(function(){
			BasketUpdate(el, counterInputValueNew);
			timerBasketUpdate = false;
		}, 700);
	}
	// class input
	else if(bClassCount){
		var counterInputValueNew = counterInputValue;

		if(counterInputValueNew <= 0 || isNaN(counterInputValueNew)){
			counterInputValueNew = 1;
		}
		el.val(counterInputValueNew);
		BasketUpdate(el, counterInputValueNew);
	}

	if(!getCurUri && !el.closest('.basket.fly').length){
		buttonToCart.data('quantity', counterInputValueNew);
	}
}

BasketUpdate = function(el, counterInputValueNew){
	var	itemData = el.closest('[data-item]').data('item'),
		itemData = (typeof(arBasketItems) === 'object' && typeof(arBasketItems[itemData.ID]) === 'object' ? arBasketItems[itemData.ID] : itemData),
		buyBlock = el.closest('.buy_block'),
		buttonToCart = buyBlock.find('.to_cart'),
		getCurUri = $.trim($('input[name=getPageUri]').val())
		scrollTop = ($('.basket.fly').length ? $('.basket.fly .items_wrap').scrollTop() : ($('.basket_top:visible').length ? $('.basket_top .items:visible').scrollTop() : ''));

	if(typeof(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && $.trim(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) === 'FLY' && $('.basket.fly').length){
		var bBasketFly = true;
	}

	if(typeof(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && $.trim(arDigitalOptions['THEME']['ORDER_BASKET_VIEW']) === 'HEADER' && $('.basket_top').length){
		var bBasketTop = true;
	}

	else{
		if(typeof(itemData) != 'undefined' && !isNaN(itemData.ID) && itemData.ID > 0){
			$.ajax({
				// url: arDigitalOptions['SITE_DIR'] + 'ajax/basket_items.php',
				url: arDigitalOptions['SITE_DIR'] + 'include/footer/site-basket.php',
				data: {itemData: itemData, quantity: counterInputValueNew},
			}).success(function(data){
				if(typeof(data) === 'object'){
					arBasketItems = data;
				}
				if(bBasketTop){
					$.ajax({
						url: arDigitalOptions['SITE_DIR'] + 'include/footer/site-basket.php',
						type: 'POST',
						data: {'ajaxPost': 'Y'},
					}).success(function(html){
						buyBlock.removeClass('in');
						$('.ajax_basket').html(html);

						/*if(!getCurUri){
							setTimeout(function(){
								$('.basket_top .dropdown').addClass('expanded');
							}, 100);

							setTimeout(function(){
								$('.basket_top .dropdown').removeClass('expanded');
							}, 1000);
						}*/
					});
				}

				if(bBasketFly){
					$.ajax({
						url: arDigitalOptions['SITE_DIR'] + 'include/footer/site-basket.php',
						type: 'POST',
						data: {'ajaxPost': 'Y'},
					}).success(function(html){
						if($('.basket.fly').length){
							$('.ajax_basket').html(html);
							$('.basket.fly .items_wrap').scrollTop(scrollTop);
						}
					});
				}

				if(getCurUri){
					$.ajax({
						url: getCurUri,
						type: 'POST',
					}).success(function(html){
						if($('.basket.default').length){
							$('.basket.default').html(html);
						}
					});
				}
			});
		}
		else{
			return;
		}
	}
}

CheckTabActive = function(){
	if(typeof(clicked_tab) && clicked_tab)
	{
		if(window.matchMedia('(min-width: 768px)').matches)
		{
			clicked_tab--;
			$('.catalog.detail .nav.nav-tabs li:eq('+clicked_tab+')').addClass('active');
			$('.catalog.detail .tab-content .tab-pane:eq('+clicked_tab+')').addClass('active');
			$('.catalog.detail .tab-content .tab-pane .title-tab-heading').next().removeAttr('style');
			clicked_tab = 0;
		}
	}
}

/* menu marker */
markerNav= function(){
	/*if($('table td.active .wrap > a').length > 0)
	{
		$('table td.active .wrap > a').each(function(){
			var item = $(this),
				itemParentPos = item.closest('td').position().left,
				itemPos = item.position().left,
				mainPos = itemParentPos + itemPos + parseInt(item.css('padding-left'))
				nav = item.closest('.mega-menu').find('.marker-nav');

			nav.css('left', mainPos);
			if(window.matchMedia('(min-width: 992px)').matches)
				nav.css('width', item.find('.line-wrapper').actual('outerWidth'));
		})
	}
	else
		$('.marker-nav').css('width', 0);

	if(!$('.marker-nav').hasClass('opacity1'))
	{
		setTimeout(function(){
			$('.marker-nav').addClass('opacity1');
		}, 100);
	}*/
}

/* parallax bg */
ParallaxBg = function(){
	if($('*[data-type=parallax-bg]').length)
	{
		var x = $(window).scrollTop()/$(document).height();
		x=parseInt(-x * 280);
		$('*[data-type=parallax-bg]').stop().animate({'background-position-y':  x + 'px'}, 400, 'swing');
	}
}
SetFixedAskBlock = function(){
	if($('.ask_a_question_wrapper').length)
	{
		var offset = $('.ask_a_question_wrapper').offset(),
			block = $('.ask_a_question_wrapper').find('.ask_a_question'),
			block_offset = BX.pos(block[0]),
			block_height = block_offset.bottom-block_offset.top,
			diff_top_scroll = $('#headerfixed').height() + 20;

		if(/*offset.top+*/block_height+130 > block.closest('.fixed_wrapper').height())
			block.addClass('nonfixed');
		else
			block.removeClass('nonfixed');

		if(block_height+diff_top_scroll+documentScrollTopLast + 130 > $('footer').offset().top)
		{
			block.removeClass('fixed').css({'top': 'auto', 'width': 'auto', 'bottom': 0});
			block.parent().css('position', 'static');
			block.parent().parent().css('position', 'static');
		}
		else
		{
			block.parent().removeAttr('style');
			block.parent().parent().removeAttr('style');

			if(documentScrollTopLast + diff_top_scroll > offset.top)
				block.addClass('fixed').css({'top': diff_top_scroll, 'bottom': 'auto', 'width': $('.fixed_block_fix').width()});
			else
				block.removeClass('fixed').css({'top': 0, 'width': 'auto'});
		}
	}
}


// Events
var timerScroll = false, ignoreScroll = [], documentScrollTopLast = $(document).scrollTop();
$(window).scroll(function(){
	CheckPopupTop();
	SetFixedAskBlock();
	if(!ignoreScroll.length){
		if(timerScroll){
			clearTimeout(timerScroll);
			timerScroll = false;
		}
		timerScroll = setTimeout(function(){
			BX.onCustomEvent('onWindowScroll', false);
		}, 100);
	}
	documentScrollTopLast = $(document).scrollTop();
});

var timerResize = false, ignoreResize = [];

$(window).resize(function(){
	CheckPopupTop();
	CheckScrollToTop();
	if(!ignoreResize.length){
		if(timerResize){
			clearTimeout(timerResize);
			timerResize = false;
		}
		timerResize = setTimeout(function(){
			BX.onCustomEvent('onWindowResize', false);
		}, 100);
	}
	documentScrollTopLast = $(document).scrollTop();
});

BX.addCustomEvent('onWindowScroll', function(eventdata) {
	try{
		ignoreScroll.push(true);
		ParallaxBg();

		if(arDigitalOptions['THEME']['TYPE_SEARCH'] != 'fixed')
		{
			if(!$('header > .top-block').length)
			{
				var height_block = 0,
					scrollVal = $(window).scrollTop();
				height_block = $('.logo_and_menu-row').actual('outerHeight');
				if(!scrollVal)
				{
					$('.inline-search-block').css({
						'height': height_block,
						'line-height': height_block-4+'px',
						'top': -height_block
					})
				}
			}
		}

	}
	catch(e){}
	finally{
		ignoreScroll.pop();
	}
});

BX.addCustomEvent('onWindowResize', function(eventdata) {
	try{
		ignoreResize.push(true);

		CheckHeaderFixedMenu();
		CheckTopMenuDotted();
		CheckTopVisibleMenu();
		CheckFlexSlider();
		CheckMainBannerSliderVText($('.banners-big .flexslider'));
		CheckObjectsSizes();
		verticalAlign();
		CheckTabActive();
		setTimeout(function(){
			createTableCompare($('.main-block .items .title-block:not(.clone) .item'), $('.prop_title_table'), $('.main-block .prop_title_table .item.clone'));
		}, 100);
		SliceHeightBlocks();
	}
	catch(e){}
	finally{
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onSlide', function(eventdata) {
	try{
		ignoreResize.push(true);
		if(eventdata){
			var slider = eventdata.slider;
			if(slider){
				// add classes .curent & .shown to slide
				slider.find('.item').removeClass('current');
				var curSlide = slider.find('.item.flex-active-slide');
				var curSlideId = curSlide.attr('id');
				curSlide.addClass('current');

				slider.find('.item').css('opacity', '1');

				if(curSlide.hasClass('shown')){
					slider.find('.item.clone[id=' + curSlideId + '_clone]').addClass('shown');
				}

				curSlide.addClass('shown');
				slider.resize();

				// set main banners text vertical center
				CheckMainBannerSliderVText(slider);

				//video
				var videoAutoPlay = curSlide.attr('data-video_autoplay')
				var bVideoAutoPlay = videoAutoPlay == 1
				
				if(typeof(window["players"]) == 'object' && curSlide.length) // pause video
				{
					for(var j in window["players"])
					{
						var curVideoSlide = $('iframe[id='+window["players"][j].id+']').closest('.item'),
							videoPlayer = curVideoSlide.attr('data-video_player'),
							bVideoPlayerYoutube = videoPlayer === 'YOUTUBE',
							bVideoPlayerVimeo = videoPlayer === 'VIMEO';
						
						if(!curVideoSlide.hasClass('current') && bVideoPlayerYoutube)
						{
							window[window["players"][j].id].pauseVideo();
						}
					}
				}
				
				if(bVideoAutoPlay){
					startMainBannerSlideVideo(curSlide)
				}
				if(!slider.find('.flex-control-nav li').length && slider.hasClass('normal'))
				{
					slider.find('.flex-direction-nav li a').addClass('flex-disabled');
				}

				if(!slider.hasClass('flexslider-init-slice') && slider.hasClass('nav-title') && $('.gallery-block').closest('.tab-pane').hasClass('active'))
				{
					slider.find('.item').sliceHeight({'lineheight': -3});
					slider.addClass('flexslider-init-slice');
				}
				if(slider.find('.flex-direction-nav').length){
					if(slider.find('.flex-direction-nav').find('a.flex-disabled').length)
						slider.find('.flex-direction-nav').removeClass('opacity1').addClass('opacity0');
					else
						slider.find('.flex-direction-nav').removeClass('opacity0').addClass('opacity1');
				}
			}
		}
	}
	catch(e){}
	finally{
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onSlideEnd', function(eventdata) {
	try{
		ignoreResize.push(true);
		if(eventdata){
			var slider = eventdata.slider;
			if(slider){
				
			}
		}
	}
	catch(e){}
	finally{
		ignoreResize.pop();
	}
});

$(window).resize(function(){
	CheckTopMenuPadding();
	CheckTopMenuOncePadding();
	CheckSearchWidth();
});