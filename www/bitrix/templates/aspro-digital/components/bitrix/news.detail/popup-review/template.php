<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?$bImage = strlen($arResult['FIELDS']['PREVIEW_PICTURE']['SRC']);
$arImage = ($bImage ? CFile::ResizeImageGet($arResult['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 150, 'height' => 150), BX_RESIZE_IMAGE_EXACT, true) : array());
$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/svg/Staff_noimage2.svg');?>
<div class="popup review-detail">
	<div class="item-views reviews front">
		<div class="item">
			<div class="header-block">
				<?if($imageSrc):?>
					<div class="image <?=($bImage ? '' : 'wpi')?>">
						<div class="image-wrapper">
							<div class="image-inner">
								<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arResult['PREVIEW_PICTURE']['ALT'] : $arResult['NAME'])?>" title="<?=($bImage ? $arResult['PREVIEW_PICTURE']['TITLE'] : $arResult['NAME'])?>" />
							</div>
						</div>
					</div>
				<?endif;?>
				<div class="body-info">
					<div class="title">
						<?=$arResult['NAME'];?><?if($arResult['PROPERTIES']['POST']['VALUE']):?>, <?=$arResult['PROPERTIES']['POST']['VALUE'];?><?endif;?>
					</div>
					<?if($arResult['PROPERTIES']['COMPANY']['VALUE']):?>
						<div class="company"><?=$arResult['PROPERTIES']['COMPANY']['VALUE'];?></div>
					<?endif;?>
				</div>
			</div>
			<div class="bottom-block">
				<?if($arResult["PREVIEW_TEXT"] && (isset($arResult['FIELDS']['PREVIEW_TEXT']) && $arResult['FIELDS']['PREVIEW_TEXT'])):?>
					<div class="preview-text"><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></div>
				<?endif;?>
				<div class="close-block">
					<span class="btn btn-default btn-lg jqmClose"><?=Loc::getMessage('CLOSE');?></span>
				</div>
			</div>
		</div>
	</div>
</div>