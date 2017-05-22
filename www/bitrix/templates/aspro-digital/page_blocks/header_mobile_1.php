<div class="mobileheader-v1">
	<div class="burger pull-left">
		<i class="svg svg-burger mask"></i>
		<i class="svg svg-close black lg"></i>
	</div>
	<div class="logo-block pull-left">
		<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
			<?=CDigital::ShowLogo();?>
		</div>
	</div>
	<div class="right-icons pull-right">
		<div class="pull-right">
			<div class="wrap_icon">
				<button class="top-btn inline-search-show twosmallfont">
					<i class="svg svg-search lg" aria-hidden="true"></i>
				</button>
			</div>
		</div>
		<?if($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['ORDER_BASKET_VIEW']['VALUE']=='HEADER'):?>
			<div class="pull-right">
				<div class="wrap_icon wrap_basket">
					<?=CDigital::showBasketLink('', 'lg', '', '');?>
				</div>
			</div>
		<?endif;?>
		<?if($arTheme["CABINET"]["VALUE"]=='Y'):?>
			<div class="pull-right">
				<div class="wrap_icon wrap_cabinet">
					<?=CDigital::showCabinetLink(true, false, 'lg');?>
				</div>
			</div>
		<?endif;?>
	</div>
</div>