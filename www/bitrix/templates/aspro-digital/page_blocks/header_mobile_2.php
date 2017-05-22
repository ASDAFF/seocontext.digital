<div class="mobileheader-v2">
	<div class="burger pull-left">
		<i class="svg svg-burger white lg"></i>
		<i class="svg svg-close white lg"></i>
	</div>
	<div class="title-block col-sm-8 col-xs-7 pull-left"><?$APPLICATION->ShowTitle(false)?></div>
	<div class="right-icons pull-right">
		<div class="pull-right">
			<div class="wrap_icon">
				<button class="top-btn inline-search-show twosmallfont">
					<i class="svg svg-search lg white" aria-hidden="true"></i>
				</button>
			</div>
		</div>
		<?if($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['ORDER_BASKET_VIEW']['VALUE']=='HEADER'):?>
			<div class="pull-right">
				<div class="wrap_icon wrap_basket">
					<?=CDigital::showBasketLink('', 'lg white', '', '');?>
				</div>
			</div>
		<?endif;?>
		<?if($arTheme["CABINET"]["VALUE"]=='Y'):?>
			<div class="pull-right">
				<div class="wrap_icon wrap_cabinet">
					<?=CDigital::showCabinetLink(true, false, 'lg white');?>
				</div>
			</div>
		<?endif;?>
	</div>
</div>