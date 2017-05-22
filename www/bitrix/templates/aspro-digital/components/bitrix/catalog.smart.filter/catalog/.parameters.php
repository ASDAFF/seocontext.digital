<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Loader::includeModule('iblock');
$arProperty_LNS = array();
$arProperty_LNS[]="-";
$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>(isset($arCurrentValues["IBLOCK_ID"])?$arCurrentValues["IBLOCK_ID"]:$arCurrentValues["ID"])));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S")))
	{
		$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arTemplateParameters = array(
	'PROP_1' => array(
		'NAME' => GetMessage('PROP_1'),
		'TYPE' => 'LIST',
		'VALUES' => $arProperty_LNS,
		'DEFAULT' => 'SIZE',
	),
	'PROP_2' => array(
		'NAME' => GetMessage('PROP_2'),
		'TYPE' => 'LIST',
		'VALUES' => $arProperty_LNS,
		'DEFAULT' => 'FILTER_PRICE',
	),
	'PROP_3' => array(
		'NAME' => GetMessage('PROP_3'),
		'TYPE' => 'LIST',
		'VALUES' => $arProperty_LNS,
		'DEFAULT' => 'TYPE_BUILDINGS',
	),
	'FORM_URL' => array(
		'NAME' => GetMessage('FORM_URL'),
		'TYPE' => 'STRING',
		'DEFAULT' => SITE_DIR."filter/",
	),
);
?>