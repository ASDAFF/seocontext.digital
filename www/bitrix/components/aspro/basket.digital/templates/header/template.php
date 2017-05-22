<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);

$bItems = ($arResult['ITEMS_COUNT'] > 0 ? true : false);

$title_text = GetMessage("TITLE_BASKET", array("#SUMM#" => $arResult['ALL_SUM']));
if(intval($arResult['ITEMS_COUNT']) <= 0)
	$title_text = GetMessage("EMPTY_BASKET");
?>
<div class="basket_top sep<?=(intval($arResult['ITEMS_COUNT']) <= 0 ? ' empted_cart' : '')?>">
	<div class="b_wrap">
		<a href="<?=$arParams['PATH_TO_BASKET']?>" class="icon" title="<?=$title_text;?>" data-summ="<?=$arResult['ALL_SUM'];?>"><span class="count<?=(intval($arResult['ITEMS_COUNT']) <= 0 ? ' empted' : '')?>"><?=($bItems ? $arResult['ITEMS_COUNT'] : '0')?></span></a>
	</div>
</div>

<?$frame->end();?>