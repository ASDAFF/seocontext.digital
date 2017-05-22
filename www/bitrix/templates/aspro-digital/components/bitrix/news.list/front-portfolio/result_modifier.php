<?
if($arResult['ITEMS'])
{
	foreach($arResult['ITEMS'] as $i => $arItem)
	{
		if(!is_array($arItem['FIELDS']['PREVIEW_PICTURE']))
			unset($arResult['ITEMS'][$i]);
	}
}
?>