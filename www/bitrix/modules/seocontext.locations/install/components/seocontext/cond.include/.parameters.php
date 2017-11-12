<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use Seocontext\Locations\Manager;
use Bitrix\Main\Config\Option;

if (!Loader::includeModule('seocontext.locations')) {
	//ShowError(Loc::getMessage('ACADEMY_D7_MODULE_NOT_INSTALLED'));
	throw new Main\LoaderException(Loc::getMessage('SEOCONTEXT_MODULE_NOT_INSTALLED'));
}

// Loading selected locations
$locations = Manager::getSelectedLocations();
$selectedLocations = array();
foreach($locations as $loc)
{
	$selectedLocations[$loc['CODE']]=$loc['NAME'];
}

// Get content for chosen location
//AddMessage2Log($arCurrentValues);
//AddMessage2Log($_REQUEST);


$includeIds = \Seocontext\Locations\Content::getAllIncludeIds();
// make associative array with same keys and values
$includeIds = array_combine($includeIds, $includeIds);


$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"CACHE_TIME" => array('DEFAULT'=>36000),

		"INCLUDE_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SEOCONTEXT_INCLUDE_ID"),
			"TYPE" => "LIST",
			"VALUES" => $includeIds,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "includeArea"
		),

		"ALL_ON_PAGE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SEOCONTEXT_ALL_ON_PAGE"),
			"TYPE" => "CHECKBOX",
		),

		"CONTENT" => array(
			'PARENT' => 'BASE',
			"NAME" => GetMessage("SEOCONTEXT_CONTENT"),
			"TYPE" => "CUSTOM",
			// todo: autodetect settings.js path
			'JS_FILE' => '/bitrix/components/seocontext/cond.include/settings/settings.js',
			'JS_EVENT' => 'onCondIncludeSettingsCreate'
		)

	),
);
?>