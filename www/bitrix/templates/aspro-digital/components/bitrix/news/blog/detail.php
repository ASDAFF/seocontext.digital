<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
// get element
$arItemFilter = CDigital::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$arElement = CCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arItemFilter, false, false, array('ID', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'));

if($arParams["SHOW_NEXT_ELEMENT"] == "Y")
{
	$arSort=array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]);
	$arElementNext = array();

	$arAllElements = CCache::CIblockElement_GetList(array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"], 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $arElement["IBLOCK_SECTION_ID"]/*, ">ID" => $arElement["ID"]*/ ), false, false, array('ID', 'DETAIL_PAGE_URL', 'IBLOCK_ID', 'SORT'));
	if($arAllElements)
	{
		$url_page = $APPLICATION->GetCurPage();
		$key_item = 0;
		foreach($arAllElements as $key => $arItemElement)
		{
			if($arItemElement["DETAIL_PAGE_URL"] == $url_page)
			{
				$key_item = $key;
				break;
			}
		}
		if(strlen($key_item))
		{
			$arElementNext = $arAllElements[$key_item+1];
		}
		if($arElementNext)
		{
			if($arElementNext["DETAIL_PAGE_URL"] && is_array($arElementNext["DETAIL_PAGE_URL"])){
				$arElementNext["DETAIL_PAGE_URL"]=current($arElementNext["DETAIL_PAGE_URL"]);
			}
		}
	}
}
?>
<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CDigital::goto404Page();?>
<?else:?>
	<?// rss
	if($arParams['USE_RSS'] !== 'N'){
		CDigital::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
	}?>
	<?CDigital::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>

	<?// filter for linked items?>
	<?$GLOBALS['arAlsoFilter'] = array('!ID' => $arElement['ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
	if($arElement['IBLOCK_SECTION_ID'])
		$GLOBALS['arAlsoFilter']['SECTION_ID'] = $arElement['IBLOCK_SECTION_ID'];
	?>

	<div class="detail <?=($templateName = $component->{'__template'}->{'__name'})?>">
		<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
			<div class="share top <?=($arParams['USE_RSS'] !== 'N' ? 'rss-block' : '');?>">
				<div class="shares-block">
					<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
					<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
				</div>
			</div>
			<style type="text/css">h1{padding-right:300px;}</style>
			<script type="text/javascript">
				$('h1').addClass('shares');
				$(document).ready(function(){

					if($('a.rss').length)
						$('a.rss').after($('.share.top'));
					else
						$('h1').before($('.share.top'));
				})
			</script>
			<?if($arParams['USE_RSS'] !== 'N'):?>
				<style type="text/css">body h1{padding-right:360px;}</style>
			<?endif;?>
		<?endif;?>
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
			<?if($arParams['ALSO_ITEMS_POSITION'] == 'side'):?>
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "items-blog-list", array(
					"IBLOCK_TYPE" => "aspro_digital_content",
					"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_articles"][0],
					"NEWS_COUNT" => "20",
					"TITLE_BLOCK" => ($arParams["T_ALSO_ITEMS"] ? $arParams["T_ALSO_ITEMS"] : GetMessage('T_ALSO_ITEMS')),
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "arAlsoFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "PREVIEW_TEXT",
						2 => "PREVIEW_PICTURE",
						3 => "DATE_ACTIVE_FROM",
					),
					"PROPERTY_CODE" => array(
						0 => "DOCUMENTS",
						1 => "POST",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "j F Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "Новости",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"SHOW_TABS" => "N",
					"SHOW_IMAGE" => "Y",
					"SHOW_NAME" => "Y",
					"SHOW_DETAIL" => "Y",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"AJAX_OPTION_ADDITIONAL" => ""
					),
				false, array("HIDE_ICONS" => "Y")
				);?>
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
			<?endif;?>
		<?$this->__component->__template->EndViewTarget();?>

		<?//element?>
		<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["BLOG_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
		<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
		
	</div>
	<?
	if(is_array($arElement['IBLOCK_SECTION_ID']) && count($arElement['IBLOCK_SECTION_ID']) > 1){
		CDigital::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}
	?>
	
	<div style="clear:both"></div>
	<hr class="bottoms" />
	<?$APPLICATION->ShowViewContent('tags_content');?>
	<?if($arParams["SHOW_NEXT_ELEMENT"] == "Y"):?>
		<div class="row links-block">
			<div class="col-md-12 links">
				<a class="back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>"><i class="fa fa-angle-left"></i><span><?=($arParams["T_PREV_LINK"] ? $arParams["T_PREV_LINK"] : GetMessage('BACK_LINK'));?></span></a>
				<?if($arElementNext):?>
					<a class="next-url url-block" href="<?=$arElementNext['DETAIL_PAGE_URL']?>"><i class="fa fa-angle-right"></i><span><?=($arParams["T_NEXT_LINK"] ? $arParams["T_NEXT_LINK"] : GetMessage('NEXT_LINK'));?></span></a>
				<?endif;?>
			</div>
		</div>
	<?else:?>
		<div class="row">
			<div class="col-md-6 share">
				<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
					<div class="shares-block">
						<span class="text"><?=GetMessage('SHARE_TEXT')?></span>
						<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
						<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
					</div>
				<?endif;?>
			</div>
			<div class="col-md-6">
				<a class="back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>"><i class="fa fa-angle-left"></i><span><?=($arParams["T_PREV_LINK"] ? $arParams["T_PREV_LINK"] : GetMessage('BACK_LINK'));?></span></a>
			</div>
		</div>
	<?endif;?>
	<?if($arParams['ALSO_ITEMS_POSITION'] != 'side'):?>
		<?$APPLICATION->IncludeComponent("bitrix:news.list", "items-blog-slider", array(
			"IBLOCK_TYPE" => "aspro_digital_content",
			"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_articles"][0],
			"NEWS_COUNT" => "20",
			"TITLE_BLOCK" => ($arParams["T_ALSO_ITEMS"] ? $arParams["T_ALSO_ITEMS"] : GetMessage('T_ALSO_ITEMS')),
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "arAlsoFilter",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",
				2 => "PREVIEW_PICTURE",
				3 => "DATE_ACTIVE_FROM",
			),
			"PROPERTY_CODE" => array(
				0 => "DOCUMENTS",
				1 => "POST",
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "j F Y",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "Y",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"VIEW_TYPE" => "list",
			"SHOW_TABS" => "N",
			"SHOW_IMAGE" => "Y",
			"SHOW_NAME" => "Y",
			"SHOW_DETAIL" => "Y",
			"IMAGE_POSITION" => "left",
			"COUNT_IN_LINE" => "3",
			"AJAX_OPTION_ADDITIONAL" => ""
			),
		false, array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>
	<?if($arParams["DETAIL_USE_COMMENTS"] == "Y"):?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/rating_likes.js");?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.comments", 
			"main", 
			array(
				'CACHE_TYPE' => $arParams['CACHE_TYPE'],
				'CACHE_TIME' => $arParams['CACHE_TIME'],
				'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
				"COMMENTS_COUNT" => $arParams['COMMENTS_COUNT'],
				"ELEMENT_CODE" => "",
				"ELEMENT_ID" => $arElement["ID"],
				"FB_USE" => $arParams["DETAIL_FB_USE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"IBLOCK_TYPE" => "aspro_digital_catalog",
				"SHOW_DEACTIVATED" => "N",
				"TEMPLATE_THEME" => "blue",
				"URL_TO_COMMENT" => "",
				"VK_USE" => $arParams["DETAIL_VK_USE"],
				"AJAX_POST" => "Y",
				"WIDTH" => "",
				"COMPONENT_TEMPLATE" => ".default",
				"BLOG_USE" => $arParams["DETAIL_BLOG_USE"],
				"BLOG_TITLE" => $arParams["BLOG_TITLE"],
				"BLOG_URL" => $arParams["DETAIL_BLOG_URL"],
				"PATH_TO_SMILE" => '/bitrix/images/blog/smile/',
				"EMAIL_NOTIFY" => $arParams["DETAIL_BLOG_EMAIL_NOTIFY"],
				"SHOW_SPAM" => "Y",
				"SHOW_RATING" => "Y",
				"RATING_TYPE" => "like_graphic",
				"FB_TITLE" => $arParams["FB_TITLE"],
				"FB_USER_ADMIN_ID" => "",
				"FB_APP_ID" => $arParams["DETAIL_FB_APP_ID"],
				"FB_COLORSCHEME" => "light",
				"FB_ORDER_BY" => "reverse_time",
				"VK_TITLE" => $arParams["VK_TITLE"],
				"VK_API_ID" => $arParams["DETAIL_VK_API_ID"]
			),
			false, array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>
<?endif;?>