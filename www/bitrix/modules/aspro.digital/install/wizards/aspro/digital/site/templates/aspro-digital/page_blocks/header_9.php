<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
$bOrder = ($arTheme['ORDER_VIEW']['VALUE'] == 'Y' && $arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['ORDER_BASKET_VIEW']['VALUE']=='HEADER' ? true : false);
$bCabinet = ($arTheme["CABINET"]["VALUE"]=='Y' ? true : false);
$bPhone = (intval($arTheme['HEADER_PHONES']) > 0 ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$fixedMenuClass = ($arTheme['TOP_MENU_FIXED']['VALUE'] == 'Y' ? ' canfixed' : '');
$basketViewClass = strtolower($arTheme["ORDER_BASKET_VIEW"]["VALUE"]);
?>
<header class="header-v9<?=$fixedMenuClass?><?=$basketViewClass?>">
	<div class="logo_and_menu-row">
		<div class="logo-row">
			<div class="maxwidth-theme col-md-12">
				<div class="row">
					<div class="col-md-4">
						<div class="search-block inner-table-block">
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
					<div class="logo-block col-md-2 text-center col-md-offset-1">
						<div class="logo<?=$logoClass?>">
							<?=CDigital::ShowLogo();?>
						</div>
					</div>
					<div class="right-icons pull-right">
						<div class="phone-block with_btn">
							<?if($bPhone):?>
								<div class="inner-table-block">
									<?CDigital::ShowHeaderPhones();?>
									<div class="schedule">
										<?$APPLICATION->IncludeFile(SITE_DIR."include/header-schedule.php", array(), array("MODE" => "html","NAME" => GetMessage('HEADER_SCHEDULE'),));?>
									</div>
								</div>
							<?endif?>
							<div class="inner-table-block">
								<span class="callback-block animate-load twosmallfont colored white btn-default btn" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback"><?=GetMessage("S_CALLBACK")?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="menu-row bgcolored sliced">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="right-icons pull-right">
					<?if($bOrder):?>
						<div class="pull-right">
							<div class="wrap_icon inner-table-block">
								<?=CDigital::showBasketLink('', 'white','');?>
							</div>
						</div>
					<?endif;?>
					<?if($bCabinet):?>
						<div class="pull-right">
							<div class="wrap_icon inner-table-block">
								<?=CDigital::showCabinetLink(true, false, 'white');?>
							</div>
						</div>
					<?endif;?>
				</div>
				<div class="menu-only">
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
		</div>
	</div>
	<div class="line-row visible-xs"></div>
</header>