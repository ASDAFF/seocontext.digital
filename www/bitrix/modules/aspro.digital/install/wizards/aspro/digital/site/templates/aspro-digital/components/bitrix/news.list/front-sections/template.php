<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if($arResult['SECTIONS']):?>
	<div class="row margin0">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="item-views catalog1 teasers front icons sections blocks">
					<?if($arParams["TITLE"]):?>
						<h3 class="text-center"><?=($arParams["TITLE"] ? $arParams["TITLE"] : GetMessage("TITLE"));?></h3>
					<?endif;?>
					<div class="items row margin0 <?=$arParams['VIEW_TYPE_SECTION'];?>">
						<?foreach($arResult['SECTIONS'] as $arItem):?>
							<?
							// edit/add/delete buttons for edit mode
							$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
							$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

							// preview picture
							if($bShowSectionImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE'])){
								$bImage = strlen($arItem['~PICTURE']);
								$arSectionImage = ($bImage ? CFile::ResizeImageGet($arItem['~PICTURE'], array('width' => 254, 'height' => 254), BX_RESIZE_IMAGE_PROPORTIONAL, true) : array());
								$imageSectionSrc = ($bImage ? $arSectionImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_sections.png');
							}
							?>
							<div class="col-md-6 col-sm-12">
								<div class="item <?=($bShowSectionImage ? '' : ' wti')?> slice-item <?=$arParams['IMAGE_CATALOG_POSITION'];?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<?// icon or preview picture?>
									<?if($bShowSectionImage):?>
										<div class="image">
											<a href="<?=$arItem['SECTION_PAGE_URL']?>">
												<img src="<?=$imageSectionSrc?>" alt="<?=( $arItem['PICTURE']['ALT'] ? $arItem['PICTURE']['ALT'] : $arItem['NAME']);?>" title="<?=( $arItem['PICTURE']['TITLE'] ? $arItem['PICTURE']['TITLE'] : $arItem['NAME']);?>" class="img-responsive" />
											</a>
										</div>
									<?endif;?>
									
									<div class="info">
										<?// section name?>
										<?if(in_array('NAME', $arParams['FIELD_CODE'])):?>
											<div class="title">
												<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="dark-color">
													<?=$arItem['NAME']?>
												</a>
											</div>
										<?endif;?>

										<?// section child?>
										<?if($arItem['CHILD']):?>
											<div class="text childs">
												<ul>
													<?foreach($arItem['CHILD'] as $arSubItem):?>
														<li><a href="<?=($arSubItem['SECTION_PAGE_URL'] ? $arSubItem['SECTION_PAGE_URL'] : $arSubItem['DETAIL_PAGE_URL'] );?>"><?=$arSubItem['NAME']?></a></li>
													<?endforeach;?>
												</ul>
											</div>
										<?endif;?>
										
									</div>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>