<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
$bOrder = ($arTheme['ORDER_VIEW']['VALUE'] == 'Y' && $arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['ORDER_BASKET_VIEW']['VALUE'] == 'HEADER' ? true : false);
$bCabinet = ($arTheme["CABINET"]["VALUE"] == 'Y' ? true : false);
$bPhone = (intval($arTheme['HEADER_PHONES']) > 0 ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$fixedMenuClass = ($arTheme['TOP_MENU_FIXED']['VALUE'] == 'Y' ? ' canfixed' : '');
$basketViewClass = strtolower($arTheme["ORDER_BASKET_VIEW"]["VALUE"]);
?>
<div class="top-block top-block-v1">
	<div class="maxwidth-theme">
		<div class="top-block-item col-md-4">
			<div class="phone-block">
				<?if($bPhone):?>
					<div class="inline-block">
						<?CDigital::ShowHeaderPhones();?>
					</div>
				<?endif?>
				<div class="inline-block">
					<span class="callback-block animate-load twosmallfont colored" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback"><?=GetMessage("S_CALLBACK")?></span>
				</div>
			</div>
		</div>
		<div class="top-block-item pull-left">
			<div class="address twosmallfont inline-block">
				<i class="svg svg-address black"></i>
				<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
						"MODE" => "html",
						"NAME" => "Address",
						"TEMPLATE" => "include_area",
					)
				);?>
			</div>
		</div>
		<div class="top-block-item pull-right show-fixed top-ctrl">
			<button class="top-btn inline-search-show twosmallfont">
				<i class="svg svg-search" aria-hidden="true"></i>
				<span class="dark-color"><?=GetMessage('SEARCH_TITLE')?></span>
			</button>
		</div>

		<?if($bOrder):?>
			<div class="top-block-item pull-right show-fixed top-ctrl">
				<div class="basket_wrap twosmallfont">
					<?=CDigital::showBasketLink('', '', GetMessage('BASKET'));?>
				</div>
			</div>
		<?endif;?>

		<?if($bCabinet):?>
			<div class="top-block-item pull-right show-fixed top-ctrl">
				<div class="personal_wrap">
					<div class="personal top login twosmallfont">
						<?=CDigital::showCabinetLink(true, true);?>
					</div>
				</div>
			</div>
		<?endif;?>
	</div>
</div>
<header class="header-v1 topmenu-LIGHT<?=$fixedMenuClass?><?=$basketViewClass?>">
	<div class="logo_and_menu-row">
		<div class="logo-row row">
			<div class="maxwidth-theme">
				<div class="logo-block col-md-2 col-sm-3">
					<div class="logo<?=$logoClass?>">
						<?=CDigital::ShowLogo();?>
					</div>
				</div>
				<div class="col-md-2 hidden-sm hidden-xs">
					<div class="top-description">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/header/header-text.php", array(), array(
								"MODE" => "html",
								"NAME" => "Text in title",
								"TEMPLATE" => "include_area",
							)
						);?>
					</div>
				</div>
				<div class="col-md-8 menu-row">
					<div class="nav-main-collapse collapse in">
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
		</div><?// class=logo-row?>
	</div>
	<div class="line-row visible-xs"></div>
</header>