<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SEOCONTEXT_COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage("SEOCONTEXT_COMPONENT_DESCRIPTION"),
	"ICON" => "/images/icon.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "seocontext",
		"NAME" => GetMessage("SEOCONTEXT_GROUP_NAME"),
		"CHILD" => array(
			"ID" => "seocontext_locations",
			"NAME" => GetMessage("SEOCONTEXT_SECTION_NAME"),  
		),
	),
	"COMPLEX" => "N",
);

?>