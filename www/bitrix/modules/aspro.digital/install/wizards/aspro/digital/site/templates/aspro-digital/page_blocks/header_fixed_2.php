<div class="maxwidth-theme">
	<div class="logo-row v2 row margin0">
		<div class="inner-table-block nopadding logo-block">
			<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
				<?=CDigital::ShowLogo();?>
			</div>
		</div>
		<div class="inner-table-block menu-block">
			<div class="navs table-menu js-nav">
				<nav class="mega-menu sliced">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/header/menu.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "include_area.php"
						),
						false, array("HIDE_ICONS" => "Y")
					);?>
				</nav>
			</div>
		</div>
		<?if($arTheme["CABINET"]["VALUE"]=='Y'):?>
			<div class="inner-table-block nopadding small-block">
				<div class="wrap_icon wrap_cabinet">
					<?=CDigital::showCabinetLink(true, false, 'lg');?>
				</div>
			</div>
		<?endif;?>
		<?=CDigital::showBasketLink('inner-table-block nopadding', 'lg','');?>
		<div class="inner-table-block small-block nopadding inline-search-show" data-type_search="fixed">
			<div class="search-block top-btn"><i class="svg svg-search lg"></i></div>
		</div>
	</div>
</div>