<?
foreach($arResult['ITEMS'] as $arItem){
	if($SID = $arItem['IBLOCK_SECTION_ID']){
		$arSectionsIDs[] = $SID;
	}
}

if($arSectionsIDs){
	$arResult['SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
}

// group elements by sections
foreach($arResult['ITEMS'] as $arItem){
	$SID = ($arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : 0);

	if($arItem['PROPERTIES'])
	{
		foreach($arItem['PROPERTIES'] as $key2 => $arProp)
		{
			if(($key2 == 'EMAIL' || $key2 == 'PHONE') && $arProp['VALUE'])
				$arItem['MIDDLE_PROPS'][] = $arProp;
			if(strpos($key2, 'SOCIAL') !== false && $arProp['VALUE'])
				$arItem['SOCIAL_PROPS'][] = $arProp;
		}
	}
	
	$arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
}

// unset empty sections
if(is_array($arResult['SECTIONS'])){
	foreach($arResult['SECTIONS'] as $i => $arSection){
		if(!$arSection['ITEMS']){
			unset($arResult['SECTIONS'][$i]);
		}
	}
}
?>