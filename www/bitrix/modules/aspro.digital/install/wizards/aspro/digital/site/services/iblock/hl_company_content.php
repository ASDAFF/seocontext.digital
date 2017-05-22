<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)	die();

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";	

set_time_limit(0);

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$HL_ID = $_SESSION["DIGITAL_HBLOCK_COMPANY_ID"];
unset($_SESSION["DIGITAL_HBLOCK_COMPANY_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if($HL_ID){
	$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	$entity_data_class = $hlentity->getDataClass();
	$arProfits = array(
		"SITES" => array(
			"IMAGE" => "references_files/iblock/95e/95e93e08947090d78e49db942816ca5f.png",
			"CLASS" => "spincrement",
			"DESC" => "200",
			"FULL_DESC" => "+",
		),
		"TEAMS" => array(
			"IMAGE" => "references_files/iblock/d69/d69ceaf03c384c9bc6032c050c9c2c76.png",
			"CLASS" => "spincrement",
			"DESC" => "20",
		),
		"CLIENTS" => array(
			"IMAGE" => "references_files/iblock/e8c/e8c15a947af575ad854ef1787102bc13.png",
			"CLASS" => "spincrement",
			"DESC" => "25",
		),
		"COMP" => array(
			"IMAGE" => "references_files/iblock/94c/94c4652b64f3571063b058a47497e223.png",
			"DESC" => "+Msk",
		),
	);
	$sort = 100;
	foreach($arProfits as $profitName => $arFile){
		$arData = array(
			'UF_NAME' => GetMessage("WZD_REF_PROFIT_".$profitName),
			'UF_FILE' =>
				array (
					'name' => ToLower($profitName).".png",
					'type' => 'image/png',
					'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$arFile["IMAGE"]
				),
			'UF_SORT' => $sort,
			// 'UF_DEF' => ($sort > 100) ? "0" : "1",
			'UF_XML_ID' => ToLower($profitName)
		);
		if($arFile["DESC"])
			$arData["UF_DESCRIPTION"]=$arFile["DESC"];

		if($arFile["FULL_DESC"])
			$arData["UF_FULL_DESCRIPTION"]=$arFile["FULL_DESC"];
		
		if($arFile["LINK"])
			$arData["UF_LINK"]=$arFile["LINK"];
		
		if($arFile["CLASS"])
			$arData["UF_CLASS"]=$arFile["CLASS"];
		
		$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$HL_ID, $arData);
		$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$HL_ID, null, $arData);
		$result = $entity_data_class::add($arData);
		$sort += 100;
	}
}
?>