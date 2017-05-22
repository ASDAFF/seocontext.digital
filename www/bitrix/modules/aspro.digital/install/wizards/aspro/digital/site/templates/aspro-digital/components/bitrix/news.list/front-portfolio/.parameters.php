<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"TITLE_BLOCK" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_TITLE"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("TITLE_BLOCK_VALUE")
	),
	"LINK_BLOCK_TEXT" => Array(
		"NAME" => GetMessage("LINK_BLOCK_TITLE"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("LINK_BLOCK_VALUE")
	),
	"ALL_URL" => Array(
		"NAME" => GetMessage("ALL_URL_TITLE"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("ALL_URL_VALUE")
	)
);
?>
