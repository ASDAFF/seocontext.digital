<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$this->setFrameMode(true);
if($arParams["DISPLAY_PICTURE"] != "N"){
	$picture = ($arResult["FIELDS"]["DETAIL_PICTURE"] ? "DETAIL_PICTURE" : "PREVIEW_PICTURE");
	CDigital::getFieldImageData($arResult, array($picture));
	$arPhoto = $arResult[$picture];
	if($arPhoto){
		$arImgs[] = array(
			'DETAIL' => $arPhoto,
			'PREVIEW' => CFile::ResizeImageGet($arPhoto["ID"], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
			'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
		);
	}
}
?>
<div class="detail <?=($templateName = $component->{"__parent"}->{"__template"}->{"__name"})?>">
	<article>
		<?// images?>
		<?if($arImgs):?>
			<div class="detailimage">
				<?if($arImgs):?>
					<div class="img-partner">
						<img src="<?=$arImgs[0]["DETAIL"]["SRC"]?>" title="<?=$arImgs[0]["TITLE"]?>" alt="<?=$arImgs[0]["ALT"]?>" class="img-responsive" />
					</div>
				<?endif;?>
				<?if(strlen($arResult["FIELDS"]["PREVIEW_TEXT"].$arResult["FIELDS"]["DETAIL_TEXT"])):?>
					<div class="preview"><?=$arResult["FIELDS"]["PREVIEW_TEXT"];?></div>
				<?endif;?>
			</div>
		<?endif;?>
		
		<div class="post-content">
			<?if($arParams["DISPLAY_NAME"] != "N" && strlen($arResult["NAME"])):?>
				<h2><?=$arResult["NAME"]?></h2>
			<?endif;?>
			<div class="content">
				<?// text?>
				<?if(strlen($arResult["FIELDS"]["PREVIEW_TEXT"].$arResult["FIELDS"]["DETAIL_TEXT"])):?>
					<div class="text">
						<?if($arResult["DETAIL_TEXT_TYPE"] == "text"):?>
							<p><?=$arResult["FIELDS"]["DETAIL_TEXT"];?></p>
						<?else:?>
							<?=$arResult["FIELDS"]["DETAIL_TEXT"];?>
						<?endif;?>
					</div>
				<?endif;?>
				
				<?// display properties?>
				<?if($arResult["DISPLAY_PROPERTIES"]):?>
					<hr/>
					<div class="properties">
						<?foreach($arResult["DISPLAY_PROPERTIES"] as $PCODE => $arProperty):?>
							<?$bIconBlock = ($PCODE == 'EMAIL' || $PCODE == 'PHONE' || $PCODE == 'SITE');?>
							<div class="inner-wrapper">
								<div class="property <?=($bIconBlock ? "icon-block" : "");?> <?=strtolower($PCODE);?>">
									<?if(!$bIconBlock):?>
										<?=$arProperty['NAME']?>:&nbsp;
									<?endif;?>
									<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
										<?$val = implode("&nbsp;/ ", $arProperty["DISPLAY_VALUE"]);?>
									<?else:?>
										<?$val = $arProperty["DISPLAY_VALUE"];?>
									<?endif;?>
									<?if($PCODE == "SITE"):?>
										<!--noindex-->
										<a href="<?=(strpos($arProperty['VALUE'], 'http') === false ? 'http://' : '').$arProperty['VALUE'];?>" rel="nofollow" target="_blank">
											<?=$arProperty['VALUE'];?>
										</a>
										<!--/noindex-->
									<?elseif($PCODE == "EMAIL"):?>
										<a href="mailto:<?=$val?>"><?=$val?></a>
									<?else:?>
										<?=$val?>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
			</div>
		</div>
	</article>
</div>