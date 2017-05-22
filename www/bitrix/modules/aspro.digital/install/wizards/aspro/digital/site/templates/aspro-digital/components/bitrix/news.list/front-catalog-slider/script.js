$(document).ready(function(){
	var index = $('.tabs_ajax .head-block .item-link.active').index();
	setBasketItemsClasses();
	
	if(!index)
	{
		$('.item-block:eq('+index+') .catalog.item-views.table .item .title').sliceHeight();
		$('.item-block:eq('+index+') .catalog.item-views.table .item .cont').sliceHeight();
		$('.item-block:eq('+index+') .catalog.item-views.table .item .slice_price').sliceHeight();
		$('.item-block:eq('+index+') .catalog.item-views.table .item').sliceHeight({classNull: '.footer-button'});
	}
});