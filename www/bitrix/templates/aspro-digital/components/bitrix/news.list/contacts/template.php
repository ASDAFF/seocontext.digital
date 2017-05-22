<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views-wrapper <?=$templateName;?>">
	
	<?if($arResult['SECTIONS']):?>
		<div class="row">
			<div class="maxwidth-theme">
				<div class="col-md-12">
					<table class="contacts-stores no-border">
						<?foreach($arResult['SECTIONS'] as $si => $arSection):?>
							<?$bHasSection = (isset($arSection['SECTION']) && $arSection['SECTION'])?>
							<?if($bHasSection):?>
								<?// edit/add/delete buttons for edit mode
								$arSectionButtons = CIBlock::GetPanelButtons($arSection['SECTION']['IBLOCK_ID'], 0, $arSection['SECTION']['ID'], array('SESSID' => false, 'CATALOG' => true));
								$this->AddEditAction($arSection['SECTION']['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['SECTION']['IBLOCK_ID'], 'SECTION_EDIT'));
								$this->AddDeleteAction($arSection['SECTION']['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['SECTION']['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
								<tr id="<?=$this->GetEditAreaId($arSection['SECTION']['ID'])?>">
									<td colspan="2"><h4><?=$arSection['SECTION']['NAME'];?></h4></td>
									<td class="hidden-xs"></td>
									<td class="hidden-xs hidden-sm"></td>
								</tr>
							<?endif;?>
							<?foreach($arSection['ITEMS'] as $i => $arItem):?>
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								// use detail link?
								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								// preview picture
								$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
								$imageSrc = ($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : false);
								$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
								?>

								<tr class="item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<?if($imageSrc):?>
										<td class="hidden-xs img">
											<?if($imageSrc):?>
												<img src="<?=$imageSrc;?>" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>" class="img-responsive"/>
											<?endif;?>
										</td>
									<?endif;?>
									<td class="hidden-xs" <?=(($arResult['ITEMS_HAS_IMG'] && !$imageSrc) ? 'colspan=2' : '');?>>
										<div class="title"><?=$arItem['NAME'];?></div>
										<?if($arItem['PROPERTIES']['SCHEDULE']['VALUE']):?>
											<div class="muted">
												<span class="icon-text schedule grey s25"><i class="fa fa-clock-o"></i> <span class="text"><?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'];?></span></span>
											</div>
										<?endif;?>
									</td>
									<td class="phone hidden-xs">
									<?if($arItem['PROPERTIES']['PHONE']['VALUE'])
									{
										foreach($arItem['PROPERTIES']['PHONE']['VALUE'] as $phone):?>
											<a href="tel:+<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>" class="black"><?=$phone;?></a>
										<?endforeach;
									}?>
									</td>
									<td class="visible-xs mobile-title-phone" colspan=3>
										<div class="row">
											<div class="col-xs-8">
												<div class="titles-block">
													<div class="title"><?=$arItem['NAME'];?></div>
													<?if($arItem['PROPERTIES']['SCHEDULE']['VALUE']):?>
														<div class="muted">
															<span class="icon-text schedule grey s25"><i class="fa fa-clock-o"></i> <span class="text"><?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'];?></span></span>
														</div>
													<?endif;?>
												</div>
											</div>
											<div class="col-xs-4">
												<div class="phones-block">
													<?if($arItem['PROPERTIES']['PHONE']['VALUE'])
													{
														foreach($arItem['PROPERTIES']['PHONE']['VALUE'] as $phone):?>
															<a href="tel:+<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>" class="black"><?=$phone;?></a>
														<?endforeach;
													}?>
												</div>
											</div>
										</div>
									</td>
									<td class="hidden-xs hidden-sm">
										<?if($arItem['PROPERTIES']['PAY_TYPE']['VALUE'])
										{
											foreach($arItem['PROPERTIES']['PAY_TYPE']['FORMAT'] as $arPays):?>
												<span class="icon-text grey s30">
													<?if($arPays['UF_ICON_CLASS']):?><i class="fa <?=$arPays['UF_ICON_CLASS'];?>"></i>
													<?elseif($arPays['UF_FILE']):?>
														<i><img src="<?=CFile::GetPath($arPays['UF_FILE']);?>" width="17" /></i>
													<?endif;?> <?=$arPays['UF_NAME'];?>
												</span>
											<?endforeach;
										}?>
									</td>
								</tr>
							<?endforeach;?>
						<?endforeach;?>
					</table>
				</div>
			</div>
		</div>
	<?endif;?>
</div>