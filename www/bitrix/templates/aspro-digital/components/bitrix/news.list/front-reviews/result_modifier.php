<?
foreach($arResult['ITEMS'] as $key => $arItem){
	CDigital::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
}
?>