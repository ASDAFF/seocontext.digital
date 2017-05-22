<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<div class="row margin0 company-block block-with-bg" <?=($arResult["BG_VALUE"] ? "style='background:url(".$arResult["BG_VALUE"]["SRC"].") no-repeat;'" : "");?> data-type="parallax-bg">
	<div class="maxwidth-theme">
		<div class="col-md-12">
			<div class="item-views front blocks list-type-block">
				<h3 class="text-center"><?=$arResult["NAME"];?></h3>
				<?if($arResult["PREVIEW_TEXT"] && (isset($arResult['FIELDS']['PREVIEW_TEXT']) && $arResult['FIELDS']['PREVIEW_TEXT'])):?>
					<div class="preview-text"><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></div>
				<?endif;?>
				<?if(isset($arResult['COMPANY_PROPS']) && $arResult['COMPANY_PROPS']):?>
					<div class="props row">
						<?
						switch(count($arResult['COMPANY_PROPS'])):
							case 1:
								$col_md = $col_sm = 12;
								break;
							case 2:
								$col_md = $col_sm = 6;
								break;
							case 3:
								$col_md = 4;
								$col_sm = 12;
								break;
							default:
								$col_md = 3;
								$col_sm = 6;
								break;
						endswitch;
						?>
						<?foreach($arResult['COMPANY_PROPS'] as $arProp):?>
							<div class="col-md-<?=$col_md;?> col-sm-<?=$col_sm;?>">
								<div class="item noborder clearfix">
									<?if(isset($arProp['UF_FILE']) && $arProp['UF_FILE']):?>
										<div class="image"><img src="<?=$arProp['UF_FILE_FORMAT']['SMALL']['src']?>" alt="<?=$arProp['UF_NAME']?>" title="<?=$arProp['UF_NAME']?>" /></div>
									<?endif;?>
									<div class="body-info">
										<?if(isset($arProp['UF_DESCRIPTION']) && $arProp['UF_DESCRIPTION']):?>
											<div class="value"><?if(isset($arProp['UF_FULL_DESCRIPTION']) && $arProp['UF_FULL_DESCRIPTION']):?><?=$arProp['UF_FULL_DESCRIPTION'];?><?endif;?><span <?=((isset($arProp['UF_CLASS']) && $arProp['UF_CLASS']) ? "class=".$arProp['UF_CLASS'] : "")?> data-value="<?=$arProp['UF_DESCRIPTION'];?>"><?=((int)$arProp['UF_DESCRIPTION'] ? 0 : $arProp['UF_DESCRIPTION']);?></span></div>
										<?endif;?>
										<div class="title"><?=$arProp['UF_NAME']?></div>
									</div>
								</div>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
			</div>
		</div>
	</div>
</div>