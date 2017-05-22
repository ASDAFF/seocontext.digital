<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<div class="row margin0 greyline review-block block-with-bg">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<?
				$qntyItems = count($arResult['ITEMS']);

				global $arTheme;
				$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
				$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
				$bAnimation = (bool)$slideshowSpeed;
				?>
				<div class="item-views reviews front blocks">
					<h3 class="text-center"><?=($arParams["TITLE"] ? $arParams["TITLE"] : Loc::getMessage("TITLE"));?></h3>
					<div class="flexslider unstyled row navigation-vcenter dark-nav" data-plugin-options='{"directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, <?=($slideshowSpeed >= 0 ? '"slideshowSpeed": '.$slideshowSpeed.',' : '')?> <?=($animationSpeed >= 0 ? '"animationSpeed": '.$animationSpeed.',' : '')?> "counts": [1, 1, 1]}'>
						<ul class="slides items" data-slice="Y">
							<?foreach($arResult['ITEMS'] as $i => $arItem):?>
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								// use detail link?
								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								// preview image
								$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
								$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 150, 'height' => 150), BX_RESIZE_IMAGE_EXACT, true) : array());
								$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/svg/Staff_noimage2.svg');
								?>
								<li class="col-md-12">
									<div class="item" data-slice-block="Y" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
											<?if($imageSrc):?>
												<div class="image <?=($bImage ? '' : 'wpi')?>">
													<div class="image-wrapper">
														<div class="image-inner">
															<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
														</div>
													</div>
												</div>
											<?endif;?>
										<?if($bDetailLink):?></a><?endif;?>
										<div class="title">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
											<?=$arItem['NAME'];?><?if($arItem['PROPERTIES']['POST']['VALUE']):?>, <?=$arItem['PROPERTIES']['POST']['VALUE'];?><?endif;?>
											<?if($bDetailLink):?></a><?endif;?>
										</div>
										<?if($arItem['PROPERTIES']['COMPANY']['VALUE']):?>
											<div class="company"><?=$arItem['PROPERTIES']['COMPANY']['VALUE'];?></div>
										<?endif;?>
										<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
											<div class="preview-text">
												<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
													<p><?=$arItem['FIELDS']['PREVIEW_TEXT'];?></p>
												<?else:?>
													<?=$arItem['FIELDS']['PREVIEW_TEXT'];?>
												<?endif;?>
											</div>
											<?if(strlen($arParams['PREVIEW_TRUNCATE_LEN']) && strlen($arItem['~PREVIEW_TEXT']) > $arParams['PREVIEW_TRUNCATE_LEN']):?>
												<div class="link-block-more">
													<span class="btn-inline sm underborder animate-load" data-event="jqm" data-param-id="<?=$arItem['ID'];?>" data-param-type="review" data-name="review"><?=Loc::getMessage('MORE');?></span>
												</div>
											<?endif;?>
										<?endif;?>
									</div>
								</li>
							<?endforeach;?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>