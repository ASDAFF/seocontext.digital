<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
use \Bitrix\Main\Localization\Loc;

$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (isset($arTheme['URL_BASKET_SECTION']) && strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? $arTheme['URL_BASKET_SECTION']['VALUE'] : SITE_DIR.'cart/');
$dataItem = ($bOrderViewBasket ? CDigital::getDataItem($arResult) : false);

$bViewTarif = (strlen($arResult["DISPLAY_PROPERTIES"]["LINK_TARIF"]["VALUE"]) && $arResult["PROPERTIES"]["LINK_TARIF"]["LINK_IBLOCK_ID"]);
?>

<?// show top banners start?>
<?$bShowTopBanner = (isset($arResult['SECTION_BNR_CONTENT'] ) && $arResult['SECTION_BNR_CONTENT'] == true);?>
<?if($bShowTopBanner):?>
	<?$this->SetViewTarget("section_bnr_content");?>
		<?CDigital::ShowTopDetailBanner($arResult, $arParams);?>
	<?$this->EndViewTarget();?>
<?endif;?>
<?// show top banners end?>

<div class="item" data-id="<?=$arResult['ID']?>"<?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?>>
	<?// element name?>
	<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
		<h2 itemprop="name"><?=$arResult['NAME']?></h2>
	<?endif;?>
	<div class="head<?=($arResult['GALLERY'] ? '' : ' wti')?>">
		<div class="row">
			<?if($arResult['GALLERY']):?>
				<div class="col-md-6 col-sm-6">
					<div class="row galery">
						<div class="inner zomm_wrapper-block">
							<?if($arResult['PROPERTIES']['HIT']['VALUE']):?>
								<div class="stickers">
									<div class="stickers-wrapper">
										<?foreach($arResult['PROPERTIES']['HIT']['VALUE_XML_ID'] as $key => $class):?>
											<div class="sticker_<?=strtolower($class);?>"><?=$arResult['PROPERTIES']['HIT']['VALUE'][$key]?></div>
										<?endforeach;?>
									</div>
								</div>
							<?endif;?>
							<div class="flexslider color-controls dark-nav" data-slice="Y" id="slider" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [1, 1, 1]}'>
								<ul class="slides items">
									<?$countAll = count($arResult['GALLERY']);?>
									<?foreach($arResult['GALLERY'] as $i => $arPhoto):?>
										<li class="item" data-slice-block="Y" data-slice-params='{"lineheight": -3}'>
											<a href="<?=$arPhoto['DETAIL']['SRC']?>" target="_blank" title="<?=$arPhoto['TITLE']?>" class="fancybox" data-fancybox-group="gallery">
												<img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" itemprop="image" />
												<span class="zoom"></span>
											</a>
										</li>
									<?endforeach;?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			<?endif;?>
			
			<div class="<?=($arResult['GALLERY'] ? 'col-md-6 col-sm-6' : 'col-md-12 col-sm-12');?>">
				<div class="info" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<?
					$frame = $this->createFrame('info')->begin('');
					$frame->setAnimation(true);
					?>
					<?if($arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID'] || strlen($arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']) || $arResult['BRAND_ITEM']):?>
						<div class="hh">
							<?if(strlen($arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
								<span class="status-icon <?=$arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="availability" href="http://schema.org/InStock"><?=$arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
							<?endif;?>
							<?if(strlen($arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
								<span class="article">
									<?=GetMessage('ARTICLE')?>&nbsp;<span><?=$arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span>
								</span>
							<?endif;?>
							<?if($arResult['BRAND_ITEM']):?>
								<div class="brand">
									<?if(!$arResult["BRAND_ITEM"]["IMAGE"]):?>
										<a href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>"><?=$arResult["BRAND_ITEM"]["NAME"]?></a>
									<?else:?>
										<a class="brand_picture" href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>">
											<img  src="<?=$arResult["BRAND_ITEM"]["IMAGE"]["src"]?>" alt="<?=$arResult["BRAND_ITEM"]["NAME"]?>" title="<?=$arResult["BRAND_ITEM"]["NAME"]?>" />
										</a>
									<?endif;?>
								</div>
								<div class="clearfix"></div>
							<?endif;?>
							<hr/>
						</div>
					<?endif;?>
					<?if(strlen($arResult['FIELDS']['PREVIEW_TEXT'])):?>
						<div class="previewtext" itemprop="description">
							<?// element detail text?>
							<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
								<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
							<?else:?>
								<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
							<?endif;?>
						</div>
						<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
							<div class="link-block-more">
								<span class="btn-inline sm"><?=GetMessage('MORE_TEXT_BOTTOM');?><i class="fa fa-angle-down"></i></span>
							</div>
						<?endif;?>
					<?endif;?>
					<div class="bottom-wrapper">
						<?if(strlen($arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE'])):?>
							<div class="price">
								<div class="price_new"><span class="price_val"><?=CDigital::FormatPriceShema($arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?></span></div>
								<?if(strlen($arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'])):?>
									<div class="price_old"><span class="price_val"><?=$arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span></div>
								<?endif;?>
							</div>
						<?endif;?>
						<?// element buy block?>
						<?//if($bOrderViewBasket && $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
						<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
							<?if($bOrderViewBasket):?>
								<div class="buy_block lg clearfix">
									<div class="counter pull-left">
										<div class="wrap">
											<span class="minus ctrl bgtransition"></span>
											<div class="input"><input type="text" value="1" class="count" /></div>
											<span class="plus ctrl bgtransition"></span>
										</div>
									</div>
									<div class="buttons pull-right">
										<span class="btn btn-default pull-right to_cart animate-load" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
										<a href="<?=$basketURL?>" class="btn btn-default pull-right in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
									</div>
								</div>
							<?endif;?>
							<div class="wrapper-block-btn order<?=($bOrderViewBasket ? ' basketTrue' : '')?>">
								<?if(!$bOrderViewBasket):?>
									<div class="wrapper">
										<span class="btn btn-default animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_order_product'][0]?>" data-name="order_product" data-product="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : GetMessage('S_ORDER_SERVISE'))?></span>
									</div>
								<?endif;?>								
									<div class="wrapper">
										<span class="btn btn-default white wide-block animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_question'][0]?>" data-autoload-need_product="<?=$arResult['NAME']?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span>
									</div>
							</div>
						<?else:?>
							<?if($arResult['PROPERTIES']['LINK_TARIF']['VALUE']):?>
								<div class="wrapper-block-btn">
									<div class="wrapper">
										<span class="btn btn-default choise" data-block=".tarif-link"><?=(strlen($arParams['S_CHOISE_PRODUCT']) ? $arParams['S_CHOISE_PRODUCT'] : GetMessage('S_CHOISE_PRODUCT'))?></span>
									</div>
							<?endif;?>

							<?// ask question?>
							<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
								<div class="wrapper">
									<span class="btn btn-default white animate-load  wide-block" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_question'][0]?>" data-autoload-need_product="<?=$arResult['NAME']?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span>
								</div>
							<?endif;?>

							<?if($arResult['PROPERTIES']['LINK_TARIF']['VALUE']):?>
								</div>
							<?endif;?>						
						<?endif;?>
					</div>
					
					<?// ask question?>
					<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
						<?$this->SetViewTarget('under_sidebar_content');?>
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
									<span><span class="btn btn-default btn-lg white animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_question'][0]?>" data-autoload-need_product="<?=$arResult['NAME']?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span></span>
								</div>
							</div>
						<?$this->EndViewTarget();?>
					<?endif;?>
					<?$frame->end();?>
				</div>
			</div>
		</div>
		<?/*tizers block start*/?>
		<?$useBrands = ('Y' == $arParams['BRAND_USE']);
		if($useBrands){?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "digital", array(
				"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"ELEMENT_ID" => $arResult['ID'],
				"ELEMENT_CODE" => "",
				"PROP_CODE" => $arParams["BRAND_PROP_CODE"],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => $arParams['CACHE_TIME'],
				"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
				"ELEMENT_COUNT" => 5,
				"WIDTH" => "60",
				"WIDTH_SMALL" => "60",
				"HEIGHT" => "60",
				"HEIGHT_SMALL" => "60",
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);?>
		<?}?>
		<?/*tizers block end*/?>
		
	</div>
</div>


<?$bShowSales =  (count($arResult['DISPLAY_PROPERTIES']['LINK_SALE']['VALUE'])>0);?>	

<?if($bShowSales):?>
	<?$GLOBALS['arrSaleFilter'] = array('ID' => $arResult['DISPLAY_PROPERTIES']['LINK_SALE']['VALUE']); ?>
	<div class="stockblock">
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"news1",
		array(
			"IBLOCK_TYPE" => "aspro_digital_content",
			"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_news"][0],
			"NEWS_COUNT" => "20",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "DESC",
			"FILTER_NAME" => "arrSaleFilter",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "PREVIEW_TEXT",			
				3 => "DATE_ACTIVE_FROM",
				4 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "PERIOD",
				1 => "REDIRECT",
				2 => "",
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
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"VIEW_TYPE" => "table",
			"BIG_BLOCK" => "Y",
			"IMAGE_POSITION" => "left",
			"COUNT_IN_LINE" => "2",
		),
		false, array("HIDE_ICONS" => "Y")
	);?>
	</div>
<?endif;?>
		
		
	<?
	
	$bShowDetailTextTab = strlen($arResult['FIELDS']['DETAIL_TEXT']);
	$bShowTarifTab = !empty($arResult['DISPLAY_PROPERTIES']['LINK_TARIF']['VALUE']);
	$bShowPropsTab = !empty($arResult['CHARACTERISTICS']);
	$bShowDocsTab = !empty($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']);
	$bShowVideoTab = !empty($arResult['VIDEO']);
	$bShowFaqTab = !empty($arResult['DISPLAY_PROPERTIES']['LINK_FAQ']['VALUE']);
	$bShowProjecTab = !empty($arResult['DISPLAY_PROPERTIES']['LINK_PROJECTS']['VALUE']);
	
	if($bShowTarifTab || $bShowDetailTextTab || $bShowPropsTab || $bShowDocsTab || $bShowVideoTab || $bShowFaqTab || $bShowProjecTab):?>
		<div class="tabs">
			<ul class="nav nav-tabs">
				<?$iTab = 0;?>
				<?if($bShowTarifTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#tarif" class="tarif-link" data-toggle="tab"><?=($arParams["T_TARIF"] ? $arParams["T_TARIF"] : Loc::getMessage("T_TARIF"));?></a></li>
				<?endif;?>
				<?if($bShowDetailTextTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#desc" data-toggle="tab"><?=($arParams["T_DESC"] ? $arParams["T_DESC"] : Loc::getMessage("T_DESC"));?></a></li>
				<?endif;?>
				<?if($bShowPropsTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#props" data-toggle="tab"><?=($arParams["T_CHARACTERISTICS"] ? $arParams["T_CHARACTERISTICS"] : Loc::getMessage("T_CHARACTERISTICS"));?></a></li>
				<?endif;?>
				<?if($bShowProjecTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#projects" class="projects-link" data-toggle="tab"><?=($arParams["T_PROJECTS"] ? $arParams["T_PROJECTS"] : Loc::getMessage("T_PROJECTS"));?></a></li>
				<?endif;?>
				<?if($bShowDocsTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#docs" data-toggle="tab"><?=($arParams["T_DOCS"] ? $arParams["T_DOCS"] : Loc::getMessage("T_DOCS"));?></a></li>
				<?endif;?>
				<?if($bShowFaqTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#faq" data-toggle="tab"><?=($arParams["T_FAQ"] ? $arParams["T_FAQ"] : Loc::getMessage("T_FAQ"));?></a></li>
				<?endif;?>
				<?if($bShowVideoTab):?>
					<li class="<?=(!($iTab++) ? 'active' : '')?>"><a href="#video" data-toggle="tab"><?=($arParams["T_VIDEO"] ? $arParams["T_VIDEO"] : Loc::getMessage("T_VIDEO"));?></a></li>
				<?endif;?>
			</ul>
			<div class="tab-content">
				<?$iTab = 0;?>
				<?if($bShowTarifTab):?>
					<div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="tarif">
						<?$GLOBALS['arrTarifFilter'] = array('ID' => $arResult['PROPERTIES']['LINK_TARIF']['VALUE']);?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"tarifs",
							array(
								"IBLOCK_TYPE" => "aspro_digital_content",
								"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_catalog"]["aspro_digital_tarif"][0],
								"NEWS_COUNT" => "20",
								"SORT_BY1" => "SORT",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "ID",
								"SORT_ORDER2" => "DESC",
								"FILTER_NAME" => "arrTarifFilter",
								"FIELD_CODE" => array(
									0 => "NAME",
									1 => "PREVIEW_TEXT",
									2 => "PREVIEW_PICTURE",
									3 => "",
								),
								"PROPERTY_CODE" => array(
									0 => "LINK",
									1 => "",
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
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"PAGER_TITLE" => "Новости",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"VIEW_TYPE" => "list",
								"IMAGE_POSITION" => "left",
								"COUNT_IN_LINE" => "3",
								"ORDER_VIEW" => $bOrderViewBasket,
								"T_TARIF" => ($arParams["T_TARIF"] ? $arParams["T_TARIF"] : Loc::getMessage("T_TARIF")),
								"S_ORDER_PRODUCT" => $arParams["S_ORDER_SERVISE"],
								"AJAX_OPTION_ADDITIONAL" => ""
							),
							false, array("HIDE_ICONS" => "Y")
						);?>
					</div>
				<?endif;?>
				<?if($bShowDetailTextTab):?>
					<div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="desc">
						<div class="title-tab-heading visible-xs"><?=($arParams["T_DESC"] ? $arParams["T_DESC"] : Loc::getMessage("T_DESC"));?></div>
						<div class="content" itemprop="description">
							<?// element detail text?>
							<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
								<p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
							<?else:?>
								<?=$arResult['FIELDS']['DETAIL_TEXT'];?>
							<?endif;?>
						</div>
					</div>
				<?endif;?>
				<?if($bShowProjecTab):?>
					<div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="projects">
						<?$GLOBALS['arrProjectFilter'] = array('ID' => $arResult['PROPERTIES']['LINK_PROJECTS']['VALUE']);?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"items-row",
							array(
								"IBLOCK_TYPE" => "aspro_digital_content",
								"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_projects"][0],
								"NEWS_COUNT" => "20",
								"SORT_BY1" => "SORT",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "ID",
								"SORT_ORDER2" => "DESC",
								"FILTER_NAME" => "arrProjectFilter",
								"FIELD_CODE" => array(
									0 => "NAME",
									1 => "PREVIEW_TEXT",
									2 => "PREVIEW_PICTURE",
									3 => "",
								),
								"PROPERTY_CODE" => array(
									0 => "LINK",
									1 => "",
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
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"PAGER_TITLE" => "Новости",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"VIEW_TYPE" => "list",
								"IMAGE_POSITION" => "left",
								"COUNT_IN_LINE" => "3",
								"SHOW_TITLE" => "Y",
								"T_PROJECTS" => ($arParams["T_PROJECTS"] ? $arParams["T_PROJECTS"] : Loc::getMessage("T_PROJECTS")),
								"AJAX_OPTION_ADDITIONAL" => ""
							),
							false, array("HIDE_ICONS" => "Y")
						);?>
					</div>
				<?endif;?>
				<?if($bShowPropsTab):?>
					<div class="tab-pane chars <?=(!($iTab++) ? 'active' : '')?>" id="props">
						<div class="title-tab-heading visible-xs"><?=($arParams["T_CHARACTERISTICS"] ? $arParams["T_CHARACTERISTICS"] : Loc::getMessage("T_CHARACTERISTICS"));?></div>
						<div class="char-wrapp">
							<table class="props_table">
								<?foreach($arResult['CHARACTERISTICS'] as $arProp):?>
									<tr class="char">
										<td class="char_name">
											<?if($arProp['HINT']):?>
												<div class="hint">
													<span class="icons" data-toggle="tooltip" data-placement="top" title="<?=$arProp['HINT']?>"></span>
												</div>
											<?endif;?>
											<span><?=$arProp['NAME']?></span>
										</td>
										<td class="char_value">
											<span>
												<?if(is_array($arProp['DISPLAY_VALUE'])):?>
													<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
														<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
															<?=$value.'&nbsp;/ '?>
														<?else:?>
															<?=$value?>
														<?endif;?>
													<?endforeach;?>
												<?else:?>
													<?=$arProp['DISPLAY_VALUE']?>
												<?endif;?>
											</span>
										</td>
									</tr>
								<?endforeach;?>
							</table>
						</div>
					</div>
				<?endif;?>
				<?if($bShowDocsTab):?>
					<div class="tab-pane docs-block <?=(!($iTab++) ? 'active' : '')?>" id="docs">
						<div class="title-tab-heading visible-xs"><?=($arParams["T_DOCS"] ? $arParams["T_DOCS"] : Loc::getMessage("T_DOCS"));?></div>
						<div class="row">
							<?foreach($arResult['PROPERTIES']['DOCUMENTS']['VALUE'] as $docID):?>
								<?$arItem = CDigital::get_file_info($docID);?>
								<div class="col-md-4">
									<?
									$fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
									$fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);

									?>
									<div class="blocks clearfix <?=$arItem["TYPE"];?>">
										<div class="inner-wrapper">
											<a href="<?=$arItem['SRC']?>" class="dark-color text" target="_blank"><?=$fileTitle?></a>
											<div class="filesize"><?=CDigital::filesize_format($arItem['FILE_SIZE']);?></div>
										</div>
									</div>
								</div>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?if($bShowFaqTab):?>
					<div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="faq">
						<?$GLOBALS['arrFaqFilter'] = array('ID' => $arResult['PROPERTIES']['LINK_FAQ']['VALUE']);?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"items-list",
							array(
								"IBLOCK_TYPE" => "aspro_digital_content",
								"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_faq"][0],
								"NEWS_COUNT" => "20",
								"SORT_BY1" => "SORT",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "ID",
								"SORT_ORDER2" => "DESC",
								"FILTER_NAME" => "arrFaqFilter",
								"FIELD_CODE" => array(
									0 => "PREVIEW_TEXT",
									1 => "",
								),
								"PROPERTY_CODE" => array(
									0 => "LINK",
									1 => "",
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
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"PAGER_TITLE" => "Новости",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"VIEW_TYPE" => "accordion",
								"IMAGE_POSITION" => "left",
								"SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
								"COUNT_IN_LINE" => "3",
								"SHOW_TITLE" => "Y",
								"T_TITLE" => ($arParams["T_FAQ"] ? $arParams["T_FAQ"] : Loc::getMessage("T_FAQ")),
								"AJAX_OPTION_ADDITIONAL" => ""
							),
							false, array("HIDE_ICONS" => "Y")
						);?>
					</div>
				<?endif;?>
				<?if($bShowVideoTab):?>
					<div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="video">
						<div class="title-tab-heading visible-xs"><?=($arParams["T_VIDEO"] ? $arParams["T_VIDEO"] : Loc::getMessage("T_VIDEO"));?></div>
						<div class="row video">
							<?foreach($arResult['VIDEO'] as $i => $arVideo):?>
								<div class="col-md-6 item">
									<div class="video_body">
										<video id="js-video_<?=$i?>" width="350" height="217"  class="video-js" controls="controls" preload="metadata" data-setup="{}">
											<source src="<?=$arVideo["path"]?>" type='video/mp4' />
											<p class="vjs-no-js">
												To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
											</p>
										</video>
									</div>
									<div class="title"><?=(strlen($arVideo["title"]) ? $arVideo["title"] : $i)?></div>
								</div>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
			</div>
		</div>
	<?endif;?>

	<?if(count($arResult['GALLERY_BIG'])>0):?>
	<div class="wraps gallerys">
		<hr/>
		<h5><?=($arParams["T_GALLERY"] ? $arParams["T_GALLERY"] : Loc::getMessage("T_GALLERY"));?></h5>
		<?if($arParams['GALLERY_TYPE'] == 'small'):?>
			<div class="small-gallery-block">
				<div class="flexslider unstyled row front bigs dark-nav" data-plugin-options='{"animation": "slide", "directionNav": false, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [4, 3, 2, 1]}'>
					<ul class="slides items">
						<?foreach($arResult['GALLERY_BIG'] as $i => $arPhoto):?>
							<li class="col-md-3 item">
								<div>
									<img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
								</div>
								<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancybox dark_block_animate" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>"></a>
							</li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
		<?else:?>
			<div class="gallery-block">
				<div class="gallery-wrapper">
					<div class="inner">
						<?if(count($arResult["GALLERY_BIG"]) > 1):?>
							<div class="small-gallery-wrapper">
								<div class="thmb1 flexslider unstyled small-gallery rounded-nav" data-plugin-options='{"slideshow": "false", "animation": "slide", "animationLoop": true, "itemWidth": 60, "itemMargin": 20, "minItems": 1, "maxItems": 9, "slide_counts": 1, "asNavFor": ".gallery-wrapper .bigs"}' id="carousel1">
									<ul class="slides items">	
										<?foreach($arResult["GALLERY_BIG"] as $arPhoto):?>
											<li class="item">
												<img class="img-responsive inline" border="0" src="<?=$arPhoto["THUMB"]["src"]?>" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
											</li>
										<?endforeach;?>
									</ul>
								</div>
							</div>
						<?endif;?>
						<div class="thmb1 flexslider unstyled row bigs color-controls" id="slider" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "sync": ".gallery-wrapper .small-gallery", "counts": [1, 1, 1]}'>
							<ul class="slides items">
								<?foreach($arResult['GALLERY_BIG'] as $i => $arPhoto):?>
									<li class="col-md-12 item">
										<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancybox" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
											<img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
											<span class="zoom"></span>
										</a>
									</li>
								<?endforeach;?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?endif;?>
	</div>
	<?endif;?>