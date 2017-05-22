<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']);?>
<div class="confirm">
	<div class="description">
		<h4><?=GetMessage('T_CONFIRM_ORDER_TITLE');?></h4>
		<p><?=GetMessage('T_CONFIRM_ORDER_DESCRIPTION');?></p>
		<div class="buttons">
			<a class="btn btn-default" href="<?=$arParams['PATH_TO_CATALOG'];?>"><?=GetMessage('T_HEAD_LINK_CATALOG');?></a>
			<a class="btn btn-default white" href="<?=SITE_DIR;?>"><?=GetMessage('T_HEAD_LINK_MAIN');?></a>
		</div>
	</div>
</div>
<script>

$(document).ready(function(){
	<?if(!isset($_SESSION['ORDERS'][$_REQUEST['RESULT_ID']])):?>
		if(arDigitalOptions['THEME']['YA_GOLAS'] == 'Y' && arDigitalOptions['THEME']['YA_COUNTER_ID'] && arDigitalOptions['THEME']['USE_SALE_GOALS'] !== 'N')
		{
			var eventdata = {goal: 'goal_order_success'};
			BX.onCustomEvent('onCounterGoals', [eventdata]);
		}
		<?$_SESSION['ORDERS'][$_REQUEST['RESULT_ID']] = $_REQUEST['RESULT_ID'];?>
	<?endif;?>
	if($('.basket_top').length){
		$.ajax({
			url: arDigitalOptions['SITE_DIR'] + 'include/footer/site-basket.php',
			type: 'POST',
		}).success(function(html){
			$('.basket_top').html(html);
		});
	}
});
</script>
