<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
CModule::IncludeModule('iblock');

/* show sort property */
$arPropertySort = $arPropertySortDefault = $arPropertyDefaultSort = array();
$arPropertySortDefault = array('name', 'sort');
$arPropertySort = array('name' => GetMessage('V_NAME'), 'sort' => GetMessage('V_SORT'));
$rsProp = CIBlockProperty::GetList(array('sort' => 'asc', 'name' => 'asc'), Array('ACTIVE' => 'Y', 'IBLOCK_ID' => (isset($arCurrentValues['IBLOCK_ID']) ? $arCurrentValues['IBLOCK_ID'] : $arCurrentValues['ID'])));
while($arr = $rsProp->Fetch()){
	$arPropertySort[$arr['CODE']] = $arr['NAME'];
	$strPropName = '['.$arr['ID'].']'.('' != $arr['CODE'] ? '['.$arr['CODE'].']' : '').' '.$arr['NAME'];
	if ('S' == $arr['PROPERTY_TYPE'] && 'directory' == $arr['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arr))
		$arHighloadPropList[$arr['CODE']] = $strPropName;
}

if($arCurrentValues['SORT_PROP']){
	foreach($arCurrentValues['SORT_PROP'] as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}
else{
	foreach($arPropertySortDefault as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}

/* show sort direction */
$arSortDirection = array('asc' => GetMessage('SD_ASC'), 'desc' => GetMessage('SD_DESC'));
$arGalleryType = array('big' => GetMessage('GALLERY_BIG'), 'small' => GetMessage('GALLERY_SMALL'));

/* get sections template */
$arSectionsViews = $arSectionViews = $arSectionElementsViews = $arElemetViews = array();
foreach(glob(__DIR__.'/page_blocks/*.php', 0) as $dir){
	$file = str_replace('.php', '', basename($dir));
	if(strpos($dir, 'sections_'))
		$arSectionsViews[$file] = str_replace('sections_', '', $file);
	if(strpos($dir, 'section_'))
		$arSectionViews[$file] = str_replace('section_', '', $file);
	if(strpos($dir, 'list_elements_'))
		$arSectionElementsViews[$file] = str_replace('list_elements_', '', $file);
	if(strpos($dir, 'element_'))
		$arElemetViews[$file] = str_replace('element_', '', $file);;
}

$arTemplateParameters = array(
	'SECTIONS_TYPE_VIEW' => array(
		'PARENT' => 'BASE',
		'SORT' => 1,
		'NAME' => GetMessage('T_SECTIONS_TYPE_VIEW'),
		'TYPE' => 'LIST',
		'VALUES' => $arSectionsViews,
	),
	'SECTION_TYPE_VIEW' => array(
		'PARENT' => 'BASE',
		'SORT' => 1,
		'NAME' => GetMessage('T_SECTION_TYPE_VIEW'),
		'TYPE' => 'LIST',
		'VALUES' => $arSectionViews,
	),
	/*'SECTION_ELEMENTS_TYPE_VIEW' => array(
		'PARENT' => 'BASE',
		'SORT' => 1,
		'NAME' => GetMessage('T_SECTION_ELEMENTS_TYPE_VIEW'),
		'TYPE' => 'LIST',
		'VALUES' => $arSectionElementsViews,
	),*/
	/*'ELEMENT_TYPE_VIEW' => array(
		'PARENT' => 'BASE',
		'SORT' => 1,
		'NAME' => GetMessage('T_ELEMENT_TYPE_VIEW'),
		'TYPE' => 'LIST',
		'VALUES' => $arElemetViews,
	),*/
	'SHOW_DETAIL_LINK' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SORT_PROP' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_SORT_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $arPropertySort,
		'SIZE' => 3,
		'MULTIPLE' => 'Y',
		'REFRESH' => 'Y'
	),
	'IMAGE_CATALOG_POSITION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 250,
		'NAME' => GetMessage('IMAGE_CATALOG_POSITION'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'left' => GetMessage('IMAGE_POSITION_LEFT'),
			'right' => GetMessage('IMAGE_POSITION_RIGHT'),
		),
		'DEFAULT' => 'left',
	),
	'VIEW_TYPE' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 250,
		'NAME' => GetMessage('VIEW_TYPE_TITLE'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'table' => GetMessage('VIEW_TYPE_TABLE'),
			'list' => GetMessage('VIEW_TYPE_LIST'),
			'price' => GetMessage('VIEW_TYPE_PRICE'),
		),
		'DEFAULT' => 'left',
	),
	'SHOW_CHILD_SECTIONS' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('SHOW_CHILD_SECTIONS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'LINE_ELEMENT_COUNT' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_LINE_ELEMENT_COUNT'),
		'TYPE' => 'STRING',
		'DEFAULT' => 3,
	),
	'SHOW_BRAND_DETAIL' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('T_SHOW_BRAND_DETAIL'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SORT_PROP_DEFAULT' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_SORT_PROP_DEFAULT'),
		'TYPE' => 'LIST',
		'VALUES' => $arPropertyDefaultSort,
	),
	'SORT_DIRECTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_SORT_DIRECTION'),
		'TYPE' => 'LIST',
		'VALUES' => $arSortDirection
	),
	'USE_SHARE' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'GALLERY_TYPE' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('GALLERY_TYPE'),
		'TYPE' => 'LIST',
		'VALUES' => $arGalleryType
	),
	'S_ASK_QUESTION' => array(
		'SORT' => 700,
		'NAME' => GetMessage('S_ASK_QUESTION'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'S_ORDER_SERVISE' => array(
		'SORT' => 701,
		'NAME' => GetMessage('S_ORDER_SERVISE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'FORM_ID_ORDER_SERVISE' => array(
		'SORT' => 701,
		'NAME' => GetMessage('T_FORM_ID_ORDER_SERVISE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_GALLERY' => array(
		'SORT' => 702,
		'NAME' => GetMessage('T_GALLERY'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DOCS' => array(
		'SORT' => 703,
		'NAME' => GetMessage('T_DOCS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_PROJECTS' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_PROJECTS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_CHARACTERISTICS' => array(
		'SORT' => 705,
		'NAME' => GetMessage('T_CHARACTERISTICS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_FAQ' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_FAQ'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_TARIF' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_TARIF'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DESC' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_DESC'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DEV' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_DEV'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_SERVICES' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_SERVICES'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_ITEMS' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_ITEMS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
);

if($arCurrentValues['SEF_MODE'] == 'Y'){
	$arTemplateParameters['FILTER_URL_TEMPLATE'] = array(
		'PARENT' => 'SEF_MODE',
		'SORT' => 500,
		'NAME' => GetMessage('FILTER_URL_TEMPLATE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/',
	);
}

if(\Bitrix\Main\ModuleManager::isModuleInstalled("highloadblock"))
{
	$arTemplateParameters['DETAIL_BRAND_USE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_BRAND_USE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['DETAIL_BRAND_USE']) && 'Y' == $arCurrentValues['DETAIL_BRAND_USE'])
	{
		$arTemplateParameters['DETAIL_BRAND_PROP_CODE'] = array(
			'PARENT' => 'VISUAL',
			"NAME" => GetMessage("CP_BC_TPL_DETAIL_PROP_CODE"),
			"TYPE" => "LIST",
			"VALUES" => $arHighloadPropList,
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y"
		);
	}
}
?>