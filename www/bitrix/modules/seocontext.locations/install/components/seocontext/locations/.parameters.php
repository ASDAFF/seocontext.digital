<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"CACHE_TIME" => array('DEFAULT'=>36000),
		"RELOAD_PAGE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SEOCONTEXT_RELOAD_PAGE"),
			"TYPE" => "CHECKBOX",
		)
	),
);
?>