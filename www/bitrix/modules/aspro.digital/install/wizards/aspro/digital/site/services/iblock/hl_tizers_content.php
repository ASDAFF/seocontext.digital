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

$HL_ID = $_SESSION["DIGITAL_HBLOCK_TIZERS_ID"];
unset($_SESSION["DIGITAL_HBLOCK_TIZERS_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if($HL_ID){
	$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	$entity_data_class = $hlentity->getDataClass();
	$arProfits = array(
		"READY_SOLUTION" => array(
			"IMAGE" => "references_files/iblock/d73/d73ed7f45eacd1211d8969706056b89b.png",
			"LINK" => WIZARD_SITE_DIR."product/",
			"XML_ID" => "CX7bBvOx",			
		),
		"OPTIMIZE_PRODUCT" => array(
			"IMAGE" => "references_files/iblock/02e/02e81f69b65684817ffc30532969a5b2.png",
			"XML_ID" => "8K7Fq1gE",
		),
		"DOCS" => array(
			"IMAGE" => "references_files/iblock/364/364c62a95bffcbed49d0f9310e0ef595.png",
			"XML_ID" => "CHKOZrAD",
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
			'UF_XML_ID' => ($arFile["XML_ID"] ? $arFile["XML_ID"] : ToLower($profitName))
		);
		if($arFile["DESC"]){
			$arData["UF_DESCRIPTION"]=$arFile["DESC"];
		}
		if($arFile["LINK"]){
			$arData["UF_LINK"]=$arFile["LINK"];
		}
		$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$HL_ID, $arData);
		$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$HL_ID, null, $arData);
		$result = $entity_data_class::add($arData);
		$sort += 100;
	}
}
?>