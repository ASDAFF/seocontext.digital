<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
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
$arItemFilter = CDigital::GetIBlockAllElementsFilter($arParams);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$itemsCnt = CCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// rss
	if($arParams['USE_RSS'] !== 'N'){
		CDigital::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
	}
	?>
	<?$arSections = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', 'URL_TEMPLATE' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'])), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'DEPTH_LEVEL' => 1, 'ACTIVE' => 'Y', 'CNT_ACTIVE' => "Y"), true);
	?>
	<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
		<?if($arSections):?>
			<div class="fill-block container-block">
				<div class="title-block-middle"><?=GetMessage('CATEGORY');?></div>
				<ul class="categorys">
					<?foreach($arSections as $arSection):
						if(isset($arSection['NAME']) && $arSection['NAME']):?>
							<li><a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark-color"><span class="text"><?=$arSection['NAME'];?></span><span class="count"><?=$arSection['ELEMENT_CNT'];?></span></a></li>
						<?endif;?>
					<?endforeach;?>
				</ul>
			</div>
		<?endif;?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:search.tags.cloud",
			"main",
			Array(
				"CACHE_TIME" => "86400",
				"CACHE_TYPE" => "A",
				"CHECK_DATES" => "Y",
				"COLOR_NEW" => "3E74E6",
				"COLOR_OLD" => "C0C0C0",
				"COLOR_TYPE" => "N",
				"FILTER_NAME" => "",
				"FONT_MAX" => "50",
				"FONT_MIN" => "10",
				"PAGE_ELEMENTS" => "150",
				"PERIOD" => "",
				"PERIOD_NEW_TAGS" => "",
				"SHOW_CHAIN" => "N",
				"SORT" => "NAME",
				"TAGS_INHERIT" => "Y",
				"URL_SEARCH" => SITE_DIR."search/index.php",
				"WIDTH" => "100%",
				"arrFILTER" => array("iblock_aspro_digital_content"),
				"arrFILTER_iblock_aspro_digital_content" => array($arParams["IBLOCK_ID"])
			), $component
		);?>
	<?$this->__component->__template->EndViewTarget();?>

	<?global $arTheme;?>
	<?// section elements?>
	<?//@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["BLOG_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>

	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
<?endif;?>