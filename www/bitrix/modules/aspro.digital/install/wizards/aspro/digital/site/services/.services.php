<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arServices = Array(
	"main" => array(
		"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => array(
			"public.php",
			"template.php",
			"theme.php",
			"menu.php",
			"settings.php",
		),
	),
	"iblock" => Array(
		"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
		"STAGES" => Array(
			"types.php",
			"advtbig.php",
			"banners.php",
			"tizers.php",
			"reviews.php",
			"staff.php",
			"vacancy.php",
			"faq.php",
			"licenses.php",
			"hl_tizers.php",
			"hl_tizers_content.php",
			"hl_company.php",
			"hl_company_content.php",
			"hl_contact.php",
			"hl_contact_content.php",
			"news_personal.php",
			"news.php",
			"projects.php",
			"partners.php",
			"forms.php",
			"services.php",
			"articles.php",
			"tarifs.php",
			"catalog.php",
			"static.php",
			"contact.php",			
			"links.php",			
		),
	),
	"search" => array(
		"NAME" => GetMessage("SERVICE_SEARCH"),
		"STAGES" => array(
			"search.php",
		),
	),
);
?>