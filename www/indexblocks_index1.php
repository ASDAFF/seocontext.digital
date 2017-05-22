<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="greyline row margin0 block-with-bg">
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"front-banners-big",
		array(
			"IBLOCK_TYPE" => "aspro_digital_content",
			"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_advtbig"][0],
			"NEWS_COUNT" => "30",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",
				2 => "PREVIEW_PICTURE",
				3 => "DETAIL_PICTURE",
				4 => ""
			),
			"PROPERTY_CODE" => array(
				0 => "BANNERTYPE",
				1 => "TEXTCOLOR",
				2 => "LINKIMG",
				3 => "BUTTON1TEXT",
				4 => "BUTTON1LINK",
				4 => "BUTTON1CLASS",
				5 => "BUTTON2TEXT",
				6 => "BUTTON2LINK",
				7 => "BUTTON2CLASS",
				7 => ""
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600000",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
			"PAGER_SHOW_ALL" => "N",
			"AJAX_OPTION_ADDITIONAL" => ""
		),
		false
	);?>
</div>

<div class="greyline row margin0 block-with-bg">
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y')):?>
		<?$APPLICATION->RestartBuffer();?>
	<?endif;?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list", 
		"front-banners-more", 
		array(
			"IBLOCK_TYPE" => "aspro_digital_content",
			"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_tizers"][0],
			"NEWS_COUNT" => "5",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_PICTURE",
				2 => "PREVIEW_TEXT",
			),
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "LINK",
				2 => "TYPE",
				3 => "",
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600000",
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
			"INCLUDE_SUBSECTIONS" => "N",
			"PAGER_TEMPLATE" => "main",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
			"PAGER_SHOW_ALL" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"COMPONENT_TEMPLATE" => "front-banners-small"
		),
		false
	);?>
	<?CDigital::checkRestartBuffer();?>
</div>

<br/><br/><br/>
<div class="row">
	<div class="maxwidth-theme item-views blocks" style="max-width: 900px;" >
		<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/seo.php", Array(), Array(
		    "MODE"      => "html",
		    "NAME"      => GetMessage("SEO_TEXT"),
		    ));
		?>
	</div>		
</div>

	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list", 
		"front-sections", 
		array(
			"IBLOCK_TYPE" => "aspro_digital_content",
			"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_services"][0],
			"NEWS_COUNT" => "6",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",
				2 => "PREVIEW_PICTURE",
				3 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "ICON",
				2 => "LINK",
				3 => "",
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600000",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
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
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
			"PAGER_SHOW_ALL" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"SHOW_DETAIL_LINK" => "Y",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"COMPONENT_TEMPLATE" => "front-teasers-icons",
			"SET_LAST_MODIFIED" => "N",
			"TITLE" => "",
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"SHOW_404" => "N",
			"MESSAGE_404" => ""
		),
		false
	);?>

	<?$APPLICATION->IncludeComponent(
		"aspro:tabs.digital", 
		"main", 
		array(
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO",
			"DETAIL_URL" => "",
			"FILTER_NAME" => "arFilterCatalog",
			"HIT_PROP" => "HIT",
			"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_catalog"]["aspro_digital_catalog"][0],
			"IBLOCK_TYPE" => "aspro_digital_catalog",
			"NEWS_COUNT" => "20",
			"PARENT_SECTION" => "",
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "SHOW_ON_INDEX_PAGE",
				2 => "STATUS",
				3 => "PRICE",
				4 => "PRICEOLD",
				5 => "ARTICLE",
				6 => "FORM_ORDER",
				7 => "HIT",
			),
			"SORT_BY1" => "SORT",
			"SORT_BY2" => "ID",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "ASC",
			"TITLE" => "Наши продукты",
			"COMPONENT_TEMPLATE" => "main",
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_PICTURE",
				2 => "DETAIL_PICTURE",
				3 => "",
			),
			"S_ORDER_PRODUCT" => "Заказать",
			"S_MORE_PRODUCT" => "Подробнее"
		),
		false
	);?>
	
<br/><br/><br>
<div class="row">
	<div class="maxwidth-theme company-front">
		<div class="col-md-3 hidden-xs hidden-sm">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/company_img.php", Array(), Array(
			    "MODE"      => "html",
			    "NAME"      => GetMessage("COMPANY_IMG"),
			    ));
			?>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/company_text.php", Array(), Array(
			    "MODE"      => "html",
			    "NAME"      => GetMessage("COMPANY_TEXT"),
			    ));
			?>
		</div>
	</div>
	<br/><br/><br/>
</div>


<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"front-partners", 
	array(
		"IBLOCK_TYPE" => "aspro_digital_content",
		"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_partners"][0],
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "100000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
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
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"ITEM_IN_BLOCK" => "6",
		"SHOW_DETAIL_LINK" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"COMPONENT_TEMPLATE" => "front-partners",
		"SET_LAST_MODIFIED" => "N",
		"TITLE" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>
