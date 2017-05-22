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
	if($arParams['USE_RSS'] !== 'N')
		CDigital::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
	?>

	<?$arItems = CCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
	$arYears = array();
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			if($arItem['ACTIVE_FROM'])
			{
				if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
					$arYears[$arDateTime['YYYY']] = $arDateTime['YYYY'];
			}
		}
		if($arYears)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				rsort($arYears);?>
				<div class="head-block top">
					<div class="bottom_border"></div>
					<div class="item-link active">
						<div class="title">
							<span class="btn-inline black"><?=GetMessage('ALL_TIME');?></span>
						</div>
					</div>
					<?foreach($arYears as $year):?>
						<div class="item-link">
							<div class="title btn-inline black">
								<a class="btn-inline black" href="<?=$arResult['FOLDER'].str_replace('#YEAR#', $year, $arResult['URL_TEMPLATES']['section']);?>"><?=$year;?></a>
							</div>
						</div>
					<?endforeach;?>
				</div>
			<?}
		}
	}?>

	<?global $arTheme, $isMenu;?>

	<?if(!$isMenu):?>
		<div class="sub_container fixed_wrapper">
		<div class="row">
			<div class="col-md-9">
	<?endif;?>
	
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	
	<?// ask block?>
	<?ob_start();?>
		<div class="ask_a_question">
			<div class="inner">
				<div class="text-block">
					<?$APPLICATION->IncludeComponent(
						 'bitrix:main.include',
						 '',
						 Array(
							  'AREA_FILE_SHOW' => 'file',
							  'PATH' => SITE_DIR.'include/ask_question.php',
							  'EDIT_TEMPLATE' => ''
						 )
					);?>
				</div>
			</div>
			<div class="outer">
				<span><span class="btn btn-default btn-lg white animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_question'][0]?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span></span>
			</div>
		</div>
	<?$html = ob_get_contents();?>
	<?ob_end_clean();?>

	<?if(!$isMenu):?>
			</div>
			<div class="col-md-3  with-padding-left hidden-xs hidden-sm">
				<div class="fixed_block_fix"></div>
				<div class="ask_a_question_wrapper">
					<?=$html;?>
				</div>
			</div>
		</div>
		</div>
	<?else:?>
		<?$this->SetViewTarget('under_sidebar_content');?>
			<?=$html;?>
		<?$this->EndViewTarget();?>
	<?endif;?>
<?endif;?>