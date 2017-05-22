<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"blog",
	Array(
		"S_ASK_QUESTION" => $arParams["S_ASK_QUESTION"],
		"S_ORDER_SERVISE" => $arParams["S_ORDER_SERVISE"],
		"T_GALLERY" => $arParams["T_GALLERY"],
		"T_DOCS" => $arParams["T_DOCS"],
		"T_GOODS" => $arParams["T_GOODS"],
		"T_SERVICES" => $arParams["T_SERVICES"],
		"T_PROJECTS" => $arParams["T_PROJECTS"],
		"T_REVIEWS" => $arParams["T_REVIEWS"],
		"T_STAFF" => $arParams["T_STAFF"],
		"T_VIDEO" => $arParams["T_VIDEO"],
		"FORM_ID_ORDER_SERVISE" => $arParams["FORM_ID_ORDER_SERVISE"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
		"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
		"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"USE_SHARE" 			=> $arParams["USE_SHARE"],
		"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
		"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
		"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
		"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
		"GALLERY_TYPE" => $arParams["GALLERY_TYPE"],
	),
	$component
);?>

<?// projects links?>
<?if(in_array('LINK_PROJECTS', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_PROJECTS_VALUE']):?>
	<?$arProjects = CCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_digital_content']['aspro_digital_projects'][0]), 'MULTI' => 'Y')), array('ID' => $arElement['PROPERTY_LINK_PROJECTS_VALUE'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, false, array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'));?>
	<div class="wraps projects-block">
		<hr />
		<h5><?=(strlen($arParams['T_PROJECTS']) ? $arParams['T_PROJECTS'] : GetMessage('T_PROJECTS'))?></h5>
		<div class="projects item-views table normal-img">
			<div class="flexslider unstyled row front shadow" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "itemMargin": 32, "counts": [3, 2, 1]}'>
				<?
				$itemsCount = count($arProjects);
				$arParams['COLUMN_COUNT'] = 3;
				//$arParams['COLUMN_COUNT'] = ($arParams['COLUMN_COUNT'] > 0 && $arParams['COLUMN_COUNT'] < 6) ? ($arParams['COLUMN_COUNT'] > $itemsCount ? $itemsCount : $arParams['COLUMN_COUNT']) : 3;
				$countmd = $arParams['COLUMN_COUNT'];
				$countsm = (($tmp = ceil($arParams['COLUMN_COUNT'] / 2)) > 2 ? $tmp : (!$tmp ? 1 : $tmp));
				$colmd = floor(12 / $countmd);
				$colsm = floor(12 / $countsm);
				?>
				<ul class="slides items">
					<?foreach($arProjects as $arItem):?>
						<?
						// edit/add/delete buttons for edit mode
						$arItemButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], $arItem['ID'], 0, array('SESSID' => false, 'CATALOG' => true));
						$this->AddEditAction($arItem['ID'], $arItemButtons['edit']['edit_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arItemButtons['edit']['delete_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						$thumb = CFile::GetPath($arItem['PREVIEW_PICTURE'] ? $arItem['PREVIEW_PICTURE'] : $arItem['DETAIL_PICTURE']);
						?>
						<li class="col-md-4">
							<div class="item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<?// preview picture?>
									<div class="image shine <?=($thumb ? "w-picture" : "wo-picture");?>">
										<?if($thumb):?>
											<img src="<?=$thumb?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" class="img-responsive" />
										<?else:?>
											<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/images/noimage.png" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
										<?endif;?>
									</div>
									<div class="info">
										<?// element name?>
										<div class="title dark-color">
											<span><?=$arItem['NAME']?></span>
										</div>
									</div>
								</a>
							</div>
						</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
	</div>
<?endif;?>