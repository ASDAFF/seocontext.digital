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

$arGalleryType = array('big' => GetMessage('GALLERY_BIG'), 'small' => GetMessage('GALLERY_SMALL'));

/* get sections template */
$arSectionElementsViews = $arElemetViews = array();

if(\Bitrix\Main\Loader::includeModule('aspro.digital'))
{
	$arTheme = CDigital::GetFrontParametrsValues(SITE_ID);
	$arSectionElementsViews['FROM_MODULE'] = GetMessage('FROM_MODULE_PARAMS');

	if(isset($arTheme['PROJECTS_PAGE_DETAIL']) && $arTheme['PROJECTS_PAGE_DETAIL'])
		$arElemetViews['FROM_MODULE'] = GetMessage('FROM_MODULE_PARAMS');
}

foreach(glob(__DIR__.'/page_blocks/*.php', 0) as $dir){
	$file = str_replace('.php', '', basename($dir));
	if(strpos($dir, 'list_elements_'))
		$arSectionElementsViews[$file] = $file;
	if(strpos($dir, 'element_'))
		$arElemetViews[$file] = $file;
}

$arTemplateParameters = array(
	'SECTION_ELEMENTS_TYPE_VIEW' => array(
		'PARENT' => 'BASE',
		'SORT' => 1,
		'NAME' => GetMessage('T_SECTION_ELEMENTS_TYPE_VIEW'),
		'TYPE' => 'LIST',
		'VALUES' => $arSectionElementsViews,
	),
	'SHOW_DETAIL_LINK' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'IMAGE_POSITION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 250,
		'NAME' => GetMessage('IMAGE_POSITION'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'left' => GetMessage('IMAGE_POSITION_LEFT'),
			'right' => GetMessage('IMAGE_POSITION_RIGHT'),
		),
		'DEFAULT' => 'left',
	),
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_SECTION_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_SHOW_SECTION_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'LINE_ELEMENT_COUNT' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_LINE_ELEMENT_COUNT'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'2' => 2,
			'3' => 3,
		),
	),
	'LINE_ELEMENT_COUNT_LIST' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_LINE_ELEMENT_COUNT_LIST'),
		'TYPE' => 'STRING',
		'DEFAULT' => 3,
	),
	'SHOW_NEXT_ELEMENT' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('T_SHOW_NEXT_ELEMENT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'USE_SHARE' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
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
	'T_GOODS' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_GOODS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_SERVICES' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_SERVICES'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_CLIENTS' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_CLIENTS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_PROJECTS' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_PROJECTS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_NEXT_LINK' => array(
		'SORT' => 707,
		'NAME' => GetMessage('T_NEXT_LINK'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_PREV_LINK' => array(
		'SORT' => 707,
		'NAME' => GetMessage('T_PREV_LINK'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	)
);

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

if(count($arElemetViews) > 1)
{
	$arTemplateParameters['ELEMENT_TYPE_VIEW'] = array(
		'PARENT' => 'BASE',
		'SORT' => 1,
		'NAME' => GetMessage('T_ELEMENT_TYPE_VIEW'),
		'TYPE' => 'LIST',
		'VALUES' => $arElemetViews,
	);
}
?>