<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>
<div class="text_before_items">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?>
</div>
<?
// get section items count and subsections
$arItemFilter = CDigital::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams, false);
$arSubSectionFilter = CDigital::GetCurrentSectionSubSectionFilter($arResult["VARIABLES"], $arParams, false);
$itemsCnt = CCache::CIBlockElement_GetList(array("CACHE" => array("TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());
$arSubSections = CCache::CIBlockSection_GetList(array("CACHE" => array("TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y")), $arSubSectionFilter, false, array("ID"));

// rss
if($arParams['USE_RSS'] !== 'N'){
	CDigital::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
}
?>
<?if(!$itemsCnt && !$arSubSections):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// sections?>
	<?@include_once('page_blocks/'.$arParams["SECTIONS_TYPE_VIEW"].'.php');?>

	<?// section elements?>
	<?if(strlen($arParams["FILTER_NAME"])):?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = array_merge((array)$GLOBALS[$arParams["FILTER_NAME"]], $arItemFilter);?>
	<?else:?>
		<?$arParams["FILTER_NAME"] = "arrFilter";?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arItemFilter;?>
	<?endif;?>
	<?@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>
<?endif;?>