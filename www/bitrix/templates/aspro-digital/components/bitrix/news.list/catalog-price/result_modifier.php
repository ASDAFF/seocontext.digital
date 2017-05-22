<?
// get section names elements
foreach($arResult['ITEMS'] as $arItem){
	$arSectionsIDs[] = $arItem['IBLOCK_SECTION_ID'];
}
if($arSectionsIDs){
	$arSectionsIDs = array_unique($arSectionsIDs);
	$arSectionsTmp = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array('ID' => $arSectionsIDs), false, array('ID', 'NAME'));
	foreach($arSectionsTmp as $arSection){
		$arResult['SECTIONS'][$arSection['ID']] = $arSection;
	}
}

if(is_array($arResult['ITEMS'])){
	foreach($arResult['ITEMS'] as $i => $arItem){
		$arItem['SECTION_NAME'] = $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME'];
		CDigital::getFieldImageData($arItem, array('PREVIEW_PICTURE'));
		$arResult['ITEMS'][$i] = $arItem;
	}
}
if($arParams['PARENT_SECTION']){
	$arResult['SECTION_CURRENT'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), array('ID' => $arParams['PARENT_SECTION']), false, array('ID', 'NAME'));
}
?>