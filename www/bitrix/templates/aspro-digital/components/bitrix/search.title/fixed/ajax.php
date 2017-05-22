<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (empty($arResult["CATEGORIES"])) return;?>
<div class="bx_searche">
	<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
		<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?//=$arCategory["TITLE"]?>
			<?if($category_id === "all"):?>
				<div class="bx_item_block all_result">
					<div class="maxwidth-theme">
						<div class="bx_item_element">
							<a class="all_result_title btn btn-default white bold" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			<?elseif(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
				$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];?>
				<a class="bx_item_block" href="<?=$arItem["URL"]?>">
					<div class="maxwidth-theme">
						<div class="bx_img_element">
							<?if(is_array($arElement["PICTURE"])):?>
								<div class="bx_image" style="background-image: url('<?=$arElement["PICTURE"]["src"]?>')"></div>
							<?endif;?>
						</div>
						<div class="bx_item_element">
							<span><?=$arItem["NAME"]?></span>
						</div>
						<div style="clear:both;"></div>
					</div>
				</a>
			<?else:?>
				<?if($arItem["MODULE_ID"]):?>
					<a class="bx_item_block others_result" href="<?=$arItem["URL"]?>">
						<div class="maxwidth-theme">
							<div class="bx_item_element">
								<span><?=$arItem["NAME"]?></span>
							</div>
							<div style="clear:both;"></div>
						</div>
					</a>
				<?endif;?>
			<?endif;?>
		<?endforeach;?>
	<?endforeach;?>
</div>