<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock")) return;
if(!CModule::IncludeModule("aspro.digital")) return;
	
if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

// iblocks ids
$servicesIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_services"][0];
$studyIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_catalog"]["aspro_digital_study"][0];
$staffIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_staff"][0];
$reviewsIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_reviews"][0];
$projectsIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_projects"][0];
$catalogIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_catalog"]["aspro_digital_catalog"][0];
$partnerIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_partners"][0];
$newsIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_news"][0];
$articlesIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_articles"][0];
$companyIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_digital_content"]["aspro_digital_static"][0];


// XML_ID => ID (here XML_ID - old ID, ID - new ID)
$arStatics = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($companyIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $companyIBlockID), false, false, array("ID", "XML_ID"));

//update company id on front page
if($arStatics && $arStatics["945"])
	CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("COMPANY_ELEMENT_ID" => $arStatics["945"]));

$arServices = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($servicesIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $servicesIBlockID), false, false, array("ID", "XML_ID"));
$arCatalog = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($catalogIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $catalogIBlockID), false, false, array("ID", "XML_ID"));
$arProjects = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($projectsIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $projectsIBlockID), false, false, array("ID", "XML_ID"));
$arReviews = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($reviewsIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $reviewsIBlockID), false, false, array("ID", "XML_ID"));
$arNews = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($newsIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $newsIBlockID), false, false, array("ID", "XML_ID"));

// update links in projects
CIBlockElement::SetPropertyValuesEx($arProjects["152"], $projectsIBlockID, array("LINK_SERVICES" => array($arServices["12"]), "LINK_GOODS" => array($arCatalog["874"], $arCatalog["202"])));
CIBlockElement::SetPropertyValuesEx($arProjects["893"], $projectsIBlockID, array("LINK_PROJECTS" => array($arProjects["215"]), "LINK_SERVICES" => array($arServices["15"], $arServices["16"], $arServices["14"]), "LINK_GOODS" => array($arCatalog["871"], $arCatalog["203"])));
CIBlockElement::SetPropertyValuesEx($arProjects["895"], $projectsIBlockID, array("LINK_PROJECTS" => array($arProjects["901"], $arProjects["894"], $arProjects["892"]), "LINK_SERVICES" => array($arServices["186"], $arServices["16"], $arServices["14"]), "LINK_REVIEWS" => array($arReviews["932"])));
CIBlockElement::SetPropertyValuesEx($arProjects["154"], $projectsIBlockID, array("LINK_PROJECTS" => array($arProjects["901"], $arProjects["895"], $arProjects["892"]), "LINK_SERVICES" => array($arServices["13"]), "LINK_REVIEWS" => array($arReviews["931"]), "LINK_GOODS" => array($arCatalog["871"], $arCatalog["188"])));
CIBlockElement::SetPropertyValuesEx($arProjects["892"], $projectsIBlockID, array("LINK_PROJECTS" => array($arProjects["901"], $arProjects["895"], $arProjects["154"]), "LINK_SERVICES" => array($arServices["15"], $arServices["207"]), "LINK_REVIEWS" => array($arReviews["933"])));
CIBlockElement::SetPropertyValuesEx($arProjects["894"], $projectsIBlockID, array("LINK_PROJECTS" => array($arProjects["895"], $arProjects["152"]), "LINK_SERVICES" => array($arServices["16"], $arServices["186"], $arServices["15"]), "LINK_REVIEWS" => array($arReviews["931"]), "LINK_GOODS" => array($arCatalog["202"], $arCatalog["203"], $arCatalog["874"], $arCatalog["923"])));
CIBlockElement::SetPropertyValuesEx($arProjects["901"], $projectsIBlockID, array("LINK_SERVICES" => array($arServices["14"], $arServices["15"])));
CIBlockElement::SetPropertyValuesEx($arProjects["215"], $projectsIBlockID, array("LINK_SERVICES" => array($arServices["11"], $arServices["206"])));

// update links in services
CIBlockElement::SetPropertyValuesEx($arServices["12"], $servicesIBlockID, array("LINK_GOODS" => array($arCatalog["188"], $arCatalog["202"], $arCatalog["878"])));
CIBlockElement::SetPropertyValuesEx($arServices["13"], $servicesIBlockID, array("LINK_GOODS" => array($arCatalog["203"], $arCatalog["879"], $arCatalog["880"])));
CIBlockElement::SetPropertyValuesEx($arServices["186"], $servicesIBlockID, array("LINK_GOODS" => array($arCatalog["923"], $arCatalog["924"])));

// update links in news
CIBlockElement::SetPropertyValuesEx($arNews["1022"], $newsIBlockID, array("LINK_SERVICES" => array($arServices["206"])));
CIBlockElement::SetPropertyValuesEx($arNews["1023"], $newsIBlockID, array("LINK_SERVICES" => array($arServices["10"], $arServices["14"], $arServices["15"], $arServices["16"]), "LINK_GOODS" => array($arCatalog["923"], $arCatalog["188"], $arCatalog["202"], $arCatalog["874"], $arCatalog["878"])));
CIBlockElement::SetPropertyValuesEx($arNews["991"], $newsIBlockID, array("LINK_GOODS" => array($arCatalog["941"])));
CIBlockElement::SetPropertyValuesEx($arNews["979"], $newsIBlockID, array("LINK_SERVICES" => array($arServices["12"], $arServices["14"]),"LINK_GOODS" => array($arCatalog["202"], $arCatalog["878"])));
CIBlockElement::SetPropertyValuesEx($arNews["977"], $newsIBlockID, array("LINK_SERVICES" => array($arServices["16"], $arServices["186"])));
CIBlockElement::SetPropertyValuesEx($arNews["1024"], $newsIBlockID, array("LINK_GOODS" => array($arCatalog["941"])));


/*print_r($arProjects);
die();*/

// iblock user fields
$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch()) $lang = $arSite["LANGUAGE_ID"];
if(!strlen($lang)) $lang = "ru";
WizardServices::IncludeServiceLang("links", $lang);

// clear and update list of UF_VIEWTYPE in some catalog sections
$arUserFieldViewType = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION", "FIELD_NAME" => "UF_VIEWTYPE"))->Fetch();
$resUserFieldViewTypeEnum = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arUserFieldViewType["ID"]));
while($arUserFieldViewTypeEnum = $resUserFieldViewTypeEnum->GetNext()){
	$obEnum = new CUserFieldEnum;
	$obEnum->SetEnumValues($arUserFieldViewType["ID"], array($arUserFieldViewTypeEnum["ID"] => array("DEL" => "Y")));
}
$obEnum = new CUserFieldEnum;
$obEnum->SetEnumValues($arUserFieldViewType["ID"], array(
	"n0" => array(
		"VALUE" => GetMessage("WZD_UFIELDENUM_TABLE"),
		"XML_ID" => "table",
	),
	"n1" => array(
		"VALUE" => GetMessage("WZD_UFIELDENUM_LIST"),
		"XML_ID" => "list",
	),
	"n2" => array(
		"VALUE" => GetMessage("WZD_UFIELDENUM_PRICE"),
		"XML_ID" => "price",
	),
));
$resUserFieldViewTypeEnum = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arUserFieldViewType["ID"]));
while($arUserFieldViewTypeEnum = $resUserFieldViewTypeEnum->GetNext()){
	$arUserFieldViewTypeEnums[$arUserFieldViewTypeEnum["XML_ID"]] = $arUserFieldViewTypeEnum["ID"];
}

$arCatalog = CCache::CIBlockSection_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($catalogIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $catalogIBlockID), false, array("ID", "XML_ID"));
$bs = new CIBlockSection;
$res = $bs->Update($arCatalog["19"], array("UF_VIEWTYPE" => $arUserFieldViewTypeEnums["list"]));
$res = $bs->Update($arCatalog["31"], array("UF_VIEWTYPE" => $arUserFieldViewTypeEnums["list"]));

// add top seo prop to catalog
$arFields = array(
	"FIELD_NAME" => "UF_TOP_SEO",
	"USER_TYPE_ID" => "string",
	"XML_ID" => "UF_TOP_SEO",
	"SORT" => 100,
	"MULTIPLE" => "N",
	"MANDATORY" => "N",
	"SHOW_FILTER" => "I",
	"SHOW_IN_LIST" => "Y",
	"EDIT_IN_LIST" => "Y",
	"IS_SEARCHABLE" => "N",
	"SETTINGS" => array(
		"SIZE" => 100,
		"ROWS" => 100,
	)
);
$arLangs = array(
	"EDIT_FORM_LABEL"   => array(
        "ru"    => GetMessage("SEO_PREVIEW_TEXT"),
        "en"    => "SEO_PREVIEW_TEXT",
    ),
    "LIST_COLUMN_LABEL" => array(
        "ru"    => GetMessage("SEO_PREVIEW_TEXT"),
        "en"    => "SEO_PREVIEW_TEXT",
    )
);
$arUserFieldTopSeo = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION", "FIELD_NAME" => "UF_TOP_SEO"))->Fetch();
if(!$arUserFieldTopSeo)
{
	$ob = new CUserTypeEntity();
	$FIELD_ID = $ob->Add(array_merge($arFields, array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION"), $arLangs));
}
else
{
	$ob = new CUserTypeEntity();
	$ob->Update($arUserFieldTopSeo["ID"], $arLangs);
}

$arUserFieldTopSeo = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$servicesIBlockID."_SECTION", "FIELD_NAME" => "UF_TOP_SEO"))->Fetch();
if(!$arUserFieldTopSeo)
{
	$ob = new CUserTypeEntity();
	$FIELD_ID = $ob->Add(array_merge($arFields, array("ENTITY_ID" => "IBLOCK_".$servicesIBlockID."_SECTION"), $arLangs));
}
else
{
	$ob = new CUserTypeEntity();
	$ob->Update($arUserFieldTopSeo["ID"], $arLangs);
}

$arServices = CCache::CIBlockSection_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($servicesIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $servicesIBlockID), false, array("ID", "XML_ID"));
$bs = new CIBlockSection;
$res = $bs->Update($arCatalog["4"], array("UF_TOP_SEO" => GetMessage("SECTION_4")));
$res = $bs->Update($arCatalog["6"], array("UF_TOP_SEO" => GetMessage("SECTION_6")));
?>