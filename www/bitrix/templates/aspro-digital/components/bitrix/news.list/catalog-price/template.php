<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
global $arTheme;
$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
$bShowOrderButton = in_array('FORM_ORDER', $arParams['PROPERTY_CODE']);
$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (strlen(trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']) : '');
?>
<?
$bHasSection = false;
if(isset($arResult['SECTION_CURRENT']) && $arResult['SECTION_CURRENT'])
	$bHasSection = true;
if($bHasSection)
{
	// edit/add/delete buttons for edit mode
	$arSectionButtons = CIBlock::GetPanelButtons($arParams['IBLOCK_ID'], 0, $arResult['SECTION_CURRENT']['ID'], array('SESSID' => false, 'CATALOG' => true));
	$this->AddEditAction($arResult['SECTION_CURRENT']['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT'));
	$this->AddDeleteAction($arResult['SECTION_CURRENT']['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="section" id="<?=$this->GetEditAreaId($arResult['SECTION_CURRENT']['ID'])?>">
	<?
}?>
<div class="catalog item-views price">
	<?if($arResult['ITEMS']):?>
		<?if($arParams['DISPLAY_TOP_PAGER']):?>
			<?=$arResult['NAV_STRING']?>
		<?endif;?>
		<div class="row items" itemscope itemtype="http://schema.org/ItemList">
			<?foreach($arResult['ITEMS'] as $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				if($bShowImage){
					$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
					$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 101, 'height' => 100), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
					$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
					$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
				}
				// use order button?
				$bOrderButton = ($arItem['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES');
				// use status label?
				$bStatusLabel = strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']);
				// show price?
				$bPrice = strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']);
				$dataItem = ($bOrderViewBasket ? CDigital::getDataItem($arItem) : false);
				?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>"<?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?> itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
						<div class="row">
							<?if($bShowImage):?>
								<div class="col-md-2 col-sm-2 col-xs-2 img-block">
									<div class="image">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url">
										<?elseif($imageDetailSrc):?><a href="<?=$imageDetailSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox" itemprop="url">
										<?endif;?>
											<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" itemprop="image" />
										<?if($bDetailLink):?></a>
										<?elseif($imageDetailSrc):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
							<div class="<?=($bShowImage ? 'col-md-10 col-sm-10 col-xs-10' : 'col-md-12 col-sm-12 col-xs-12')?>">
								<div class="text">
									<div class="row">
										<?$colmd = 12 - 3 - ($bOrderButton && !$bOrderViewBasket ? 2 :0) - ($bOrderViewBasket ? 3 : 0);?>
										<div class="col-md-6 col-sm-6 text-block">
											<?// element name?>
											<?if(strlen($arItem['FIELDS']['NAME'])):?>
												<div class="title">
													<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url" class="dark-color"><?endif;?>
														<span itemprop="name"><?=$arItem['NAME']?></span>
													<?if($bDetailLink):?></a><?endif;?>
												</div>
											<?endif;?>

											<?if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
												<span class="status-icon <?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
											<?endif;?>
											<?// element article?>
											<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
												<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
											<?endif;?>
										</div>

										<?// element status?>

										<?// element price?>
										<div class="col-md-2 col-sm-3 price-block">
											<?if($bPrice):?>
												<div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
													<div class="price_new">
														<span class="price_val"><?=CDigital::FormatPriceShema($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?></span>
													</div>
													<?if($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']):?>
														<div class="price_old">
															<span class="price_val"><?=$arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span>
														</div>
													<?endif;?>
												</div>
											<?endif;?>
										</div>
										<?if($bOrderButton):?>
											<?// element order button?>
											<?if($bOrderButton && !$bOrderViewBasket):?>
												<div class="col-md-4 col-sm-3">
													<span class="btn btn-default pull-right animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_order_product'][0]?>" data-product="<?=$arItem['NAME']?>" data-name="order_product"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
												</div>
											<?elseif($bOrderViewBasket && $bOrderButton):?>
												<div class="col-md-4 col-sm-3 buy_block clearfix">
													<div class="counter">
														<div class="wrap">
															<span class="minus ctrl bgtransition"></span>
															<div class="input"><input type="text" value="1" class="count" maxlength="20" /></div>
															<span class="plus ctrl bgtransition"></span>
														</div>
													</div>
													<div class="buttons">
														<span class="btn btn-default to_cart animate-load" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
														<a href="<?=$basketURL;?>" class="btn btn-default in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
													</div>
												</div>
											<?endif;?>
										<?else:?>
											<span class="btn btn-default"><?=(strlen($arParams['TO_ALL']) ? $arParams['TO_ALL'] : GetMessage('TO_ALL'))?></span>
										<?endif;?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>

		<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
			<?=$arResult['NAV_STRING']?>
		<?endif;?>
	<?endif;?>
</div>
<?if($bHasSection):?>
	</div>
<?endif;?>
<?$frame->end();?>