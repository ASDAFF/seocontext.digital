<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?\Bitrix\Main\Loader::includeModule('iblock');
$arItems = $arSections = $arTabs = $arGoodsSectionsID = $arSectionsTmp = array();

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arrFilter = array();
else
{
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
}

$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"], "!PROPERTY_SHOW_ON_INDEX_PAGE" => false);
if($arParams["SECTION_ID"])
	$arFilter[]=array("SECTION_ID"=>$arParams["SECTION_ID"],"INCLUDE_SUBSECTIONS"=>"Y" );
elseif($arParams["SECTION_CODE"])
	$arFilter[]=array("SECTION_CODE"=>$arParams["SECTION_CODE"],"INCLUDE_SUBSECTIONS"=>"Y" );
	
global $arTheme;
$bOrderViewBasket = (trim($arTheme["ORDER_VIEW"]["VALUE"]) === "Y");
if(isset($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$arTheme["INDEX_TYPE"]["VALUE"]]))
	$bCatalogIndex = $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$arTheme["INDEX_TYPE"]["VALUE"]]["CATALOG_INDEX"]["VALUE"] == 'Y';
else
	$bCatalogIndex = true;
$arParams["ORDER_VIEW"] = $bOrderViewBasket;

foreach($arParams as $key => $value)
{
	if(strpos($key, "~"))
		unset($arParams[$key]);
}
if($bCatalogIndex)
{
	// get all items
	$arItems = CCache::CIBLockElement_GetList(array('CACHE' => array("TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array_merge($arFilter, $arrFilter), false, false, array("ID", "IBLOCK_SECTION_ID", "PROPERTY_".$arParams["HIT_PROP"]));

	if($arItems)
	{
		$bShowHit = false;
		foreach($arItems as $arItem)
		{
			if(isset($arItem["PROPERTY_".$arParams["HIT_PROP"]."_VALUE"]) && $arItem["PROPERTY_".$arParams["HIT_PROP"]."_VALUE"])
				$bShowHit = true;
			if($arItem["IBLOCK_SECTION_ID"])
				$arGoodsSectionsID[$arItem["IBLOCK_SECTION_ID"]] = $arItem["IBLOCK_SECTION_ID"];
		}
		
		if($arParams["HIT_PROP"] && $bShowHit)
		{
			$arPropHit = CIBlockProperty::GetList(Array("sort"=>"asc", "id"=>"desc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>$arParams["HIT_PROP"]))->Fetch();
			$arTabs[] = array(
				"NAME" => $arPropHit["NAME"],
				"FILTER" => array("!PROPERTY_".$arPropHit["CODE"] => false)
			);
		}
		
		if($arGoodsSectionsID)
		{
			$arSectionsFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
			
			$arSectionsTmp = CCache::CIBLockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array_merge($arSectionsFilter, $arGoodsSectionsID), false, array("ID", "LEFT_MARGIN", "RIGHT_MARGIN"));
			if($arSectionsTmp)
			{
				foreach($arSectionsTmp as $arSection)
				{
					$arSections[] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), array_merge($arSectionsFilter, array("<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1)), false, array('ID', 'NAME'));
				}
				if($arSections)
				{
					foreach($arSections as $arSectionTmp)
					{
						$arTabs[$arSectionTmp["ID"]] = $arSectionTmp;
						$arTabs[$arSectionTmp["ID"]]["FILTER"] = array("SECTION_ID" => $arSectionTmp["ID"], "INCLUDE_SUBSECTIONS" => "Y");
					}
				}
			}
			else
			{
				$arTabs[] = array(
					"NAME" => GetMessage("ALL_ITEMS")
				);
			}
		}
		else
		{
			$arTabs[] = array(
				"NAME" => GetMessage("ALL_ITEMS")
			);
		}
	}
	else
		return;
	$arResult["TABS"] = $arTabs;
	$this->IncludeComponentTemplate();
}
else
	return;
?>