<div class="maxwidth-theme">
	<div class="logo-row v1 row margin0">
		<div class="pull-left">
			<div class="inner-table-block sep-left nopadding logo-block">
				<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
					<?=CDigital::ShowLogo();?>
				</div>
			</div>
		</div>
		<div class="pull-left">
			<div class="inner-table-block menu-block rows sep-left">
				<div class="title"><i class="svg svg-burger"></i><?=GetMessage("S_MOBILE_MENU")?>&nbsp;&nbsp;<i class="fa fa-angle-down"></i></div>
				<div class="navs table-menu js-nav">
					<?$APPLICATION->IncludeComponent(
						"bitrix:menu",
						"top_fixed_field",
						Array(
							"COMPONENT_TEMPLATE" => "top_fixed_field",
							"MENU_CACHE_TIME" => "3600000",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_USE_GROUPS" => "N",
							"MENU_CACHE_GET_VARS" => array(
							),
							"DELAY" => "N",
							"MAX_LEVEL" => "4",
							"ALLOW_MULTI_SELECT" => "Y",
							"ROOT_MENU_TYPE" => "top",
							"CHILD_MENU_TYPE" => "left",
							"USE_EXT" => "Y"
						)
					);?>
				</div>
			</div>
		</div>
		<div class="pull-left col-md-4 nopadding hidden-sm hidden-xs search animation-width">
			<div class="inner-table-block">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => SITE_DIR."include/header/search.title.php",
						"EDIT_TEMPLATE" => "include_area.php"
					)
				);?>
			</div>
		</div>
		<div class="pull-right">
			<?=CDigital::ShowBasketLink('top-btn inner-table-block', 'lg', '');?>
		</div>
		<?if($arTheme["CABINET"]["VALUE"]=='Y'):?>
			<div class="pull-right">
				<div class="inner-table-block small-block">
					<div class="wrap_icon wrap_cabinet">
						<?=CDigital::showCabinetLink(true, false, 'lg');?>
					</div>
				</div>
			</div>
		<?endif;?>
		<div class="pull-right">
			<div class="inner-table-block">
				<div class="animate-load btn btn-default white btn-sm" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback">
					<span><?=GetMessage("S_CALLBACK")?></span>
				</div>
			</div>
		</div>
		<div class="pull-right logo_and_menu-row">
			<div class="inner-table-block phones">
				<?CDigital::ShowHeaderPhones();?>
			</div>
		</div>
	</div>
</div>