<?
if($arResult['ITEMS'])
{
	foreach($arResult['ITEMS'] as $i => $arItem){
		CDigital::getFieldImageData($arResult['ITEMS'][$i], array('PREVIEW_PICTURE'));
	}
}
?>