<?$this->setFrameMode(true);?>
<?if($arResult["NavPageCount"] > 1):?>
	<?
	$count_item = 2;
	$arResult["nStartPage"] = $arResult["NavPageNomer"] - $count_item;
	$arResult["nStartPage"] = $arResult["nStartPage"] <= 0 ? 1 : $arResult["nStartPage"];
	$arResult["nEndPage"] = $arResult["NavPageNomer"] + $count_item;
	$arResult["nEndPage"] = $arResult["nEndPage"] > $arResult["NavPageCount"] ? $arResult["NavPageCount"] : $arResult["nEndPage"];
	$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
	$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
	if($arResult["NavPageNomer"] == 1){
		$bPrevDisabled = true;
	}
	elseif($arResult["NavPageNomer"] < $arResult["NavPageCount"]){
		$bPrevDisabled = false;
	}
	if($arResult["NavPageNomer"] == $arResult["NavPageCount"]){
		$bNextDisabled = true;
	}
	else{
		$bNextDisabled = false;
	}
	?>
	<?if(!$bNextDisabled){?>
		<div class="ajax_load_btn">
			<span class="more_text_ajax">
				<?=GetMessage('PAGER_SHOW_MORE')?>
				<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><path class="cls-spin" d="M902,1459h-4a1,1,0,0,1,0-2h1.7a5.441,5.441,0,0,0-4.2-2,5.5,5.5,0,1,0,4.611,8.54l0.011,0.01A0.991,0.991,0,0,1,902,1464a1.023,1.023,0,0,1-.13.47h0c-0.038.06-.086,0.12-0.127,0.18-0.017.02-.026,0.04-0.044,0.06a7.522,7.522,0,1,1-.7-9.27V1454a1,1,0,0,1,2,0v4A1,1,0,0,1,902,1459Z" transform="translate(-888 -1453)"/></svg>
			</span>
		</div>
	<?}?>
	<div class="module-pagination">
		<div class="nums">
			<ul class="flex-direction-nav">
				<li class="flex-nav-prev <?if($bPrevDisabled){echo " disabled";}?>"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="flex-prev"></a></li>
				<li class="flex-nav-next <?if($bNextDisabled){echo " disabled";}?>"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="flex-next"></a></li>
			</ul>
			<?if($arResult["nStartPage"] > 1):?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
				<span class='point_sep'></span>
			<?endif;?>
			<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
				<?if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
					<span class="cur"><?=$arResult["nStartPage"]?></span>
				<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
					<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
				<?else:?>
					<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
				<?endif;?>
				<?$arResult["nStartPage"]++;?>
			<?endwhile;?>
			<?if($arResult["nEndPage"] < $arResult["NavPageCount"]):?>
				<span class='point_sep'></span>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
			<?endif;?>
		</div>
	</div>
<?endif;?>