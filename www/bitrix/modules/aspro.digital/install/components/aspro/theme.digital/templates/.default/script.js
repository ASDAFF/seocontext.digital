$(document).ready(function() {
	$('.style-switcher .item input[type=checkbox]').on('change', function(){
		var _this =  $(this);
		if(_this.is(':checked'))
			_this.val('Y');
		else
			_this.val('N');
		$('form[name=style-switcher]').submit();
	})

	new DG.OnOffSwitchAuto({
        cls:'.custom-switch',
        textOn:"",
        height:33,
        heightTrack:16,
        textOff:"",
        trackColorOff:"f5f5f5",
        listener:function(name, checked){
        	var bNested = $('input[name='+name+']').closest('.values').length;
        	if(checked)
				$('input[name='+name+']').val('Y');
        	
			else
				$('input[name='+name+']').val('N');
			
			if(bNested)
			{
				var ajax_btn = $('<div class="btn-ajax-block animation-opacity"></div>'),
					option_wrapper = $('input[name='+name+']').closest('.option-wrapper'),
					pos = BX.pos(option_wrapper[0], true),
					top = 0;
				ajax_btn.html($('.values > .apply-block').html());
				option_wrapper.toggleClass('disabled');
				top = pos.top+$('.style-switcher .header').actual('outerHeight');
				ajax_btn.css('top',top);
				if($('.btn-ajax-block').length)
					$('.btn-ajax-block').remove();
				ajax_btn.appendTo($('.style-switcher'));
				ajax_btn.addClass('opacity1');
			}

			setTimeout(function(){
				if(!bNested)
					$('form[name=style-switcher]').submit();
			},200);
        }
    });


	if($.cookie('styleSwitcher') == 'open')
		$('.style-switcher').addClass('active');

	if($('.base_color_custom input[type=hidden]').length)
	{
		$('.base_color_custom input[type=hidden]').each(function(){
			var _this = $(this),
				parent = $(this).closest('.base_color_custom');
			_this.spectrum({
				preferredFormat: 'hex',
				showButtons: true,
				showInput: true,
				showPalette: false,
				appendTo: parent,
				chooseText: BX.message('CUSTOM_COLOR_CHOOSE'),
				cancelText: BX.message('CUSTOM_COLOR_CANCEL'),
				containerClassName: 'custom_picker_container',
				replacerClassName: 'custom_picker_replacer',
				clickoutFiresChange: false,
				move: function(color) {
					var colorCode = color.toHexString();
					parent.find('a span').attr('style', 'background:' + colorCode);
				},
				hide: function(color) {
					var colorCode = color.toHexString();
					parent.find('a span').attr('style', 'background:' + colorCode);
				},
				change: function(color) {
					parent.addClass('current').siblings().removeClass('current');

					$('form[name=style-switcher] input[name=' + parent.find('a').data('option-id') + ']').val(parent.find('a').data('option-value'));
					$('form[name=style-switcher]').submit();
				}
			});
		})
	}

	$('.base_color_custom').click(function(e) {
		e.preventDefault();
		$('#custom_picker').spectrum('toggle');
		return false;
	});

	var curcolor = $('.base_color.current').data('color');
	if(curcolor != undefined && curcolor.length)
	{
		$('#custom_picker').spectrum('set', curcolor);
		$('.base_color_custom a span').attr('style', 'background:' + curcolor);
	}

	$('.style-switcher .switch').click(function(e){
		e.preventDefault();
		var styleswitcher = $(this).closest('.style-switcher');
		
		HideHintBlock();

		if(styleswitcher.hasClass('active')){
			styleswitcher.addClass('closes');
			setTimeout(function(){
				styleswitcher.removeClass('active');
			},500)
			$.removeCookie('styleSwitcher', {path: '/'});
		}
		else{
			ShowOverlay();
			styleswitcher.removeClass('closes').addClass('active');
			$.cookie('styleSwitcher', 'open', {path: '/'});
		}
	});

	HideHintBlock = function()
	{
		HideOverlay();
		$.cookie('clickedSwitcher', 'Y', {path: '/'});
		if($('.hint-theme').length)
		{
			$('.hint-theme').fadeIn(300, function(){
				$('.hint-theme').remove();
			});
		}
	}

	$(document).on('click', '.close-overlay', function(){
		HideHintBlock()
	})

	$(document).on('click', '.jqmOverlay', function(){
		var styleswitcher = $('.style-switcher');
		if(!$('.hint-theme').length)
			HideOverlay();
		styleswitcher.each(function(){
			var _this = $(this);
			_this.addClass('closes');
			setTimeout(function(){
				_this.removeClass('active');
			},500);
			$('.form_demo-switcher').animate({left: '-' + $('.form_demo-switcher').outerWidth() + 'px'}, 100).removeClass('active abs');
		})
		$.removeCookie('styleSwitcher', {path: '/'});
	})

	$('.style-switcher .section-block').on('click', function(){
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$('.style-switcher .right-block .block-item').removeClass('active');
		$('.style-switcher .right-block .block-item:eq('+$(this).index()+')').addClass('active');
		$.cookie('styleSwitcherTabIndex', $(this).index(), {path: '/'});
	})

	$('.style-switcher .reset').click(function(e){
		$('form[name=style-switcher]').append('<input type="hidden" name="THEME" value="default" />');
		$('form[name=style-switcher]').submit();
	});

	$(document).on('click', '.style-switcher .apply', function(){
		$('form[name=style-switcher]').submit();
	})
	$('.style-switcher .sup-params.options .block-title').click(function(){
		$(this).next().slideToggle();
	})

	$('.style-switcher .options > a,.style-switcher .options > div:not(.base_color_custom) a').click(function(e){
		var _this = $(this);
		if(_this.hasClass('current'))
			return;

		_this.addClass('current').siblings().removeClass('current');
		$('form[name=style-switcher] input[name=' + _this.data('option-id') + ']').val(_this.data('option-value'));

		if(typeof($(this).data('option-type')) != 'undefined') // set cookie for scroll block
			$.cookie('scoll_block', $(this).data('option-type'));

		if(typeof($(this).data('option-url')) != 'undefined') // set action form for redirect
			$('form[name=style-switcher]').prepend('<input type="hidden" name="backurl" value='+$(this).data('option-url')+' />');

		if(_this.closest('.options').hasClass('refresh-block'))
		{
			if(!_this.closest('.options').hasClass('sup-params'))
				var index = _this.index()-1;


			/*if(_this.data('option-value') == 'custom' || (typeof(index) != 'undefined' && !$('.sup-params.options:eq('+index+')').length))
			{
				$('.sup-params.options').removeClass('active');
				$('form[name=style-switcher]').submit();
			}
			else
			{*/
				/*if($('.sup-params.options').length && typeof(index) != 'undefined')
				{*/
					_this.closest('.item').find('.sup-params.options').removeClass('active');
					_this.closest('.item').find('.sup-params.options.s_'+_this.data('option-value')+'').addClass('active');
					// _this.closest('.item').find('.sup-params.options:eq('+index+')').addClass('active');
				//}
			//}
			$('form[name=style-switcher]').submit();
		}
		else
			$('form[name=style-switcher]').submit();
	});

	$('.tooltip-link').on('shown.bs.tooltip', function (e) {
		var tooltip_block = $(this).next(),
			wihdow_height = $(window).height(),
			scroll = $(this).closest('form').scrollTop(),
			pos = BX.pos($(this)[0], true),
			pos_tooltip = BX.pos(tooltip_block[0], true),
			pos_item_wrapper = BX.pos($(this).closest('.item')[0], true);

		if(!$(this).closest('.item').next().length && pos_tooltip.bottom > pos_item_wrapper.bottom)
		{
			tooltip_block.removeClass('bottom').addClass('top');
			tooltip_block.css({'top':(pos.top-tooltip_block.actual('outerHeight'))});
		}
	})
});