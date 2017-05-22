<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);
if($arResult["TABS"]):?>
	<div class="row margin0 block-with-bg">
		<div class="item-views catalog sections1 front blocks">
			<h3 class="text-center"><?=($arParams["TITLE"] ? $arParams["TITLE"] : GetMessage("TITLE"));?></h3>
			<?$arParams['SET_TITLE'] = 'N';$arParamsTmp = urlencode(serialize($arParams));?>
			<span class='request-data' data-value='<?=$arParamsTmp?>'></span>
			<div class="tabs_ajax">
				<div class="items head-block" <?=(count($arResult["TABS"]) == 1 ? "style='display:none;'" : "");?>>
					<div class="row margin0">
						<div class="maxwidth-theme">
							<div class="col-md-12">
								<?foreach($arResult["TABS"] as $key => $arItem):?>
									<div class="item-link <?=(!$key ? 'active clicked' : '');?>">
										<div class="title btn-inline black">
											<span><?=$arItem['NAME']?></span>
										</div>
									</div>
								<?endforeach;?>
							</div>
						</div>
					</div>
				</div>
				<div class="body-block">
					<div class="row margin0">
						<div class="maxwidth-theme">
							<div class="col-md-12">
								<?foreach($arResult["TABS"] as $key => $arItem):?>
									<div class="item-block <?=(!$key ? 'active opacity1' : '');?>" data-filter="<?=($arItem["FILTER"] ? urlencode(serialize($arItem["FILTER"])) : '');?>">
										<?if(!$key)
										{
											if($arItem["FILTER"])
												$GLOBALS[$arParams["FILTER_NAME"]] = $arItem["FILTER"];

											include(str_replace("//", "/", $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/mainpage/comp_catalog_ajax.php"));
										}?>
									</div>
								<?endforeach;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>