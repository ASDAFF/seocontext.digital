<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<?global $arTheme;
	$bOrderViewBasket = $arParams['ORDER_VIEW'];
	$basketURL = (isset($arTheme['URL_BASKET_SECTION']) && strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? $arTheme['URL_BASKET_SECTION']['VALUE'] : SITE_DIR.'cart/');
	?>
	<div class="title-tab-heading visible-xs"><?=$arParams["T_TARIF"];?></div>
	<div class="row">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="item-views tarifs">
					<?//items?>
					<div class="head-block">
						<div class="dynamic-block"></div>
						<div class="frame top">
							<div class="wraps">
								<table class="items_view top">
									<tr>
										<?foreach($arResult["ITEMS"] as $arItem):?>
											<?
											// edit/add/delete buttons for edit mode
											$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
											$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
											$dataItem = ($bOrderViewBasket ? CDigital::getDataItem($arItem) : false);
											?>
											<td>
												<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" <?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?>>
													<div class="body-info">
														<?if(strlen($arItem['FIELDS']['NAME'])):?>
															<div class="title">
																<?=$arItem['NAME']?>
															</div>
														<?endif;?>
														<?if($arItem['PROPERTIES']['PRICE']['VALUE']):?>
															<div class="price-block"><?=$arItem['PROPERTIES']['PRICE']['VALUE'];?></div>
														<?endif;?>
														<?if($bOrderViewBasket && $arItem['PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
															<div class="buy_block lg clearfix">
																<div class="buttons">
																	<span class="btn btn-default to_cart btn-xs animate-load" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
																	<a href="<?=$basketURL?>" class="btn btn-default in_cart btn-xs"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
																</div>
															</div>
														<?endif;?>
														<?if($arItem['PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
															<div class="order<?=($bOrderViewBasket ? ' basketTrue' : '')?>">
																<?if($arItem['PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
																	<span class="btn btn-default btn-xs animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_order_product'][0]?>" data-name="order_product" data-product="<?=$arItem['NAME']?>"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
																<?endif;?>
															</div>
														<?endif;?>
													</div>
												</div>
											</td>
										<?endforeach;?>
									</tr>
								</table>
							</div>
						</div>
						<?//slide?>
						<div class="wrapp_scrollbar rounded-nav">
							<div class="wr_scrollbar">
								<div class="scrollbar">
									<div class="handle">
										<div class="mousearea"></div>
									</div>
								</div>
							</div>
							<ul class="slider_navigation compare custom_flex">
								<ul class="flex-direction-nav">
									<li class="flex-nav-prev backward"><a class="flex-prev">Previous</a></li>
									<li class="flex-nav-next forward"><a class="flex-next">Next</a></li>
								</ul>
							</ul>
						</div>
					</div>
					
					<?//props?>
					<div class="main-block">
						<div class="prop_title_table dynamic-block"></div>
						<div class="frame props">
							<div class="wraps">
								<table class="data_table_props items_view">
									<?foreach($arResult['PROPS'] as $key => $arProp):?>
										<tr class="item-block">
											<td><?=$arProp['NAME'];?></td>
											<?foreach($arResult['ITEMS'] as $i => $arItem):?>
												<td>
													<div class="prop-block s<?=$j;?> <?=((isset($arItem['PROPERTIES'][$key]['TYPE']) && $arItem['PROPERTIES'][$key]['TYPE']) ? "icon-block ".$arItem['PROPERTIES'][$key]['TYPE'] : "" );?>">
														<?=$arItem['PROPERTIES'][$key]['VALUE'];?>
													</div>
												</td>
											<?endforeach;?>
										</tr>
									<?endforeach;?>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>