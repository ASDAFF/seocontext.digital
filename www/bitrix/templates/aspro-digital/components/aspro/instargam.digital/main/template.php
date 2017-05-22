<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);
if(isset($_POST["AJAX_REQUEST_INSTAGRAM"]) && $_POST["AJAX_REQUEST_INSTAGRAM"] == "Y"):
	$inst=new CInstargramDigital($arParams["TOKEN"]);
	$arInstagramPosts=$inst->getInstagramPosts();
	$arInstagramUser=$inst->getInstagramUser();
	if($arInstagramPosts && !$arInstagramPosts["meta"]["error_message"]):?>
		<div class="item-views front blocks">
			<h3 class="text-center"><?=($arParams["TITLE"] ? $arParams["TITLE"] : GetMessage("TITLE"));?></h3>
			<div class="instagram clearfix">
				<div class="container">
					<?$index = 0;?>
					<div class="items row">
						<div class="item user">
							<div class="body2">
								<div class="title"><h4><?=GetMessage('INSTAGRAM_TITLE');?></h4></div>
								<div class="description"><?=GetMessage('INSTAGRAM_DESCRIPTION');?></div>
								<div class="link"><a href="https://www.instagram.com/<?=$arInstagramUser['data']['username']?>/" target="_blank"><?=$arInstagramUser['data']['username']?></a></div>
							</div>
						</div>
						<?foreach ($arInstagramPosts['data'] as $arItem):?>
							<div class="item">
								<div class="image shine"><a href="<?=$arItem['link']?>" target="_blank"><img src="<?=$arItem['images']['standard_resolution']['url']?>" alt="<?=$arItem['caption']['text']?>" title="<?=$arItem['caption']['text']?>" /></a></div>
							</div>
							<?if ($index == 3) break;?>
							<?++$index;?>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
<?else:?>
	<div class="row margin0">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="instagram_ajax loader_circle"></div>
			</div>
		</div>
	</div>
<?endif;?>