<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	switch($qntyItems)
	{
		case 2:
			$colmd = 6;
			$colsm = 12;
			break;
		case 3:
			$colmd = 4;
			$colsm = 6;
			break;
		default:
			$colmd = 3;
			$colsm = 4;
			break;
	}
	if($arResult['HAS_WIDE_BLOCK'])
	{
		$colmd = 3;
		$colsm = 6;
		$i = 0;
	}
	$isAjax = (isset($_GET["AJAX_REQUEST"]) && $_GET["AJAX_REQUEST"] == "Y");
	if($isAjax && array_key_exists("PAGEN_1", $_GET))
	{
		$colmd = 3;
		$colsm = 12;
	}
	?>
	<?if(!$isAjax):?>
	<div class="row margin0 block-with-bg">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="banners-small front">
					<div class="items row">
	<?endif;?>
						<?foreach($arResult['ITEMS'] as $key => $arItem):?>
							<?
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							// preview image
							$bImage = (is_array($arItem['PREVIEW_PICTURE']) && $arItem['PREVIEW_PICTURE']['SRC']);
							$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
							// link
							$bLink = strlen($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']);
							$mclass = '';
							$isNormalBlock = $isWideBlock = false;
							
							if($arResult['HAS_WIDE_BLOCK'])
							{
								$colmd_size = $colmd;
								if($key >= 5)
								{
									$i = 0;
									$isNormalBlock = true;
									$colsm_size = $colsm;
								}
								else
								{
									$colsm_size = 12;
									$mclass	= 'custom-md';
								}
								if($arItem['PROPERTIES']['BIG_BLOCK']['VALUE'] == 'Y')
								{
									$i = 0;
									$colmd_size = 6;
									$colsm_size = 12;
									$isWideBlock = true;
								}
							}
							else
							{
								$colmd_size = $colmd;
								$colsm_size = $colsm;
							}

							if($arResult['HAS_WIDE_BLOCK']):
								if(!$i):?>
									<div class="col-md-<?=$colmd_size?> col-sm-<?=$colsm_size?> <?=$mclass;?>">
								<?endif;?>
							<?else:?>
								<div class="col-md-<?=$colmd_size?> col-sm-<?=$colsm_size?>">
							<?endif;?>
								<div class="item<?=($bImage ? '' : ' wti')?> <?=($isWideBlock ? 'wide-block' : 'normal-block')?>"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<div class="inner-item">
										<?if($bImage):?>
											<div class="image shine">
												<?if($bLink):?><a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"><?endif;?>
												<?if($arItem['DISPLAY_PROPERTIES']['TYPE']['VALUE']):?>
													<div class="type-block"><?=$arItem['DISPLAY_PROPERTIES']['TYPE']['VALUE'];?></div>
												<?endif;?>
												<img src=<?=$imageSrc?> alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
												<?if($bLink):?></a><?endif;?>
											</div>
										<?endif;?>
										<div class="title">
											<?if($bLink):?><a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"><?endif;?>
												<?=$arItem['NAME']?>
											<?if($bLink):?></a><?endif;?>
											<?if($arItem['PREVIEW_TEXT'] && $isWideBlock):?>
												<div class="prev_text-block"><?=$arItem['PREVIEW_TEXT'];?></div>
											<?endif;?>
											<?if($arItem['DISPLAY_ACTIVE_FROM']):?>
												<div class="date-block"><?=$arItem['DISPLAY_ACTIVE_FROM'];?></div>
											<?endif;?>
										</div>
									</div>
								</div>
							<?if($arResult['HAS_WIDE_BLOCK']):
								if(!$i):
									if($qntyItems-1 == $key || $arItem['PROPERTIES']['BIG_BLOCK']['VALUE'] == 'Y' || $isNormalBlock):?>
										</div>
										<?$i = 0;?>
									<?else:?>
										<?$i++;?>
									<?endif;?>
								<?else:
									$i = 0;?>
									</div>
								<?endif;?>
							<?else:?>
								</div>
							<?endif;?>
						<?endforeach;?>
	<?if(!$isAjax):?>
					</div>
	<?endif;?>
					<div class="bottom_nav" <?=($isAjax ? "style='display: none; '" : "");?>>
						<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
					</div>
	<?if(!$isAjax):?>
				</div>
			</div>
		</div>
	</div>
	<?endif;?>
<?endif;?>