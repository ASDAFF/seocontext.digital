<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<header class="header-v1 topmenu-LIGHT<?=($arTheme["TOP_MENU_FIXED"]["VALUE"] == "Y" ? ' canfixed' : '')?> <?=strtolower($arTheme["ORDER_BASKET_VIEW"]["VALUE"])?>">
	<div class="logo_and_menu-row">
		<div class="logo-row row">
			<div class="maxwidth-theme">
				<div class="col-md-2 col-sm-3 logo-block">
					<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
						<?=CDigital::ShowLogo();?>
					</div>
				</div>
				<div class="col-md-3 hidden-sm hidden-xs">
					<div class="top-description">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/header/header-text.php", array(), array(
								"MODE" => "html",
								"NAME" => "Text in title",
								"TEMPLATE" => "include_area",
							)
						);?>
					</div>
				</div>
				<div class="col-md-4 col-sm-7 col-xs-12 onesmallfont">
					<div class="top-description">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/header/header-contacts.php", array(), array(
								"MODE" => "html",
								"NAME" => "Header Contacts",
								"TEMPLATE" => "include_area",
							)
						);?>
					</div>					
				</div>
				<div class="col-md-3 hidden-sm hidden-xs">
					<div class="pull-left">
						<div class="top-description">
							<div class="onesmallfont muted">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-phone.php", array(), array(
										"MODE" => "html",
										"NAME" => "Header Contacts",
										"TEMPLATE" => "include_area",
									)
								);?>
							</div>
							<div>
								<span class="callback-block hover sep animate-load twosmallfont colored" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback"><?=GetMessage("S_CALLBACK")?></span>
							</div>
						</div>
					</div>
					<div class="pull-right">
						<div class="top-description">
							<div class="top-btn hover inline-search-show pull-right"><i class="svg svg-search" aria-hidden="true"></i></div>
							<?=CDigital::ShowBasketLink('top-btn hover pull-right');?>
						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
		
		<div class="menu-row  maxwidth-theme">
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
	<div class="line-row visible-xs"></div>
</header>