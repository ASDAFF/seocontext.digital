<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<!DOCTYPE html>

<?if(CModule::IncludeModule("aspro.digital"))
	$arThemeValues = CDigital::GetFrontParametrsValues(SITE_ID);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" class="<?=($_SESSION['SESS_INCLUDE_AREAS'] ? 'bx_editmode ' : '')?><?=strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0' ) ? 'ie ie7' : ''?> <?=strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0' ) ? 'ie ie8' : ''?> <?=strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0' ) ? 'ie ie9' : ''?>">
	<head>
		<?global $APPLICATION;?>
		<?IncludeTemplateLangFile(__FILE__);?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<?$APPLICATION->ShowMeta("viewport");?>
		<?$APPLICATION->ShowMeta("HandheldFriendly");?>
		<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
		<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
		<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
		<?$APPLICATION->ShowHead();?>
		<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject($MESS, false).')</script>', true);?>
		<?if(CModule::IncludeModule("aspro.digital")) {CDigital::Start(SITE_ID);}?>
	</head>

	<body class="mheader-v<?=$arThemeValues["HEADER_MOBILE"];?> header-v<?=$arThemeValues["HEADER_TYPE"];?> title-v<?=$arThemeValues["PAGE_TITLE"];?><?=($arThemeValues['ORDER_VIEW'] == 'Y' && $arThemeValues['ORDER_BASKET_VIEW']=='HEADER'? ' with_order' : '')?><?=($arThemeValues['CABINET'] == 'Y' ? ' with_cabinet' : '')?><?=(intval($arThemeValues['HEADER_PHONES']) > 0 ? ' with_phones' : '')?>">
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<?if(!CModule::IncludeModule("aspro.digital")):?>
			<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_DIGITAL_TITLE"));?>
			<?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?>
			<?die();?>
		<?endif;?>
		<?CDigital::SetJSOptions();?>
		<?global $arSite, $isMenu, $isIndex, $is404, $bTopServicesIndex, $bPortfolioIndex, $bPartnersIndex, $bTeasersIndex, $bInstagrammIndex, $bReviewsIndex, $bConsultIndex, $bCompanyIndex, $bTeamIndex, $bNewsIndex;?>
		<?$is404 = defined("ERROR_404") && ERROR_404 === "Y"?>
		<?$arSite = CSite::GetByID(SITE_ID)->Fetch();?>
		<?$isMenu = ($APPLICATION->GetProperty('MENU') !== "N" ? true : false);?>
		<?global $arTheme;?>
		<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.digital", "", array(), false);?>
		<?$isForm = CSite::inDir(SITE_DIR.'form/');?>
		<?$isBlog = CSite::inDir(SITE_DIR.'articles/');?>
		<?$isCabinet = CSite::inDir(SITE_DIR.'cabinet/');?>
		<?$isIndex = CSite::inDir(SITE_DIR."index.php");?>
		
		<?if($isIndex = CSite::inDir(SITE_DIR."index.php")):?>
			<?$indexType = $arTheme["INDEX_TYPE"]["VALUE"];?>
			<?$bTopServicesIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TOP_SERVICES_INDEX"]["VALUE"] == 'Y';?>
			<?$bPartnersIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["PARTNERS_INDEX"]["VALUE"] == 'Y';?>
			<?$bTeasersIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TEASERS_INDEX"]["VALUE"] == 'Y';?>
			<?$bPortfolioIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["PORTFOLIO_INDEX"]["VALUE"] == 'Y';?>
			<?if(isset($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM_INDEX"]))
				$bInstagrammIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM_INDEX"]["VALUE"] == 'Y';
			else
				$bInstagrammIndex = true;?>
			<?$bReviewsIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["REVIEWS_INDEX"]["VALUE"] == 'Y';?>
			<?$bConsultIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CONSULT_INDEX"]["VALUE"] == 'Y';?>
			<?$bCompanyIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COMPANY_INDEX"]["VALUE"] == 'Y';?>
			<?$bTeamIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TEAM_INDEX"]["VALUE"] == 'Y';?>
			<?$bNewsIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["NEWS_INDEX"]["VALUE"] == 'Y';?>
		<?endif;?>

		<?CDigital::get_banners_position('TOP_HEADER');?>
		<div class="visible-lg visible-md title-v<?=$arTheme["PAGE_TITLE"]["VALUE"];?><?=($isIndex ? ' index' : '')?>">
			<?CDigital::ShowPageType('header');?>
		</div>

		<?CDigital::get_banners_position('TOP_UNDERHEADER');?>

		<?if($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y'):?>
			<div id="headerfixed">
				<?CDigital::ShowPageType('header_fixed');?>
			</div>
		<?endif;?>

		<div id="mobileheader" class="visible-xs visible-sm">
			<?CDigital::ShowPageType('header_mobile');?>
			<div id="mobilemenu" class="<?=($arTheme["HEADER_MOBILE_MENU_OPEN"]["VALUE"] == '1' ? 'leftside':'dropdown')?>">
				<?CDigital::ShowPageType('header_mobile_menu');?>
			</div>
		</div>


		<div class="body <?=($isIndex ? 'index' : '')?> hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>">
			<div class="body_media"></div>

			<div role="main" class="main banner-<?=$arTheme["BANNER_WIDTH"]["VALUE"];?>">
				<?if(!$isIndex && !$is404 && !$isForm):?>

					<?$APPLICATION->ShowViewContent('section_bnr_content');?>
					<?if($APPLICATION->GetProperty("HIDETITLE")!=='Y'):?>
						<!--title_content--> 
						<? CDigital::ShowPageType('page_title');?>
						<!--end-title_content-->
					<?endif;?>

					<?$APPLICATION->ShowViewContent('top_section_filter_content');?>
				<?endif; // if !$isIndex && !$is404 && !$isForm?>

				<div class="container <?=($isCabinet ? 'cabinte-page' : '');?>">
					<?$GLOBALS['arFrontItemsFilter'] = array('!PROPERTY_SHOW_ON_INDEX_PAGE' => false);?>
					<?if(!$isIndex):?>
						<div class="row">
							<?if($APPLICATION->GetProperty("FULLWIDTH")!=='Y'):?>
								<div class="maxwidth-theme">
							<?endif;?>
							<?if($is404):?>
								<div class="col-md-12 col-sm-12 col-xs-12 content-md">
							<?else:?>
								<?if(!$isMenu):?>
									<div class="col-md-12 col-sm-12 col-xs-12 content-md">
								<?elseif($isMenu && ($arTheme["SIDE_MENU"]["VALUE"] == "RIGHT" || $isBlog)):?>
									<div class="col-md-9 col-sm-12 col-xs-12 content-md">
									<?CDigital::get_banners_position('CONTENT_TOP');?>
								<?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT" && !$isBlog):?>
									<div class="col-md-3 col-sm-3 hidden-xs hidden-sm left-menu-md">
										<?$APPLICATION->IncludeComponent(
											"bitrix:menu",
											"left",
											array(
												"ROOT_MENU_TYPE" => ($isCabinet ? "cabinet" : "left"),
												"MENU_CACHE_TYPE" => "A",
												"MENU_CACHE_TIME" => "3600000",
												"MENU_CACHE_USE_GROUPS" => ($isCabinet ? "Y" : "N"),
												"MENU_CACHE_GET_VARS" => array(
												),
												"MAX_LEVEL" => "4",
												"CHILD_MENU_TYPE" => "left",
												"USE_EXT" => "Y",
												"DELAY" => "N",
												"ALLOW_MULTI_SELECT" => "Y",
												"COMPONENT_TEMPLATE" => "left"
											),
											false
										);?>
										<div class="sidearea">
											<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
											<?CDigital::get_banners_position('SIDE');?>
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												".default",
												array(
													"AREA_FILE_SHOW" => "sect",
													"AREA_FILE_SUFFIX" => "sidebar",
													"AREA_FILE_RECURSIVE" => "Y",
													"COMPONENT_TEMPLATE" => ".default",
													"EDIT_TEMPLATE" => "include_area.php"
												),
												false
											);?>
										</div>
									</div>
									<div class="col-md-9 col-sm-12 col-xs-12 content-md">
									<?CDigital::get_banners_position('CONTENT_TOP');?>
								<?endif;?>
							<?endif;?>
					<?endif;?>
					<?CDigital::checkRestartBuffer();?>