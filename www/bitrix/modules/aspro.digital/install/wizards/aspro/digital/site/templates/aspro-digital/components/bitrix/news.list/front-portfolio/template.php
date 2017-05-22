<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult["ITEMS"] ):?>
	<?$qnt_items = count($arResult["ITEMS"]);?>
	<div class="row margin0 block-with-bg">
		<div class="item-views blocks portfolio">
			<div class="wrap-portfolio-front">
				<?if($arParams["TITLE_BLOCK"]){?>
					<div class="container-fluid">
						<h2 class="text-center big-margin"><?=$arParams["TITLE_BLOCK"];?></h2>
					</div>
				<?}?>
				<div class="row items" itemscope itemtype="http://schema.org/ItemList">
					<div class="col-md-3 col-sm-12 item1">
						<div class="inner-wrapper-text <?=($qnt_items > 3 ? 'big' : 'small');?>">
							<div class="main-wrapper">
								<?$title_block=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('NEWS_TITLE'));
								$title_block_all=($arParams["LINK_BLOCK_TEXT"] ? $arParams["LINK_BLOCK_TEXT"] : GetMessage('ALL_NEWS'));
								$url=($arParams["ALL_URL"] ? $arParams["ALL_URL"] : "projects/");?>
								<div class="title"><?=$title_block;?></div>
								<div class="text"><?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/portfolio_description.php", Array(), Array("MODE" => "html", "NAME" => "Portfolio"));?></div>
								<div class="link"><a href="<?=SITE_DIR.$url;?>" class="btn btn-default white white-bg btn-lg"><?=$title_block_all;?></a></div>
							</div>
						</div>
					</div>
					<div class="col-md-9 col-sm-12 portfolio-items item1 <?=($qnt_items%2 == 0 ? 'even' : 'odd');?>">
						<div class="row">
							<?$i=1;?>
							<?foreach($arResult["ITEMS"] as $arItem):?>
								<?
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CATALOG_ELEMENT_DELETE_CONFIRM')));
								?>
								<div class="col-md-4 col-sm-6">
									<div class="item animated-block animated delay0<?=$i;?> duration06" data-animation="zoomIn" id="<?=$this->GetEditAreaId($arItem['ID'])?>" >
										<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="dark_block_animate">
											<div class="text">
												<div class="cont">
													<div class="title">
														<span itemprop="name"><?=$arItem['NAME']?></span>
														<div class="text_more"><div class="mores"><?=GetMessage('SHOW_PROJECT');?></div></div>
													</div>			
												</div>
											</div>
										</a>
										<div class="img_block scale_block_animate" style="background-image: url('<?=$arItem['PREVIEW_PICTURE']["SRC"];?>');">
											<?/*<img src="<?=$arItem['PREVIEW_PICTURE']["SRC"];?>" />*/?>
										</div>
									</div>
								</div>
								<?$i+=2;?>
							<?endforeach;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>