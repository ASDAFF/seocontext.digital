$(document).ready(function() {
	$.ajax({
		url: arDigitalOptions['SITE_DIR']+'include/mainpage/comp_instagramm.php',
		data: {'AJAX_REQUEST_INSTAGRAM': 'Y', 'SHOW_INSTAGRAM': arDigitalOptions['THEME']['INSTAGRAMM_INDEX']},
		type: 'POST',
		success: function(html){
			$('.instagram_ajax').html(html).addClass('loaded');
			$('.instagram_ajax .instagram .item').sliceHeight();
			var eventdata = {action:'instagrammLoaded'};
			BX.onCustomEvent('onCompleteAction', [eventdata]);
		}
	});
});