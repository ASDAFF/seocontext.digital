checkTable = function() {
	var z = parseInt($('.body_media').css('top'));
	$('.catalog.item-views.price .item > div').css('margin-top', '');
	$('.catalog.item-views.price .item .label').css('margin-top', '');
	$('.catalog.item-views.price .item .price').css('margin-top', '');
	$('.catalog.item-views.price .item').each(function() {
		var title = $(this).find('.title').parent();
		var buy_block = $(this).find('.buy_block');
		var price = $(this).find('.price');
		var btn = $(this).find('.btn');

		if(btn.length){
			btn.css('margin-top', (!$(this).find('.price_old').length ? '-3px' : '7px'));
		}

		if(z > 0){
			var itemHeight = $(this).outerHeight() - parseInt($(this).css('padding-top')) - parseInt($(this).css('padding-bottom')) - parseInt($(this).css('border-top-width')) - parseInt($(this).css('border-bottom-width'));

			if(title.length){
				var titleHeight = title.outerHeight();
				var titleMarginTop = Math.floor((itemHeight - titleHeight) / 2);
				title.css('margin-top', titleMarginTop + 'px');
			}

			if(buy_block.length){
				var statusHeight = buy_block.outerHeight();
				var statusMarginTop = Math.floor((itemHeight - statusHeight) / 2);
				buy_block.css('margin-top', statusMarginTop + 'px');
			}

			if(price.length){
				var priceHeight = price.outerHeight();
				var priceMarginTop = Math.floor((itemHeight - priceHeight) / 2);
				price.css('margin-top', priceMarginTop + 'px');
			}

			if(btn.length){
				var btnHeight = btn.outerHeight();
				var btnMarginTop = Math.floor((itemHeight - btnHeight) / 2);
				btn.css('margin-top', btnMarginTop + 'px');
			}
		}
	});
}
BX.addCustomEvent('onWindowResize', function(eventdata) {
	ignoreResize.push(true);
	checkTable();
	ignoreResize.pop();
});

$(document).ready(function(){
	setBasketItemsClasses();
	checkTable();
});