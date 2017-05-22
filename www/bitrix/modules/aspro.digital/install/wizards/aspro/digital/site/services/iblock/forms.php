<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock")) return;

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

$arFormsShortCODE = array("example", "order_project", "order_product", "order_services", "resume", "question", "callback", "order_page", "consultation", "director");
foreach($arFormsShortCODE as $iblockShortCODE){
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/".$iblockShortCODE.".xml";
	$iblockTYPE = "aspro_digital_form";
	$iblockXMLID = "aspro_digital_".$iblockShortCODE."_".WIZARD_SITE_ID;
	$iblockCODE = "aspro_digital_".$iblockShortCODE;
	$iblockID = false;

	$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockXMLID, "TYPE" => $iblockTYPE));
	if ($arIBlock = $rsIBlock->Fetch()) {
		$iblockID = $arIBlock["ID"];
		if (WIZARD_INSTALL_DEMO_DATA) {
			// delete if already exist & need install demo
			CIBlock::Delete($arIBlock["ID"]);
			$iblockID = false;
		}
	}

	if(WIZARD_INSTALL_DEMO_DATA){
		if(!$iblockID){
			// add new iblock
			$permissions = array("1" => "X", "2" => "R");

			if($iblockShortCODE == "director")
			{
				$permissions = array("1" => "X", "2" => "D");
				$dbGroupVote = CGroup::GetList($by = "", $order = "", array("STRING_ID" => "RATING_VOTE_AUTHORITY|RATING_VOTE"));
				while($arGroupVote = $dbGroupVote->Fetch()){
					$permissions[$arGroupVote["ID"]] = "R";
				};

			}
			$dbGroup = CGroup::GetList($by = "", $order = "", array("STRING_ID" => "content_editor"));
			if($arGroup = $dbGroup->Fetch()){
				$permissions[$arGroup["ID"]] = "W";
			};
			
			// replace macros IN_XML_SITE_ID & IN_XML_SITE_DIR in xml file - for correct url links to site
			if(file_exists($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back")){
				@copy($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back", $_SERVER["DOCUMENT_ROOT"].$iblockXMLFile);
			}
			@copy($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, $_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back");
			CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, Array("IN_XML_SITE_DIR" => WIZARD_SITE_DIR));
			CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, Array("IN_XML_SITE_ID" => WIZARD_SITE_ID));
			$iblockID = WizardServices::ImportIBlockFromXML($iblockXMLFile, $iblockCODE, $iblockTYPE, WIZARD_SITE_ID, $permissions);
			if(file_exists($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back")){
				@copy($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back", $_SERVER["DOCUMENT_ROOT"].$iblockXMLFile);
			}
			if ($iblockID < 1)	return;
				
			// iblock fields
			$iblock = new CIBlock;
			$arFields = array(
				"ACTIVE" => "Y",
				"CODE" => $iblockCODE,
				"XML_ID" => $iblockXMLID,
				"FIELDS" => array(
					"IBLOCK_SECTION" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					),
					"ACTIVE" => array(
						"IS_REQUIRED" => "Y",
						"DEFAULT_VALUE"=> "Y",
					),
					"ACTIVE_FROM" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					),
					"ACTIVE_TO" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					),
					"SORT" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "0",
					), 
					"NAME" => array(
						"IS_REQUIRED" => "Y",
						"DEFAULT_VALUE" => "",
					), 
					"PREVIEW_PICTURE" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => array(
							"FROM_DETAIL" => "N",
							"SCALE" => "N",
							"WIDTH" => "",
							"HEIGHT" => "",
							"IGNORE_ERRORS" => "N",
							"METHOD" => "resample",
							"COMPRESSION" => 75,
							"DELETE_WITH_DETAIL" => "N",
							"UPDATE_WITH_DETAIL" => "N",
						),
					), 
					"PREVIEW_TEXT_TYPE" => array(
						"IS_REQUIRED" => "Y",
						"DEFAULT_VALUE" => "text",
					), 
					"PREVIEW_TEXT" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					), 
					"DETAIL_PICTURE" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => array(
							"SCALE" => "N",
							"WIDTH" => "",
							"HEIGHT" => "",
							"IGNORE_ERRORS" => "N",
							"METHOD" => "resample",
							"COMPRESSION" => 75,
						),
					), 
					"DETAIL_TEXT_TYPE" => array(
						"IS_REQUIRED" => "Y",
						"DEFAULT_VALUE" => "text",
					), 
					"DETAIL_TEXT" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					), 
					"XML_ID" =>  array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					), 
					"CODE" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => array(
							"UNIQUE" => "N",
							"TRANSLITERATION" => "N",
							"TRANS_LEN" => 100,
							"TRANS_CASE" => "L",
							"TRANS_SPACE" => "-",
							"TRANS_OTHER" => "-",
							"TRANS_EAT" => "Y",
							"USE_GOOGLE" => "N",
						),
					),
					"TAGS" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					), 
					"SECTION_NAME" => array(
						"IS_REQUIRED" => "Y",
						"DEFAULT_VALUE" => "",
					), 
					"SECTION_PICTURE" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => array(
							"FROM_DETAIL" => "N",
							"SCALE" => "N",
							"WIDTH" => "",
							"HEIGHT" => "",
							"IGNORE_ERRORS" => "N",
							"METHOD" => "resample",
							"COMPRESSION" => 75,
							"DELETE_WITH_DETAIL" => "N",
							"UPDATE_WITH_DETAIL" => "N",
						),
					), 
					"SECTION_DESCRIPTION_TYPE" => array(
						"IS_REQUIRED" => "Y",
						"DEFAULT_VALUE" => "text",
					), 
					"SECTION_DESCRIPTION" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					), 
					"SECTION_DETAIL_PICTURE" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => array(
							"SCALE" => "N",
							"WIDTH" => "",
							"HEIGHT" => "",
							"IGNORE_ERRORS" => "N",
							"METHOD" => "resample",
							"COMPRESSION" => 75,
						),
					), 
					"SECTION_XML_ID" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => "",
					), 
					"SECTION_CODE" => array(
						"IS_REQUIRED" => "N",
						"DEFAULT_VALUE" => array(
							"UNIQUE" => "N",
							"TRANSLITERATION" => "N",
							"TRANS_LEN" => 100,
							"TRANS_CASE" => "L",
							"TRANS_SPACE" => "-",
							"TRANS_OTHER" => "-",
							"TRANS_EAT" => "Y",
							"USE_GOOGLE" => "N",
						),
					), 
				),
			);
			
			$iblock->Update($iblockID, $arFields);
		}
		else{
			// attach iblock to site
			$arSites = array(); 
			$db_res = CIBlock::GetSite($iblockID);
			while ($res = $db_res->Fetch())
				$arSites[] = $res["LID"]; 
			if (!in_array(WIZARD_SITE_ID, $arSites)){
				$arSites[] = WIZARD_SITE_ID;
				$iblock = new CIBlock;
				$iblock->Update($iblockID, array("LID" => $arSites));
			}
		}

		if($iblockShortCODE == "question")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["NEED_PRODUCT"].'--#--'.GetMessage("WZD_OPTION_252").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["NEED_PRODUCT"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "order_product")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["PRODUCT"].'--#--'.GetMessage("WZD_OPTION_252").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["PRODUCT"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "order_project")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["PROJECT"].'--#--'.GetMessage("WZD_OPTION_120").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["PROJECT"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "order_services")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["SERVICE"].'--#--'.GetMessage("WZD_OPTION_134").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["SERVICE"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "director")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["TITLE"].'--#--'.GetMessage("WZD_OPTION_254").'--,--PROPERTY_'.$arProperty["USER"].'--#--'.GetMessage("WZD_OPTION_255").'--,--PROPERTY_'.$arProperty["SERVICE"].'--#--'.GetMessage("WZD_OPTION_134").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["TITLE"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "consultation")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "callback")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "resume")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["FIO"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["POST"].'--#--'.GetMessage("WZD_OPTION_132").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--,--PROPERTY_'.$arProperty["FILE"].'--#--'.GetMessage("WZD_OPTION_256").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["FIO"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["POST"].',PROPERTY_'.$arProperty["MESSAGE"].',PROPERTY_'.$arProperty["FILE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
		elseif($iblockShortCODE == "resume")
		{
			// edit form user oprions
			CUserOptions::SetOption("form", "form_element_".$iblockID, array(
				"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_106").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["NAME"].'--#--'.GetMessage("WZD_OPTION_251").'--,--PROPERTY_'.$arProperty["PHONE"].'--#--'.GetMessage("WZD_OPTION_118").'--,--PROPERTY_'.$arProperty["COMPANY"].'--#--'.GetMessage("WZD_OPTION_210").'--,--PROPERTY_'.$arProperty["EMAIL"].'--#--'.GetMessage("WZD_OPTION_142").'--,--PROPERTY_'.$arProperty["SESSION_ID"].'--#--'.GetMessage("WZD_OPTION_257").'--,--PROPERTY_'.$arProperty["ADDRESS"].'--#--'.GetMessage("WZD_OPTION_258").'--,--PROPERTY_'.$arProperty["MESSAGE"].'--#--'.GetMessage("WZD_OPTION_253").'--,--PROPERTY_'.$arProperty["ORDER_LIST"].'--#--'.GetMessage("WZD_OPTION_259").'--;--;--',
			));

			// list user options
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
				"columns" => 'PROPERTY_'.$arProperty["NAME"].',PROPERTY_'.$arProperty["PHONE"].',PROPERTY_'.$arProperty["COMPANY"].',PROPERTY_'.$arProperty["EMAIL"].',PROPERTY_'.$arProperty["MESSAGE"].',TIMESTAMP_X,ID', "by" => "date_active_from", "order" => "desc", "page_size" => "20", 
			));
		}
	}

	if($iblockID){
		// replace macros IBLOCK_TYPE & IBLOCK_ID & IBLOCK_CODE
		CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_FORM_".strtoupper($iblockShortCODE)."_TYPE" => $iblockTYPE));
		CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_FORM_".strtoupper($iblockShortCODE)."_ID" => $iblockID));
		CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_FORM_".strtoupper($iblockShortCODE)."_CODE" => $iblockCODE));
		CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_FORM_".strtoupper($iblockShortCODE)."_TYPE" => $iblockTYPE));
		CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_FORM_".strtoupper($iblockShortCODE)."_ID" => $iblockID));
		CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_FORM_".strtoupper($iblockShortCODE)."_CODE" => $iblockCODE));
	}
}
?>
