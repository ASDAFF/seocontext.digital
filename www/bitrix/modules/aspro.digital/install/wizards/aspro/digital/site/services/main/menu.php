<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!defined("WIZARD_SITE_ID"))
	return;

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

\Bitrix\Main\Loader::includeModule('fileman');
$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);

if(!isset($arMenuTypes['top']))
	$arMenuTypes['top'] = Loc::getMessage('WIZ_MENU_TOP_DEFAULT');
if(!isset($arMenuTypes['left']))
	$arMenuTypes['left'] = Loc::getMessage('WIZ_MENU_LIGHT_TOP');
if(!isset($arMenuTypes['cabinet']))
	$arMenuTypes['cabinet'] = Loc::getMessage('WIZ_MENU_CABINET');
if(!isset($arMenuTypes['bottom1']))
	$arMenuTypes['bottom1'] = Loc::getMessage('WIZ_MENU_BOTTOM1');
if(!isset($arMenuTypes['bottom2']))
	$arMenuTypes['bottom2'] = Loc::getMessage('WIZ_MENU_BOTTOM2');
if(!isset($arMenuTypes['bottom3']))
	$arMenuTypes['bottom3'] = Loc::getMessage('WIZ_MENU_BOTTOM3');
if(!isset($arMenuTypes['bottom4']))
	$arMenuTypes['bottom4'] = Loc::getMessage('WIZ_MENU_BOTTOM4');

SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
\Bitrix\Main\Config\Option::set("fileman", "num_menu_param", 2, WIZARD_SITE_ID);
?>