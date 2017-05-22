<?if(!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die()?>
<?
if($arResult['ITEMS'])
{
	$arResult['HAS_WIDE_BLOCK'] = false;
	$arWideItem = array();
	$qntyItems = count($arResult['ITEMS']);

	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		if($arItem['PROPERTIES']['BIG_BLOCK']['VALUE'] == 'Y' && $qntyItems >= 5 && (!isset($_GET["AJAX_REQUEST"]) && $_GET["AJAX_REQUEST"] != "Y"))
		{
			$arResult['HAS_WIDE_BLOCK'] = true;
			$arWideItem = $arItem;
			unset($arResult['ITEMS'][$key]);
		}
	}
	if($arResult['HAS_WIDE_BLOCK'])
	{
		$arResult['ITEMS'] = array_merge(
            array_slice($arResult['ITEMS'], 0, 2),
            array($arWideItem),
            array_slice($arResult['ITEMS'], 2)
        );
	}
}
?>