<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<header class="topmenu-LIGHT<?=($arTheme["TOP_MENU_FIXED"]["VALUE"] == "Y" ? ' canfixed' : '')?> <?=strtolower($arTheme["ORDER_BASKET_VIEW"]["VALUE"])?>">
	<div class="logo_and_menu-row">
		<div class="logo-row row">
			<div class="maxwidth-theme">
				<div class="col-md-2 col-sm-3 logo-block">
					<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
						<?=CDigital::ShowLogo();?>
					</div>
					<div class="top-callback col-md-8">			
						<button class="btn btn-responsive-nav visible-xs" data-toggle="collapse" data-target=".nav-main-collapse">
							<i class="fa fa-bars"></i>
						</button>
					</div>
				</div>
				<div class="col-md-2 hidden-sm hidden-xs desc-block">
					<div class="top-description">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/header/header-text.php", array(), array(
								"MODE" => "html",
								"NAME" => "Text in title",
								"TEMPLATE" => "include_area",
							)
						);?>
					</div>
				</div>
				<div class="col-md-8 col-sm-9 col-xs-12 menu-block">
					<div class="menu-row">
						<div class="nav-main-collapse collapse">
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
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="line-row visible-xs"></div>
</header>